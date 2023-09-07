<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SuppliertExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::orderByDesc('id')->get();
        // dd($suppliers);
        // dd(auth()->guard('admin')->user()->id);
        // $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
        // dd(auth()->user()->name);
        return view('backend.pages.supplier.supplierlist', compact('suppliers'));
    }


    public function ExportSuppliers(){
        return Excel::download(new SuppliertExport, 'supplierlist.xlsx');
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
    public function store(SupplierRequest $request)
    {
        try {
            $data               = $request->all();
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;

            $supplier   = Supplier::create($data);
            if(!$supplier)
                throw new Exception("Unable to create Supplier!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Supplier Created Successfully!',
                'data'      => $supplier
            ]);
                
        } catch (\Exception $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage(),
                'data'      => null
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        try {

            $data               = $request->all();
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;

            $supplierstatus = $supplier->update($data);
            if(!$supplierstatus)
                throw new Exception("Unable to Update Supplier!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Supplier Updated Successfully!',
                'data'      => $supplier->first()
            ]);
                
        } catch (\Exception $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage(),
                'data'      => null
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        try {

            $isDeleted = $supplier->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Supplier!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Supplier Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


    public function getsupplierpdf(){
        $getsuppliers = Supplier::get();
        $pdf = PDF::loadView('backend.pages.supplier.supplier_pdf', compact('getsuppliers'), [], [
            'margin_left'   => 20,
            'margin_right'  => 15,
            'margin_top'    => 45,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark'     => env('APP_NAME','Micro Media')
        ]);
        return $pdf->stream('customer_list.pdf');

        // return view('backend.pages.supplier.supplier_pdf');

    }


}
