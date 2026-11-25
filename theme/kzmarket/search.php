<?php
if (!defined('ABSPATH')) { exit; }
get_header();
$product_search = get_query_var('post_type') === 'product';
?>
<main id="main" class="container content-page">
    <h1>Результаты поиска: <?php echo esc_html(get_search_query()); ?></h1>
    <?php if (have_posts()) : ?>
        <?php if ($product_search && function_exists('woocommerce_product_loop_start')) : ?>
            <div class="woocommerce">
                <?php woocommerce_product_loop_start(); ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
                <?php woocommerce_product_loop_end(); ?>
            </div>
        <?php else : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php the_excerpt(); ?></article>
            <?php endwhile; ?>
        <?php endif; ?>
        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p>По вашему запросу товары не найдены. Попробуйте другое название.</p>
        <a class="button button-primary" href="<?php echo esc_url(kzmarket_shop_url()); ?>">Открыть каталог</a>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
