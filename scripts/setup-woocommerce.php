<?php
if (!defined('ABSPATH') || !class_exists('WooCommerce')) { exit; }

if (get_option('kzmarket_initial_setup_done')) {
    return;
}

update_option('woocommerce_currency', 'KZT');
update_option('woocommerce_default_country', 'KZ');
update_option('woocommerce_enable_guest_checkout', 'yes');
update_option('woocommerce_manage_stock', 'yes');
global $wp_rewrite;
$wp_rewrite->set_permalink_structure('/%postname%/');
$wp_rewrite->flush_rules();

// This is an order-request flow, not an online payment integration.
update_option('woocommerce_cod_settings', [
    'enabled' => 'yes',
    'title' => 'Оплата при получении',
    'description' => 'Заказ поступит менеджеру. Способ оплаты и доставки согласовываются после оформления.',
    'instructions' => 'Ожидайте подтверждения заказа от менеджера.',
]);

// New shops should not charge tax or promise shipping rates until configured.
update_option('woocommerce_calc_taxes', 'no');

// A clear placeholder shipping method lets customers complete test orders.
// The actual delivery price must be configured by the merchant before launch.
if (class_exists('WC_Shipping_Zone')) {
    $zone = new WC_Shipping_Zone(0); // Locations not covered by other zones.
    $found = false;
    foreach ($zone->get_shipping_methods() as $method) {
        if ($method->id === 'flat_rate') {
            $found = true;
            break;
        }
    }
    if (!$found) {
        $instance_id = $zone->add_shipping_method('flat_rate');
        if ($instance_id) {
            update_option('woocommerce_flat_rate_' . $instance_id . '_settings', [
                'enabled' => 'yes',
                'title' => 'Доставка — стоимость уточняется менеджером',
                'tax_status' => 'none',
                'cost' => '0',
            ]);
        }
    }
}

update_option('kzmarket_initial_setup_done', 'yes');
