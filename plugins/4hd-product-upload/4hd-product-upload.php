<?php
/*
Plugin Name: 4HD Product Upload
Description: Reusable artwork upload and product customizer for WooCommerce products.
Version: 2.0.0
Author: 4HD PRINT
*/

defined('ABSPATH') || exit;

/* =========================================================
   HELPERS
========================================================= */

function fourhd_get_upload_mode($product_id) {
    $mode = get_post_meta($product_id, '_fourhd_upload_mode', true);

    // Backward compatibility with v1.x products.
    if (!$mode) {
        $legacy_enabled = get_post_meta($product_id, '_fourhd_enable_upload', true);
        if ($legacy_enabled === 'yes') {
            return 'customizer';
        }
    }

    return in_array($mode, ['disabled', 'customizer', 'print'], true)
        ? $mode
        : 'disabled';
}

function fourhd_get_print_fields($product_id) {
    $fields = get_post_meta($product_id, '_fourhd_print_fields', true);

    if (!is_array($fields) || empty($fields)) {
        return [
            [
                'key'             => 'artwork',
                'label'           => 'Print Design',
                'required'        => 'yes',
                'condition_attr'  => '',
                'condition_value' => '',
            ],
        ];
    }

    return $fields;
}

function fourhd_print_allowed_mimes($product_id) {
    $types = get_post_meta($product_id, '_fourhd_print_file_types', true);
    if (!is_array($types) || empty($types)) {
        $types = ['pdf', 'jpg', 'png'];
    }

    $mimes = [];
    if (in_array('pdf', $types, true)) {
        $mimes['pdf'] = 'application/pdf';
    }
    if (in_array('jpg', $types, true)) {
        $mimes['jpg|jpeg'] = 'image/jpeg';
    }
    if (in_array('png', $types, true)) {
        $mimes['png'] = 'image/png';
    }

    return $mimes;
}

function fourhd_print_field_is_active($field, $variation_attributes = []) {
    $attr  = !empty($field['condition_attr']) ? sanitize_title($field['condition_attr']) : '';
    $value = isset($field['condition_value']) ? sanitize_title($field['condition_value']) : '';

    if ($attr === '' || $value === '') {
        return true;
    }

    $keys = [
        'attribute_' . $attr,
        'attribute_pa_' . $attr,
        $attr,
        'pa_' . $attr,
    ];

    foreach ($keys as $key) {
        if (isset($variation_attributes[$key])) {
            return sanitize_title($variation_attributes[$key]) === $value;
        }
    }

    // On the initial variable-product page load no variation is selected yet.
    return false;
}

/* =========================================================
   1. PRODUCT SETTINGS
========================================================= */

