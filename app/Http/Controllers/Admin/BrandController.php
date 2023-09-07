<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;
use App\Http\Services\ImageChecker;

class BrandController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::orderByDesc('id')->get();
        return view('backend.pages.brand.brandListing', compact('brands'));
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
    public function store(BrandRequest $request)
    {
        try {

            $brand_image    = $request->brand_image;
            $data           = $request->all();
            $fileLocation   = 'assets/img/blank-img.png';
    
            if($brand_image){
                //file, dir
                $fileResponse = $this->uploadFile($brand_image, 'brand/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);
    
                $fileLocation = $fileResponse['fileLocation'];
            }

            // $data['brand_image'] = $fileLocation;
            // $data  = $request->all();
            
            $data['brand_image'] = $fileLocation;
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $brand = Brand::create($data);
            if(!$brand)
                throw new Exception('Unable to create brand', 403);
            return response()->json([
                'success'   => true,
                'msg'       => 'Brand Created Successfully!',
                'data'      => $brand
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
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        try {

            if(!$brand)
                throw new Exception("No record Found!", 404);

            // $categoryStatus = $brand->update($data);
            $data           = $request->all();
            $brand_image    = $request->brand_image;
            $fileLocation   = $brand->brand_image;
            
            if ($brand_image) {
                //file, dir
                if($fileLocation){
                    $this->deleteImage($fileLocation);
                }

                $fileResponse = $this->uploadFile($brand_image, 'brand/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['brand_image'] = $fileLocation;
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;
            $brandStatus = $brand->update($data);

            if(!$brandStatus)
                throw new Exception("Unable to Update Brand!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Brand Updated Successfully!',
                'data'      => $brand->first()
            ]);
                
        } catch (\Exception $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage(),
                'data'      => null,
                'file'      => $th->getTrace()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        try {

            $isDeleted = $brand->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete category!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Brand Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }
    
}
