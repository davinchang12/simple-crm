<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'edit roles']);
        Permission::create(['name' => 'edit clients']);
        Permission::create(['name' => 'look projects']);
        Permission::create(['name' => 'edit projects']);
        Permission::create(['name' => 'edit tasks']);

        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'manager'])
            ->givePermissionTo(['edit clients', 'look projects', 'edit projects', 'edit tasks']);

        Role::create(['name' => 'worker'])
            ->givePermissionTo(['look projects', 'edit tasks']);
    }
}
