<?php

namespace App\Http\Controllers\User;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use App\Http\Services\ImageChecker;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
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
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {

            $data           = $request->all();
            $photo          = $request->photo;
            $fileLocation   = $user->profile ? $user->profile->photo : null;

            if($user->email !== $request->email)
                throw new Exception("Email can't be change!");

            $user->update([
                'name'      => $data['name'],
                'username'  => $data['username'],
                'email'     => $data['email'],
            ]);

            if ($photo && count(explode(',', $photo))>=2) {
                //file, dir
                if($fileLocation){
                    $this->deleteImage($fileLocation);
                }
                
                $fileResponse = $this->uploadFile($photo, 'UserProfile/');
                if (!$fileResponse['success'])
                    throw new Exception($fileResponse['msg'], $fileResponse['code'] ?? 403);

                $fileLocation = $fileResponse['fileLocation'];
            }

            $data['photo'] = $fileLocation;

            $arrData = [
                'photo'     => $data['photo'] ?? null,
                'mobile_no' => $data['mobile_no'] ?? null,
                'country'   => $data['country'] ?? null,                
                'division'  => $data['division'] ?? null, 
                'district'  => $data['district'] ?? null,
                'gender'    => $data['gender'],
                'address'   => $data['address'] ?? null
            ];

            if(!$user->profile){
                // dd($user->profile);
                $profilestatus = $user->profile()->create($arrData);

            }else{
                $profilestatus = $user->profile()->update($arrData);
            }

            if(!$profilestatus)
                throw new Exception("Unable to Update Profile!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Profile Updated Successfully!',
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
     * Profile password update
     */
    public function passwordreset(Request $request, User $user){
        try {

            $data = $request->all();

            if(!array_key_exists('old_password',$data))
                throw new Exception("Please Input Old Password", 403);

            if(!$data['old_password'])
                throw new Exception("Please Input Old Password", 403);
                
            if(!array_key_exists('new_password',$data))
                throw new Exception("Please Input new Password", 403);

            if(!$data['new_password'])
                throw new Exception("Please Input new Password", 403);

            if(!Hash::check($data['old_password'], $user->password))
                throw new Exception("Old Password Doesn't Match", 403);

            $user->update([
                'password' => Hash::make($data['new_password'])
            ]);

            
            return response()->json([
                'success'   => true,
                'msg'       => 'Password Updated Successfully!',
            ]);

        } catch (\Exception $th) {
            return response()->json([
                'Success' => false,
                'mgs' => $th->getMessage()
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
