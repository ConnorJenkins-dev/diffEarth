<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run()
    {
    // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $collaboratorRole = Role::firstOrCreate(['name' => 'collaborator']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

    // Create test admin
        $admin = User::firstOrCreate([
        'email' => 'admin@test.com'
        ], [
        'name' => 'Admin User',
        'password' => Hash::make('adminpassword')
        ]);
        $admin->roles()->sync([$adminRole->id]);

    // Create test collaborator
        $collaborator = User::firstOrCreate([
        'email' => 'collaborator@test.com'
        ], [
        'name' => 'Collaborator User',
        'password' => Hash::make('collaboratorpassword')
        ]);
        $collaborator->roles()->sync([$collaboratorRole->id]);

    // Create test user
        $user = User::firstOrCreate([
        'email' => 'user@test.com'
        ], [
        'name' => 'Regular User',
        'password' => Hash::make('userpassword')
        ]);
        $user->roles()->sync([$userRole->id]);

        $this->command->info('Test users and roles seeded successfully!');
    }
}
