<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login( Request $request ): JsonResponse { 
        $validated = $request->validate([ 'email' => [ 'required', 'email', ], 'password' => [ 'required', 'string', ], 'device_name' => [ 'nullable', 'string', 'max:255', ], ]); 
        $user = User::query() ->where( 'email', $validated['email'] ) ->first(); 
        if ( $user === null || ! Hash::check( $validated['password'], $user->password ) ) { 
            return response()->json([ 'message' => 'Email atau password salah.', ], 401); 
        } 
        
        $deviceName = $validated['device_name'] ?? 'android-app'; 
        $token = $user ->createToken($deviceName) 
        ->plainTextToken; 
        
        return response()->json([ 'message' => 'Login berhasil.', 'token' => $token, 'user' => [ 'id' => $user->id, 'name' => $user->name, 'email' => $user->email, ], ]); 
        
    } 
    
    public function logout( Request $request ): JsonResponse { 
        /* * Hanya menghapus token yang sedang digunakan. */ 
        $request->user() 
        ->currentAccessToken() 
        ?->delete(); 
        
        return response()->json([ 'message' => 'Logout berhasil.', ]); 
    }
}
