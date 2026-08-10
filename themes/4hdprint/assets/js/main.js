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