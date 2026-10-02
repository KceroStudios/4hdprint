<?php
/*
Plugin Name: 4HD Project Questionnaire
Description: Project questionnaire for 4HD PRINT design services.
Version: 1.0
Author: 4HD PRINT
*/

defined('ABSPATH') || exit;


/* =========================================================
   1. PRODUCT SETTINGS
========================================================= */

/**
 * Add questionnaire option to WooCommerce product editor.
 */
add_action(
    'woocommerce_product_options_general_product_data',
    'fourhd_questionnaire_product_settings'
);

function fourhd_questionnaire_product_settings() {

    echo '<div class="options_group">';

    woocommerce_wp_checkbox([
        'id'          => '_fourhd_enable_questionnaire',
        'label'       => 'Project Questionnaire',
        'description' => 'Require the customer to complete a project questionnaire before adding this product to the cart.',
    ]);

    echo '</div>';
}


/**
 * Save questionnaire product setting.
 */
add_action(
    'woocommerce_process_product_meta',
    'fourhd_save_questionnaire_product_settings'
);

function fourhd_save_questionnaire_product_settings($product_id) {

    $enabled =
        isset($_POST['_fourhd_enable_questionnaire'])
            ? 'yes'
            : 'no';

    update_post_meta(
        $product_id,
        '_fourhd_enable_questionnaire',
        $enabled
    );
}


/* =========================================================
   2. FRONTEND QUESTIONNAIRE
========================================================= */

add_action(
    'woocommerce_before_add_to_cart_button',
    'fourhd_project_questionnaire'
);

