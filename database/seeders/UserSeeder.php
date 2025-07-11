<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el ID del rol Admin
        $adminRoleId = DB::table('roles')->where('name', 'Admin')->first()->id;

        DB::table('users')->insert([
            'role_id' => $adminRoleId,
            'name' => 'Administrador',
            'username' => 'admin',
            'email' => 'admin@siscotraining.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
