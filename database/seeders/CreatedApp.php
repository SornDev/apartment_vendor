<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\Roles;

class CreatedApp extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // cerate user
       User::create([
            'name' => 'admin',
            'user_name' => 'admin',
            'gender'=>'male',
            'staff_type'=>'user',
            'password' => Hash::make('admin9'),
            'roles' => 1, // 1 = admin, 2 = user
            'user_type' => 'admin',
            'status' => 'active',
        ]);

        // create setting
        Setting::create([
            'company_name' => 'shipping',
            'company_tel' => '020...',
            'company_address' => '',
            'company_email' => '',
            'company_logo' => '',
            'printer_default' => 'a4',
            'tax_value' => 10,
        ]);

        // create roles
        Roles::create([
            'role_name' => 'admin',
            'permission_access' => '["REC_ACC","REC_ACC_EDIT","REC_ACC_DEL","CUS_ACC","DOCMG_ACC","DOCMG_ACC_EDIT","DOCMG_ACC_DEL","SET_ROLES","ACC_ACC","ACC_REJECT","SET_ACC","USER_ACC","RP_ACC_IE","RP_ACC_CAS","USER_ACC_EDIT","CUS_ACC_EDIT","CUS_ACC_DEL","USER_ACC_DEL","DOC_ACC","DOC_ACC_EDIT","DOC_ACC_DEL"]',
        ]);

    }
}