function fourhd_project_questionnaire() {

    global $product;

    if (!$product) {
        return;
    }

    $product_id = $product->get_id();

    if ($product->is_type('variation')) {
        $product_id = $product->get_parent_id();
    }

    $enabled = get_post_meta(
        $product_id,
        '_fourhd_enable_questionnaire',
        true
    );

    if ($enabled !== 'yes') {
        return;
    }

    ?>

    <div
        id="fourhd-project-questionnaire"
        class="fourhd-project-questionnaire"
    >

        <h3>Tell Us About Your Project</h3>

        <p class="fourhd-questionnaire-intro">
            Help us understand your business and creative vision.
            The information you provide will be used as the creative
            brief for your project.
        </p>


        <!-- =============================================
             PROJECT INFORMATION
        ============================================== -->

        <div class="fourhd-questionnaire-section">

            <h4>Project Information</h4>


            <!-- BUSINESS NAME -->

            <div class="fourhd-field">

                <label for="fourhd_brand_name">
                    Business / Brand Name
                    <span class="fourhd-required">*</span>
                </label>

                <input
                    type="text"
                    id="fourhd_brand_name"
                    name="fourhd_brand_name"
                    required
                >

                <small>
                    Enter the exact name that should appear in your logo.
                </small>

            </div>


            <!-- TAGLINE -->

            <div class="fourhd-field">

                <label for="fourhd_tagline">
                    Tagline / Slogan
                </label>

                <input
                    type="text"
                    id="fourhd_tagline"
                    name="fourhd_tagline"
                >

                <small>
                    Optional. Leave blank if your brand does not use a tagline.
                </small>

            </div>


            <!-- BUSINESS DESCRIPTION -->

            <div class="fourhd-field">

                <label for="fourhd_business_description">
                    Tell Us About Your Business
                    <span class="fourhd-required">*</span>
                </label>

                <textarea
                    id="fourhd_business_description"
                    name="fourhd_business_description"
                    rows="5"
                    required
                ></textarea>

                <small>
                    Briefly describe your business, products or services,
                    and your target audience.
                </small>

            </div>


            <!-- BRAND STYLE -->

            <div class="fourhd-field">

                <label>
                    Brand Style
                    <span class="fourhd-required">*</span>
                </label>

                <small>
                    Select the styles that best represent your brand.
                </small>

                <div class="fourhd-checkbox-grid">

                    <?php

                    $styles = [
                        'modern'    => 'Modern',
                        'minimal'   => 'Minimal',
                        'elegant'   => 'Elegant',
                        'luxury'    => 'Luxury',
                        'bold'      => 'Bold',
                        'playful'   => 'Playful',
                        'corporate' => 'Corporate',
                        'vintage'   => 'Vintage',
                    ];

                    foreach ($styles as $value => $label) :
                    ?>

                        <label class="fourhd-checkbox-option">

                            <input
                                type="checkbox"
                                name="fourhd_brand_style[]"
                                value="<?php echo esc_attr($value); ?>"
                            >

                            <span>
                                <?php echo esc_html($label); ?>
                            </span>

                        </label>

                    <?php endforeach; ?>

                </div>

            </div>


            <!-- OTHER STYLE -->

            <div class="fourhd-field">

                <label for="fourhd_other_style">
                    Other Style
                </label>

                <input
                    type="text"
                    id="fourhd_other_style"
                    name="fourhd_other_style"
                >

            </div>


            <!-- PREFERRED COLORS -->

            <div class="fourhd-field">

                <label for="fourhd_preferred_colors">
                    Preferred Colors
                </label>

                <input
                    type="text"
                    id="fourhd_preferred_colors"
                    name="fourhd_preferred_colors"
                >

                <small>
                    Tell us which colors you would like us to explore.
                </small>

            </div>


            <!-- COLORS TO AVOID -->

            <div class="fourhd-field">

                <label for="fourhd_colors_avoid">
                    Colors to Avoid
                </label>

                <input
                    type="text"
                    id="fourhd_colors_avoid"
                    name="fourhd_colors_avoid"
                >

            </div>


            <!-- REFERENCES -->

            <div class="fourhd-field">

                <label for="fourhd_references">
                    References / Inspiration
                </label>

                <textarea
                    id="fourhd_references"
                    name="fourhd_references"
                    rows="4"
                ></textarea>

                <small>
                    Share links to brands, logos or visual styles you like.
                </small>

            </div>


            <!-- FILE UPLOAD -->

            <div class="fourhd-field">

                <label for="fourhd_reference_file">
                    Reference File
                </label>

                <input
                    type="file"
                    id="fourhd_reference_file"
                    name="fourhd_reference_file"
                    accept=".jpg,.jpeg,.png,.pdf"
                >

                <small>
                    Optional. JPG, PNG or PDF.
                </small>

            </div>


            <!-- ADDITIONAL INFORMATION -->

            <div class="fourhd-field">

                <label for="fourhd_additional_information">
                    Additional Information
                </label>

                <textarea
                    id="fourhd_additional_information"
                    name="fourhd_additional_information"
                    rows="4"
                ></textarea>

                <small>
                    Is there anything else we should know about your project?
                </small>

            </div>

        </div>

        <!-- =============================================
     ADVANCED — CORPORATE STATIONERY
============================================== -->

<div
    id="fourhd-advanced-fields"
    class="fourhd-questionnaire-section fourhd-package-fields"
    hidden
>

    <h4>Corporate Stationery Information</h4>

    <p>
        Please provide the information you would like us to use
        across your corporate stationery.
    </p>


    <div class="fourhd-field">

        <label for="fourhd_contact_name">
            Full Name
        </label>

        <input
            type="text"
            id="fourhd_contact_name"
            name="fourhd_contact_name"
        >

    </div>


    <div class="fourhd-field">

        <label for="fourhd_job_title">
            Job Title
        </label>

        <input
            type="text"
            id="fourhd_job_title"
            name="fourhd_job_title"
        >

    </div>


    <div class="fourhd-field">

        <label for="fourhd_business_address">
            Business Address
        </label>

        <textarea
            id="fourhd_business_address"
            name="fourhd_business_address"
            rows="3"
        ></textarea>

    </div>


    <div class="fourhd-field">

        <label for="fourhd_business_phone">
            Business Phone
        </label>

        <input
            type="text"
            id="fourhd_business_phone"
            name="fourhd_business_phone"
        >

    </div>


    <div class="fourhd-field">

        <label for="fourhd_business_email">
            Business Email
        </label>

        <input
            type="email"
            id="fourhd_business_email"
            name="fourhd_business_email"
        >

    </div>


    <div class="fourhd-field">

        <label for="fourhd_business_website">
            Website
        </label>

        <input
            type="text"
            id="fourhd_business_website"
            name="fourhd_business_website"
            placeholder="https://"
        >

    </div>

</div>

        <!-- =============================================
             CONFIRMATION
        ============================================== -->

        <div class="fourhd-questionnaire-confirmation">

            <label>

                <input
                    type="checkbox"
                    name="fourhd_brief_confirmation"
                    value="yes"
                    required
                >

                <span>
                    I confirm that the information provided above is
                    accurate and will be used as the creative brief
                    for my project.
                </span>

            </label>

        </div>

    </div>

    <!-- =============================================
     PREMIUM — SOCIAL MEDIA
============================================== -->

<div
    id="fourhd-premium-fields"
    class="fourhd-questionnaire-section fourhd-package-fields"
    hidden
>

    <h4>Social Media Information</h4>

    <p>
        Tell us where your brand is active so we can prepare
        your Social Media Kit.
    </p>


    <div class="fourhd-field">

        <label>
            Social Media Platforms
        </label>

        <div class="fourhd-checkbox-grid">

            <?php

            $social_platforms = [
                'instagram' => 'Instagram',
                'facebook'  => 'Facebook',
                'linkedin'  => 'LinkedIn',
                'x'         => 'X / Twitter',
                'tiktok'    => 'TikTok',
                'youtube'   => 'YouTube',
                'other'     => 'Other',
            ];

            foreach ($social_platforms as $value => $label) :
            ?>

                <label class="fourhd-checkbox-option">

                    <input
                        type="checkbox"
                        name="fourhd_social_platforms[]"
                        value="<?php echo esc_attr($value); ?>"
                    >

                    <span>
                        <?php echo esc_html($label); ?>
                    </span>

                </label>

            <?php endforeach; ?>

        </div>

    </div>


    <div class="fourhd-field">

        <label for="fourhd_social_usernames">
            Social Media Username(s)
        </label>

        <textarea
            id="fourhd_social_usernames"
            name="fourhd_social_usernames"
            rows="3"
            placeholder="Instagram: @yourbrand&#10;Facebook: Your Brand"
        ></textarea>

    </div>


    <div class="fourhd-field">

        <label for="fourhd_social_content">
            Social Media Content Style
        </label>

        <textarea
            id="fourhd_social_content"
            name="fourhd_social_content"
            rows="4"
        ></textarea>

        <small>
            What type of content does your business typically
            publish or plan to publish?
        </small>

    </div>

</div>

    <?php
}

