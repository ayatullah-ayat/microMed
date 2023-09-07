<?php

namespace App\Http\Controllers\Admin\Custom;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomServiceCategoryRequest;
use App\Http\Services\ImageChecker;
use App\Models\Custom\CustomServiceCategory;
use App\Models\Custom\OurCustomService;
use Exception;

class CustomServiceCategoryController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $CustomServiceCategories = CustomServiceCategory::orderByDesc('id')->get();
        $customservices = OurCustomService::get();
        return view('backend.pages.custom_service.customservicecategory', compact( 'CustomServiceCategories' , 'customservices'));
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
    public function store(CustomServiceCategoryRequest $request)
    {
        try {
            // dd($request->all());

            $category_thumbnail = $request->category_thumbnail;
            $data               = $request->all();
            $fileLocation       = 'assets/img/blank-img.png';
    
            if($category_thumbnail){
                //file, dir
                $fileResponse = $this->uploadFile($category_thumbnail, 'CustomServiceCategory/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);
    
                $fileLocation = $fileResponse['fileLocation'];
            }
            
            $data['category_thumbnail'] = $fileLocation;
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $customservicecategory = CustomServiceCategory::create($data);

            if(!$customservicecategory)
                throw new Exception('Unable to create Service Category', 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Service Category Created Successfully!',
                'data'      => $customservicecategory
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
     * @param  \App\Models\CustomServiceCategory  $customServiceCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CustomServiceCategory $customServiceCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomServiceCategory  $customServiceCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomServiceCategory $customServiceCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomServiceCategory  $customServiceCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomServiceCategory $customServiceCategory)
    {
        try {

            if(!$customServiceCategory)
                throw new Exception("No record Found!", 404);
                
            $data               = $request->all();
            $category_thumbnail  = $request->category_thumbnail;
            $fileLocation       = $customServiceCategory->category_thumbnail;

            if ($category_thumbnail) {
                //file, dir
                if($fileLocation){
                    $this->deleteImage($fileLocation);
                }
                
                $fileResponse = $this->uploadFile($category_thumbnail, 'CustomServiceCategory/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['category_thumbnail'] = $fileLocation;
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;
            $servicestatus = $customServiceCategory->update($data);
            if(!$servicestatus)
                throw new Exception("Unable to Update Service Category!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Service Category Updated Successfully!',
                'data'      => $customServiceCategory->first()
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
     * @param  \App\Models\CustomServiceCategory  $customServiceCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomServiceCategory $customServiceCategory)
    {
        try {

            $isDeleted = $customServiceCategory->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Service Category!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Service Category Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


}
