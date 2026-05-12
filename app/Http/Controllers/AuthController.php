<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return match (Auth::user()->role) {
                'manager' => redirect()->intended('/manager'),
                'cashier' => redirect()->intended('/cashier'),
                'logistic' => redirect()->intended('/logistic'),
                default => abort(403, 'Unauthorized'),
            };
        }

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Username not found.',
            ])->withInput($request->only('username'));
        }

        return back()->withErrors([
            'password' => 'Wrong Password.',
        ])->withInput($request->only('username'));

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}