/* =========================================================
   3. PACKAGE / VARIATION BEHAVIOR
========================================================= */

add_action('wp_footer', 'fourhd_questionnaire_variation_script');

function fourhd_questionnaire_variation_script() {

    if (!is_product()) {
        return;
    }

    ?>

    <script>
    jQuery(function($) {

            $('form.cart').attr(
            'enctype',
            'multipart/form-data'
        );

        const questionnaire = $('#fourhd-project-questionnaire');

        if (!questionnaire.length) {
            return;
        }


        const advancedFields = $('#fourhd-advanced-fields');
        const premiumFields  = $('#fourhd-premium-fields');

        // Hide the entire questionnaire until a package is selected
        questionnaire.hide();
        /**
         * Hide package-specific fields by default.
         */
        function resetPackageFields() {

            advancedFields.prop('hidden', true);
            premiumFields.prop('hidden', true);

        }


        /**
         * WooCommerce found a valid variation.
         */
        $('form.variations_form').on(
            'found_variation',
            function(event, variation) {

                resetPackageFields();
                questionnaire.stop(true, true).slideDown(250);


                if (
                    !variation ||
                    !variation.attributes
                ) {
                    return;
                }


                let packageName = '';


                /**
                 * Find the Package attribute.
                 *
                 * This works even if WooCommerce generated
                 * something such as:
                 *
                 * attribute_package
                 * attribute_pa_package
                 */
                $.each(
                    variation.attributes,
                    function(attributeName, attributeValue) {

                        if (
                            attributeName
                                .toLowerCase()
                                .includes('package')
                        ) {

                            packageName =
                                String(attributeValue)
                                    .toLowerCase();

                        }

                    }
                );


                /**
                 * ADVANCED
                 */
                if (packageName.includes('advanced')) {

                    advancedFields.prop('hidden', false);

                }


                /**
                 * PREMIUM
                 *
                 * Premium includes everything from Advanced.
                 */
                if (packageName.includes('premium')) {

                    advancedFields.prop('hidden', false);
                    premiumFields.prop('hidden', false);

                }

            }
        );


        /**
         * Customer resets the variation.
         */
       $('form.variations_form').on(
    'reset_data hide_variation',
    function() {

        resetPackageFields();

        questionnaire
            .stop(true, true)
            .slideUp(200);

    }
);


        resetPackageFields();

    });
    </script>

    <?php
}

