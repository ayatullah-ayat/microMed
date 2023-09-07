<?php

namespace Alauddin\Authorize\Controllers;

use Exception;
use App\Http\Requests;

use Illuminate\Http\Request;
use Alauddin\Authorize\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::orderBy('created_at','DESC')->paginate(20);

        return view('vendor.authorize.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('vendor.authorize.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        try 
        {
            $requestData = $request->all();

            if(array_key_exists('name',$requestData) && is_null($requestData['name']))
                throw new Exception("Name is Requied!", 403);
                

            if(array_key_exists('alias',$requestData) && is_null($requestData['alias']))
                throw new Exception("Alias is Requied!", 403);
                
            
            Role::create($requestData);

            Session::flash('flash_message', 'Role added!');

            return redirect(Config("authorization.route-prefix") . '/roles');

        } catch (\Exception $e) 
        {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);

        return view('vendor.authorize.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('vendor.authorize.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {

        try 
        {

            $requestData = $request->all();

            if(array_key_exists('name',$requestData) && is_null($requestData['name']))
            throw new Exception("Name is Requied!", 403);
            

            if(array_key_exists('alias',$requestData) && is_null($requestData['alias']))
                throw new Exception("Alias is Requied!", 403);
        
            $role = Role::findOrFail($id);
            $role->update($requestData);
    
            Session::flash('flash_message', 'Role updated!');
    
            return redirect(Config("authorization.route-prefix") . '/roles');

        } catch (\Exception $e) 
        {
            return redirect()->back()->withErrors($e->getMessage());
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Role::destroy($id);

        Session::flash('flash_message', 'Role deleted!');

        return redirect(Config("authorization.route-prefix") . '/roles');
    }
}
