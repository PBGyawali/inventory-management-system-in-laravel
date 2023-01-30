<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\CompanyInfo;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $info=CompanyInfo::first();
        if(!$info)
            return redirect()->route('settings_create');
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->validate($request, [
          //  'secret_password' => ['required'],
            'password' => ['required','password:web'],
        ]);
        $secret_password =CompanyInfo::first()->secret_password;

        if (!Hash::check($request->secret_password, $secret_password)) {
           throw ValidationException::withMessages([
                'secret_password' => __('auth.password'),
            ]);
        }
       $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
