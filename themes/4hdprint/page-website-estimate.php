<?php
/**
 * Template Name: Website Estimate
 */

defined('ABSPATH') || exit;

get_header();
?>

<main class="website-estimate">

<?php
$estimate_status = isset($_GET['estimate_status'])
    ? sanitize_key($_GET['estimate_status'])
    : '';
?>

<?php if ($estimate_status === 'success') : ?>

    <div class="estimate-notice estimate-notice--success">

        <strong>Thank you!</strong>

        <p>
            Your website quote request has been received.
            We'll review your project and contact you soon.
        </p>

    </div>

<?php elseif ($estimate_status === 'error') : ?>

    <div class="estimate-notice estimate-notice--error">

        <strong>We couldn't send your request.</strong>

        <p>
            Please try again or contact us directly.
        </p>

    </div>

<?php endif; ?>

    <section class="website-estimate__header">

        <h1>Website Project Estimator</h1>

        <p>
            Tell us a little about your project and get a preliminary
            estimate for your website.
        </p>

    </section>


    <form
    id="website-estimator"
    method="post"
    action=""
    >
        <?php wp_nonce_field(
            'fourhd_website_estimate',
            'fourhd_estimate_nonce'
        ); ?>

        <input
            type="hidden"
            name="fourhd_estimate_action"
            value="submit_estimate"
        >

        <!-- =====================================
            STEP 1 — WEBSITE PACKAGE
        ====================================== -->

        <section class="estimate-section">

            <div class="estimate-section__header">
                <span class="estimate-step">01</span>

                <div>
                    <h2>Choose Your Website</h2>
                    <p>
                        Start with the option that best fits your project.
                        You can customize it in the next step.
                    </p>
                </div>
            </div>


    <div class="estimate-options estimate-options--3">

        <!-- STARTER WEBSITE -->

        <label class="estimate-option">

            <input
                type="radio"
                name="website_type"
                value="starter"
            >

            <span class="estimate-option__content">

                <strong>Starter Website</strong>

                <small>
                    A simple and professional website for presenting
                    your business, services or portfolio.
                </small>

                <span class="estimate-option__includes">
                    1–3 pages<br>
                    Contact form<br>
                    Gallery / Portfolio<br>
                    Maps & Social Links
                </span>

                <span class="estimate-option__price">
                    Starting at $450
                </span>

            </span>

        </label>


        <!-- BUSINESS WEBSITE -->

        <label class="estimate-option">

            <input
                type="radio"
                name="website_type"
                value="business"
            >

            <span class="estimate-option__content">

                <strong>Business Website</strong>

                <small>
                    A professional website with more pages,
                    administration tools and custom functionality.
                </small>

                <span class="estimate-option__includes">
                    3+ pages<br>
                    Admin Panel<br>
                    Custom Forms<br>
                    Advanced Functionality
                </span>

                <span class="estimate-option__price">
                    Starting at $900
                </span>

            </span>

        </label>


        <!-- ONLINE STORE -->

        <label class="estimate-option">

            <input
                type="radio"
                name="website_type"
                value="store"
            >

            <span class="estimate-option__content">

                <strong>Online Store</strong>

                <small>
                    A complete e-commerce website designed
                    to sell your products online.
                </small>

                <span class="estimate-option__includes">
                    WooCommerce<br>
                    Shopping Cart & Checkout<br>
                    Online Payments<br>
                    Order Management
                </span>

                <span class="estimate-option__price">
                    Starting at $1,800
                </span>

            </span>

        </label>

    </div>

</section>


<!-- =====================================
     STEP 2 — CUSTOMIZE WEBSITE
====================================== -->

