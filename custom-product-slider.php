<?php

/**
 * Plugin Name: Custom Product Slider
 * Description: WooCommerce center-mode product carousel
 * Version: 1.0
 * Author: Xian Saiful
 */

if (!defined('ABSPATH')) exit;

class CPS_Product_Slider
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('custom_product_slider', [$this, 'render_slider']);
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function enqueue_assets()
    {
        wp_enqueue_style('cps-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');

        // Swiper.js (carousel engine)
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

                            <div class="cps-image">
                                <?php echo woocommerce_get_product_thumbnail(); ?>
                            </div>

                            <div class="cps-info">
                                <div class="cps-info-left">
                                    <h3 class="cps-title"><?php the_title(); ?></h3>
                                    <div class="cps-price"><?php echo $product->get_price_html(); ?></div>
                                </div>

                                <div class="cps-product-button-right">
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

    // Admin Menu
    public function admin_menu()
    {
        add_menu_page(
            'Product Slider',
            'Product Slider',
            'manage_options',
            'cps-slider',
            [$this, 'settings_page'],
            'dashicons-images-alt2'
        );
    }

    public function register_settings()
    {
        register_setting('cps_settings_group', 'cps_categories');
        register_setting('cps_settings_group', 'cps_arrows');
        register_setting('cps_settings_group', 'cps_dots');
    }

    public function settings_page()
    {
        $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
    ?>

        <div class="wrap">
            <h1>Custom Product Slider</h1>

            <form method="post" action="options.php">
                <?php settings_fields('cps_settings_group'); ?>

                <h3>Select Categories</h3>
                <?php foreach ($categories as $cat): ?>
                    <label>
                        <input type="checkbox" name="cps_categories[]" value="<?php echo $cat->term_id; ?>"
                            <?php checked(in_array($cat->term_id, (array)get_option('cps_categories', []))); ?>>
                        <?php echo $cat->name; ?>
                    </label><br>
                <?php endforeach; ?>

                <h3>Options</h3>
                <label>
                    <input type="checkbox" name="cps_arrows" value="1" <?php checked(get_option('cps_arrows', 1)); ?>>
                    Show Arrows
                </label><br>

                <label>
                    <input type="checkbox" name="cps_dots" value="1" <?php checked(get_option('cps_dots', 1)); ?>>
                    Show Dots
                </label>

                <?php submit_button(); ?>
            </form>
        </div>

<?php
    }
}

new CPS_Product_Slider();
