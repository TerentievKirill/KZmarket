<?php
/**
 * Optional, idempotent sample catalogue. No real product photographs or promises.
 * Run with ./scripts/seed-demo.sh after the initial setup.
 */

if (!defined('ABSPATH') || !function_exists('wc_get_product')) {
    fwrite(STDERR, "Activate WooCommerce first.\n");
    exit(1);
}

$categories = [
    'Ноутбуки', 'Смартфоны', 'Мониторы', 'Наушники',
    'Компьютеры', 'Планшеты', 'Аксессуары',
];

$term_ids = [];
foreach ($categories as $category) {
    $term = term_exists($category, 'product_cat');
    if (!$term) {
        $term = wp_insert_term($category, 'product_cat');
    }
    if (!is_wp_error($term)) {
        $term_ids[$category] = is_array($term) ? (int) $term['term_id'] : (int) $term;
    }
}

$items = [
    ['DEMO-001', 'Ноутбук 15.6″, 16 ГБ RAM, 512 ГБ SSD', '329900', 'Ноутбуки'],
    ['DEMO-002', 'Смартфон 128 ГБ, экран 6.5″', '118900', 'Смартфоны'],
    ['DEMO-003', 'Монитор 27″ Full HD', '89900', 'Мониторы'],
    ['DEMO-004', 'Беспроводные наушники с шумоподавлением', '34900', 'Наушники'],
    ['DEMO-005', 'Компьютер для работы, 16 ГБ RAM', '259900', 'Компьютеры'],
    ['DEMO-006', 'Планшет 10.9″, 128 ГБ', '169900', 'Планшеты'],
    ['DEMO-007', 'Беспроводная клавиатура', '19900', 'Аксессуары'],
    ['DEMO-008', 'Игровой монитор 24″, 165 Гц', '109900', 'Мониторы'],
];

foreach ($items as [$sku, $name, $price, $category]) {
    if (wc_get_product_id_by_sku($sku)) {
        continue;
    }

    $product = new WC_Product_Simple();
    $product->set_name($name);
    $product->set_sku($sku);
    $product->set_regular_price($price);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_manage_stock(true);
    $product->set_stock_quantity(10);
    $product->set_description('Демонстрационный товар для проверки каталога. Характеристики, фотографии и цены необходимо заменить перед запуском продаж.');
    $product->set_short_description('Демонстрационная карточка — не предназначена для реальных заказов.');
    if (isset($term_ids[$category])) {
        $product->set_category_ids([$term_ids[$category]]);
    }
    $product->save();
    echo "Created {$sku}\n";
}

echo "Demo catalogue is ready. Remove DEMO-* products before launch.\n";