<section class="estimate-section" id="estimate-customize">

    <div class="estimate-section__header">

        <span class="estimate-step">02</span>

        <div>
            <h2>Customize Your Website</h2>
            <p>
                Choose the features and functionality your website needs.
            </p>
        </div>

    </div>


    <!-- =====================================
         NUMBER OF PAGES
    ====================================== -->

    <div
        class="estimate-customize-group"
        id="estimate-pages-group"
    >

        <h3>Number of Pages</h3>

        <div class="estimate-options estimate-options--5">

            <label class="estimate-option estimate-option--small">
                <input type="radio" name="pages" value="1">

                <span class="estimate-option__content">
                    <strong>1</strong>
                    <small>Page</small>
                </span>
            </label>


            <label class="estimate-option estimate-option--small">
                <input type="radio" name="pages" value="3">

                <span class="estimate-option__content">
                    <strong>2–3</strong>
                    <small>Pages</small>
                </span>
            </label>


            <label class="estimate-option estimate-option--small">
                <input type="radio" name="pages" value="5">

                <span class="estimate-option__content">
                    <strong>4–5</strong>
                    <small>Pages</small>
                </span>
            </label>


            <label class="estimate-option estimate-option--small">
                <input type="radio" name="pages" value="10">

                <span class="estimate-option__content">
                    <strong>6–10</strong>
                    <small>Pages</small>
                </span>
            </label>


            <label class="estimate-option estimate-option--small">
                <input type="radio" name="pages" value="10plus">

                <span class="estimate-option__content">
                    <strong>10+</strong>
                    <small>Pages</small>
                </span>
            </label>

        </div>

    </div>


    <!-- =====================================
         GENERAL FEATURES
    ====================================== -->

    <div
        class="estimate-customize-group"
        id="estimate-general-features"
    >

        <h3>General Features</h3>

        <div class="estimate-features">

            <?php
            $general_features = [

                'contact' =>
                    'Contact Form',

                'gallery' =>
                    'Gallery / Portfolio',

                'maps' =>
                    'Google Maps',

                'social' =>
                    'Social Media Integration',

                'blog' =>
                    'Blog',

                'newsletter' =>
                    'Newsletter',

                'multilingual' =>
                    'Multilingual Website',

            ];

            foreach ($general_features as $value => $label) :
            ?>

                <label
                    class="estimate-feature"
                    data-feature="<?php echo esc_attr($value); ?>"
                >

                    <input
                        type="checkbox"
                        name="features[]"
                        value="<?php echo esc_attr($value); ?>"
                    >

                    <span>
                        <?php echo esc_html($label); ?>
                    </span>

                </label>

            <?php endforeach; ?>

        </div>

    </div>


    <!-- =====================================
         BUSINESS FEATURES
    ====================================== -->

    <!-- =====================================
     FORMS & ADVANCED FUNCTIONALITY
====================================== -->

<div
    class="estimate-customize-group"
    id="estimate-business-features"
>

    <h3>Forms & Advanced Functionality</h3>


    <!-- STANDARD CUSTOM FORM -->

    <div class="estimate-subgroup">

        <div class="estimate-subgroup__header">

            <strong>Standard Custom Form</strong>

            <span
                class="estimate-subgroup__badge"
                id="business-form-badge"
                style="display: none;"
            >
                1 Included
            </span>

        </div>

        <p
            class="estimate-group-description"
            id="business-form-message"
            style="display: none;"
        >
            Your Business Website includes one standard custom form.
            Choose the option that best fits your business.
        </p>


        <div class="estimate-features">

            <label
                class="estimate-feature"
                data-feature="quote_form"
            >

                <input
                    type="checkbox"
                    name="features[]"
                    value="quote_form"
                >

                <span>
                    Quote Request Form
                </span>

            </label>


            <label
                class="estimate-feature"
                data-feature="employment_form"
            >

                <input
                    type="checkbox"
                    name="features[]"
                    value="employment_form"
                >

                <span>
                    Employment / Application Form
                </span>

            </label>

        </div>

    </div>


    <!-- ADDITIONAL FUNCTIONALITY -->

    <div class="estimate-subgroup">

        <div class="estimate-subgroup__header">

            <strong>Additional Functionality</strong>

        </div>


        <div class="estimate-features">

            <label
                class="estimate-feature"
                data-feature="booking"
            >

                <input
                    type="checkbox"
                    name="features[]"
                    value="booking"
                >

                <span>
                    Appointment Booking
                </span>

            </label>


            <label
                class="estimate-feature"
                data-feature="calculator"
            >

                <input
                    type="checkbox"
                    name="features[]"
                    value="calculator"
                >

                <span>
                    Custom Calculator
                </span>

            </label>


            <label
                class="estimate-feature"
                data-feature="upload"
            >

                <input
                    type="checkbox"
                    name="features[]"
                    value="upload"
                >

                <span>
                    File Upload
                </span>

            </label>

        </div>

    </div>

