<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        $user = User::latest()->get();
        return response()->json(['data' => $user], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:5' 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->assignRole($request->role);
        return response()->json(['message' => 'user created'], 201);
    }

    public function login(Request $request)
    {
        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('nApp')->accessToken;
            return response()->json(['token' => $token, 'mesage' => 'login success'], 200);
        }else{
            return response()->json(['message' => 'unautorized'], 401);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json(['message' => 'success logout'], 200);
    }

}
