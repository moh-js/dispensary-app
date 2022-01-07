<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'super-admin'
            ], [
                'name' => 'admin', 'permissions' => [
                    ['user', 'action' => ['view', 'add', 'update', 'delete', 'activate', 'deactivate']],
                    ['role', 'action' => ['view', 'add', 'update', 'delete', 'activate', 'deactivate', 'grant-permission']],
                    ['configuration', 'action' => ['general', 'data-import']],
                ]
            ], [
                'name' => 'doctor', 'permissions' => [
                    ['patient', 'action' => ['view', 'add', 'update', 'delete', 'activate', 'deactivate']],
                    ['item', 'action' => ['view', 'add', 'update', 'delete', 'management', 'issue']],
                    ['encounter', 'action' => ['view', 'create', 'update']],
                    ['report', 'action' => ['dispensing-view', 'inventory-ledger-view']],
                ]
            ], [
                'name' => 'cashier', 'permissions' => [
                    ['bill', 'action' => ['completed', 'complete', 'add', 'show', 'remove-single', 'remove-all', ]],
                ]
            ], [
                'permissions' => [
                    // ['personal-information', 'action' => ['update', 'create', 'complete']],
                ]
            ]
        ];


        foreach ($roles as $role) {
            if (isset($role['name'])) { // if role is found create it
                $roleInstance = Role::firstOrCreate([
                    'name' => $role['name']
                ]);
                echo "Role $roleInstance->name  created \n";
            }

            foreach ($role['permissions']??[] as $permission) {
                foreach ($permission['action'] as $action) {
                    $permissionInstance = Permission::firstOrCreate(['name' => $permission[0].'-'.$action]);
                    echo "Permission $permissionInstance->name  created \n";
                    if (isset($role['name'])) { // if role was created give permissions to that role
                        $roleInstance->givePermissionTo($permissionInstance->name);
                    }
                }
            }
        }
    }
}
