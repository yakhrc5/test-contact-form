<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;



class AuthController extends Controller
{
  // ログイン画面
  public function login()
  {
    return view('auth.login');
  }

  // ログイン処理
  public function loginStore(LoginRequest $request)
  {
    $credentials = $request->validated();

    if (!Auth::attempt($credentials)) {
      throw ValidationException::withMessages([
        'email' => 'ログイン情報が登録されていません',
      ]);
    }

    $request->session()->regenerate();

    return redirect('/admin');
  }

  // 登録画面
  public function register()
  {
    return view('auth.register');
  }

  // 登録処理
  public function registerStore(RegisterRequest $request)
  {
    $validated = $request->validated();

    $user = User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);

    return redirect('/admin');
  }
}

