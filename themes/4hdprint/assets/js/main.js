// alert('hola mundo');

function ajustarElementos() {
    if (window.innerWidth < 600) {
        document.querySelector('.menu_container')?.classList.add('mobile');
         //document.querySelector('.ejemplo2')?.classList.add('show');

    } else {
        document.querySelector('.menu_container')?.classList.remove('mobile');
        // document.querySelector('.ejemplo2')?.classList.remove('show');
    
    }
}

// Ejecutar cuando cargue la página
ajustarElementos();

// Escuchar cambios de tamaño en la ventana
window.addEventListener('resize', ajustarElementos);

//////////////////////////////////////////////////////////

//scroll menu

  // Scroll menu

const scrollLimit = 110;
const menu = document.querySelector('.menu_container');

if (menu) {

    function updateScrollMenu() {

        if (window.scrollY >= scrollLimit) {
            menu.classList.add('scroll-menu');
        } else {
            menu.classList.remove('scroll-menu');
        }

    }

    window.addEventListener('scroll', updateScrollMenu);

    updateScrollMenu();
}

  //////////////////////
  document.addEventListener("DOMContentLoaded", function () {
  const btn = document.querySelector(".icon_menu");
  const menu = document.querySelector(".main_menu ul");

  if (btn && menu) {
    btn.addEventListener("click", function () {
      menu.classList.toggle("open");
    });
  }
});

// Back to top

const backToTop = document.querySelector('#back-to-top');

if (backToTop) {

    window.addEventListener('scroll', () => {

        if (window.scrollY >= 400) {
            backToTop.classList.add('is-visible');
        } else {
            backToTop.classList.remove('is-visible');
        }

    });

    backToTop.addEventListener('click', () => {

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

    });

}

const designInput = document.getElementById('fourhd_design');
const designPreview = document.getElementById('fourhd_design_preview');
const designPreviewImage = document.getElementById('fourhd_design_preview_image');
const designFilename = document.getElementById('fourhd_design_filename');

if (
    designInput &&
    designPreview &&
    designPreviewImage &&
    designFilename
) {

    designInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {
            designPreview.hidden = true;
            designPreviewImage.src = '';
            designFilename.textContent = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {

            designPreviewImage.src = event.target.result;
            designFilename.textContent = file.name;

            designPreview.hidden = false;

        };

        reader.readAsDataURL(file);

    });

}

// =========================================
// SIZE SWATCHES
// =========================================

document.addEventListener('DOMContentLoaded', () => {

    const sizeSelect = document.querySelector(
    'select[data-attribute_name="attribute_size"]'
);

    if (!sizeSelect) {
        return;
    }

    const wrapper = document.createElement('div');
    wrapper.className = 'fourhd-size-swatches';

    Array.from(sizeSelect.options).forEach(option => {

        if (!option.value) {
            return;
        }

        const button = document.createElement('button');

        button.type = 'button';
        button.className = 'fourhd-size-swatch';
        button.dataset.value = option.value;
        button.textContent = option.textContent;

        button.addEventListener('click', () => {

            sizeSelect.value = option.value;

            sizeSelect.dispatchEvent(
                new Event('change', {
                    bubbles: true
                })
            );

            wrapper
                .querySelectorAll('.fourhd-size-swatch')
                .forEach(item => {
                    item.classList.remove('is-selected');
                });

            button.classList.add('is-selected');

        });

        wrapper.appendChild(button);

    });


    sizeSelect.insertAdjacentElement(
        'afterend',
        wrapper
    );

});

// =========================================
// COLOR SWATCHES
// =========================================

document.addEventListener('DOMContentLoaded', () => {

    const colorSelect = document.querySelector(
        'select[name="attribute_color"]'
    );

    if (!colorSelect) {
        return;
    }

    const colorMap = {
        black: '#000000',
        white: '#ffffff',
        red: '#d62828',
        blue: '#2563eb',
        yellow: '#f4d03f'
    };

    const wrapper = document.createElement('div');
    wrapper.className = 'fourhd-color-swatches';

    Array.from(colorSelect.options).forEach(option => {

        if (!option.value) {
            return;
        }

        const button = document.createElement('button');

        button.type = 'button';
        button.className = 'fourhd-color-swatch';
        button.dataset.value = option.value;

        const normalizedColor = option.value.toLowerCase();

        button.style.setProperty(
            '--swatch-color',
            colorMap[normalizedColor] || '#cccccc'
        );

        button.setAttribute(
            'aria-label',
            option.textContent
        );

        button.title = option.textContent;

        button.addEventListener('click', () => {

            colorSelect.value = option.value;

            colorSelect.dispatchEvent(
                new Event('change', {
                    bubbles: true
                })
            );

            wrapper
                .querySelectorAll('.fourhd-color-swatch')
                .forEach(item => {
                    item.classList.remove('is-selected');
                });

            button.classList.add('is-selected');

        });

        wrapper.appendChild(button);

    });


    colorSelect.insertAdjacentElement(
        'afterend',
        wrapper
    );

});
// =========================================
// CUSTOM T-SHIRT CUSTOMIZER
// =========================================

