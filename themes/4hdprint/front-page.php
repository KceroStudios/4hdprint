<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

/*
 * Homepage Banner
 */
$banner = get_option(
    '4hd_homepage_banner',
    [
        'enabled'     => 1,
        'image'       => '',
        'title'       => '',
        'description' => '',
        'button_text' => '',
        'button_url'  => '',
    ]
);

?>

<main id="primary" class="site-main">

    <?php if ( ! empty( $banner['enabled'] ) ) : ?>

        <section class="homepage-banner">

    <?php if ( ! empty( $banner['image'] ) ) : ?>

        <div class="homepage-banner__image">

            <?php
            echo wp_get_attachment_image(
                absint( $banner['image'] ),
                'full'
            );
            ?>

        </div>

    <?php endif; ?>


    <div class="homepage-banner__content">

        <?php if ( ! empty( $banner['title'] ) ) : ?>

            <h1>
                <?php echo esc_html( $banner['title'] ); ?>
            </h1>

        <?php endif; ?>


        <?php if ( ! empty( $banner['description'] ) ) : ?>

            <p>
                <?php echo esc_html( $banner['description'] ); ?>
            </p>

        <?php endif; ?>


        <?php if ( ! empty( $banner['button_text'] ) && ! empty( $banner['button_url'] ) ) : ?>

            <a
                href="<?php echo esc_url( $banner['button_url'] ); ?>"
                class="homepage-banner__button"
            >
                <?php echo esc_html( $banner['button_text'] ); ?>
            </a>

        <?php endif; ?>

    </div>

</section>

<div class="services_slide">
    <div class="featured__header"></div>

    <h2>Our Services</h2>
    <h3>Personalized Solutions, Made for You</h3>

    <div class="center_container slide">

        <div class="card"> 
            <img src="https://4hdprint.local/wp-content/themes/4hdprint/assets/images/print.png" alt="Print Services">
            <h3>Print Services</h3>
            <p>High-quality custom printing for your business</p>
            <a class="featured-products__shop-button" href="/product-category/print-services/">
                View All Products
            </a>
        </div>
            
        <div class="card"> 
            <img src="https://4hdprint.local/wp-content/themes/4hdprint/assets/images/custom.png" alt="Promotional Products">
            <h3>Promotional Products</h3>
            <p>Custom products designed especially just for you</p>
            <a class="featured-products__shop-button" href="/product-category/promotional-products/">
                View All Products
            </a>
        </div>

        <div class="card"> 
            <img src="https://4hdprint.local/wp-content/themes/4hdprint/assets/images/design.png" alt="Graphic Design">
            <h3>Graphic Design</h3>
            <p>Creative solutions to bring your ideas to life.</p>
            <a class="featured-products__shop-button" href="/product-category/graphic-design/">
                View All Products
            </a>
        </div> 

        <div class="card"> 
            <img src="https://4hdprint.local/wp-content/themes/4hdprint/assets/images/web.png" alt="Web Solutions">
            <h3>Web Solutions</h3>
            <p>Modern web design for growing businesses</p>
            <a class="featured-products__shop-button" href="/product-category/web-solutions/">
                View All Products
            </a>
        </div>

    </div>
</div>


    <?php endif; ?>


    <?php
/**
 * Featured Products
 */

$featured_products = new WP_Query([
    'post_type'      => 'product',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
    'tax_query'      => [
        [
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
        ],
    ],
]);
?>

