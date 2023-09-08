<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'username' => 'required|min:4',
            'email' => 'required|email:dns|unique:users,email',
            'password' => 'required|min:8'
        ];

        // membuat validasi
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg' => 'Data gagal di daftarkan',
                'error_msg' => $validator->errors()
            ], 401);
        }

        // simpan data ke dalam tabel users
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'email-verified-at' => now(),
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10)
        ]);

        // kirim response berhasil
        return response()->json([
            'status' => true,
            'msg' => 'Berhasil daftar akun!',
            'data' => [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email:dns',
            'password' => 'required|min:8'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg' => 'Validasi gagal, mohon masukkan username dan email yang benar',
                'error_msg' => $validator->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'msg' => 'Login gagal, silahkan coba lagi',
                'data' => $request->all()
            ], 401);
        }

        $data_user_login = User::where('email', $request->email)->first();
        $token_name = 'api_sanctum_token_' . $data_user_login->name;

        return response()->json([
            'status' => true,
            'msg' => 'Berhasil login!',
            'token' => $data_user_login->createToken($token_name)->plainTextToken
        ]);
    }
}
