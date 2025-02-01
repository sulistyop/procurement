<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        if (auth()->user()->hasRole('admin')) {
            return '/dashboard'; // Ganti '/dashboard' dengan rute yang sesuai
        }
        
        return route('welcome'); // Rute untuk pengguna biasa
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override the login method to handle custom error message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
        ];
    }

    /**
     * Handle a failed login attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()->withErrors([
            'email' => 'Email dan password tidak sesuai.',
        ]);
    }

    /**
     * After authentication logic
     */
    protected function authenticated()
    {
        if(Auth::user()->roles->pluck('name')->first() === 'admin') {
            return redirect()->route('dashboard');
        }
        
        return redirect()->route('welcome');
    }

    public function login(Request $request)
    {
        // Validasi data login, termasuk reCAPTCHA
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha', // Validasi reCAPTCHA
        ], [
            'g-recaptcha-response.required' => Lang::get('The reCAPTCHA field is required.'),
            'g-recaptcha-response.captcha' => Lang::get('The reCAPTCHA is invalid. Please try again.'),
        ]);

        // Jika validasi berhasil, lakukan login
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return redirect()->intended(route('dashboard'));
        }

        // Jika gagal, kembalikan ke halaman login dengan error
        return back()->withErrors([
            'email' => Lang::get('auth.failed'),
        ]);
    }
}
