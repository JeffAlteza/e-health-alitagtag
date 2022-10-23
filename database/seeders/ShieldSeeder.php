<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ShieldSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_appointment","view_any_appointment","create_appointment","update_appointment","restore_appointment","restore_any_appointment","replicate_appointment","reorder_appointment","delete_appointment","delete_any_appointment","force_delete_appointment","force_delete_any_appointment","view_doctor::schedule","view_any_doctor::schedule","create_doctor::schedule","update_doctor::schedule","restore_doctor::schedule","restore_any_doctor::schedule","replicate_doctor::schedule","reorder_doctor::schedule","delete_doctor::schedule","delete_any_doctor::schedule","force_delete_doctor::schedule","force_delete_any_doctor::schedule","view_role","view_any_role","create_role","update_role","restore_role","restore_any_role","replicate_role","reorder_role","delete_role","delete_any_role","force_delete_role","force_delete_any_role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","page_MyProfile","widget_AppointmentOverview","widget_LatestAppointment"]},{"name":"filament_user","guard_name":"web","permissions":[]}]';
        $directPermissions = '[]';

        static::makeRolesWithPemrissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPemrissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions,true))) {

            foreach ($rolePlusPermissions as $rolePlusPermission) {

                $role = Role::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name']
                ]);

                if (! blank($rolePlusPermission['permissions'])) {

                    $role->givePermissionTo($rolePlusPermission['permissions']);

                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions,true))) {

            foreach($permissions as $permission) {

                if (Permission::whereName($permission)->doesntExist()) {
                    Permission::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
