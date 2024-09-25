<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function assignRole(Request $request, $id)
    {
        // Validasi ID dan role yang diberikan
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::find($id); // Mengganti dengan ID pengguna yang sesuai
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->assignRole($request->role); // Menetapkan role ke pengguna
        return response()->json(['message' => 'Role assigned successfully'], 200);
    }
}
