<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Services\ImageChecker;



class CategoryController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderByDesc('id')->get();
        return view('backend.pages.category.categorylist', compact('categories'));
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
    public function store(CategoryRequest $request)
    {
        try {

            $category_image = $request->category_image;
            $data           = $request->all();
            $fileLocation   = 'assets/img/blank-img.png';

            if($category_image){
                //file, dir
                $fileResponse = $this->uploadFile($category_image, 'categories/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['category_image'] = $fileLocation;
            $data['created_by'] = auth()->guard('admin')->user()->id ?? null;
            $category = Category::create($data);
            if(!$category)
                throw new Exception("Unable to create category!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Category Created Successfully!',
                'data'      => $category
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try {

            if(!$category)
                throw new Exception("No record Found!", 404);
            $data           = $request->all();
            $category_image = $request->category_image;
            $fileLocation   = $category->category_image;

            if ($category_image) {
                //file, dir
                if($fileLocation){
                    $this->deleteImage($fileLocation);
                }
                
                $fileResponse = $this->uploadFile($category_image, 'categories/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['category_image'] = $fileLocation;
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;
            $categoryStatus = $category->update($data);
            if(!$categoryStatus)
                throw new Exception("Unable to Update category!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Category Updated Successfully!',
                'data'      => $category->first()
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {

            $isDeleted = $category->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete category!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Category Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }

    
}
