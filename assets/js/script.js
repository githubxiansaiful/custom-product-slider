document.addEventListener("DOMContentLoaded", function () {
    const settings = cps_settings || {};

    const swiperEl = document.querySelector('.cps-slider');
    if (!swiperEl) return;

    const swiper = new Swiper(swiperEl, {
        loop: settings.loop == 1,
        centeredSlides: settings.centered == 1,
        effect: settings.effect || 'slide',

        slidesPerView: parseFloat(settings.slides_mobile || 1.2),
        spaceBetween: parseInt(settings.space_between || 20),
        slidesPerGroup: parseInt(settings.slides_to_scroll || 1),

        breakpoints: {
            768: {
                slidesPerView: parseFloat(settings.slides_tablet || 1.8),
                slidesPerGroup: parseInt(settings.slides_to_scroll || 1)
            },
            1024: {
                slidesPerView: parseFloat(settings.slides_desktop || 2.4),
                slidesPerGroup: parseInt(settings.slides_to_scroll || 1)
            }
        },

        speed: parseInt(settings.speed || 600),

        autoplay: (settings.autoplay == 1) ? {
            delay: parseInt(settings.delay || 3000),
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        } : false,

        navigation: (settings.arrows == 1) ? {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        } : false,

        pagination: (settings.dots == 1) ? {
            el: '.swiper-pagination',
            clickable: true,
        } : false,

        mousewheel: settings.mousewheel == 1,
        keyboard: settings.keyboard == 1,
        lazy: settings.lazy_load == 1 ? { loadPrevNext: true } : false,
        rtl: settings.rtl == 1,

        watchOverflow: true,
        grabCursor: true
    });

    // Manual pause on hover fallback
    if (settings.pause_hover == 1 && settings.autoplay == 1) {
        swiperEl.addEventListener('mouseenter', () => swiper.autoplay?.stop());
        swiperEl.addEventListener('mouseleave', () => swiper.autoplay?.start());
    }
});