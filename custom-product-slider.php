<?php

/**
 * Plugin Name: Custom Product Slider
 * Description: WooCommerce center-mode product carousel
 * Version: 2.3
 * Author: Xian Saiful
 */

if (!defined('ABSPATH')) exit;

class CPS_Product_Slider
{

    public function __construct()
    {

        // Frontend
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('custom_product_slider', [$this, 'render_slider']);

        // Admin
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);

        // Plugin page link
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'settings_link']);

        // Include admin page
        require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
    }

    /**
     * FRONTEND ASSETS
     */
    public function enqueue_assets()
    {

        // CSS
        wp_enqueue_style(
            'cps-style',
            plugin_dir_url(__FILE__) . 'assets/css/style.css'
        );

        // Swiper
        wp_enqueue_style(
            'swiper-css',
            'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css'
        );

        wp_enqueue_script(
            'swiper-js',
            'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js',
            [],
            null,
            true
        );

        // Custom JS
        wp_enqueue_script(
            'cps-script',
            plugin_dir_url(__FILE__) . 'assets/js/script.js',
            ['swiper-js'],
            null,
            true
        );

        // Pass settings to JS
        wp_localize_script('cps-script', 'cps_settings', [
            'arrows' => get_option('cps_arrows', 1),
            'dots'   => get_option('cps_dots', 1),

            'autoplay' => get_option('cps_autoplay', 1),
            'delay'    => get_option('cps_delay', 3000),
            'speed'    => get_option('cps_speed', 600),
            'loop'     => get_option('cps_loop', 1),

            'pause_hover' => get_option('cps_pause_hover', 1),
            'centered'    => get_option('cps_centered', 1),

            'slides_mobile'  => get_option('cps_slides_mobile', 1.2),
            'slides_tablet'  => get_option('cps_slides_tablet', 1.8),
            'slides_desktop' => get_option('cps_slides_desktop', 2.4),

            'space_between' => get_option('cps_space_between', 0),
            'effect' => get_option('cps_effect', 'slide'),
        ]);
    }

    /**
     * RENDER SLIDER
     */
    public function render_slider()
    {

        if (!class_exists('WooCommerce')) return;

        $categories = get_option('cps_categories', []);

        $args = [
            'post_type' => 'product',
            'posts_per_page' => 10,
        ];

        if (!empty($categories)) {
            $args['tax_query'] = [[
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $categories,
            ]];
        }

        $query = new WP_Query($args);

        ob_start(); ?>

        <div class="cps-slider swiper">
            <div class="swiper-wrapper">

                <?php while ($query->have_posts()) : $query->the_post();
                    global $product; ?>

                    <div class="swiper-slide cps-slide">
                        <div class="cps-card">

                            <!-- Animated Background -->
                            <div class="cps-bg"></div>

                            <!-- Image -->
                            <div class="cps-image">
                                <?php
                                $img = wp_get_attachment_image_url(get_post_thumbnail_id(), 'large');
                                ?>
                                <img src="<?php echo esc_url($img); ?>" alt="<?php the_title(); ?>">
                            </div>

                            <!-- Info -->
                            <div class="cps-info">
                                <div>
                                    <h3 class="cps-title"><?php the_title(); ?></h3>
                                    <div class="cps-price"><?php echo $product->get_price_html(); ?></div>
                                </div>

                                <a href="<?php the_permalink(); ?>" class="cps-btn">
                                    DISCOVER MORE
                                </a>
                            </div>

                        </div>
                    </div>

                <?php endwhile;
                wp_reset_postdata(); ?>

            </div>

            <!-- Controls -->
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>

<?php return ob_get_clean();
    }

    /**
     * ADMIN MENU
     */
    public function admin_menu()
    {
        add_menu_page(
            'Product Slider',
            'Product Slider',
            'manage_options',
            'cps-slider',
            'cps_render_admin_page',
            'dashicons-images-alt2'
        );
    }

    /**
     * REGISTER SETTINGS
     */
    public function register_settings()
    {

        $fields = [
            'cps_categories',
            'cps_arrows',
            'cps_dots',

            'cps_autoplay',
            'cps_delay',
            'cps_speed',
            'cps_loop',

            'cps_pause_hover',
            'cps_centered',

            'cps_slides_mobile',
            'cps_slides_tablet',
            'cps_slides_desktop',

            'cps_space_between',
            'cps_effect'
        ];

        foreach ($fields as $field) {
            register_setting('cps_settings_group', $field);
        }
    }

    /**
     * ADMIN CSS (ONLY LOAD ON PLUGIN PAGE)
     */
    public function admin_assets($hook)
    {

        if ($hook !== 'toplevel_page_cps-slider') {
            return;
        }

        wp_enqueue_style(
            'cps-admin-style',
            plugin_dir_url(__FILE__) . 'assets/css/admin.css'
        );
    }

    /**
     * SETTINGS LINK IN PLUGINS PAGE
     */
    public function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=cps-slider">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}

new CPS_Product_Slider();
