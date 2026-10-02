<?php
/**
 * WooCommerce Product Category
 *
 * 4HD PRINT
 */

defined('ABSPATH') || exit;

get_header();

$category = get_queried_object();

?>

<main id="primary" class="site-main product-category-page">

    <!-- =========================================
         CATEGORY HEADER
    ========================================== -->
    <section class="product-category-hero">

        <div class="product-category-hero__content">

            <p class="product-category-hero__label">
                Shop | <?php echo esc_html($category->name); ?> 
            </p>

        </div>

    </section>


    <!-- =========================================
         CATEGORY CONTENT
    ========================================== -->

    <!-- =========================================
     CATEGORY CONTENT
========================================== -->

<section class="product-category-content">

    <div class="product-category-layout">


        <!-- =====================================
             SIDEBAR — CATEGORIES
        ====================================== -->

        <!-- =====================================
     SIDEBAR — CATEGORIES
====================================== -->

<aside class="product-category-sidebar">

    <h2>Categories</h2>

    <?php

   $parent_categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);


/*
 * Keep regular categories alphabetical,
 * but place Graphic Design and Web Solutions
 * at the end.
 */
if (! empty($parent_categories) && ! is_wp_error($parent_categories)) {

    $last_categories = [
        'graphic-design' => 1,
        'web-solutions'  => 2,
    ];

    usort(
        $parent_categories,
        function ($a, $b) use ($last_categories) {

            $a_special = isset($last_categories[$a->slug]);
            $b_special = isset($last_categories[$b->slug]);

            // Both are regular categories.
            // Keep alphabetical order.
            if (! $a_special && ! $b_special) {
                return strcasecmp($a->name, $b->name);
            }

            // A is special: move it down.
            if ($a_special && ! $b_special) {
                return 1;
            }

            // B is special: move it down.
            if (! $a_special && $b_special) {
                return -1;
            }

            // Both are special.
            // Graphic Design first, Web Solutions second.
            return $last_categories[$a->slug]
                <=> $last_categories[$b->slug];
        }
    );
}
    ?>

    <?php if (! empty($parent_categories) && ! is_wp_error($parent_categories)) : ?>

        <ul class="product-category-menu">

            <?php foreach ($parent_categories as $parent) : ?>

                <?php

                /*
                 * Check if the current category is:
                 * - the parent itself
                 * - one of its children
                 */

                $is_parent_active =
                    ($category->term_id === $parent->term_id);

                $children = get_terms([
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                    'parent'     => $parent->term_id,
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                ]);

                $has_active_child = false;

                if (! empty($children) && ! is_wp_error($children)) {

                    foreach ($children as $child) {

                        if ($category->term_id === $child->term_id) {

                            $has_active_child = true;

                            break;

                        }

                    }

                }

                $parent_is_open =
                    $is_parent_active || $has_active_child;

                ?>

                <li
                    class="
                        product-category-menu__parent
                        <?php echo $parent_is_open ? 'is-open' : ''; ?>
                        <?php echo $is_parent_active ? 'is-active' : ''; ?>
                    "
                >

                    <div class="product-category-menu__parent-link">

                        <a
                            href="<?php echo esc_url(get_term_link($parent)); ?>"
                        >
                            <?php echo esc_html($parent->name); ?>
                        </a>


                        <?php if (! empty($children) && ! is_wp_error($children)) : ?>

                            <button
                                type="button"
                                class="product-category-menu__toggle"
                                aria-expanded="<?php echo $parent_is_open ? 'true' : 'false'; ?>"
                                aria-label="Toggle <?php echo esc_attr($parent->name); ?> categories"
                            >
                                <span aria-hidden="true">›</span>
                            </button>

                        <?php endif; ?>

                    </div>


                    <?php if (! empty($children) && ! is_wp_error($children)) : ?>

                        <ul class="product-category-menu__children">

                            <?php foreach ($children as $child) : ?>

                                <?php

                                $is_child_active =
                                    ($category->term_id === $child->term_id);

                                ?>

                                <li
                                    class="<?php echo $is_child_active ? 'is-active' : ''; ?>"
                                >

                                    <a
                                        href="<?php echo esc_url(get_term_link($child)); ?>"
                                    >
                                        <?php echo esc_html($child->name); ?>
                                    </a>

                                </li>

                            <?php endforeach; ?>

                        </ul>

                    <?php endif; ?>

                </li>

            <?php endforeach; ?>

        </ul>

    <?php endif; ?>

</aside>


        <!-- =====================================
             PRODUCTS
        ====================================== -->

       <div class="product-category-products">

    <?php

    /*
     * Get direct child categories
     * of the current category.
     */
    $subcategories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => $category->term_id,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);

    ?>


    <?php
    /*
     * If the current category has subcategories,
     * show the subcategories instead of products.
     */
    if (
        ! empty($subcategories) &&
        ! is_wp_error($subcategories)
    ) :
    ?>

        <div class="product-subcategories">

            <?php foreach ($subcategories as $subcategory) : ?>

                <?php

                $thumbnail_id = get_term_meta(
                    $subcategory->term_id,
                    'thumbnail_id',
                    true
                );

                $image_url = $thumbnail_id
                    ? wp_get_attachment_image_url(
                        $thumbnail_id,
                        'woocommerce_thumbnail'
                    )
                    : wc_placeholder_img_src(
                        'woocommerce_thumbnail'
                    );

                ?>

                <a
                    href="<?php echo esc_url(
                        get_term_link($subcategory)
                    ); ?>"
                    class="product-subcategory-card"
                >

                    <div class="product-subcategory-card__image">

                        <img
                            src="<?php echo esc_url($image_url); ?>"
                            alt="<?php echo esc_attr(
                                $subcategory->name
                            ); ?>"
                        >

                    </div>


                    <h2 class="product-subcategory-card__title">

                        <?php echo esc_html(
                            $subcategory->name
                        ); ?>

                    </h2>

                </a>

            <?php endforeach; ?>

        </div>


    <?php else : ?>


        <?php
        /*
         * No subcategories.
         * Show the products normally.
         */
        if (woocommerce_product_loop()) :
        ?>

            <?php woocommerce_product_loop_start(); ?>


            <?php while (have_posts()) : ?>

                <?php the_post(); ?>

                <?php
                wc_get_template_part(
                    'content',
                    'product'
                );
                ?>

            <?php endwhile; ?>


            <?php woocommerce_product_loop_end(); ?>


            <?php
            do_action(
                'woocommerce_after_shop_loop'
            );
            ?>


        <?php else : ?>


            <?php
            do_action(
                'woocommerce_no_products_found'
            );
            ?>


        <?php endif; ?>


    <?php endif; ?>

</div>


    </div>

</section>

</main>


<?php

get_footer();