</div>


    <!-- =====================================
         ONLINE STORE OPTIONS
    ====================================== -->

    <div
        class="estimate-customize-group estimate-store-options"
        id="estimate-store-options"
    >

        <h3>Store Products</h3>

        <p class="estimate-group-description">
            How many products would you like us to add
            when we build your store?
        </p>


        <div class="estimate-options estimate-options--5">

            <label class="estimate-option estimate-option--small">

                <input
                    type="radio"
                    name="products"
                    value="10"
                >

                <span class="estimate-option__content">

                    <strong>Up to 10</strong>

                    <small>
                        Products
                    </small>

                </span>

            </label>


            <label class="estimate-option estimate-option--small">

                <input
                    type="radio"
                    name="products"
                    value="25"
                >

                <span class="estimate-option__content">

                    <strong>11–25</strong>

                    <small>
                        Products
                    </small>

                </span>

            </label>


            <label class="estimate-option estimate-option--small">

                <input
                    type="radio"
                    name="products"
                    value="50"
                >

                <span class="estimate-option__content">

                    <strong>26–50</strong>

                    <small>
                        Products
                    </small>

                </span>

            </label>


            <label class="estimate-option estimate-option--small">

                <input
                    type="radio"
                    name="products"
                    value="100"
                >

                <span class="estimate-option__content">

                    <strong>51–100</strong>

                    <small>
                        Products
                    </small>

                </span>

            </label>


            <label class="estimate-option estimate-option--small">

                <input
                    type="radio"
                    name="products"
                    value="custom"
                >

                <span class="estimate-option__content">

                    <strong>100+</strong>

                    <small>
                        Custom Quote
                    </small>

                </span>

            </label>

        </div>

    </div>

</section>


        <!-- =====================================
            STEP 3 — DOMAIN & HOSTING
        ====================================== -->

        <section class="estimate-section">

            <div class="estimate-section__header">

                <span class="estimate-step">03</span>

                <div>
                    <h2>Domain & Hosting</h2>

                    <p>
                        Domain registration and web hosting are provided
                        through a third-party hosting provider and can be
                        set up for you.
                    </p>
                </div>

            </div>


            <div class="estimate-options estimate-options--3">

                <!-- ALREADY HAVE DOMAIN & HOSTING -->

                <label class="estimate-option">

                    <input
                        type="radio"
                        name="domain_hosting"
                        value="existing"
                    >

                    <span class="estimate-option__content">

                        <strong class="estimate-option__title">
                            I Have Domain & Hosting

                            <span
                                class="estimate-tooltip"
                                tabindex="0"
                                aria-label="Hosting compatibility information"
                            >
                                ?

                                <span class="estimate-tooltip__content">
                                    If your current hosting environment requires
                                    advanced server or cloud configuration,
                                    additional setup fees may apply.
                                </span>

                            </span>
                        </strong>

                        <small>
                            WordPress compatible
                        </small>

                        <span class="estimate-option__price">
                            $0 / year
                        </span>

                    </span>

                </label>


                <!-- 4HD SETUP -->

                <label class="estimate-option">

                    <input
                        type="radio"
                        name="domain_hosting"
                        value="managed"
                    >

                    <span class="estimate-option__content">

                        <strong>I Need Domain & Hosting</strong>

                        <small>
                            We'll help set up your domain and hosting
                            through a third-party provider.
                        </small>

                        <span class="estimate-option__price">
                            $199 / year
                        </span>

                    </span>

                </label>


                <!-- NOT SURE -->

                <label class="estimate-option">

                    <input
                        type="radio"
                        name="domain_hosting"
                        value="unsure"
                    >

                    <span class="estimate-option__content">

                        <strong>I'm Not Sure</strong>

                        <small>
                            We'll review what you currently have and
                            help determine what you need.
                        </small>

                        <span class="estimate-option__price">
                            To Be Determined
                        </span>

                    </span>

                </label>

            </div>

        </section>

<!-- =====================================
     STEP 4 — WEBSITE MAINTENANCE
====================================== -->

<section class="estimate-section">

    <div class="estimate-section__header">

        <span class="estimate-step">04</span>

        <div>
            <h2>Website Maintenance</h2>
            <p>
                Choose how you'd like your website to be managed after launch.
            </p>
        </div>

    </div>


    <div class="estimate-options estimate-options--4">

        <!-- SELF MANAGED -->

        <label class="estimate-option">

            <input
                type="radio"
                name="maintenance"
                value="self"
            >

            <span class="estimate-option__content">

                <strong>Self Managed</strong>

                <small>
                    You'll manage updates and website maintenance yourself.
                </small>

                <span class="estimate-option__price">
                    $0 / month
                </span>

            </span>

        </label>


        <!-- WEBSITE CARE -->

        <label class="estimate-option">

            <input
                type="radio"
                name="maintenance"
                value="care"
            >

            <span class="estimate-option__content">

                <strong>Website Care</strong>

                <small>
                    Updates, backups, security checks and basic
                    technical maintenance.
                </small>

                <span class="estimate-option__price">
                    $49 / month
                </span>

            </span>

        </label>


        <!-- WEBSITE CARE PLUS -->

        <label class="estimate-option">

            <input
                type="radio"
                name="maintenance"
                value="care_plus"
            >

            <span class="estimate-option__content">

                <strong>Website Care Plus</strong>

                <small>
                    Everything in Website Care plus up to 1 hour
                    of small content changes each month.
                </small>

                <span class="estimate-option__price">
                    $99 / month
                </span>

            </span>

        </label>


        <!-- E-COMMERCE CARE -->

        <label class="estimate-option">

            <input
                type="radio"
                name="maintenance"
                value="ecommerce"
            >

            <span class="estimate-option__content">

                <strong>E-Commerce Care</strong>

                <small>
                    Maintenance and support for WooCommerce
                    stores and online sales functionality.
                </small>

                <span class="estimate-option__price">
                    $149 / month
                </span>

            </span>

        </label>

    </div>

