<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebFooterRequest;
use App\Models\WebFooter;
use Exception;
use Illuminate\Http\Request;
use App\Http\Services\ImageChecker;

class WebFooterController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $footerdatas = WebFooter::orderByDesc('id')->get();
        return view('backend.pages.cms_settings.webfooter', compact('footerdatas'));
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
    public function store(WebFooterRequest $request)
    {
        try {

            $footer_logo    = $request->footer_logo;
            $data           = $request->all();
            $fileLocation   = 'assets/img/blank-img.png';

            if($footer_logo){
                //file, dir
                $fileResponse = $this->uploadFile($footer_logo, 'WebFooter/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['footer_logo'] = $fileLocation;
            $category = WebFooter::create($data);
            if(!$category)
                throw new Exception("Unable to create Footer About!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Footer About Created Successfully!',
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
    public function update(WebFooterRequest $request, WebFooter $webfooter)
    {
        try {

            if(!$webfooter)
                throw new Exception("No record Found!", 404);
            $data           = $request->all();
            $footer_logo   = $request->footer_logo;
            $fileLocation   = $webfooter->footer_logo;

            if ($footer_logo) {
                if($fileLocation){
                    $this->deleteImage($fileLocation);
                }
                
                $fileResponse = $this->uploadFile($footer_logo, 'WebFooter/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['footer_logo'] = $fileLocation;
            $footerStatus = $webfooter->update($data);
            if(!$footerStatus)
                throw new Exception("Unable to Update Footer About!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Category Updated Footer About!'
            ]);
                
        } catch (\Exception $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebFooter $webfooter)
    {
        try {

            $isDeleted = $webfooter->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Footer About!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Foote About Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


}
