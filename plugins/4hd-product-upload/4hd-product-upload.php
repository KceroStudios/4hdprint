<?php
/*
Plugin Name: 4HD Product Upload
Description: Reusable artwork upload and product customizer for WooCommerce products.
Version: 2.0.1
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

    echo '<style>
        #woocommerce-product-data .fourhd-mode-group .form-field select { min-width: 250px; }
        #woocommerce-product-data .fourhd-admin-heading { margin: 0; padding: 12px; font-size: 14px; }
        #woocommerce-product-data .fourhd-print-settings { padding: 0 12px 12px; }
        #woocommerce-product-data .fourhd-print-settings .fourhd-setting-row { margin: 0 0 16px; }
        #woocommerce-product-data .fourhd-print-settings .fourhd-setting-label { display:block; margin:0 0 6px; font-weight:600; }
        #woocommerce-product-data .fourhd-print-settings input[type="number"],
        #woocommerce-product-data .fourhd-print-settings input[type="text"] { width:100%; max-width:420px; }
        #woocommerce-product-data .fourhd-file-types { display:flex; flex-wrap:wrap; gap:8px 20px; align-items:center; }
        #woocommerce-product-data .fourhd-file-types label { display:inline-flex; align-items:center; gap:6px; margin:0; width:auto; float:none; }
        #woocommerce-product-data .fourhd-file-types input[type="checkbox"] { margin:0; }
        #woocommerce-product-data .fourhd-upload-fields-help { margin:18px 0 10px; }
        #woocommerce-product-data .fourhd-print-field-row { border:1px solid #dcdcde; border-radius:4px; padding:14px; margin:0 0 12px; background:#fff; }
        #woocommerce-product-data .fourhd-print-field-header { display:flex; justify-content:space-between; align-items:center; gap:12px; margin:0 0 14px; }
        #woocommerce-product-data .fourhd-print-field-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        #woocommerce-product-data .fourhd-print-field-control { min-width:0; }
        #woocommerce-product-data .fourhd-print-field-control label { display:block; width:auto; float:none; margin:0 0 5px; font-weight:600; }
        #woocommerce-product-data .fourhd-print-field-control input[type="text"] { width:100%; max-width:none; float:none; margin:0; }
        #woocommerce-product-data .fourhd-print-field-required { grid-column:1 / -1; }
        #woocommerce-product-data .fourhd-print-field-required label { display:inline-flex; align-items:center; gap:7px; width:auto; float:none; margin:0; font-weight:400; }
        #woocommerce-product-data .fourhd-print-field-required input { margin:0; }
        #woocommerce-product-data .fourhd-condition-title { grid-column:1 / -1; margin:2px 0 -4px; padding-top:12px; border-top:1px solid #eee; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#646970; }
        #woocommerce-product-data .fourhd-add-field-wrap { margin:12px 0 0; }
        @media (max-width: 782px) {
            #woocommerce-product-data .fourhd-print-field-grid { grid-template-columns:1fr; }
            #woocommerce-product-data .fourhd-print-field-required,
            #woocommerce-product-data .fourhd-condition-title { grid-column:1; }
        }
    </style>';

    echo '<div class="options_group fourhd-mode-group">';
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
    woocommerce_wp_select([
    'id'          => '_fourhd_customizer_type',
    'label'       => 'Customizer Type',
    'description' => 'Select the customization layout for this product.',
    'desc_tip'    => true,
    'options'     => [
        'apparel' => 'Apparel',
        'caps'    => 'Caps',
    ],
]);

    woocommerce_wp_select([
        'id'          => '_fourhd_cap_print_layout',
        'label'       => 'Cap Print Layout',
        'description' => 'Choose which print locations customers can customize.',
        'desc_tip'    => true,
        'options'     => [
            'front_only' => 'Front Only',
            'multiple'   => 'Multiple Locations',
        ],
    ]);

woocommerce_wp_text_input([
    'id'          => '_fourhd_customizer_heading',
    'label'       => 'Customizer Heading',
    'placeholder' => 'Customize Your T-Shirt',
    'description' => 'Example: Customize Your Hoodie, Customize Your Cap.',
    'desc_tip'    => true,
]);
    echo '<h4 class="fourhd-admin-heading">Product Customizer Pricing</h4>';

    $price_fields = [
        // Apparel and Caps
        '_fourhd_front_extra' => [
            'Front Print Extra',
            'Additional front printing cost.'
        ],
        '_fourhd_back_extra' => [
            'Back Print Extra',
            'Additional back printing cost.'
        ],

        // Apparel only
        '_fourhd_pocket_extra' => [
            'Pocket Print Extra',
            'Additional pocket printing cost.'
        ],
        '_fourhd_left_sleeve_extra' => [
            'Left Sleeve Extra',
            'Additional left sleeve printing cost.'
        ],
        '_fourhd_right_sleeve_extra' => [
            'Right Sleeve Extra',
            'Additional right sleeve printing cost.'
        ],

        // Caps only
        '_fourhd_front_left_extra' => [
            'Front Left Print Extra',
            'Additional front-left printing cost.'
        ],
        '_fourhd_front_right_extra' => [
            'Front Right Print Extra',
            'Additional front-right printing cost.'
        ],
        '_fourhd_left_side_extra' => [
            'Left Side Print Extra',
            'Additional left-side printing cost.'
        ],
        '_fourhd_right_side_extra' => [
            'Right Side Print Extra',
            'Additional right-side printing cost.'
        ],
        '_fourhd_under_brim_extra' => [
            'Under Brim Print Extra',
            'Additional under-brim printing cost.'
        ],
        '_fourhd_brim_sticker_extra' => [
            'Premium Brim Sticker Extra',
            'Additional premium sticker cost.'
        ],
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

    $saved_types = get_post_meta($post->ID, '_fourhd_print_file_types', true);
    if (!is_array($saved_types) || empty($saved_types)) {
        $saved_types = ['pdf', 'jpg', 'png'];
    }
    $max_mb = get_post_meta($post->ID, '_fourhd_print_max_mb', true) ?: '10';

    echo '<div class="options_group fourhd-print-admin">';
    echo '<h4 class="fourhd-admin-heading">Print Artwork Settings</h4>';
    echo '<div class="fourhd-print-settings">';

    echo '<div class="fourhd-setting-row">';
    echo '<label class="fourhd-setting-label" for="_fourhd_print_max_mb">Maximum File Size</label>';
    printf('<input type="number" id="_fourhd_print_max_mb" name="_fourhd_print_max_mb" value="%s" min="1" max="100" step="1"> <span class="description">MB per artwork file</span>', esc_attr($max_mb));
    echo '</div>';

    echo '<div class="fourhd-setting-row">';
    echo '<span class="fourhd-setting-label">Accepted File Types</span>';
    echo '<div class="fourhd-file-types">';
    foreach (['pdf' => 'PDF', 'jpg' => 'JPG / JPEG', 'png' => 'PNG'] as $key => $label) {
        printf(
            '<label><input type="checkbox" name="_fourhd_print_file_types[]" value="%s" %s> <span>%s</span></label>',
            esc_attr($key),
            checked(in_array($key, $saved_types, true), true, false),
            esc_html($label)
        );
    }
    echo '</div></div>';

    echo '<div class="fourhd-upload-fields-help"><strong>Upload Fields</strong><br><span class="description">Create reusable artwork fields. Conditions are optional. Example: Back Design → attribute <code>printing</code> → value <code>front-back</code>.</span></div>';

    $fields = fourhd_get_print_fields($post->ID);
    echo '<div id="fourhd-print-fields">';
    foreach ($fields as $index => $field) {
        fourhd_render_admin_print_field($index, $field);
    }
    echo '</div>';
    echo '<input type="hidden" id="_fourhd_print_fields_json" name="_fourhd_print_fields_json" value="">';
    echo '<p class="fourhd-add-field-wrap"><button type="button" class="button" id="fourhd-add-print-field">+ Add Upload Field</button></p>';
    echo '</div></div>';

    ?>
    <script type="text/template" id="fourhd-print-field-template">
        <?php fourhd_render_admin_print_field('__INDEX__', [
            'key' => '', 'label' => '', 'required' => 'yes', 'condition_attr' => '', 'condition_value' => ''
        ]); ?>
    </script>
    <script>
    jQuery(function($){
        function toggleFourHDAdmin() {
            var mode = $('#_fourhd_upload_mode').val();

            $('.fourhd-customizer-admin').toggle(mode === 'customizer');
            $('.fourhd-print-admin').toggle(mode === 'print');
        }

        function toggleFourHDPricingFields() {
            var type = $('#_fourhd_customizer_type').val();

            $('#_fourhd_cap_print_layout')
            .closest('.form-field')
            .toggle(type === 'caps');

            var apparelOnly = [
                '_fourhd_pocket_extra',
                '_fourhd_left_sleeve_extra',
                '_fourhd_right_sleeve_extra'
            ];

            var capsOnly = [
                '_fourhd_front_left_extra',
                '_fourhd_front_right_extra',
                '_fourhd_left_side_extra',
                '_fourhd_right_side_extra',
                '_fourhd_under_brim_extra',
                '_fourhd_brim_sticker_extra'
            ];

            function setFieldState(id, enabled) {
                var field = $('#' + id);

                field.prop('disabled', !enabled);
                field.closest('.form-field').css({
                    opacity: enabled ? 1 : 0.4
                });
            }

            apparelOnly.forEach(function(id) {
                setFieldState(id, type === 'apparel');
            });

            var capLayout = $('#_fourhd_cap_print_layout').val();
            var multipleCaps = type === 'caps' && capLayout === 'multiple';

            capsOnly.forEach(function(id) {
                setFieldState(id, multipleCaps);
            });

            // Back printing is available for Apparel and Multiple Location Caps.
            setFieldState(
                '_fourhd_back_extra',
                type === 'apparel' || multipleCaps
            );

            // Front Only Caps do not need additional print pricing.
            setFieldState(
                '_fourhd_front_extra',
                type === 'apparel' || multipleCaps
            );
        }

        toggleFourHDAdmin();
        toggleFourHDPricingFields();

        $('#_fourhd_upload_mode').on('change', toggleFourHDAdmin);
        $('#_fourhd_customizer_type').on('change', toggleFourHDPricingFields);
        $('#_fourhd_cap_print_layout').on('change', toggleFourHDPricingFields);

        $('#fourhd-add-print-field').on('click', function(){
            var indexes = $('#fourhd-print-fields .fourhd-print-field-row').map(function(){
                return parseInt($(this).attr('data-index'), 10) || 0;
            }).get();
            var index = indexes.length ? Math.max.apply(null, indexes) + 1 : 0;
            var html = $('#fourhd-print-field-template').html().replace(/__INDEX__/g, index);
            $('#fourhd-print-fields').append(html);
        });

        $(document).on('click', '.fourhd-remove-print-field', function(){
            $(this).closest('.fourhd-print-field-row').remove();
            syncFourHDPrintFields();
        });

        function syncFourHDPrintFields(){
            var fields = [];
            $('#fourhd-print-fields .fourhd-print-field-row').each(function(){
                var row = $(this);
                fields.push({
                    label: row.find('input[name$="[label]"]').val() || '',
                    key: row.find('input[name$="[key]"]').val() || '',
                    required: row.find('input[name$="[required]"]').is(':checked') ? 'yes' : 'no',
                    condition_attr: row.find('input[name$="[condition_attr]"]').val() || '',
                    condition_value: row.find('input[name$="[condition_value]"]').val() || ''
                });
            });
            $('#_fourhd_print_fields_json').val(JSON.stringify(fields));
        }

        $(document).on('input change', '#fourhd-print-fields input', syncFourHDPrintFields);
        $('#post').on('submit', syncFourHDPrintFields);
        syncFourHDPrintFields();
    });
    </script>
    <?php
});

function fourhd_render_admin_print_field($index, $field) {
    $field = wp_parse_args($field, [
        'key' => '', 'label' => '', 'required' => 'yes', 'condition_attr' => '', 'condition_value' => ''
    ]);
    ?>
    <div class="fourhd-print-field-row" data-index="<?php echo esc_attr($index); ?>">
        <div class="fourhd-print-field-header">
            <strong>Artwork Field</strong>
            <button type="button" class="button-link-delete fourhd-remove-print-field">Remove</button>
        </div>

        <div class="fourhd-print-field-grid">
            <div class="fourhd-print-field-control">
                <label for="fourhd-field-label-<?php echo esc_attr($index); ?>">Label</label>
                <input id="fourhd-field-label-<?php echo esc_attr($index); ?>" type="text" name="fourhd_print_fields[<?php echo esc_attr($index); ?>][label]" value="<?php echo esc_attr($field['label']); ?>" placeholder="Front Design">
            </div>

            <div class="fourhd-print-field-control">
                <label for="fourhd-field-key-<?php echo esc_attr($index); ?>">Field Key</label>
                <input id="fourhd-field-key-<?php echo esc_attr($index); ?>" type="text" name="fourhd_print_fields[<?php echo esc_attr($index); ?>][key]" value="<?php echo esc_attr($field['key']); ?>" placeholder="front_design">
            </div>

            <div class="fourhd-print-field-required">
                <label><input type="checkbox" name="fourhd_print_fields[<?php echo esc_attr($index); ?>][required]" value="yes" <?php checked($field['required'], 'yes'); ?>> <span>Required when this field is active</span></label>
            </div>

            <div class="fourhd-condition-title">Condition (optional)</div>

            <div class="fourhd-print-field-control">
                <label for="fourhd-field-attr-<?php echo esc_attr($index); ?>">Attribute</label>
                <input id="fourhd-field-attr-<?php echo esc_attr($index); ?>" type="text" name="fourhd_print_fields[<?php echo esc_attr($index); ?>][condition_attr]" value="<?php echo esc_attr($field['condition_attr']); ?>" placeholder="printing">
            </div>

            <div class="fourhd-print-field-control">
                <label for="fourhd-field-value-<?php echo esc_attr($index); ?>">Value / Slug</label>
                <input id="fourhd-field-value-<?php echo esc_attr($index); ?>" type="text" name="fourhd_print_fields[<?php echo esc_attr($index); ?>][condition_value]" value="<?php echo esc_attr($field['condition_value']); ?>" placeholder="front-back">
            </div>
        </div>
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
    // Save Customizer Type.
$customizer_type = isset($_POST['_fourhd_customizer_type'])
    ? sanitize_key(wp_unslash($_POST['_fourhd_customizer_type']))
    : 'apparel';

if (!in_array($customizer_type, ['apparel', 'caps'], true)) {
    $customizer_type = 'apparel';
}

update_post_meta($product_id, '_fourhd_customizer_type', $customizer_type);

// Save Cap Print Layout.
$cap_print_layout = isset($_POST['_fourhd_cap_print_layout'])
    ? sanitize_key(wp_unslash($_POST['_fourhd_cap_print_layout']))
    : 'front_only';

if (!in_array($cap_print_layout, ['front_only', 'multiple'], true)) {
    $cap_print_layout = 'front_only';
}

update_post_meta(
    $product_id,
    '_fourhd_cap_print_layout',
    $cap_print_layout
);

// Save Customizer Heading.
$customizer_heading = isset($_POST['_fourhd_customizer_heading'])
    ? sanitize_text_field(wp_unslash($_POST['_fourhd_customizer_heading']))
    : '';

update_post_meta($product_id, '_fourhd_customizer_heading', $customizer_heading);

    // Keep legacy meta synchronized for backward compatibility.
    update_post_meta($product_id, '_fourhd_enable_upload', $mode === 'customizer' ? 'yes' : 'no');

    $customizer_price_fields = [
        // Apparel and Caps
        '_fourhd_front_extra',
        '_fourhd_back_extra',

        // Apparel
        '_fourhd_pocket_extra',
        '_fourhd_left_sleeve_extra',
        '_fourhd_right_sleeve_extra',

        // Caps
        '_fourhd_front_left_extra',
        '_fourhd_front_right_extra',
        '_fourhd_left_side_extra',
        '_fourhd_right_side_extra',
        '_fourhd_under_brim_extra',
        '_fourhd_brim_sticker_extra',
    ];

    foreach ($customizer_price_fields as $field) {
        if (!isset($_POST[$field])) {
            continue;
        }

        $value = wc_format_decimal(wp_unslash($_POST[$field]));
        $value = $value === '' ? '' : max(0, (float) $value);

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
    $submitted_fields = [];

    // Prefer the compact JSON payload. This is more reliable on products with
    // many variations because it uses a single POST variable.
    if (!empty($_POST['_fourhd_print_fields_json'])) {
        $decoded = json_decode(wp_unslash($_POST['_fourhd_print_fields_json']), true);
        if (is_array($decoded)) {
            $submitted_fields = $decoded;
        }
    } elseif (isset($_POST['fourhd_print_fields']) && is_array($_POST['fourhd_print_fields'])) {
        $submitted_fields = wp_unslash($_POST['fourhd_print_fields']);
    }

    foreach ($submitted_fields as $row) {
        if (!is_array($row)) {
            continue;
        }

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
            'required'        => !empty($row['required']) && $row['required'] === 'yes' ? 'yes' : 'no',
            'condition_attr'  => isset($row['condition_attr']) ? sanitize_title($row['condition_attr']) : '',
            // Keep the human-readable WooCommerce option value, e.g. "Front & Back".
            'condition_value' => isset($row['condition_value']) ? sanitize_text_field($row['condition_value']) : '',
        ];
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
   $customizer_type = get_post_meta(
        $product_id,
        '_fourhd_customizer_type',
        true
    ) ?: 'apparel';

    $customizer_heading = get_post_meta(
        $product_id,
        '_fourhd_customizer_heading',
        true
    );

    $cap_layout = get_post_meta(
    $product_id,
    '_fourhd_cap_print_layout',
    true
) ?: 'front_only';

    $is_front_only_cap = (
        $customizer_type === 'caps' &&
        $cap_layout !== 'multiple'
    );

    if ($customizer_type === 'caps') {

        if ($is_front_only_cap) {
            $locations = [
                'front' => [
                    'label' => 'Front',
                    'price' => 0,
                ],
            ];
        } else {
            $cap_locations = [
                'front'       => 'Front Center',
                'front_left'  => 'Front Left',
                'front_right' => 'Front Right',
                'back'        => 'Back',
                'left_side'   => 'Left Side',
                'right_side'  => 'Right Side',
                'under_brim'  => 'Under Brim',
            ];

            $price_meta = [
                'front'       => '_fourhd_front_extra',
                'front_left'  => '_fourhd_front_left_extra',
                'front_right' => '_fourhd_front_right_extra',
                'back'        => '_fourhd_back_extra',
                'left_side'   => '_fourhd_left_side_extra',
                'right_side'  => '_fourhd_right_side_extra',
                'under_brim'  => '_fourhd_under_brim_extra',
            ];

            $locations = [];

            foreach ($cap_locations as $key => $label) {
                $locations[$key] = [
                    'label' => $label,
                    'price' => (float) get_post_meta(
                        $product_id,
                        $price_meta[$key],
                        true
                    ),
                ];
            }
        }

    } else {

        $locations = [
            'front' => [
                'label' => 'Front',
                'price' => (float) get_post_meta($product_id, '_fourhd_front_extra', true),
            ],
            'back' => [
                'label' => 'Back',
                'price' => (float) get_post_meta($product_id, '_fourhd_back_extra', true),
            ],
            'pocket' => [
                'label' => 'Pocket',
                'price' => (float) get_post_meta($product_id, '_fourhd_pocket_extra', true),
            ],
            'left_sleeve' => [
                'label' => 'Left Sleeve',
                'price' => (float) get_post_meta($product_id, '_fourhd_left_sleeve_extra', true),
            ],
            'right_sleeve' => [
                'label' => 'Right Sleeve',
                'price' => (float) get_post_meta($product_id, '_fourhd_right_sleeve_extra', true),
            ],
        ];
    }
    ?>
    <div class="fourhd-product-customizer" data-base-price="<?php echo esc_attr($product->get_price()); ?>">
        <h3>
            <?php echo esc_html(
                $customizer_heading ?: (
                    $customizer_type === 'caps'
                        ? 'Customize Your Cap'
                        : 'Customize Your Apparel'
                )
            ); ?>
        </h3>
        <div class="fourhd-print-section">
            <h4>Included Print Location</h4>
            <p>
                <?php if ($is_front_only_cap) : ?>
                    Front printing is included with your cap.
                <?php else : ?>
                    Choose one print location included with your product.
                <?php endif; ?>
            </p>
            <?php if ($is_front_only_cap) : ?>
                <input type="hidden" name="fourhd_primary_location" value="front">
            <?php endif; ?>
            <div class="fourhd-location-options"
                <?php echo $is_front_only_cap ? 'hidden' : ''; ?>>

                <?php if (!$is_front_only_cap) : ?>
                    <?php foreach ($locations as $key => $location) : ?>

                        <label class="fourhd-location-option">
                            <input
                                type="radio"
                                name="fourhd_primary_location"
                                value="<?php echo esc_attr($key); ?>"
                                required
                            >
                            <span>
                                <?php echo esc_html($location['label']); ?>
                            </span>
                        </label>

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <?php if ($is_front_only_cap) : ?>
                <p class="fourhd-cap-note">
                    Looking for a different print placement or custom setup?
                    Contact us to discuss your options.
                </p>
            <?php endif; ?>
        </div>

        <div class="fourhd-print-section"
            <?php echo $is_front_only_cap ? 'hidden' : ''; ?>>
            <h4>Additional Print Locations</h4>
            <p>Add more print locations if needed.</p>
            <div class="fourhd-extra-locations">
                <?php if (!$is_front_only_cap) : ?>
                <?php foreach ($locations as $key => $location) : ?>
                    <label class="fourhd-extra-location" data-location="<?php echo esc_attr($key); ?>">
                        <input type="checkbox" name="fourhd_extra_locations[]" value="<?php echo esc_attr($key); ?>" data-price="<?php echo esc_attr($location['price']); ?>">
                        <span class="fourhd-extra-location__name"><?php echo esc_html($location['label']); ?></span>
                        <?php if ($location['price'] > 0) : ?>
                            <span class="fourhd-extra-location__price">+<?php echo wp_kses_post(wc_price($location['price'])); ?></span>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($customizer_type === 'caps' && !$is_front_only_cap) : ?>

        <?php
        $sticker_price = (float) get_post_meta(
            $product_id,
            '_fourhd_brim_sticker_extra',
            true
        );
        ?>

        <div class="fourhd-print-section fourhd-sticker-section">

            <h4>Premium Brim Sticker</h4>

            <p>Add a premium decorative sticker to your cap's brim.</p>

            <label class="fourhd-extra-location">

                <input
                    type="checkbox"
                    name="fourhd_brim_sticker"
                    value="yes"
                    data-price="<?php echo esc_attr($sticker_price); ?>"
                >

                <span class="fourhd-extra-location__name">
                    Add Premium Brim Sticker
                </span>

                <span class="fourhd-extra-location__price">
                    +<?php echo wp_kses_post(wc_price($sticker_price)); ?>
                </span>

            </label>
            <div class="fourhd-sticker-upload" hidden>

                <label for="fourhd_brim_sticker_design">
                    Upload Your Sticker Design
                </label>

                <input
                    type="file"
                    id="fourhd_brim_sticker_design"
                    name="fourhd_brim_sticker_design"
                    accept="image/png,image/jpeg"
                >

                <div class="fourhd-design-preview" hidden>
                    <img src="" alt="Sticker Design Preview">
                    <span class="fourhd-design-preview__filename"></span>
                </div>

            </div>

        </div>

    <?php endif; ?>

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
            $condition_value = sanitize_text_field($field['condition_value'] ?? '');
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

        function normalizeValue(value){
            return String(value || '')
                .trim()
                .toLowerCase()
                .replace(/&/g, 'and')
                .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        }

        function refreshFields(){
            wrap.querySelectorAll('.fourhd-artwork-field').forEach(function(row){
                var attr = row.dataset.conditionAttr || '';
                var wanted = row.dataset.conditionValue || '';
                var selected = selectedValue(attr);
                var show = !attr || !wanted || normalizeValue(selected) === normalizeValue(wanted);
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
        return fourhd_validate_customizer($passed, $product_id);
    }

    if ($mode === 'print') {
        return fourhd_validate_print_upload($passed, $product_id, $variations);
    }

    return $passed;
}
    function fourhd_validate_customizer($passed, $product_id) {

        $type = get_post_meta(
            $product_id,
            '_fourhd_customizer_type',
            true
        ) ?: 'apparel';

        $cap_layout = get_post_meta(
            $product_id,
            '_fourhd_cap_print_layout',
            true
        ) ?: 'front_only';

        // Allowed print locations
        if ($type === 'caps') {

            $allowed = $cap_layout === 'multiple'
                ? [
                    'front',
                    'front_left',
                    'front_right',
                    'back',
                    'left_side',
                    'right_side',
                    'under_brim'
                ]
                : ['front'];

        } else {

            $allowed = [
                'front',
                'back',
                'pocket',
                'left_sleeve',
                'right_sleeve'
            ];
        }

        // Primary print location
        $primary = isset($_POST['fourhd_primary_location'])
            ? sanitize_key(wp_unslash($_POST['fourhd_primary_location']))
            : '';

        if (!in_array($primary, $allowed, true)) {
            wc_add_notice('Please select a valid print location.', 'error');
            return false;
        }

        // Additional print locations
        $extras = [];

        if (!empty($_POST['fourhd_extra_locations'])) {

            if (!is_array($_POST['fourhd_extra_locations'])) {
                wc_add_notice('Invalid additional print locations.', 'error');
                return false;
            }

            foreach (wp_unslash($_POST['fourhd_extra_locations']) as $location) {

                $location = sanitize_key($location);

                if (
                    !in_array($location, $allowed, true) ||
                    $location === $primary
                ) {
                    wc_add_notice('Invalid additional print location.', 'error');
                    return false;
                }

                $extras[] = $location;
            }
        }

        // Front-only caps cannot have extra locations
        if (
            $type === 'caps' &&
            $cap_layout !== 'multiple' &&
            !empty($extras)
        ) {
            wc_add_notice('This cap only supports front printing.', 'error');
            return false;
        }

        $active = array_merge([$primary], $extras);

        // Prevent duplicate locations
        if (count($active) !== count(array_unique($active))) {
            wc_add_notice('Duplicate print locations are not allowed.', 'error');
            return false;
        }

        // Flat Bill Cap: Front Center incompatibility
        if ($type === 'caps' && $cap_layout === 'multiple') {

            if (
                in_array('front', $active, true) &&
                (
                    in_array('front_left', $active, true) ||
                    in_array('front_right', $active, true)
                )
            ) {
                wc_add_notice(
                    'Front Center cannot be combined with Front Left or Front Right.',
                    'error'
                );
                return false;
            }
        }

        // Apparel: Front and Pocket incompatibility
        if (
            $type !== 'caps' &&
            in_array('front', $active, true) &&
            in_array('pocket', $active, true)
        ) {
            wc_add_notice(
                'Front and Pocket printing cannot be selected together.',
                'error'
            );
            return false;
        }

        // Validate artwork for selected print locations
        foreach ($active as $location) {

            $name = 'fourhd_design_' . $location;

            if (
                empty($_FILES[$name]['name']) ||
                !isset($_FILES[$name]['error']) ||
                $_FILES[$name]['error'] !== UPLOAD_ERR_OK
            ) {
                wc_add_notice(
                    sprintf(
                        'Please upload your %s design.',
                        ucwords(str_replace('_', ' ', $location))
                    ),
                    'error'
                );
                return false;
            }
        }

        // Premium Brim Sticker validation
        $sticker_selected = (
            $type === 'caps' &&
            $cap_layout === 'multiple' &&
            isset($_POST['fourhd_brim_sticker']) &&
            wp_unslash($_POST['fourhd_brim_sticker']) === 'yes'
        );

        if ($sticker_selected) {

            $file = $_FILES['fourhd_brim_sticker_design'] ?? null;

            if (
                !$file ||
                empty($file['name']) ||
                !isset($file['error']) ||
                $file['error'] !== UPLOAD_ERR_OK
            ) {
                wc_add_notice(
                    'Please upload your Premium Brim Sticker design.',
                    'error'
                );
                return false;
            }

            // Validate actual file type
            $file_type = wp_check_filetype_and_ext(
                $file['tmp_name'],
                $file['name'],
                [
                    'jpg|jpeg' => 'image/jpeg',
                    'png'      => 'image/png'
                ]
            );

            if (
                !in_array(
                    $file_type['type'],
                    ['image/jpeg', 'image/png'],
                    true
                ) ||
                empty($file_type['ext'])
            ) {
                wc_add_notice(
                    'Premium Brim Sticker designs must be PNG or JPG images.',
                    'error'
                );
                return false;
            }

            // Maximum file size: 10 MB
            if ($file['size'] > 10 * 1024 * 1024) {
                wc_add_notice(
                    'Premium Brim Sticker design must be 10 MB or smaller.',
                    'error'
                );
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

    $customizer_type = get_post_meta(
        $product_id,
        '_fourhd_customizer_type',
        true
    );

    $cap_layout = get_post_meta(
        $product_id,
        '_fourhd_cap_print_layout',
        true
    ) ?: 'front_only';

    if ($customizer_type === 'caps') {
        $allowed = $cap_layout === 'multiple'
            ? [
                'front', 'front_left', 'front_right',
                'back', 'left_side', 'right_side', 'under_brim'
            ]
            : ['front'];
    } else {
        $allowed = [
            'front', 'back', 'pocket',
            'left_sleeve', 'right_sleeve'
        ];
    }

    // Primary location
    $primary = isset($_POST['fourhd_primary_location'])
        ? sanitize_key(wp_unslash($_POST['fourhd_primary_location']))
        : '';

    if (in_array($primary, $allowed, true)) {
        $cart_item_data['fourhd_primary_location'] = $primary;
    }

    // Additional locations
    $extras = [];

    if (
        !empty($_POST['fourhd_extra_locations']) &&
        is_array($_POST['fourhd_extra_locations'])
    ) {
        $submitted = wp_unslash($_POST['fourhd_extra_locations']);

        foreach ($submitted as $location) {
            $location = sanitize_key($location);

            if (
                in_array($location, $allowed, true) &&
                $location !== $primary
            ) {
                $extras[] = $location;
            }
        }
    }

    $extras = array_values(array_unique($extras));

    if ($extras) {
        $cart_item_data['fourhd_extra_locations'] = $extras;
    }

    $active = array_values(array_unique(
        array_merge($primary ? [$primary] : [], $extras)
    ));

    // Premium Brim Sticker
    $sticker_selected = (
        $customizer_type === 'caps' &&
        $cap_layout === 'multiple' &&
        isset($_POST['fourhd_brim_sticker']) &&
        wp_unslash($_POST['fourhd_brim_sticker']) === 'yes'
    );

    // Build one upload queue for all required designs
    $queue = [];

    foreach ($active as $location) {
        $queue[] = [
            'field'    => 'fourhd_design_' . $location,
            'location' => $location,
            'label'    => ucwords(str_replace('_', ' ', $location)),
        ];
    }

    if ($sticker_selected) {
        $queue[] = [
            'field'    => 'fourhd_brim_sticker_design',
            'location' => 'brim_sticker',
            'label'    => 'Premium Brim Sticker',
        ];
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';

    $uploaded_paths = [];
    $saved_designs = [];
    $upload_failed = false;

    $mimes = [
        'jpg|jpeg' => 'image/jpeg',
        'png'      => 'image/png',
    ];

    foreach ($queue as $entry) {

        $field = $entry['field'];
        $file = $_FILES[$field] ?? null;

        // Reject missing or incomplete uploads
        if (
            !is_array($file) ||
            empty($file['name']) ||
            !isset($file['error']) ||
            $file['error'] !== UPLOAD_ERR_OK ||
            empty($file['tmp_name']) ||
            !is_uploaded_file($file['tmp_name'])
        ) {
            $upload_failed = true;

            wc_add_notice(
                sprintf(
                    'Unable to receive your %s design. Please try again.',
                    $entry['label']
                ),
                'error'
            );

            break;
        }

        // Validate size and actual file type
        $check = wp_check_filetype_and_ext(
            $file['tmp_name'],
            $file['name'],
            $mimes
        );

        if (
            empty($check['ext']) ||
            !in_array(
                $check['type'] ?? '',
                ['image/jpeg', 'image/png'],
                true
            ) ||
            (int) $file['size'] > 10 * 1024 * 1024
        ) {
            $upload_failed = true;

            wc_add_notice(
                sprintf(
                    '%s must be a valid PNG or JPG image, 10 MB or smaller.',
                    $entry['label']
                ),
                'error'
            );

            break;
        }

        // Save file
        $upload = wp_handle_upload($file, [
            'test_form' => false,
            'mimes'     => $mimes,
        ]);

        if (
            !empty($upload['error']) ||
            empty($upload['url']) ||
            empty($upload['file'])
        ) {
            $upload_failed = true;

            wc_add_notice(
                sprintf(
                    'Unable to save your %s design. Please try again.',
                    $entry['label']
                ),
                'error'
            );

            // Handle a partially created file, if returned
            if (!empty($upload['file']) && is_file($upload['file'])) {
                @unlink($upload['file']);
            }

            break;
        }

        $uploaded_paths[] = $upload['file'];

        $saved_designs[$entry['location']] = [
            'url'  => esc_url_raw($upload['url']),
            'name' => sanitize_file_name($file['name']),
        ];
    }

    // Roll back every successful upload if any file failed
    if ($upload_failed) {

        foreach ($uploaded_paths as $path) {
            if (is_file($path)) {
                wp_delete_file($path);
            }
        }

        throw new Exception('');

        return $cart_item_data;
    }

    // Commit all successfully uploaded designs
    foreach ($active as $location) {
        if (isset($saved_designs[$location])) {
            $cart_item_data['fourhd_designs'][$location] =
                $saved_designs[$location];
        }
    }

    if ($sticker_selected) {
        $cart_item_data['fourhd_brim_sticker'] = 'yes';
        $cart_item_data['fourhd_brim_sticker_design'] =
            $saved_designs['brim_sticker'];
    }

    // Existing mockup option
    if (
        isset($_POST['fourhd_mockup']) &&
        wp_unslash($_POST['fourhd_mockup']) === 'yes'
    ) {
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
        'front'        => 'Front Center',
        'front_left'   => 'Front Left',
        'front_right'  => 'Front Right',
        'back'         => 'Back',
        'left_side'    => 'Left Side',
        'right_side'   => 'Right Side',
        'under_brim'   => 'Under Brim',
        'pocket'       => 'Pocket',
        'left_sleeve'  => 'Left Sleeve',
        'right_sleeve' => 'Right Sleeve',
    ];

    if (!empty($cart_item['fourhd_primary_location'])) {
        $location = $cart_item['fourhd_primary_location'];

        $item_data[] = [
            'name'  => 'Included Print',
            'value' => $labels[$location] ?? $location,
        ];
    }

    if (!empty($cart_item['fourhd_extra_locations'])) {

        $extra_labels = [];

        foreach ($cart_item['fourhd_extra_locations'] as $location) {
            $extra_labels[] = $labels[$location] ?? $location;
        }

        $item_data[] = [
            'name'  => 'Additional Prints',
            'value' => implode(', ', $extra_labels),
        ];
    }

    if (!empty($cart_item['fourhd_designs'])) {

        foreach ($cart_item['fourhd_designs'] as $location => $design) {

            $item_data[] = [
                'name'  => ($labels[$location] ?? $location) . ' Design',
                'value' => 'File received',
            ];
        }
    }

    // Premium Brim Sticker
    if (
        !empty($cart_item['fourhd_brim_sticker']) &&
        $cart_item['fourhd_brim_sticker'] === 'yes'
    ) {

        $item_data[] = [
            'name'  => 'Premium Brim Sticker',
            'value' => 'Yes',
        ];

        if (!empty($cart_item['fourhd_brim_sticker_design']['url'])) {

            $item_data[] = [
                'name'  => 'Brim Sticker Design',
                'value' => sanitize_file_name(
                    $cart_item['fourhd_brim_sticker_design']['name'] ?? 'File received'
                ),
            ];
        }
    }

    // Existing print artwork
    if (!empty($cart_item['fourhd_print_artwork'])) {

        foreach ($cart_item['fourhd_print_artwork'] as $artwork) {

            $item_data[] = [
                'name'  => $artwork['label'],
                'value' => $artwork['name'] ?: 'File received',
            ];
        }
    }

    if (
        !empty($cart_item['fourhd_mockup']) &&
        $cart_item['fourhd_mockup'] === 'yes'
    ) {
        $item_data[] = [
            'name'  => 'Mockup',
            'value' => 'Requested by email',
        ];
    }

    return $item_data;
}

/* =========================================================
   6. SAVE TO ORDER
========================================================= */