add_action('woocommerce_product_options_general_product_data', function () {
    global $post;

    echo '<div class="options_group">';

    woocommerce_wp_select([
        'id'          => '_fourhd_upload_mode',
        'label'       => 'Upload Mode',
        'description' => 'Choose how customers provide artwork for this product.',
        'desc_tip'    => true,
        'options'     => [
            'disabled'   => 'Disabled',
            'customizer' => 'Product Customizer',
            'print'      => 'Print Artwork Upload',
        ],
    ]);

    echo '</div>';

    echo '<div class="options_group fourhd-customizer-admin">';
    echo '<p style="padding:0 12px"><strong>Product Customizer Pricing</strong></p>';

    $price_fields = [
        '_fourhd_front_extra'        => ['Front Print Extra', 'Extra price when Front is an additional print location.'],
        '_fourhd_back_extra'         => ['Back Print Extra', 'Extra price when Back is an additional print location.'],
        '_fourhd_pocket_extra'       => ['Pocket Print Extra', 'Extra price when Pocket is an additional print location.'],
        '_fourhd_left_sleeve_extra'  => ['Left Sleeve Extra', 'Extra price when Left Sleeve is an additional print location.'],
        '_fourhd_right_sleeve_extra' => ['Right Sleeve Extra', 'Extra price when Right Sleeve is an additional print location.'],
    ];

    foreach ($price_fields as $id => $data) {
        woocommerce_wp_text_input([
            'id'                => $id,
            'label'             => $data[0],
            'description'       => $data[1],
            'desc_tip'          => true,
            'type'              => 'number',
            'custom_attributes' => ['step' => '0.01', 'min' => '0'],
        ]);
    }

    echo '</div>';

    echo '<div class="options_group fourhd-print-admin">';
    echo '<p style="padding:0 12px"><strong>Print Artwork Settings</strong></p>';

    woocommerce_wp_text_input([
        'id'                => '_fourhd_print_max_mb',
        'label'             => 'Maximum File Size',
        'description'       => 'Maximum size per artwork file, in MB.',
        'desc_tip'          => true,
        'type'              => 'number',
        'value'             => get_post_meta($post->ID, '_fourhd_print_max_mb', true) ?: '10',
        'custom_attributes' => ['step' => '1', 'min' => '1', 'max' => '100'],
    ]);

    $saved_types = get_post_meta($post->ID, '_fourhd_print_file_types', true);
    if (!is_array($saved_types) || empty($saved_types)) {
        $saved_types = ['pdf', 'jpg', 'png'];
    }

    echo '<p class="form-field"><label>Accepted Files</label>';
    foreach (['pdf' => 'PDF', 'jpg' => 'JPG / JPEG', 'png' => 'PNG'] as $key => $label) {
        printf(
            '<label style="margin-right:16px"><input type="checkbox" name="_fourhd_print_file_types[]" value="%s" %s> %s</label>',
            esc_attr($key),
            checked(in_array($key, $saved_types, true), true, false),
            esc_html($label)
        );
    }
    echo '</p>';

    echo '<p style="padding:0 12px 6px"><strong>Upload Fields</strong><br><span class="description">Create reusable artwork fields. Conditions are optional. Example: Back Design → attribute <code>printing</code> → value <code>front-back</code>.</span></p>';

    $fields = fourhd_get_print_fields($post->ID);
    echo '<div id="fourhd-print-fields" style="padding:0 12px 12px">';

    foreach ($fields as $index => $field) {
        fourhd_render_admin_print_field($index, $field);
    }

    echo '</div>';
    echo '<p style="padding:0 12px"><button type="button" class="button" id="fourhd-add-print-field">+ Add Upload Field</button></p>';
    echo '</div>';

    ?>
    <script type="text/template" id="fourhd-print-field-template">
        <?php fourhd_render_admin_print_field('__INDEX__', [
            'key' => '', 'label' => '', 'required' => 'yes', 'condition_attr' => '', 'condition_value' => ''
        ]); ?>
    </script>
    <script>
    jQuery(function($){
        function toggleFourHDAdmin(){
            var mode = $('#_fourhd_upload_mode').val();
            $('.fourhd-customizer-admin').toggle(mode === 'customizer');
            $('.fourhd-print-admin').toggle(mode === 'print');
        }
        toggleFourHDAdmin();
        $('#_fourhd_upload_mode').on('change', toggleFourHDAdmin);

        $('#fourhd-add-print-field').on('click', function(){
            var index = $('#fourhd-print-fields .fourhd-print-field-row').length;
            var html = $('#fourhd-print-field-template').html().replace(/__INDEX__/g, index);
            $('#fourhd-print-fields').append(html);
        });

        $(document).on('click', '.fourhd-remove-print-field', function(){
            $(this).closest('.fourhd-print-field-row').remove();
        });
    });
    </script>
    <?php
});

