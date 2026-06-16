<?php

namespace Database\Seeders;

use App\Models\Masjid;
use Illuminate\Database\Seeder;

class MasjidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Masjid::query()->delete();

        $masjids = [
            [
                'name' => 'Baitul Mukarram Masjid Karachi',
                'address' => 'Main University Road, Gulshan-e-Iqbal, Karachi, Pakistan',
                'area' => 'Gulshan-e-Iqbal',
                'city' => 'Karachi',
                'sect' => 'Sunni',
                'latitude' => 24.89740000,
                'longitude' => 67.07680000,
                'fajr' => '04:42',
                'zuhr' => '12:37',
                'asr' => '16:58',
                'maghrib' => '19:21',
                'isha' => '20:46',
                'juma_time' => '13:25',
                'eid_time' => '07:00',
                'phone' => '+92 21 34987654',
                'description' => 'A well-known mosque in Gulshan-e-Iqbal, Karachi.',
                'is_featured' => true,
            ],
            [
                'name' => 'Tooba Masjid DHA Karachi',
                'address' => 'Korangi Road, Phase 2, DHA, Karachi, Pakistan',
                'area' => 'DHA Phase 2',
                'city' => 'Karachi',
                'sect' => 'Sunni',
                'latitude' => 24.84360000,
                'longitude' => 67.05040000,
                'fajr' => '04:44',
                'zuhr' => '12:39',
                'asr' => '17:00',
                'maghrib' => '19:23',
                'isha' => '20:48',
                'juma_time' => '13:28',
                'eid_time' => '07:02',
                'phone' => '+92 21 35881234',
                'description' => 'Prominent landmark and major community mosque in DHA.',
                'is_featured' => true,
            ],
            [
                'name' => 'Masjid-e-Yasrab DHA Karachi',
                'address' => 'Phase 4, DHA, Karachi, Pakistan',
                'area' => 'DHA Phase 4',
                'city' => 'Karachi',
                'sect' => 'Shia',
                'latitude' => 24.82220000,
                'longitude' => 67.07440000,
                'fajr' => '04:45',
                'zuhr' => '12:40',
                'asr' => '17:01',
                'maghrib' => '19:24',
                'isha' => '20:49',
                'juma_time' => '13:35',
                'eid_time' => '07:06',
                'phone' => '+92 21 35891122',
                'description' => 'A prominent Shia mosque and community center in DHA Phase 4.',
                'is_featured' => true,
            ],
        ];

        foreach ($masjids as $masjid) {
            Masjid::create($masjid);
        }
    }
}
