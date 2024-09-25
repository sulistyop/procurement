<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Menampilkan form registrasi
    public function create()
    {
        return view('auth.register'); // Ganti dengan nama view yang sesuai
    }

    // Menyimpan data registrasi
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|string|in:user,admin', // Validasi role
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // Menambahkan role dari input form
        // $user->assignRole($request->role); // Menggunakan role yang dipilih dari form
        $user->syncRoles([$request->role]);
        // Login pengguna setelah registrasi
        auth()->login($user);

        return redirect()->route('dashboard'); // Ganti dengan rute yang sesuai
    }
}
