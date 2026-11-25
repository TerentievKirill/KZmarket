<?php if (!defined('ABSPATH')) { exit; } ?>
    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <a class="footer-brand" href="<?php echo esc_url(home_url('/')); ?>">KZmarket<span>.</span></a>
                <p>Интернет-магазин электроники. Цены, ассортимент и условия продажи уточняйте у продавца.</p>
            </div>
            <div>
                <h3>Покупателям</h3>
                <a href="<?php echo esc_url(kzmarket_shop_url()); ?>">Каталог</a>
                <a href="<?php echo esc_url(home_url('/dostavka-i-oplata/')); ?>">Доставка и оплата</a>
                <a href="<?php echo esc_url(home_url('/vozvrat/')); ?>">Возврат товара</a>
            </div>
            <div>
                <h3>Информация</h3>
                <a href="<?php echo esc_url(home_url('/kontakty/')); ?>">Контакты</a>
                <a href="<?php echo esc_url(home_url('/politika-konfidencialnosti/')); ?>">Политика конфиденциальности</a>
                <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url()); ?>">Личный кабинет</a>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>© <?php echo esc_html(date_i18n('Y')); ?> <?php bloginfo('name'); ?></span>
            <span>Сделано на WordPress + WooCommerce</span>
        </div>
    </footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
