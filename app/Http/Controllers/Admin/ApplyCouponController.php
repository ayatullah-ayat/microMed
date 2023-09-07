<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\ApplyCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApplyCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::with('applycoupons')
                ->where('status', 1)
                ->whereDate('coupon_validity','>=', date('Y-m-d'))
                ->orderByDesc('id')
                ->get();

        $applycoupons = ApplyCoupon::groupBy('coupon_id')->get();

        return view('backend.pages.coupon.applycoupon', compact('coupons', 'applycoupons'));
    }

    public function searchProduct(){

        $productName = request('term')['term'] ?? null;

        if(!$productName) return;

        $products = Product::where('is_active', 1)
                    ->where('is_publish', 1)
                    ->where('product_name', 'like', "%{$productName}%")
                    ->groupBy('id')
                    ->orderByDesc('id')
                    ->get();

        return response()->json($products);
    }

    public function searchProductCategory(){

        $categoryName = request('term')['term'] ?? null;

        if(!$categoryName) return;

        $products = Product::groupBy('category_id')
                    ->where('is_active', 1)
                    ->where('is_publish', 1)
                    ->where('category_name', 'like', "%{$categoryName}%")
                    ->orderByDesc('id')
                    ->get();

        return response()->json($products);
    }


    public function getCouponData(Request $request){

        $coupon_id  = request('coupon_id');
        $coupon_type= request('coupon_type');

        $applycoupons = ApplyCoupon::where('coupon_id', $coupon_id)
                    ->orderByDesc('created_at')
                    ->get();

        return response()->json($applycoupons);
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

            $data = $request->all();

            if(!array_key_exists('coupon_id',$data))
                throw new Exception("Invalid Request!", 403);

            $coupon = Coupon::with('applycoupons')
                        ->where('status', 1)
                        ->where('id', $data['coupon_id'])
                        ->whereDate('coupon_validity', '>=', date('Y-m-d'))
                        ->orderByDesc('id')
                        ->first();

            if(!$coupon)
                throw new Exception("No Coupon Found!", 403);
                

            if(isset($coupon->applycoupons) && count($coupon->applycoupons))
                throw new Exception("{$coupon->coupon_name}, Already Applied!", 403);
                

            $updatedData =[];

            if(preg_match("/category/im", $coupon->coupon_type)){
                if(isset($data['category_id']) && !count($data['category_id']))
                    throw new Exception("Please Try again!", 403);

                foreach ($data['category_id'] as $category_id) {
                    $category = Category::find($category_id);
                    if(!$category)
                        throw new Exception("Oops! No category Found!", 403);
                        
                    $updatedData[]=[
                        'coupon_id'     => $coupon->id,
                        'coupon_code'   => $coupon->coupon_code,
                        'category_id'   => $category_id,
                        'category_name' => $category->category_name,
                        'applied_by'    => auth()->guard('admin')->user()->id ?? null,
                    ];
                }
                    
            }

            if(preg_match("/include|exclude/im", $coupon->coupon_type)){
                foreach ($data['product_id'] as $product_id) {
                    $product = Product::find($product_id);
                    if (!$product)
                        throw new Exception("Product not Found!", 403);

                    $updatedData[] = [
                        'coupon_id'    => $coupon->id,
                        'coupon_code'  => $coupon->coupon_code,
                        'product_id'   => $product->id,
                        'product_name' => $product->product_name,
                        'applied_by'   => auth()->guard('admin')->user()->id ?? null,
                    ];
                }
            }

            DB::beginTransaction();

            $coupon->applycoupons()->createMany($updatedData);

            DB::commit();

            return response()->json([
                'success'   => true,
                'msg'       => 'Coupon Applied Successfully!'
            ]);

        } catch (\Throwable $th) {

            DB::rollback();

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
     * @param  \App\Models\ApplyCoupon  $applyCoupon
     * @return \Illuminate\Http\Response
     */
    public function show(ApplyCoupon $applyCoupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApplyCoupon  $applyCoupon
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplyCoupon $applyCoupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ApplyCoupon  $applyCoupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        try {

            $data = $request->all();

            if (!array_key_exists('coupon_id', $data))
                throw new Exception("Invalid Request!", 403);

            if (!$coupon)
                throw new Exception("No Coupon Found!", 403);

            if (!$coupon->status)
                throw new Exception("{$coupon->coupon_name}, Currently InActive!", 403);

            if (date('Y-m-d', strtotime($coupon->coupon_validity)) <  date('Y-m-d'))
                throw new Exception("{$coupon->coupon_name}, Expired!", 403);

            $updatedData = [];

            $appliedBy = $coupon->applycoupons && count($coupon->applycoupons) ? $coupon->applycoupons[0]->applied_by : (auth()->guard('admin')->user()->id ?? null);
            
            if (preg_match("/category/im", $coupon->coupon_type)) {
                if (isset($data['category_id']) && !count($data['category_id']))
                throw new Exception("Please Try again!", 403);


                foreach ($data['category_id'] as $category_id) {
                    $category = Category::find($category_id);
                    if (!$category)
                        throw new Exception("Oops! No category Found!", 403);

                    $updatedData[] = [
                        'coupon_id'     => $coupon->id,
                        'coupon_code'   => $coupon->coupon_code,
                        'category_id'   => $category_id,
                        'category_name' => $category->category_name,
                        'applied_by'    => $appliedBy,
                        'updated_by'    => auth()->guard('admin')->user()->id ?? null,
                    ];
                }
            }

            if (preg_match("/include|exclude/im", $coupon->coupon_type)) {
                foreach ($data['product_id'] as $product_id) {
                    $product = Product::find($product_id);
                    if (!$product)
                        throw new Exception("Product not Found!", 403);

                    $updatedData[] = [
                        'coupon_id'    => $coupon->id,
                        'coupon_code'  => $coupon->coupon_code,
                        'product_id'   => $product->id,
                        'product_name' => $product->product_name,
                        'applied_by'   => $appliedBy,
                        'updated_by'   => auth()->guard('admin')->user()->id ?? null,
                    ];
                }
            }

            DB::beginTransaction();

            $coupon->applycoupons()->delete();
            $coupon->applycoupons()->createMany($updatedData);

            DB::commit();

            return response()->json([
                'success'   => true,
                'msg'       => 'Coupon Re-Applied Successfully!'
            ]);
        } catch (\Throwable $th) {

            DB::rollback();

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
     * @param  \App\Models\ApplyCoupon  $applyCoupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        try {

            $isDeleted = $coupon->applycoupons()->delete();
            if (!$isDeleted)
                throw new Exception("Unable to delete!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'All Applied Coupon Deleted Successfully!',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }
}
