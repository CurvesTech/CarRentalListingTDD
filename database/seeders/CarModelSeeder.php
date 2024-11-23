<?php

namespace Database\Seeders;

use App\Models\Maker;
use App\Enums\CarShape;
use App\Models\CarModel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CarModelSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach( $this->data() as $maker => $models ) {
            $maker = Maker::where('title', $maker)->first();
            foreach( $models as $model ) {
                CarModel::create([
                    'maker_id' => $maker->id,
                    'title' => $model[0],
                    'shape' => $model[1],
                ]);
            }
        }
    }

    private function data() : array {
        return [
            'Toyota' => [
                ['Corolla', CarShape::SEDAN],
                ['Camry', CarShape::SEDAN],
                ['Yaris', CarShape::HATCHBACK],
                ['Prius', CarShape::HATCHBACK],
                ['Vitz', CarShape::HATCHBACK],
            ],
            'Honda' => [
                ['Civic', CarShape::SEDAN],
                ['Accord', CarShape::SEDAN],
                ['Fit', CarShape::HATCHBACK],
                ['CR-V', CarShape::SUV],
                ['HR-V', CarShape::SUV],
            ],
            'Nissan' => [
                ['Almera', CarShape::SEDAN],
                ['Teana', CarShape::SEDAN],
                ['March', CarShape::HATCHBACK],
                ['X-Trail', CarShape::SUV],
                ['Juke', CarShape::SUV],
            ],
            'Mitsubishi' => [
                ['Lancer', CarShape::SEDAN],
                ['Mirage', CarShape::HATCHBACK],
                ['Outlander', CarShape::SUV],
                ['Pajero', CarShape::SUV],
                ['Eclipse Cross', CarShape::SUV],
            ], 
            'Suzuki' => [
                ['Swift', CarShape::HATCHBACK],
                ['Baleno', CarShape::HATCHBACK],
                ['Ignis', CarShape::HATCHBACK],
                ['Vitara', CarShape::SUV],
                ['Jimny', CarShape::SUV],
            ],
            'Daihatsu' => [
                ['Ayla', CarShape::HATCHBACK],
                ['Sigra', CarShape::HATCHBACK],
                ['Terios', CarShape::SUV],
                ['Rocky', CarShape::SUV],
                ['Xenia', CarShape::HATCHBACK],
            ],
            'Kia' => [
                ['Rio', CarShape::HATCHBACK],
                ['Picanto', CarShape::HATCHBACK],
                ['Seltos', CarShape::SUV],
                ['Sportage', CarShape::SUV],
                ['Carnival', CarShape::OTHER],
            ],
            'Hyundai' => [
                ['Accent', CarShape::SEDAN],
                ['Elantra', CarShape::SEDAN],
                ['i20', CarShape::HATCHBACK],
                ['Kona', CarShape::SUV],
                ['Santa Fe', CarShape::SUV],
            ]
        ];
    }
}
