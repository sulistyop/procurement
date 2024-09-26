<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function assignRole(Request $request, $id)
    {
        // Validasi ID dan role-permission yang diberikan
        $request->validate([
            'role-permission' => 'required|string|exists:roles,name',
        ]);

        $user = User::find($id); // Mengganti dengan ID pengguna yang sesuai
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->assignRole($request->role); // Menetapkan role-permission ke pengguna
        return response()->json(['message' => 'Role assigned successfully'], 200);
    }
	
	public function index()
	{
		$users = User::with('roles', 'permissions')->get();
		return view('pengguna.index', compact('users'));
	}
	
	public function create()
	{
		$roles = Role::all();
		$permissions = Permission::all();
		return view('pengguna.create', compact('roles', 'permissions'));
	}
	
	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:8|confirmed',
			'roles' => 'required|array',
		]);
		
		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
		]);
		
		$user->syncRoles($request->roles);
		$user->syncPermissions($request->permissions);
		
		return redirect()->route('user.index')->with('success', 'User berhasil dibuat.');
	}
	
	public function edit(User $user)
	{
		$roles = Role::all();
		$permissions = Permission::all();
		$prodis = Prodi::all();
		return view('pengguna.edit', compact('user', 'roles', 'permissions', 'prodis'));
	}
	
	public function update(Request $request, User $user)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
			'password' => 'nullable|string|min:8|confirmed',
			'roles' => 'required|array',
		]);
		
		
		$user->update([
			'name' => $request->name,
			'email' => $request->email,
			'password' => $request->password ? Hash::make($request->password) : $user->password,
			'prodi_id' => $request->prodi_id,
		]);
		
		$user->syncRoles($request->roles);
		
		return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
	}
	
	public function destroy(User $user)
	{
		$user->delete();
		return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
	}
}


