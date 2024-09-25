<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
	public function getPermissions(Role $role)
	{
		return response()->json([
			'permissions' => $role->permissions->pluck('name')
		]);
	}
}