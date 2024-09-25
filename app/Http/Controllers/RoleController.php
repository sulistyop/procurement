<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Validasi input
    $request->validate([
        'name' => 'required|string|unique:roles,name|max:255',
    ]);

    // Membuat role-permission baru
    $role = Role::create(['name' => $request->name]);

    // Mengembalikan respons JSON
    return response()->json(['role-permission' => $role], 201);
}