/* =========================================================
   4. QUESTIONNAIRE VALIDATION
========================================================= */

add_filter(
    'woocommerce_add_to_cart_validation',
    'fourhd_questionnaire_validation',
    10,
    5
);

function fourhd_questionnaire_validation(
    $passed,
    $product_id,
    $quantity,
    $variation_id = 0,
    $variations = []
) {

    $parent_id = $product_id;

    if ($variation_id) {
        $variation = wc_get_product($variation_id);

        if ($variation && $variation->is_type('variation')) {
            $parent_id = $variation->get_parent_id();
        }
    }

    $enabled = get_post_meta(
        $parent_id,
        '_fourhd_enable_questionnaire',
        true
    );

    if ($enabled !== 'yes') {
        return $passed;
    }


    /* -----------------------------------------
       BUSINESS / BRAND NAME
    ----------------------------------------- */

    if (
        empty($_POST['fourhd_brand_name']) ||
        !trim(wp_unslash($_POST['fourhd_brand_name']))
    ) {

        wc_add_notice(
            'Please enter your Business / Brand Name.',
            'error'
        );

        $passed = false;
    }


    /* -----------------------------------------
       BUSINESS DESCRIPTION
    ----------------------------------------- */

    if (
        empty($_POST['fourhd_business_description']) ||
        !trim(wp_unslash($_POST['fourhd_business_description']))
    ) {

        wc_add_notice(
            'Please tell us about your business.',
            'error'
        );

        $passed = false;
    }


    /* -----------------------------------------
       BRAND STYLE
    ----------------------------------------- */

    $brand_styles =
        isset($_POST['fourhd_brand_style']) &&
        is_array($_POST['fourhd_brand_style'])
            ? $_POST['fourhd_brand_style']
            : [];

    $other_style =
        isset($_POST['fourhd_other_style'])
            ? trim(
                sanitize_text_field(
                    wp_unslash($_POST['fourhd_other_style'])
                )
            )
            : '';

    if (empty($brand_styles) && empty($other_style)) {

        wc_add_notice(
            'Please select at least one Brand Style or enter another style.',
            'error'
        );

        $passed = false;
    }


    /* -----------------------------------------
       CONFIRMATION
    ----------------------------------------- */

    if (
        empty($_POST['fourhd_brief_confirmation']) ||
        $_POST['fourhd_brief_confirmation'] !== 'yes'
    ) {

        wc_add_notice(
            'Please confirm that your project information is accurate.',
            'error'
        );

        $passed = false;
    }


    return $passed;
}

/* =========================================================
   5. SAVE QUESTIONNAIRE TO CART
========================================================= */

add_filter(
    'woocommerce_add_cart_item_data',
    'fourhd_save_questionnaire_cart_data',
    10,
    3
);

