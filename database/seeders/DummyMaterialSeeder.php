<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class DummyMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Material::setCurrentUser('system');

        Material::query()->create([
            'code'          => 'DC-UPC',
            'name'          => 'Connector UPC',
            'description'   => '',
            'quantity'      => 100,
            'price'         => 30000,
            'total_price'   => 3000000,
        ]);

        Material::query()->create([
            'code'          => 'FE-SM',
            'name'          => 'Kabel Feeder',
            'description'   => '',
            'quantity'      => 100,
            'price'         => 500000,
            'total_price'   => 50000000,
        ]);

        Material::query()->create([
            'code'          => 'PC-PG',
            'name'          => 'Patch Cord',
            'description'   => '',
            'quantity'      => 100,
            'price'         => 10000,
            'total_price'   => 1000000,
        ]);
    }
}
