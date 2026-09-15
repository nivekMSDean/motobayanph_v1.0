<?php

namespace Database\Seeders;

use Beike\Models\Category;
use Beike\Models\CategoryDescription;
use Beike\Models\CategoryPath;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        Category::query()->truncate();
        foreach ($this->getCategories() as $item) {
            Category::query()->create($item);
        }

        CategoryDescription::query()->truncate();
        foreach ($this->getCategoryDescriptions() as $item) {
            CategoryDescription::query()->create($item);
        }

        CategoryPath::query()->truncate();
        CategoryPath::query()->insert(collect($this->getCategoryPaths())->map(function ($item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
            return $item;
        })->toArray());
    }

    public function getCategories(): array
    {
        return [
            ['id' => 100003, 'parent_id' => 0, 'position' => 1, 'active' => 1, 'image' => 'image/motobayan/categories/engine-performance.webp'],
            ['id' => 100005, 'parent_id' => 0, 'position' => 2, 'active' => 1, 'image' => 'image/motobayan/categories/brakes-safety.webp'],
            ['id' => 100006, 'parent_id' => 0, 'position' => 3, 'active' => 1, 'image' => 'image/motobayan/categories/helmets-gear.webp'],
            ['id' => 100007, 'parent_id' => 0, 'position' => 4, 'active' => 1, 'image' => 'image/motobayan/categories/maintenance-fluids.webp'],
            ['id' => 100010, 'parent_id' => 0, 'position' => 5, 'active' => 1, 'image' => 'image/motobayan/categories/electrical-lighting.webp'],
            ['id' => 100012, 'parent_id' => 0, 'position' => 6, 'active' => 1, 'image' => 'image/motobayan/categories/tires-drive.webp'],
            ['id' => 100018, 'parent_id' => 0, 'position' => 7, 'active' => 1, 'image' => 'image/motobayan/categories/touring-accessories.webp'],
        ];
    }

    public function getCategoryDescriptions(): array
    {
        $categories = [
            100003 => ['Performance & Suspension', 'Engine tune-up, intake, ignition and suspension parts for daily rides and weekend builds.'],
            100005 => ['Brakes & Safety', 'Brake components, locks and safety upgrades selected for reliable everyday use.'],
            100006 => ['Helmets & Riding Gear', 'Protective helmets, jackets and gloves designed for commuters and touring riders.'],
            100007 => ['Maintenance & Fluids', 'Oil, service items and consumables for regular motorcycle maintenance.'],
            100010 => ['Electrical & Lighting', 'Lighting and electrical upgrades for clearer visibility and dependable starts.'],
            100012 => ['Drivetrain & Wheels', 'Chains, sprockets and road-ready drivetrain parts for smooth power delivery.'],
            100018 => ['Touring & Accessories', 'Storage and practical accessories for city riding, errands and long-distance trips.'],
        ];

        $rows = [];
        $id = 1;
        foreach ($categories as $categoryId => [$name, $description]) {
            foreach (['en', 'zh_cn'] as $locale) {
                $rows[] = [
                    'id' => $id++,
                    'category_id' => $categoryId,
                    'locale' => $locale,
                    'name' => $name,
                    'content' => $description,
                    'meta_title' => $name . ' | MotoBayan PH',
                    'meta_description' => $description,
                    'meta_keywords' => 'motorcycle parts, motorbike parts, Philippines, ' . strtolower($name),
                ];
            }
        }
        return $rows;
    }

    public function getCategoryPaths(): array
    {
        $ids = [100003, 100005, 100006, 100007, 100010, 100012, 100018];
        return array_map(fn ($id, $index) => [
            'id' => $index + 1,
            'category_id' => $id,
            'path_id' => $id,
            'level' => 0,
        ], $ids, array_keys($ids));
    }
}
