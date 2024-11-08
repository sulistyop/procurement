<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserPermissionsTableSeeder extends Seeder
{
	public function run()
	{
		$permissions = [
			
			'pengajuan' => [
				'manage pengajuan',
				'view pengajuan',
				'create pengajuan',
				'edit pengajuan',
				'delete pengajuan',
				'export pengajuan',
			],
			'rekap pengajuan' => [
				'manage rekap pengajuan',
				'view rekap pengajuan',
				'export rekap pengajuan',
			],
		];
		
		$adminRole = Role::firstOrCreate(['name' => 'user']);
		
		foreach ($permissions as $module => $modulePermissions) {
			foreach ($modulePermissions as $permission) {
				$perm = Permission::updateOrCreate(
					['name' => $permission, 'module' => $module],
					['name' => $permission, 'module' => $module]
				);
				$adminRole->givePermissionTo($perm);
			}
		}
	}
}
