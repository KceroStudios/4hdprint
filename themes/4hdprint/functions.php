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

    // Main CSS
    wp_enqueue_style(
        'main-style',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        filemtime(get_template_directory() . '/assets/css/main.css')
    );

    // Website Estimate CSS
    if (is_page('website-estimate')) {

        wp_enqueue_style(
            'website-estimate-style',
            get_template_directory_uri() . '/assets/css/website-estimate.css',
            ['main-style'],
            filemtime(get_template_directory() . '/assets/css/website-estimate.css')
        );

        wp_enqueue_script(
        'website-estimate-script',
        get_template_directory_uri() . '/assets/js/website-estimate.js',
        [],
        filemtime(get_template_directory() . '/assets/js/website-estimate.js'),
        true
    );

    }

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

/* =========================================
   4HD DELIVERY METHOD
   PICKUP / SHIPPING
========================================= */

add_action('woocommerce_cart_totals_before_shipping', function () {

    if (!WC()->cart || !WC()->cart->needs_shipping()) {
        return;
    }

    ?>
    <tr class="fourhd-delivery-method">
        <th>Delivery Method</th>

        <td data-title="Delivery Method">

            <label style="display:block; margin-bottom:6px;">
                <input
                    type="radio"
                    name="fourhd_delivery_method"
                    value="pickup"
                    onchange="this.form.submit();"
                >
                Pickup
            </label>

            <label style="display:block;">
                <input
                type="radio"
                name="fourhd_delivery_method"
                value="shipping"
                onchange="this.form.submit();"
            >
                Shipping
            </label>

        </td>
    </tr>
    <?php
});

/* =========================================
   WAIT FOR DELIVERY METHOD SELECTION
========================================= */

add_filter('woocommerce_cart_ready_to_calc_shipping', function ($ready) {

    if (!is_cart()) {
        return $ready;
    }

    if (!WC()->session) {
        return $ready;
    }

    $method = WC()->session->get('fourhd_delivery_method', '');

    if ($method !== 'shipping') {
        return false;
    }

    return $ready;
});

/* =========================================
   SAVE DELIVERY METHOD
========================================= */

add_action('wp_loaded', function () {

    if (
        isset($_POST['fourhd_delivery_method']) &&
        WC()->session
    ) {
        $method = sanitize_key(
            wp_unslash($_POST['fourhd_delivery_method'])
        );

        if (in_array($method, ['pickup', 'shipping'], true)) {
            WC()->session->set('fourhd_delivery_method', $method);
        }
    }
});

/* =========================================
   WEBSITE ESTIMATE — FORM PROCESSING
========================================= */

