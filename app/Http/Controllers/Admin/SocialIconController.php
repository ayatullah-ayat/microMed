<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialIconRequest;
use App\Models\SocialIcon;
use Exception;
use Illuminate\Http\Request;


class SocialIconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sociallists = SocialIcon::orderByDesc('id')->get();
        // dd($sociallists);
        return view('backend.pages.cms_settings.socialicon', compact('sociallists'));
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
    public function store(SocialIconRequest $request)
    {
        try {
            $data      = $request->all();
            $unit   = SocialIcon::create($data);
            if(!$unit)
                throw new Exception("Unable to create Social Link!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Social Link Created Successfully!',
                'data'      => $unit
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
    public function update(Request $request,  SocialIcon $socialicon)
    {
        try {

            $data       = $request->all();
            $sociallinkstatus = $socialicon->update($data);
            if(!$sociallinkstatus)
                throw new Exception("Unable to Update Social Link!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Social Link Updated Successfully!',
                'data'      => $socialicon->first()
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
    public function destroy( SocialIcon $socialicon )
    {
        {
            try {
    
                $isDeleted = $socialicon->delete();
                if(!$isDeleted)
                    throw new Exception("Unable to delete Social Icon!", 403);
                    
                return response()->json([
                    'success'   => true,
                    'msg'       => 'Social Icon Deleted Successfully!',
                ]);
    
            } catch (\Throwable $th) {
                return response()->json([
                    'success'   => false,
                    'msg'       => $th->getMessage()
                ]);
            }
        }
    }


}
