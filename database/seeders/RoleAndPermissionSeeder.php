<?php

namespace Database\Seeders;


use App\Models\Enums\UserPermission;
use App\Models\Enums\Modules;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleTable = config('permission.table_names.roles');
        $permissionTable = config('permission.table_names.permissions');

        $this->truncateAllTables();

        $this->insert($roleTable, Modules::cases());
        $this->insert($permissionTable, UserPermission::cases());

        $this->assignPermissionsToRoles();
        $this->assignRolesToUsers();
    }

    public function truncateAllTables(): void
    {
        $tableNames = config('permission.table_names');
        foreach ($tableNames as $key => $tableName) {
            DB::table($tableName)->truncate();
        }
    }

    public function insert(string $table, array $enums): void
    {
        $attributes = array_map(function ($item) {
            return [
                'name' => $item->value,
                'guard_name' => 'api'
            ];
        }, $enums);

        DB::table($table)->insert($attributes);
    }

    /**
     * @return void
     */
    public function assignPermissionsToRoles(): void
    {
        $admin = Role::where('name', Modules::ADMIN)->first();
        $admin->givePermissionTo(UserPermission::USER_MANAGEMENT);
    }

    /**
     * @return void
     */
    public function assignRolesToUsers(): void
    {
        $roles = Modules::cases();
        $roleCount = count($roles);

        foreach (User::all() as $user) {
            $role = $user->name == 'admin'
                ? Modules::ADMIN
                : $roles[rand(0, $roleCount - 1)]->value;

            $user->assignRole($role);
        }
    }
}
