<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;
use App\Http\Requests\TaxRequest;
use Exception;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taxes = Tax::orderByDesc('id')->get();
        return view('backend.pages.tax.taxlist', compact('taxes'));
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
    public function store(TaxRequest $request)
    {
        try {
            $data      = $request->all();
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $tax   = Tax::create($data);
            if(!$tax)
                throw new Exception("Unable to create Tax!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Tax Created Successfully!',
                'data'      => $tax
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
     * @param  \App\Models\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function show(Tax $tax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function edit(Tax $tax)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tax $tax)
    {
        try {

            $data           = $request->all();
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;
            $taxstatus  = $tax->update($data);
            if(!$taxstatus)
                throw new Exception("Unable to Update Tax!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Tax Updated Successfully!',
                'data'      => $tax->first()
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
     * @param  \App\Models\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tax $tax)
    {
        try {

            $isDeleted = $tax->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Tax!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Tax Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }



}
