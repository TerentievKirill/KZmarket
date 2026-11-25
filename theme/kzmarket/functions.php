<?php
/**
 * KZmarket: a lightweight WooCommerce theme, with no page builder dependency.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    load_theme_textdomain('kzmarket', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', ['height' => 70, 'width' => 260, 'flex-height' => true, 'flex-width' => true]);
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus([
        'primary' => 'Основное меню',
        'footer'  => 'Меню в подвале',
    ]);
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'kzmarket-style',
        get_stylesheet_uri(),
        [],
        (string) filemtime(get_stylesheet_directory() . '/style.css')
    );
    wp_enqueue_script(
        'kzmarket-navigation',
        get_template_directory_uri() . '/assets/navigation.js',
        [],
        '1.0.0',
        true
    );
}, 20);

add_filter('woocommerce_placeholder_img_src', function () {
    return get_template_directory_uri() . '/assets/product-placeholder.svg';
});

/** Make the optional demo catalogue readable without shipping anyone else's photos. */
add_filter('woocommerce_product_get_image', function ($image, $product) {
    if ($product->get_image_id()) {
        return $image;
    }
    $demo_art = [
        'DEMO-001' => 'product-placeholder.svg',
        'DEMO-002' => 'demo-phone.svg',
        'DEMO-003' => 'demo-monitor.svg',
        'DEMO-004' => 'demo-headphones.svg',
    ];
    $sku = $product->get_sku();
    if (!isset($demo_art[$sku])) {
        return $image;
    }
    $src = get_template_directory_uri() . '/assets/' . $demo_art[$sku];
    return '<img src="' . esc_url($src) . '" alt="' . esc_attr($product->get_name()) . '" loading="lazy" width="480" height="480">';
}, 10, 2);

function kzmarket_shop_url(): string
{
    if (function_exists('wc_get_page_id')) {
        $page_id = wc_get_page_id('shop');
        if ($page_id > 0) {
            return get_permalink($page_id);
        }
    }
    return home_url('/shop/');
}

function kzmarket_cart_url(): string
{
    return function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
}

function kzmarket_cart_count(): int
{
    return function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

function kzmarket_categories(int $limit = 8): array
{
    if (!taxonomy_exists('product_cat')) {
        return [];
    }

    $terms = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => 0,
        'number' => $limit,
        'orderby' => 'name',
        'exclude' => [(int) get_option('default_product_cat', 0)],
    ]);

    return is_wp_error($terms) ? [] : $terms;
}

function kzmarket_category_icon(string $name): string
{
    $name = function_exists('mb_strtolower')
        ? mb_strtolower($name, 'UTF-8')
        : strtr($name, 'АБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЬЭЮЯ', 'абвгдежзийклмнопрстуфхцчшщыьэюя');
    if (str_contains($name, 'ноут') || str_contains($name, 'компьют')) {
        return '💻';
    }
    if (str_contains($name, 'смарт') || str_contains($name, 'телефон')) {
        return '📱';
    }
    if (str_contains($name, 'науш') || str_contains($name, 'аудио')) {
        return '🎧';
    }
    if (str_contains($name, 'монитор') || str_contains($name, 'экран')) {
        return '🖥️';
    }
    if (str_contains($name, 'планшет')) {
        return '📲';
    }
    if (str_contains($name, 'аксесс')) {
        return '🔌';
    }
    return '📦';
}

add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    ob_start();
    ?>
    <span class="header-cart-count"><?php echo esc_html((string) kzmarket_cart_count()); ?></span>
    <?php
    $fragments['span.header-cart-count'] = ob_get_clean();
    return $fragments;
});

/** Dynamic catalogue filtering for any product attributes configured in WooCommerce. */
add_action('woocommerce_product_query', function ($query) {
    if (is_admin() || !function_exists('wc_get_attribute_taxonomies')) {
        return;
    }

    $tax_query = (array) $query->get('tax_query');
    foreach (wc_get_attribute_taxonomies() as $attribute) {
        $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
        $query_key = 'attr_' . $attribute->attribute_name;
        if (!taxonomy_exists($taxonomy) || !isset($_GET[$query_key]) || !is_string($_GET[$query_key])) {
            continue;
        }

        $value = sanitize_title(wp_unslash($_GET[$query_key]));
        if ($value !== '') {
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => [$value],
            ];
        }
    }

    if ($tax_query) {
        $query->set('tax_query', $tax_query);
    }
});

/** Add a sensible placeholder for new shops, without inventing merchant promises. */
add_action('customize_register', function ($customize) {
    $customize->add_section('kzmarket_contacts', [
        'title' => 'KZmarket: контакты',
        'priority' => 31,
    ]);
    $customize->add_setting('kzmarket_phone', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $customize->add_control('kzmarket_phone', [
        'label' => 'Номер телефона (виден в шапке)',
        'section' => 'kzmarket_contacts',
        'type' => 'text',
    ]);

    $customize->add_section('kzmarket_home', [
        'title' => 'KZmarket: главный баннер',
        'priority' => 32,
    ]);
    $customize->add_setting('kzmarket_hero_title', [
        'default' => 'Техника для работы и жизни',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $customize->add_control('kzmarket_hero_title', [
        'label' => 'Заголовок баннера',
        'section' => 'kzmarket_home',
        'type' => 'text',
    ]);
    $customize->add_setting('kzmarket_hero_text', [
        'default' => 'Ноутбуки, смартфоны, компьютеры и аксессуары в одном каталоге.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $customize->add_control('kzmarket_hero_text', [
        'label' => 'Подзаголовок баннера',
        'section' => 'kzmarket_home',
        'type' => 'textarea',
    ]);
    $customize->add_setting('kzmarket_hero_image', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $customize->add_control(new WP_Customize_Image_Control($customize, 'kzmarket_hero_image', [
        'label' => 'Изображение баннера',
        'section' => 'kzmarket_home',
    ]));
});
