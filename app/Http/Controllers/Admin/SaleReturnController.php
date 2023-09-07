<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SaleReturnExport;
use PDF;
use Exception;
use App\Models\SaleReturn;
use App\Models\SaleProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class SaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = SaleReturn::selectRaw('sum(returned_qty) returned_qty, sum(purchase_price) purchase_price, sum(subtotal) subtotal, max(created_at) created_at, invoice_no, sale_id')->groupBy('invoice_no')->get();

        return view('backend.pages.sale.return_sale', compact('sales'));
    }


    public function salereturnexport(){
        return Excel::download(new SaleReturnExport,'sale_return.xlsx');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //

            $products = $request->products;

            if (!$products)
                throw new Exception("Invalid Request!", 403);

            if (!is_array($products))
                throw new Exception("Invalid Request!", 403);

            if (!count($products))
                throw new Exception("Please Select Product!", 403);


            DB::beginTransaction();

            $returnInvoice = uniqid();

            foreach ($products as $product) {

                if (intval($product['returned_qty']) > 0 && $product['sale_product_id']) {
                    $saleProduct = SaleProduct::find($product['sale_product_id']);
                    if ($saleProduct) {

                        // dd($saleProduct);

                        $sale = $saleProduct->sale;

                        if($saleProduct->product_qty < intval($product['returned_qty']))
                            throw new Exception("Returnable Qty: {$saleProduct->product_qty}", 403);
                            

                        $currentQty = $saleProduct->product_qty - intval($product['returned_qty']);
                        $updatables = [
                            'product_qty' => $currentQty,
                            'subtotal'    => $currentQty * $saleProduct->sales_price,
                            'returned_qty'=> $saleProduct->returned_qty + intval($product['returned_qty']) ?? 0,
                        ];

                        $updatesaleProduct = $saleProduct->update($updatables);
                        if (!$updatesaleProduct)
                            throw new Exception("Unable to Update sale Product!", 403);


                        //product update
                        $saleProduct->sale()->update([
                            'sold_total_qty'    => $sale->sold_total_qty - intval($product['returned_qty']),
                            'sold_total_price'  => $sale->sold_total_price - (intval($product['returned_qty']) * $saleProduct->sales_price),
                            'order_grand_total' => $sale->order_grand_total - (intval($product['returned_qty']) * $saleProduct->sales_price),
                            'number_of_returned'=> $sale->number_of_returned + intval($product['returned_qty']), // summary insert 
                        ]);

                        $singleProduct = $saleProduct->product;

                        if ($singleProduct) {

                            if ($singleProduct->total_stock_out_qty <  intval($product['returned_qty']))
                                throw new Exception("Invalid Qty!", 403);


                            $productUpdate = $saleProduct->product()->update([
                                'total_stock_qty'           => $singleProduct->total_stock_qty + intval($product['returned_qty']),
                                'total_stock_price'         => $singleProduct->total_stock_price + (intval($product['returned_qty']) * $singleProduct->sales_price),
                                'total_stock_out_qty'       => $singleProduct->total_stock_out_qty - intval($product['returned_qty']),
                                'total_stock_out_price'     => $singleProduct->total_stock_out_price - (intval($product['returned_qty']) * $singleProduct->sales_price),
                            ]);

                            if (!$productUpdate)
                                throw new Exception("Unable to Update Stock Qty!", 403);
                        }


                        $saleReturn = SaleReturn::create([
                            'product_id'            => $singleProduct ? $singleProduct->id : null,
                            'sale_id'               => $sale->id ?? null,
                            'sale_product_id'       => $product['sale_product_id'] ?? null,
                            'invoice_no'            => $returnInvoice,
                            'barcode'               => $singleProduct ? $singleProduct->barcode : null,
                            'product_name'          => $saleProduct ? $saleProduct->product_name : null,
                            'product_unit'          => $singleProduct ? $singleProduct->product_unit : null,
                            'product_color'         => $saleProduct ? $saleProduct->product_color : null,
                            'product_size'          => $saleProduct ? $saleProduct->product_size : null,
                            'returned_qty'          => intval($product['returned_qty']),
                            'unit_price'            => $singleProduct ? $singleProduct->unit_price : 0,
                            'sales_price'           => $singleProduct ? $singleProduct->sales_price : 0,
                            'wholesale_price'       => $singleProduct ? wholesalesPrice($singleProduct) : 0,
                            'purchase_price'        => $singleProduct ? $singleProduct->purchase_price : 0,
                            'subtotal'              => intval($product['returned_qty']) * $saleProduct->sales_price,
                            'returned_by'           => auth()->guard('admin')->user()->id ?? null,
                        ]);

                        if (!$saleReturn)
                            throw new Exception("Unable to return product!", 403);
                    }
                }
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Returned Successfully!'
            ]);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'msg'     => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function show(SaleReturn $saleReturn)
    {
        //
    }


    public function showInvoice($invoice_no)
    {
        //

        $sale_return  = SaleReturn::where('invoice_no', $invoice_no)->first();
        $sale_returns = SaleReturn::where('invoice_no', $invoice_no)->get();

        try {

            $pdf = PDF::loadView('backend.pages.sale.return_invoice', compact('sale_return', 'sale_returns'), [], [
                'margin_left'   => 20,
                'margin_right'  => 15,
                'margin_top'    => 48,
                'margin_bottom' => 25,
                'margin_header' => 10,
                'margin_footer' => 10,
                'watermark'     => 'Returned',
            ]);


            // dd($pdf);

            return $pdf->stream('return_sale_invoice_' . preg_replace("/\s/", '_', $invoice_no) . '_' . (date('Y_m_d', strtotime($sale_return->created_at)) ?? '') . '_.pdf');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleReturn $saleReturn)
    {
        //
    }

    public function salereturn_pdf(){
        $getsale_return = SaleReturn::selectRaw('sum(returned_qty) returned_qty, sum(purchase_price) purchase_price, sum(subtotal) subtotal, max(created_at) created_at, invoice_no, sale_id')->groupBy('invoice_no')->get();
        $pdf = PDF::loadView('backend.pages.sale.return_sale_pdf', compact('getsale_return'), [], [
            'margin_left'   => 20,
            'margin_right'  => 15,
            'margin_top'    => 45,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark'     => env('APP_NAME','Micro Media')
        ]);
        return $pdf->stream('sales_return.pdf');
    }


}
