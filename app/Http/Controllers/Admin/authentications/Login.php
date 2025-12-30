<?php

namespace App\Http\Controllers\Admin\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
  public function index()
  {
    return view('content.authentications.login');
  }

  public function authenticate(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|string',
      'password' => 'required|string',
    ]);

    $loginField = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)
      ? 'email'
      : 'username';

    if (Auth::attempt([
      $loginField => $credentials['email'],
      'password' => $credentials['password']
    ], $request->has('remember-me'))) {

      $request->session()->regenerate();

      return redirect()->route('dashboard');
    }

    return back()->withErrors([
      'email' => 'Invalid credentials',
    ]);
  }

  public function logout(Request $request)
  {
    Auth::guard('web')->logout();

    return redirect()->route('login');
  }
}
