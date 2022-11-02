<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() == 0)
        {
            User::create([
                'name' => 'Librarian User',
                'email' => 'admin@example.com',
                'password' => bcrypt('secret'),
                'is_verified' => 1,
                'status' => 'active',
                'role' => 'librarian',
            ]);
        }
    }
}
