# 4HD PRINT — Technical Documentation

Technical documentation for the custom **4HD PRINT WordPress theme**.

This document describes the architecture, custom functionality, implementation decisions, and important development considerations of the project.

The documentation is maintained progressively as each area of the website is reviewed and finalized.


# Website Estimator & Quote Management

## Overview

The Website Estimator is a custom lead-generation and project-estimation system developed for the Web Solutions section of the 4HD PRINT website.

It allows potential customers to configure a website project, receive a preliminary project estimate, and submit their project information to 4HD PRINT for review.

The estimator is not a WooCommerce product and does not create an order or collect payment.

Its purpose is to:

- Guide customers through the initial website planning process.
- Provide transparent preliminary pricing.
- Collect structured project requirements.
- Reduce unnecessary back-and-forth before the initial consultation.
- Generate qualified website project leads.
- Store quote requests inside WordPress for administrative follow-up.

Final pricing is determined by 4HD PRINT after reviewing the submitted project requirements.

## Main Components

The system currently consists of:

- Website Estimator frontend.
- JavaScript estimate calculator.
- Server-side PHP validation and price calculation.
- Quote request email notification.
- WordPress Website Quotes management area.
- Quote status management.
- Anti-spam and request rate limiting.
- Security validation and sanitization.

## Main Files

The estimator currently uses the following theme files:

- `page-website-estimate.php`
- `assets/css/website-estimate.css`
- `assets/js/website-estimate.js`
- `functions.php`

### `page-website-estimate.php`

Contains the frontend estimator interface and project request form.

### `assets/css/website-estimate.css`

Contains styles specific to the estimator interface.

### `assets/js/website-estimate.js`

Controls frontend estimator behavior and provides the customer with a real-time preliminary estimate.

### `functions.php`

Handles server-side processing, validation, independent price calculation, quote storage, email notifications, and WordPress administration functionality related to Website Quotes.

## Estimator Workflow

The Website Estimator separates the customer-facing estimate from the authoritative server-side calculation.

The general request flow is:

1. The customer selects a website package and project options.
2. JavaScript updates the preliminary estimate in real time.
3. The customer enters their contact information and project description.
4. The completed form is submitted to WordPress using POST.
5. PHP validates and sanitizes the submitted data.
6. The project price is independently recalculated on the server.
7. The quote request is stored in WordPress as a Website Quote.
8. An HTML email notification is sent to the website administrator.
9. The customer is redirected back to the estimator with a success or error status.

### Client-Side Calculation

`assets/js/website-estimate.js` provides immediate pricing feedback while the customer configures the project.

The JavaScript calculation is used only for the user interface and must never be considered authoritative.

A customer can potentially modify JavaScript, HTML form values, or POST data through browser development tools.

For this reason, prices submitted by the browser are not trusted.

### Server-Side Calculation

When the form is submitted, PHP independently rebuilds the project estimate using the submitted package and selected options.

Only predefined and validated values are accepted.

This ensures that modifying the frontend calculation does not allow a customer to manipulate the project price stored by the system.

### Quote Processing

After validation and price calculation, the request is stored in WordPress and an email notification is generated for administrative review.

The stored Website Quote becomes the internal record used by 4HD PRINT to review the project and continue communication with the customer.

The estimate remains preliminary until the project requirements are reviewed and a final quote is prepared by 4HD PRINT.

## Packages & Pricing Logic

The Website Estimator currently supports three website project types:

- Starter Website
- Business Website
- Online Store

Each package has a base project price and a predefined scope. Additional functionality may increase the preliminary estimate.

Removing functionality already included in a package does not reduce its base price.

The pricing displayed by JavaScript is duplicated and independently enforced by the server-side PHP calculation. Any pricing change must therefore be reviewed in both locations to keep the frontend estimate and server-side calculation synchronized.

### Starter Website

**Base Price:** $450

Designed as an entry-level, template-based website for customers who need a simple professional web presence.

Included:

- 1 page
- Responsive design
- Contact form
- Social media links
- Basic SEO
- WordPress administration
- Basic website configuration

