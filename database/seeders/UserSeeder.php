<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Đặng Minh Đạt',
                'email' => 'datdmgcc210147@fpt.edu.vn',
                'lecturer_id' => 'GCC210147',
                'student_id' => null,
                'role' => '1',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Nguyễn Huỳnh Ngọc Thi',
                'email' => 'thinhngdc210099@fpt.edu.vn',
                'lecturer_id' => null,
                'student_id' => 'GDC210099',
                'role' => '2',
                'password' => Hash::make('password456'),
            ],
            // Add more sample users as needed
        ];

        // Insert the sample data into the database
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
