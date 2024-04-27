<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\Roles;
use App\Models\AccType;

class CreatedApp extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // check if not empty
        if (User::count() > 0) {
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
        }
        
        // if not empty
        if (Setting::count() > 0) {
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
        }
        

        // if not empty
        if (Roles::count() > 0) {
            // create roles
            Roles::create([
                'role_name' => 'admin',
                'permission_access' => '["DOCMG_ACC","SET_ACC","USER_ACC","USER_ACC_EDIT","USER_ACC_DEL","CUS_ACC_EDIT","CUS_ACC","CUS_ACC_DEL","SET_ROLES","RP_ACC_CAS","RP_ACC_IE","DOCMG_ACC_EDIT","DOCMG_ACC_DEL","ACC_ACC","ACC_REJECT","REC_ACC_DEL","REC_ACC_EDIT","REC_ACC","DOC_ACC","DOC_ACC_EDIT","DOC_ACC_DEL","ACC_ACC_ADD","ACC_ACC_MG"]',
            ]);
        }

        // if not empty
        if (AccType::count() > 0) {
            // create account type
            // create array of acc type
            $acc_type = ['ຄ່າຂີ້ເຫຍື້ອ','ຄ່ານ້ຳ','ຄ່າໄຟ','ຄ່າອິນເຕີເນັດ'];
            foreach ($acc_type as $key => $value) {
                AccType::create([
                    'acc_type_name' => $value,
                ]);
            }
        }
        

    }
}