Additional pages:

- 1 page: Included
- 2–3 pages: +$200
- 4–5 pages: +$400
- 6–10 pages: +$750
- More than 10 pages: Custom Quote

Optional functionality:

- Gallery: +$100
- Maps: +$50
- Quote Form: +$150
- Employment Form: +$150
- File Upload: +$75 when used with a Quote or Employment Form
- Blog: +$150
- Newsletter: +$100
- Booking: +$250
- Calculator: Starting at +$300
- Multilingual Website: Starting at +$250

### Business Website

**Base Price:** $1,500

Designed for businesses requiring a more complete website with additional content and customer interaction.

Included:

- 3–5 pages
- Responsive design
- WordPress administration
- Basic SEO
- Contact form
- Gallery with up to 20 images
- Maps with up to 2 locations
- Social media links
- One standard custom form
- Basic file upload when used as part of the included standard custom form

A standard custom form can be either:

- Quote Form
- Employment Form

If both standard forms are selected, the first form is included and the second adds $150 to the estimate.

Additional pages:

- Up to 5 pages: Included
- 6–10 pages: +$400
- More than 10 pages: Custom Quote

Optional functionality:

- Second Standard Custom Form: +$150
- Booking: +$250
- Blog: +$150
- Newsletter: +$100
- Calculator: Starting at +$300
- Multilingual Website: Starting at +$250

### Online Store

**Base Price:** $2,500

Designed for businesses that need an e-commerce website powered by WooCommerce.

Included:

- Up to 5 informational pages
- WooCommerce configuration
- Shop and product pages
- Cart and checkout
- Up to 10 products configured
- Simple products and basic product variations
- Standard payment gateway configuration
- Customer accounts
- Order management
- WooCommerce transactional emails
- Shipping configuration
- Local Pickup
- Basic tax configuration
- Contact form
- Maps
- Social media links
- Responsive design
- Basic SEO
- WordPress and WooCommerce administration

Additional informational pages:

- Up to 5 pages: Included
- 6–10 pages: +$400
- More than 10 pages: Custom Quote

Additional product configuration:

- Up to 10 products: Included
- 11–25 products: +$250
- 26–50 products: +$500
- 51–100 products: +$900
- More than 100 products: Custom Quote

Optional functionality:

- Gallery: +$100
- Blog: +$150
- Newsletter: +$100
- Quote Form: +$150
- Employment Form: +$150
- File Upload: +$75 when used with a Quote or Employment Form
- Booking: +$250
- Calculator: Starting at +$300
- Multilingual Website: Starting at +$250

Standard e-commerce functionality such as the shop, cart, checkout, order management, shipping, and Local Pickup is part of the Online Store package and should not be treated as optional paid extras.

### Custom Quote Conditions

The estimator switches the Website Project estimate to **Custom Quote** when the requested scope exceeds predefined pricing ranges.

Current Custom Quote conditions include:

- More than 10 website pages.
- More than 100 products for an Online Store.

Additional complex functionality may also require manual evaluation even when the estimator provides a preliminary numerical estimate.

### Pricing Maintenance

Pricing exists in both the frontend estimator and the server-side processing logic.

When package pricing or optional feature pricing changes:

1. Update the pricing configuration in `assets/js/website-estimate.js`.
2. Update the corresponding server-side calculation in `functions.php`.
3. Test the same configuration on both sides.
4. Confirm that the displayed estimate matches the estimate stored in Website Quotes.
5. Update this documentation if the package scope or pricing has changed.

The server-side PHP calculation is always considered authoritative.

## Quote Storage & Administration

Website quote requests are stored inside WordPress to provide 4HD PRINT with a persistent administrative record of each submitted project.

This allows quote requests to be reviewed and managed independently from email notifications.

### Website Quote Post Type

Quote requests are stored using the custom post type:

`fourhd_web_quote`

The post type is used internally through the WordPress administration area and is not intended to create public frontend pages.

Each successful estimator submission creates a new Website Quote record.

