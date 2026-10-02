<?php
/* Template Name: Contact Us*/
if (!defined('ABSPATH')){die();}
get_header(); 
?>
    <div class="about-us">
        <div class="center_container">
            <h1>4HD PRINT</h1>
            <div class="row">
                <div class="col">    
                    <p>
                    At 4HD PRINT, art is our passion, and we believe every project is an opportunity to transform ideas into meaningful creations that truly connect with people. Our mission is to deliver creative, high-quality solutions that not only highlight each client’s unique identity but also strengthen their presence and recognition.
    
                    We offer a wide range of services, including professional graphic design, customized promotional items, exclusive t-shirts and keepsakes, as well as modern, functional websites. Every project we take on is crafted to communicate an authentic, creative, and lasting message.
    
                    More than just a design and print shop, we are a creative team that blends experience, innovation, and attention to detail to bring ideas to life. From a logo to a personalized gift, from a brochure to a complete website, every piece we deliver reflects our passion for art and our dedication to adding value to those who trust us.
    
                    At 4HD PRINT, we don’t just design and print—we create visual experiences that last.
                    </p>
                </div>
                <div class="divider">
                    <span class="dashicons dashicons-format-quote" aria-hidden="true"></span>
                    <div class="line"></div>
                </div>
                <div class="col about-img">
                    <img src="<?php echo get_template_directory_uri()?>/assets/images/about.png">
                </div>
            </div>

            <div class="philosophy">
                <h2>Our Philosophy</h2>

                <div class="row">
                    <div class="col">
                        <h3>Mission</h3>
                        <p>
                            At 4HD PRINT, we are dedicated to transforming ideas into art through graphic design, promotional products, and personalized items. Our mission is to provide creative, high-quality solutions that reflect each client’s unique identity, contributing to their success, recognition, and growth.
                        </p>
                    </div>
                
                    <div class="col">
                        <h3>Vision</h3>
                        <p>
                            To be leaders in design and promotional product customization, recognized for our creativity, innovation, and commitment to excellence. We aspire to inspire and empower our clients, becoming their trusted partner for all their creative, graphic, and promotional needs.
                        </p>
                    </div>
               
                    <div class="col">
                        <h3>Values</h3>
                        <ul>
                            <li>Creativity: We believe in the power of creativity to transform ideas into impactful visual experiences.</li>

                            <li>Quality: We are committed to exceeding expectations with products and services of the highest quality.</li>

                            <li>Passion: We approach every project with enthusiasm, dedication, and a unique artistic touch.</li>

                            <li>Innovation: We constantly explore new ideas and technologies to deliver modern and effective solutions.</li>

                            <li>Collaboration: We foster close relationships with our clients, understanding their goals to create tailored and effective results.</li>

                            <li>Integrity: We act with honesty, transparency, and ethics, building long-term relationships based on trust.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="team">
                <h2>Our Team</h2>
                <div class="row">
                    <div class="col team_card">
                        <img src="<?php echo get_template_directory_uri()?>/assets/images/lup.png">
                        <h3>L. Cabarcas</h3>
                        <small>Advertising Specialist & Visual Artist</small>
                        <input type="checkbox" class="read-more-toggle" id="readMoreToggle">
                        <div class="read-more-container">
                            <p>
                                Holds a degree in Advertising and continues to expand her artistic vision through studies at UASD, Altos de Chavón School of Design, and the National School of Fine Arts in the Dominican Republic. Her background combines strategy and creativity, bringing fresh ideas, artistic sensibility, and a strong visual impact to our team’s projects.
                            </p>
                        </div>
                        <label for="readMoreToggle" class="read-more-label"></label>
                    </div>
                    <div class="col team_card">
                        <img src="<?php echo get_template_directory_uri()?>/assets/images/edi.png">
                        <h3>E. Gómez </h3>
                        <small>Graphic Designer & Web Developer</small>
                        <input type="checkbox" class="read-more-toggle" id="readMoreToggle">
                        <div class="read-more-container">
                            <p>
                                Is a versatile creative professional with academic training from UASD and ITLA, complemented by specialized courses on platforms like Udemy. His expertise spans from visual design to functional web development, allowing him to craft digital solutions that are both aesthetically engaging and technically solid. He brings innovation, adaptability, and a problem-solving mindset to every project.
                            </p>
                        </div>
                        <label for="readMoreToggle" class="read-more-label"></label>
                    </div>
                    <div class="col team_card">
                        <img src="<?php echo get_template_directory_uri()?>/assets/images/astro.png">
                        <h3>AstroDoodle</h3>
                        <small>Concept Artist & Creative Thinker</small>
                        <input type="checkbox" class="read-more-toggle" id="readMoreToggle">
                        <div class="read-more-container">
                            <p>
                                Astro Doodle specializes in transforming concepts into visual stories that spark imagination and inspire collaboration. With a natural talent for sketching and an inventive mindset, she turns abstract ideas into clear, creative directions. Her quick sketches and bold imagination help the team brainstorm, refine concepts, and push boundaries in every project. Always exploring new ways to blend art and design, she brings energy, playfulness, and fresh perspectives that make our creative process truly dynamic.
                            </p>
                        </div>
                        <label for="readMoreToggle" class="read-more-label"></label>
                    </div>
                </div>
                    
                </div>
            </div>






        </div>
        
        
    </div>
    


<?php get_footer(); ?>
