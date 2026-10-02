<?php
/**
 * Single Product Template
 *
 * @package 4hdprint
 */

    defined('ABSPATH') || exit;

    get_header('shop');

    do_action('woocommerce_before_main_content');
?>

<?php while (have_posts()) : ?>

    <?php the_post(); ?>

    <?php
    global $product;

    if ( ! $product || ! $product->is_visible() ) {
        continue;
    }
    ?>

    <?php do_action('woocommerce_before_single_product'); ?>


    <div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

        <!-- =========================================
             PRODUCT MAIN
        ========================================== -->

        <section class="single-product-main">


            <!-- PRODUCT GALLERY -->

            <div class="single-product-gallery">

                <?php
                do_action('woocommerce_before_single_product_summary');
                ?>

            </div>


            <!-- PRODUCT INFORMATION -->

            <div class="single-product-summary">

                <?php
                do_action('woocommerce_single_product_summary');
                ?>

            </div>


        </section>


        <!-- =========================================
             PRODUCT DESCRIPTION
        ========================================== -->

        <section class="single-product-description">

            <?php
            do_action('woocommerce_after_single_product_summary');
            ?>

        </section>


    </div>


    <?php do_action('woocommerce_after_single_product'); ?>

<?php endwhile; ?>


<?php
do_action('woocommerce_after_main_content');

get_footer('shop');