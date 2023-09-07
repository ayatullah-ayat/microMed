<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SaleDataExport;
use PDF;
use Exception;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SaleProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::all();
        return view("backend.pages.sale.managesale", compact('sales'));
    }

    public function saledataexport(){
        return Excel::download(new SaleDataExport, 'saleexport.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::where('is_active', 1)->where('is_block', 0)->get();

        return view('backend.pages.sale.addsale', compact('customers'));
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

            $data = $request->all();
            if(!$request->sale_date)
                throw new Exception("Please Select Sales Date!", 403);

            if(!$request->customer_id)
                throw new Exception("Please Select Customer!", 403);

            if(!array_key_exists('products', $data))
                throw new Exception("Product Missing!", 403);

            if(!count($data['products']))
                throw new Exception("Please select Product!", 403);


            $totalQtys = [];

            foreach ($data['products'] as $product) {

                if(!array_key_exists($product['product_id'], $totalQtys)){
                    $totalQtys[$product['product_id']] = 0;
                }

                $totalQtys[$product['product_id']] += intval($product['product_qty']);

            }

            DB::beginTransaction();

            $salesInvoice = uniqid();
                
            $sale = Sale::create([
                'customer_id'           => $data['customer_id'] ?? null,
                'customer_name'         => $data['customer_name'] ?? null,
                'sales_date'            => $data['sale_date'] ?? null,
                'invoice_no'            => $salesInvoice,
                'sold_sizes'            => null,
                'sold_colors'           => null,
                'sold_total_qty'        => 0,
                'total_payment'         => $data['total_payment'] ?? 0,
                'total_payment_due'     => floatval($data['order_grand_total'] ?? 0) - floatval($data['total_payment'] ?? 0),
                'sold_total_price'      => $data['order_subtotal'] ?? 0,
                'total_discount_price'  => $data['discount_price'] ?? 0,
                'order_grand_total'     => $data['order_grand_total'] ?? 0,
                'sales_note'            => null,
                'sold_by'               => auth()->guard('admin')->user()->id ?? null,
                'sold_by_name'          => auth()->guard('admin')->user()->name ?? null,
            ]);

            if(!$sale)
                throw new Exception("Unable to create Sale!", 403);


            if(floatval($data['total_payment'] ?? 0)){

                $payment = Payment::create([
                    'customer_id'       => $sale->customer_id ?? null,
                    'sale_id'           => $sale->id,
                    'payment_type'      => 'cash',
                    'transection_id'    => 'manual_' . uniqid(),
                    // 'currency'          => null,
                    'payment_amount'    => $data['total_payment'],
                    'payment_due'       => $due = (floatval($data['order_grand_total'] ?? 0) - floatval($data['total_payment'] ?? 0)),
                    'payment_status'    => $due > 0 ? 'Due' : 'Paid',
                    'payment_by'        => auth()->guard('admin')->user()->id ?? null,
                ]);
    
                if (!$payment)
                    throw new Exception("Unable to make Payment!", 403);
            }

            $isFirstCheck = false;

            foreach ($data['products'] as $product) {

                $singleProduct = Product::find($product['product_id']);

                $soldProduct = $product + [
                    'invoice_no' => $salesInvoice,
                    'unit_price' => $singleProduct->unit_price ?? 0,
                ];

                $productCreate = $sale->saleProducts()->create($soldProduct);
                if(! $productCreate )
                    throw new Exception("Unable to create Product!", 403);

                if(!$isFirstCheck && $singleProduct->total_stock_qty < $totalQtys[$product['product_id']])
                    throw new Exception("{$singleProduct->product_name}, Available Stock: {$singleProduct->total_stock_qty}", 1);

                    
                if ($isFirstCheck && $singleProduct->total_stock_qty < intval($product['product_qty']))
                    throw new Exception("{$singleProduct->product_name}, Available Stock: {$singleProduct->total_stock_qty}", 1);
                    
                $isFirstCheck = true;

                $singleProduct->decrement('total_stock_qty', intval($product['product_qty']));
                $singleProduct->decrement('total_stock_price', intval($product['subtotal']));

                $singleProduct->increment('total_stock_out_qty', intval($product['product_qty']));
                $singleProduct->increment('total_stock_out_price', intval($product['subtotal']));
                    
            }

            $updateSale = $sale->update([
                'sold_sizes'            => $this->summary($salesInvoice, "group_concat(DISTINCT product_size) as result"),
                'sold_colors'           => $this->summary($salesInvoice, "group_concat(DISTINCT product_color) as result"),
                'sold_total_qty'        => $this->summary($salesInvoice, 'sum(product_qty) as result'),
            ]);

            if(!$updateSale)
                throw new Exception("Unable to Update Sale!", 403);
                

            // dd($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Data store Successfully!'
            ]);


        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'msg'     => $th->getMessage()
            ]);
        }
    }


    private function summary($invoice_no,$select="*"){
        $result = SaleProduct::selectRaw($select)
            ->where('invoice_no', $invoice_no)
            ->groupBy('invoice_no')
            ->first();

        return $result ? $result->result : null;
    }



    public function payment(Request $request)
    {
        try {
            //

            $sale_id    = $request->sale_id;
            $customer_id= $request->customer_id;

            $total      = $request->total_payment;
            if (!$total)
                throw new Exception("Payment Amount is Required!", 403);

            DB::beginTransaction();

            if ($sale_id) {

                $sale = Sale::find($sale_id);
                if (!$sale)
                    throw new Exception("Purchase Not Found!", 404);

                $totalPaid = $sale->total_payment + $total;
                if ($totalPaid > $sale->order_grand_total)
                    throw new Exception('Invalid Amount!');

                $payment = Payment::create([
                    'customer_id'       => $sale->customer_id ?? null,
                    'sale_id'           => $sale_id,
                    'payment_type'      => 'cash',
                    'transection_id'    => 'manual_' . uniqid(),
                    // 'currency'          => null,
                    'payment_amount'    => $total,
                    'payment_due'       => $due = $sale->total_payment - $total,
                    'payment_status'    => $due > 0 ? 'Due' : 'Paid',
                    'payment_by'        => auth()->guard('admin')->user()->id ?? null,
                ]);

                if (!$payment)
                    throw new Exception("Unable to make Payment!", 403);

                $total_payment_amount = Payment::where('sale_id', $sale_id)->sum('payment_amount');

                $update = $sale->update([
                    'total_payment'     => $total_payment_amount ?? 0,
                    'total_payment_due' => $sale->order_grand_total - floatval($total_payment_amount),
                ]);

                if (!$update)
                    throw new Exception("Unable to make payment for the purchase!", 403);

                $total_payment_amount = Payment::where('customer_id', $sale->customer_id)->sum('payment_amount');

                $customer = $sale->customer()->update(['current_balance' => $total_payment_amount]);
                if (!$customer)
                    throw new Exception("Unable to add Amount", 403);
            }



            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Paymented Successfully!'
            ]);
        } catch (\Throwable $th) {

            DB::rollback();
            return response()->json([
                'success' => false,
                'msg'     => $th->getMessage()
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        //
    }


    public function showInvoice($invoice_no)
    {
        //

        $sale = Sale::where('invoice_no', $invoice_no)->first();

        try {

            $pdf = PDF::loadView('backend.pages.sale.invoice', compact('sale'), [], [
                'margin_left'   => 20,
                'margin_right'  => 15,
                'margin_top'    => 48,
                'margin_bottom' => 25,
                'margin_header' => 10,
                'margin_footer' => 10,
                'watermark'     => $this->setWaterMark($sale),
            ]);


            // dd($pdf);

            return $pdf->stream('sale_invoice_' . preg_replace("/\s/", '_', $invoice_no) . '_' . ($sale->sales_date ?? '') . '_.pdf');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    private function setWaterMark($sale)
    {
        return $sale && ($sale->order_grand_total <= $sale->total_payment) ? 'Paid' : 'Due';
    }



    public function getVariantsByProduct(Product $product)
    {
        $colors = $product->productColors;
        $sizes  = $product->productSizes;

        return response()->json([
            'colors' => $colors,
            'sizes' => $sizes,
            'product'=> $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        //
        $customers = Customer::where('is_active', 1)->where('is_block', 0)->get();

        return view('backend.pages.sale.editsale', compact('customers', 'sale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        try {

            $data = $request->all();
            if (!$request->sale_date)
                throw new Exception("Please Select Sales Date!", 403);

            if (!$request->customer_id)
                throw new Exception("Please Select Customer!", 403);

            if (!array_key_exists('products', $data))
                throw new Exception("Product Missing!", 403);

            if (!count($data['products']))
            throw new Exception("Please select Product!", 403);


            $totalQtys = [];

            foreach ($data['products'] as $product) {

                if (!array_key_exists($product['product_id'], $totalQtys)) {
                    $totalQtys[$product['product_id']] = 0;
                }

                $totalQtys[$product['product_id']] += intval($product['product_qty']);
            }

            DB::beginTransaction();

            $salesInvoice = uniqid();

            $saleUpdate = $sale->update([
                'customer_id'           => $data['customer_id'] ?? null,
                'customer_name'         => $data['customer_name'] ?? null,
                'sales_date'            => $data['sale_date'] ?? null,
                'invoice_no'            => $salesInvoice,
                'sold_sizes'            => null,
                'sold_colors'           => null,
                'sold_total_qty'        => 0,
                'sold_total_price'      => $data['order_subtotal'] ?? 0,
                'total_discount_price'  => $data['discount_price'] ?? 0,
                'order_grand_total'     => $data['order_grand_total'] ?? 0,
                'sales_note'            => null,
                'updated_by'            => auth()->guard('admin')->user()->id ?? null,
                'updated_by_name'       => auth()->guard('admin')->user()->name ?? null,
            ]);

            if (!$saleUpdate)
                throw new Exception("Unable to Update Sale!", 403);

            $isFirstCheck = false;

             foreach ($sale->saleProducts as $saleProd) {

                $singleProductItem = $saleProd->product;

                $singleProductItem->increment('total_stock_qty', $saleProd->product_qty);
                $singleProductItem->increment('total_stock_price', ($saleProd->product_qty * $saleProd->sales_price));

                $singleProductItem->decrement('total_stock_out_qty', $saleProd->product_qty);
                $singleProductItem->decrement('total_stock_out_price', ($saleProd->product_qty * $saleProd->sales_price));
            }

            $sale->saleProducts()->delete();


            foreach ($data['products'] as $product) {

                $singleProduct = Product::find($product['product_id']);

                $soldProduct = $product + [
                    'invoice_no' => $salesInvoice,
                    'unit_price' => $singleProduct->unit_price ?? 0,
                ];

                $productCreate = $sale->saleProducts()->create($soldProduct);
                if (!$productCreate)
                    throw new Exception("Unable to create Product!", 403);

                if (!$isFirstCheck && $singleProduct->total_stock_qty < $totalQtys[$product['product_id']])
                    throw new Exception("{$singleProduct->product_name}, Available Stock: {$singleProduct->total_stock_qty}", 1);


                if ($isFirstCheck && $singleProduct->total_stock_qty < intval($product['product_qty']))
                    throw new Exception("{$singleProduct->product_name}, Available Stock: {$singleProduct->total_stock_qty}", 1);

                $isFirstCheck = true;

                $singleProduct->decrement('total_stock_qty', intval($product['product_qty']));
                $singleProduct->decrement('total_stock_price', intval($product['subtotal']));

                $singleProduct->increment('total_stock_out_qty', intval($product['product_qty']));
                $singleProduct->increment('total_stock_out_price', intval($product['subtotal']));
            }

            $updateSale = $sale->update([
                'sold_sizes'            => $this->summary($salesInvoice, "group_concat(DISTINCT product_size) as result"),
                'sold_colors'           => $this->summary($salesInvoice, "group_concat(DISTINCT product_color) as result"),
                'sold_total_qty'        => $this->summary($salesInvoice, 'sum(product_qty) as result'),
            ]);

            if (!$updateSale)
                throw new Exception("Unable to Update Sale!", 403);


            // dd($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Data Updated Successfully!'
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        //
    }


    public function managesales_pdf(){
        $getsales = Sale::get();
        $pdf = PDF::loadView('backend.pages.sale.sale_pdf', compact('getsales'), [], [
            'margin_left'   => 20,
            'margin_right'  => 15,
            'margin_top'    => 45,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark'     => env('APP_NAME','Micro Media')
        ]);
        return $pdf->stream('sales.pdf');

        // return view('backend.pages.sale.sale_pdf');
    }
}
