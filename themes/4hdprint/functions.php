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


