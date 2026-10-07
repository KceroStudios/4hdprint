<?php
/* Template Name: About Us */
if (!defined('ABSPATH')){die();}
get_header(); 
?>
    <div class="about-us">
        <div class="center_container">
            <h1>4HD PRINT</h1>
            <div class="about-intro">

                <img
                    class="about-intro__image"
                    src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/about.png' ); ?>"
                    alt="4HD Print creative workspace"
                >

                <p>
                    At 4HD PRINT, art is our passion, and we believe every project is an opportunity to transform ideas into meaningful creations that truly connect with people. Our mission is to deliver creative, high-quality solutions that not only highlight each client’s unique identity but also strengthen their presence and recognition.

                    We offer a wide range of services, including professional graphic design, customized promotional items, exclusive t-shirts and keepsakes, as well as modern, functional websites. Every project we take on is crafted to communicate an authentic, creative, and lasting message.

                    More than just a design and print shop, we are a creative team that blends experience, innovation, and attention to detail to bring ideas to life. From a logo to a personalized gift, from a brochure to a complete website, every piece we deliver reflects our passion for art and our dedication to adding value to those who trust us.

                    At 4HD PRINT, we don’t just design and print—we create visual experiences that last.
                </p>

            </div>

            <div class="philosophy">
                <h2>Our Philosophy</h2>

                <div class="philosophy-grid">

                    <div class="philosophy-item">
                        <h3>Mission</h3>
                        <p>
                            At 4HD PRINT, we are dedicated to transforming ideas into art through graphic design, promotional products, and personalized items. Our mission is to provide creative, high-quality solutions that reflect each client’s unique identity, contributing to their success, recognition, and growth.
                        </p>
                    </div>

                    <div class="philosophy-item">
                        <h3>Vision</h3>
                        <p>
                            To be leaders in design and promotional product customization, recognized for our creativity, innovation, and commitment to excellence. We aspire to inspire and empower our clients, becoming their trusted partner for all their creative, graphic, and promotional needs.
                        </p>
                    </div>

                    <div class="philosophy-values">
                        <h3>Values</h3>

                        <ul>
                            <li>
                                <strong>Creativity</strong>
                                <span>We believe in the power of creativity to transform ideas into impactful visual experiences.</span>
                            </li>

                            <li>
                                <strong>Quality</strong>
                                <span>We are committed to exceeding expectations with products and services of the highest quality.</span>
                            </li>

                            <li>
                                <strong>Passion</strong>
                                <span>We approach every project with enthusiasm, dedication, and a unique artistic touch.</span>
                            </li>

                            <li>
                                <strong>Innovation</strong>
                                <span>We constantly explore new ideas and technologies to deliver modern and effective solutions.</span>
                            </li>

                            <li>
                                <strong>Collaboration</strong>
                                <span>We foster close relationships with our clients, understanding their goals to create tailored and effective results.</span>
                            </li>

                            <li>
                                <strong>Integrity</strong>
                                <span>We act with honesty, transparency, and ethics, building long-term relationships based on trust.</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="team">
                <h2>Our Team</h2>

                <div class="row">

                    <div class="col team_card">
                        <img
                            src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/lup.png'); ?>"
                            alt="L. Cabarcas"
                        >

                        <h3>L. Cabarcas</h3>
                        <small>Advertising Specialist & Visual Artist</small>

                        <p>
                            Advertising specialist and visual artist with training from UASD,
                            Altos de Chavón, and the National School of Fine Arts. She combines
                            strategic thinking with artistic sensibility to create distinctive
                            and engaging visual experiences.
                        </p>
                        <div class="team-credentials">
                            <span>UASD</span>
                            <span>Altos de Chavón</span>
                            <span>Fine Arts</span>
                        </div>
                    </div>

                    <div class="col team_card">
                        <img
                            src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/edi.png'); ?>"
                            alt="E. Gómez"
                        >

                        <h3>E. Gómez</h3>
                        <small>Graphic Designer & Web Developer</small>

                        <p>
                            Graphic designer and web developer with a multidisciplinary background
                            in design and technology. He combines visual creativity and web development
                            to create functional, engaging, and technically solid solutions.
                        </p>
                        <div class="team-credentials">
                            <span>UASD</span>
                            <span>Udemy</span>
                            <span>ITLA</span>
                        </div>
                    </div>

                    <div class="col team_card">
                        <img
                            src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/astro.png'); ?>"
                            alt="AstroDoodle"
                        >

                        <h3>AstroDoodle</h3>
                        <small>Concept Artist & Creative Thinker</small>

                        <p>
                            Concept artist and creative thinker who transforms ideas into visual
                            stories. Through sketching, experimentation, and imagination,
                            AstroDoodle helps turn early concepts into distinctive creative
                            directions.
                        </p>
                        <div class="team-credentials">
                            <span>Concept Art</span>
                            <span>Illustration</span>
                            <span>Creative Thinking</span>
                        </div>
                    </div>

                </div>
            </div>





        </div>
        
        
    </div>
    


<?php get_footer(); ?>
