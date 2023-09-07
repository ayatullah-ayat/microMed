<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Exception;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::orderByDesc('id')->get();
        return view('backend.pages.coupon.managecoupon', compact('coupons'));
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
    public function store(CouponRequest $request)
    {
        try {
            $data               = $request->all();
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $coupon   = Coupon::create($data);
            if(!$coupon)
                throw new Exception("Unable to create Coupon!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Coupon Created Successfully!',
                'data'      => $coupon
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
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        try {

            $data               = $request->all();
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;

            $couponStatus   = $coupon->update($data);
            if(!$couponStatus)
                throw new Exception("Unable to Update Coupon!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Coupon Updated Successfully!',
                'data'      => $coupon->first()
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
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        try {

            $isDeleted = $coupon->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete coupon!", 403);

            $coupon->applycoupons()->delete();
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Coupon Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


}
