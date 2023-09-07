<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\CurrencyRequest;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = Currency::orderByDesc('id')->get();
        return view('backend.pages.currency.currencylist', compact('currencies'));
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
    public function store(CurrencyRequest $request)
    {
        try {
            $data  = $request->all();
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $tax   = Currency::create($data);
            if(!$tax)
                throw new Exception("Unable to create Currency!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Currency Created Successfully!',
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
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        try {

            $data            = $request->all();
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;
            $currencystatus  = $currency->update($data);
            if(!$currencystatus)
                throw new Exception("Unable to Update Tax!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Tax Updated Successfully!',
                'data'      => $currency->first()
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
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        try {

            $isDeleted = $currency->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Currency!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Currency Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


}