function fourhd_render_admin_print_field($index, $field) {
    $field = wp_parse_args($field, [
        'key' => '', 'label' => '', 'required' => 'yes', 'condition_attr' => '', 'condition_value' => ''
    ]);
    ?>
    <div class="fourhd-print-field-row" style="border:1px solid #ddd;padding:10px;margin:0 0 10px;background:#fff">
        <p style="margin:0 0 8px"><strong>Artwork Field</strong> <button type="button" class="button-link-delete fourhd-remove-print-field" style="float:right">Remove</button></p>
        <p style="margin:5px 0"><label>Key<br><input type="text" name="fourhd_print_fields[<?php echo esc_attr($index); ?>][key]" value="<?php echo esc_attr($field['key']); ?>" placeholder="front_design" style="width:100%"></label></p>
        <p style="margin:5px 0"><label>Label<br><input type="text" name="fourhd_print_fields[<?php echo esc_attr($index); ?>][label]" value="<?php echo esc_attr($field['label']); ?>" placeholder="Front Design" style="width:100%"></label></p>
        <p style="margin:5px 0"><label><input type="checkbox" name="fourhd_print_fields[<?php echo esc_attr($index); ?>][required]" value="yes" <?php checked($field['required'], 'yes'); ?>> Required when this field is active</label></p>
        <p style="margin:5px 0"><label>Show when attribute (optional)<br><input type="text" name="fourhd_print_fields[<?php echo esc_attr($index); ?>][condition_attr]" value="<?php echo esc_attr($field['condition_attr']); ?>" placeholder="printing" style="width:100%"></label></p>
        <p style="margin:5px 0"><label>Equals value/slug (optional)<br><input type="text" name="fourhd_print_fields[<?php echo esc_attr($index); ?>][condition_value]" value="<?php echo esc_attr($field['condition_value']); ?>" placeholder="front-back" style="width:100%"></label></p>
    </div>
    <?php
}

add_action('woocommerce_process_product_meta', function ($product_id) {
    $allowed_modes = ['disabled', 'customizer', 'print'];
    $mode = isset($_POST['_fourhd_upload_mode']) ? sanitize_key(wp_unslash($_POST['_fourhd_upload_mode'])) : 'disabled';
    if (!in_array($mode, $allowed_modes, true)) {
        $mode = 'disabled';
    }
    update_post_meta($product_id, '_fourhd_upload_mode', $mode);

    // Keep legacy meta synchronized for backward compatibility.
    update_post_meta($product_id, '_fourhd_enable_upload', $mode === 'customizer' ? 'yes' : 'no');

    foreach (['_fourhd_front_extra','_fourhd_back_extra','_fourhd_pocket_extra','_fourhd_left_sleeve_extra','_fourhd_right_sleeve_extra'] as $field) {
        $value = isset($_POST[$field]) ? wc_format_decimal(wp_unslash($_POST[$field])) : '';
        update_post_meta($product_id, $field, $value);
    }

    $max_mb = isset($_POST['_fourhd_print_max_mb']) ? absint($_POST['_fourhd_print_max_mb']) : 10;
    update_post_meta($product_id, '_fourhd_print_max_mb', max(1, min(100, $max_mb)));

    $allowed_types = ['pdf', 'jpg', 'png'];
    $types = isset($_POST['_fourhd_print_file_types']) && is_array($_POST['_fourhd_print_file_types'])
        ? array_values(array_intersect($allowed_types, array_map('sanitize_key', wp_unslash($_POST['_fourhd_print_file_types']))))
        : [];
    update_post_meta($product_id, '_fourhd_print_file_types', $types ?: ['pdf', 'jpg', 'png']);

    $clean_fields = [];
    if (isset($_POST['fourhd_print_fields']) && is_array($_POST['fourhd_print_fields'])) {
        foreach (wp_unslash($_POST['fourhd_print_fields']) as $row) {
            $label = isset($row['label']) ? sanitize_text_field($row['label']) : '';
            $key   = isset($row['key']) ? sanitize_key($row['key']) : '';
            if ($label === '') {
                continue;
            }
            if ($key === '') {
                $key = sanitize_key($label);
            }
            $clean_fields[] = [
                'key'             => $key,
                'label'           => $label,
                'required'        => !empty($row['required']) ? 'yes' : 'no',
                'condition_attr'  => isset($row['condition_attr']) ? sanitize_title($row['condition_attr']) : '',
                'condition_value' => isset($row['condition_value']) ? sanitize_title($row['condition_value']) : '',
            ];
        }
    }
    update_post_meta($product_id, '_fourhd_print_fields', $clean_fields);
});

/* =========================================================
   2. FRONTEND FIELDS
========================================================= */

add_action('woocommerce_before_add_to_cart_button', 'fourhd_upload_field');

