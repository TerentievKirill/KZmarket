<?php
if (!defined('ABSPATH')) { exit; }
get_header();
$is_catalog = is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy();
?>
<main id="main" class="container shop-layout <?php echo $is_catalog ? 'has-sidebar' : ''; ?>">
    <?php if ($is_catalog) : ?>
        <aside class="catalog-sidebar" aria-label="Фильтры товаров">
            <h2>Каталог</h2>
            <ul class="sidebar-categories">
                <?php foreach (kzmarket_categories(30) as $category) : ?>
                    <li><a href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a></li>
                <?php endforeach; ?>
            </ul>
            <form class="filters-form" method="get" action="<?php echo esc_url(is_product_category() ? get_term_link(get_queried_object()) : kzmarket_shop_url()); ?>">
                <h3>Цена, ₸</h3>
                <div class="price-inputs">
                    <label>От <input name="min_price" type="number" min="0" placeholder="0" value="<?php echo isset($_GET['min_price']) ? esc_attr((string) absint(wp_unslash($_GET['min_price']))) : ''; ?>"></label>
                    <label>До <input name="max_price" type="number" min="0" placeholder="Любая" value="<?php echo isset($_GET['max_price']) ? esc_attr((string) absint(wp_unslash($_GET['max_price']))) : ''; ?>"></label>
                </div>
                <?php if (function_exists('wc_get_attribute_taxonomies')) : ?>
                    <?php foreach (array_slice(wc_get_attribute_taxonomies(), 0, 8) as $attribute) : ?>
                        <?php
                        $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
                        if (!taxonomy_exists($taxonomy)) { continue; }
                        $values = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => true]);
                        if (is_wp_error($values) || !$values) { continue; }
                        $query_key = 'attr_' . $attribute->attribute_name;
                        $current = isset($_GET[$query_key]) && is_string($_GET[$query_key]) ? sanitize_title(wp_unslash($_GET[$query_key])) : '';
                        ?>
                        <label class="brand-filter"><?php echo esc_html($attribute->attribute_label); ?>
                            <select name="<?php echo esc_attr($query_key); ?>">
                                <option value="">Любое значение</option>
                                <?php foreach ($values as $value) : ?>
                                    <option value="<?php echo esc_attr($value->slug); ?>" <?php selected($current, $value->slug); ?>><?php echo esc_html($value->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
                <button class="button button-primary" type="submit">Применить</button>
                <a class="clear-filters" href="<?php echo esc_url(is_product_category() ? get_term_link(get_queried_object()) : kzmarket_shop_url()); ?>">Сбросить фильтры</a>
            </form>
        </aside>
    <?php endif; ?>
    <div class="shop-content <?php echo $is_catalog ? '' : 'full-width'; ?>">
        <?php woocommerce_content(); ?>
    </div>
</main>
<?php get_footer(); ?>
