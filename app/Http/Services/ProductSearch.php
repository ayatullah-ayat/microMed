<?php 
namespace App\Http\Services;

use Exception;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductTag;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Models\Custom\CustomServiceProduct;

trait ProductSearch 
{
    


    private function ecommerceProduct($query=null, $filter= "created_at"){

        $result = [];
        if($query){

            $q = Product::orWhere('products.product_name', 'LIKE', "%{$query}%")
                ->leftJoin('product_tags', 'product_tags.product_id', '=', 'products.id')
                ->leftJoin('product_brand', 'product_brand.product_id', '=', 'products.id')
                ->orWhere('product_tags.tag_name', 'LIKE', "%{$query}%")
                ->orWhere('product_brand.brand_name', 'LIKE', "%{$query}%")
                ->orWhere('products.category_name', 'LIKE', "%{$query}%")
                ->orWhere('products.subcategory_name', 'LIKE', "%{$query}%")
                ->where('products.is_publish', 1)
                ->where('products.is_active', 1);

            if(preg_match("/max_price|max|max_/im", $filter)){
                $q->selectRaw('*, max(unit_price) max_price')
                // selectRaw('*, max( (total_product_unit_price - (total_product_unit_price * (product_discount / 100) )) / total_product_qty ) max_price')
                ->orderByDesc('max_price');
            }else if(preg_match("/created_at|new|newest/im", $filter)){
                $filter = "created_at";
                $q->orderByDesc("products.{$filter}");
            }
            
            $products = $q->groupBy('products.id')
                    ->orderBy('products.product_name')
                    ->orderBy('products.is_best_sale')
                    ->get();

            if(count($products)){
                $result = $products;
            }
            
        }
        
        return $result;

    }


    private function customizeProduct($query=null, $filter = "created_at"){

        $result = [];
        if($query){

            $products= CustomServiceProduct::orWhere('product_name', 'LIKE', "%{$query}%")
                    ->orWhere('category_name', 'LIKE', "%{$query}%")
                    ->orWhere('product_name', 'LIKE', "%{$query}%")
                    ->where('is_active', 1)
                    ->orderBy('product_name')
                    ->orderBy("{$filter}")
                    ->get();

            if(count($products)){
                $result = $products;
            }

            
        }
        
        return $result;

    }


    private function productCookies(){
        $userId = null;

        if (auth()->check()) {
            $userId = auth()->user()->id;
        }

        $productIds= Cookie::get('productIds');
        $cartQtys  = Cookie::get('cartQtys');
        $wishLists = Cookie::get('wishLists' . $userId);

        if (!is_null($productIds)) {

            $pureData = $this->protectBadData($productIds);

            if ($pureData !== false) {
                $productIds = $pureData;
            }

            if (is_null($productIds)) {
                $productIds = [];
            }
        } else {
            $productIds = [];
        }

        if (!is_null($cartQtys)) {

            $pureData = $this->protectBadData($cartQtys);

            if ($pureData !== false) {
                $cartQtys = $pureData;
            }

            if (is_null($cartQtys)) {
                $cartQtys = [];
            }
        } else {
            $cartQtys = [];
        }

        if (!is_null($wishLists)) {

            $pureData = $this->protectBadData($wishLists);

            if ($pureData !== false) {
                $wishLists = $pureData;
            }

            if (is_null($wishLists)) {
                $wishLists = [];
            }
        } else {
            $wishLists = [];
        }

        return [
            'productIds'    => $productIds,
            'wishLists'     => $wishLists,
            'cartQtys'      => $cartQtys,
        ];
    }



    private function protectBadData($reqdata)
    {
        $data = preg_replace_callback(
            '/s:(\d+):"(.*?)";/',
            function ($m) {
                return 's:' . strlen($m[2]) . ':"' . $m[2] . '";';
            },
            $reqdata
        );

        return @unserialize($data);
    }



