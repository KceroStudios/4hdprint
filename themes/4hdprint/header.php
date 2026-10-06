<?php if ( ! defined('ABSPATH') ) exit; ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div>
            <header class="main_header">

              <div class="social">
                <div class="center_container social_container">

                    <!-- AQUÍ VAN TODOS LOS SOCIAL-ITEMS -->

                  <a
                      class="social-item"
                      href="https://www.instagram.com/4hdprint/"
                      target="_blank"
                      rel="noopener noreferrer"
                      aria-label="Instagram"
                  >
                      <span class="dashicons dashicons-instagram" aria-hidden="true"></span>
                  </a>

                  <a
                      class="social-item"
                      href="https://www.facebook.com/4hdprint/"
                      target="_blank"
                      rel="noopener noreferrer"
                      aria-label="Facebook"
                  >
                      <span class="dashicons dashicons-facebook-alt" aria-hidden="true"></span>
                  </a>

                  <a
                      class="social-item"
                      href="mailto:info@4hdprint.com"
                      aria-label="Email 4HD Print"
                  >
                      <span class="dashicons dashicons-email" aria-hidden="true"></span>
                  </a>

                  <a
                      class="social-item"
                      href="https://www.google.com/maps/search/?api=1&query=4HD+PRINT+344+Union+Ave+Rutherford+NJ+07070"
                      target="_blank"
                      rel="noopener noreferrer"
                      aria-label="4HD Print location"
                  >
                      <span class="dashicons dashicons-location" aria-hidden="true"></span>
                  </a>

                  <a
                      class="social-item"
                      href="https://wa.me/12018939132"
                      target="_blank"
                      rel="noopener noreferrer"
                      aria-label="WhatsApp"
                  >
                      <span class="dashicons dashicons-whatsapp" aria-hidden="true"></span>
                  </a>

                  <a
                      class="social-item social-item--phone"
                      href="tel:+12018939132"
                  >
                      <span class="dashicons dashicons-phone" aria-hidden="true"></span>
                      <span class="social-item__text">+1 (201) 893-9132</span>
                  </a>


                  <div class="account-menu">

                      <button
                          type="button"
                          class="social-item account-menu__button"
                          aria-label="My Account"
                          aria-expanded="false"
                      >
                          <span class="dashicons dashicons-admin-users" aria-hidden="true"></span>
                      </button>

                      <div class="account-menu__dropdown">

                          <?php if ( is_user_logged_in() ) : ?>

                              <a href="<?php echo esc_url( wc_get_account_endpoint_url('dashboard') ); ?>">
                                  Dashboard
                              </a>

                              <a href="<?php echo esc_url( wc_get_account_endpoint_url('orders') ); ?>">
                                  Orders
                              </a>

                              <a href="<?php echo esc_url( wc_get_account_endpoint_url('downloads') ); ?>">
                                  Downloads
                              </a>

                              <a href="<?php echo esc_url( wc_get_account_endpoint_url('edit-address') ); ?>">
                                  Addresses
                              </a>

                              <a href="<?php echo esc_url( wc_get_account_endpoint_url('edit-account') ); ?>">
                                  Account Details
                              </a>

                              <a
                                  class="account-menu__logout"
                                  href="<?php echo esc_url( wc_logout_url() ); ?>"
                              >
                                  Log Out
                              </a>

                          <?php else : ?>

                              <a href="<?php echo esc_url( wc_get_page_permalink('myaccount') ); ?>">
                                  My Account
                              </a>

                          <?php endif; ?>

                      </div>

                  </div>

                </div>
              </div>

    

            
            <div class="menu_container">
              <div class="center_container">
 
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="logo"></a>

                <nav>
                  <div class="main_menu">
                    <button type="button" class="icon_menu" aria-label="Abrir menú">
                      <span class="dashicons dashicons-menu-alt2"></span>
                    </button>

                    <?php
                    wp_nav_menu([
                      'theme_location' => 'main_menu',
                      'container'      => false,
                      'menu_class'     => 'menu-list',
                      'fallback_cb'    => function () {
                        echo '<ul class="menu-list">';
                        wp_list_pages(['title_li' => '']);
                        echo '</ul>';
                      },
                    ]);
                    ?>
                  </div>
                </nav>

              </div>
            </div>

</header>

</div> <!-- .container -->

    

