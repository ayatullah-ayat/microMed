<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PurchaseReturnExport;
use PDF;
use Exception;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $purchases = PurchaseReturn::selectRaw('sum(returned_qty) returned_qty, sum(purchase_price) purchase_price, sum(subtotal) subtotal, max(created_at) created_at, invoice_no, purchase_id')->groupBy('invoice_no')->get();
        
        return view('backend.pages.purchase.return_purchase', compact('purchases'));

    }


    public function purchasereturnexport(){
        return Excel::download(new PurchaseReturnExport, 'purchase_return.xlsx');
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
        try{
            //

            $products = $request->products;

            if(!$products)
                throw new Exception("Invalid Request!", 403);
                
            if(!is_array($products))
                throw new Exception("Invalid Request!", 403);
                
            if(!count($products))
                throw new Exception("Please Select Product!", 403);


            DB::beginTransaction();

            $returnInvoice = uniqid();

            foreach ($products as $product) {

                if(intval($product['returned_qty']) > 0 && $product['purchase_product_id']){
                    $purchaseProduct = PurchaseProduct::find($product['purchase_product_id']);
                    if($purchaseProduct){

                        // dd($purchaseProduct);
                        
                        $purchase = $purchaseProduct->purchase;

                        if ($purchaseProduct->product_qty < intval($product['returned_qty']))
                            throw new Exception("Returnable Qty: {$purchaseProduct->product_qty}", 403);

                        $currentQty = $purchaseProduct->product_qty - intval($product['returned_qty']);
                        $updatables = [
                            'product_qty' => $currentQty,
                            'subtotal'    => $currentQty * $purchaseProduct->product_price,
                            'returned_qty'=> $purchaseProduct->returned_qty + intval($product['returned_qty']) ?? 0,
                        ];

                        if($purchaseProduct->stocked_qty > 0){
                            $updatables['stocked_qty']= $purchaseProduct->stocked_qty - intval($product['returned_qty']);
                        }
    
                        $updatePurchaseProduct = $purchaseProduct->update($updatables);
                        if(!$updatePurchaseProduct)
                            throw new Exception("Unable to Update Purchase Product!", 403);
                            

                        //product update
                        $purchaseProduct->purchase()->update([
                            'total_qty'     => $purchase->total_qty - intval($product['returned_qty']),
                            'total_price'   => $purchase->total_price - (intval($product['returned_qty']) * $purchaseProduct->product_price ),
                            'is_returned'   => 1,
                        ]);

                        $singleProduct = $purchaseProduct->product;

                        if($purchase->is_manage_stock && $singleProduct){

                            if($singleProduct->total_stock_qty <  intval($product['returned_qty']))
                                throw new Exception("Available Stock Qty: {$singleProduct->total_stock_qty}", 403);


                            $productUpdate = $purchaseProduct->product()->update([
                                'total_product_qty'         =>$singleProduct->total_product_qty - intval($product['returned_qty']),
                                'total_stock_qty'           =>$singleProduct->total_stock_qty - intval($product['returned_qty']),
                                'total_product_unit_price'  =>$singleProduct->total_product_unit_price - (intval($product['returned_qty']) * $singleProduct->purchase_price),
                                'total_stock_price'         =>$singleProduct->total_stock_price - (intval($product['returned_qty']) * $singleProduct->purchase_price),
                            ]);

                            if(!$productUpdate)
                                throw new Exception("Unable to Update Stock Qty!", 403);
                                

                        }


                        $purchaseReturn = PurchaseReturn::create([
                            'product_id'            => $singleProduct ? $singleProduct->id : null,
                            'purchase_id'           => $purchase->id ?? null,
                            'purchase_product_id'   => $product['purchase_product_id'] ?? null,
                            'invoice_no'            => $returnInvoice,
                            'barcode'               => $singleProduct ? $singleProduct->barcode : null,
                            'product_name'          => $purchaseProduct ? $purchaseProduct->product_name : null,
                            'product_unit'          => $purchaseProduct ? $purchaseProduct->product_unit : null,
                            'product_color'         => null,
                            'product_size'          => null,
                            'returned_qty'          => intval($product['returned_qty']),
                            'purchase_price'        => $purchaseProduct->product_price,
                            'unit_price'            => $singleProduct ? $singleProduct->unit_price : 0,
                            'sales_price'           => $singleProduct ? $singleProduct->sales_price : 0,
                            'wholesale_price'       => $singleProduct ? wholesalesPrice($singleProduct) : 0,
                            'subtotal'              => intval($product['returned_qty']) * $purchaseProduct->product_price,
                            'returned_by'           => auth()->guard('admin')->user()->id ?? null,
                        ]);

                        if(!$purchaseReturn)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function showInvoice($invoice_no)
    {
        //

        $purchase_return  = PurchaseReturn::where('invoice_no', $invoice_no)->first();
        $purchase_returns = PurchaseReturn::where('invoice_no', $invoice_no)->get();

        try {

            $pdf = PDF::loadView('backend.pages.purchase.return_invoice', compact('purchase_return', 'purchase_returns'), [], [
                'margin_left'   => 20,
                'margin_right'  => 15,
                'margin_top'    => 48,
                'margin_bottom' => 25,
                'margin_header' => 10,
                'margin_footer' => 10,
                'watermark'     => 'Returned',
            ]);


            // dd($pdf);

            return $pdf->stream('return_purchase_invoice_' . preg_replace("/\s/", '_', $invoice_no) . '_' . ( date('Y_m_d',strtotime($purchase_return->created_at)) ?? '') . '_.pdf');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getreturnpurchasepdf(){
        // $returnpurchases = PurchaseReturn::get();
        $returnpurchases = PurchaseReturn::selectRaw('sum(returned_qty) returned_qty, sum(purchase_price) purchase_price, sum(subtotal) subtotal, max(created_at) created_at, invoice_no, purchase_id')->groupBy('invoice_no')->get();
        $pdf = PDF::loadView('backend.pages.purchase.return_purchase_pdf', compact('returnpurchases'), [], [
            'margin_left'   => 20,
            'margin_right'  => 15,
            'margin_top'    => 45,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark'     => env('APP_NAME','Micro Media')
        ]);
        return $pdf->stream('product_data.pdf');

        // return view('backend.pages.purchase.return_purchase_pdf');
    }

}
