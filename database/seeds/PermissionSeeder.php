<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'manage_category']);
        Permission::create(['name' => 'manage_unit']);
        Permission::create(['name' => 'manage_tax']);
        Permission::create(['name' => 'manage_product']);
        Permission::create(['name' => 'create_purchase_invoice']);
        Permission::create(['name' => 'manage_purchase_invoice']);
        Permission::create(['name' => 'manage_supplier_payment']);
        Permission::create(['name' => 'manage_customer_payment']);
        Permission::create(['name' => 'manage_employee']);
        Permission::create(['name' => 'manage_customer']);
        Permission::create(['name' => 'manage_supplier']);
        Permission::create(['name' => 'manage_expense']);
        Permission::create(['name' => 'manage_expense_category']);
        Permission::create(['name' => 'manage_income']);
        Permission::create(['name' => 'manage_income_category']);
        Permission::create(['name' => 'application_setting']);
        Permission::create(['name' => 'manage_user']);
        Permission::create(['name' => 'manage_ledger']);


        // $role = Role::where('name', 'Super Admin')->first();
        // $role->givePermissionTo(Permission::all());
    }
}
