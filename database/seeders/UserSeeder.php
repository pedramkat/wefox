<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Create Admin user
        $adminuser = User::create([
            'name' => 'Admin',
            'email' => 'admin@wefox.it',
            'password' => bcrypt('admin'),
        ]);
        $adminuser->markEmailAsVerified();
        $adminuser->save();
    }
}
