<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
	public function run()
	{
		$permissions = [
			'manage users',
			'view users',
			'create users',
			'edit users',
			'delete users',
			
			'manage roles',
			'view roles',
			'create roles',
			'edit roles',
			'delete roles',
			
			'manage permissions',
			'view permissions',
			'create permissions',
			'edit permissions',
			'delete permissions',
			
			'manage pengajuan',
			'view pengajuan',
			'create pengajuan',
			'edit pengajuan',
			'delete pengajuan',
			'approve pengajuan',
			'export pengajuan',
			'import pengajuan',
			
			'manage rekap pengajuan',
			'view rekap pengajuan',
			'export rekap pengajuan',
			
		];
		
		// Pastikan role-permission 'admin' sudah ada di database
		$adminRole = Role::firstOrCreate(['name' => 'admin']);
		
		foreach ($permissions as $permission) {
			$perm = Permission::firstOrCreate(['name' => $permission]);
			$adminRole->givePermissionTo($perm);
		}
	}
}