function fourhd_upload_field() {
    global $product;
    if (!$product) {
        return;
    }

    $product_id = $product->is_type('variation') ? $product->get_parent_id() : $product->get_id();
    $mode = fourhd_get_upload_mode($product_id);

    if ($mode === 'customizer') {
        fourhd_render_product_customizer($product, $product_id);
    } elseif ($mode === 'print') {
        fourhd_render_print_artwork_upload($product, $product_id);
    }
}

function fourhd_render_product_customizer($product, $product_id) {
    $locations = [
        'front'        => ['label' => 'Front',        'price' => (float) get_post_meta($product_id, '_fourhd_front_extra', true)],
        'back'         => ['label' => 'Back',         'price' => (float) get_post_meta($product_id, '_fourhd_back_extra', true)],
        'pocket'       => ['label' => 'Pocket',       'price' => (float) get_post_meta($product_id, '_fourhd_pocket_extra', true)],
        'left_sleeve'  => ['label' => 'Left Sleeve',  'price' => (float) get_post_meta($product_id, '_fourhd_left_sleeve_extra', true)],
        'right_sleeve' => ['label' => 'Right Sleeve', 'price' => (float) get_post_meta($product_id, '_fourhd_right_sleeve_extra', true)],
    ];
    ?>
    <div class="fourhd-product-customizer" data-base-price="<?php echo esc_attr($product->get_price()); ?>">
        <h3>Customize Your T-Shirt</h3>
        <div class="fourhd-print-section">
            <h4>Included Print Location</h4>
            <p>Choose one print location included with your T-shirt.</p>
            <div class="fourhd-location-options">
                <?php foreach ($locations as $key => $location) : ?>
                    <label class="fourhd-location-option">
                        <input type="radio" name="fourhd_primary_location" value="<?php echo esc_attr($key); ?>" required>
                        <span><?php echo esc_html($location['label']); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="fourhd-print-section">
            <h4>Additional Print Locations</h4>
            <p>Add more print locations if needed.</p>
            <div class="fourhd-extra-locations">
                <?php foreach ($locations as $key => $location) : ?>
                    <label class="fourhd-extra-location" data-location="<?php echo esc_attr($key); ?>">
                        <input type="checkbox" name="fourhd_extra_locations[]" value="<?php echo esc_attr($key); ?>" data-price="<?php echo esc_attr($location['price']); ?>">
                        <span class="fourhd-extra-location__name"><?php echo esc_html($location['label']); ?></span>
                        <?php if ($location['price'] > 0) : ?>
                            <span class="fourhd-extra-location__price">+<?php echo wp_kses_post(wc_price($location['price'])); ?></span>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="fourhd-location-uploads">
            <?php foreach ($locations as $key => $location) : ?>
                <div class="fourhd-location-upload" data-location="<?php echo esc_attr($key); ?>" hidden>
                    <label for="fourhd_design_<?php echo esc_attr($key); ?>" class="fourhd-design-upload__label">Upload <?php echo esc_html($location['label']); ?> Design <span class="fourhd-required">*</span></label>
                    <input id="fourhd_design_<?php echo esc_attr($key); ?>" type="file" name="fourhd_design_<?php echo esc_attr($key); ?>" accept="image/png,image/jpeg">
                    <div class="fourhd-design-preview" data-preview="<?php echo esc_attr($key); ?>" hidden>
                        <img src="" alt="<?php echo esc_attr($location['label']); ?> design preview">
                        <strong class="fourhd-design-preview__filename"></strong>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="fourhd-custom-price"><span>Estimated Total</span><strong id="fourhd_custom_total"><?php echo wp_kses_post($product->get_price_html()); ?></strong></div>
        <label class="fourhd-mockup-option"><input type="checkbox" name="fourhd_mockup" value="yes"><span>I would like to receive a mockup by email.</span></label>
    </div>
    <?php
}

