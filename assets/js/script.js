document.addEventListener("DOMContentLoaded", function () {

    new Swiper('.cps-slider', {
        centeredSlides: true,
        loop: true,
        spaceBetween: 0,

        slidesPerView: 1.2, // mobile

        breakpoints: {
            768: {
                slidesPerView: 1.8
            },
            1024: {
                slidesPerView: 2.4 // gives ~40% side visibility
            }
        },

        navigation: cps_settings.arrows ? {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        } : false,

        pagination: cps_settings.dots ? {
            el: '.swiper-pagination',
            clickable: true,
        } : false,
    });

});