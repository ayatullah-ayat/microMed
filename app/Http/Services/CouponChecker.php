<?php 
namespace App\Http\Services;

use Exception;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ApplyCoupon;
use App\Models\CustomerType;
use Illuminate\Support\Facades\Cookie;

trait CouponChecker
{
  
    private function checkCouponValidation($coupon, $order){
        try {
            
            if(!$coupon)
                throw new Exception("Invalid Request!", 403);

            $couponData = Coupon::where('coupon_code',$coupon)->first();
            if(!$couponData)
                throw new Exception("Coupon Not Found!", 404);

            if(!$couponData->status)
                throw new Exception("Coupon: {$coupon}, Not Active!", 403);

            if(date('Y-m-d', strtotime($couponData->coupon_validity)) < date('Y-m-d'))
                throw new Exception("Coupon: {$coupon}, Not Valid!", 403);


            if($couponData->usage_limit && $this->getPrevCustomerOrder($coupon) >= $couponData->usage_limit )
                throw new Exception("You can't use the {$coupon}, due to limitation!", 403);

            if(!count($order))
                throw new Exception("Error occured to make order! ", 403);

            if(!array_key_exists('grand_total', $order))
                throw new Exception("Error occured to make order! ", 403);

            $totalBill = floatval($order['grand_total']);

            if($couponData->min_bill_limit && $totalBill < $couponData->min_bill_limit )
                throw new Exception("Bill Amount shuold be >= {$couponData->min_bill_limit}!", 403);

            if($couponData->max_bill_limit && $totalBill > $couponData->max_bill_limit )
                throw new Exception("Bill Amount shuold be <= {$couponData->max_bill_limit}!", 403);

            return [
                'success'   => true,
                'msg'       => 'Success',
                'data'      => $couponData
            ];

        } catch (\Throwable $th) {
            return [
                'success'   => false,
                'msg'       => $th->getMessage(),
                'code'      => $th->getCode()
            ];
        }
    }

    private function getPrevCustomerOrder($coupon){
        return Order::where('customer_id', auth()->guard('web')->user()->id)
        ->where('coupon_code', $coupon)->count();
    }


    private function checkCouponItemsValidation($couponData, $order){
        try {

            
            if(!$couponData)
                throw new Exception("Coupon Not Found!", 403);

            $patternCat     = "/category/im";
            $patternProduct = "/exclude|include/im";
            $products       = $order['products'];
            $total_discount_amount = 0;
            $total_discount_price = 0;
            $couponProducts = [];


            if(preg_match($patternProduct,$couponData->coupon_type)){

                foreach ($products as $product) {

                    $product_id = $product['product_id'] ?? null;
                    $couponProduct = ApplyCoupon::where('product_id', $product_id)
                                    ->where('coupon_code', $couponData['coupon_code'])
                                    ->first();
                    // dd($couponProduct);
                    if(preg_match("/include/im",$couponData->coupon_type) && $couponProduct){
                        $product_price      = floatval($product['sales_price']);
                        $coupon_discount    = floatval($couponData['coupon_discount']);
                        $discount_type      = $couponData['discount_type'];
                        $discount_amount    = $coupon_discount;

                        if(preg_match("/parcent|parcentage|percent/im",$discount_type)){
                            $discount_amount    = $product_price *  $discount_amount / 100;
                        }

                        $product['coupon_price'] = round(($discount_amount) * (int)$product['qty']);
                        $total_discount_price += round(($product_price - $discount_amount) * (int)$product['qty']);
                        $total_discount_amount += round(($discount_amount) * (int)$product['qty']);
                        $couponProducts[]=$product;
                            
                    }elseif(preg_match("/exclude/im",$couponData->coupon_type) && !$couponProduct){
                        $product_price      = floatval($product['sales_price']);
                        $coupon_discount    = floatval($couponData['coupon_discount']);
                        $discount_type      = $couponData['discount_type'];
                        $discount_amount    = $coupon_discount;

                        if(preg_match("/parcent|parcentage|percent/im",$discount_type)){
                            $discount_amount    = $product_price *  $discount_amount / 100;
                        }

                        $product['coupon_price'] = round(($discount_amount) * (int)$product['qty']);
                        $total_discount_price += round(($product_price - $discount_amount) * (int)$product['qty']);
                        $total_discount_amount += round(($discount_amount) * (int)$product['qty']);
                        $couponProducts[] = $product;
                    }

                }
            }elseif(preg_match($patternCat,$couponData->coupon_type)){

                foreach ($products as $product) {

                    $product_id = $product['product_id'] ?? null;
                    $product    =  Product::find($product_id);
                    if(!$product)
                        throw new Exception("Invalid Coupon!", 403);

                    $couponCategory = ApplyCoupon::where('category_id', $product->category_id)->first();
                    if(!$couponCategory) continue;

                    $product_price      = floatval($product['sales_price']);
                    $coupon_discount    = floatval($couponData['coupon_discount']);
                    $discount_type      = $couponData['discount_type'];
                    $discount_amount    = $coupon_discount;

                    if(preg_match("/parcent|parcentage|percent/im",$discount_type)){
                        $discount_amount    = $product_price *  $discount_amount / 100;
                    }

                    $product['coupon_price'] = round(($discount_amount) * (int)$product['qty']);

                    $total_discount_price += round(($product_price - $discount_amount) * (int)$product['qty']);
                    $total_discount_amount += round(($discount_amount) * (int)$product['qty']);
                    $couponProducts[] = $product;
                }
            }

            $order['total_discount_amount'] = $total_discount_amount;
            $order['total_discount_price']  = $total_discount_price;
            $order['coupon_products']       = $couponProducts;

            return [
                'success'   => true,
                'msg'       => 'Success',
                'data'      => $order,
            ];

        } catch (\Throwable $th) {
            return [
                'success'   => false,
                'msg'       => $th->getMessage(),
                'code'      => $th->getCode(),
                'data'      => null
            ];
        }
    }


}