function fourhd_save_questionnaire_cart_data(
    $cart_item_data,
    $product_id,
    $variation_id
) {

    $parent_id = $product_id;

    if ($variation_id) {

        $variation = wc_get_product($variation_id);

        if ($variation && $variation->is_type('variation')) {
            $parent_id = $variation->get_parent_id();
        }
    }


    $enabled = get_post_meta(
        $parent_id,
        '_fourhd_enable_questionnaire',
        true
    );

    if ($enabled !== 'yes') {
        return $cart_item_data;
    }


    /* -----------------------------------------
       DETERMINE SELECTED PACKAGE
    ----------------------------------------- */

    $package = '';

    if ($variation_id) {

        $variation = wc_get_product($variation_id);

        if ($variation) {

            foreach ($variation->get_attributes() as $key => $value) {

                if (stripos($key, 'package') !== false) {

                    $package = sanitize_text_field($value);
                    break;
                }
            }
        }
    }


    /* -----------------------------------------
       BASE QUESTIONNAIRE
    ----------------------------------------- */

    $cart_item_data['fourhd_questionnaire'] = [

        'package' => $package,

        'brand_name' =>
            isset($_POST['fourhd_brand_name'])
                ? sanitize_text_field(
                    wp_unslash($_POST['fourhd_brand_name'])
                )
                : '',

        'tagline' =>
            isset($_POST['fourhd_tagline'])
                ? sanitize_text_field(
                    wp_unslash($_POST['fourhd_tagline'])
                )
                : '',

        'business_description' =>
            isset($_POST['fourhd_business_description'])
                ? sanitize_textarea_field(
                    wp_unslash($_POST['fourhd_business_description'])
                )
                : '',

        'brand_style' =>
            isset($_POST['fourhd_brand_style']) &&
            is_array($_POST['fourhd_brand_style'])
                ? array_map(
                    'sanitize_text_field',
                    wp_unslash($_POST['fourhd_brand_style'])
                )
                : [],

        'other_style' =>
            isset($_POST['fourhd_other_style'])
                ? sanitize_text_field(
                    wp_unslash($_POST['fourhd_other_style'])
                )
                : '',

        'preferred_colors' =>
            isset($_POST['fourhd_preferred_colors'])
                ? sanitize_text_field(
                    wp_unslash($_POST['fourhd_preferred_colors'])
                )
                : '',

        'colors_avoid' =>
            isset($_POST['fourhd_colors_avoid'])
                ? sanitize_text_field(
                    wp_unslash($_POST['fourhd_colors_avoid'])
                )
                : '',

        'references' =>
            isset($_POST['fourhd_references'])
                ? sanitize_textarea_field(
                    wp_unslash($_POST['fourhd_references'])
                )
                : '',

        'additional_information' =>
            isset($_POST['fourhd_additional_information'])
                ? sanitize_textarea_field(
                    wp_unslash($_POST['fourhd_additional_information'])
                )
                : '',
    ];


    /* -----------------------------------------
       ADVANCED / PREMIUM
    ----------------------------------------- */

    if (
        stripos($package, 'advanced') !== false ||
        stripos($package, 'premium') !== false
    ) {

        $cart_item_data['fourhd_questionnaire']['contact_name'] =
            isset($_POST['fourhd_contact_name'])
                ? sanitize_text_field(
                    wp_unslash($_POST['fourhd_contact_name'])
                )
                : '';

        $cart_item_data['fourhd_questionnaire']['job_title'] =
            isset($_POST['fourhd_job_title'])
                ? sanitize_text_field(
                    wp_unslash($_POST['fourhd_job_title'])
                )
                : '';

        $cart_item_data['fourhd_questionnaire']['business_address'] =
            isset($_POST['fourhd_business_address'])
                ? sanitize_textarea_field(
                    wp_unslash($_POST['fourhd_business_address'])
                )
                : '';

        $cart_item_data['fourhd_questionnaire']['business_phone'] =
            isset($_POST['fourhd_business_phone'])
                ? sanitize_text_field(
                    wp_unslash($_POST['fourhd_business_phone'])
                )
                : '';

        $cart_item_data['fourhd_questionnaire']['business_email'] =
            isset($_POST['fourhd_business_email'])
                ? sanitize_email(
                    wp_unslash($_POST['fourhd_business_email'])
                )
                : '';

        $cart_item_data['fourhd_questionnaire']['business_website'] =
            isset($_POST['fourhd_business_website'])
                ? esc_url_raw(
                    wp_unslash($_POST['fourhd_business_website'])
                )
                : '';
    }


    /* -----------------------------------------
       PREMIUM
    ----------------------------------------- */

    if (stripos($package, 'premium') !== false) {

        $cart_item_data['fourhd_questionnaire']['social_platforms'] =
            isset($_POST['fourhd_social_platforms']) &&
            is_array($_POST['fourhd_social_platforms'])
                ? array_map(
                    'sanitize_text_field',
                    wp_unslash($_POST['fourhd_social_platforms'])
                )
                : [];

        $cart_item_data['fourhd_questionnaire']['social_usernames'] =
            isset($_POST['fourhd_social_usernames'])
                ? sanitize_textarea_field(
                    wp_unslash($_POST['fourhd_social_usernames'])
                )
                : '';

        $cart_item_data['fourhd_questionnaire']['social_content'] =
            isset($_POST['fourhd_social_content'])
                ? sanitize_textarea_field(
                    wp_unslash($_POST['fourhd_social_content'])
                )
                : '';
    }


    /*
     * Prevent WooCommerce from merging two separately
     * configured projects into one cart item.
     */
    $cart_item_data['fourhd_questionnaire_key'] =
        wp_generate_uuid4();


    return $cart_item_data;
}

