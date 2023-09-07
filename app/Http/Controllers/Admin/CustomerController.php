<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderByDesc('id')->get();
        return view('backend.pages.customer.managecustomer', compact('customers'));
    }


    public function customerExportData(){
        return Excel::download(new  CustomerExport, 'customerlist.xlsx');
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
    public function store(CustomerRequest $request)
    {
        try {
            $data               = $request->all();
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            
            $customer   = Customer::create($data);

            if(!$customer)
                throw new Exception("Unable to create Customer!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Customer Created Successfully!',
                'data'      => $customer
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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        try {

            $data               = $request->all();
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;

            $customerstatus = $customer->update($data);
            if(!$customerstatus)
                throw new Exception("Unable to Update customer!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Customer Updated Successfully!',
                'data'      => $customer->first()
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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try {

            $isDeleted = $customer->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Customer!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Customer Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


    public function getcustomerpdf(){
        $getcustomers = Customer::get();
        // dd($getcustomers);
        $pdf = PDF::loadView('backend.pages.customer.customer_pdf', compact('getcustomers'), [], [
            'margin_left'   => 20,
            'margin_right'  => 15,
            'margin_top'    => 45,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark'     => env('APP_NAME','Micro Media')
        ]);
        return $pdf->stream('customer_list.pdf');

        // return view('backend.pages.customer.customer_pdf');
    }


}
