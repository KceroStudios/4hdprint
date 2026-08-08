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

  const scrollLimit = 110; 
  const menu = document.querySelector('.menu_container');

  window.addEventListener('scroll', () => {
    if (window.scrollY >= scrollLimit) {
      menu.classList.add('scroll-menu');
    } else {
      menu.classList.remove('scroll-menu');
    }
  });

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