<?php

namespace Database\Seeders;

use Beike\Repositories\SettingRepo;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run()
    {
        SettingRepo::update('system', 'base', ['menu_setting' => $this->getMenuSetting()]);
        SettingRepo::update('system', 'base', ['design_setting' => $this->getHomeSetting()]);
        SettingRepo::update('system', 'base', ['footer_setting' => $this->getFooterSetting()]);
    }

    private function bilingual(string $text): array
    {
        return ['en' => $text, 'zh_cn' => $text];
    }

    private function menu(string $name, string $type, string|int $value, bool $featured = false): array
    {
        return [
            'isFull' => false,
            'badge' => [
                'isShow' => $featured,
                'name' => $this->bilingual($featured ? 'RIDER PICK' : ''),
                'bg_color' => '#cc333f',
                'text_color' => '#ffffff',
            ],
            'link' => ['type' => $type, 'value' => $value, 'text' => [], 'link' => ''],
            'name' => $this->bilingual($name),
            'isChildren' => false,
            'childrenGroup' => [],
        ];
    }

    private function getMenuSetting(): array
    {
        return ['menus' => [
            $this->menu('All Parts', 'static', 'categories.index'),
            $this->menu('Performance', 'category', 100003, true),
            $this->menu('Safety', 'category', 100005),
            $this->menu('Riding Gear', 'category', 100006),
            $this->menu('Maintenance', 'category', 100007),
            $this->menu('Lighting', 'category', 100010),
            $this->menu('Touring', 'category', 100018),
        ]];
    }

    private function getHomeSetting(): array
    {
        $categoryCards = [
            [100003, '/image/motobayan/categories/engine-performance.webp', 'Performance & Suspension'],
            [100005, '/image/motobayan/categories/brakes-safety.webp', 'Brakes & Safety'],
            [100006, '/image/motobayan/categories/helmets-gear.webp', 'Helmets & Riding Gear'],
            [100007, '/image/motobayan/categories/maintenance-fluids.webp', 'Maintenance & Fluids'],
            [100010, '/image/motobayan/categories/electrical-lighting.webp', 'Electrical & Lighting'],
            [100012, '/image/motobayan/categories/tires-drive.webp', 'Drivetrain & Wheels'],
            [100018, '/image/motobayan/categories/touring-accessories.webp', 'Touring & Accessories'],
        ];

        return ['modules' => [
            [
                'code' => 'img_text_slideshow_2',
                'module_id' => 'motobayanHero2026',
                'name' => 'MotoBayan Hero',
                'view_path' => '',
                'content' => [
                    'style' => ['background_color' => '#0a182a'],
                    'floor' => $this->bilingual(''),
                    'module_size' => 'container-fluid',
                    'scroll_text' => [
                        'text' => $this->bilingual('Built for the daily biyahe — parts, gear and practical upgrades for Filipino riders.'),
                        'bg' => '#f4b52f',
                        'color' => '#0a182a',
                        'font_size' => '14',
                        'padding' => '12',
                    ],
                    'images' => [[
                        'image' => ['src' => '/image/motobayan/banners/hero-road.webp', 'alt' => $this->bilingual('Stylized motorcycle riding through mountain roads')],
                        'sub_title' => $this->bilingual('MOTO BAYAN • PARA SA BIYAHE'),
                        'title' => $this->bilingual('Ride ready. Every day.'),
                        'description' => $this->bilingual('Practical motorcycle parts and riding gear with a clean, local-first shopping experience.'),
                        'text_position' => 'start',
                        'show' => true,
                        'link' => ['type' => 'static', 'value' => 'categories.index', 'link' => ''],
                    ]],
                ],
            ],
            [
                'code' => 'icons',
                'module_id' => 'motobayanCategories2026',
                'name' => 'Shop by Category',
                'view_path' => '',
                'content' => [
                    'style' => ['background_color' => ''],
                    'module_size' => 'container-fluid',
                    'title' => $this->bilingual('Shop by ride need'),
                    'sub_title' => $this->bilingual('From maintenance day to long rides, find the part of the garage you need.'),
                    'floor' => $this->bilingual(''),
                    'images' => array_map(function ($card) {
                        return [
                            'image' => ['src' => $card[1], 'alt' => $this->bilingual($card[2])],
                            'link' => ['type' => 'category', 'value' => $card[0], 'link' => ''],
                            'text' => $this->bilingual($card[2]),
                            'sub_text' => $this->bilingual('Browse parts'),
                            'show' => true,
                        ];
                    }, $categoryCards),
                ],
            ],
            [
                'code' => 'tab_product',
                'module_id' => 'motobayanFeatured2026',
                'name' => 'Featured Products',
                'view_path' => '',
                'content' => [
                    'style' => ['background_color' => ''],
                    'module_size' => 'container-fluid',
                    'floor' => $this->bilingual(''),
                    'editableTabsValue' => '0',
                    'title' => $this->bilingual('Garage favorites'),
                    'tabs' => [
                        ['title' => $this->bilingual('Daily rider'), 'products' => [1, 2, 4, 6, 7, 10, 11, 12]],
                        ['title' => $this->bilingual('Tune & maintain'), 'products' => [3, 5, 9, 10]],
                    ],
                ],
            ],
            [
                'code' => 'img_text_banner',
                'module_id' => 'motobayanTouring2026',
                'name' => 'Touring Banner',
                'view_path' => '',
                'content' => [
                    'style' => ['background_color' => ''],
                    'floor' => $this->bilingual(''),
                    'module_size' => 'container-fluid',
                    'bg_color' => '#0a182a',
                    'text_color' => '#ffffff',
                    'btn_bg' => '#f4b52f',
                    'btn_color' => '#0a182a',
                    'image' => ['src' => '/image/motobayan/products/12.webp', 'alt' => $this->bilingual('Motorcycle top case illustration')],
                    'title' => $this->bilingual('Pack for the long ride'),
                    'sub_title' => $this->bilingual('Touring essentials without the clutter'),
                    'description' => $this->bilingual('Storage, locks and rider-ready accessories for weekend loops, work commutes and everything in between.'),
                    'link' => ['type' => 'category', 'value' => 100018, 'link' => ''],
                    'image_position' => 'left',
                    'text_position' => 'left',
                    'text_max_width' => '600',
                ],
            ],
            [
                'code' => 'brand',
                'module_id' => 'motobayanBrands2026',
                'name' => 'Brand Module',
                'view_path' => '',
                'content' => [
                    'style' => ['background_color' => ''],
                    'module_size' => 'container-fluid',
                    'floor' => $this->bilingual(''),
                    'full' => true,
                    'title' => $this->bilingual('Garage brands'),
                    'brands' => [1,2,3,4,5,6,7,8],
                ],
            ],
        ]];
    }

    private function getFooterSetting(): array
    {
        return [
            'services' => [
                'enable' => true,
                'items' => [
                    ['image' => 'image/motobayan/icons/delivery.png', 'title' => $this->bilingual('Nationwide-ready shipping'), 'sub_title' => $this->bilingual('Sample flat-rate shipping is configured for the demo.'), 'show' => true],
                    ['image' => 'image/motobayan/icons/fitment.png', 'title' => $this->bilingual('Fitment first'), 'sub_title' => $this->bilingual('Check motorcycle model and dimensions before ordering.'), 'show' => true],
                    ['image' => 'image/motobayan/icons/secure.png', 'title' => $this->bilingual('Secure checkout foundation'), 'sub_title' => $this->bilingual('CSRF protection, secure sessions and hardened headers.'), 'show' => true],
                    ['image' => 'image/motobayan/icons/support.png', 'title' => $this->bilingual('Rider support'), 'sub_title' => $this->bilingual('Sample support details are clearly marked for replacement.'), 'show' => true],
                ],
            ],
            'content' => [
                'intro' => [
                    'logo' => 'image/motobayan/motobayan-logo.png',
                    'text' => $this->bilingual('<p><strong>MotoBayan PH</strong> is a remodeled demo storefront for motorcycle parts, commuter gear and practical ride upgrades. Replace sample contacts, policies and payment setup before launch.</p>'),
                    'social_network' => [],
                ],
                'link1' => [
                    'title' => $this->bilingual('Shop'),
                    'links' => [
                        ['type' => 'static', 'value' => 'categories.index', 'text' => $this->bilingual('All parts'), 'link' => ''],
                        ['type' => 'static', 'value' => 'brands.index', 'text' => $this->bilingual('Brands'), 'link' => ''],
                        ['type' => 'category', 'value' => 100006, 'text' => $this->bilingual('Riding gear'), 'link' => ''],
                    ],
                ],
                'link2' => [
                    'title' => $this->bilingual('Account'),
                    'links' => [
                        ['type' => 'static', 'value' => 'account.index', 'text' => $this->bilingual('My account'), 'link' => ''],
                        ['type' => 'static', 'value' => 'account.order.index', 'text' => $this->bilingual('Orders'), 'link' => ''],
                        ['type' => 'static', 'value' => 'account.wishlist.index', 'text' => $this->bilingual('Wishlist'), 'link' => ''],
                    ],
                ],
                'link3' => [
                    'title' => $this->bilingual('Rider notes'),
                    'links' => [
                        ['type' => 'category', 'value' => 100007, 'text' => $this->bilingual('Maintenance items'), 'link' => ''],
                        ['type' => 'category', 'value' => 100005, 'text' => $this->bilingual('Safety parts'), 'link' => ''],
                        ['type' => 'category', 'value' => 100018, 'text' => $this->bilingual('Touring accessories'), 'link' => ''],
                    ],
                ],
                'contact' => [
                    'email' => true,
                    'telephone' => '+63 917 000 0000',
                    'address' => $this->bilingual('Imus, Cavite, Philippines — sample demo location'),
                ],
            ],
            'bottom' => [
                'copyright' => $this->bilingual('© ' . date('Y') . ' MotoBayan PH. Demo storefront for academic/development use.'),
                'image' => '',
            ],
        ];
    }
}
