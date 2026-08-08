<?php
    if ( ! defined('ABSPATH') ) exit;

    add_action('after_setup_theme', function () {
        // Soportes del tema
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('woocommerce');

        // Registro de menú
        register_nav_menus([
            'main_menu' => __('Main Menu', 'for_hd_print'),
        ]);
    });

    //iconos wordpress
    add_action('wp_enqueue_scripts', function () {
        wp_enqueue_style('dashicons');
    });

    add_action('wp_enqueue_scripts', function () {
        wp_enqueue_style(
            'google-fonts',
            'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700&display=swap',
            [],
            null
        );
    });

    // Estilos principales del tema
    add_action('wp_enqueue_scripts', function () {
        wp_enqueue_style(
            'main-style',
            get_template_directory_uri() . '/assets/css/main.css',
            [], // dependencias
            filemtime(get_template_directory() . '/assets/css/main.css') // versión dinámica segun fecha de modificación
        );
    });


// Cambiar Cart del menú por un icono de carrito
add_filter('wp_nav_menu_objects', function ($items, $args) {

    // Solo modificar el menú principal
    if ($args->theme_location !== 'main_menu') {
        return $items;
    }

    // Comprobar que WooCommerce esté activo
    if (!function_exists('wc_get_cart_url')) {
        return $items;
    }

    foreach ($items as $item) {

        // Detectar el enlace del carrito
        if (untrailingslashit($item->url) === untrailingslashit(wc_get_cart_url())) {

           $cart_count = 0;

if (function_exists('WC') && WC()->cart) {
    $cart_count = WC()->cart->get_cart_contents_count();
}

$cart_count_html = '';

if ($cart_count > 0) {
    $cart_count_html = sprintf(
        '<span class="cart-count">%d</span>',
        $cart_count
    );
}

$item->title = sprintf(
    '<span class="cart-icon dashicons dashicons-cart" aria-hidden="true"></span>%s',
    $cart_count_html
);
            
            break;
        }
    }

    return $items;

}, 10, 2);

