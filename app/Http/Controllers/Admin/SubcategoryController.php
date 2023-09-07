<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Requests\SubCategoryRequest;
use App\Models\Category;
use Exception;
use App\Http\Services\ImageChecker;


class SubcategoryController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategories = Subcategory::with('category')->orderByDesc('id')->get();
        $categories = Category::select('category_name','id')->latest()->get();
        return view('backend.pages.category.subcategory', compact('subcategories' , 'categories'));
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
    public function store(SubCategoryRequest $request)
    {
        try {

            $subcategory_image = $request->subcategory_image;
            $data           = $request->all();
            $fileLocation   = 'assets/img/blank-img.png';

            if($subcategory_image){
                //file, dir
                $fileResponse = $this->uploadFile($subcategory_image, 'subcategories/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['subcategory_image'] = $fileLocation;
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $subcategory = Subcategory::create($data);
            if(!$subcategory)
                throw new Exception("Unable to create SubCategory!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'SubCategory Created Successfully!',
                'data'      => $subcategory
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
     * @param  \App\Models\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Subcategory $subcategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        try {

            if(!$subcategory)
                throw new Exception("No record Found!", 404);
                
            $data               = $request->all();
            $subcategory_image  = $request->subcategory_image;
            $fileLocation       = $subcategory->subcategory_image;

            if ($subcategory_image) {
                //file, dir
                if($fileLocation){
                    $this->deleteImage($fileLocation);
                }
                
                $fileResponse = $this->uploadFile($subcategory_image, 'subcategories/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['subcategory_image'] = $fileLocation;
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;

            $subcategoryStatus = $subcategory->update($data);
            if(!$subcategoryStatus)
                throw new Exception("Unable to Update SubCategory!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'SubCategory Updated Successfully!',
                'data'      => $subcategory->first()
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
     * @param  \App\Models\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategory $subcategory)
    {
        try {

            $isDeleted = $subcategory->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete subcategory!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Sub Category Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


    public function subcategoriesByCategory($id)
    {
        $subcategories = Subcategory::selectRaw('subcategory_name as text, id')
        ->where([
            ['category_id', $id],
            ['is_active', 1],
        ])
        ->get();
        return response()->json($subcategories);
    }

}
