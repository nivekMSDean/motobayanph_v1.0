<?php

namespace Database\Seeders;

use Beike\Models\Brand;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    public function run()
    {
        Brand::query()->truncate();
        foreach ($this->getItems() as $item) {
            Brand::query()->create($item);
        }
    }

    public function getItems(): array
    {
        return [
            ['id' => 1, 'name' => 'AeroMoto',  'first' => 'A', 'logo' => 'image/motobayan/brands/01.webp', 'sort_order' => 1, 'active' => 1],
            ['id' => 2, 'name' => 'BayanGrip', 'first' => 'B', 'logo' => 'image/motobayan/brands/02.webp', 'sort_order' => 2, 'active' => 1],
            ['id' => 3, 'name' => 'DriveMate', 'first' => 'D', 'logo' => 'image/motobayan/brands/03.webp', 'sort_order' => 3, 'active' => 1],
            ['id' => 4, 'name' => 'LumenX',    'first' => 'L', 'logo' => 'image/motobayan/brands/04.webp', 'sort_order' => 4, 'active' => 1],
            ['id' => 5, 'name' => 'RidgeLine', 'first' => 'R', 'logo' => 'image/motobayan/brands/05.webp', 'sort_order' => 5, 'active' => 1],
            ['id' => 6, 'name' => 'RoadLock',  'first' => 'R', 'logo' => 'image/motobayan/brands/06.webp', 'sort_order' => 6, 'active' => 1],
            ['id' => 7, 'name' => 'StopSure',  'first' => 'S', 'logo' => 'image/motobayan/brands/07.webp', 'sort_order' => 7, 'active' => 1],
            ['id' => 8, 'name' => 'TrailBox',  'first' => 'T', 'logo' => 'image/motobayan/brands/08.webp', 'sort_order' => 8, 'active' => 1],
        ];
    }
}
