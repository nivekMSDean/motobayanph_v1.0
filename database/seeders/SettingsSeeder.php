<?php

namespace Database\Seeders;

use Beike\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        Setting::query()->truncate();
        foreach ($this->getItems() as $item) {
            Setting::query()->create($item);
        }
    }

    public function getItems(): array
    {
        return [
            ['type' => 'system', 'space' => 'base', 'name' => 'country_id', 'value' => '168', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'locale', 'value' => 'en', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'theme', 'value' => 'default', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'status', 'value' => '', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'admin_name', 'value' => 'admin', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'tax', 'value' => '0', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'tax_address', 'value' => 'payment', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'currency', 'value' => 'PHP', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'zone_id', 'value' => '2575', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'logo', 'value' => 'image/motobayan/motobayan-logo.png', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'placeholder', 'value' => 'image/placeholder.png', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'favicon', 'value' => 'image/motobayan/favicon.png', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'meta_title', 'value' => 'MotoBayan PH | Motorcycle Parts & Riding Gear', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'meta_description', 'value' => 'MotoBayan PH is a Filipino-focused demo motorcycle parts and riding gear storefront built on BeikeShop and Laravel.', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'meta_keywords', 'value' => 'motorcycle parts Philippines, motorbike parts, riding gear, helmet, motorcycle accessories, MotoBayan', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'telephone', 'value' => '+63 917 000 0000', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'email', 'value' => 'hello@motobayan.example', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'default_customer_group_id', 'value' => '1', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'rate_api_key', 'value' => '', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'guest_checkout', 'value' => '1', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'show_price_after_login', 'value' => '0', 'json' => 0],
            ['type' => 'system', 'space' => 'base', 'name' => 'hot_keywords', 'value' => json_encode([
                'zh_cn' => 'helmet,brake pads,LED light,chain,10W-40,top box',
                'en' => 'helmet,brake pads,LED light,chain,10W-40,top box'
            ], JSON_UNESCAPED_UNICODE), 'json' => 1],

            // Shipping is enabled with sample demo pricing. Adjust before production.
            ['type' => 'plugin', 'space' => 'flat_shipping', 'name' => 'type', 'value' => 'fixed', 'json' => 0],
            ['type' => 'plugin', 'space' => 'flat_shipping', 'name' => 'value', 'value' => '120', 'json' => 0],
            ['type' => 'plugin', 'space' => 'flat_shipping', 'name' => 'status', 'value' => '1', 'json' => 0],
            ['type' => 'plugin', 'space' => 'latest_products', 'name' => 'status', 'value' => '1', 'json' => 0],

            // Payment providers are intentionally disabled and contain no credentials.
            ['type' => 'plugin', 'space' => 'stripe', 'name' => 'publishable_key', 'value' => '', 'json' => 0],
            ['type' => 'plugin', 'space' => 'stripe', 'name' => 'secret_key', 'value' => '', 'json' => 0],
            ['type' => 'plugin', 'space' => 'stripe', 'name' => 'test_mode', 'value' => '1', 'json' => 0],
            ['type' => 'plugin', 'space' => 'stripe', 'name' => 'status', 'value' => '0', 'json' => 0],
            ['type' => 'plugin', 'space' => 'paypal', 'name' => 'currency', 'value' => 'PHP', 'json' => 0],
            ['type' => 'plugin', 'space' => 'paypal', 'name' => 'sandbox_client_id', 'value' => '', 'json' => 0],
            ['type' => 'plugin', 'space' => 'paypal', 'name' => 'sandbox_secret', 'value' => '', 'json' => 0],
            ['type' => 'plugin', 'space' => 'paypal', 'name' => 'live_client_id', 'value' => '', 'json' => 0],
            ['type' => 'plugin', 'space' => 'paypal', 'name' => 'live_secret', 'value' => '', 'json' => 0],
            ['type' => 'plugin', 'space' => 'paypal', 'name' => 'sandbox_mode', 'value' => '1', 'json' => 0],
            ['type' => 'plugin', 'space' => 'paypal', 'name' => 'status', 'value' => '0', 'json' => 0],
        ];
    }
}
