<?php

namespace Database\Seeders;

use App\Models\Maker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $makers = [
            'Toyota',
            'Honda',
            'Nissan',
            'Mitsubishi',
            'Suzuki',
            'Daihatsu',
            'Kia',
            'Hyundai',
        ];

        foreach ($makers as $maker) {
            Maker::create([
                'title' => $maker,
            ]);
        }
    }
}