The customer name is used as the WordPress post title to make requests easy to identify in the administration screen.

### Stored Quote Data

Project information is stored as post metadata associated with the Website Quote.

The stored information includes:

- Customer name
- Business name
- Email address
- Phone number
- Current website URL
- Website package
- Number of pages
- Product range for Online Store projects
- Selected features
- Domain and hosting selection
- Website maintenance selection
- Project description
- Calculated project estimate
- Custom Quote status
- Internal quote status

This stored data represents the server-validated version of the request.

### WordPress Administration

Website Quotes are available through the WordPress administration area under **Website Quotes**.

The quote list provides a simplified overview using administrative columns for:

- Customer
- Website package
- Project estimate
- Quote status
- Submission date

Opening a Website Quote displays the complete project information submitted through the estimator.

### Quote Status

Each Website Quote has an internal workflow status.

Available statuses are:

- New
- Contacted
- Quoted
- Accepted
- Closed

New submissions are automatically assigned the **New** status.

The status can later be changed by an administrator as the customer moves through the sales process.

These statuses are internal management tools and do not automatically send messages or modify the customer's project.

### Administrative Access

Website Quote management is restricted to users with the WordPress `manage_options` capability.

This is intended to limit access to customer contact information and project details to website administrators.

Quote status changes are also protected by WordPress capability checks and nonce verification.

### Email Notifications

After a valid quote request is processed, the system sends an HTML email notification to the WordPress administrator email address.

The email contains the relevant customer information, project configuration, selected services, preliminary estimate, and project description.

The email notification is not the primary storage mechanism.

The Website Quote stored in WordPress remains the internal project record even if email delivery fails.

### Data Retention

Website Quotes may contain personally identifiable information such as names, email addresses, phone numbers, and project information.

A formal retention and deletion policy should be established before production use.

Quote records should not be retained indefinitely unless there is a legitimate business reason to keep them.

## Security & Anti-Spam

The Website Estimator accepts public input from visitors and therefore treats all submitted data as untrusted.

Security controls are applied before quote data is stored, used for pricing, displayed in WordPress, or included in administrative communications.

### CSRF Protection

The estimator form uses a WordPress nonce.

The nonce is generated with `wp_nonce_field()` and verified server-side with `wp_verify_nonce()` before the request is processed.

This helps prevent unauthorized cross-site form submissions.

### Input Validation and Sanitization

Customer information is sanitized using appropriate WordPress functions before it is processed or stored.

Examples include:

- `sanitize_text_field()` for standard text fields.
- `sanitize_textarea_field()` for the project description.
- `sanitize_email()` for email addresses.
- `esc_url_raw()` for submitted website URLs.
- `sanitize_key()` for predefined estimator selections.

Scalar checks are performed before processing fields expected to contain a single value.

Maximum input lengths are also enforced server-side to prevent unexpectedly large submissions.

### Allowed Values

Estimator selections are validated against predefined allowed values.

This includes:

- Website packages
- Page ranges
- Product ranges
- Hosting selections
- Maintenance selections
- Optional features

Unexpected values are rejected or removed before the request is processed.

This prevents arbitrary form values from being trusted simply because they were submitted by the browser.

### Server-Side Pricing

The browser does not determine the authoritative project price.

PHP independently recalculates the project estimate after validating the submitted configuration.

This protects the pricing system against users modifying JavaScript, HTML values, or POST data through browser development tools.

### Output Escaping

Stored customer and project information is escaped when displayed in the WordPress administration interface.

Appropriate WordPress escaping functions are used depending on the output context.

This reduces the risk of stored Cross-Site Scripting (XSS).

Basic malicious-input testing was performed during development using script tags, HTML elements, and event-handler payloads. The tested input was displayed as data rather than executed as browser code.

### Honeypot Protection

The public estimator includes a hidden honeypot field.

Normal visitors should never populate this field.

If the honeypot contains a value, the request is treated as automated spam and is not stored or emailed.

The system returns a normal-looking response instead of revealing that the anti-spam mechanism was triggered.

