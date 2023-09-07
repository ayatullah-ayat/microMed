<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

class AdminAuthenticatedSessionController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (!Auth::guard('admin')->attempt($credentials, $request->remember)) {
            return redirect()->route('admin.login');
        }

        return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
  
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