/* =========================================================
   6. REFERENCE FILE UPLOAD
========================================================= */

add_filter(
    'woocommerce_add_cart_item_data',
    'fourhd_questionnaire_reference_upload',
    20,
    3
);

function fourhd_questionnaire_reference_upload(
    $cart_item_data,
    $product_id,
    $variation_id
) {

    if (
        empty($_FILES['fourhd_reference_file']) ||
        empty($_FILES['fourhd_reference_file']['name'])
    ) {
        return $cart_item_data;
    }


    $file = $_FILES['fourhd_reference_file'];


    if ($file['error'] !== UPLOAD_ERR_OK) {

        wc_add_notice(
            'There was a problem uploading your reference file.',
            'error'
        );

        return $cart_item_data;
    }


    /* Maximum 10 MB */

    if ($file['size'] > 10 * 1024 * 1024) {

        wc_add_notice(
            'The reference file cannot exceed 10 MB.',
            'error'
        );

        return $cart_item_data;
    }


    require_once ABSPATH . 'wp-admin/includes/file.php';


    $allowed_mimes = [
        'jpg|jpeg' => 'image/jpeg',
        'png'      => 'image/png',
        'pdf'      => 'application/pdf',
    ];


    $upload = wp_handle_upload(
        $file,
        [
            'test_form' => false,
            'mimes'     => $allowed_mimes,
        ]
    );


    if (isset($upload['error'])) {

        wc_add_notice(
            'Reference file upload failed: ' .
            esc_html($upload['error']),
            'error'
        );

        return $cart_item_data;
    }


    if (!isset($cart_item_data['fourhd_questionnaire'])) {
        $cart_item_data['fourhd_questionnaire'] = [];
    }


    $cart_item_data['fourhd_questionnaire']['reference_file'] = [
        'name' => sanitize_file_name($file['name']),
        'url'  => esc_url_raw($upload['url']),
        'file' => sanitize_text_field($upload['file']),
    ];


    return $cart_item_data;
}

/* =========================================================
   7. SAVE QUESTIONNAIRE TO ORDER
========================================================= */

add_action(
    'woocommerce_checkout_create_order_line_item',
    'fourhd_questionnaire_save_to_order',
    10,
    4
);