### Minimum Submission Time

The estimator records when the form was generated.

Submissions occurring unrealistically quickly, or containing an invalid future form time, are treated as automated requests.

These requests are silently rejected without creating a Website Quote or sending an email.

### Rate Limiting

Valid quote submissions are rate limited by client IP address.

The current limit is:

**5 accepted quote requests per IP address within approximately 15 minutes.**

The IP address is hashed before being used as part of the temporary WordPress rate-limit key.

When the limit is exceeded, additional requests are not stored or emailed.

The rate limiter currently uses `REMOTE_ADDR`.

If the production website is later placed behind a CDN, reverse proxy, or similar infrastructure, the IP detection strategy must be reviewed before trusting forwarded client-IP headers.

### Administrative Protection

Website Quotes contain customer contact information and are restricted to WordPress administrators through the `manage_options` capability.

Quote status changes additionally require:

- Appropriate WordPress permissions.
- A valid WordPress nonce.
- A predefined allowed status.

### Security Testing

The following protections were manually tested during development:

- Normal estimator submission.
- Server-side pricing for all three website packages.
- Malicious HTML and JavaScript input.
- Honeypot rejection.
- Minimum submission-time rejection.
- Rate-limit blocking.
- Website Quote administrative access.
- Quote status updates.

Normal estimator functionality was retested after the security changes.

### Production Considerations

Before production deployment, security should be reviewed again in the production environment.

Particular attention should be given to:

- WordPress, WooCommerce, theme, and plugin updates.
- Administrator account security and least-privilege access.
- HTTPS configuration.
- Production email delivery.
- Backups and restoration procedures.
- Debug settings and error visibility.
- Plugin and dependency hygiene.
- Customer data retention.
- Server and hosting security.
- CDN or proxy behavior if introduced.
- File upload restrictions if additional upload functionality is implemented.

Security documentation should be updated whenever a new public form, upload mechanism, external integration, authentication feature, or sensitive-data workflow is added.

---------------------------------------------------
---------------------------------------------------
---------------------------------------------------


# Header

## Header Overview

The site header is a global theme component displayed across the website. It provides quick contact and social links, primary site navigation, WooCommerce account access, cart access, sticky navigation behavior, and responsive mobile navigation.

The header is divided into two primary areas:

1. A top bar containing social, contact, location, phone, and customer account controls.
2. A main navigation bar containing the site logo, WordPress navigation menu, and WooCommerce cart access.

The component adapts its behavior for desktop, scrolling, and mobile layouts.

## Header Main Files

The header component is primarily implemented through:

- `header.php` — Header markup, top bar, WordPress navigation, WooCommerce account menu, and site logo.
- `assets/css/components/header.css` — Header-specific layout, top bar, account dropdown, navigation, sticky state, and mobile styles.
- `assets/js/main.js` — Responsive header behavior, sticky navigation, mobile menu toggle, and account dropdown interaction.
- `functions.php` — Loads the global header stylesheet and other theme assets.

Global variables, typography, shared containers, and other site-wide styles remain in `assets/css/main.css`.

## Top Bar

The top bar provides quick access to:

- Instagram
- Facebook
- Email
- Business location
- WhatsApp
- Phone
- Customer account

All top-bar controls use the shared `.social-item` structure to maintain consistent icon sizing, spacing, hover behavior, and alignment.

Spacing between controls is managed by the parent `.social_container` using `gap`, rather than individual icon margins. This prevents neighboring controls from shifting when icon hover animations are applied.

## Main Navigation

The main navigation contains the 4HD PRINT logo and the WordPress menu assigned to the `main_menu` theme location.

The logo uses theme image assets and changes appearance on hover.

WooCommerce cart access is included as part of the main navigation and receives dedicated styling for desktop and mobile layouts.

## Account Menu

The top bar includes a customer account control integrated with WooCommerce.

For authenticated customers, the dropdown provides access to:

- Dashboard
- Orders
- Downloads
- Addresses
- Account Details
- Log Out

