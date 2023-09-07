<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\AdsManager;
use Illuminate\Http\Request;
use App\Http\Services\ImageChecker;
use App\Http\Controllers\Controller;

class AdsManagerController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ads = AdsManager::orderByDesc('id')->get();

        return view('backend.pages.cms_settings.ads', compact('ads'));
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

            $banner_image   = $request->banner_image;
            if(!$banner_image)
                throw new Exception("Ads Banner is Required!", 403);

                
            $data           = $request->all();
            $fileLocation   = null;

            if ($banner_image) {
                //file, dir
                $fileResponse = $this->uploadFile($banner_image, 'ads/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['image'] = $fileLocation;
            $banner = AdsManager::create($data);
            if (!$banner)
                throw new Exception("Unable to Add Banner!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Ads Created Successfully!',
                'data'      => $banner
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     //
    // }


    public function update(Request $request, AdsManager $adsManager)
    {
        try {

            if (!$adsManager)
                throw new Exception("No record Found!", 404);

            $data           = $request->all();
            $banner_image   = $request->banner_image;
            $fileLocation   = $adsManager->image;

            if ($banner_image) {
                //file, dir
                if ($fileLocation) {
                    $this->deleteImage($fileLocation);
                }

                $fileResponse = $this->uploadFile($banner_image, 'ads/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['image'] = $fileLocation;

            $bannerStatus = $adsManager->update($data);
            if (!$bannerStatus)
                throw new Exception("Unable to Update Banner!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Ads Updated Successfully!',
                'data'      => $adsManager->first()
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdsManager $adsManager)
    {
        try {

            $isDeleted = $adsManager->delete();
            if (!$isDeleted)
                throw new Exception("Unable to delete Banner!", 403);

            $fileLocation   = $adsManager->image;
            if ($fileLocation) {
                $this->deleteImage($fileLocation);
            }

            return response()->json([
                'success'   => true,
                'msg'       => 'Ads Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }
}
