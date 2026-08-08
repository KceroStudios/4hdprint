<?php if ( ! defined('ABSPATH') ) exit; ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body>
    <div class="container">
        <header class="main_header">
            <div class="social">
                <div class="center_container social_container">
                    <a class="insta"
                        href="https://www.instagram.com/4hdprint?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="
                        target="_blank" rel="noopener noreferrer" aria-label="Instagram 4HD Print">
                    <span class="dashicons dashicons-instagram" aria-hidden="true"></span>
                    </a>
                    <a class="facebook"
                        href="https://www.facebook.com/profile.php?id=61560731580431"
                        target="_blank" rel="noopener noreferrer" aria-label="Facebook 4HD Print">
                    <span class="dashicons dashicons-facebook-alt" aria-hidden="true"></span>
                    </a>
                    <a class="email"
                        href="mailto:info@4hdprint.com"
                        aria-label="Enviar correo a info@4hdprint.com">
                    <span class="dashicons dashicons-email-alt" aria-hidden="true"></span>
                    </a>
                    <a class="location"
                      href="https://www.google.com/maps/dir/?api=1&destination=4HD+PRINT+344+Union+Ave+Rutherford,+NJ+07070"
                      target="_blank"
                      aria-label="Location 4HD Print">
                      <span class="dashicons dashicons-location" aria-hidden="true"></span>
                    </a>
                    <a class="wapp"
                        href="https://wa.me/12018939132?text=Hola,%20quisiera%20m%C3%A1s%20informaci%C3%B3n"
                        target="_blank" rel="noopener noreferrer" aria-label="WhatsApp 4HD Print">
                    <span class="dashicons dashicons-whatsapp" aria-hidden="true"></span>
                    
                    </a>   
                    <a class="phone" href="tel:+12018939132" aria-label="Llamar al (201) 893-9132">
                        <span class="dashicons dashicons-phone" aria-hidden="true"></span>
                        <span class="text">+1 (201) 893-9132</span>
                    </a> 
                </div>
            </div>

            
            <div class="menu_container">
  <div class="line line_1"></div>

  <a href="<?php echo esc_url( home_url('/') ); ?>" class="logo"></a>

  <div class="line line_2"></div>

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

  <div class="line line_3"></div>
</div>

</header>

</div> <!-- .container -->

    