For visitors who are not authenticated, the dropdown provides:

- Log In
- Create Account

WooCommerce account URLs are generated dynamically instead of being hard-coded. The logout URL is also generated dynamically by WooCommerce so the required WordPress security nonce is included.

JavaScript controls the dropdown state by adding or removing the `.is-open` class. The menu can be closed by clicking outside the account component or by pressing the `Escape` key.

Customer accounts are optional. The site is intended to continue supporting WooCommerce guest checkout.

## Sticky Navigation

The main navigation becomes fixed after the visitor scrolls approximately 110 pixels down the page.

JavaScript adds the `.scroll-menu` class to `.menu_container` when the scroll threshold is reached and removes it when the visitor returns above the threshold.

While the sticky state is active, the logo is slightly reduced in size to create a more compact navigation bar.

## Mobile Navigation

For viewport widths up to 768 pixels, JavaScript adds the `.mobile` class to `.menu_container`.

The desktop navigation is then presented as a mobile off-canvas menu controlled by the hamburger button.

The mobile panel:

- Uses a dark background consistent with the site header.
- Slides into view from the right.
- Uses vertically arranged navigation links.
- Provides mobile-specific cart styling.
- Keeps the logo and menu toggle aligned within the main navigation bar.

The `.open` class controls whether the mobile navigation panel is visible.

---------------------------------------------------
---------------------------------------------------
---------------------------------------------------

## Homepage Banner

### Overview

The Homepage Banner is a configurable hero section displayed on the website homepage.

Banner content and appearance can be managed from the WordPress administration area without modifying theme files.

### Main Files

- `front-page.php` — Renders the banner on the homepage.
- `functions.php` — Registers the banner settings, admin interface, sanitization, and WordPress Media Library integration.
- `assets/css/components/homepage.css` — Contains homepage-specific styles, including the Homepage Banner.

### Banner Settings

The administrator can configure:

- Enable or disable the banner.
- Banner title.
- Banner description.
- Button text and URL.
- Main banner image.
- Show or hide the main image.
- Background style.
- Background image.

### Background Styles

The banner supports predefined visual styles:

- Dark
- Orange
- Light
- Dark Gradient
- Orange Gradient
- Background Image

Using predefined styles keeps the banner consistent with the theme's design system and CSS variables.

When `Background Image` is selected, an independent image can be chosen through the WordPress Media Library.

### Image Behavior

The banner uses two independent image settings:

**Main Image**

Displayed as part of the banner content and can be enabled or disabled using the `Show Main Image` setting.

**Background Image**

Used as the background of the entire banner when the `Background Image` style is selected.

The admin interface only displays the Background Image controls when they are relevant.

### Data Handling

Banner settings are stored in the WordPress option:

`4hd_homepage_banner`

Input values are sanitized before being stored.

Background style values are restricted to an allowlist of supported styles, and image values are stored as WordPress attachment IDs.

---------------------------------------------------
---------------------------------------------------
---------------------------------------------------


## Our Services

### Overview

The Our Services section provides direct access to the main service categories offered by 4HD PRINT.

The section is currently defined directly in `front-page.php` and is not managed through a WordPress admin component.

### Main Files

- `front-page.php` — Contains the section structure and service category links.
- `assets/css/components/homepage.css` — Contains the layout, card, hover, and responsive styles.

### Service Categories

The section currently displays:

- Print Services
- Promotional Products
- Graphic Design
- Web Solutions

Theme images use `get_template_directory_uri()` instead of hardcoded local URLs, allowing the section to work correctly across development and production environments.

### Responsive Behavior

The section uses a responsive CSS Grid layout.

- Desktop displays four service cards.
- Tablet displays two columns while preserving the full card content.
- Mobile displays a compact two-column layout with only the service icon and title.
- On mobile, the entire service card is clickable.
- Desktop and tablet use the `View More` button for navigation.

---------------------------------------------------
---------------------------------------------------
---------------------------------------------------

## Featured Products

### Overview

The Featured Products section displays a selection of WooCommerce products marked as featured.