function fourhd_render_print_artwork_upload($product, $product_id) {
    $fields = fourhd_get_print_fields($product_id);
    $types = get_post_meta($product_id, '_fourhd_print_file_types', true);
    if (!is_array($types) || empty($types)) {
        $types = ['pdf', 'jpg', 'png'];
    }
    $max_mb = (int) get_post_meta($product_id, '_fourhd_print_max_mb', true);
    if ($max_mb < 1) {
        $max_mb = 10;
    }

    $accept = [];
    if (in_array('pdf', $types, true)) $accept[] = 'application/pdf';
    if (in_array('jpg', $types, true)) $accept[] = 'image/jpeg';
    if (in_array('png', $types, true)) $accept[] = 'image/png';
    ?>
    <div class="fourhd-print-artwork" data-fourhd-print-upload="1">
        <h3>Upload Your Design</h3>
        <p>Upload your print-ready artwork. Please use high-resolution files and include bleed when applicable.</p>
        <p class="fourhd-artwork-help"><strong>Accepted files:</strong> <?php echo esc_html(strtoupper(implode(', ', $types))); ?> &nbsp; <strong>Maximum:</strong> <?php echo esc_html($max_mb); ?> MB per file</p>

        <?php foreach ($fields as $field) :
            $key = sanitize_key($field['key']);
            $condition_attr = sanitize_title($field['condition_attr'] ?? '');
            $condition_value = sanitize_title($field['condition_value'] ?? '');
        ?>
            <div class="fourhd-artwork-field"
                 data-field-key="<?php echo esc_attr($key); ?>"
                 data-condition-attr="<?php echo esc_attr($condition_attr); ?>"
                 data-condition-value="<?php echo esc_attr($condition_value); ?>"
                 <?php echo ($condition_attr && $condition_value) ? 'hidden' : ''; ?>>
                <label for="fourhd_artwork_<?php echo esc_attr($key); ?>">
                    <?php echo esc_html($field['label']); ?>
                    <?php if (($field['required'] ?? 'no') === 'yes') : ?><span class="fourhd-required">*</span><?php endif; ?>
                </label>
                <input
                    id="fourhd_artwork_<?php echo esc_attr($key); ?>"
                    type="file"
                    name="fourhd_artwork_<?php echo esc_attr($key); ?>"
                    accept="<?php echo esc_attr(implode(',', $accept)); ?>"
                    data-required="<?php echo (($field['required'] ?? 'no') === 'yes') ? 'yes' : 'no'; ?>"
                >
                <div class="fourhd-artwork-filename" aria-live="polite"></div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        var wrap = document.querySelector('[data-fourhd-print-upload="1"]');
        if (!wrap) return;

        function selectedValue(attr){
            var selectors = [
                '[name="attribute_' + attr + '"]',
                '[name="attribute_pa_' + attr + '"]'
            ];
            for (var i=0; i<selectors.length; i++) {
                var el = document.querySelector(selectors[i]);
                if (el && el.value) return el.value;
            }
            return '';
        }

        function refreshFields(){
            wrap.querySelectorAll('.fourhd-artwork-field').forEach(function(row){
                var attr = row.dataset.conditionAttr || '';
                var wanted = row.dataset.conditionValue || '';
                var show = !attr || !wanted || selectedValue(attr) === wanted;
                row.hidden = !show;
                var input = row.querySelector('input[type="file"]');
                if (input) input.required = show && input.dataset.required === 'yes';
            });
        }

        document.querySelectorAll('form.variations_form select').forEach(function(select){
            select.addEventListener('change', refreshFields);
        });
        refreshFields();

        wrap.querySelectorAll('input[type="file"]').forEach(function(input){
            input.addEventListener('change', function(){
                var out = input.parentElement.querySelector('.fourhd-artwork-filename');
                if (out) out.textContent = input.files && input.files[0] ? input.files[0].name : '';
            });
        });
    });
    </script>
    <?php
}

/* =========================================================
   3. VALIDATION
========================================================= */

add_filter('woocommerce_add_to_cart_validation', 'fourhd_validate_upload', 10, 5);

function fourhd_validate_upload($passed, $product_id, $quantity, $variation_id = 0, $variations = []) {
    $mode = fourhd_get_upload_mode($product_id);

    if ($mode === 'customizer') {
        return fourhd_validate_customizer($passed);
    }

    if ($mode === 'print') {
        return fourhd_validate_print_upload($passed, $product_id, $variations);
    }

    return $passed;
}

