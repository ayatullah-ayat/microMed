<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactInformationRequest;
use App\Models\ContactInformation;
use Exception;
use Illuminate\Http\Request;

class ContactInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $infoDatas = ContactInformation::orderByDesc('id')->get();
        // dd($infoDatas);
        return view('backend.pages.cms_settings.contactinformaiton', compact('infoDatas'));
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
    public function store(ContactInformationRequest $request)
    {
        try {
            $data      = $request->all();
            $contactinfo   = ContactInformation::create($data);
            if(!$contactinfo)
                throw new Exception("Unable to create Contact Information!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Contact Information Created Successfully!',
                'data'      => $contactinfo
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
     * @param  \App\Models\ContactInformation  $contactInformation
     * @return \Illuminate\Http\Response
     */
    public function show(ContactInformation $contactInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactInformation  $contactInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactInformation $contactInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactInformation  $contactInformation
     * @return \Illuminate\Http\Response
     */
    public function update(ContactInformationRequest $request, ContactInformation $contactInformation)
    {
        try {

            $data       = $request->all();
            $infostatus = $contactInformation->update($data);
            if(!$infostatus)
                throw new Exception("Unable to Update Contact Info!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Contact Info Updated Successfully!',
                'data'      => $contactInformation->first()
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
     * @param  \App\Models\ContactInformation  $contactInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactInformation $contactInformation)
    {
        try {

            $isDeleted = $contactInformation->delete();
            if(!$isDeleted)
                throw new Exception("Unable to delete Contact Information!", 403);
                
            return response()->json([
                'success'   => true,
                'msg'       => 'Contact Information Deleted Successfully!',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }


}