</section>


        <!-- =====================================
             STEP 5 — PROJECT DETAILS
        ====================================== -->

        <section class="estimate-section">

            <div class="estimate-section__header">

                <span class="estimate-step">05</span>

                <div>
                    <h2>Tell us about your project</h2>

                    <p>
                        We'll use this information to prepare your final quote.
                    </p>
                </div>

            </div>


            <div class="estimate-form-grid">

                <div class="estimate-field">

                    <label for="estimate-name">
                        Your Name <span class="estimate-required">*</span>
                    </label>

                    <input
                        id="estimate-name"
                        type="text"
                        name="name"
                        required
                    >

                </div>


                <div class="estimate-field">

                    <label for="estimate-business">
                        Business Name
                    </label>

                    <input
                        id="estimate-business"
                        type="text"
                        name="business"
                    >

                </div>


                <div class="estimate-field">

                    <label for="estimate-email">
                         Email <span class="estimate-required">*</span>
                    </label>

                    <input
                        id="estimate-email"
                        type="email"
                        name="email"
                        required
                    >

                </div>


                <div class="estimate-field">

                    <label for="estimate-phone">
                        Phone
                    </label>

                    <input
                        id="estimate-phone"
                        type="tel"
                        name="phone"
                    >

                </div>


                <div class="estimate-field estimate-field--full">

                    <label for="estimate-current-website">
                        Current Website
                        <span>(optional)</span>
                    </label>

                    <input
                        id="estimate-current-website"
                        type="url"
                        name="current_website"
                        placeholder="https://"
                    >

                </div>


                <div class="estimate-field estimate-field--full">

                    <label for="estimate-description">
                        Tell us about your project
                         <span class="estimate-required">*</span>
                    </label>

                    <textarea
                        id="estimate-description"
                        name="description"
                        rows="6"
                        required
                        placeholder="Tell us about your business, goals and what you would like your website to accomplish."
                    ></textarea>

                </div>

            </div>

        </section>


        <!-- =====================================
             ESTIMATE
        ====================================== -->

        <!-- =====================================
     PRELIMINARY ESTIMATE
====================================== -->

<section class="estimate-result">

    <span class="estimate-result__label">
        Preliminary Estimate
    </span>


    <!-- WEBSITE PROJECT -->

    <div class="estimate-summary">

        <div class="estimate-summary__item">

            <div class="estimate-summary__info">
                <strong>Website Project</strong>
                <small>One-time project estimate</small>
            </div>

            <div
                class="estimate-summary__price"
                id="website-estimate-price"
            >
                Select your options
            </div>

        </div>


        <!-- WEBSITE CARE -->

        <div class="estimate-summary__item">

            <div class="estimate-summary__info">
                <strong>Website Care</strong>
                <small>Optional monthly maintenance</small>
            </div>

            <div
                class="estimate-summary__price"
                id="website-maintenance-price"
            >
                Not selected
            </div>

        </div>


        <!-- DOMAIN + HOSTING -->

        <div class="estimate-summary__item">

            <div class="estimate-summary__info">
                <strong>Domain + Hosting</strong>
                <small>Annual hosting and domain service</small>
            </div>

            <div
                class="estimate-summary__price"
                id="website-hosting-price"
            >
                Not selected
            </div>

        </div>

    </div>


    <p class="estimate-result__disclaimer">
        This is a preliminary estimate based on your selections.
        Final pricing will be confirmed after reviewing your
        project requirements.
    </p>


    <button
        type="submit"
        class="estimate-submit"
    >
        Request My Quote
    </button>

</section>

    </form>

</main>

<?php
get_footer();