<?php
/** Create minimal placeholders to avoid broken menu/footer links on a demo install. */
if (!defined('ABSPATH')) { exit; }

$pages = [
    'dostavka-i-oplata' => [
        'Доставка и оплата',
        '<p>Условия доставки и способы оплаты будут опубликованы владельцем магазина до начала продаж.</p>',
    ],
    'kontakty' => [
        'Контакты',
        '<p>Контактный телефон, адрес и режим работы необходимо заполнить перед запуском магазина.</p>',
    ],
    'vozvrat' => [
        'Возврат товара',
        '<p>Информация о порядке возврата и обмена товаров будет опубликована владельцем магазина.</p>',
    ],
    'politika-konfidencialnosti' => [
        'Политика конфиденциальности',
        '<p>Это временная страница. До начала обработки персональных данных покупателей владелец магазина должен разместить действующую политику конфиденциальности.</p>',
    ],
];

foreach ($pages as $slug => [$title, $content]) {
    if (get_page_by_path($slug)) { continue; }
    wp_insert_post([
        'post_title' => $title,
        'post_name' => $slug,
        'post_content' => $content,
        'post_type' => 'page',
        'post_status' => 'publish',
    ]);
    echo "Created page: {$slug}\n";
}