add_action('template_redirect', function () {

    // Only process POST requests.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    // Make sure this is our estimate form.
    if (
        !isset($_POST['fourhd_estimate_action']) ||
        $_POST['fourhd_estimate_action'] !== 'submit_estimate'
    ) {
        return;
    }

    // Verify WordPress nonce.
    if (
        !isset($_POST['fourhd_estimate_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['fourhd_estimate_nonce'])
            ),
            'fourhd_website_estimate'
        )
    ) {
        wp_die('Security verification failed.');
    }


    /* =====================================
       CUSTOMER INFORMATION
    ===================================== */

    $name = isset($_POST['name'])
        ? sanitize_text_field(wp_unslash($_POST['name']))
        : '';

    $business = isset($_POST['business'])
        ? sanitize_text_field(wp_unslash($_POST['business']))
        : '';

    $email = isset($_POST['email'])
        ? sanitize_email(wp_unslash($_POST['email']))
        : '';

    $phone = isset($_POST['phone'])
        ? sanitize_text_field(wp_unslash($_POST['phone']))
        : '';

    $current_website = isset($_POST['current_website'])
        ? esc_url_raw(wp_unslash($_POST['current_website']))
        : '';

    $description = isset($_POST['description'])
        ? sanitize_textarea_field(
            wp_unslash($_POST['description'])
        )
        : '';


    /* =====================================
       BASIC SERVER VALIDATION
    ===================================== */

    if (
        empty($name) ||
        empty($email) ||
        !is_email($email) ||
        empty($description)
    ) {
        wp_die(
            'Please complete all required fields with valid information.'
        );
    }


    /* =====================================
       ESTIMATOR SELECTIONS
    ===================================== */

    $website_type = isset($_POST['website_type'])
        ? sanitize_key($_POST['website_type'])
        : '';

    $pages = isset($_POST['pages'])
        ? sanitize_key($_POST['pages'])
        : '';

    $products = isset($_POST['products'])
        ? sanitize_key($_POST['products'])
        : '';

    $domain_hosting = isset($_POST['domain_hosting'])
        ? sanitize_key($_POST['domain_hosting'])
        : '';

    $maintenance = isset($_POST['maintenance'])
        ? sanitize_key($_POST['maintenance'])
        : '';


    /* =====================================
       FEATURES
    ===================================== */

    $features = [];

    if (
        isset($_POST['features']) &&
        is_array($_POST['features'])
    ) {

        $features = array_map(
            'sanitize_key',
            wp_unslash($_POST['features'])
        );

    }

/* =====================================
   SERVER-SIDE PRICE CALCULATION
===================================== */

$project_price = 0;
$custom_quote  = false;


/* -------------------------------------
   STARTER
------------------------------------- */

if ($website_type === 'starter') {

    $project_price = 450;

    // Pages
    switch ($pages) {

        case '1':
            break;

        case '3':
            $project_price += 200;
            break;

        case '5':
            $project_price += 400;
            break;

        case '10':
            $project_price += 750;
            break;

        case '10plus':
            $custom_quote = true;
            break;
    }


    // Features
    $starter_feature_prices = [
        'gallery'         => 100,
        'maps'            => 50,
        'blog'            => 150,
        'newsletter'      => 100,
        'quote_form'      => 150,
        'employment_form' => 150,
        'booking'         => 250,
        'calculator'      => 300,
        'multilingual'    => 250,
    ];

    foreach ($starter_feature_prices as $feature => $price) {

        if (in_array($feature, $features, true)) {
            $project_price += $price;
        }

    }


    // File Upload +$75 only when a form is selected.
    $has_standard_form =
        in_array('quote_form', $features, true) ||
        in_array('employment_form', $features, true);

    if (
        in_array('upload', $features, true) &&
        $has_standard_form
    ) {
        $project_price += 75;
    }

}


/* -------------------------------------
   BUSINESS
------------------------------------- */

elseif ($website_type === 'business') {

    $project_price = 1500;


    // Pages
    switch ($pages) {

        case '1':
        case '3':
        case '5':
            break;

        case '10':
            $project_price += 400;
            break;

        case '10plus':
            $custom_quote = true;
            break;
    }


    // Features
    $business_feature_prices = [
        'booking'      => 250,
        'blog'         => 150,
        'newsletter'   => 100,
        'calculator'   => 300,
        'multilingual' => 250,
    ];

    foreach ($business_feature_prices as $feature => $price) {

        if (in_array($feature, $features, true)) {
            $project_price += $price;
        }

    }


    /*
     * One standard custom form is included.
     * Second form costs +$150.
     */

    $standard_form_count = 0;

    if (in_array('quote_form', $features, true)) {
        $standard_form_count++;
    }

    if (in_array('employment_form', $features, true)) {
        $standard_form_count++;
    }

    if ($standard_form_count > 1) {
        $project_price += 150;
    }

}


/* -------------------------------------
   ONLINE STORE
------------------------------------- */

elseif ($website_type === 'store') {

    $project_price = 2500;


    // Pages
    switch ($pages) {

        case '1':
        case '3':
        case '5':
            break;

        case '10':
            $project_price += 400;
            break;

        case '10plus':
            $custom_quote = true;
            break;
    }


    // Products
    switch ($products) {

        case '10':
            break;

        case '25':
            $project_price += 250;
            break;

        case '50':
            $project_price += 500;
            break;

        case '100':
            $project_price += 900;
            break;

        case 'custom':
            $custom_quote = true;
            break;
    }


    // Features
    $store_feature_prices = [
        'gallery'         => 100,
        'blog'            => 150,
        'newsletter'      => 100,
        'quote_form'      => 150,
        'employment_form' => 150,
        'booking'         => 250,
        'calculator'      => 300,
        'multilingual'    => 250,
    ];

    foreach ($store_feature_prices as $feature => $price) {

        if (in_array($feature, $features, true)) {
            $project_price += $price;
        }

    }


    // File Upload +$75 when a standard form is selected.
    $has_standard_form =
        in_array('quote_form', $features, true) ||
        in_array('employment_form', $features, true);

    if (
        in_array('upload', $features, true) &&
        $has_standard_form
    ) {
        $project_price += 75;
    }

}


/* -------------------------------------
   INVALID PACKAGE
------------------------------------- */

else {

    wp_die('Invalid website package.');

}
   /* =====================================
   HUMAN-READABLE VALUES
===================================== */

$package_labels = [
    'starter'  => 'Starter Website',
    'business' => 'Business Website',
    'store'    => 'Online Store',
];

$page_labels = [
    '1'      => '1 Page',
    '3'      => '2–3 Pages',
    '5'      => '4–5 Pages',
    '10'     => '6–10 Pages',
    '10plus' => '10+ Pages',
];

$product_labels = [
    '10'     => 'Up to 10 Products',
    '25'     => '11–25 Products',
    '50'     => '26–50 Products',
    '100'    => '51–100 Products',
    'custom' => '100+ Products',
];

$feature_labels = [
    'contact'         => 'Contact Form',
    'gallery'         => 'Gallery / Portfolio',
    'maps'            => 'Google Maps',
    'social'          => 'Social Media Integration',
    'blog'            => 'Blog',
    'newsletter'      => 'Newsletter',
    'multilingual'    => 'Multilingual Website',
    'quote_form'      => 'Quote Request Form',
    'employment_form' => 'Employment / Application Form',
    'booking'         => 'Appointment Booking',
    'calculator'      => 'Custom Calculator',
    'upload'          => 'File Upload',
];

$maintenance_labels = [
    'self'      => 'Self Managed — $0 / month',
    'care'      => 'Website Care — $49 / month',
    'care_plus' => 'Website Care Plus — $99 / month',
    'ecommerce' => 'E-Commerce Care — $149 / month',
];

$hosting_labels = [
    'existing' => 'Existing Domain & Hosting — $0 / year',
    'managed'  => 'Domain + Secure Hosting — Starting at $199 / year',
    'unsure'   => 'To Be Determined',
];


$package_label = $package_labels[$website_type] ?? $website_type;
$page_label    = $page_labels[$pages] ?? $pages;

$maintenance_label =
    $maintenance_labels[$maintenance] ?? 'Not selected';

$hosting_label =
    $hosting_labels[$domain_hosting] ?? 'Not selected';

$product_label = '';

if ($website_type === 'store') {
    $product_label =
        $product_labels[$products] ?? $products;
}

$estimate_label = $custom_quote
    ? 'Custom Quote'
    : '$' . number_format($project_price);

    /* =====================================
   SAVE WEBSITE QUOTE
===================================== */

$quote_id = wp_insert_post([
    'post_type'   => 'fourhd_web_quote',
    'post_status' => 'publish',

    'post_title' => sprintf(
        '%s — %s',
        $name,
        $package_label
    ),
]);

if (!is_wp_error($quote_id) && $quote_id) {

    update_post_meta(
        $quote_id,
        '_fourhd_customer_name',
        $name
    );

    update_post_meta(
        $quote_id,
        '_fourhd_business',
        $business
    );

    update_post_meta(
        $quote_id,
        '_fourhd_email',
        $email
    );

    update_post_meta(
        $quote_id,
        '_fourhd_phone',
        $phone
    );

    update_post_meta(
        $quote_id,
        '_fourhd_current_website',
        $current_website
    );

    update_post_meta(
        $quote_id,
        '_fourhd_website_type',
        $website_type
    );

    update_post_meta(
        $quote_id,
        '_fourhd_pages',
        $pages
    );

    update_post_meta(
        $quote_id,
        '_fourhd_products',
        $products
    );

    update_post_meta(
        $quote_id,
        '_fourhd_features',
        $features
    );

    update_post_meta(
        $quote_id,
        '_fourhd_domain_hosting',
        $domain_hosting
    );

    update_post_meta(
        $quote_id,
        '_fourhd_maintenance',
        $maintenance
    );

    update_post_meta(
        $quote_id,
        '_fourhd_description',
        $description
    );

    update_post_meta(
        $quote_id,
        '_fourhd_project_price',
        $project_price
    );

    update_post_meta(
        $quote_id,
        '_fourhd_custom_quote',
        $custom_quote ? 'yes' : 'no'
    );

    update_post_meta(
        $quote_id,
        '_fourhd_status',
        'new'
    );
}


   /* =====================================
   BUILD HTML EMAIL
===================================== */

$to = get_option('admin_email');

$subject =
    'New Website Quote Request - ' .
    $package_label;


/* -------------------------------------
   FEATURES LIST
------------------------------------- */

$features_html = '';

if (!empty($features)) {

    foreach ($features as $feature) {

        if (!isset($feature_labels[$feature])) {
            continue;
        }

        $status = '';

        // Included features by package.
        if ($website_type === 'starter') {

            if (in_array($feature, ['contact', 'social'], true)) {
                $status = 'Included';
            }

        } elseif ($website_type === 'business') {

            if (
                in_array(
                    $feature,
                    ['contact', 'gallery', 'maps', 'social'],
                    true
                )
            ) {
                $status = 'Included';
            }

        } elseif ($website_type === 'store') {

            if (
                in_array(
                    $feature,
                    ['contact', 'maps', 'social'],
                    true
                )
            ) {
                $status = 'Included';
            }
        }

        $features_html .= '
            <tr>
                <td style="
                    padding:8px 0;
                    border-bottom:1px solid #eeeeee;
                ">
                    ' . esc_html($feature_labels[$feature]) . '
                </td>

                <td style="
                    padding:8px 0;
                    border-bottom:1px solid #eeeeee;
                    text-align:right;
                    color:#f47721;
                    font-weight:600;
                ">
                    ' . esc_html($status) . '
                </td>
            </tr>
        ';
    }

} else {

    $features_html = '
        <tr>
            <td style="padding:8px 0;">
                No additional features selected.
            </td>
        </tr>
    ';
}


/* -------------------------------------
   PRODUCTS ROW
------------------------------------- */

$products_html = '';

if (
    $website_type === 'store' &&
    !empty($product_label)
) {

    $products_html = '
        <tr>
            <td style="padding:6px 0;color:#707070;">
                Products
            </td>

            <td style="
                padding:6px 0;
                text-align:right;
                font-weight:600;
            ">
                ' . esc_html($product_label) . '
            </td>
        </tr>
    ';
}


/* -------------------------------------
   OPTIONAL CUSTOMER INFORMATION
------------------------------------- */

$business_html = '';

if (!empty($business)) {
    $business_html = '
        <tr>
            <td style="padding:6px 0;color:#707070;">
                Business
            </td>
            <td style="padding:6px 0;text-align:right;">
                ' . esc_html($business) . '
            </td>
        </tr>
    ';
}

$phone_html = '';

if (!empty($phone)) {
    $phone_html = '
        <tr>
            <td style="padding:6px 0;color:#707070;">
                Phone
            </td>
            <td style="padding:6px 0;text-align:right;">
                ' . esc_html($phone) . '
            </td>
        </tr>
    ';
}

$website_html = '';

if (!empty($current_website)) {
    $website_html = '
        <tr>
            <td style="padding:6px 0;color:#707070;">
                Current Website
            </td>
            <td style="padding:6px 0;text-align:right;">
                ' . esc_html($current_website) . '
            </td>
        </tr>
    ';
}


/* -------------------------------------
   EMAIL CONTENT
------------------------------------- */

$message = '
<!DOCTYPE html>
<html>
<body style="
    margin:0;
    padding:0;
    background:#f2f2f2;
    font-family:Arial,Helvetica,sans-serif;
    color:#151515;
">

<div style="
    max-width:680px;
    margin:0 auto;
    padding:35px 20px;
">

    <div style="
        background:#252525;
        padding:28px 30px;
        border-radius:12px 12px 0 0;
    ">

        <div style="
            color:#f47721;
            font-size:13px;
            font-weight:700;
            letter-spacing:1px;
        ">
            4HD PRINT
        </div>

        <h1 style="
            margin:8px 0 0;
            color:#ffffff;
            font-size:24px;
            font-weight:600;
        ">
            New Website Quote Request
        </h1>

    </div>


    <div style="
        background:#ffffff;
        padding:30px;
    ">

        <h2 style="
            margin:0 0 15px;
            font-size:16px;
        ">
            Customer
        </h2>

        <table style="
            width:100%;
            border-collapse:collapse;
            font-size:14px;
        ">

            <tr>
                <td style="padding:6px 0;color:#707070;">
                    Name
                </td>
                <td style="padding:6px 0;text-align:right;">
                    ' . esc_html($name) . '
                </td>
            </tr>

            <tr>
                <td style="padding:6px 0;color:#707070;">
                    Email
                </td>
                <td style="padding:6px 0;text-align:right;">
                    ' . esc_html($email) . '
                </td>
            </tr>

            ' . $business_html . '
            ' . $phone_html . '
            ' . $website_html . '

        </table>


        <h2 style="
            margin:30px 0 15px;
            font-size:16px;
        ">
            Project
        </h2>

        <table style="
            width:100%;
            border-collapse:collapse;
            font-size:14px;
        ">

            <tr>
                <td style="padding:6px 0;color:#707070;">
                    Package
                </td>
                <td style="
                    padding:6px 0;
                    text-align:right;
                    font-weight:600;
                ">
                    ' . esc_html($package_label) . '
                </td>
            </tr>

            <tr>
                <td style="padding:6px 0;color:#707070;">
                    Pages
                </td>
                <td style="
                    padding:6px 0;
                    text-align:right;
                    font-weight:600;
                ">
                    ' . esc_html($page_label) . '
                </td>
            </tr>

            ' . $products_html . '

        </table>


        <h2 style="
            margin:30px 0 15px;
            font-size:16px;
        ">
            Features
        </h2>

        <table style="
            width:100%;
            border-collapse:collapse;
            font-size:14px;
        ">
            ' . $features_html . '
        </table>


        <h2 style="
            margin:30px 0 15px;
            font-size:16px;
        ">
            Services
        </h2>

        <table style="
            width:100%;
            border-collapse:collapse;
            font-size:14px;
        ">

            <tr>
                <td style="padding:6px 0;color:#707070;">
                    Website Care
                </td>

                <td style="
                    padding:6px 0;
                    text-align:right;
                ">
                    ' . esc_html($maintenance_label) . '
                </td>
            </tr>

            <tr>
                <td style="padding:6px 0;color:#707070;">
                    Domain + Hosting
                </td>

                <td style="
                    padding:6px 0;
                    text-align:right;
                ">
                    ' . esc_html($hosting_label) . '
                </td>
            </tr>

        </table>


        <div style="
            margin:30px 0;
            padding:22px;
            background:#252525;
            border-radius:8px;
            text-align:center;
        ">

            <div style="
                margin-bottom:5px;
                color:#c9c9c9;
                font-size:12px;
                text-transform:uppercase;
                letter-spacing:1px;
            ">
                Preliminary Project Estimate
            </div>

            <div style="
                color:#f47721;
                font-size:28px;
                font-weight:700;
            ">
                ' . esc_html($estimate_label) . '
            </div>

        </div>


        <h2 style="
            margin:30px 0 10px;
            font-size:16px;
        ">
            Project Description
        </h2>

        <div style="
            padding:15px;
            background:#f2f2f2;
            border-radius:7px;
            font-size:14px;
            line-height:1.6;
        ">
            ' . nl2br(esc_html($description)) . '
        </div>

    </div>


    <div style="
        padding:18px 30px;
        background:#151515;
        border-radius:0 0 12px 12px;
        color:#707070;
        font-size:11px;
        text-align:center;
    ">
        Website Estimate generated by 4HD PRINT
    </div>

</div>

</body>
</html>
';


/* =====================================
   EMAIL HEADERS
===================================== */

$headers = [
    'Content-Type: text/html; charset=UTF-8',
    'Reply-To: ' . $name . ' <' . $email . '>',
];


/* =====================================
   SEND EMAIL
===================================== */

$email_sent = wp_mail(
    $to,
    $subject,
    $message,
    $headers
);

/* =====================================
   REDIRECT
===================================== */

$redirect_url = add_query_arg(
    'estimate_status',
    $email_sent ? 'success' : 'error',
    get_permalink()
);

wp_safe_redirect($redirect_url);
exit;
});

