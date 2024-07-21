<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [

            ////Operaciones sobre tabla roles
            'ver-rol',
            'crear-rol',
            'editar-rol',
            'borrar-rol',

            ////Operaciones sobre menu usuarios
            'ver-usuario',
            'crear-usuario',
            'editar-usuario',
            'borrar-usuario',

            /////Operaciones sobre el modulo Reportes
            'ver-reportes',
            ////Operaciones en Dashboard
            'ver-dashboard',

            ////Operaciones sobre el modulo de auditoria
            'ver-auditoria',

        ];
        foreach ($permisos as $p) {
            Permission::create(['name' => $p]);
        }
    }
}
