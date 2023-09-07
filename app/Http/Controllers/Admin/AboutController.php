<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\AboutUsRequest;
use App\Models\About;
use Exception;
use Illuminate\Http\Request;
use App\Http\Services\ImageChecker;

class AboutController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aboutdatas = About::orderByDesc('id')->get();
       return view('backend.pages.cms_settings.aboutus', compact('aboutdatas'));
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
    public function store(AboutUsRequest $request)
    {
        try {

            $about_thumbnail = $request->about_thumbnail;
            $data           = $request->all();
            $fileLocation   = 'assets/img/blank-img.png';

            if($about_thumbnail){
                $fileResponse = $this->uploadFile($about_thumbnail, 'aboutUs/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['about_thumbnail'] = $fileLocation;
            $about = About::create($data);
            if(!$about)
                throw new Exception("Unable to create About Information!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'About Information Created Successfully!',
                'data'      => $about
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
     * @param  \App\Models\About  $about
     * @return \Illuminate\Http\Response
     */
    public function show(About $about)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\About  $about
     * @return \Illuminate\Http\Response
     */
    public function edit(About $about)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\About  $about
     * @return \Illuminate\Http\Response
     */
    public function update(AboutUsRequest $request, About $about)
    {
        try {

            if(!$about)
                throw new Exception("No record Found!", 404);
                
            $data           = $request->all();
            $about_thumbnail = $request->about_thumbnail;
            $fileLocation   = $about->about_thumbnail;

            if ($about_thumbnail) {
                //file, dir
                if($fileLocation){
                    $this->deleteImage($fileLocation);
                }
                
                $fileResponse = $this->uploadFile($about_thumbnail, 'aboutUs/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['about_thumbnail'] = $fileLocation;
           
            $aboutStatus = $about->update($data);
            if(!$aboutStatus)
                throw new Exception("Unable to Update About Information!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'About Information Updated Successfully!',
                'data'      => $about->first()
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
     * @param  \App\Models\About  $about
     * @return \Illuminate\Http\Response
     */
    public function destroy(About $about)
    {
        try {

            $isDeleted = $about->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete about details!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'About Details Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }

}