/* =========================================
   WEBSITE QUOTES — ADMIN
========================================= */

add_action('init', function () {

    $labels = [
        'name'               => 'Website Quotes',
        'singular_name'      => 'Website Quote',
        'menu_name'          => 'Website Quotes',
        'add_new'            => 'Add Quote',
        'add_new_item'       => 'Add Website Quote',
        'edit_item'          => 'View Website Quote',
        'new_item'           => 'New Website Quote',
        'view_item'          => 'View Website Quote',
        'search_items'       => 'Search Website Quotes',
        'not_found'          => 'No website quotes found',
        'not_found_in_trash' => 'No website quotes found in Trash',
    ];

    register_post_type('fourhd_web_quote', [

        'labels' => $labels,

        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,

        'menu_icon' => 'dashicons-media-document',

        'supports' => [
            'title',
        ],

        'capability_type' => 'post',
        'map_meta_cap' => true,

        'has_archive' => false,
        'rewrite' => false,
        'query_var' => false,

    ]);

    /* =========================================
   WEBSITE QUOTES — DETAILS
========================================= */

add_action('add_meta_boxes', function () {

    add_meta_box(
        'fourhd_web_quote_details',
        'Quote Details',
        'fourhd_render_web_quote_details',
        'fourhd_web_quote',
        'normal',
        'high'
    );

});


function fourhd_render_web_quote_details($post) {

    $name = get_post_meta(
        $post->ID,
        '_fourhd_customer_name',
        true
    );

    $business = get_post_meta(
        $post->ID,
        '_fourhd_business',
        true
    );

    $email = get_post_meta(
        $post->ID,
        '_fourhd_email',
        true
    );

    $phone = get_post_meta(
        $post->ID,
        '_fourhd_phone',
        true
    );

    $current_website = get_post_meta(
        $post->ID,
        '_fourhd_current_website',
        true
    );

    $website_type = get_post_meta(
        $post->ID,
        '_fourhd_website_type',
        true
    );

    $pages = get_post_meta(
        $post->ID,
        '_fourhd_pages',
        true
    );

    $products = get_post_meta(
        $post->ID,
        '_fourhd_products',
        true
    );

    $features = get_post_meta(
        $post->ID,
        '_fourhd_features',
        true
    );

    $domain_hosting = get_post_meta(
        $post->ID,
        '_fourhd_domain_hosting',
        true
    );

    $maintenance = get_post_meta(
        $post->ID,
        '_fourhd_maintenance',
        true
    );

    $description = get_post_meta(
        $post->ID,
        '_fourhd_description',
        true
    );

    $project_price = get_post_meta(
        $post->ID,
        '_fourhd_project_price',
        true
    );

    $custom_quote = get_post_meta(
        $post->ID,
        '_fourhd_custom_quote',
        true
    );


    /* -------------------------------------
       LABELS
    ------------------------------------- */

    $package_labels = [
        'starter'  => 'Starter Website',
        'business' => 'Business Website',
        'store'    => 'Online Store',
    ];

    $page_labels = [
        '1'      => '1 Page',
        '3'      => '2–3 Pages',
        '5'      => '4–5 Pages',
        '10'     => '6–10 Pages',
        '10plus' => '10+ Pages',
    ];

    $product_labels = [
        '10'     => 'Up to 10 Products',
        '25'     => '11–25 Products',
        '50'     => '26–50 Products',
        '100'    => '51–100 Products',
        'custom' => '100+ Products',
    ];

    $feature_labels = [
        'contact'         => 'Contact Form',
        'gallery'         => 'Gallery / Portfolio',
        'maps'            => 'Google Maps',
        'social'          => 'Social Media Integration',
        'blog'            => 'Blog',
        'newsletter'      => 'Newsletter',
        'multilingual'    => 'Multilingual Website',
        'quote_form'      => 'Quote Request Form',
        'employment_form' => 'Employment / Application Form',
        'booking'         => 'Appointment Booking',
        'calculator'      => 'Custom Calculator',
        'upload'          => 'File Upload',
    ];

    $maintenance_labels = [
        'self'      => 'Self Managed — $0 / month',
        'care'      => 'Website Care — $49 / month',
        'care_plus' => 'Website Care Plus — $99 / month',
        'ecommerce' => 'E-Commerce Care — $149 / month',
    ];

    $hosting_labels = [
        'existing' => 'Existing Domain & Hosting — $0 / year',
        'managed'  => 'Domain + Secure Hosting — Starting at $199 / year',
        'unsure'   => 'To Be Determined',
    ];


    /* -------------------------------------
       DISPLAY
    ------------------------------------- */

    ?>

    <div class="fourhd-quote-details">

        <h3>Customer</h3>

        <table class="widefat striped">

            <tbody>

                <tr>
                    <td><strong>Name</strong></td>
                    <td><?php echo esc_html($name); ?></td>
                </tr>

                <?php if ($business) : ?>
                    <tr>
                        <td><strong>Business</strong></td>
                        <td><?php echo esc_html($business); ?></td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td><strong>Email</strong></td>
                    <td>
                        <a href="mailto:<?php echo esc_attr($email); ?>">
                            <?php echo esc_html($email); ?>
                        </a>
                    </td>
                </tr>

                <?php if ($phone) : ?>
                    <tr>
                        <td><strong>Phone</strong></td>
                        <td><?php echo esc_html($phone); ?></td>
                    </tr>
                <?php endif; ?>

                <?php if ($current_website) : ?>
                    <tr>
                        <td><strong>Current Website</strong></td>
                        <td>
                            <a
                                href="<?php echo esc_url($current_website); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <?php echo esc_html($current_website); ?>
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>

        </table>


        <h3>Project</h3>

        <table class="widefat striped">

            <tbody>

                <tr>
                    <td><strong>Package</strong></td>
                    <td>
                        <?php
                        echo esc_html(
                            $package_labels[$website_type]
                            ?? $website_type
                        );
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><strong>Pages</strong></td>
                    <td>
                        <?php
                        echo esc_html(
                            $page_labels[$pages]
                            ?? $pages
                        );
                        ?>
                    </td>
                </tr>

                <?php if ($website_type === 'store') : ?>

                    <tr>
                        <td><strong>Products</strong></td>
                        <td>
                            <?php
                            echo esc_html(
                                $product_labels[$products]
                                ?? $products
                            );
                            ?>
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>


        <h3>Features</h3>

        <ul>

            <?php if (!empty($features) && is_array($features)) : ?>

                <?php foreach ($features as $feature) : ?>

                    <?php
                    if (!isset($feature_labels[$feature])) {
                        continue;
                    }
                    ?>

                    <li>
                        ✓
                        <?php
                        echo esc_html(
                            $feature_labels[$feature]
                        );
                        ?>
                    </li>

                <?php endforeach; ?>

            <?php else : ?>

                <li>No additional features selected.</li>

            <?php endif; ?>

        </ul>


        <h3>Services</h3>

        <table class="widefat striped">

            <tbody>

                <tr>
                    <td><strong>Website Care</strong></td>
                    <td>
                        <?php
                        echo esc_html(
                            $maintenance_labels[$maintenance]
                            ?? 'Not selected'
                        );
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><strong>Domain + Hosting</strong></td>
                    <td>
                        <?php
                        echo esc_html(
                            $hosting_labels[$domain_hosting]
                            ?? 'Not selected'
                        );
                        ?>
                    </td>
                </tr>

            </tbody>

        </table>


        <h3>Project Estimate</h3>

        <p style="
            font-size:24px;
            font-weight:600;
            color:#f47721;
        ">

            <?php if ($custom_quote === 'yes') : ?>

                Custom Quote

            <?php else : ?>

                $<?php echo esc_html(
                    number_format((float) $project_price)
                ); ?>

            <?php endif; ?>

        </p>


        <h3>Project Description</h3>

        <div style="
            padding:15px;
            background:#f6f7f7;
            border-radius:4px;
            line-height:1.6;
        ">

            <?php echo nl2br(
                esc_html($description)
            ); ?>

        </div>

    </div>

    <?php
}

});

