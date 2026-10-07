<?php
/**
 * Template for the Products page
 *
 * Displays the complete WooCommerce product catalog.
 *
 * 4HD PRINT
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main products-page">

    <section class="products-page__header">

        <div class="center_container">

            <h1>All Products</h1>

            <p>
                Explore our complete selection of custom products,
                printing services, and creative solutions.
            </p>

        </div>

    </section>

    <section class="products-catalog">

    <div class="center_container">

        <?php
        $paged = max(
            1,
            get_query_var('paged'),
            get_query_var('page')
        );

        $products_query = new WP_Query([
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 12,
            'paged'          => $paged,
        ]);
        ?>

        <?php if ($products_query->have_posts()) : ?>

            <div class="products-catalog__grid">

                <?php while ($products_query->have_posts()) : ?>

                    <?php
                    $products_query->the_post();
                    $product = wc_get_product(get_the_ID());

                    if (!$product) {
                        continue;
                    }
                    ?>

                    <article class="products-catalog__card">

                        <a
                            href="<?php the_permalink(); ?>"
                            class="products-catalog__image"
                        >
                            <?php
                            echo $product->get_image(
                                'woocommerce_thumbnail'
                            );
                            ?>
                        </a>

                        <div class="products-catalog__content">

                            <h2>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <div class="products-catalog__price">
                                <?php echo wp_kses_post($product->get_price_html()); ?>
                            </div>

                            <a
                                href="<?php the_permalink(); ?>"
                                class="products-catalog__button"
                            >
                                View Product
                            </a>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

            <nav class="products-catalog__pagination">
                <?php
                echo paginate_links([
                    'total'   => $products_query->max_num_pages,
                    'current' => $paged,
                ]);
                ?>
            </nav>

        <?php else : ?>

            <p class="products-catalog__empty">
                No products are currently available.
            </p>

        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>

</section>

</main>

<?php
get_footer();