<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PurcahseProductStockReportExport;
use App\Exports\StockReportExport;
use App\Exports\SupplierStockReportExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class StockReportController extends Controller
{
    /**
     * Stock report
     */
    public function stockreport(){
        $stocks = Product::where('is_active', 1)->where('is_publish', 1)->get();
        // dd($stocks);
        return view('backend.pages.stock.stockreport', compact('stocks'));
    }


    public function exportStockReport(){
        return Excel::download(new StockReportExport, 'stockreport.xlsx');
    }

    /**
     * Supplier report
     */
    public function supplierstock(Request $request ){
        $suppliers  = Supplier::where('is_active', 1)->get();
        $date       = $request->date;
        $supplier_id= $request->supplier_id;

        if($request->ajax()){

            $sql = Purchase::selectRaw('
                    purchases.purchase_date, 
                    purchases.supplier_id, 
                    purchases.supplier_name, 
                    purchases.total_payment, 
                    purchases.total_payment_due,
                    purchases.is_manage_stock,
                    purchase_products.*,
                    products.sales_price,
                    products.category_name
                    ')
                    ->join('purchase_products', 'purchase_products.purchase_id','=', 'purchases.id')
                    ->join('products', 'purchase_products.product_id','=', 'products.id')
                    ->where('purchases.supplier_id', $supplier_id)
                    ->where('purchases.is_manage_stock', 1)
                    ->where('purchase_products.stocked_qty', ">", 0);
    
            if($date){
                $sql->Where('purchases.purchase_date', $date);
            }
    
            $stocks = $sql->get();

            return response()->json($stocks);
        }


        // dd($stocks);

        return view('backend.pages.stock.supplierstockreport', compact('suppliers'));
    }


    public function supplierstockreportExport(Request $request){

        $date       = $request->date;
        $supplier_id= $request->supplier_id;

        $sql = Purchase::selectRaw(' 
        purchases.supplier_name, 
        purchase_products.product_name,
        products.category_name,
        purchase_products.product_unit,
        products.sales_price,
        purchase_products.product_price,
        purchase_products.product_qty,
        purchase_products.stocked_qty,
        purchase_products.returned_qty
        ')
        ->join('purchase_products', 'purchase_products.purchase_id','=', 'purchases.id')
        ->join('products', 'purchase_products.product_id','=', 'products.id')
        ->where('purchases.supplier_id', $supplier_id)
        ->where('purchases.is_manage_stock', 1)
        ->where('purchase_products.stocked_qty', ">", 0);

        if($date){
            $sql->Where('purchases.purchase_date', $date);
        }

        $stocks = $sql->get();
        
        // dd($stocks);
        return Excel::download(new SupplierStockReportExport($stocks), 'supplier_stock_report.xlsx');

    }

    /**
     * product report
     */
    public function productreport(Request $request){
        
        $suppliers  = Supplier::where('is_active', 1)->get();
        $from_date  = $request->from_date;
        $to_date    = $request->to_date;
        $supplier_id= $request->supplier_id;
        $product_id = $request->product_id;

        if ($request->ajax()) {

            $sql = Purchase::selectRaw('
                purchases.purchase_date, 
                purchases.supplier_id, 
                purchases.supplier_name, 
                purchases.total_payment, 
                purchases.total_payment_due,
                purchases.is_manage_stock,
                purchase_products.*,
                products.sales_price,
                products.category_name
            ')
            ->join('purchase_products', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->join('products', 'purchase_products.product_id', '=', 'products.id')
            ->where('purchases.supplier_id', $supplier_id)
            ->where('purchase_products.product_id', $product_id);
            // ->where('purchases.is_manage_stock', 1)
            // ->where('purchase_products.stocked_qty', ">", 0);

            if ($from_date) {
                $sql->whereDate('purchases.purchase_date', '>=', $from_date);
            }

            if ($to_date) {
                $sql->whereDate('purchases.purchase_date', '<=', $from_date);
            }

            $stocks = $sql->get();

            return response()->json($stocks);
        }

        return view('backend.pages.stock.productstockreport', compact('suppliers'));
    }


    public function purchaseProductStockReportExport( Request $request){

        $from_date  = $request->from_date;
        $to_date    = $request->to_date;
        $supplier_id= $request->supplier_id;
        $product_id = $request->product_id;
        // dd($from_date, $to_date);

        $sql = Purchase::selectRaw('
                purchases.supplier_name, 
                purchase_products.product_name,
                purchase_products.product_unit,
                purchase_products.product_price,
                purchase_products.product_qty,
                purchase_products.stocked_qty,
                purchase_products.returned_qty,
                round(purchase_products.product_qty * purchase_products.product_price,0) in_amount,
                round(purchase_products.stocked_qty * purchase_products.product_price,0) in_stock_amount,
                round(purchase_products.returned_qty * purchase_products.product_price,0) in_return_amount
            ')
            ->join('purchase_products', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->join('products', 'purchase_products.product_id', '=', 'products.id')
            ->where('purchases.supplier_id', $supplier_id)
            ->where('purchase_products.product_id', $product_id);

            if ($from_date) {
                $sql->whereDate('purchases.purchase_date', '>=', $from_date);
            }

            if ($to_date) {
                $sql->whereDate('purchases.purchase_date', '<=', $from_date);
            }

            $stocks = $sql->get();

            return Excel::download(new PurcahseProductStockReportExport($stocks), 'purchase_product_stock_eport.xlsx');
    }


    public function getProductBySupplier(Request $request){
        $supplier_id = $request->supplier_id;

        $data = Purchase::selectRaw('
            products.id,
            products.product_name
        ')
        ->join('purchase_products', 'purchase_products.purchase_id', '=', 'purchases.id')
        ->join('products', 'purchase_products.product_id', '=', 'products.id')
        ->where('purchases.supplier_id', $supplier_id)
        ->groupBy('purchase_products.product_id')
        ->get();

        return response()->json($data);

    }

    public function index()
    {
        //
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
        //
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


    public function getstockreportpdf(){
        $getstocks = Product::select('product_name','category_name','product_unit','sales_price','purchase_price','total_product_qty','total_stock_out_qty','total_stock_qty')->get();
        // dd($getstocks);
        $pdf = PDF::loadView('backend.pages.stock.stockreport_pdf', compact('getstocks'), [], [
            'margin_left'   => 20,
            'margin_right'  => 15,
            'margin_top'    => 45,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark'     => env('APP_NAME','Micro Media')
        ]);
        return $pdf->stream('stock.pdf');
    }


    public function supplier_stock_pdf(Request $request){
        $supplier_id= $request->supplier_id;
        $date       = $request->date;

        $sql = Purchase::selectRaw(' 
        purchases.supplier_name, 
        purchase_products.product_name,
        products.category_name,
        purchase_products.product_unit,
        products.sales_price,
        purchase_products.product_price,
        purchase_products.product_qty,
        purchase_products.stocked_qty,
        purchase_products.returned_qty
        ')
        ->join('purchase_products', 'purchase_products.purchase_id','=', 'purchases.id')
        ->join('products', 'purchase_products.product_id','=', 'products.id')
        ->where('purchases.supplier_id', $supplier_id)
        ->where('purchases.is_manage_stock', 1)
        ->where('purchase_products.stocked_qty', ">", 0);

        if($date){
            $sql->Where('purchases.purchase_date', $date);
        }

        $stocks = $sql->get();

        $pdf = PDF::loadView('backend.pages.stock.supplierstock_report_pdf', compact('stocks'), [], [
            'margin_left'   => 20,
            'margin_right'  => 15,
            'margin_top'    => 45,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark'     => env('APP_NAME','Micro Media')
        ]);
        return $pdf->stream('supplier_stock.pdf');
        
        // dd($stocks);
    }

    public function product_stock_pdf(Request $request){
        $from_date  = $request->from_date;
        $to_date    = $request->to_date;
        $supplier_id= $request->supplier_id;
        $product_id = $request->product_id;

        $sql = Purchase::selectRaw('
                purchases.supplier_name, 
                purchase_products.product_name,
                purchase_products.product_unit,
                purchase_products.product_price,
                purchase_products.product_qty,
                purchase_products.stocked_qty,
                purchase_products.returned_qty,
                round(purchase_products.product_qty * purchase_products.product_price,0) in_amount,
                round(purchase_products.stocked_qty * purchase_products.product_price,0) in_stock_amount,
                round(purchase_products.returned_qty * purchase_products.product_price,0) in_return_amount
            ')
            ->join('purchase_products', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->join('products', 'purchase_products.product_id', '=', 'products.id')
            ->where('purchases.supplier_id', $supplier_id)
            ->where('purchase_products.product_id', $product_id);

            if ($from_date) {
                $sql->whereDate('purchases.purchase_date', '>=', $from_date);
            }

            if ($to_date) {
                $sql->whereDate('purchases.purchase_date', '<=', $from_date);
            }

            $product_stocks = $sql->get();
            $pdf = PDF::loadView('backend.pages.stock.product_stock_pdf', compact('product_stocks'), [], [
                'margin_left'   => 20,
                'margin_right'  => 15,
                'margin_top'    => 45,
                'margin_bottom' => 20,
                'margin_header' => 5,
                'margin_footer' => 5,
                'watermark'     => env('APP_NAME','Micro Media')
            ]);
            return $pdf->stream('product_stock.pdf');
    }


}