Products are loaded dynamically from WooCommerce, allowing the store administrator to control which products appear on the homepage without modifying theme files.

### Main Files

- `front-page.php` — Queries and renders featured WooCommerce products.
- `assets/css/components/homepage.css` — Contains the section layout, product cards, buttons, and responsive styles.

### Product Selection

The section uses a WordPress query to retrieve:

- Published WooCommerce products.
- Products marked as featured.
- A maximum of four products.

If no featured products are available, the section is not displayed.

### Product Information

Each product card displays:

- Product image.
- Product title.
- WooCommerce price.
- Link to the individual product page.

A `View All Products` button links to the main WooCommerce Shop page.

### Responsive Behavior

The section uses a responsive CSS Grid layout.

- Desktop displays four products per row.
- Tablet displays two products per row.
- Mobile maintains a compact two-column layout.
- Product cards and controls are resized for smaller screens.

---------------------------------------------------
---------------------------------------------------
---------------------------------------------------

## Promotional Banner

### Overview

The Promotional Banner is a configurable homepage section used to highlight promotions, special offers, or important marketing messages.

The banner can be managed from the WordPress administration area without modifying theme files.

### Main Files

- `front-page.php` — Renders the Promotional Banner on the homepage.
- `functions.php` — Handles the banner settings, administration interface, sanitization, and image selection.
- `assets/css/components/homepage.css` — Contains the banner layout and responsive styles.

### Banner Settings

The administrator can configure:

- Enable or disable the banner.
- Banner image.
- Title.
- Description.
- Promotional offer.
- Button text.
- Button URL.

### Display Behavior

The Promotional Banner is only rendered when it is enabled.

Individual elements such as the title, description, offer, button, and image are only displayed when their corresponding values are available.

### Responsive Behavior

The banner uses a horizontal layout on larger screens, with promotional content and an image displayed side by side.

On mobile devices, the layout changes to a vertical arrangement with the content displayed above the image.

---------------------------------------------------
---------------------------------------------------
---------------------------------------------------

## Client Logos

### Overview

The Client Logos section displays a continuously scrolling carousel of client logos on the homepage.

Logos are managed through WordPress and are rendered dynamically from the `4hd_client_logos` option.

### Main Files

- `front-page.php` — Retrieves and renders the client logos.
- `functions.php` — Handles the administration and storage of client logo settings.
- `assets/css/components/homepage.css` — Contains the carousel layout, animation, hover effects, and responsive styles.


### Carousel Behavior

The logo collection is rendered twice inside the carousel track to create a continuous scrolling animation.

The carousel:

- Scrolls automatically in a continuous loop.
- Pauses when the user hovers over it.
- Displays logos in grayscale by default.
- Restores the original logo colors on hover.

### Image Handling

Logo images are stored as WordPress attachment IDs.

Invalid or empty image entries are skipped before rendering.

Images are generated using the WordPress attachment system rather than hardcoded image URLs.

### Responsive Behavior

On mobile devices:

- The carousel uses the full available width.
- Logo dimensions and spacing are reduced.
- The section header uses a smaller font size.

---------------------------------------------------
---------------------------------------------------
---------------------------------------------------

## Homepage Pre-Footer

The homepage includes a custom pre-footer section rendered from `footer.php`.

This section is displayed only on the front page and contains:

- Latest Video
- Social Media links
- Information links
- Contact Form

The Contact Form is implemented as a shared theme component and is also used on the Contact Us page.

See the Contact Form section for implementation, security, email delivery, and styling details.

---------------------------------------------------
---------------------------------------------------
---------------------------------------------------

## About Us Page

### Overview

The About Us page presents the company introduction, philosophy, and team information.

### Main Files

- `page-about-us.php` — Page template and content structure.
- `assets/css/components/about-us.css` — Page-specific styles and responsive layout.

### Structure

The page is divided into three main sections:

- **Introduction** — Company overview with an image integrated into the text.
- **Our Philosophy** — Mission, Vision, and company Values.
- **Our Team** — Team member cards with short biographies and background/skill tags.

