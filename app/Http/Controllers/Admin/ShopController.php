<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Models\Shop;
use Exception;
use Illuminate\Http\Request;
use App\Http\Services\ImageChecker;


class ShopController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shopBanner = Shop::orderByDesc('id')->get();

        return view('backend.pages.cms_settings.shopbanner', compact('shopBanner'));
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
    public function store(BannerRequest $request)
    {
        try {

            $banner_image   = $request->banner_image;
            $data           = $request->all();
            $fileLocation   = 'assets/img/blank-img.png';

            if($banner_image){
                //file, dir
                $fileResponse = $this->uploadFile($banner_image, 'BannerImages/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['banner_image'] = $fileLocation;
            $banner = Shop::create($data);
            if(!$banner)
                throw new Exception("Unable to Add Banner!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Banner Added Successfully!',
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
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shop $shop)
    {
        try {

            if(!$shop)
                throw new Exception("No record Found!", 404);
                
            $data           = $request->all();
            $banner_image   = $request->banner_image;
            $fileLocation   = $shop->banner_image;

            if ($banner_image) {
                //file, dir
                if($fileLocation){
                    $this->deleteImage($fileLocation);
                }
                
                $fileResponse = $this->uploadFile($banner_image, 'BannerImages/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['banner_image'] = $fileLocation;
          
            $bannerStatus = $shop->update($data);
            if(!$bannerStatus)
                throw new Exception("Unable to Update Banner!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Banner Updated Successfully!',
                'data'      => $shop->first()
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
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop)
    {
        try {

            $isDeleted = $shop->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Banner!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Banner Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


}
