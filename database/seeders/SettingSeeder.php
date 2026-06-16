<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::query()->delete();

        $settings = [
            ['site_name', 'Masjid Locator'],
            ['site_tagline', 'Find Nearest Masjid & Prayer Timings'],
            ['default_city', 'Karachi'],
            ['contact_email', 'info@masjidlocator.com'],
            ['contact_phone', '+92 21 1234567'],
            ['contact_address', 'Karachi, Sindh, Pakistan'],
            ['default_lat', '24.8607'],
            ['default_lng', '67.0011'],
        ];

        foreach ($settings as [$key, $value]) {
            Setting::create([
                'setting_key' => $key,
                'setting_value' => $value,
            ]);
        }
    }
}
