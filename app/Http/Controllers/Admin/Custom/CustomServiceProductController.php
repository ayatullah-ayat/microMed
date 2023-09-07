<?php

namespace App\Http\Controllers\Admin\Custom;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomServiceProductRequest;
use App\Http\Services\ImageChecker;
use App\Models\Custom\CustomServiceCategory;
use App\Models\Custom\CustomServiceProduct;
use App\Models\Custom\OurCustomService;
use Exception;

class CustomServiceProductController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customproducts = CustomServiceProduct::orderByDesc('id')->get();
        $customservices = OurCustomService::get();
        $servicescategories = CustomServiceCategory::get();
        return view('backend.pages.custom_service.customproduct', compact('customproducts','customservices' ,'servicescategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategory($service_id)
    {
        try {

            $categories = CustomServiceCategory::selectRaw(
                'category_name as text, id'
            )->where('service_id', $service_id)->get();
            
            return response()->json($categories);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
    

    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomServiceProductRequest $request)
    {
        try {

            $product_thumbnail  = $request->product_thumbnail;
            $data               = $request->all();
            $fileLocation       = 'assets/img/blank-img.png';
    
            if($product_thumbnail){
                //file, dir
                $fileResponse = $this->uploadFile($product_thumbnail, 'CustomServiceProduct/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);
    
                $fileLocation = $fileResponse['fileLocation'];
            }
            
            $data['product_thumbnail'] = $fileLocation;
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $customProduct = CustomServiceProduct::create($data);
            if(!$customProduct)
                throw new Exception('Unable to create Product', 403);
            return response()->json([
                'success'   => true,
                'msg'       => 'Product Created Successfully!',
                'data'      => $customProduct
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
     * @param  \App\Models\CustomServiceProduct  $customServiceProduct
     * @return \Illuminate\Http\Response
     */
    public function show(CustomServiceProduct $customServiceProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomServiceProduct  $customServiceProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomServiceProduct $customServiceProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomServiceProduct  $customServiceProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomServiceProduct $customServiceProduct)
    {
        try {

            if(!$customServiceProduct)
                throw new Exception("No record Found!", 404);
                
            $data               = $request->all();
            $product_thumbnail  = $request->product_thumbnail;
            $fileLocation       = $customServiceProduct->product_thumbnail;

            if ($product_thumbnail) {
                //file, dir
                if($fileLocation){
                    $this->deleteImage($fileLocation);
                }
                
                $fileResponse = $this->uploadFile($product_thumbnail, 'CustomServiceProduct/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['product_thumbnail'] = $fileLocation;
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;
            $customproductstatus = $customServiceProduct->update($data);
            if(!$customproductstatus)
                throw new Exception("Unable to Update Service!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Service Updated Successfully!',
                'data'      => $customServiceProduct->first()
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
     * @param  \App\Models\CustomServiceProduct  $customServiceProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomServiceProduct $customServiceProduct)
    {
        try {

            $isDeleted = $customServiceProduct->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Service!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Service Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


}
