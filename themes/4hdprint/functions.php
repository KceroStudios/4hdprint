<?php
    if ( ! defined('ABSPATH') ) exit;

    add_action('after_setup_theme', function () {
        // Soportes del tema
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Registro de menú
        register_nav_menus([
            'main_menu' => __('Main Menu', 'for_hd_print'),
            'footer_menu' => __('Footer Menu', 'for_hd_print'),
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

    // JavaScript principal del tema
add_action('wp_enqueue_scripts', function () {

    wp_enqueue_script(
        '4hd-main-js',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        filemtime(get_template_directory() . '/assets/js/main.js'),
        true
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

/**
 * Homepage Banner - Admin Page
 */
add_action('admin_menu', function () {

    add_theme_page(
        'Homepage Banner',
        'Homepage Banner',
        'manage_options',
        '4hd-homepage-banner',
        'four_hd_homepage_banner_page'
    );

});


/**
 * Homepage Banner - Settings
 */
add_action('admin_init', function () {

    register_setting(
        '4hd_homepage_banner_settings',
        '4hd_homepage_banner'
    );

});


/**
 * Homepage Banner - Admin Page HTML
 */
function four_hd_homepage_banner_page() {

    $banner = get_option('4hd_homepage_banner', [
        'enabled'     => 1,
        'image'       => '',
        'title'       => '',
        'description' => '',
        'button_text' => '',
        'button_url'  => '',
    ]);

    ?>

    <div class="wrap">

        <h1>4HD PRINT — Homepage Banner</h1>

        <form method="post" action="options.php">

            <?php
            settings_fields('4hd_homepage_banner_settings');
            ?>

            <table class="form-table">

                <tr>
                    <th scope="row">
                        Enable Banner
                    </th>

                    <td>

                        <label>
                            <input
                                type="checkbox"
                                name="4hd_homepage_banner[enabled]"
                                value="1"
                                <?php checked($banner['enabled'], 1); ?>
                            >

                            Show banner on homepage

                        </label>

                    </td>
                </tr>


                <tr>

    <th scope="row">
        Banner Image
    </th>

    <td>

        <?php
        $image_id = ! empty($banner['image']) ? absint($banner['image']) : 0;
        ?>

        <input
            type="hidden"
            id="4hd_banner_image"
            name="4hd_homepage_banner[image]"
            value="<?php echo esc_attr($image_id); ?>"
        >

        <div id="4hd_banner_preview">

            <?php
            if ($image_id) {
                echo wp_get_attachment_image(
                    $image_id,
                    'medium'
                );
            }
            ?>

        </div>

        <button
            type="button"
            class="button"
            id="4hd_banner_select_image"
        >
            Select Image
        </button>

        <button
            type="button"
            class="button"
            id="4hd_banner_remove_image"
            <?php echo $image_id ? '' : 'style="display:none;"'; ?>
        >
            Remove Image
        </button>

    </td>

</tr>


                <tr>

                    <th scope="row">
                        Title
                    </th>

                    <td>

                        <input
                            type="text"
                            name="4hd_homepage_banner[title]"
                            value="<?php echo esc_attr($banner['title']); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>


                <tr>

                    <th scope="row">
                        Description
                    </th>

                    <td>

                        <textarea
                            name="4hd_homepage_banner[description]"
                            rows="4"
                            class="large-text"
                        ><?php echo esc_textarea($banner['description']); ?></textarea>

                    </td>

                </tr>


                <tr>

                    <th scope="row">
                        Button Text
                    </th>

                    <td>

                        <input
                            type="text"
                            name="4hd_homepage_banner[button_text]"
                            value="<?php echo esc_attr($banner['button_text']); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>


                <tr>

                    <th scope="row">
                        Button URL
                    </th>

                    <td>

                       <input
    type="text"
    name="4hd_homepage_banner[button_url]"
    value="<?php echo esc_attr($banner['button_url']); ?>"
    class="regular-text"
>

                    </td>

                </tr>

            </table>

            <?php submit_button('Save Banner'); ?>

        </form>

    </div>

    <?php
}

/**
 * Homepage Banner - Media Library
 */
add_action('admin_enqueue_scripts', function ($hook) {

    if ($hook !== 'appearance_page_4hd-homepage-banner') {
        return;
    }

    wp_enqueue_media();

});

/**
 * Homepage Banner - Media Library JavaScript
 */
add_action('admin_footer', function () {

    $screen = get_current_screen();

    if (!$screen || $screen->id !== 'appearance_page_4hd-homepage-banner') {
        return;
    }

    ?>

    <script>

        jQuery(document).ready(function ($) {

            let mediaFrame;

            $('#4hd_banner_select_image').on('click', function (event) {

                event.preventDefault();

                if (mediaFrame) {
                    mediaFrame.open();
                    return;
                }

                mediaFrame = wp.media({
                    title: 'Select Banner Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });

                mediaFrame.on('select', function () {

                    const attachment = mediaFrame
                        .state()
                        .get('selection')
                        .first()
                        .toJSON();

                    $('#4hd_banner_image').val(attachment.id);

                    $('#4hd_banner_preview').html(
                        '<img src="' + attachment.url + '" style="max-width:400px;height:auto;">'
                    );

                    $('#4hd_banner_remove_image').show();

                });

                mediaFrame.open();

            });


            $('#4hd_banner_remove_image').on('click', function (event) {

                event.preventDefault();

                $('#4hd_banner_image').val('');

                $('#4hd_banner_preview').html('');

                $(this).hide();

            });

        });

    </script>

    <?php

});

/**
 * Promotional Banner - Admin Page
 */
add_action('admin_menu', function () {

    add_theme_page(
        'Promotional Banner',
        'Promotional Banner',
        'manage_options',
        '4hd-promotional-banner',
        'four_hd_promotional_banner_page'
    );

});


/**
 * Promotional Banner - Settings
 */
add_action('admin_init', function () {

    register_setting(
        '4hd_promotional_banner_settings',
        '4hd_promotional_banner'
    );

});


/**
 * Promotional Banner - Admin Page HTML
 */
function four_hd_promotional_banner_page() {

    $banner = get_option('4hd_promotional_banner', [
        'enabled'     => 1,
        'image'       => '',
        'title'       => '',
        'description' => '',
        'offer'       => '',
        'button_text' => '',
        'button_url'  => '',
    ]);

    ?>

    <div class="wrap">

        <h1>4HD PRINT — Promotional Banner</h1>

        <form method="post" action="options.php">

            <?php settings_fields('4hd_promotional_banner_settings'); ?>

            <table class="form-table">

                <!-- Enable -->

                <tr>

                    <th scope="row">
                        Enable Banner
                    </th>

                    <td>

                        <label>

                            <input
                                type="checkbox"
                                name="4hd_promotional_banner[enabled]"
                                value="1"
                                <?php checked($banner['enabled'], 1); ?>
                            >

                            Show promotional banner on homepage

                        </label>

                    </td>

                </tr>


                <!-- Image -->

                <tr>

                    <th scope="row">
                        Image
                    </th>

                    <td>

                        <?php
                        $image_id = ! empty($banner['image'])
                            ? absint($banner['image'])
                            : 0;
                        ?>

                        <input
                            type="hidden"
                            id="4hd_promotional_image"
                            name="4hd_promotional_banner[image]"
                            value="<?php echo esc_attr($image_id); ?>"
                        >

                        <div
                            id="4hd_promotional_preview"
                            style="margin-bottom:15px;"
                        >

                            <?php

                            if ($image_id) {

                                echo wp_get_attachment_image(
                                    $image_id,
                                    'medium'
                                );

                            }

                            ?>

                        </div>


                        <button
                            type="button"
                            class="button"
                            id="4hd_promotional_select_image"
                        >
                            Select Image
                        </button>


                        <button
                            type="button"
                            class="button"
                            id="4hd_promotional_remove_image"
                            <?php echo $image_id ? '' : 'style="display:none;"'; ?>
                        >
                            Remove Image
                        </button>

                    </td>

                </tr>


                <!-- Title -->

                <tr>

                    <th scope="row">
                        Title
                    </th>

                    <td>

                        <input
                            type="text"
                            name="4hd_promotional_banner[title]"
                            value="<?php echo esc_attr($banner['title']); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>


                <!-- Description -->

                <tr>

                    <th scope="row">
                        Description
                    </th>

                    <td>

                        <textarea
                            name="4hd_promotional_banner[description]"
                            rows="4"
                            class="large-text"
                        ><?php echo esc_textarea($banner['description']); ?></textarea>

                    </td>

                </tr>


                <!-- Offer -->

                <tr>

                    <th scope="row">
                        Offer
                    </th>

                    <td>

                        <input
                            type="text"
                            name="4hd_promotional_banner[offer]"
                            value="<?php echo esc_attr($banner['offer']); ?>"
                            class="regular-text"
                        >

                        <p class="description">
                            Example: 500 cards — $39.99
                        </p>

                    </td>

                </tr>


                <!-- Button Text -->

                <tr>

                    <th scope="row">
                        Button Text
                    </th>

                    <td>

                        <input
                            type="text"
                            name="4hd_promotional_banner[button_text]"
                            value="<?php echo esc_attr($banner['button_text']); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>


                <!-- Button URL -->

                <tr>

                    <th scope="row">
                        Button URL
                    </th>

                    <td>

                        <input
                            type="text"
                            name="4hd_promotional_banner[button_url]"
                            value="<?php echo esc_attr($banner['button_url']); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>

            </table>


            <?php submit_button('Save Promotional Banner'); ?>

        </form>

    </div>

    <?php
}


/**
 * Promotional Banner - Media Library
 */
add_action('admin_enqueue_scripts', function ($hook) {

    if ($hook !== 'appearance_page_4hd-promotional-banner') {
        return;
    }

    wp_enqueue_media();

});


/**
 * Promotional Banner - Media Library JavaScript
 */
add_action('admin_footer', function () {

    $screen = get_current_screen();

    if (
        ! $screen ||
        $screen->id !== 'appearance_page_4hd-promotional-banner'
    ) {
        return;
    }

    ?>

    <script>

        jQuery(document).ready(function ($) {

            let mediaFrame;


            $('#4hd_promotional_select_image').on('click', function (event) {

                event.preventDefault();


                if (mediaFrame) {

                    mediaFrame.open();
                    return;

                }


                mediaFrame = wp.media({
                    title: 'Select Promotional Banner Image',
                    button: {
                        text: 'Use This Image'
                    },
                    multiple: false
                });


                mediaFrame.on('select', function () {

                    const attachment =
                        mediaFrame.state()
                        .get('selection')
                        .first()
                        .toJSON();


                    $('#4hd_promotional_image')
                        .val(attachment.id);


                    $('#4hd_promotional_preview')
                        .html(
                            '<img src="' +
                            attachment.url +
                            '" style="max-width:300px;height:auto;">'
                        );


                    $('#4hd_promotional_remove_image')
                        .show();

                });


                mediaFrame.open();

            });


            $('#4hd_promotional_remove_image').on('click', function (event) {

                event.preventDefault();


                $('#4hd_promotional_image')
                    .val('');


                $('#4hd_promotional_preview')
                    .html('');


                $(this).hide();

            });

        });

    </script>

    <?php

});

/**
 * Client Logos - Admin Page
 */
add_action('admin_menu', function () {

    add_theme_page(
        'Client Logos',
        'Client Logos',
        'manage_options',
        '4hd-client-logos',
        'four_hd_client_logos_page'
    );

});


/**
 * Client Logos - Settings
 */
add_action('admin_init', function () {

    register_setting(
        '4hd_client_logos_settings',
        '4hd_client_logos'
    );

});


/**
 * Client Logos - Admin Page
 */
function four_hd_client_logos_page() {

    $logos = get_option('4hd_client_logos', []);

    ?>

    <div class="wrap">

        <h1>4HD PRINT — Client Logos</h1>

        <p>
            Add the logos of clients you want to display
            on the homepage.
        </p>


        <form method="post" action="options.php">

            <?php
            settings_fields('4hd_client_logos_settings');
            ?>


            <div id="4hd-client-logos">

                <?php

                if ( ! empty($logos) ) :

                    foreach ($logos as $index => $logo) :

                        $image_id = ! empty($logo['image'])
                            ? absint($logo['image'])
                            : 0;

                        ?>

                        <div
                            class="4hd-client-logo"
                            style="
                                background:#fff;
                                border:1px solid #ccd0d4;
                                padding:20px;
                                margin-bottom:15px;
                                max-width:600px;
                            "
                        >

                            <input
                                type="hidden"
                                class="client-logo-image"
                                name="4hd_client_logos[<?php echo $index; ?>][image]"
                                value="<?php echo esc_attr($image_id); ?>"
                            >


                            <div
                                class="client-logo-preview"
                                style="margin-bottom:15px;"
                            >

                                <?php

                                if ($image_id) {

                                    echo wp_get_attachment_image(
                                        $image_id,
                                        'medium'
                                    );

                                }

                                ?>

                            </div>


                            <button
                                type="button"
                                class="button select-client-logo"
                            >
                                Select Logo
                            </button>


                            <button
                                type="button"
                                class="button remove-client-logo"
                            >
                                Remove
                            </button>


                            <button
                                type="button"
                                class="button remove-client-logo-row"
                                style="color:#b32d2e;"
                            >
                                Delete Logo
                            </button>

                        </div>

                        <?php

                    endforeach;

                endif;

                ?>

            </div>


            <p>

                <button
                    type="button"
                    class="button button-secondary"
                    id="4hd-add-client-logo"
                >
                    + Add Client Logo
                </button>

            </p>


            <?php submit_button('Save Client Logos'); ?>

        </form>

    </div>


    <script>

        jQuery(document).ready(function ($) {

            let mediaFrame;


            /*
             * Select logo
             */

            $(document).on(
                'click',
                '.select-client-logo',
                function (event) {

                    event.preventDefault();

                    const button = $(this);
                    const container = button.closest(
                        '.4hd-client-logo'
                    );

                    mediaFrame = wp.media({

                        title: 'Select Client Logo',

                        button: {
                            text: 'Use This Logo'
                        },

                        multiple: false

                    });


                    mediaFrame.on(
                        'select',
                        function () {

                            const attachment =
                                mediaFrame.state()
                                    .get('selection')
                                    .first()
                                    .toJSON();


                            container
                                .find('.client-logo-image')
                                .val(attachment.id);


                            container
                                .find('.client-logo-preview')
                                .html(
                                    '<img src="' +
                                    attachment.url +
                                    '" style="max-width:300px;height:auto;">'
                                );

                        }
                    );


                    mediaFrame.open();

                }
            );


            /*
             * Remove image
             */

            $(document).on(
                'click',
                '.remove-client-logo',
                function (event) {

                    event.preventDefault();

                    const container = $(this).closest(
                        '.4hd-client-logo'
                    );


                    container
                        .find('.client-logo-image')
                        .val('');


                    container
                        .find('.client-logo-preview')
                        .html('');

                }
            );


            /*
             * Delete logo row
             */

            $(document).on(
                'click',
                '.remove-client-logo-row',
                function (event) {

                    event.preventDefault();

                    $(this)
                        .closest('.4hd-client-logo')
                        .remove();

                }
            );


            /*
             * Add new logo
             */

            $('#4hd-add-client-logo').on(
                'click',
                function (event) {

                    event.preventDefault();


                    const index =
                        $('#4hd-client-logos .4hd-client-logo')
                        .length;


                    const html = `

                        <div
                            class="4hd-client-logo"
                            style="
                                background:#fff;
                                border:1px solid #ccd0d4;
                                padding:20px;
                                margin-bottom:15px;
                                max-width:600px;
                            "
                        >

                            <input
                                type="hidden"
                                class="client-logo-image"
                                name="4hd_client_logos[${index}][image]"
                                value=""
                            >

                            <div
                                class="client-logo-preview"
                                style="margin-bottom:15px;"
                            ></div>

                            <button
                                type="button"
                                class="button select-client-logo"
                            >
                                Select Logo
                            </button>

                            <button
                                type="button"
                                class="button remove-client-logo"
                            >
                                Remove
                            </button>

                            <button
                                type="button"
                                class="button remove-client-logo-row"
                                style="color:#b32d2e;"
                            >
                                Delete Logo
                            </button>

                        </div>

                    `;


                    $('#4hd-client-logos')
                        .append(html);

                }
            );

        });

    </script>

    <?php
}


/**
 * Client Logos - WordPress Media Library
 */
add_action('admin_enqueue_scripts', function ($hook) {

    if ($hook !== 'appearance_page_4hd-client-logos') {
        return;
    }

    wp_enqueue_media();

});

/**
 * Homepage Video - Admin Page
 */
add_action('admin_menu', function () {

    add_theme_page(
        'Homepage Video',
        'Homepage Video',
        'manage_options',
        '4hd-homepage-video',
        'four_hd_homepage_video_page'
    );

});


/**
 * Homepage Video - Settings
 */
add_action('admin_init', function () {

    register_setting(
        '4hd_homepage_video_settings',
        '4hd_homepage_video'
    );

});


/**
 * Homepage Video - Admin Page
 */
function four_hd_homepage_video_page() {

    $video = get_option('4hd_homepage_video', [
        'enabled'     => 1,
        'image'       => '',
        'url'         => '',
        'title'       => '',
    ]);

    ?>

    <div class="wrap">

        <h1>4HD PRINT — Homepage Video</h1>

        <form method="post" action="options.php">

            <?php
            settings_fields('4hd_homepage_video_settings');
            ?>

            <table class="form-table">

                <!-- Enable -->

                <tr>

                    <th scope="row">
                        Enable Video
                    </th>

                    <td>

                        <label>

                            <input
                                type="checkbox"
                                name="4hd_homepage_video[enabled]"
                                value="1"
                                <?php checked($video['enabled'], 1); ?>
                            >

                            Show latest video on homepage

                        </label>

                    </td>

                </tr>


                <!-- Video URL -->

                <tr>

                    <th scope="row">
                        YouTube URL
                    </th>

                    <td>

                        <input
                            type="url"
                            name="4hd_homepage_video[url]"
                            value="<?php echo esc_attr($video['url']); ?>"
                            class="large-text"
                            placeholder="https://www.youtube.com/watch?v=..."
                        >

                        <p class="description">
                            Paste the URL of your YouTube video.
                        </p>

                    </td>

                </tr>


                <!-- Title -->

                <tr>

                    <th scope="row">
                        Video Title
                    </th>

                    <td>

                        <input
                            type="text"
                            name="4hd_homepage_video[title]"
                            value="<?php echo esc_attr($video['title']); ?>"
                            class="regular-text"
                            placeholder="Watch our latest video"
                        >

                    </td>

                </tr>


                <!-- Thumbnail -->

                <tr>

                    <th scope="row">
                        Thumbnail
                    </th>

                    <td>

                        <?php
                        $image_id = ! empty($video['image'])
                            ? absint($video['image'])
                            : 0;
                        ?>


                        <input
                            type="hidden"
                            id="4hd_homepage_video_image"
                            name="4hd_homepage_video[image]"
                            value="<?php echo esc_attr($image_id); ?>"
                        >


                        <div
                            id="4hd_homepage_video_preview"
                            style="margin-bottom:15px;"
                        >

                            <?php

                            if ($image_id) {

                                echo wp_get_attachment_image(
                                    $image_id,
                                    'medium'
                                );

                            }

                            ?>

                        </div>


                        <button
                            type="button"
                            class="button"
                            id="4hd_homepage_video_select_image"
                        >
                            Select Thumbnail
                        </button>


                        <button
                            type="button"
                            class="button"
                            id="4hd_homepage_video_remove_image"
                            <?php echo $image_id ? '' : 'style="display:none;"'; ?>
                        >
                            Remove Thumbnail
                        </button>

                    </td>

                </tr>

            </table>


            <?php submit_button('Save Homepage Video'); ?>

        </form>

    </div>


    <script>

        jQuery(document).ready(function ($) {

            let mediaFrame;


            $('#4hd_homepage_video_select_image').on(
                'click',
                function (event) {

                    event.preventDefault();


                    mediaFrame = wp.media({

                        title: 'Select Video Thumbnail',

                        button: {
                            text: 'Use This Image'
                        },

                        multiple: false

                    });


                    mediaFrame.on(
                        'select',
                        function () {

                            const attachment =
                                mediaFrame.state()
                                    .get('selection')
                                    .first()
                                    .toJSON();


                            $('#4hd_homepage_video_image')
                                .val(attachment.id);


                            $('#4hd_homepage_video_preview')
                                .html(
                                    '<img src="' +
                                    attachment.url +
                                    '" style="max-width:400px;height:auto;">'
                                );


                            $('#4hd_homepage_video_remove_image')
                                .show();

                        }
                    );


                    mediaFrame.open();

                }
            );


            $('#4hd_homepage_video_remove_image').on(
                'click',
                function (event) {

                    event.preventDefault();


                    $('#4hd_homepage_video_image')
                        .val('');


                    $('#4hd_homepage_video_preview')
                        .html('');


                    $(this).hide();

                }
            );

        });

    </script>

    <?php
}


/**
 * Homepage Video - Media Library
 */
add_action('admin_enqueue_scripts', function ($hook) {

    if ($hook !== 'appearance_page_4hd-homepage-video') {
        return;
    }

    wp_enqueue_media();

});

/* =========================================
   PRODUCT QUANTITY LABEL
========================================= */

add_action('woocommerce_before_add_to_cart_quantity', function () {
    echo '<span class="fourhd-quantity-label">Quantity</span>';
});

/* =========================================
   REMOVE PRODUCT META
   SKU + CATEGORY + TAGS
========================================= */

remove_action(
    'woocommerce_single_product_summary',
    'woocommerce_template_single_meta',
    40
);

/* =========================================
   ORDER RECEIVED — CUSTOM ACTIONS
========================================= */

add_action(
    'woocommerce_thankyou',
    'fourhd_order_received_actions',
    20
);

function fourhd_order_received_actions($order_id) {

    if (!$order_id) {
        return;
    }

    $order = wc_get_order($order_id);

    if (!$order) {
        return;
    }

    ?>

    <div class="fourhd-order-actions">

        <!-- PRINT ORDER -->

        <button
            type="button"
            class="fourhd-order-action fourhd-order-action--print"
            onclick="window.print();"
        >
            Print Order
        </button>


        <!-- TRACK PACKAGE -->

        <?php

        $tracking_number = $order->get_meta(
            '_fourhd_tracking_number'
        );

        if (!empty($tracking_number)) :

            $tracking_url =
                'https://www.ups.com/track?tracknum=' .
                rawurlencode($tracking_number);

        ?>

            <a
                href="<?php echo esc_url($tracking_url); ?>"
                class="fourhd-order-action"
                target="_blank"
                rel="noopener"
            >
                Track Package
            </a>

        <?php endif; ?>


        <!-- CREATE ACCOUNT -->

        <?php if (!is_user_logged_in()) : ?>

            <a
                href="<?php echo esc_url(
                    wc_get_page_permalink('myaccount')
                ); ?>"
                class="fourhd-order-action"
            >
                Create Account
            </a>

        <?php endif; ?>


        <!-- CONTINUE SHOPPING -->

        <a
            href="<?php echo esc_url(
                wc_get_page_permalink('shop')
            ); ?>"
            class="fourhd-order-action"
        >
            Continue Shopping
        </a>

    </div>

    <?php
}