function fourhd_questionnaire_save_to_order(
    $item,
    $cart_item_key,
    $values,
    $order
) {

    if (empty($values['fourhd_questionnaire'])) {
        return;
    }

    $q = $values['fourhd_questionnaire'];


    /* =========================================
       PROJECT INFORMATION
    ========================================= */

    if (!empty($q['brand_name'])) {
        $item->add_meta_data(
            'Business / Brand Name',
            $q['brand_name'],
            true
        );
    }

    if (!empty($q['tagline'])) {
        $item->add_meta_data(
            'Tagline / Slogan',
            $q['tagline'],
            true
        );
    }

    if (!empty($q['business_description'])) {
        $item->add_meta_data(
            'About the Business',
            $q['business_description'],
            true
        );
    }

    if (!empty($q['brand_style'])) {

        $styles = array_map(
            function($style) {
                return ucwords(
                    str_replace(
                        ['-', '_'],
                        ' ',
                        $style
                    )
                );
            },
            $q['brand_style']
        );

        $item->add_meta_data(
            'Brand Style',
            implode(', ', $styles),
            true
        );
    }

    if (!empty($q['other_style'])) {
        $item->add_meta_data(
            'Other Style',
            $q['other_style'],
            true
        );
    }

    if (!empty($q['preferred_colors'])) {
        $item->add_meta_data(
            'Preferred Colors',
            $q['preferred_colors'],
            true
        );
    }

    if (!empty($q['colors_avoid'])) {
        $item->add_meta_data(
            'Colors to Avoid',
            $q['colors_avoid'],
            true
        );
    }

    if (!empty($q['references'])) {
        $item->add_meta_data(
            'References / Inspiration',
            $q['references'],
            true
        );
    }

    if (!empty($q['additional_information'])) {
        $item->add_meta_data(
            'Additional Information',
            $q['additional_information'],
            true
        );
    }


    /* =========================================
       REFERENCE FILE
    ========================================= */

    if (
        !empty($q['reference_file']) &&
        !empty($q['reference_file']['url'])
    ) {

        $file_name =
            !empty($q['reference_file']['name'])
                ? $q['reference_file']['name']
                : 'Reference File';

        $item->add_meta_data(
            'Reference File',
            $file_name . ' | ' . $q['reference_file']['url'],
            true
        );
    }


    /* =========================================
       CORPORATE STATIONERY
       Advanced + Premium
    ========================================= */

    if (!empty($q['contact_name'])) {
        $item->add_meta_data(
            'Contact Name',
            $q['contact_name'],
            true
        );
    }

    if (!empty($q['job_title'])) {
        $item->add_meta_data(
            'Job Title',
            $q['job_title'],
            true
        );
    }

    if (!empty($q['business_address'])) {
        $item->add_meta_data(
            'Business Address',
            $q['business_address'],
            true
        );
    }

    if (!empty($q['business_phone'])) {
        $item->add_meta_data(
            'Business Phone',
            $q['business_phone'],
            true
        );
    }

    if (!empty($q['business_email'])) {
        $item->add_meta_data(
            'Business Email',
            $q['business_email'],
            true
        );
    }

    if (!empty($q['business_website'])) {
        $item->add_meta_data(
            'Website',
            $q['business_website'],
            true
        );
    }


    /* =========================================
       SOCIAL MEDIA
       Premium
    ========================================= */

    if (!empty($q['social_platforms'])) {

        $platforms = array_map(
            function($platform) {

                if ($platform === 'x') {
                    return 'X / Twitter';
                }

                return ucwords(
                    str_replace(
                        ['-', '_'],
                        ' ',
                        $platform
                    )
                );
            },
            $q['social_platforms']
        );

        $item->add_meta_data(
            'Social Media Platforms',
            implode(', ', $platforms),
            true
        );
    }

    if (!empty($q['social_usernames'])) {
        $item->add_meta_data(
            'Social Media Username(s)',
            $q['social_usernames'],
            true
        );
    }

    if (!empty($q['social_content'])) {
        $item->add_meta_data(
            'Social Media Content Style',
            $q['social_content'],
            true
        );
    }
}