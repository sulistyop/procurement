<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Method untuk membuat role-permission baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
        ]);

        // Membuat role baru
        $role = Role::create(['name' => $request->name]);

        // Mengembalikan respons JSON
        return response()->json(['role' => $role], 201);
    }
}
