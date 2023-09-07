<?php

namespace App\Http\Controllers\Admin\Custom;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomServiceRequest;
use App\Models\Custom\OurCustomService;
use Exception;
use App\Http\Services\ImageChecker;


class OurCustomServiceController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = OurCustomService::orderByDesc('id')->get();
        return view('backend.pages.custom_service.customservice', compact('services'));
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
    public function store(CustomServiceRequest $request)
    {
        try {

            $service_thumbnail  = $request->service_thumbnail;
            $data               = $request->all();
            $fileLocation       = 'assets/img/blank-img.png';
    
            if($service_thumbnail){
                //file, dir
                $fileResponse = $this->uploadFile($service_thumbnail, 'services/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);
    
                $fileLocation = $fileResponse['fileLocation'];
            }
            
            $data['service_thumbnail'] = $fileLocation;
            $service = OurCustomService::create($data);
            if(!$service)
                throw new Exception('Unable to create Service', 403);
            return response()->json([
                'success'   => true,
                'msg'       => 'Service Created Successfully!',
                'data'      => $service
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
     * @param  \App\Models\OurCustomService  $ourCustomService
     * @return \Illuminate\Http\Response
     */
    public function show(OurCustomService $ourCustomService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OurCustomService  $ourCustomService
     * @return \Illuminate\Http\Response
     */
    public function edit(OurCustomService $ourCustomService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OurCustomService  $ourCustomService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OurCustomService $ourCustomService)
    {
        try {

            if(!$ourCustomService)
                throw new Exception("No record Found!", 404);
                
            $data               = $request->all();
            $service_thumbnail  = $request->service_thumbnail;
            $fileLocation       = $ourCustomService->service_thumbnail;

            if ($service_thumbnail) {
                //file, dir
                if($fileLocation){
                    $this->deleteImage($fileLocation);
                }
                
                $fileResponse = $this->uploadFile($service_thumbnail, 'services/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['service_thumbnail'] = $fileLocation;
            $data['updated_by'] = auth()->guard('admin')->user()->id ?? null;
            $servicestatus = $ourCustomService->update($data);
            if(!$servicestatus)
                throw new Exception("Unable to Update Service!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Service Updated Successfully!',
                'data'      => $ourCustomService->first()
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
     * @param  \App\Models\OurCustomService  $ourCustomService
     * @return \Illuminate\Http\Response
     */
    public function destroy(OurCustomService $ourCustomService)
    {
        try {

            $isDeleted = $ourCustomService->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Service!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Service Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


}