<?php if ( $featured_products->have_posts() ) : ?>

    <section class="featured-products">

        <div class="featured__header">

            <h2>Featured Products</h2>

            <p>
                Discover some of our most popular products.
            </p>

        </div>


        <div class="featured-products__grid">

            <?php while ( $featured_products->have_posts() ) : ?>

                <?php $featured_products->the_post(); ?>

                <?php
                $product = wc_get_product( get_the_ID() );
                ?>

                <article class="featured-product">

                    <a
                        href="<?php the_permalink(); ?>"
                        class="featured-product__image"
                    >

                        <?php
                        if ( has_post_thumbnail() ) {
                            the_post_thumbnail('woocommerce_thumbnail');
                        }
                        ?>

                    </a>


                    <div class="featured-product__info">

                        <h3>

                            <a href="<?php the_permalink(); ?>">

                                <?php the_title(); ?>

                            </a>

                        </h3>


                        <?php if ( $product ) : ?>

                            <div class="featured-product__price">

                                <?php echo wp_kses_post( $product->get_price_html() ); ?>

                            </div>

                        <?php endif; ?>


                        <a
                            href="<?php the_permalink(); ?>"
                            class="featured-product__button"
                        >
                            View Product
                        </a>

                    </div>

                </article>

            <?php endwhile; ?>

        </div>


        <div class="featured-products__footer">

            <a
                href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>"
                class="featured-products__shop-button"
            >
                View All Products
            </a>

        </div>

    </section>

<?php endif; ?>

<?php wp_reset_postdata(); ?>

<?php
/**
 * Promotional Banner
 */

$promo = get_option('4hd_promotional_banner', [
    'enabled'     => 0,
    'image'       => '',
    'title'       => '',
    'description' => '',
    'offer'       => '',
    'button_text' => '',
    'button_url'  => '',
]);
?>

<?php if ( ! empty($promo['enabled']) ) : ?>

    <section class="promotional-banner">

        <div class="promotional-banner__content">

            <?php if ( ! empty($promo['title']) ) : ?>

                <h2>
                    <?php echo esc_html($promo['title']); ?>
                </h2>

            <?php endif; ?>


            <?php if ( ! empty($promo['description']) ) : ?>

                <p class="promotional-banner__description">
                    <?php echo esc_html($promo['description']); ?>
                </p>

            <?php endif; ?>


            <?php if ( ! empty($promo['offer']) ) : ?>

                <div class="promotional-banner__offer">
                    <?php echo esc_html($promo['offer']); ?>
                </div>

            <?php endif; ?>


            <?php if (
                ! empty($promo['button_text']) &&
                ! empty($promo['button_url'])
            ) : ?>

                <a
                    href="<?php echo esc_url($promo['button_url']); ?>"
                    class="promotional-banner__button"
                >
                    <?php echo esc_html($promo['button_text']); ?>
                </a>

            <?php endif; ?>

        </div>


        <?php if ( ! empty($promo['image']) ) : ?>

            <div class="promotional-banner__image">

                <?php
                echo wp_get_attachment_image(
                    absint($promo['image']),
                    'large'
                );
                ?>

            </div>

        <?php endif; ?>

    </section>

<?php endif; ?>

<?php
/**
 * Client Logos
 */

$client_logos = get_option('4hd_client_logos', []);

if (! empty($client_logos)) :
?>

<section class="client-logos">

    <div class="client-logos__header">

        <h2>Trusted by our clients</h2>

    </div>


    <div class="client-logos__carousel">

        <div class="client-logos__track">

            <?php foreach ($client_logos as $logo) : ?>

                <?php
                $image_id = ! empty($logo['image'])
                    ? absint($logo['image'])
                    : 0;

                if (! $image_id) {
                    continue;
                }
                ?>

                <div class="client-logo">

                    <?php
                    echo wp_get_attachment_image(
                        $image_id,
                        'medium'
                    );
                    ?>

                </div>

            <?php endforeach; ?>


            <?php
            /*
             * Duplicate logos so the animation
             * can loop continuously.
             */
            ?>

            <?php foreach ($client_logos as $logo) : ?>

                <?php
                $image_id = ! empty($logo['image'])
                    ? absint($logo['image'])
                    : 0;

                if (! $image_id) {
                    continue;
                }
                ?>

                <div class="client-logo">

                    <?php
                    echo wp_get_attachment_image(
                        $image_id,
                        'medium'
                    );
                    ?>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<?php endif; ?>



</main>

<?php

get_footer();