/* =========================================
   WEBSITE QUOTES — STATUS
========================================= */

add_action('add_meta_boxes', function () {

    add_meta_box(
        'fourhd_web_quote_status',
        'Quote Status',
        'fourhd_render_web_quote_status',
        'fourhd_web_quote',
        'side',
        'high'
    );

});


function fourhd_render_web_quote_status($post) {

    $status = get_post_meta(
        $post->ID,
        '_fourhd_status',
        true
    );

    if (empty($status)) {
        $status = 'new';
    }

    wp_nonce_field(
        'fourhd_save_quote_status',
        'fourhd_quote_status_nonce'
    );

    $statuses = [
        'new'       => 'New',
        'contacted' => 'Contacted',
        'quoted'    => 'Quoted',
        'accepted'  => 'Accepted',
        'closed'    => 'Closed',
    ];

    ?>

    <p>
        <label for="fourhd_quote_status">
            <strong>Current Status</strong>
        </label>
    </p>

    <select
        name="fourhd_quote_status"
        id="fourhd_quote_status"
        style="width:100%;"
    >

        <?php foreach ($statuses as $value => $label) : ?>

            <option
                value="<?php echo esc_attr($value); ?>"
                <?php selected($status, $value); ?>
            >
                <?php echo esc_html($label); ?>
            </option>

        <?php endforeach; ?>

    </select>

    <?php
}


