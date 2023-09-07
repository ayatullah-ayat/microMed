<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Passwords\CanResetPassword;

class AdminPasswordResetLinkController extends Controller
{
    use CanResetPassword;
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
    
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::broker('admins')->sendResetLink($request->only('email'));
        
        // ,function($adminInstance){

        //     if($adminInstance){
        //         // custom notification mail for resetting password
        //     }
        //     // dd($res);
        // });


        // $status = $this->broker()->sendResetLink(
        //     $request->only('email')
        // );

        //use this code in verdor ResetPassword.php in resetUrl func
        /*
            if($notifiable->getTable() == "admins"){
            return url(route('admin.password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        }

        */


        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }


    public function broker(): \Illuminate\Contracts\Auth\PasswordBroker
    {
        return Password::broker('admins');
    }


    protected function guard()
    {
        return Auth::guard('admin');
    }

}
