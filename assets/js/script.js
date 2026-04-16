document.addEventListener("DOMContentLoaded", function () {

    const settings = cps_settings || {}; // fallback if object not passed

    const swiperEl = document.querySelector('.cps-slider');

    if (!swiperEl) {
        console.warn('Custom Product Slider: .cps-slider element not found.');
        return;
    }

    const swiper = new Swiper(swiperEl, {

        // Core Settings
        loop: settings.loop == 1,
        centeredSlides: settings.centered == 1,
        effect: settings.effect || 'slide',

        // Slides Per View + Responsive
        slidesPerView: parseFloat(settings.slides_mobile || 1.2),
        spaceBetween: parseInt(settings.space_between || 20),
        slidesPerGroup: parseInt(settings.slides_to_scroll || 1),   // ← New: How many slides move at once

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

        // Speed & Autoplay
        speed: parseInt(settings.speed || 600),

        autoplay: (settings.autoplay == 1) ? {
            delay: parseInt(settings.delay || 3000),
            disableOnInteraction: false,
            pauseOnMouseEnter: true   // Built-in Swiper option (better than manual)
        } : false,

        // Navigation & Pagination
        navigation: (settings.arrows == 1) ? {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        } : false,

        pagination: (settings.dots == 1) ? {
            el: '.swiper-pagination',
            clickable: true,
        } : false,

        // Advanced Controls
        mousewheel: settings.mousewheel == 1 ? {
            enabled: true,
            sensitivity: 1.2
        } : false,

        keyboard: settings.keyboard == 1 ? {
            enabled: true,
            onlyInViewport: true
        } : false,

        // Lazy Loading
        lazy: settings.lazy_load == 1 ? {
            loadPrevNext: true,        // Preload nearby slides
            loadPrevNextAmount: 2
        } : false,

        // RTL Support
        rtl: settings.rtl == 1,

        // Other useful defaults
        watchOverflow: true,
        grabCursor: true
    });

    // Fallback manual pause on hover (in case autoplay is enabled but built-in doesn't work)
    if (settings.pause_hover == 1 && settings.autoplay == 1) {
        swiperEl.addEventListener('mouseenter', () => {
            if (swiper.autoplay) swiper.autoplay.stop();
        });

        swiperEl.addEventListener('mouseleave', () => {
            if (swiper.autoplay) swiper.autoplay.start();
        });
    }

    // Optional: Re-init on window resize for better responsiveness
    window.addEventListener('resize', () => {
        swiper.update();
    });

});