/* =========================================
   WEBSITE QUOTES — SAVE STATUS
========================================= */

add_action('save_post_fourhd_web_quote', function ($post_id) {

    // Ignore WordPress autosaves.
    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    // Verify our nonce.
    if (
        !isset($_POST['fourhd_quote_status_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(
                wp_unslash(
                    $_POST['fourhd_quote_status_nonce']
                )
            ),
            'fourhd_save_quote_status'
        )
    ) {
        return;
    }

    // Make sure the user can edit this quote.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['fourhd_quote_status'])) {
        return;
    }

    $status = sanitize_key(
        wp_unslash($_POST['fourhd_quote_status'])
    );

    $allowed_statuses = [
        'new',
        'contacted',
        'quoted',
        'accepted',
        'closed',
    ];

    if (!in_array($status, $allowed_statuses, true)) {
        return;
    }

    update_post_meta(
        $post_id,
        '_fourhd_status',
        $status
    );

});

/* =========================================
   WEBSITE QUOTES — ADMIN COLUMNS
========================================= */

add_filter(
    'manage_fourhd_web_quote_posts_columns',
    function ($columns) {

        return [
            'cb'             => $columns['cb'],
            'title'          => 'Customer',
            'quote_package'  => 'Package',
            'quote_estimate' => 'Estimate',
            'quote_status'   => 'Status',
            'date'           => 'Date',
        ];
    }
);


