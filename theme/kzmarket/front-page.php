<?php
if (!defined('ABSPATH')) { exit; }
get_header();
$categories = kzmarket_categories(6);
?>
<main id="main">
    <div class="container">
        <section class="hero" aria-label="Главное предложение">
            <div class="hero-text">
                <span class="eyebrow">Техника на каждый день</span>
                <h1><?php echo esc_html(get_theme_mod('kzmarket_hero_title', 'Техника для работы и жизни')); ?></h1>
                <p><?php echo esc_html(get_theme_mod('kzmarket_hero_text', 'Ноутбуки, смартфоны, компьютеры и аксессуары в одном каталоге.')); ?></p>
                <a class="button button-white" href="<?php echo esc_url(kzmarket_shop_url()); ?>">Перейти в каталог <span aria-hidden="true">→</span></a>
            </div>
            <div class="hero-picture" aria-hidden="true">
                <img src="<?php echo esc_url(get_theme_mod('kzmarket_hero_image', '') ?: get_template_directory_uri() . '/assets/hero-devices.svg'); ?>" alt="" width="600" height="370">
            </div>
        </section>

        <section class="home-section home-categories" aria-labelledby="categories-title">
            <div class="section-heading">
                <h2 id="categories-title">Категории товаров</h2>
                <a href="<?php echo esc_url(kzmarket_shop_url()); ?>">Весь каталог <span aria-hidden="true">→</span></a>
            </div>
            <div class="category-tiles">
                <?php if ($categories) : ?>
                    <?php foreach ($categories as $category) : ?>
                        <a class="category-tile" href="<?php echo esc_url(get_term_link($category)); ?>">
                            <span class="category-icon" aria-hidden="true"><?php echo esc_html(kzmarket_category_icon($category->name)); ?></span>
                            <strong><?php echo esc_html($category->name); ?></strong>
                            <span class="category-arrow" aria-hidden="true">↗</span>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php foreach (['Ноутбуки', 'Смартфоны', 'Мониторы', 'Аксессуары'] as $name) : ?>
                        <a class="category-tile" href="<?php echo esc_url(kzmarket_shop_url()); ?>">
                            <span class="category-icon" aria-hidden="true"><?php echo esc_html(kzmarket_category_icon($name)); ?></span>
                            <strong><?php echo esc_html($name); ?></strong>
                            <span class="category-arrow" aria-hidden="true">↗</span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="home-section" aria-labelledby="products-title">
            <div class="section-heading">
                <h2 id="products-title">Популярные товары</h2>
                <a href="<?php echo esc_url(kzmarket_shop_url()); ?>">Смотреть все <span aria-hidden="true">→</span></a>
            </div>
            <?php if (function_exists('wc_get_products') && wc_get_products(['limit' => 1, 'status' => 'publish', 'return' => 'ids'])) : ?>
                <?php echo do_shortcode('[products limit="8" columns="4" orderby="date" order="DESC"]'); ?>
            <?php else : ?>
                <div class="empty-catalogue">
                    <span aria-hidden="true">📦</span>
                    <div>
                        <h3>Каталог скоро появится</h3>
                        <p>В панели управления можно добавить товары вручную или загрузить CSV-файл.</p>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <?php if (function_exists('wc_get_product_ids_on_sale') && wc_get_product_ids_on_sale()) : ?>
            <section class="home-section" aria-labelledby="sale-title">
                <div class="section-heading">
                    <h2 id="sale-title">Акции и скидки</h2>
                    <a href="<?php echo esc_url(kzmarket_shop_url()); ?>">Весь каталог <span aria-hidden="true">→</span></a>
                </div>
                <?php echo do_shortcode('[sale_products limit="4" columns="4"]'); ?>
            </section>
        <?php endif; ?>

        <section class="features-strip" aria-label="Возможности магазина">
            <div><span aria-hidden="true">🚚</span><strong>Удобная доставка</strong><p>Варианты получения заказа</p></div>
            <div><span aria-hidden="true">💳</span><strong>Выбор способа оплаты</strong><p>При оформлении заказа</p></div>
            <div><span aria-hidden="true">🛡️</span><strong>Информация о гарантии</strong><p>В карточке товара</p></div>
            <div><span aria-hidden="true">💬</span><strong>Помощь с выбором</strong><p>Свяжитесь с магазином</p></div>
        </section>
    </div>
</main>
<?php get_footer(); ?>
