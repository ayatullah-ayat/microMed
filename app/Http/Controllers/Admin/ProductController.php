<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Currency;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use App\Exports\ProductDataExport;
use Illuminate\Support\Facades\DB;
use App\Http\Services\ImageChecker;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Services\ProductChecker;
use PDF;

class ProductController extends Controller
{
    use ImageChecker, ProductChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('is_active', 1)->where('is_publish',1)->get();
        return view('backend.pages.product.manageproduct', compact('products'));
    }

    public function unPublishProducts()
    {
        $products = Product::where('is_active', 1)->where('is_publish',0)->get();
        return view('backend.pages.product.unpublish', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::select('category_name','id')->where('is_active',1)->get();
        $brands     = Brand::select('brand_name','id')->where('is_active',1)->get();
        $units      = Unit::select('unit_name','id')->where('is_active',1)->get();
        $currencies = Currency::select('currency_name','id')->where('is_active',1)->get();
        $colors     = Variant::select('variant_name','id')->where([ ['is_active', 1], ['variant_type', 'color']])->get();
        $sizes      = Variant::select('variant_name','id')->where([ ['is_active', 1], ['variant_type', 'size']])->get();

        return view('backend.pages.product.addproduct', compact('categories', 'brands', 'units', 'currencies','colors','sizes'));
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


            $data                       = $request->all();
            $product_thumbnail_image    = $request->product_thumbnail_image;
            $product_gallery            = $request->product_gallery;
            $fileLocation               = null;
            $product_thumbnail_image    = $product_thumbnail_image && count(json_decode($product_thumbnail_image)) > 0 ? json_decode($product_thumbnail_image)[0] : null;
            $product_gallery_images     = $product_gallery && count(json_decode($product_gallery)) > 0 ? json_decode($product_gallery) : null;

            if ($product_thumbnail_image) {
                $fileResponse = $this->uploadFile($product_thumbnail_image, 'products/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
                $data['product_thumbnail_image'] = $fileLocation;
            }

            $data['product_gallerys']=[];
            if ($product_gallery_images) {

                foreach ($product_gallery_images as $image) {
                    $fileResponse = $this->uploadFile($image, 'products/');
                    if (!$fileResponse['success'])
                        throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);
    
                    $fileLocation = $fileResponse['fileLocation'];
                    $data['product_gallerys'][]= ['product_image' => $fileLocation];
                }
            }

            DB::beginTransaction();

            // create product 
            $productData = $this->createProduct($data);

            // dd($productData);
            if(!$productData['success'])
                throw new Exception($productData['msg'] ?? "Unable to Create Product!", 403);

            $purchase_product_id= $request->purchase_product_id;

            if($purchase_product_id){
                $purchase_product = PurchaseProduct::find($purchase_product_id);
                $purchase_product->increment('stocked_qty', $request->product_qty);
                $purchase_product->update(['product_id' => $productData['data']['id'] ?? null ]);

                $purchase_product->purchase()->update([
                    'is_manage_stock' => 1
                ]); 
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'msg'       => $productData['msg'] ?? 'Success!',
                'data'      => $productData['data'] ?? null
            ]);
                
        } catch (\Exception $th) {

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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // $singleproducts = Product::find($product);
        // dd($singleproducts);
        return view('backend.pages.product.viewproduct', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        // dd($product->category->subCategories);
        // dd($product->sizes);
        $tags = [];
        $selectedSizes  = [];
        $selectedColors = [];
        $unitPrice      = null;
        $salesPrice     = null;
        $wholesalesPrice= null;

        if(isset($product->singleProductTags)){
            foreach ($product->singleProductTags as $tag) {
                $tags[]= $tag->tag_name;
            }
        }

        if(isset($product->sizes)){
            foreach ($product->sizes as $size) {
                $selectedSizes[]= $size->size_name;
            }
        }

        // dd($product->productSizes);

        if(isset($product->productSizes)){
            foreach ($product->productSizes as $size) {
                $selectedSizes[]= $size->size_name;
            }
        }

        if(isset($product->productColors)){
            foreach ($product->productColors as $color) {
                $selectedColors[]= $color->color_name;
            }
        }


        if (!$product->is_product_variant && $unitPrice == null) {
            // $unitPrice          = $product->total_product_unit_price / $product->total_product_qty;
            $unitPrice          = $product->unit_price;
            $salesPrice         = salesPrice($product) ?? 0;
            $wholesalesPrice    = wholesalesPrice($product) ?? 0;
        }


        $categories = Category::select('category_name', 'id')->where('is_active', 1)->get();
        $brands     = Brand::select('brand_name', 'id')->where('is_active', 1)->get();
        $units      = Unit::select('unit_name', 'id')->where('is_active', 1)->get();
        $currencies = Currency::select('currency_name', 'id')->where('is_active', 1)->get();
        $colors     = Variant::select('variant_name', 'id')->where([['is_active', 1], ['variant_type', 'color']])->get();
        $sizes      = Variant::select('variant_name', 'id')->where([['is_active', 1], ['variant_type', 'size']])->get();
    
        return view('backend.pages.product.editproduct', compact('unitPrice', 'salesPrice', 'wholesalesPrice', 'categories', 'tags', 'selectedSizes', 'selectedColors','product', 'brands', 'units', 'currencies', 'colors', 'sizes'));

    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // dd($product, $request->all());

        try{


            $fileLocation               = $product->product_thumbnail_image ?? null;
            $data                       = $request->all();
            $product_gallery            = $request->product_gallery;
            $product_thumbnail_image    = $request->product_thumbnail_image;

            $product_thumbnail_image    = $product_thumbnail_image && count(json_decode($product_thumbnail_image)) > 0 ? json_decode($product_thumbnail_image)[0] : null;
            $product_gallery_images     = $product_gallery && count(json_decode($product_gallery)) > 0 ? json_decode($product_gallery) : null;

            if ($product_thumbnail_image) {
 
                if ($fileLocation) {
                    $this->deleteImage($fileLocation);
                }

                $fileResponse = $this->uploadFile($product_thumbnail_image, 'products/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }
            
            $data['product_thumbnail_image'] = $fileLocation;

            $data['product_gallerys'] = [];
            if ($product_gallery_images) {

                foreach ($product_gallery_images as $image) {

                    if(isset($product->productImages) && count($product->productImages)) {
                        foreach ($product->productImages as $oldImage) {
                            $this->deleteImage($oldImage);
                        }
                    }

                    $fileResponse = $this->uploadFile($image, 'products/');
                    if (!$fileResponse['success'])
                        throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                    $fileLocation = $fileResponse['fileLocation'];
                    $data['product_gallerys'][] = ['product_image' => $fileLocation];
                }
            }

            DB::beginTransaction();

            // create product 
            $productData = $this->updateProduct($data, $product);

            // dd($productData);
            if (!$productData['success'])
                throw new Exception($productData['msg'] ?? "Unable to Update Product!", 403);


            $purchase_product_id = $request->purchase_product_id;

            if ($purchase_product_id) {
                $purchase_product = PurchaseProduct::find($purchase_product_id);
                $purchase_product->increment('stocked_qty', $request->product_qty);

                if(isset($productData['data']['id'])){
                    $purchase_product->update(['product_id' => $productData['data']['id'] ?? null]);
                }

                $purchase_product->purchase()->update([
                    'is_manage_stock' => 1
                ]);
            }


            DB::commit();

            return response()->json([
                'success'   => true,
                'msg'       => $productData['msg'] ?? 'Success!',
                'data'      => $productData['data'] ?? null
            ]);

        } catch (\Exception $th) {

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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        try {

            DB::beginTransaction();

            $product->delete();
            $product->brands()->detach();
            $product->tags()->detach();
            $product->productImages()->delete();
            $product->variants()->detach();
            $product->productColors()->delete();
            $product->productSizes()->delete();

            DB::commit();

            return response()->json([
                'success'   => true,
                'msg'       => 'Product Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();
            
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);

        }
    }


    public function publish(Request $request, Product $product)
    {

        try {

            $product->update([
                'is_publish' => $request->is_publish
            ]);

            return response()->json([
                'success'   => true,
                'msg'       => 'Product Published Successfully!',
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);

        }
    }


    public function productexport(){
        return Excel::download(new ProductDataExport, 'product_list.xlsx');
    }

    public function getproductpdf(){

        $getproducts = Product::get();
        // dd($getproducts);
        $pdf = PDF::loadView('backend.pages.product.product_pdf', compact('getproducts'), [], [
            'margin_left'   => 20,
            'margin_right'  => 15,
            'margin_top'    => 45,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 5,
            'watermark'     => env('APP_NAME','Micro Media')
        ]);
        return $pdf->stream('product_data.pdf');
        // return view('backend.pages.product.product_pdf');
    }



}