function fourhd_validate_customizer($passed) {
    $allowed = ['front', 'back', 'pocket', 'left_sleeve', 'right_sleeve'];

    if (empty($_POST['fourhd_primary_location'])) {
        wc_add_notice('Please select an included print location.', 'error');
        return false;
    }

    $primary = sanitize_key(wp_unslash($_POST['fourhd_primary_location']));
    if (!in_array($primary, $allowed, true)) {
        wc_add_notice('Invalid print location.', 'error');
        return false;
    }

    $active = [$primary];
    if (!empty($_POST['fourhd_extra_locations']) && is_array($_POST['fourhd_extra_locations'])) {
        foreach (wp_unslash($_POST['fourhd_extra_locations']) as $location) {
            $location = sanitize_key($location);
            if (in_array($location, $allowed, true) && $location !== $primary) {
                $active[] = $location;
            }
        }
    }

    foreach (array_unique($active) as $location) {
        $name = 'fourhd_design_' . $location;
        if (empty($_FILES[$name]['name']) || !isset($_FILES[$name]['error']) || $_FILES[$name]['error'] !== UPLOAD_ERR_OK) {
            wc_add_notice(sprintf('Please upload your %s design.', ucwords(str_replace('_', ' ', $location))), 'error');
            return false;
        }
    }

    return $passed;
}

function fourhd_validate_print_upload($passed, $product_id, $variations) {
    $fields = fourhd_get_print_fields($product_id);
    $max_mb = (int) get_post_meta($product_id, '_fourhd_print_max_mb', true);
    if ($max_mb < 1) $max_mb = 10;
    $max_bytes = $max_mb * 1024 * 1024;
    $allowed_mimes = array_values(fourhd_print_allowed_mimes($product_id));

    foreach ($fields as $field) {
        if (!fourhd_print_field_is_active($field, $variations)) {
            continue;
        }

        $key = sanitize_key($field['key']);
        $name = 'fourhd_artwork_' . $key;
        $required = ($field['required'] ?? 'no') === 'yes';
        $has_file = !empty($_FILES[$name]['name']);

        if ($required && (!$has_file || !isset($_FILES[$name]['error']) || $_FILES[$name]['error'] !== UPLOAD_ERR_OK)) {
            wc_add_notice(sprintf('Please upload %s.', $field['label']), 'error');
            return false;
        }

        if (!$has_file) {
            continue;
        }

        if ($_FILES[$name]['error'] !== UPLOAD_ERR_OK) {
            wc_add_notice(sprintf('There was a problem uploading %s.', $field['label']), 'error');
            return false;
        }

        if ((int) $_FILES[$name]['size'] > $max_bytes) {
            wc_add_notice(sprintf('%s exceeds the %d MB file-size limit.', $field['label'], $max_mb), 'error');
            return false;
        }

        $check = wp_check_filetype_and_ext($_FILES[$name]['tmp_name'], $_FILES[$name]['name']);
        if (empty($check['type']) || !in_array($check['type'], $allowed_mimes, true)) {
            wc_add_notice(sprintf('%s has an unsupported file type.', $field['label']), 'error');
            return false;
        }
    }

    return $passed;
}

/* =========================================================
   4. SAVE TO CART
========================================================= */

add_filter('woocommerce_add_cart_item_data', 'fourhd_save_upload', 10, 2);

function fourhd_save_upload($cart_item_data, $product_id) {
    $mode = fourhd_get_upload_mode($product_id);

    if ($mode === 'customizer') {
        $cart_item_data = fourhd_save_customizer_to_cart($cart_item_data, $product_id);
    } elseif ($mode === 'print') {
        $cart_item_data = fourhd_save_print_upload_to_cart($cart_item_data, $product_id);
    }

    if ($mode !== 'disabled') {
        $cart_item_data['fourhd_unique_key'] = md5(microtime(true) . wp_rand());
    }

    return $cart_item_data;
}

