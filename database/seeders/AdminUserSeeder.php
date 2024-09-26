<?php
	
	namespace Database\Seeders;
	
	use App\Models\User;
	use Illuminate\Database\Seeder;
	use Illuminate\Support\Facades\Hash;
	use Spatie\Permission\Models\Role;
	
	class AdminUserSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 *
		 * @return void
		 */
		public function run()
		{
			// Create the admin role-permission if it doesn't exist
			$adminRole = Role::firstOrCreate(['name' => 'admin']);
			
			// Create an admin user
			$adminUser = User::updateOrCreate(
				['email' => 'admin@example.com'], // Condition to check for existing record
				[
					'name' => 'Admin User',
					'password' => Hash::make('password'), // Change this to a secure password
				]
			);
			
			if($adminUser->wasRecentlyCreated) {
				$adminUser->assignRole($adminRole);
			}
			
			$userRole = Role::firstOrCreate(['name' => 'user']);
			
			$user = User::updateOrCreate(
				['email' => 'user@gmail.com'],
				[
					'name' => 'User',
					'password' => Hash::make('password'),
				]
			);
			
			if($user->wasRecentlyCreated) {
				$user->assignRole($userRole);
			}
		}
	}