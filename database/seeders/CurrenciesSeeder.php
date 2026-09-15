<?php

namespace Database\Seeders;

use Beike\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    public function run()
    {
        Currency::query()->truncate();
        foreach ($this->getItems() as $item) {
            Currency::query()->create($item);
        }
    }

    public function getItems(): array
    {
        return [[
            'id' => 1,
            'name' => 'Philippine Peso',
            'code' => 'PHP',
            'symbol_left' => '₱',
            'symbol_right' => '',
            'decimal_place' => 2,
            'value' => 1,
            'active' => 1,
        ]];
    }
}
