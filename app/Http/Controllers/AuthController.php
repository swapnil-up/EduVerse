<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request -> validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Login successful', 'user' => Auth::user()], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout successful'], 200);
    }

    public function passwordReset(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user=User::where('email', $request->email )-> first();
        $user->password= Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successful'], 200);
    }
}
