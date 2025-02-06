<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/')->with('success', 'Prihlásenie úspešné!');
        }

        return back()->with('error', 'Nesprávne prihlasovacie údaje');
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Boli ste odhlásení.');
    }


    // Zobrazí formulár na zmenu hesla
    public function showChangePasswordForm()
    {
        return view('change-password');
    }

    // Spracuje zmenu hesla
    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect('/')->with('error', 'Heslo bolo úspešne zmenené.');
    }


}
