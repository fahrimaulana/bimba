<?php

use Illuminate\Database\Seeder;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;

class ClientPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::set('platform', 'Client');
        Permission::withoutGlobalScopes()->whereScope('Client')->delete();

        Permission::insert([
            ['scope' => 'Client', 'group' => 'Setting', 'name' => 'change-preference', 'display_name' => 'Change Preference'],

            ['scope' => 'Client', 'group' => 'User', 'name' => 'view-user-list', 'display_name' => 'View User List'],
            ['scope' => 'Client', 'group' => 'User', 'name' => 'create-user', 'display_name' => 'Create User'],
            ['scope' => 'Client', 'group' => 'User', 'name' => 'edit-user', 'display_name' => 'Edit User'],
            ['scope' => 'Client', 'group' => 'User', 'name' => 'delete-user', 'display_name' => 'Delete User'],
            ['scope' => 'Client', 'group' => 'User', 'name' => 'view-user-login-history', 'display_name' => 'View User Login History'],
            ['scope' => 'Client', 'group' => 'User', 'name' => 'change-user-password', 'display_name' => 'Change User Password'],

            ['scope' => 'Client', 'group' => 'Role', 'name' => 'view-role-list', 'display_name' => 'View Role List'],
            ['scope' => 'Client', 'group' => 'Role', 'name' => 'create-role', 'display_name' => 'Create Role'],
            ['scope' => 'Client', 'group' => 'Role', 'name' => 'edit-role', 'display_name' => 'Edit Role'],
            ['scope' => 'Client', 'group' => 'Role', 'name' => 'delete-role', 'display_name' => 'Delete Role'],

            ['scope' => 'Client', 'group' => 'Department', 'name' => 'view-department-list', 'display_name' => 'View Department List'],
            ['scope' => 'Client', 'group' => 'Department', 'name' => 'create-department', 'display_name' => 'Create Department'],
            ['scope' => 'Client', 'group' => 'Department', 'name' => 'edit-department', 'display_name' => 'Edit Department'],
            ['scope' => 'Client', 'group' => 'Department', 'name' => 'delete-department', 'display_name' => 'Delete Department'],

            ['scope' => 'Client', 'group' => 'Staff Position', 'name' => 'view-staff-position-list', 'display_name' => 'View Staff Position List'],
            ['scope' => 'Client', 'group' => 'Staff Position', 'name' => 'create-staff-position', 'display_name' => 'Create Staff Position'],
            ['scope' => 'Client', 'group' => 'Staff Position', 'name' => 'edit-staff-position', 'display_name' => 'Edit Staff Position'],
            ['scope' => 'Client', 'group' => 'Staff Position', 'name' => 'delete-staff-position', 'display_name' => 'Delete Staff Position'],

            ['scope' => 'Client', 'group' => 'Absence Reason', 'name' => 'view-absence-reason-list', 'display_name' => 'View Absence Reason List'],
            ['scope' => 'Client', 'group' => 'Absence Reason', 'name' => 'create-absence-reason', 'display_name' => 'Create Absence Reason'],
            ['scope' => 'Client', 'group' => 'Absence Reason', 'name' => 'edit-absence-reason', 'display_name' => 'Edit Absence Reason'],
            ['scope' => 'Client', 'group' => 'Absence Reason', 'name' => 'delete-absence-reason', 'display_name' => 'Delete Absence Reason'],

            ['scope' => 'Client', 'group' => 'Public Relation Status', 'name' => 'view-public-relation-status-list', 'display_name' => 'View Public Relation Status List'],
            ['scope' => 'Client', 'group' => 'Public Relation Status', 'name' => 'create-public-relation-status', 'display_name' => 'Create Public Relation Status'],
            ['scope' => 'Client', 'group' => 'Public Relation Status', 'name' => 'edit-public-relation-status', 'display_name' => 'Edit Public Relation Status'],
            ['scope' => 'Client', 'group' => 'Public Relation Status', 'name' => 'delete-public-relation-status', 'display_name' => 'Delete Public Relation Status'],

            ['scope' => 'Client', 'group' => 'Grade', 'name' => 'view-grade-list', 'display_name' => 'View Grade List'],
            ['scope' => 'Client', 'group' => 'Grade', 'name' => 'create-grade', 'display_name' => 'Create Grade'],
            ['scope' => 'Client', 'group' => 'Grade', 'name' => 'edit-grade', 'display_name' => 'Edit Grade'],
            ['scope' => 'Client', 'group' => 'Grade', 'name' => 'delete-grade', 'display_name' => 'Delete Grade'],

            ['scope' => 'Client', 'group' => 'Class Group', 'name' => 'view-class-group-list', 'display_name' => 'View Class Group List'],
            ['scope' => 'Client', 'group' => 'Class Group', 'name' => 'create-class-group', 'display_name' => 'Create Class Group'],
            ['scope' => 'Client', 'group' => 'Class Group', 'name' => 'edit-class-group', 'display_name' => 'Edit Class Group'],
            ['scope' => 'Client', 'group' => 'Class Group', 'name' => 'delete-class-group', 'display_name' => 'Delete Class Group'],

            ['scope' => 'Client', 'group' => 'Class', 'name' => 'view-class-list', 'display_name' => 'View Class List'],
            ['scope' => 'Client', 'group' => 'Class', 'name' => 'create-class', 'display_name' => 'Create Class'],
            ['scope' => 'Client', 'group' => 'Class', 'name' => 'edit-class', 'display_name' => 'Edit Class'],
            ['scope' => 'Client', 'group' => 'Class', 'name' => 'delete-class', 'display_name' => 'Delete Class'],

            ['scope' => 'Client', 'group' => 'Class Price', 'name' => 'view-class-price-list', 'display_name' => 'View Class Price List'],
            ['scope' => 'Client', 'group' => 'Class Price', 'name' => 'create-class-price', 'display_name' => 'Create Class Price'],
            ['scope' => 'Client', 'group' => 'Class Price', 'name' => 'edit-class-price', 'display_name' => 'Edit Class Price'],
            ['scope' => 'Client', 'group' => 'Class Price', 'name' => 'delete-class-price', 'display_name' => 'Delete Class Price'],

            ['scope' => 'Client', 'group' => 'Media Source', 'name' => 'view-media-source-list', 'display_name' => 'View Media Source List'],
            ['scope' => 'Client', 'group' => 'Media Source', 'name' => 'create-media-source', 'display_name' => 'Create Media Source'],
            ['scope' => 'Client', 'group' => 'Media Source', 'name' => 'edit-media-source', 'display_name' => 'Edit Media Source'],
            ['scope' => 'Client', 'group' => 'Media Source', 'name' => 'delete-media-source', 'display_name' => 'Delete Media Source'],

            ['scope' => 'Client', 'group' => 'Student Phase', 'name' => 'view-student-phase-list', 'display_name' => 'View Student Phase List'],
            ['scope' => 'Client', 'group' => 'Student Phase', 'name' => 'create-student-phase', 'display_name' => 'Create Student Phase'],
            ['scope' => 'Client', 'group' => 'Student Phase', 'name' => 'edit-student-phase', 'display_name' => 'Edit Student Phase'],
            ['scope' => 'Client', 'group' => 'Student Phase', 'name' => 'delete-student-phase', 'display_name' => 'Delete Student Phase'],

            ['scope' => 'Client', 'group' => 'Student Out Reason', 'name' => 'view-student-out-reason-list', 'display_name' => 'View Student Out Reason List'],
            ['scope' => 'Client', 'group' => 'Student Out Reason', 'name' => 'create-student-out-reason', 'display_name' => 'Create Student Out Reason'],
            ['scope' => 'Client', 'group' => 'Student Out Reason', 'name' => 'edit-student-out-reason', 'display_name' => 'Edit Student Out Reason'],
            ['scope' => 'Client', 'group' => 'Student Out Reason', 'name' => 'delete-student-out-reason', 'display_name' => 'Delete Student Out Reason'],

            ['scope' => 'Client', 'group' => 'Student Note', 'name' => 'view-student-note-list', 'display_name' => 'View Student Note List'],
            ['scope' => 'Client', 'group' => 'Student Note', 'name' => 'create-student-note', 'display_name' => 'Create Student Note'],
            ['scope' => 'Client', 'group' => 'Student Note', 'name' => 'edit-student-note', 'display_name' => 'Edit Student Note'],
            ['scope' => 'Client', 'group' => 'Student Note', 'name' => 'delete-student-note', 'display_name' => 'Delete Student Note'],

            ['scope' => 'Client', 'group' => 'Special Allowance Group', 'name' => 'view-special-allowance-group-list', 'display_name' => 'View Special Allowance Group List'],
            ['scope' => 'Client', 'group' => 'Special Allowance Group', 'name' => 'create-special-allowance-group', 'display_name' => 'Create Special Allowance Group'],
            ['scope' => 'Client', 'group' => 'Special Allowance Group', 'name' => 'edit-special-allowance-group', 'display_name' => 'Edit Special Allowance Group'],
            ['scope' => 'Client', 'group' => 'Special Allowance Group', 'name' => 'delete-special-allowance-group', 'display_name' => 'Delete Special Allowance Group'],

            ['scope' => 'Client', 'group' => 'Special Allowance', 'name' => 'view-special-allowance-list', 'display_name' => 'View Special Allowance List'],
            ['scope' => 'Client', 'group' => 'Special Allowance', 'name' => 'create-special-allowance', 'display_name' => 'Create Special Allowance'],
            ['scope' => 'Client', 'group' => 'Special Allowance', 'name' => 'edit-special-allowance', 'display_name' => 'Edit Special Allowance'],
            ['scope' => 'Client', 'group' => 'Special Allowance', 'name' => 'delete-special-allowance', 'display_name' => 'Delete Special Allowance'],

            ['scope' => 'Client', 'group' => 'Unit Profile', 'name' => 'edit-profile', 'display_name' => 'Edit Profile'],

            ['scope' => 'Client', 'group' => 'Product', 'name' => 'view-product-list', 'display_name' => 'View Product List'],
            ['scope' => 'Client', 'group' => 'Product', 'name' => 'create-product', 'display_name' => 'Create Product'],
            ['scope' => 'Client', 'group' => 'Product', 'name' => 'edit-product', 'display_name' => 'Edit Product'],
            ['scope' => 'Client', 'group' => 'Product', 'name' => 'delete-product', 'display_name' => 'Delete Product'],

            ['scope' => 'Client', 'group' => 'Student Statistic', 'name' => 'view-student-statistic', 'display_name' => 'View Student Statistic'],

            ['scope' => 'Client', 'group' => 'Trial Student', 'name' => 'view-trial-student-list', 'display_name' => 'View Trial Student List'],
            ['scope' => 'Client', 'group' => 'Trial Student', 'name' => 'view-trial-student', 'display_name' => 'View Trial Student'],
            ['scope' => 'Client', 'group' => 'Trial Student', 'name' => 'create-trial-student', 'display_name' => 'Create Trial Student'],
            ['scope' => 'Client', 'group' => 'Trial Student', 'name' => 'edit-trial-student', 'display_name' => 'Edit Trial Student'],
            ['scope' => 'Client', 'group' => 'Trial Student', 'name' => 'delete-trial-student', 'display_name' => 'Delete Trial Student'],
            ['scope' => 'Client', 'group' => 'Trial Student', 'name' => 'add-to-master-book', 'display_name' => 'Add to Master Book'],

            ['scope' => 'Client', 'group' => 'Student', 'name' => 'view-tuition', 'display_name' => 'View Tuition'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'view-mbc', 'display_name' => 'View MBC'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'view-certificate', 'display_name' => 'View Certificate'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'view-move-grades', 'display_name' => 'View Move Grades'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'view-student-list', 'display_name' => 'View Student List'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'view-student', 'display_name' => 'View Student'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'create-student', 'display_name' => 'Create Student'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'edit-student', 'display_name' => 'Edit Student'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'print-student', 'display_name' => 'Print Student'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'print-mbc', 'display_name' => 'Print MBC Murid'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'print-certificate', 'display_name' => 'Print Certificate Scholarship'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'delete-student', 'display_name' => 'Delete Student'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'set-student-as-out', 'display_name' => 'Set Student as Out'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'set-student-as-active', 'display_name' => 'Set Student as Active'],
            ['scope' => 'Client', 'group' => 'Student', 'name' => 'extend-scholarship', 'display_name' => 'Extend Scholarship'],

            ['scope' => 'Client', 'group' => 'Staff', 'name' => 'view-staff-list', 'display_name' => 'View Staff List'],
            ['scope' => 'Client', 'group' => 'Staff', 'name' => 'create-staff', 'display_name' => 'Create Staff'],
            ['scope' => 'Client', 'group' => 'Staff', 'name' => 'show-staff', 'display_name' => 'Show Staff'],
            ['scope' => 'Client', 'group' => 'Staff', 'name' => 'edit-staff', 'display_name' => 'Edit Staff'],
            ['scope' => 'Client', 'group' => 'Staff', 'name' => 'delete-staff', 'display_name' => 'Delete Staff'],

            ['scope' => 'Client', 'group' => 'Staff', 'name' => 'view-staff-absence-list', 'display_name' => 'View Staff Absence List'],
            ['scope' => 'Client', 'group' => 'Staff', 'name' => 'create-staff-absence', 'display_name' => 'Create Staff Absence'],
            ['scope' => 'Client', 'group' => 'Staff', 'name' => 'edit-staff-absence', 'display_name' => 'Edit Staff Absence'],
            ['scope' => 'Client', 'group' => 'Staff', 'name' => 'delete-staff-absence', 'display_name' => 'Delete Staff Absence'],

            ['scope' => 'Client', 'group' => 'Public Relation', 'name' => 'view-public-relation-list', 'display_name' => 'View Public Relation List'],
            ['scope' => 'Client', 'group' => 'Public Relation', 'name' => 'create-public-relation', 'display_name' => 'Create Public Relation'],
            ['scope' => 'Client', 'group' => 'Public Relation', 'name' => 'edit-public-relation', 'display_name' => 'Edit Public Relation'],
            ['scope' => 'Client', 'group' => 'Public Relation', 'name' => 'delete-public-relation', 'display_name' => 'Delete Public Relation'],
            ['scope' => 'Client', 'group' => 'Public Relation', 'name' => 'show-public-relation-list', 'display_name' => 'Show Public Relation List'],

            ['scope' => 'Client', 'group' => 'Voucher', 'name' => 'view-voucher-list', 'display_name' => 'View Voucher List'],
            ['scope' => 'Client', 'group' => 'Voucher', 'name' => 'create-voucher', 'display_name' => 'Create Voucher'],
            ['scope' => 'Client', 'group' => 'Voucher', 'name' => 'edit-voucher', 'display_name' => 'Edit Voucher'],
            ['scope' => 'Client', 'group' => 'Voucher', 'name' => 'delete-voucher', 'display_name' => 'Delete Voucher'],

            ['scope' => 'Client', 'group' => 'Transaction', 'name' => 'view-transaction-list', 'display_name' => 'View Transaction List'],
            ['scope' => 'Client', 'group' => 'Transaction', 'name' => 'create-transaction', 'display_name' => 'Create Transaction'],
            ['scope' => 'Client', 'group' => 'Transaction', 'name' => 'edit-transaction', 'display_name' => 'Edit Transaction'],
            ['scope' => 'Client', 'group' => 'Transaction', 'name' => 'delete-transaction', 'display_name' => 'Delete Transaction'],

            ['scope' => 'Client', 'group' => 'Petty Cash', 'name' => 'view-petty-cash-transaction-list', 'display_name' => 'View Petty Cash Transaction List'],
            ['scope' => 'Client', 'group' => 'Petty Cash', 'name' => 'create-petty-cash-transaction', 'display_name' => 'Create Petty Cash Transaction'],
            ['scope' => 'Client', 'group' => 'Petty Cash', 'name' => 'edit-petty-cash-transaction', 'display_name' => 'Edit Petty Cash Transaction'],
            ['scope' => 'Client', 'group' => 'Petty Cash', 'name' => 'delete-petty-cash-transaction', 'display_name' => 'Delete Petty Cash Transaction'],

            ['scope' => 'Client', 'group' => 'Rekap', 'name' => 'view-recap-list', 'display_name' => 'View Recap List'],

            ['scope' => 'Client', 'group' => 'Data SPP', 'name' => 'view-tuition-report', 'display_name' => 'Data SPP'],

            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'view-skim-list', 'display_name' => 'View SKIM List'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'create-skim', 'display_name' => 'Create SKIM'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'edit-skim', 'display_name' => 'Edit SKIM'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'delete-skim', 'display_name' => 'Delete SKIM'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'generate-staff-salary', 'display_name' => 'Generate Staff Salary'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'view-staff-income-list', 'display_name' => 'View Staff Income List'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'edit-staff-income', 'display_name' => 'Edit Staff Income'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'view-staff-deduction-list', 'display_name' => 'View Staff Deduction List'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'edit-staff-deduction', 'display_name' => 'Edit Staff Deduction'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'view-staff-salary-list', 'display_name' => 'View Staff Salary List'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'view-staff-slip-list', 'display_name' => 'View Staff Slip List'],
            ['scope' => 'Client', 'group' => 'Gaji', 'name' => 'print-salary-slip', 'display_name' => 'Print Salary Slip'],

            ['scope' => 'Client', 'group' => 'Progressive', 'name' => 'view-recap-progressive', 'display_name' => 'View Recap Progressive'],
            ['scope' => 'Client', 'group' => 'Progressive', 'name' => 'view-payment-progressive', 'display_name' => 'View Payment Progressive'],
            ['scope' => 'Client', 'group' => 'Progressive', 'name' => 'view-slip-progressive', 'display_name' => 'View Slip Progressive'],

            ['scope' => 'Client', 'group' => 'Bagi Hasil', 'name' => 'view-profit-sharing', 'display_name' => 'View Profit Sharing'],

            ['scope' => 'Client', 'group' => 'Module Statistic', 'name' => 'view-module-statistic', 'display_name' => 'View Module Statistic'],

            ['scope' => 'Client', 'group' => 'Module Price', 'name' => 'view-module-price-list', 'display_name' => 'View Module Price List'],
            ['scope' => 'Client', 'group' => 'Module Price', 'name' => 'create-module-price', 'display_name' => 'Create Module Price'],
            ['scope' => 'Client', 'group' => 'Module Price', 'name' => 'edit-module-price', 'display_name' => 'Edit Module Price'],
            ['scope' => 'Client', 'group' => 'Module Price', 'name' => 'delete-module-price', 'display_name' => 'Delete Module Price'],

            ['scope' => 'Client', 'group' => 'Module Addition', 'name' => 'view-module-addition-list', 'display_name' => 'View Module Addition List'],
            ['scope' => 'Client', 'group' => 'Module Addition', 'name' => 'create-module-addition', 'display_name' => 'Create Module Addition'],
            ['scope' => 'Client', 'group' => 'Module Addition', 'name' => 'edit-module-addition', 'display_name' => 'Edit Module Addition'],
            ['scope' => 'Client', 'group' => 'Module Addition', 'name' => 'delete-module-addition', 'display_name' => 'Delete Module Addition'],

            ['scope' => 'Client', 'group' => 'Module Usage', 'name' => 'view-module-usage-list', 'display_name' => 'View Module Usage List'],
            ['scope' => 'Client', 'group' => 'Module Usage', 'name' => 'create-module-usage', 'display_name' => 'Create Module Usage'],
            ['scope' => 'Client', 'group' => 'Module Usage', 'name' => 'edit-module-usage', 'display_name' => 'Edit Module Usage'],
            ['scope' => 'Client', 'group' => 'Module Usage', 'name' => 'delete-module-usage', 'display_name' => 'Delete Module Usage'],

            ['scope' => 'Client', 'group' => 'Module Stock Recap', 'name' => 'view-module-stock-recap-list', 'display_name' => 'View Module Usage List'],
            ['scope' => 'Client', 'group' => 'Module Stock Recap', 'name' => 'change-module-stock-opname', 'display_name' => 'Change Module Stock Opname'],

            ['scope' => 'Client', 'group' => 'DPU', 'name' => 'view-dpu', 'display_name' => 'View DPU'],

            ['scope' => 'Client', 'group' => 'Order - Statistic', 'name' => 'view-order-statistic', 'display_name' => 'View Order Statistic'],

            ['scope' => 'Client', 'group' => 'Order - Modul', 'name' => 'view-order-module-list', 'display_name' => 'View Order Module List'],

            ['scope' => 'Client', 'group' => 'Order - KA | ME | Tas', 'name' => 'view-order-attribute-list', 'display_name' => 'View Order KA | ME | Tas List'],
            ['scope' => 'Client', 'group' => 'Order - KA | ME | Tas', 'name' => 'edit-order-attribute', 'display_name' => 'Edit Order KA | ME | Tas'],

            ['scope' => 'Client', 'group' => 'Order - Sertifikat', 'name' => 'view-order-certificate-list', 'display_name' => 'View Order Certificate List'],
            ['scope' => 'Client', 'group' => 'Order - Sertifikat', 'name' => 'edit-order-certificate', 'display_name' => 'Edit Order Certificate'],

            ['scope' => 'Client', 'group' => 'Order - STPB', 'name' => 'view-order-stpb-list', 'display_name' => 'View Order STPB List'],
            ['scope' => 'Client', 'group' => 'Order - STPB', 'name' => 'edit-order-stpb', 'display_name' => 'Edit Order STPB'],

            ['scope' => 'Client', 'group' => 'Order - ATK', 'name' => 'view-order-atk-list', 'display_name' => 'View Order ATK List'],
            ['scope' => 'Client', 'group' => 'Order - ATK', 'name' => 'create-order-atk', 'display_name' => 'Create Order ATK'],
            ['scope' => 'Client', 'group' => 'Order - ATK', 'name' => 'edit-order-atk', 'display_name' => 'Edit Order ATK'],
            ['scope' => 'Client', 'group' => 'Order - ATK', 'name' => 'delete-order-atk', 'display_name' => 'Delete Order ATK'],
            ['scope' => 'Client', 'group' => 'Finance', 'name' => 'view-report-finance', 'display_name' => 'View Report Finance'],

            /* Other Permissions */
        ]);

        foreach (Role::withoutGlobalScope('client')->get() as $role) {
            $role->syncPermissions(Permission::all());
        }
    }
}
