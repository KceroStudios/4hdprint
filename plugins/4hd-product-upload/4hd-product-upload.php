<?php
/*
Plugin Name: 4HD Product Image Upload
Description: Permite subir diseños personalizados en productos WooCommerce.
Version: 1.0
Author: 4HD PRINT
*/

add_action(
'woocommerce_before_add_to_cart_button',
'fourhd_upload_field'
);

function fourhd_upload_field(){

echo '
<p>
<label>
Sube tu diseño:
</label>

<input 
type="file" 
name="fourhd_design"
accept="image/png,image/jpeg">

</p>';

}
add_filter(
    'woocommerce_add_cart_item_data',
    'fourhd_save_upload',
    10,
    2
);


function fourhd_save_upload($cart_item_data, $product_id) {

    if (isset($_FILES['fourhd_design']) && !empty($_FILES['fourhd_design']['name'])) {

        require_once(ABSPATH . 'wp-admin/includes/file.php');

        $upload = wp_handle_upload(
            $_FILES['fourhd_design'],
            array(
                'test_form' => false
            )
        );

        if (isset($upload['url'])) {

            $cart_item_data['fourhd_design'] = $upload['url'];

        }
    }

    return $cart_item_data;

}

add_filter(
    'woocommerce_get_item_data',
    'fourhd_show_upload',
    10,
    2
);


function fourhd_show_upload($item_data, $cart_item) {


    if(isset($cart_item['fourhd_design'])) {

        $item_data[] = array(
            'name' => 'Diseño',
            'value' => 'Archivo recibido'
        );

    }


    return $item_data;

}

add_action(
    'woocommerce_checkout_create_order_line_item',
    'fourhd_save_design_to_order',
    10,
    4
);


function fourhd_save_design_to_order(
    $item,
    $cart_item_key,
    $values,
    $order
){

    if(isset($values['fourhd_design'])){

        $item->add_meta_data(
            'Diseño del cliente',
            $values['fourhd_design']
        );

    }

}