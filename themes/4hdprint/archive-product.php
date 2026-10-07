<?php
/**
 * WooCommerce Shop Archive
 *
 * 4HD PRINT
 */

defined('ABSPATH') || exit;

get_header();

?>

<main id="primary" class="site-main shop-page">

    <!-- =========================================
         SHOP HEADER
    ========================================== -->

    <!-- =========================================
         FEATURED CATEGORIES
    ========================================== -->
    <?php

        $custom_products = get_term_by(
            'slug',
            'promotional-products',
            'product_cat'
        );

        $print_products = get_term_by(
            'slug',
            'print-services',
            'product_cat'
        );

        $graphic_design = get_term_by(
            'slug',
            'graphic-design',
            'product_cat'
        );

        $web_solutions = get_term_by(
            'slug',
            'web-solutions',
            'product_cat'
        );

    ?>

    <section class="shop-featured-categories">

        <div class="shop-section-header">

            <h2>What are you looking for?</h2>

            <p>
                Discover the products and services
                we offer for your business and personal projects.
            </p>

        </div>


        <div class="shop-featured-categories__grid">


            <!-- Promotional Products -->

            <a
                href="<?php echo esc_url(get_term_link($custom_products)); ?>"
                class="shop-category-card shop-category-card--custom"
            >

                <div class="shop-category-card__content">

                    <h3>Promotional Products</h3>

                    <p>
                        Personalized products made
                        around your ideas.
                    </p>

                    <span>
                        Explore →
                    </span>

                </div>

            </a>


            <!-- Print Products -->

           <a
                href="<?php echo esc_url(get_term_link($print_products)); ?>"
                class="shop-category-card shop-category-card--print"
            >

                <div class="shop-category-card__content">

                    <h3>Print Services</h3>

                    <p>
                        Professional printing for
                        business and personal projects.
                    </p>

                    <span>
                        Explore →
                    </span>

                </div>

            </a>


            <!-- Graphic Design -->

            <a
                href="<?php echo esc_url(get_term_link($graphic_design)); ?>"
                class="shop-category-card shop-category-card--design"
            >

                <div class="shop-category-card__content">

                    <h3>Graphic Design</h3>

                    <p>
                        Creative solutions to bring
                        your ideas to life.
                    </p>

                    <span>
                        Explore →
                    </span>

                </div>

            </a>


            <!-- Web Solutions -->

           <a
                href="<?php echo esc_url(get_term_link($web_solutions)); ?>"
                class="shop-category-card shop-category-card--web"
            >

                <div class="shop-category-card__content">

                    <h3>Web Solutions</h3>

                    <p>
                        Modern websites and online stores
                        for your business.
                    </p>

                    <span>
                        Explore →
                    </span>

                </div>

            </a>


        </div>

    </section>

    <!-- =========================================
     ALL CATEGORIES
    ========================================== -->

    <section class="shop-all-categories">

    <div class="shop-section-header">

        <h2>Explore All Categories</h2>

        <p>
            Browse our complete selection of products.
        </p>

    </div>


<?php

    /*
    * Categories already displayed in the
    * Featured Categories section.
    */
    $featured_category_slugs = [
        'promotional-products',
        'print-services',
        'graphic-design',
        'web-solutions',
    ];


    /*
    * Get all product categories.
    */
    $product_categories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
    ]);


    /*
    * Build the secondary category collection.
    */
    $other_categories = [];

    if ( ! empty($product_categories) && ! is_wp_error($product_categories) ) {

        foreach ($product_categories as $category) {

            /*
            * Ignore the WooCommerce default category.
            */
            if ($category->slug === 'uncategorized') {
                continue;
            }

            /*
            * Skip categories already displayed
            * in the Featured Categories section.
            */
            if (in_array($category->slug, $featured_category_slugs, true)) {
                continue;
            }

            $other_categories[] = $category;
        }
    }

?>

<!-- =========================================
     OTHER CATEGORIES
========================================== -->

<?php if ( ! empty($other_categories) ) : ?>

   


    <div class="shop-all-categories__grid shop-all-categories__grid--secondary">

        <?php foreach ($other_categories as $category) : ?>

            <?php
            $category_link = get_term_link($category);
            ?>

            <a
    href="<?php echo esc_url($category_link); ?>"
    class="shop-all-category"
>

    <?php

    $thumbnail_id = get_term_meta(
        $category->term_id,
        'thumbnail_id',
        true
    );

    if ( $thumbnail_id ) :

        echo wp_get_attachment_image(
            absint($thumbnail_id),
            'medium',
            false,
            [
                'class' => 'shop-all-category__image',
                'alt'   => esc_attr($category->name),
            ]
        );

    endif;

    ?>


    <div class="shop-all-category__content">

        <h3>
            <?php echo esc_html($category->name); ?>
        </h3>

        <span>

            <?php echo esc_html($category->count); ?>

            <?php
            echo $category->count === 1
                ? ' Product'
                : ' Products';
            ?>

        </span>

    </div>

</a>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

</section>

<!-- =========================================
     FEATURED PRODUCTS
========================================== -->

<section class="shop-featured-products">

    <div class="shop-section-header">

        <h2>Featured Products</h2>

        <p>
            Discover some of our most popular products.
        </p>

    </div>


    <?php
    $featured_products = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'post_status'    => 'publish',

        'tax_query' => [
            [
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ],
        ],
    ]);
    ?>


    <?php if ($featured_products->have_posts()) : ?>

        <div class="shop-featured-products__grid">

            <?php while ($featured_products->have_posts()) : ?>

                <?php $featured_products->the_post(); ?>

                <?php $product = wc_get_product(get_the_ID()); ?>


                <article class="shop-product-card">

                    <a
                        href="<?php the_permalink(); ?>"
                        class="shop-product-card__image"
                    >

                        <?php
                        echo $product->get_image('woocommerce_thumbnail');
                        ?>

                    </a>


                    <div class="shop-product-card__content">

                        <h3>

                            <a href="<?php the_permalink(); ?>">

                                <?php the_title(); ?>

                            </a>

                        </h3>


                        <div class="shop-product-card__price">

                            <?php echo $product->get_price_html(); ?>

                        </div>


                        <a
                            href="<?php the_permalink(); ?>"
                            class="shop-product-card__button"
                        >
                            View Product
                        </a>

                    </div>

                </article>


            <?php endwhile; ?>

        </div>


       <div class="shop-featured-products__action">
            <a
                href="<?php echo esc_url(home_url('/products/')); ?>"
                class="shop-view-all"
            >
                View All Products →
            </a>
        </div>


    <?php else : ?>

        <p class="shop-no-products">
            No featured products available.
        </p>

    <?php endif; ?>


    <?php wp_reset_postdata(); ?>

</section>

    


</main>


<?php

get_footer();