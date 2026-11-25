<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main id="main" class="container content-page">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>><h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1><?php the_excerpt(); ?></article>
        <?php endwhile; ?>
        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <h1>Ничего не найдено</h1>
        <p>Попробуйте изменить поисковый запрос или перейти в каталог.</p>
        <a class="button button-primary" href="<?php echo esc_url(kzmarket_shop_url()); ?>">Перейти в каталог</a>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
