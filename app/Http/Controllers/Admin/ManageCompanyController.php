<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Http\Services\ImageChecker;
use App\Models\Company;

class ManageCompanyController extends Controller
{
    use ImageChecker;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companydata = Company::first();
        // dd($companydata);
        return view('backend.pages.settings.managecompany', compact('companydata'));
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

            $dark_logo      = $request->dark_logo;
            $white_logo     = $request->white_logo;
            $data           = $request->all();
            $fileLocation   = 'assets/img/blank-img.png';

            if($dark_logo){
                $fileResponse = $this->uploadFile($dark_logo, 'companylogo/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
                $data['dark_logo']  = $fileLocation;

            }

            if($white_logo){
                $fileResponse = $this->uploadFile($white_logo, 'companylogo/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $whitefileLocation = $fileResponse['fileLocation'];
                $data['white_logo'] = $whitefileLocation;
            }

            $company = Company::create($data);
            if(!$company)
                throw new Exception("Unable to Add Company Information!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Company Informaiton Added Successfully!'
            ]);
                
        } catch (\Exception $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
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
    public function update(Request $request, Company $company)
    {
        try {

            if(!$company)
                throw new Exception("No record Found!", 404);
                $dark_logo      = $request->dark_logo;
                $white_logo     = $request->white_logo;
                $data           = $request->all();
                $fileLocation   = 'assets/img/blank-img.png';
    
                if($dark_logo){
                    $fileResponse = $this->uploadFile($dark_logo, 'companylogo/');
                    if (!$fileResponse['success'])
                        throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);
    
                    $fileLocation = $fileResponse['fileLocation'];
                    $data['dark_logo']  = $fileLocation;

                }else{
                    unset($data['dark_logo'] );
                }
    
                if($white_logo){
                    $fileResponse = $this->uploadFile($white_logo, 'companylogo/');
                    if (!$fileResponse['success'])
                        throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);
    
                    $whitefileLocation = $fileResponse['fileLocation'];
                    $data['white_logo'] = $whitefileLocation;
                }else{
                    unset($data['white_logo'] );
                }
    

            $companyStatus = $company->update($data);
            if(!$companyStatus)
                throw new Exception("Unable to Update Company Information!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Company Information Updated Successfully!'
            ]);
                
        } catch (\Exception $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        try {

            $isDeleted = $company->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete company information!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Company Information Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


}
