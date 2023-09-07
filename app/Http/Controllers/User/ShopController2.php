<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\ProductSearch;
use Illuminate\Support\Facades\Cookie;

class ShopController2 extends Controller
{
    use ProductSearch;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     
    public function index()
    {
        dd('shopController2');
        $maxId    = request()->max_id ?? 0;
        $limit    = request()->limit ?? 20;
        $operator = request()->operator ?? '<';

        $productSql   = Product::orderByDesc('created_at')
                    ->where('is_active', 1)
                    ->where('is_publish', 1);
        if($maxId){
            $productSql->where('id', $operator, $maxId);
        }

        $products = $productSql->latest()
                    ->take($limit)
                    ->get();

        $cookieData = $this->productCookies();

        if (request()->ajax()){
            $lastId         = 0;
            $isLastRecord   = false;
            $lastData       = Product::first();
            if ($lastData) {
                $lastId = $lastData->id;
            }

            $maxId          = 0;
            $html           = "";


            foreach ($products as $item) :

                $maxId = $item->id;

                if ($lastId == $maxId) $isLastRecord = true;

                    $isInwish       = in_array($item->id, $cookieData['wishLists']) ? 'removeFromWish' : 'addToWish';
                    $isInCart       = !in_array($item->id, $cookieData['productIds']) ? 'addToCart' : 'alreadyInCart';
                    $cartContent    = !in_array($item->id, $cookieData['productIds']) ? 'কার্ডে যুক্ত করুন' : '<span>অলরেডি যুক্ত আছে</span>';
                    $userId         = auth()->user()->id ?? null;
                    $thumbnail      = asset($item->product_thumbnail_image);
                    $route          = route('product_detail', $item->id);
                    $route2         = route('checkout_index', $item->id);
                    $unitprice      = 0.0;
                    $salesPrice     = salesPrice($item) ?? 0.0;

                    if ( $item->total_product_unit_price && $item->total_product_qty ):
                        $totalprice = $item->total_product_unit_price;
                        $totalqty   = $item->total_product_qty;
                        $unitprice  = ($totalprice / $totalqty);
                    endif; 


                    $discountContent = "";
                    if($item->product_discount){
                        $discountContent .= "<span class=\"text-decoration-line-through text-danger\"> {$unitprice} /=</span>";
                    }

                    $buttonCentent = "";

                    if($item->total_stock_qty > 0):
                        $buttonCentent .= "<button type=\"button\" data-productid=\"{$item->id}\" class=\"btn btn-sm btn-secondary btn-card {$isInCart}\"> {$cartContent}</button>
                        <a href=\"{$route2}\" type=\"button\" class=\"btn btn-sm btn-danger\"> অর্ডার করুন </a>";
                    else:
                        $buttonCentent .= "<span class=\"text-danger\">Out of Stock</span>";
                    endif; 

                    $html .= "<div class=\"mb-3\">
                            <div class=\"card __product-card\">
                                <div class=\"card-wishlist {$isInwish}\" data-auth=\"{$userId}\" data-productid=\"{$item->id}\" style=\"z-index: 100;\" type=\"button\"> <i class=\"fa-solid fa-heart\"></i></div>
                                <a href=\"{$route}\">
                                    <img draggable=\"false\" src=\"{$thumbnail}\" class=\"card-img-top\" alt=\"...\">
                                </a>
                                <div class=\"card-body p-0\">
                                    <div class=\"card-product-title card-title text-center fw-bold\">
                                        <a href=\"{$route}\" class=\"text-decoration-none text-dark\"><h5>{$item->product_name}</h5></a>
                                    </div>

                                    <div class=\"card-product-price card-text text-center fw-bold\">
                                        <h5>বর্তমান মূুল্য {$salesPrice} /= {$discountContent} </h5>
                                    </div>
                                    <div class=\"card-product-button d-flex justify-content-evenly\">
                                        {$buttonCentent}
                                    </div>
                                </div>
                            </div>
                        </div>
                    ";

            endforeach;

            return response()->json([
                'html'  => $html,
                'max_id' => $maxId,
                'isLast' => $isLastRecord
            ]);

        }


        $countProducts = Product::where('is_active', 1)->where('is_publish', 1)->count();

        return view('frontend.pages.shop', compact('products', 'countProducts', 'limit'));
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
    public function show(Product $product, $slug=null)
    {
        dd('ShopController2 Show');

        if(!$product) abort(404, "Product Not Found!");

        $otherProducts = Product::where('category_id', $product->category_id)
                        ->where('id','!=', $product->id)
                        ->where('is_active', 1)
                        ->where('is_publish', 1)
                        ->get();


        return view('frontend.pages.product_detail', compact('product', 'otherProducts'));
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
