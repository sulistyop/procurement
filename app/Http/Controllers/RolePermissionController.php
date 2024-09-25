<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
	public function edit()
	{
		$roles = Role::all();
		$permissions = Permission::all();
		$groupedPermissions = $permissions->groupBy(function ($item) {
			return Str::slug($item->module);
		});
		return view('role-permission.edit', compact('roles', 'groupedPermissions'));
	}
	
	public function update(Request $request)
	{
		$request->validate([
			'role_id' => 'required|exists:roles,id',
			'permissions' => 'required|array',
		]);
		
		$role = Role::findById($request->role_id);
		$role->syncPermissions($request->permissions);
		
		return redirect()->route('roles-permissions.edit')->with('success', 'Permissions updated successfully.');
	}
}