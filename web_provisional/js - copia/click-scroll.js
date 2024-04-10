var nav = document.querySelector('.navbar');
var umbralDesplazamiento = 100;
var ultimaPosicionScroll = 0;
var logo = document.querySelector('.brand-logo');

function handleScroll() {
    var scrolled = window.scrollY;

    if (Math.abs(scrolled - ultimaPosicionScroll) >= umbralDesplazamiento) {
        if (scrolled != 0) {
            nav.classList.add('scrolled');
            // Cambiar la imagen del logo cuando se desplace hacia abajo
            logo.src = "https://emececuadrado.com/wp-content/uploads/2023/07/Grupo-Emececuadrado-logo-300x144.png";
        } else {
            nav.classList.remove('scrolled');
            logo.src = "images/logo_blanco.png";
        }
        ultimaPosicionScroll = scrolled;
    } else if (scrolled == 0) {
        nav.classList.remove('scrolled');
        logo.src = "images/logo_blanco.png";
    }
}


let toggleState = false;

function handleToggle() {
    if (toggleState) {
        nav.classList.remove('toggle');
        toggleState = false;
    } else {
        nav.classList.add('toggle');
        toggleState = true;
    }
    logo.src = "https://emececuadrado.com/wp-content/uploads/2023/07/Grupo-Emececuadrado-logo-300x144.png";
}

// Agregar un listener de scroll
window.addEventListener('scroll', handleScroll);
window.addEventListener('scroll', handleScroll, { passive: true });



$(document).ready(function() {
    var sectionArray = [1, 2, 3, 4, 5, 6, 7];

    $.each(sectionArray, function(index, value) {
        $(document).scroll(function() {
            var sectionId = 'section_' + value;
            var $section = $('#' + sectionId);

            if ($section.length > 0) {
                var offsetSection = $section.offset().top - 84;
                var docScroll = $(document).scrollTop();
                var docScroll1 = docScroll + 1;

                if (docScroll1 >= offsetSection) {
                    $('.navbar-nav .nav-item .nav-link').removeClass('active');
                    $('.navbar-nav .nav-item .nav-link:link').addClass('inactive');
                    $('.navbar-nav .nav-item .nav-link').eq(index).addClass('active');
                    $('.navbar-nav .nav-item .nav-link').eq(index).removeClass('inactive');
                }
            }
        });

        $('.click-scroll').eq(index).click(function(e) {
            var sectionId = 'section_' + value;
            var $section = $('#' + sectionId);

            if ($section.length > 0) {
                var offsetClick = $section.offset().top - 84;
                e.preventDefault();
                $('html, body').animate({
                    'scrollTop': offsetClick
                }, 300);
            }
        });
    });

    $('.navbar-nav .nav-item .nav-link:link').addClass('inactive');
    $('.navbar-nav .nav-item .nav-link').eq(0).addClass('active');
    $('.navbar-nav .nav-item .nav-link:link').eq(0).removeClass('inactive');
});
