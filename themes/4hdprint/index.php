<?php
    if (!defined('ABSPATH')){die();}
    get_header();
    echo do_shortcode('[smartslider3 slider="2"]');
?>

<div class="services_slide">
    <div class="center_container slide">
        <div class="card"> 
            <img src="<?php echo get_template_directory_uri()?>/assets/images/print.png">
            <h3>PRINT SERVICES</h3>
            <p> High-quality printing for business cards, flyers, and more. Stand out with vibrant colors and professional finishes...</p>
            <!-- <a class="more" target="_blank" href="https://www.instagram.com/4hdprint?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==">Read More</a>   -->
        </div>
        <div class="card_divider"></div>
        <div class="card"> 
            <img src="<?php echo get_template_directory_uri()?>/assets/images/custom.png">
            <h3>CUSTOM PRODUCTS</h3>
            <p> Personalize t-shirts, mugs, and more with unique designs. Perfect for gifts and branding with a special touch...</p>
            <!-- <a class="more" target="_blank" href="https://www.instagram.com/4hdprint?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==">Read More</a>   -->
        </div>
        <div class="card_divider"></div>
        <div class="card"> 
            <img src="<?php echo get_template_directory_uri()?>/assets/images/web.png">
            <h3>WEB SOLUTIONS</h3>
            <p> High-quality Modern and functional web design for businesses. Boost your online presence with fast and attractive websites...</p>
            <!-- <a class="more" target="_blank" href="https://www.instagram.com/4hdprint?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==">
                <span class="squared"></span>
                Read More
            </a>   -->
        </div> 
    </div>
    <div class="more_services">
        <div class="line line_2"></div>
        <a target="_blank" href="https://www.instagram.com/4hdprint?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==">MORE SERVICES</a>
        <div class="line line_1"></div>
    </div>
</div> 
<div class="featured center_container_col">
    <h2>FEATURED PROJECTS</h2>
    <?php echo do_shortcode('[Best_Wordpress_Gallery id="1"]'); ?>
</div>

<div class="client_container">
    <div class="clients_group">
        <h2>Our Clients</h2>
        <?php echo do_shortcode('[logocarousel id="12"]'); ?>
    </div>
</div>




<?php   
    
    get_footer();
?>

