<?php

namespace Database\Seeders;

use Beike\Models\Product;
use Beike\Models\ProductCategory;
use Beike\Models\ProductDescription;
use Beike\Models\ProductRelation;
use Beike\Models\ProductSku;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    private array $catalog = [
        1  => ['StreetShield Full-Face Helmet', 1, 100006, 2990, 3490, 'Full-face helmet with clear visor, ventilation channels and removable comfort liner.'],
        2  => ['BayanGrip Touring Gloves',      2, 100006, 790,  990,  'Touch-friendly touring gloves with reinforced palm panels and adjustable wrist closure.'],
        3  => ['RidgeLine Rear Shock Pair',     5, 100003, 2490, 2890, 'Twin rear shocks tuned for commuter comfort with adjustable spring preload.'],
        4  => ['StopSure Disc Brake Kit',       7, 100005, 1290, 1590, 'Road-use brake disc and pad kit intended for routine replacement and safer stopping.'],
        5  => ['SparkPro Iridium Plug',         1, 100003, 420,  520,  'Long-life iridium spark plug for cleaner ignition and reliable cold starts.'],
        6  => ['LumenX LED Headlight',          4, 100010, 890,  1090, 'Bright LED headlight assembly for improved nighttime visibility and lower power draw.'],
        7  => ['DriveMate 428 Chain Set',       3, 100012, 1390, 1690, '428 chain and sprocket set for dependable everyday power transfer.'],
        8  => ['RainGuard Rider Jacket',        2, 100006, 1690, 1990, 'Lightweight weather-resistant riding jacket with reflective panels and ventilation.'],
        9  => ['AirFlow Performance Filter',    1, 100003, 650,  790,  'Reusable high-flow air filter for common small-displacement commuter motorcycles.'],
        10 => ['MotoBayan 10W-40 Motorcycle Oil',1,100007, 380,  450,  'Four-stroke 10W-40 motorcycle oil for daily city riding and regular maintenance.'],
        11 => ['RoadLock Disc Alarm',           6, 100005, 1090, 1290, 'Compact disc lock with motion alarm for an added theft deterrent while parked.'],
        12 => ['TrailBox 32L Top Case',         8, 100018, 2890, 3290, '32-liter top case with mounting plate for helmets, rain gear and daily essentials.'],
    ];

    public function run()
    {
        Product::query()->truncate();
        foreach ($this->getProducts() as $item) {
            $item['images'] = json_decode($item['images'], true);
            $item['variables'] = json_decode($item['variables'], true);
            Product::query()->create($item);
        }

        ProductCategory::query()->truncate();
        foreach ($this->getProductCategories() as $item) {
            ProductCategory::query()->create($item);
        }

        ProductDescription::query()->truncate();
        foreach ($this->getProductDescriptions() as $item) {
            ProductDescription::query()->create($item);
        }

        ProductSku::query()->truncate();
        foreach ($this->getProductSkus() as $item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
            $item['images'] = json_decode($item['images'], true);
            $item['variants'] = json_decode($item['variants'], true);
            ProductSku::query()->create($item);
        }

        ProductRelation::query()->truncate();
        ProductRelation::query()->insert(collect($this->getProductRelations())->map(function ($item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
            return $item;
        })->toArray());
    }

    private function getProducts(): array
    {
        $rows = [];
        foreach ($this->catalog as $id => [$name, $brandId]) {
            $rows[] = [
                'id' => $id,
                'brand_id' => $brandId,
                'images' => json_encode([sprintf('image/motobayan/products/%02d.webp', $id)]),
                'price' => 0,
                'video' => '',
                'position' => $id,
                'active' => 1,
                'variables' => '[]',
                'tax_class_id' => 1,
                'sales' => max(0, 28 - $id),
                'deleted_at' => null,
            ];
        }
        return $rows;
    }

    private function getProductCategories(): array
    {
        $rows = [];
        foreach ($this->catalog as $id => $item) {
            $rows[] = ['product_id' => $id, 'category_id' => $item[2]];
        }
        return $rows;
    }

    private function getProductDescriptions(): array
    {
        $rows = [];
        $rowId = 1;
        foreach ($this->catalog as $id => [$name, $brandId, $categoryId, $price, $origin, $description]) {
            $content = '<p>' . e($description) . '</p><p><strong>Fitment note:</strong> Verify dimensions and model compatibility before ordering. Sample catalog data is provided for the remodeled demo store.</p>';
            foreach (['en', 'zh_cn'] as $locale) {
                $rows[] = [
                    'id' => $rowId++,
                    'product_id' => $id,
                    'locale' => $locale,
                    'name' => $name,
                    'content' => $content,
                    'meta_title' => $name . ' | MotoBayan PH',
                    'meta_description' => $description,
                    'meta_keywords' => 'motorcycle parts, Philippines, MotoBayan, ' . strtolower($name),
                ];
            }
        }
        return $rows;
    }

    private function getProductSkus(): array
    {
        $rows = [];
        foreach ($this->catalog as $id => [$name, $brandId, $categoryId, $price, $origin]) {
            $rows[] = [
                'id' => 1000 + $id,
                'product_id' => $id,
                'variants' => '[]',
                'position' => 0,
                'images' => json_encode([sprintf('image/motobayan/products/%02d.webp', $id)]),
                'model' => 'MB-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT),
                'sku' => 'MBPH-' . str_pad((string) $id, 5, '0', STR_PAD_LEFT),
                'weight' => 0,
                'price' => $price,
                'origin_price' => $origin,
                'cost_price' => round($price * 0.62, 2),
                'quantity' => 25 + ($id * 3),
                'is_default' => 1,
                'active' => 1,
            ];
        }
        return $rows;
    }

    private function getProductRelations(): array
    {
        $rows = [];
        foreach (array_keys($this->catalog) as $id) {
            foreach (array_values(array_filter([$id - 1, $id + 1], fn ($relation) => isset($this->catalog[$relation]))) as $relation) {
                $rows[] = ['product_id' => $id, 'relation_id' => $relation];
            }
        }
        return $rows;
    }
}