### Responsive Behavior

The About Us layout adapts for mobile devices:

- The introduction image moves above the text.
- Mission and Vision change from two columns to a single-column layout.
- Values change to a single-column list.
- Team cards stack vertically.

### Stylesheet Loading

`about-us.css` is loaded only on the `about-us` page using the WordPress conditional `is_page('about-us')`.


---------------------------------------------------
---------------------------------------------------
---------------------------------------------------


## Contact Form

The contact form is a reusable custom theme component shared by the Homepage and Contact Us page. It does not depend on WPForms.

### Main Files

- `template-parts/contact-form.php` — Shared contact form markup.
- `assets/css/components/contact-form.css` — Shared contact form styles.
- `assets/css/components/contact.css` — Contact Us page-specific layout and form variations.
- `functions.php` — Form processing, validation, security, email delivery, settings, and stylesheet loading.

### Usage

The shared form is loaded using:

`get_template_part('template-parts/contact-form');`

It is currently used on:

- Homepage
- Contact Us

The Contact Us page provides its own visual layout, responsive behavior, and Google Maps section while reusing the same form processing system.

### Form Fields

The form collects:

- Name
- Email
- Phone
- Service
- Message

Form submissions are processed by the theme through `four_hd_handle_contact_form()`.

### Recipient Email

The recipient email can be configured from:

**WordPress Admin → Appearance → Contact Form**

The setting is stored in the `4hd_contact_form_settings` WordPress option.

If no recipient email is configured, the WordPress administration email is used as a fallback.

### Security

The shared form includes:

- WordPress nonce verification
- Honeypot spam protection
- Input sanitization
- Required field validation
- Email validation
- Service allowlist validation
- Input length limits
- Rate limiting
- Post/Redirect/Get behavior

Rate limiting currently allows a maximum of **5 valid submissions per IP address within 15 minutes**.

### Email Delivery

Messages are sent using WordPress `wp_mail()`.

The visitor's email address is added as the `Reply-To` address so the recipient can reply directly to the customer.

A successful `wp_mail()` result indicates that WordPress accepted the message for delivery. Actual delivery depends on the server's mail configuration.

### Front-End Feedback

The form can display:

- Successful submission
- Email sending error
- Rate limit warning

### Styling

Shared contact form styles are located in:

`assets/css/components/contact-form.css`

Contact Us page-specific layout and form variations are located in:

`assets/css/components/contact.css`

Homepage-specific layout remains in:

`assets/css/components/homepage.css`

---------------------------------------------------
---------------------------------------------------
---------------------------------------------------

## Contact Us Page

### Overview

The Contact Us page provides a dedicated contact experience for visitors who want to ask questions, discuss a project, or request information about 4HD PRINT services.

The page reuses the shared Contact Form component instead of maintaining a separate form implementation.

### Main Files

- `page-contact-us.php` — Contact Us page structure and Google Maps embed.
- `assets/css/components/contact.css` — Contact Us page-specific layout and responsive styles.
- `template-parts/contact-form.php` — Shared contact form markup.
- `assets/css/components/contact-form.css` — Shared contact form styles.
- `functions.php` — Loads the Contact Us stylesheet and handles contact form processing.

### Page Structure

The page contains three primary elements:

- Visual panel using the 4HD PRINT contact artwork.
- Shared Contact Form.
- Embedded Google Map.

The visual panel uses:

`assets/images/contact.png`

The Contact Us page applies its own visual treatment to the shared form without modifying the form styles used on the Homepage.

### Responsive Behavior

On larger screens, the visual panel and contact form are displayed side by side.

On mobile devices:

- The visual panel and form stack vertically.
- Form fields use a single-column layout.
- The Google Map remains contained within the shared site width.
- Page spacing and typography are adjusted for smaller screens.

### Stylesheet Loading

`contact.css` is loaded only on the Contact Us page using:

`is_page('contact-us')`

The shared `contact-form.css` stylesheet remains available wherever the reusable Contact Form component is displayed.