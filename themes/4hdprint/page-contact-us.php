<?php
/* Template Name: Contact Us*/
if (!defined('ABSPATH')){die();}
get_header(); 
?>
    <section class="contact-page">

    <div class="center_container">

        <div class="contact-page__grid">

            <div class="contact-page__visual">
                <div class="contact-page__visual-content">
                    <span>4HD PRINT</span>
                    <h2>Let’s create something together.</h2>
                    <p>
                        Tell us about your idea, project, or custom order.
                        We’ll be happy to help bring it to life.
                    </p>
                </div>
            </div>

            <div class="contact-page__form">

                <span class="contact-page__eyebrow">GET IN TOUCH</span>

                <h1>Contact Us</h1>

                <p class="contact-page__intro">
                    Have a question or a project in mind? Send us a message
                    and we’ll get back to you as soon as possible.
                </p>

                <?php get_template_part('template-parts/contact-form'); ?>

            </div>

        </div>

    </div>

</section>

<div class="contact-page__map">
    <div class="center_container">
        <div class="contact-page__map-frame">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3018.595833034297!2d-74.11464459999999!3d40.8368404!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2f9b8f01207e1%3A0x423b33b2c88c5b0d!2s4HD%20PRINT!5e0!3m2!1sen!2sus!4v1755022812344!5m2!1sen!2sus"
                width="100%"
                height="400"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
        </div>
    </div>
</div>


<?php get_footer(); ?>