document.addEventListener('DOMContentLoaded', () => {

    const customizer = document.querySelector(
        '.fourhd-product-customizer'
    );

    if (!customizer) {
        return;
    }


    const primaryOptions = customizer.querySelectorAll(
        'input[name="fourhd_primary_location"]'
    );

    const extraOptions = customizer.querySelectorAll(
        'input[name="fourhd_extra_locations[]"]'
    );

    const uploadBlocks = customizer.querySelectorAll(
        '.fourhd-location-upload'
    );

    const totalElement = customizer.querySelector(
        '#fourhd_custom_total'
    );


    const basePrice = parseFloat(
        customizer.dataset.basePrice || '0'
    );


    function formatPrice(value) {

        return new Intl.NumberFormat(
            'en-US',
            {
                style: 'currency',
                currency: 'USD'
            }
        ).format(value);

    }


    function getPrimaryLocation() {

        const selected = customizer.querySelector(
            'input[name="fourhd_primary_location"]:checked'
        );

        return selected
            ? selected.value
            : null;

    }


    function updateExtraLocations() {

        const primaryLocation = getPrimaryLocation();


        extraOptions.forEach(input => {

            const wrapper = input.closest(
                '.fourhd-extra-location'
            );

            if (!wrapper) {
                return;
            }


            if (
                primaryLocation &&
                input.value === primaryLocation
            ) {

                input.checked = false;
                input.disabled = true;

                wrapper.classList.add(
                    'is-disabled'
                );

            } else {

                input.disabled = false;

                wrapper.classList.remove(
                    'is-disabled'
                );

            }

        });

    }


    function updatePrice() {

        let total = basePrice;


        extraOptions.forEach(input => {

            if (
                input.checked &&
                !input.disabled
            ) {

                total += parseFloat(
                    input.dataset.price || '0'
                );

            }

        });


        if (totalElement) {
            totalElement.textContent =
                formatPrice(total);
        }

    }


    function updateUploadFields() {

        const activeLocations = new Set();

        const primaryLocation = getPrimaryLocation();


        if (primaryLocation) {
            activeLocations.add(primaryLocation);
        }


        extraOptions.forEach(input => {

            if (
                input.checked &&
                !input.disabled
            ) {

                activeLocations.add(input.value);

            }

        });


        uploadBlocks.forEach(block => {

            const location = block.dataset.location;

            const fileInput = block.querySelector(
                'input[type="file"]'
            );


            if (activeLocations.has(location)) {

                block.hidden = false;

                if (fileInput) {
                    fileInput.required = true;
                }

            } else {

                block.hidden = true;

                if (fileInput) {
                    fileInput.required = false;
                    fileInput.value = '';
                }

                const preview = block.querySelector(
                    '.fourhd-design-preview'
                );

                if (preview) {
                    preview.hidden = true;
                }

            }

        });

    }


    function updateCustomizer() {

        updateExtraLocations();
        updatePrice();
        updateUploadFields();

    }


    // Primary location

    primaryOptions.forEach(input => {

        input.addEventListener(
            'change',
            updateCustomizer
        );

    });


    // Extra locations

    extraOptions.forEach(input => {

        input.addEventListener(
            'change',
            updateCustomizer
        );

    });


    // File previews

    uploadBlocks.forEach(block => {

        const input = block.querySelector(
            'input[type="file"]'
        );

        const preview = block.querySelector(
            '.fourhd-design-preview'
        );

        const image = preview
            ? preview.querySelector('img')
            : null;

        const filename = preview
            ? preview.querySelector(
                '.fourhd-design-preview__filename'
            )
            : null;


        if (!input || !preview || !image || !filename) {
            return;
        }


        input.addEventListener(
            'change',
            function () {

                const file = this.files[0];


                if (!file) {

                    preview.hidden = true;
                    image.src = '';
                    filename.textContent = '';

                    return;
                }


                const reader = new FileReader();


                reader.onload = function (event) {

                    image.src = event.target.result;

                    filename.textContent =
                        file.name;

                    preview.hidden = false;

                };


                reader.readAsDataURL(file);

            }
        );

    });


    // Initial state

    updateCustomizer();

});

document.addEventListener('DOMContentLoaded', function () {

    const categoryToggles = document.querySelectorAll(
        '.product-category-menu__toggle'
    );

    categoryToggles.forEach(function (toggle) {

        toggle.addEventListener('click', function () {

            const parent = toggle.closest(
                '.product-category-menu__parent'
            );

            if (!parent) {
                return;
            }

            const isOpen = parent.classList.toggle('is-open');

            toggle.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );

        });

    });

});

document.querySelectorAll('.services_slide .card[data-url]').forEach((card) => {

    card.addEventListener('click', (event) => {

        if (window.innerWidth > 600) {
            return;
        }

        const url = card.dataset.url;

        if (url) {
            window.location.href = url;
        }

    });

});