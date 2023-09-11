<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebFooterRequest;
use App\Models\WebFooter;
use Exception;
use Illuminate\Http\Request;
use App\Http\Services\ImageChecker;
use App\Models\ShippingCharge;

class ShippingChargeController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippingCharge = ShippingCharge::orderByDesc('id')->get();
        return view('backend.pages.cms_settings.shipping-charge', compact('shippingCharge'));
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

            $shipping_location      = $request->shipping_location;
            $amount                 = $request->amount;
            $data                   = $request->all();

            $category = ShippingCharge::create($data);
            if(!$category)
                throw new Exception("Unable to create Shipping Charge!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Shipping Charge Created Successfully!',
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
    public function update(Request $request, ShippingCharge $shippingcharge)
    {
        try {

            if(!$shippingcharge)
                throw new Exception("No record Found!", 404);
            $data           = $request->all();

            $footerStatus = $shippingcharge->update($data);
            if(!$footerStatus)
                throw new Exception("Unable to Update Shipping Charge!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Shipping Charge Updated Successfully!'
            ]);
                
        } catch (\Exception $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingCharge $shippingcharge)
    {
        try {

            $isDeleted = $shippingcharge->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Footer About!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Shipping Charged Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


}
