document.addEventListener('DOMContentLoaded', function () {

    /* =========================================
       ELEMENTS
    ========================================= */

    const packageInputs = document.querySelectorAll(
        'input[name="website_type"]'
    );

    const pageInputs = document.querySelectorAll(
        'input[name="pages"]'
    );

    const productInputs = document.querySelectorAll(
        'input[name="products"]'
    );

    const featureInputs = document.querySelectorAll(
        'input[name="features[]"]'
    );

    const storeOptions = document.getElementById(
        'estimate-store-options'
    );

    const estimatePrice = document.getElementById(
        'website-estimate-price'
    );

    const businessFormMessage = document.getElementById(
        'business-form-message'
    );

    const businessFormBadge = document.getElementById(
        'business-form-badge'
    );

    const standardFormInputs = document.querySelectorAll(
        'input[value="quote_form"], input[value="employment_form"]'
    );

    let currentPackage = '';


    /* =========================================
       PACKAGE CONFIGURATION
    ========================================= */

    const packages = {

        starter: {
            basePrice: 450,
            included: [
                'contact',
                'social'
            ],
            defaultPages: '1',
            showStore: false
        },

        business: {
            basePrice: 1500,
            included: [
                'contact',
                'gallery',
                'maps',
                'social'
            ],
            defaultPages: '5',
            showStore: false
        },

        store: {
            basePrice: 2500,
            included: [
                'contact',
                'maps',
                'social'
            ],
            defaultPages: '5',
            showStore: true
        }

    };


    /* =========================================
       RESET FEATURES
    ========================================= */

    function resetFeatures() {

        featureInputs.forEach(function (input) {

            input.checked = false;

            const feature = input.closest(
                '.estimate-feature'
            );

            feature.classList.remove(
                'is-included'
            );

            const oldBadge = feature.querySelector(
                '.estimate-feature__status'
            );

            if (oldBadge) {
                oldBadge.remove();
            }

        });

    }


    /* =========================================
       MARK FEATURE AS INCLUDED
    ========================================= */

    function markIncluded(input) {

        input.checked = true;

        const feature = input.closest(
            '.estimate-feature'
        );

        feature.classList.add(
            'is-included'
        );

        const oldBadge = feature.querySelector(
            '.estimate-feature__status'
        );

        if (oldBadge) {
            oldBadge.remove();
        }

        const badge = document.createElement(
            'small'
        );

        badge.className =
            'estimate-feature__status';

        badge.textContent =
            'Included';

        feature.appendChild(badge);

    }


    /* =========================================
       SELECT DEFAULT PAGES
    ========================================= */

    function selectDefaultPages(value) {

        const pageInput = document.querySelector(
            'input[name="pages"][value="' +
            value +
            '"]'
        );

        if (pageInput) {
            pageInput.checked = true;
        }

    }


    /* =========================================
       BUSINESS INCLUDED FORM
    ========================================= */

    function updateBusinessForms() {

        standardFormInputs.forEach(function (input) {

            const feature = input.closest(
                '.estimate-feature'
            );

            feature.classList.remove(
                'is-included'
            );

            const oldBadge = feature.querySelector(
                '.estimate-feature__status'
            );

            if (oldBadge) {
                oldBadge.remove();
            }

        });


        if (currentPackage !== 'business') {
            return;
        }


        const selectedForms = Array.from(
            standardFormInputs
        ).filter(function (input) {
            return input.checked;
        });


        if (selectedForms.length === 0) {
            return;
        }


        // First selected standard form is included.
        markIncluded(selectedForms[0]);

    }


    /* =========================================
       CALCULATE ESTIMATE
    ========================================= */

    function calculateEstimate() {

        if (!currentPackage) {
            return;
        }

        const config =
            packages[currentPackage];

        if (!config) {
            return;
        }

        let total = config.basePrice;


        const selectedPages =
            document.querySelector(
                'input[name="pages"]:checked'
            );


        /* -------------------------------------
           STARTER — PAGES
        ------------------------------------- */

        if (
            currentPackage === 'starter' &&
            selectedPages
        ) {

            switch (selectedPages.value) {

                case '1':
                    break;

                case '3':
                    total += 200;
                    break;

                case '5':
                    total += 400;
                    break;

                case '10':
                    total += 750;
                    break;

                case '10plus':
                    estimatePrice.textContent =
                        'Custom Quote';
                    return;
            }

        }


        /* -------------------------------------
           BUSINESS — PAGES
        ------------------------------------- */

        if (
            currentPackage === 'business' &&
            selectedPages
        ) {

            switch (selectedPages.value) {

                case '1':
                case '3':
                case '5':
                    break;

                case '10':
                    total += 400;
                    break;

                case '10plus':
                    estimatePrice.textContent =
                        'Custom Quote';
                    return;
            }

        }


        /* -------------------------------------
           ONLINE STORE — PAGES
        ------------------------------------- */

        if (
            currentPackage === 'store' &&
            selectedPages
        ) {

            switch (selectedPages.value) {

                case '1':
                case '3':
                case '5':
                    break;

                case '10':
                    total += 400;
                    break;

                case '10plus':
                    estimatePrice.textContent =
                        'Custom Quote';
                    return;
            }

        }


        /* -------------------------------------
           ONLINE STORE — PRODUCTS
        ------------------------------------- */

        if (currentPackage === 'store') {

            const selectedProducts =
                document.querySelector(
                    'input[name="products"]:checked'
                );

            if (selectedProducts) {

                switch (selectedProducts.value) {

                    case '10':
                        break;

                    case '25':
                        total += 250;
                        break;

                    case '50':
                        total += 500;
                        break;

                    case '100':
                        total += 900;
                        break;

                    case 'custom':
                        estimatePrice.textContent =
                            'Custom Quote';
                        return;
                }

            }

        }


        /* -------------------------------------
           STARTER — FEATURES
        ------------------------------------- */

        if (currentPackage === 'starter') {

            const starterFeaturePrices = {

                gallery: 100,
                maps: 50,
                blog: 150,
                newsletter: 100,

                quote_form: 150,
                employment_form: 150,

                booking: 250,
                calculator: 300,
                multilingual: 250

            };


            Object.entries(
                starterFeaturePrices
            ).forEach(function ([feature, price]) {

                const input =
                    document.querySelector(
                        'input[name="features[]"][value="' +
                        feature +
                        '"]'
                    );

                if (
                    input &&
                    input.checked
                ) {
                    total += price;
                }

            });


            /* File Upload */

            const fileUpload =
                document.querySelector(
                    'input[name="features[]"][value="upload"]'
                );

            const quoteForm =
                document.querySelector(
                    'input[name="features[]"][value="quote_form"]'
                );

            const employmentForm =
                document.querySelector(
                    'input[name="features[]"][value="employment_form"]'
                );

            const hasForm =
                (quoteForm && quoteForm.checked) ||
                (employmentForm && employmentForm.checked);


            if (
                fileUpload &&
                fileUpload.checked &&
                hasForm
            ) {
                total += 75;
            }

        }


        /* -------------------------------------
           BUSINESS — FEATURES
        ------------------------------------- */

        if (currentPackage === 'business') {

            const businessFeaturePrices = {

                booking: 250,
                blog: 150,
                newsletter: 100,
                calculator: 300,
                multilingual: 250

            };


            Object.entries(
                businessFeaturePrices
            ).forEach(function ([feature, price]) {

                const input =
                    document.querySelector(
                        'input[name="features[]"][value="' +
                        feature +
                        '"]'
                    );

                if (
                    input &&
                    input.checked
                ) {
                    total += price;
                }

            });


            /*
             * First standard form included.
             * Second standard form +$150.
             */

            const selectedStandardForms =
                Array.from(
                    standardFormInputs
                ).filter(function (input) {
                    return input.checked;
                });


            if (
                selectedStandardForms.length > 1
            ) {
                total += 150;
            }


            /*
             * File Upload is included when
             * attached to a standard form.
             */

        }


        /* -------------------------------------
           ONLINE STORE — FEATURES
        ------------------------------------- */

        if (currentPackage === 'store') {

            const storeFeaturePrices = {

                gallery: 100,
                blog: 150,
                newsletter: 100,

                quote_form: 150,
                employment_form: 150,

                booking: 250,
                calculator: 300,
                multilingual: 250

            };


            Object.entries(
                storeFeaturePrices
            ).forEach(function ([feature, price]) {

                const input =
                    document.querySelector(
                        'input[name="features[]"][value="' +
                        feature +
                        '"]'
                    );

                if (
                    input &&
                    input.checked
                ) {
                    total += price;
                }

            });


            /* File Upload */

            const fileUpload =
                document.querySelector(
                    'input[name="features[]"][value="upload"]'
                );

            const quoteForm =
                document.querySelector(
                    'input[name="features[]"][value="quote_form"]'
                );

            const employmentForm =
                document.querySelector(
                    'input[name="features[]"][value="employment_form"]'
                );

            const hasForm =
                (quoteForm && quoteForm.checked) ||
                (employmentForm && employmentForm.checked);


            if (
                fileUpload &&
                fileUpload.checked &&
                hasForm
            ) {
                total += 75;
            }

        }


        /* =====================================
           DISPLAY TOTAL
        ===================================== */

        estimatePrice.textContent =
            '$' + total.toLocaleString();

    }


    /* =========================================
       PACKAGE CHANGE
    ========================================= */

    packageInputs.forEach(function (input) {

        input.addEventListener(
            'change',
            function () {

                const selectedPackage =
                    this.value;

                const config =
                    packages[selectedPackage];

                currentPackage =
                    selectedPackage;


                if (!config) {
                    return;
                }


                /* Business form message */

                if (
                    selectedPackage ===
                    'business'
                ) {

                    if (businessFormMessage) {
                        businessFormMessage.style.display =
                            'block';
                    }

                    if (businessFormBadge) {
                        businessFormBadge.style.display =
                            'inline-block';
                    }

                } else {

                    if (businessFormMessage) {
                        businessFormMessage.style.display =
                            'none';
                    }

                    if (businessFormBadge) {
                        businessFormBadge.style.display =
                            'none';
                    }

                }


                /* Reset previous package */

                resetFeatures();


                /* Mark included features */

                config.included.forEach(
                    function (featureName) {

                        const featureInput =
                            document.querySelector(
                                'input[name="features[]"][value="' +
                                featureName +
                                '"]'
                            );

                        if (featureInput) {
                            markIncluded(
                                featureInput
                            );
                        }

                    }
                );


                /* Default pages */

                selectDefaultPages(
                    config.defaultPages
                );


                /* Store product options */

                if (storeOptions) {

                    if (config.showStore) {

                        storeOptions.style.display =
                            'block';

                        const firstProductOption =
                            storeOptions.querySelector(
                                'input[name="products"][value="10"]'
                            );

                        if (firstProductOption) {
                            firstProductOption.checked =
                                true;
                        }

                    } else {

                        storeOptions.style.display =
                            'none';

                        productInputs.forEach(
                            function (product) {
                                product.checked =
                                    false;
                            }
                        );

                    }

                }


                updateBusinessForms();

                calculateEstimate();

            }
        );

    });


    /* =========================================
       PAGE CHANGE
    ========================================= */

    pageInputs.forEach(function (input) {

        input.addEventListener(
            'change',
            function () {
                calculateEstimate();
            }
        );

    });


    /* =========================================
       PRODUCT CHANGE
    ========================================= */

    productInputs.forEach(function (input) {

        input.addEventListener(
            'change',
            function () {
                calculateEstimate();
            }
        );

    });


    /* =========================================
       STANDARD FORM CHANGE
    ========================================= */

    standardFormInputs.forEach(function (input) {

        input.addEventListener(
            'change',
            function () {

                updateBusinessForms();
                calculateEstimate();

            }
        );

    });


    /* =========================================
       FEATURE CHANGE
    ========================================= */

    featureInputs.forEach(function (input) {

        input.addEventListener(
            'change',
            function () {
                calculateEstimate();
            }
        );

    });

    /* =========================================
   DOMAIN + HOSTING SUMMARY
========================================= */

const domainHostingInputs = document.querySelectorAll(
    'input[name="domain_hosting"]'
);

const hostingPrice = document.getElementById(
    'website-hosting-price'
);

function updateDomainHostingSummary() {

    const selected = document.querySelector(
        'input[name="domain_hosting"]:checked'
    );

    if (!selected || !hostingPrice) {
        return;
    }

    switch (selected.value) {

        case 'existing':
            hostingPrice.textContent = '$0 / year';
            break;

       case 'managed':
            hostingPrice.textContent = 'Starting at $199 / year';
            break;

        case 'unsure':
            hostingPrice.textContent = 'To Be Determined';
            break;
    }
}

domainHostingInputs.forEach(function (input) {

    input.addEventListener('change', function () {
        updateDomainHostingSummary();
    });

});


/* =========================================
   WEBSITE CARE SUMMARY
========================================= */

const maintenanceInputs = document.querySelectorAll(
    'input[name="maintenance"]'
);

const maintenancePrice = document.getElementById(
    'website-maintenance-price'
);

function updateMaintenanceSummary() {

    const selected = document.querySelector(
        'input[name="maintenance"]:checked'
    );

    if (!selected || !maintenancePrice) {
        return;
    }

    switch (selected.value) {

        case 'self':
            maintenancePrice.textContent =
                '$0 / month';
            break;

        case 'care':
            maintenancePrice.textContent =
                '$49 / month';
            break;

        case 'care_plus':
            maintenancePrice.textContent =
                '$99 / month';
            break;

        case 'ecommerce':
            maintenancePrice.textContent =
                '$149 / month';
            break;
    }
}

maintenanceInputs.forEach(function (input) {

    input.addEventListener('change', function () {
        updateMaintenanceSummary();
    });

});

});