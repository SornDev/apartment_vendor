<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
            'status' => 'active',
        ]);

    }
}
