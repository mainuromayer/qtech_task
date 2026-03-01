<?php

namespace Database\Seeders;

use App\Models\CouponAndDeal;
use App\Models\SystemSetting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);

        SystemSetting::updateOrCreate(
            ['id' => 1],
            [
                'system_title' => 'Ediz Phactory',
                'system_short_title' => 'EP',
                'logo' => 'https://thumbs.dreamstime.com/b/demo-demo-icon-139882881.jpg',
                'minilogo' => 'https://thumbs.dreamstime.com/b/demo-demo-icon-139882881.jpg',
                'favicon' => 'https://thumbs.dreamstime.com/b/demo-demo-icon-139882881.jpg',
                'company_name' => 'Ediz Phactory',
                'tag_line' => 'Your learning partner',
                'phone_code' => '+880',
                'phone_number' => '1234567890',
                'whatsapp' => '1234567890',
                'email' => 'info@edizphactory.com',
                'time_zone' => 'Asia/Dhaka',
                'language' => 'en',
                'admin_title' => 'Admin Panel',
                'admin_short_title' => 'AP',
                'admin_logo' => 'https://thumbs.dreamstime.com/b/demo-demo-icon-139882881.jpg',
                'admin_mini_logo' => 'https://thumbs.dreamstime.com/b/demo-demo-icon-139882881.jpg',
                'admin_favicon' => 'https://thumbs.dreamstime.com/b/demo-demo-icon-139882881.jpg',
                'copyright_text' => '© 2026 Ediz Phactory. All rights reserved.',
                'admin_copyright_text' => '© 2026 Ediz Phactory. All rights reserved.',
            ]
        );
    }
}
