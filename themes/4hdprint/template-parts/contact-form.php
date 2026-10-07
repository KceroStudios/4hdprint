<?php
if (!defined('ABSPATH')) {
    exit;
}

$contact_status = isset($_GET['contact'])
    ? sanitize_key(wp_unslash($_GET['contact']))
    : '';
?>

<?php if ($contact_status === 'success') : ?>

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