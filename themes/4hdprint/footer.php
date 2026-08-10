<?php if ( is_front_page() ) : ?>
<?php
/**
 * Homepage Bottom Section
 */

$video = get_option('4hd_homepage_video', [
    'enabled' => 0,
    'image'   => '',
    'url'     => '',
    'title'   => '',
]);
?>
    <!-- =========================================
         HOMEPAGE PRE-FOOTER
    ========================================== -->

    <section class="homepage-bottom" aria-label="Additional information">

        <div class="homepage-bottom__columns">


            <!-- =====================================
                 COLUMN 1 — VIDEO
            ====================================== -->

            <div class="homepage-bottom__column homepage-bottom__video">

                <h2>Latest Video</h2>

                <?php if (
                    ! empty($video['enabled']) &&
                    ! empty($video['url'])
                ) : ?>

                    <a
                        href="<?php echo esc_url($video['url']); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="homepage-video"
                    >

                        <?php
                        if ( ! empty($video['image']) ) {

                            echo wp_get_attachment_image(
                                absint($video['image']),
                                'large'
                            );

                        }
                        ?>

                        <span
                            class="homepage-video__play"
                            aria-hidden="true"
                        >
                            <span class="dashicons dashicons-controls-play"></span>
                        </span>

                    </a>


                    <?php if ( ! empty($video['title']) ) : ?>

                        <h3>
                            <?php echo esc_html($video['title']); ?>
                        </h3>

                    <?php endif; ?>


                    <a
                        href="<?php echo esc_url($video['url']); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="homepage-video__link"
                    >
                        Watch on YouTube →
                    </a>

                <?php else : ?>

                    <p>No video available.</p>

                <?php endif; ?>

                <!-- Social Networks -->

                <div
                    class="homepage-social"
                    aria-label="Social media"
                >

                    <a
                        href="https://www.instagram.com/4hdprint/"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Instagram"
                    >
                        <span class="dashicons dashicons-instagram"></span>
                    </a>


                    <a
                        href="https://www.facebook.com/profile.php?id=61560731580431"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Facebook"
                    >
                        <span class="dashicons dashicons-facebook"></span>
                    </a>


                    <a
                        href="https://www.youtube.com/@4HDPRINT"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="YouTube"
                    >
                        <span class="dashicons dashicons-youtube"></span>
                    </a>

                </div>

            </div>


            <!-- =====================================
                 COLUMN 2 — QUICK LINKS
            ====================================== -->

            <div class="homepage-bottom__column homepage-bottom__links">

                <h2>Quick Links</h2>

                <nav
                    class="homepage-quick-links"
                    aria-label="Quick links"
                >

                    <?php
                    wp_nav_menu([
                        'theme_location' => 'main_menu',
                        'container'      => false,
                        'menu_class'     => 'homepage-links-list',
                        'fallback_cb'    => false,
                    ]);
                    ?>

                </nav>


                <h3>Information</h3>

                <nav
                    class="homepage-information-links"
                    aria-label="Information"
                >

                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer_menu',
                        'container'      => false,
                        'menu_class'     => 'homepage-links-list',
                        'fallback_cb'    => false,
                    ]);
                    ?>

                </nav>

            </div>


            <!-- =====================================
                 COLUMN 3 — CONTACT
            ====================================== -->

            <div class="homepage-bottom__column homepage-bottom__contact">

                <h2>Contact Us</h2>

                <?php
                echo do_shortcode(
                    '[wpforms id="61" title="false" description="false"]'
                );
                ?>

            </div>

        </div>

    </section>

<?php endif; ?>


<!-- =========================================
     GLOBAL FOOTER
========================================== -->

<footer class="site-footer">

    <div class="site-footer__inner">

        <!-- Brand -->

        <div class="site-footer__brand">

            <a
                href="<?php echo esc_url( home_url('/') ); ?>"
                class="site-footer__logo"
                aria-label="4HD PRINT Home"
            >

                <img
                    src="<?php echo esc_url(
                        get_template_directory_uri() . '/assets/images/whitelogo.png'
                    ); ?>"
                    alt="4HD PRINT"
                >

            </a>

        </div>


        <!-- Legal Navigation -->

        <nav
            class="site-footer__navigation"
            aria-label="Legal navigation"
        >

            <?php
            wp_nav_menu([
                'theme_location' => 'footer_menu',
                'container'      => false,
                'menu_class'     => 'footer-menu',
                'fallback_cb'    => false,
            ]);
            ?>

        </nav>


        <!-- Copyright -->

        <div class="site-footer__copyright">

            <p>
                © <?php echo esc_html( wp_date('Y') ); ?>
                4HD PRINT LLC
            </p>

        </div>

    </div>

</footer>

<button
    id="back-to-top"
    class="back-to-top"
    type="button"
    aria-label="Back to top"
>
    <span class="dashicons dashicons-arrow-up-alt2"></span>
</button>
<?php wp_footer(); ?>


