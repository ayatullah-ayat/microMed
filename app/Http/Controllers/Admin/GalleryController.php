<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Services\ImageChecker;

class GalleryController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gallery = Gallery::first();
        return view('backend.pages.cms_settings.gallery', compact('gallery'));
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

        try{

            $req_data       = $request->all();
            $imagesData     = json_decode($req_data['data']);
            $uploadedFiles  = [];
            $oldGallery     = Gallery::first();
            $deleteImage    = false;
            $imagesDb       = [];

            if (!count($imagesData->images))
                throw new Exception("Image should not be NULL OR Empty!", 403);

            if ($oldGallery) {
                $imagesDb = isset($oldGallery->images) && $oldGallery->images ? json_decode($oldGallery->images) : [];
                if ($deleteImage) {
                    foreach ($imagesDb as $key => $imgObj) {
                        $delIamge =  $this->deleteImage($imgObj->image ?? null);
                        if (!$delIamge)
                            throw new Exception("Something wents wrong!", 403);
                    }

                    $imagesDb = [];
                }
            }


            foreach ($imagesData->images as $key => $image) {
                $responseImage = $this->galleryImageUploader($image, 'gallery/');
                if (!$responseImage['success'])
                    throw new Exception($responseImage['msg'] ?? "Unable to Upload Image!", $responseImage['code'] ?? 403);

                $uploadedFiles[] = [
                    'id'        => uniqid(),
                    'title'     => null,
                    'image'     => $responseImage['fileLocation'],
                    'is_active' => 1,
                ];
            }

            
            DB::beginTransaction();

            $resDB = Gallery::updateOrCreate(
                ['id' => $oldGallery->id ?? null],
                [
                    'title'             => $imagesData->title ?? null,
                    'images'            => json_encode(array_merge($uploadedFiles, $imagesDb)),
                    'is_allow_caption'  => 0,
                    'created_by'        => auth()->guard('admin')->user()->id ?? null
                ]
            );

            if (!$resDB)
                throw new Exception("Unable to Update Gallery!", 403);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Gallery Updated successfully!'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'success' => false,
                'msg'     => $e->getMessage()
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        try {

            $image_id   = request('image_id');
            $arr        = [];

            if ($gallery && $gallery->images) {
                $images = json_decode($gallery->images) ?? [];
                foreach ($images as $indx => $imgObj) {
                    if ($image_id != $imgObj->id) {
                        $arr[] = $imgObj;
                    } else {
                        $delIamge = $this->deleteImage($imgObj->image ?? null);
                        if (!$delIamge)
                            throw new Exception("Something wents wrong!", 403);
                    }
                }

                $update = $gallery->update(['images' => json_encode($arr)]);
                if (!$update)
                    throw new Exception("Unable to Remove Image!", 403);
            }

            return response()->json([
                'success' => true,
                'msg'     => 'Image removed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => $e->getMessage()
            ]);
        }
    }
}
