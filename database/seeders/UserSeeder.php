<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'avatar' => 'https://static.vecteezy.com/system/resources/previews/048/926/084/non_2x/silver-membership-icon-default-avatar-profile-icon-membership-icon-social-media-user-image-illustration-vector.jpg',
                'name' => 'Admin',
                'role' => 'admin',
                'phone' => '+12025550123',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'is_admin' => true,
                'status' => 'active',
                'joining_date' => now(),
            ]
        );

        // User
        User::updateOrCreate(
            ['email' => 'user@user.com'],
            [
                'avatar' => 'https://static.vecteezy.com/system/resources/previews/048/926/084/non_2x/silver-membership-icon-default-avatar-profile-icon-membership-icon-social-media-user-image-illustration-vector.jpg',
                'name' => 'User',
                'role' => 'user',
                'phone' => '+12025550124',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'status' => 'active',
                'joining_date' => now(),
            ]
        );

        // Instructor
        User::updateOrCreate(
            ['email' => 'instructor@instructor.com'],
            [
                'avatar' => 'https://static.vecteezy.com/system/resources/previews/048/926/084/non_2x/silver-membership-icon-default-avatar-profile-icon-membership-icon-social-media-user-image-illustration-vector.jpg',
                'name' => 'Instructor',
                'role' => 'instructor',
                'phone' => '+12025550125',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'status' => 'active',
                'joining_date' => now(),
            ]
        );

        // Student
        User::updateOrCreate(
            ['email' => 'student@student.com'],
            [
                'avatar' => 'https://static.vecteezy.com/system/resources/previews/048/926/084/non_2x/silver-membership-icon-default-avatar-profile-icon-membership-icon-social-media-user-image-illustration-vector.jpg',
                'name' => 'Student',
                'role' => 'student',
                'phone' => '+12025550126',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'status' => 'active',
                'joining_date' => now(),
            ]
        );
    }
}





