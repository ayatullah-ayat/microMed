<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\ContactInformation;
use App\Models\SocialIcon;
use Exception;


class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contactInfo = ContactInformation::where('is_active', 1)->first();
        $sociallink = SocialIcon::where('is_active', 1)->first();
        return view('frontend.pages.contact', compact('contactInfo' , 'sociallink'));
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
    public function store(ContactRequest $request)
    {
        try {
            $data  = $request->all();
            $tax   = Contact::create($data);
            if(!$tax)
                throw new Exception("Unable to make request!", 403);

            return response()->json([
                'success'   => true,
                'msg'       => 'Request sent Successfully!',
                'data'      => $tax
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
