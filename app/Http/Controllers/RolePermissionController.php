<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
	
	public function update(Request $request, $roleId)
	{
		try {
			$request->validate([
				'permissions' => 'required|array',
			]);
			
			$role = Role::findById($roleId);
			$role->syncPermissions($request->permissions);
			
			return redirect()->back()->with('success', 'Permissions updated successfully.');
		} catch (\Exception $e) {
			Log::error('Update failed: ' . $e->getMessage());
			return redirect()->route('roles-permissions.edit')->with('error', 'Update failed.');
		}
	}
}