function fourhd_save_customizer_to_cart($cart_item_data, $product_id) {
    $allowed = [
        'front' => 'Front', 'back' => 'Back', 'pocket' => 'Pocket',
        'left_sleeve' => 'Left Sleeve', 'right_sleeve' => 'Right Sleeve',
    ];

    if (!empty($_POST['fourhd_primary_location'])) {
        $primary = sanitize_key(wp_unslash($_POST['fourhd_primary_location']));
        if (isset($allowed[$primary])) {
            $cart_item_data['fourhd_primary_location'] = $primary;
        }
    }

    if (!empty($_POST['fourhd_extra_locations']) && is_array($_POST['fourhd_extra_locations'])) {
        $extras = array_map('sanitize_key', wp_unslash($_POST['fourhd_extra_locations']));
        $extras = array_filter($extras, function ($location) use ($allowed) { return isset($allowed[$location]); });
        if (!empty($cart_item_data['fourhd_primary_location'])) {
            $extras = array_diff($extras, [$cart_item_data['fourhd_primary_location']]);
        }
        $cart_item_data['fourhd_extra_locations'] = array_values($extras);
    }

    $active = [];
    if (!empty($cart_item_data['fourhd_primary_location'])) $active[] = $cart_item_data['fourhd_primary_location'];
    if (!empty($cart_item_data['fourhd_extra_locations'])) $active = array_merge($active, $cart_item_data['fourhd_extra_locations']);

    if ($active) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        foreach (array_unique($active) as $location) {
            $name = 'fourhd_design_' . $location;
            if (empty($_FILES[$name]['name']) || $_FILES[$name]['error'] !== UPLOAD_ERR_OK) continue;
            $file = $_FILES[$name];
            $upload = wp_handle_upload($file, [
                'test_form' => false,
                'mimes' => ['jpg|jpeg' => 'image/jpeg', 'png' => 'image/png'],
            ]);
            if (!empty($upload['url']) && empty($upload['error'])) {
                $cart_item_data['fourhd_designs'][$location] = [
                    'url' => esc_url_raw($upload['url']),
                    'name' => sanitize_file_name($file['name']),
                ];
            }
        }
    }

    if (isset($_POST['fourhd_mockup']) && $_POST['fourhd_mockup'] === 'yes') {
        $cart_item_data['fourhd_mockup'] = 'yes';
    }

    return $cart_item_data;
}

function fourhd_save_print_upload_to_cart($cart_item_data, $product_id) {
    $fields = fourhd_get_print_fields($product_id);
    $variation_attrs = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'attribute_') === 0 && !is_array($value)) {
            $variation_attrs[sanitize_key($key)] = sanitize_title(wp_unslash($value));
        }
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    $mimes = fourhd_print_allowed_mimes($product_id);

    foreach ($fields as $field) {
        if (!fourhd_print_field_is_active($field, $variation_attrs)) continue;
        $key = sanitize_key($field['key']);
        $name = 'fourhd_artwork_' . $key;
        if (empty($_FILES[$name]['name']) || $_FILES[$name]['error'] !== UPLOAD_ERR_OK) continue;

        $file = $_FILES[$name];
        $upload = wp_handle_upload($file, ['test_form' => false, 'mimes' => $mimes]);
        if (!empty($upload['url']) && empty($upload['error'])) {
            $cart_item_data['fourhd_print_artwork'][$key] = [
                'label' => sanitize_text_field($field['label']),
                'url'   => esc_url_raw($upload['url']),
                'name'  => sanitize_file_name($file['name']),
            ];
        }
    }

    return $cart_item_data;
}

/* =========================================================
   5. CART / CHECKOUT DISPLAY
========================================================= */

add_filter('woocommerce_get_item_data', 'fourhd_show_upload', 10, 2);