add_action(
    'manage_fourhd_web_quote_posts_custom_column',
    function ($column, $post_id) {

        /* ---------------------------------
           PACKAGE
        --------------------------------- */

        if ($column === 'quote_package') {

            $website_type = get_post_meta(
                $post_id,
                '_fourhd_website_type',
                true
            );

            $packages = [
                'starter'  => 'Starter Website',
                'business' => 'Business Website',
                'store'    => 'Online Store',
            ];

            echo esc_html(
                $packages[$website_type]
                ?? '—'
            );
        }


        /* ---------------------------------
           ESTIMATE
        --------------------------------- */

        if ($column === 'quote_estimate') {

            $project_price = get_post_meta(
                $post_id,
                '_fourhd_project_price',
                true
            );

            $custom_quote = get_post_meta(
                $post_id,
                '_fourhd_custom_quote',
                true
            );

            if ($custom_quote === 'yes') {

                echo '<strong>Custom Quote</strong>';

            } elseif ($project_price !== '') {

                echo '<strong>$' .
                    esc_html(
                        number_format(
                            (float) $project_price
                        )
                    ) .
                    '</strong>';

            } else {

                echo '—';
            }
        }


        /* ---------------------------------
           STATUS
        --------------------------------- */

        if ($column === 'quote_status') {

            $status = get_post_meta(
                $post_id,
                '_fourhd_status',
                true
            );

            $statuses = [
                'new'       => 'New',
                'contacted' => 'Contacted',
                'quoted'    => 'Quoted',
                'accepted'  => 'Accepted',
                'closed'    => 'Closed',
            ];

            if (empty($status)) {
                $status = 'new';
            }

            echo esc_html(
                $statuses[$status]
                ?? 'New'
            );
        }

    },
    10,
    2
);