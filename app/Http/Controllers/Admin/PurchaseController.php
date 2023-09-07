<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PurchaseDataExport;
use PDF;
use Exception;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariantPrice;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = Purchase::all();
        
        return view('backend.pages.purchase.managepurchase', compact('purchases'));
    }

    public function purchasedataexport(){
        return Excel::download(new PurchaseDataExport, 'purchase.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::select('supplier_name', 'id')->where('is_active', 1)->get();
        $units      = Unit::select('unit_name', 'id')->where('is_active', 1)->get();
        $currencies = Currency::select('currency_name', 'id')->where('is_active', 1)->get();
        $colors     = Variant::select('variant_name', 'id')->where([['is_active', 1], ['variant_type', 'color']])->get();
        $sizes      = Variant::select('variant_name', 'id')->where([['is_active', 1], ['variant_type', 'size']])->get();

        return view('backend.pages.purchase.addpurchase', compact('suppliers','units', 'currencies', 'colors', 'sizes'));
    }



    public function searchProduct()
    {

        $productName = request('term')['term'] ?? null;

        if (!$productName) return;

        $products = Product::where('product_name', 'like', "%{$productName}%")
                ->groupBy('id')
                ->orderByDesc('id')
                ->get();

        return response()->json($products);
    }


    public function searchPurchaseProduct()
    {

        $productName = request('term')['term'] ?? null;

        if (!$productName) return;

        $products = PurchaseProduct::where('product_name', 'like', "%{$productName}%")
                ->where(DB::raw('stocked_qty'), '<', DB::raw('product_qty'))
                ->groupBy('id')
                ->orderByDesc('id')
                ->get();

        return response()->json($products);
    }


    
    public function getProduct(Request $request)
    {

        try {

            $product_name= $request->product_name;
            $barcode     = $request->barcode;

            if($product_name){
                $purchaseProducts = PurchaseProduct::with('product', 'purchase:id,currency', 'product.brands', 'product.singleProductTags', 'product.productColors', 'product.productSizes')
                                ->where('product_name', $product_name)
                                ->where(DB::raw('stocked_qty'), '<', DB::raw('product_qty'))
                                ->first();
            }else{
                $purchaseProducts = PurchaseProduct::with('product', 'purchase:id,currency', 'product.brands', 'product.singleProductTags', 'product.productColors', 'product.productSizes')
                                ->where('barcode', $barcode)
                                ->where(DB::raw('stocked_qty'), '<', DB::raw('product_qty'))
                                ->first();
            }


            return response()->json($purchaseProducts);

        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'msg'     => $th->getMessage()
            ]);
        }
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

            $data               = $request->except('products');
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $data['sizes']      = null;
            $data['colors']     = null;

            if(!isset($data['supplier_id']))
                throw new Exception("Please Select Supplier!", 403);

            if(!isset($data['purchase_date']))
                throw new Exception("Please Select Purchase Date!", 403);
                

            $sizes      = [];
            $colors     = [];
            $productData= [];
            
            $products = $request->products;
            foreach ($products as $product) {
                $colors= array_merge($colors, $product['product_colors']);
                $sizes = array_merge($sizes, $product['product_sizes']);
            }

            $data['sizes']          = count($colors) ? implode(",", $colors) : null;
            $data['colors']         = count($sizes) ? implode(",", $sizes) : null;
            $data['supplier_name']  = $this->supplierName($data['supplier_id']);

            // dd($data);

            DB::beginTransaction();

            $purchase = Purchase::create($data);
            if(!$purchase)
                throw new Exception("Unable to create Purchase!", 403);

            foreach ($products as $product){

                $productResponse = $this->productByName(trim($product['product_name']));

                $productData[]= [
                    'product_colors'=> count($product['product_colors']) ? implode(",", $product['product_colors']) : null,
                    'product_sizes' => count($product['product_sizes']) ? implode(",", $product['product_sizes']) : null,
                    'purchase_id'   => $purchase->id,
                    'product_id'    => $productResponse ? $productResponse->id : null,
                ] + $product;

            }


            $purchase->purchaseProducts()->createMany($productData);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Purchased Successfully!',
            ]);


        } catch (\Throwable $th) {

            DB::rollback();
            return response()->json([
                'success' => false,
                'msg'     => $th->getMessage()
            ]);
        }
    }


    private function supplierName($id){
        $supplier = Supplier::find($id);
        return $supplier ? $supplier->supplier_name : null;
    }

    private function productByName($name){
        $product = Product::where('product_name',$name)->first();
        return $product;
    }


    public function payment(Request $request){
        try{
            //

            $purchase_id= $request->purchase_id;
            $supplier_id= $request->supplier_id;

            $total      = $request->total_payment;
            if(!$total)
                throw new Exception("Payment Amount is Required!", 403);

            DB::beginTransaction();

            if($purchase_id){

                $purchase= Purchase::find($purchase_id);
                if(!$purchase)
                    throw new Exception("Purchase Not Found!", 404);

                $totalPaid = $purchase->total_payment + $total;
                if($totalPaid > $purchase->total_price)
                    throw new Exception('Invalid Amount!');

                $payment = Payment::create([
                    'supplier_id'       => $purchase->supplier_id ?? null,
                    'purchase_id'       => $purchase_id,
                    'payment_type'      => 'cash',
                    'transection_id'    => 'manual_'.uniqid(),
                    // 'currency'          => null,
                    'payment_amount'    => $total,
                    'payment_due'       => $due = $purchase->total_payment - $total,
                    'payment_status'    => $due > 0 ? 'Due' : 'Paid',
                    'payment_by'        => auth()->guard('admin')->user()->id ?? null,
                ]);

                if(!$payment)
                    throw new Exception("Unable to make Payment!", 403);

                $total_payment_amount = Payment::where('purchase_id', $purchase_id)->sum('payment_amount');

                $update = $purchase->update([
                    'total_payment'     => $total_payment_amount ?? 0,
                    'total_payment_due' => $purchase->total_price - floatval($total_payment_amount),
                ]);

                if(!$update)
                    throw new Exception("Unable to make payment for the purchase!", 403);

                $total_payment_amount = Payment::where('supplier_id', $purchase->supplier_id)->sum('payment_amount');

                $supplier = $purchase->supplier()->update(['current_balance' => $total_payment_amount]);
                if(!$supplier)
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
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }


    public function manage_stock()
    {
        $categories = Category::select('category_name', 'id')->where('is_active', 1)->get();
        $brands     = Brand::select('brand_name', 'id')->where('is_active', 1)->get();
        $units      = Unit::select('unit_name', 'id')->where('is_active', 1)->get();
        $currencies = Currency::select('currency_name', 'id')->where('is_active', 1)->get();
        $colors     = Variant::select('variant_name', 'id')->where([['is_active', 1], ['variant_type', 'color']])->get();
        $sizes      = Variant::select('variant_name', 'id')->where([['is_active', 1], ['variant_type', 'size']])->get();
        
        return view('backend.pages.purchase.manage_stock', compact('categories', 'brands', 'units', 'currencies', 'colors', 'sizes'));
    }


    public function showInvoice($invoice_no)
    {
        //

        $purchase = Purchase::where('invoice_no',$invoice_no)->first();

        try {

            $pdf = PDF::loadView('backend.pages.purchase.invoice', compact('purchase'), [], [
                'margin_left'   => 20,
                'margin_right'  => 15,
                'margin_top'    => 48,
                'margin_bottom' => 25,
                'margin_header' => 10,
                'margin_footer' => 10,
                'watermark'     => $this->setWaterMark($purchase),
            ]);


            // dd($pdf);

            return $pdf->stream('purchase_invoice_' . preg_replace("/\s/", '_', $invoice_no) . '_' . ($purchase->purchase_date ?? '') . '_.pdf');

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


    private function setWaterMark($purchase)
    {
        return $purchase && ($purchase->total_price <= $purchase->total_payment) ? 'Paid' : 'Due';
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $suppliers  = Supplier::select('supplier_name', 'id')->where('is_active', 1)->get();
        $units      = Unit::select('unit_name', 'id')->where('is_active', 1)->get();
        $currencies = Currency::select('currency_name', 'id')->where('is_active', 1)->get();
        $colors     = Variant::select('variant_name', 'id')->where([['is_active', 1], ['variant_type', 'color']])->get();
        $sizes      = Variant::select('variant_name', 'id')->where([['is_active', 1], ['variant_type', 'size']])->get();

        return view('backend.pages.purchase.editpurchase', compact('suppliers', 'units', 'currencies', 'colors', 'sizes', 'purchase'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        try {
            //

            $data               = $request->except('products');
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $data['sizes']      = null;
            $data['colors']     = null;

            if (!isset($data['supplier_id']))
            throw new Exception("Please Select Supplier!", 403);

            if (!isset($data['purchase_date']))
            throw new Exception("Please Select Purchase Date!", 403);


            $sizes      = [];
            $colors     = [];
            $productData = [];

            $products = $request->products;
            foreach ($products as $product) {
                $colors = array_merge($colors, $product['product_colors']);
                $sizes = array_merge($sizes, $product['product_sizes']);
            }

            $data['sizes']          = count($colors) ? implode(",", $colors) : null;
            $data['colors']         = count($sizes) ? implode(",", $sizes) : null;
            $data['supplier_name']  = $this->supplierName($data['supplier_id']);

            DB::beginTransaction();

            $purchaseUpdate = $purchase->update($data);
            if (!$purchaseUpdate)
                throw new Exception("Unable to create Purchase!", 403);

            foreach ($products as $product) {
                $productData[] = [
                    'product_colors'=>count($product['product_colors']) ? implode(",", $product['product_colors']) : null,
                    'product_sizes' => count($product['product_sizes']) ? implode(",", $product['product_sizes']) : null,
                    'purchase_id'   => $purchase->id,
                    'product_id'    => null,
                ] + $product;

                // dd($productData);
            }


            $purchase->purchaseProducts()->delete();

            $purchase->purchaseProducts()->createMany($productData);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Purchase Updated Successfully!',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        try {

            $supplier_id = $purchase->supplier_id;

            DB::beginTransaction();

            $purchase->delete();
            $purchase->purchaseProducts()->delete();
            $purchase->payments()->delete();

            $total_payment_amount = Payment::where('supplier_id', $supplier_id)->sum('payment_amount');

            $supplier = $purchase->supplier()->update(['current_balance' => $total_payment_amount]);
            if (!$supplier)
                throw new Exception("Unable to add Amount", 403);

            DB::commit();

            return response()->json([
                'success'   => true,
                'msg'       => 'Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


    public function checkInvoice(){
        try {

            $invoice_no = request()->invoice;
            $purchase = Purchase::where('invoice_no', $invoice_no)->first();
            if ($purchase)
                throw new Exception("Invoice Already Exists!", 403);

            return response()->json([
                'success' => true,
                'msg'     => 'Invoice Not Found!'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'msg'     => $th->getMessage()
            ]);
        }
            
    }


    public function getpurchasepdf(){
        $getpurchases = Purchase::get();
        $pdf = PDF::loadView('backend.pages.purchase.purchase_pdf', compact('getpurchases'), [], [
            'margin_left'   => 20,
            'margin_right'  => 15,
            'margin_top'    => 45,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark'     => env('APP_NAME','Micro Media')
        ]);
        return $pdf->stream('purchase.pdf');

        // return view('backend.pages.purchase.purchase_pdf');
    }


}
