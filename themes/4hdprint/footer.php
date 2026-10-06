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

            <!-- COLUMN 2 — INFORMATION -->

            <div class="homepage-bottom__column homepage-bottom__links">

                <h2>Information</h2>

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

            <div
                id="contact"
                class="homepage-bottom__column homepage-bottom__contact"
            >

                <h2>Contact Us</h2>

                <?php
                    $contact_status = isset($_GET['contact'])
                        ? sanitize_key(wp_unslash($_GET['contact']))
                        : '';

                    if ($contact_status === 'success') :
                    ?>
                        <div
                            class="homepage-contact-form__notice homepage-contact-form__notice--success"
                            role="status"
                        >
                            Thank you! Your message has been sent successfully.
                        </div>

                    <?php elseif ($contact_status === 'rate-limit') : ?>

                    <div
                        class="homepage-contact-form__notice homepage-contact-form__notice--error"
                        role="alert"
                    >
                        Too many messages have been submitted. Please wait a few minutes and try again.
                    </div>

                <?php elseif ($contact_status === 'error') : ?>

                    <div
                        class="homepage-contact-form__notice homepage-contact-form__notice--error"
                        role="alert"
                    >
                        Sorry, your message could not be sent. Please try again.
                    </div>

                <?php endif; ?>

                <form
                    class="homepage-contact-form"
                    method="post"
                    action=""
                >

                <?php wp_nonce_field('4hd_contact_form', '4hd_contact_nonce'); ?>
                    
                <input
                        type="hidden"
                        name="4hd_contact_form"
                        value="1"
                    >

                    <div
                        class="homepage-contact-form__honeypot"
                        aria-hidden="true"
                    >
                        <label for="contact-website">Website</label>

                        <input
                            type="text"
                            id="contact-website"
                            name="contact_website"
                            tabindex="-1"
                            autocomplete="off"
                        >
                    </div>

                    <div class="homepage-contact-form__grid">

                        <div class="homepage-contact-form__field">
                            <label for="contact-name">Name *</label>
                            <input
                                type="text"
                                id="contact-name"
                                name="contact_name"
                                required
                            >
                        </div>

                        <div class="homepage-contact-form__field">
                            <label for="contact-email">Email *</label>
                            <input
                                type="email"
                                id="contact-email"
                                name="contact_email"
                                required
                            >
                        </div>

                        <div class="homepage-contact-form__field">
                            <label for="contact-phone">Phone</label>
                            <input
                                type="tel"
                                id="contact-phone"
                                name="contact_phone"
                            >
                        </div>

                        <div class="homepage-contact-form__field">
                            <label for="contact-service">Service</label>
                            <select
                                id="contact-service"
                                name="contact_service"
                            >
                                <option value="">Select a service</option>
                                <option value="print-services">Print Services</option>
                                <option value="promotional-products">Promotional Products</option>
                                <option value="graphic-design">Graphic Design</option>
                                <option value="web-solutions">Web Solutions</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                    </div>

                    <div class="homepage-contact-form__field">
                        <label for="contact-message">Message *</label>
                        <textarea
                            id="contact-message"
                            name="contact_message"
                            rows="4"
                            required
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        class="homepage-contact-form__submit"
                    >
                        Send Message
                    </button>

                </form>

            </div>

        </div>

    </section>

<?php endif; ?>


<!-- =========================================
     GLOBAL FOOTER
========================================== -->

<footer class="site-footer">

    <div class="site-footer__inner">

        <!-- Footer Main Content -->

        <div class="site-footer__main">

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


            <!-- Contact Information -->

           <div class="site-footer__contact">

                <p class="site-footer__contact-item">
                    <span class="dashicons dashicons-location" aria-hidden="true"></span>
                    <span>344 Union Avenue, Rutherford, NJ 07070</span>
                </p>

                <p class="site-footer__contact-item">
                    <span class="dashicons dashicons-phone" aria-hidden="true"></span>
                    <a href="tel:+12018939132">
                        +1 (201) 893-9132
                    </a>
                </p>

                <p class="site-footer__contact-item">
                    <span class="dashicons dashicons-email" aria-hidden="true"></span>
                    <a href="mailto:info@4hdprint.com">
                        info@4hdprint.com
                    </a>
                </p>

            </div>

        </div>


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


