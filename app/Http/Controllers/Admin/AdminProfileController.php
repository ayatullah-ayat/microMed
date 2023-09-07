<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Services\ImageChecker;
use App\Models\Admin;

class AdminProfileController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminProfile  $adminProfile
     * @return \Illuminate\Http\Response
     */
    public function show(AdminProfile $adminProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdminProfile  $adminProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminProfile $adminProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminProfile  $adminProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        try {

            $reqData = $request->all();

            if (array_key_exists('data', $reqData)) {
                $reqData = $reqData['data'];
            }

            if (is_string($reqData)) {
                $reqData = json_decode($reqData);
            }

            $hasProfile = false;

            DB::beginTransaction();

            $admin->update([
                'name'      => $reqData->name,
                'email'     => $reqData->email,
            ]);


            $updatable = [
                'mobile_no' => $reqData->phone,
                'gender'    => $reqData->gender ?? '1',
            ];
            
            if ($admin->profile) {
                $hasProfile = true;
            }

            $encoded_string = isset($reqData->photo) ? $reqData->photo : null;
            if ($encoded_string) {
                // dd($admin->profile);
                if (count(explode(',', $encoded_string)) > 1) {

                    if ($admin->profile && $admin->profile->photo) {
                        $imageRes = $this->deleteImage($admin->profile->photo);
                        if (!$imageRes)
                            throw new Exception("Unable to delete Image!", 403);
                        $hasProfile = true;
                    }


                    $fileResponse = $this->uploadFile($encoded_string, 'users/');
                    if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                    $updatable['photo'] = $fileResponse['fileLocation'] ?? null;
                }
                
                
            }

        
            if (!$hasProfile) {
                            // dd( $admin->profile()->create($updatable));
                $createProfile = $admin->profile()->create($updatable);
                if (!$createProfile)
                    throw new Exception("Unable to Create Profile!", 403);
            } else {
                $update = $admin->profile()->update($updatable);
                if (!$update)
                    throw new Exception("Unable to Update!", 403);
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'data'      => $admin->find($admin->id),
                'msg'       => 'Profile updated Successfully!',
            ], 200);
        } catch (Exception $e) {

            DB::rollback();

            return response()->json([
                'success'   => false,
                'data'      => null,
                'msg'       => $e->getMessage(),
            ], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminProfile  $adminProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminProfile $adminProfile)
    {
        //
    }

    public function profile()
    {
        return view('backend.pages.profile.profile');
    }
}
