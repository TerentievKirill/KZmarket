<?php
if (!defined('ABSPATH')) {
    exit;
}
$categories = kzmarket_categories(10);
$phone = trim((string) get_theme_mod('kzmarket_phone', ''));
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main">Перейти к содержимому</a>
<div class="site-shell">
    <div class="topbar">
        <div class="container topbar-inner">
            <span>Интернет-магазин электроники</span>
            <span class="topbar-links">
                <a href="<?php echo esc_url(home_url('/dostavka-i-oplata/')); ?>">Доставка и оплата</a>
                <a href="<?php echo esc_url(home_url('/kontakty/')); ?>">Контакты</a>
            </span>
        </div>
    </div>

    <header class="site-header">
        <div class="container header-inner">
            <a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="На главную">
                <?php if (has_custom_logo()) : ?>
                    <?php echo wp_get_attachment_image((int) get_theme_mod('custom_logo'), 'medium', false, ['class' => 'brand-image']); ?>
                <?php else : ?>
                    <span class="brand-sign">KZ</span><span class="brand-word">market<span class="brand-period">.</span></span>
                <?php endif; ?>
            </a>

            <button class="catalog-toggle" type="button" aria-expanded="false" aria-controls="catalog-menu">
                <span aria-hidden="true">☰</span><span>Каталог</span>
            </button>

            <form class="header-search" action="<?php echo esc_url(home_url('/')); ?>" role="search" method="get">
                <label class="screen-reader-text" for="product-search">Поиск товаров</label>
                <input id="product-search" name="s" type="search" placeholder="Поиск товаров" value="<?php echo esc_attr(get_search_query()); ?>">
                <input name="post_type" type="hidden" value="product">
                <button type="submit" aria-label="Найти товар">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/></svg>
                </button>
            </form>

            <div class="header-actions">
                <?php if ($phone) : ?>
                    <a class="header-phone" href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                <?php endif; ?>
                <a class="header-action account-link" href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url()); ?>">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="7" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg>
                    <span>Кабинет</span>
                </a>
                <a class="header-action cart-link" href="<?php echo esc_url(kzmarket_cart_url()); ?>">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 3h3l3 13h11l3-9H6"/><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/></svg>
                    <span>Корзина</span>
                    <span class="header-cart-count"><?php echo esc_html((string) kzmarket_cart_count()); ?></span>
                </a>
            </div>
        </div>

        <div class="catalog-menu-wrap" id="catalog-menu" hidden>
            <nav class="container catalog-menu" aria-label="Каталог товаров">
                <?php foreach ($categories as $category) : ?>
                    <a href="<?php echo esc_url(get_term_link($category)); ?>">
                        <span aria-hidden="true"><?php echo esc_html(kzmarket_category_icon($category->name)); ?></span>
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endforeach; ?>
                <a class="catalog-menu-all" href="<?php echo esc_url(kzmarket_shop_url()); ?>">Все товары →</a>
            </nav>
        </div>
    </header>

    <nav class="category-nav" aria-label="Основные категории">
        <div class="container category-nav-inner">
            <?php foreach (array_slice($categories, 0, 8) as $category) : ?>
                <a href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a>
            <?php endforeach; ?>
            <a class="nav-all" href="<?php echo esc_url(kzmarket_shop_url()); ?>">Все товары</a>
        </div>
    </nav>