    private function renderProduct($products, $lastId=null){
        try {
            //

            $cookieData     = $this->productCookies();
            $lastId         = 0;
            $isLastRecord   = false;
            $lastData       = Product::first();
            if ($lastData && !$lastId) {
                $lastId = $lastData->id;
            }

            $maxId          = 0;
            $html           = "";

            foreach ($products as $item) :

                $maxId = $item->id;

                if ($lastId == $maxId) $isLastRecord = true;

                $isInwish       = in_array($item->id, $cookieData['wishLists']) ? 'removeFromWish' : 'addToWish';
                $isInCart       = !in_array($item->id, $cookieData['productIds']) ? 'openCartModal' : 'alreadyInCart';
                $cartContent    = !in_array($item->id, $cookieData['productIds']) ? 'Add to Car' : '<span>Add to Cart</span>';
                $userId         = auth()->user()->id ?? null;
                $thumbnail      = asset($item->product_thumbnail_image);
                $route          = route('product_detail', $item->id);
                $route2         = route('checkout_index', $item->id);
                $unitprice      = $item->unit_price ?? 0.0;
                $salesPrice     = salesPrice($item) ?? 0.0;

                $product_name = \Str::limit($item->product_name, 15);

                $discountContent = "";
                if ($item->product_discount) {
                    $discountContent .= "<span class=\"text-decoration-line-through text-danger\"> {$unitprice} /=</span>";
                }

                $buttonCentent = "";

                if ($item->total_stock_qty > 0) :
                    $buttonCentent .= "<button type=\"button\" data-productid=\"{$item->id}\" class=\"btn btn-sm btn-secondary btn-card {$isInCart}\"> {$cartContent}</button>
                        <a href=\"{$route2}\" type=\"button\" class=\"btn btn-sm btn-danger\"> Order Now </a>";
                else :
                    $buttonCentent .= "<span class=\"text-danger\">Out of Stock</span>";
                endif;

                $html .= "<div class=\"col-md-3 col-sm-4 col-6\">
                            <div class=\"card __product-card\">
                                <div class=\"card-wishlist {$isInwish}\" data-auth=\"{$userId}\" data-productid=\"{$item->id}\" style=\"z-index: 100;\" type=\"button\"> <i class=\"fa-solid fa-heart\"></i></div>
                                <a href=\"{$route}\">
                                    <img draggable=\"false\" src=\"{$thumbnail}\" class=\"card-img-top\" alt=\"...\">
                                </a>
                                <div class=\"card-body p-0\">
                                    <div class=\"card-product-title card-title text-center fw-bold\">
                                        <a href=\"{$route}\" class=\"text-decoration-none text-dark\"><h5 data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$item->product_name}\">{$product_name}</h5></a>
                                    </div>

                                    <div class=\"card-product-price card-text text-center fw-bold\">
                                        <h5>Sale Price {$salesPrice} /= {$discountContent} </h5>
                                    </div>
                                    <div class=\"card-product-button d-flex justify-content-evenly\">
                                        {$buttonCentent}
                                    </div>
                                </div>
                            </div>
                        </div>
                    ";

            endforeach;


            return [
                'html'   => $html,
                'max_id' => $maxId,
                'isLast' => $isLastRecord
            ];

        } catch (\Throwable $th) {

            return [
                'html'   => null,
                'max_id' => 0,
                'isLast' => false
            ];
        }
    }


    private function renderCustomizeProduct($cProducts){

        $html = "";
        foreach ($cProducts as $item) {

            if ($item->product_thumbnail): 
                $imageSrc = asset($item->product_thumbnail);
                $route = route('customize.customorder_show', $item->id);
                $productName = $item->product_name ?? 'N/A';
                $html .= "
                    <div class=\"card card-box customize-product-box\">
                        <a href=\"{$route}\">
                            <div class=\"modal-card text-center\">
                                <img src=\"{$imageSrc}\" class=\"pt-3\" alt=\"Product Image\">
                                <p> {$productName} </p>
                            </div>
                        </a>
                    </div>
                    ";
            endif;
        }

        return $html;
    }
    





}