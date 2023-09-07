<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function addToWish(Request $request)
    {

      try {

            $customer_id = $request->customer_id;

            if (!$customer_id)
                throw new Exception("Invalid Request!", 403);

            $isAuthcustomer = User::find($customer_id); 
            if(!$isAuthcustomer)
                throw new Exception("Please Create An Account!", 403);
                
            $wishLists = Cookie::get('wishLists'. $customer_id);

            if (!is_null($wishLists)) {
                $wishLists = unserialize($wishLists);
            }

            if (is_array($wishLists) && (count($wishLists) > 0)) {

                if (!in_array($request->productId, $wishLists)) {
                    array_push($wishLists, $request->productId);
                }

                $data = serialize($wishLists);
            } else {
                $data = serialize([$request->productId]);
            }

            Cookie::queue('wishLists'. $customer_id, $data, 3600 * 30);

            return response()->json($wishLists);

      } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'msg'     => $th->getMessage()
            ]);
      }

    }



    public function removeFromWish(Request $request)
    {
       try {

            $customer_id = $request->customer_id;

            if (!$customer_id)
                throw new Exception("Invalid Request!", 403);

            $isAuthcustomer = User::find($customer_id);
            if (!$isAuthcustomer)
                throw new Exception("Please Create An Account!", 403);

            $wishLists = Cookie::get('wishLists' . $customer_id);
            $data = [];
            if (!is_null($wishLists)) {
                $wishLists = unserialize($wishLists);
                if (($key = array_search($request->productId, $wishLists)) !== false) {
                    unset($wishLists[$key]);
                }

                $data = serialize($wishLists);
            }


            Cookie::queue(Cookie::forget('wishLists'. $customer_id));

            Cookie::queue('wishLists'. $customer_id, $data, 3600 * 30);

            return response()->json($wishLists);

       } catch (\Throwable $th) {
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
}
