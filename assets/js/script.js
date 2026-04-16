document.addEventListener("DOMContentLoaded", function () {

    const swiper = new Swiper('.cps-slider', {

        loop: cps_settings.loop == 1,
        centeredSlides: cps_settings.centered == 1,

        slidesPerView: parseFloat(cps_settings.slides_mobile),
        spaceBetween: parseInt(cps_settings.space_between),

        breakpoints: {
            768: {
                slidesPerView: parseFloat(cps_settings.slides_tablet)
            },
            1024: {
                slidesPerView: parseFloat(cps_settings.slides_desktop)
            }
        },

        speed: parseInt(cps_settings.speed),
        effect: cps_settings.effect,

        autoplay: cps_settings.autoplay == 1 ? {
            delay: parseInt(cps_settings.delay),
            disableOnInteraction: false,
        } : false,

        navigation: cps_settings.arrows == 1 ? {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        } : false,

        pagination: cps_settings.dots == 1 ? {
            el: '.swiper-pagination',
            clickable: true,
        } : false,
    });

    if (cps_settings.pause_hover == 1 && cps_settings.autoplay == 1) {
        const el = document.querySelector('.cps-slider');

        el.addEventListener('mouseenter', () => swiper.autoplay.stop());
        el.addEventListener('mouseleave', () => swiper.autoplay.start());
    }

});