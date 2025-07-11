<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Administrador del sistema con acceso completo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Técnico',
                'description' => 'Técnico interno que puede tomar cursos asignados',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cliente',
                'description' => 'Cliente externo que puede tomar cursos asignados',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
