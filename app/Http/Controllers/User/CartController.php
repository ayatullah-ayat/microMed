<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cartProducts=null;
        $productIds = Cookie::get('productIds');
        if (!is_null($productIds)) {
            $productIds     = unserialize($productIds);
            $uniqueProducts = array_unique($productIds);
            $cartProducts   = Product::whereIn('id', $uniqueProducts)->get();
            // dd($cartProducts);
        }

        return view('frontend.pages.cart', compact('cartProducts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCartProductQty(Request $request)
    {
        //cartQtys
        $cartQtys = $request->cartQtys;
        Cookie::queue('cartQtys', serialize($cartQtys), 60);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function addToCart(Request $request)
    {

        // $tracker = Tracker::hit($request->productId);

        $productIds = Cookie::get('productIds');

        if (!is_null($productIds)) {
            $productIds = unserialize($productIds);
        }

        if (is_array($productIds) && (count($productIds) > 0)) {

            if(!in_array($request->productId, $productIds)){
                array_push($productIds, $request->productId);
            }

            $data = serialize($productIds);
        } else {
            $data = serialize([$request->productId]);
        }

        Cookie::queue('productIds', $data, 3600 * 10);

        return response()->json($productIds);

    }



    public function removeFromCart(Request $request)
    {

        $productIds = Cookie::get('productIds');
        $data = [];
        if (!is_null($productIds)) {
            $productIds = unserialize($productIds);
            if (($key = array_search($request->productId, $productIds)) !== false) {
                unset($productIds[$key]);
            }
            $data = serialize($productIds);
        }


        Cookie::queue(Cookie::forget('productIds'));

        Cookie::queue('productIds', $data, 3600 * 10);

        return response()->json($productIds);
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
