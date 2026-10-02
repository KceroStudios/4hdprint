<?php
/* Template Name: Contact Us*/
if (!defined('ABSPATH')){die();}
get_header(); 
?>
    <div class="contact">
        
        <div class="contact_group">
            <div class="col"></div>
            <div class="col contact_form">
                <h1>Contact Us</h1>
                <?php echo do_shortcode('[wpforms id="61" title="false"]'); ?> 
            </div>
        </div>
    </div>
    <div class="map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3018.595833034297!2d-74.11464459999999!3d40.8368404!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2f9b8f01207e1%3A0x423b33b2c88c5b0d!2s4HD%20PRINT!5e0!3m2!1sen!2sus!4v1755022812344!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>


<?php get_footer(); ?>