function fourhd_show_upload($item_data, $cart_item) {
    $labels = [
        'front' => 'Front', 'back' => 'Back', 'pocket' => 'Pocket',
        'left_sleeve' => 'Left Sleeve', 'right_sleeve' => 'Right Sleeve',
    ];

    if (!empty($cart_item['fourhd_primary_location'])) {
        $location = $cart_item['fourhd_primary_location'];
        $item_data[] = ['name' => 'Included Print', 'value' => $labels[$location] ?? $location];
    }

    if (!empty($cart_item['fourhd_extra_locations'])) {
        $extra_labels = [];
        foreach ($cart_item['fourhd_extra_locations'] as $location) $extra_labels[] = $labels[$location] ?? $location;
        $item_data[] = ['name' => 'Additional Prints', 'value' => implode(', ', $extra_labels)];
    }

    if (!empty($cart_item['fourhd_designs'])) {
        foreach ($cart_item['fourhd_designs'] as $location => $design) {
            $item_data[] = ['name' => ($labels[$location] ?? $location) . ' Design', 'value' => 'File received'];
        }
    }

    if (!empty($cart_item['fourhd_print_artwork'])) {
        foreach ($cart_item['fourhd_print_artwork'] as $artwork) {
            $item_data[] = ['name' => $artwork['label'], 'value' => $artwork['name'] ?: 'File received'];
        }
    }

    if (!empty($cart_item['fourhd_mockup']) && $cart_item['fourhd_mockup'] === 'yes') {
        $item_data[] = ['name' => 'Mockup', 'value' => 'Requested by email'];
    }

    return $item_data;
}

/* =========================================================
   6. SAVE TO ORDER
========================================================= */

add_action('woocommerce_checkout_create_order_line_item', 'fourhd_save_design_to_order', 10, 4);

function fourhd_save_design_to_order($item, $cart_item_key, $values, $order) {
    $labels = [
        'front' => 'Front', 'back' => 'Back', 'pocket' => 'Pocket',
        'left_sleeve' => 'Left Sleeve', 'right_sleeve' => 'Right Sleeve',
    ];

    if (!empty($values['fourhd_primary_location'])) {
        $location = $values['fourhd_primary_location'];
        $item->add_meta_data('Included Print', $labels[$location] ?? $location);
    }

    if (!empty($values['fourhd_extra_locations'])) {
        $extras = [];
        foreach ($values['fourhd_extra_locations'] as $location) $extras[] = $labels[$location] ?? $location;
        $item->add_meta_data('Additional Prints', implode(', ', $extras));
    }

    if (!empty($values['fourhd_designs'])) {
        foreach ($values['fourhd_designs'] as $location => $design) {
            $item->add_meta_data(($labels[$location] ?? $location) . ' Design', esc_url_raw($design['url']));
        }
    }

    if (!empty($values['fourhd_print_artwork'])) {
        foreach ($values['fourhd_print_artwork'] as $artwork) {
            $item->add_meta_data($artwork['label'], esc_url_raw($artwork['url']));
            $item->add_meta_data('_' . sanitize_key($artwork['label']) . '_filename', sanitize_file_name($artwork['name']));
        }
    }

    if (!empty($values['fourhd_mockup']) && $values['fourhd_mockup'] === 'yes') {
        $item->add_meta_data('Mockup', 'Requested by email');
    }
}

/* =========================================================
   7. CUSTOMIZER PRICING
========================================================= */

add_action('woocommerce_before_calculate_totals', 'fourhd_apply_custom_print_price');

function fourhd_apply_custom_print_price($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        if (empty($cart_item['fourhd_primary_location'])) continue;

        $product_id = $cart_item['product_id'];
        if (fourhd_get_upload_mode($product_id) !== 'customizer') continue;

        $base_product = wc_get_product(!empty($cart_item['variation_id']) ? $cart_item['variation_id'] : $product_id);
        if (!$base_product) continue;

        $base_price = (float) $base_product->get_price();
        $extra_price = 0;
        $price_fields = [
            'front' => '_fourhd_front_extra',
            'back' => '_fourhd_back_extra',
            'pocket' => '_fourhd_pocket_extra',
            'left_sleeve' => '_fourhd_left_sleeve_extra',
            'right_sleeve' => '_fourhd_right_sleeve_extra',
        ];

        if (!empty($cart_item['fourhd_extra_locations'])) {
            foreach ($cart_item['fourhd_extra_locations'] as $location) {
                if ($location === $cart_item['fourhd_primary_location']) continue;
                if (isset($price_fields[$location])) {
                    $extra_price += (float) get_post_meta($product_id, $price_fields[$location], true);
                }
            }
        }

        $cart_item['data']->set_price($base_price + $extra_price);
    }
}
