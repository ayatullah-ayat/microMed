<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Services\ProductSearch;
use App\Models\HomeBanner;
use App\Models\ProductColor;
use App\Models\Shop;

class ShopController extends Controller
{
    use ProductSearch;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $maxId = 0;
    public $limit = 20;
     
    public function index()
    {
        $maxId    = request()->max_id ?? $this->maxId;
        $limit    = request()->limit ?? $this->limit;
        $operator = request()->operator ?? '<';
        $filters  = request()->filter ?? null;

        $productSql   = Product::inRandomOrder()
                    ->where('is_active', 1)
                    ->where('is_publish', 1);
        if($maxId){
            $productSql->where('id', $operator, $maxId);
        }

        $products = $productSql
                    ->take($limit)
                    ->get();
        
        if (request()->ajax()){
            
            $response = $this->renderProduct($products);

            return response()->json([
                'html'      => $response['html'],
                'max_id'    => $response['max_id'],
                'isLast'    => $response['isLast']
            ]);
        }

       $categories = Category::with('subCategories')
                    ->where('is_active', 1)
                    // ->orderByDesc('id')
                    ->get();
        $productColors = Variant::orderByDesc('id')
                ->where('variant_type', 'color')
                ->where('is_active', 1)
                ->get();

        $productSize = Variant::orderByDesc('id')
                ->where('variant_type', 'size')
                ->where('is_active', 1)
                ->get();

        $tags = ProductTag::groupBy('tag_name')->get();


       $maxSalesPrice = Product::
                    selectRaw('max(total_product_unit_price / total_product_qty ) max_sale_price')
                    ->where('is_active', 1)
                    ->where('is_publish', 1)
                    ->first();
        $countProducts = Product::where('is_active', 1)->where('is_publish', 1)->count();

        $shopBanner = Shop::first();

        return view('frontend.pages.shop', compact('products', 'shopBanner', 'countProducts', 'limit' ,'tags', 'categories' , 'productColors' , 'productSize', 'maxSalesPrice'));
    }


    public function getVariants(Request $request){
        $product = Product::where('id', $request->product_id)->first();
        
        return response()->json([
            'product' => $product,
            'colors' => $product->productColors,
            'sizes' => $product->productSizes,
        ]);

    }
    public function ajaxFilter()
    {
        $limit                  = $this->limit;
        $maxId                  = request()->max_id;  //min record
        $operator               = request()->operator ?? '<';

        $category_ids           = request()->category_ids ?? null;
        $sub_category_ids       = request()->sub_category_ids ?? null;
        $tags                   = request()->tags ?? null;
        $colors                 = request()->colors ?? null;
        $sizes                  = request()->sizes ?? null;
        $prices                 = request()->prices ?? null;

        $q = Product::selectRaw('products.*, 
                product_variant_prices.color_name as v_color_name, 
                product_color.color_name,
                product_variant_prices.size_name as v_size_name, 
                product_size.size_name
            ')
        ->where('products.is_active', 1)->where('products.is_publish', 1);

        if($category_ids){
            $q->whereIn('products.category_id', $category_ids);
        }

        if($sub_category_ids){
            $q->whereIn('products.subcategory_id', $sub_category_ids);
        }

        if($prices){
            $q->whereBetween(DB::raw('products.total_product_unit_price / products.total_product_qty'),[$prices['minPrice'], $prices['maxPrice']]);
        }


        $joinType = $colors && count($colors) ? 'join' : 'leftJoin';
        $joinType2 = $sizes && count($sizes) ? 'join' : 'leftJoin';
        $joinType3 = $tags && count($tags) ? 'join' : 'leftJoin';


        if($tags){
            $q->{$joinType3}('product_tags', 'product_tags.product_id','=', 'products.id')
            ->whereIn('product_tags.tag_name', $tags);
        }

        $q->{$joinType}('product_color', function ($join) use($colors) {
            $join->on('products.id', '=', 'product_color.product_id')
            ->where('products.is_product_variant', '=', 0);
            if($colors && count($colors)){
                $join->whereIn('product_color.color_name', $colors);
            }
        });

        $q->{$joinType2}('product_size', function ($join) use($sizes) {
            $join->on('products.id', '=', 'product_size.product_id')
            ->where('products.is_product_variant', '=', 0);

            if ($sizes && count($sizes)) {
                $join->whereIn('product_size.size_name', $sizes);
            }

        });


        $q->leftJoin('product_variant_prices', function ($join) use ($colors, $sizes) {
            $join->on('products.id', '=', 'product_variant_prices.product_id')
            ->where('products.is_product_variant', '=', 1);
            if ($colors && count($colors)) {
                $join->whereIn('product_variant_prices.color_name', $colors);
            }

            if ($sizes && count($sizes)) {
                $join->whereIn('product_variant_prices.size_name', $sizes);
            }

        });


        if ($maxId) {
            $q->where('products.id', $operator, $maxId);
        }

        $allProducts = $q->groupBy('products.id')->orderByDesc('products.id')->get();

        $products = $q->groupBy('products.id')
        // ->orderByDesc('products.created_at')
        ->orderByDesc('products.id')
        ->orderBy('products.product_name')
        ->orderBy('products.is_best_sale')
        ->limit($limit)
        ->get();


        if (request()->ajax()) {

            $lastId   =  0;
            if( $count = count($allProducts) ){
                $lastId = $allProducts[$count-1]->id ?? 0;
            }

            $response = $this->renderProduct($products, $lastId);

            return response()->json([
                'html'      => $response['html'],
                'max_id'    => $response['max_id'],
                'isLast'    => $response['isLast'],
                'totalCount'=> count($allProducts) ?? 0
            ]);
        }

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
