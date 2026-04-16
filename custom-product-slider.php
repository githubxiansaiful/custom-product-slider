<?php

/**
 * Plugin Name: Custom Product Slider
 * Description: WooCommerce center-mode product carousel
 * Version: 1.2
 * Author: Xian Saiful
 */

if (!defined('ABSPATH')) exit;

class CPS_Product_Slider
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('custom_product_slider', [$this, 'render_slider']);

        // Admin
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);

        // Settings link in plugins page
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'settings_link']);

        // Include admin file
        require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
    }

    public function enqueue_assets()
    {
        wp_enqueue_style('cps-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');

        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
        wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', [], null, true);

        wp_enqueue_script('cps-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', ['swiper-js'], null, true);

        wp_localize_script('cps-script', 'cps_settings', [
            'arrows' => get_option('cps_arrows', 1),
            'dots'   => get_option('cps_dots', 1),
        ]);
    }

    public function render_slider()
    {
        if (!class_exists('WooCommerce')) return;

        $categories = get_option('cps_categories', []);

        $args = [
            'post_type' => 'product',
            'posts_per_page' => 10,
        ];

        if (!empty($categories)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $categories,
                ]
            ];
        }

        $query = new WP_Query($args);

        ob_start();
?>

        <div class="cps-slider swiper">
            <div class="swiper-wrapper">

                <?php while ($query->have_posts()) : $query->the_post();
                    global $product; ?>

                    <div class="swiper-slide cps-slide">
                        <div class="cps-card">

                            <!-- Animated Background -->
                            <div class="cps-bg"></div>

                            <div class="cps-image">
                                <?php echo woocommerce_get_product_thumbnail(); ?>
                            </div>

                            <div class="cps-info">
                                <div class="cps-info-left">
                                    <h3 class="cps-title"><?php the_title(); ?></h3>
                                    <div class="cps-price"><?php echo $product->get_price_html(); ?></div>
                                </div>

                                <div class="cps-info-right">
                                    <a href="<?php the_permalink(); ?>" class="cps-btn">
                                        DISCOVER MORE
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                <?php endwhile;
                wp_reset_postdata(); ?>

            </div>

            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>

<?php
        return ob_get_clean();
    }

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

    public function register_settings()
    {
        register_setting('cps_settings_group', 'cps_categories');
        register_setting('cps_settings_group', 'cps_arrows');
        register_setting('cps_settings_group', 'cps_dots');
    }

    // 🔥 Settings link in plugin list
    public function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=cps-slider">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}

new CPS_Product_Slider();
