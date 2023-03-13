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
                    ['service', 'action' => ['view', 'add', 'update', 'delete', 'activate', 'deactivate']],
                    ['item', 'action' => ['view', 'add', 'update', 'delete', 'management', 'issue']],
                    ['encounter', 'action' => ['view', 'create', 'update', 'general-info-add', 'general-info-view', 'status-toggle']],
                    ['prescription', 'action' => ['view', 'create', 'update', 'delete']],
                    ['investigation', 'action' => ['view', 'create', 'update', 'delete']],
                    ['procedure', 'action' => ['view', 'create', 'update', 'delete']],
                    ['vital', 'action' => ['view', 'add']],
                    ['report', 'action' => ['dispensing-view', 'dispensing-advanced-view', 'inventory-ledger-view', 'inventory-ledger-advanced-view', 'patient-visit-view', 'patient-visit-advanced-view']],
                ]
            ], [
                'name' => 'cashier', 'permissions' => [
                    ['report', 'action' => ['cash-view', 'cash-advanced-view']],
                    ['bill', 'action' => ['view', 'completed', 'complete', 'add', 'remove-single', 'remove-all', ]],
                ]
            ], [
                'permissions' => [
                    ['audits-inventory', 'action' => ['view']],
                    ['audits-service', 'action' => ['view']],
                    ['audits-general', 'action' => ['view']],
                    ['station', 'action' => ['change']],
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