add_action('woocommerce_checkout_create_order_line_item', 'fourhd_save_design_to_order', 10, 4);

function fourhd_save_design_to_order($item, $cart_item_key, $values, $order) {

    $labels = [
        'front'        => 'Front Center',
        'front_left'   => 'Front Left',
        'front_right'  => 'Front Right',
        'back'         => 'Back',
        'left_side'    => 'Left Side',
        'right_side'   => 'Right Side',
        'under_brim'   => 'Under Brim',
        'pocket'       => 'Pocket',
        'left_sleeve'  => 'Left Sleeve',
        'right_sleeve' => 'Right Sleeve',
    ];

    if (!empty($values['fourhd_primary_location'])) {

        $location = $values['fourhd_primary_location'];

        $item->add_meta_data(
            'Included Print',
            $labels[$location] ?? $location
        );
    }

    if (!empty($values['fourhd_extra_locations'])) {

        $extras = [];

        foreach ($values['fourhd_extra_locations'] as $location) {
            $extras[] = $labels[$location] ?? $location;
        }

        $item->add_meta_data(
            'Additional Prints',
            implode(', ', $extras)
        );
    }

    // Print location artwork
    if (!empty($values['fourhd_designs'])) {

        foreach ($values['fourhd_designs'] as $location => $design) {

            if (empty($design['url'])) {
                continue;
            }

            $item->add_meta_data(
                ($labels[$location] ?? $location) . ' Design',
                esc_url_raw($design['url'])
            );
        }
    }

    // Premium Brim Sticker
    if (
        !empty($values['fourhd_brim_sticker']) &&
        $values['fourhd_brim_sticker'] === 'yes'
    ) {

        $item->add_meta_data(
            'Premium Brim Sticker',
            'Yes'
        );

        if (!empty($values['fourhd_brim_sticker_design']['url'])) {

            $sticker = $values['fourhd_brim_sticker_design'];

            $item->add_meta_data(
                'Brim Sticker Design',
                esc_url_raw($sticker['url'])
            );

            $item->add_meta_data(
                '_fourhd_brim_sticker_filename',
                sanitize_file_name($sticker['name'] ?? '')
            );
        }
    }

    // Existing print artwork
    if (!empty($values['fourhd_print_artwork'])) {

        foreach ($values['fourhd_print_artwork'] as $artwork) {

            $item->add_meta_data(
                $artwork['label'],
                esc_url_raw($artwork['url'])
            );

            $item->add_meta_data(
                '_' . sanitize_key($artwork['label']) . '_filename',
                sanitize_file_name($artwork['name'])
            );
        }
    }

    if (
        !empty($values['fourhd_mockup']) &&
        $values['fourhd_mockup'] === 'yes'
    ) {

        $item->add_meta_data(
            'Mockup',
            'Requested by email'
        );
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
            // Apparel and Caps
            'front'       => '_fourhd_front_extra',
            'back'        => '_fourhd_back_extra',

            // Apparel
            'pocket'      => '_fourhd_pocket_extra',
            'left_sleeve' => '_fourhd_left_sleeve_extra',
            'right_sleeve'=> '_fourhd_right_sleeve_extra',

            // Caps
            'front_left'  => '_fourhd_front_left_extra',
            'front_right' => '_fourhd_front_right_extra',
            'left_side'   => '_fourhd_left_side_extra',
            'right_side'  => '_fourhd_right_side_extra',
            'under_brim'  => '_fourhd_under_brim_extra',
        ];

        // Additional print locations
        if (!empty($cart_item['fourhd_extra_locations'])) {

            foreach ($cart_item['fourhd_extra_locations'] as $location) {

                if ($location === $cart_item['fourhd_primary_location']) {
                    continue;
                }

                if (isset($price_fields[$location])) {

                    $extra_price += (float) get_post_meta(
                        $product_id,
                        $price_fields[$location],
                        true
                    );
                }
            }
        }

        // Premium Brim Sticker
        $customizer_type = get_post_meta(
            $product_id,
            '_fourhd_customizer_type',
            true
        );

        $cap_layout = get_post_meta(
            $product_id,
            '_fourhd_cap_print_layout',
            true
        );

        if (
            $customizer_type === 'caps' &&
            $cap_layout === 'multiple' &&
            !empty($cart_item['fourhd_brim_sticker']) &&
            $cart_item['fourhd_brim_sticker'] === 'yes'
        ) {

            $sticker_price = (float) get_post_meta(
                $product_id,
                '_fourhd_brim_sticker_extra',
                true
            );

            $extra_price += $sticker_price;
        }

        // Final unit price
        $cart_item['data']->set_price($base_price + $extra_price);

        $cart_item['data']->set_price($base_price + $extra_price);
    }
}