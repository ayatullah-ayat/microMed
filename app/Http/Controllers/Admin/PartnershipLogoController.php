<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\ImageChecker;
use App\Models\PartnershipLogo;
use Exception;
use Illuminate\Http\Request;

class PartnershipLogoController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partnerlogos = PartnershipLogo::orderByDesc('id')->get();
        return view('backend.pages.cms_settings.ourpartnerlogo', compact('partnerlogos'));
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
            $oldLogos       = PartnershipLogo::all();
            $deleteImage    = false;
            $imagesDb       = [];


            if (!count($imagesData->images))
                throw new Exception("Logo should not be NULL OR Empty!", 403);

            if ($oldLogos && count($oldLogos)) {
                if ($deleteImage) {
                    foreach ($oldLogos as $key => $imgObj) {
                        $delIamge =  $this->deleteImage($imgObj->logo ?? null);
                        if (!$delIamge)
                            throw new Exception("Something wents wrong!", 403);
                    }

                    $imagesDb = [];
                }
            }

            foreach ($imagesData->images as $key => $logo) {
                $responseImage = $this->galleryImageUploader($logo, 'partnershiplogo/');
                if (!$responseImage['success'])
                    throw new Exception($responseImage['msg'] ?? "Unable to Upload Logo!", $responseImage['code'] ?? 403);

                $uploadedFiles = [
                    'logo' => $responseImage['fileLocation'],
                ];
            
                $resDB = PartnershipLogo::create(array_merge($uploadedFiles, $imagesDb));
    
                if (!$resDB)
                    throw new Exception("Unable to Update Logo!", 403);
                  
            }

            return response()->json([
                'success' => true,
                'msg'     => 'Logo Updated successfully!'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'msg'     => $e->getMessage()
            ]);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PartnershipLogo  $partnershipLogo
     * @return \Illuminate\Http\Response
     */
    public function show(PartnershipLogo $partnershipLogo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartnershipLogo  $partnershipLogo
     * @return \Illuminate\Http\Response
     */
    public function edit(PartnershipLogo $partnershipLogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PartnershipLogo  $partnershipLogo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartnershipLogo $partnershipLogo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PartnershipLogo  $partnershipLogo
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartnershipLogo $partnershipLogo)
    {
        try {

            if ($partnershipLogo && $partnershipLogo->logo) {
                $delIamge = $this->deleteImage($partnershipLogo->logo ?? null);
                if (!$delIamge)
                    throw new Exception("Something wents wrong!", 403);

                $del = $partnershipLogo->delete();
                if (!$del)
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
