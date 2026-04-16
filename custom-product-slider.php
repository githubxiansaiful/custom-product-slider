<?php

/**
 * Plugin Name:       Custom Product Slider
 * Description:       WooCommerce beautiful center-mode product carousel with powerful settings
 * Version:           2.7
 * Author:            Xian Saiful
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

        require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
    }

    public function enqueue_assets()
    {
        wp_enqueue_style('cps-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');

        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'); // Updated to v11 (recommended)
        wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], null, true);

        wp_enqueue_script('cps-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', ['swiper-js'], null, true);

        // Pass ALL settings to JS
        wp_localize_script('cps-script', 'cps_settings', [
            'autoplay'       => (int) get_option('cps_autoplay', 1),
            'delay'          => (int) get_option('cps_delay', 3000),
            'speed'          => (int) get_option('cps_speed', 600),
            'loop'           => (int) get_option('cps_loop', 1),
            'pause_hover'    => (int) get_option('cps_pause_hover', 1),
            'centered'       => (int) get_option('cps_centered', 1),
            'slides_mobile'  => get_option('cps_slides_mobile', '1.2'),
            'slides_tablet'  => get_option('cps_slides_tablet', '1.8'),
            'slides_desktop' => get_option('cps_slides_desktop', '2.4'),
            'slides_to_scroll' => (int) get_option('cps_slides_to_scroll', 1),
            'space_between'  => (int) get_option('cps_space_between', 20),
            'effect'         => get_option('cps_effect', 'slide'),
            'arrows'         => (int) get_option('cps_show_arrows', 1),
            'dots'           => (int) get_option('cps_show_dots', 1),
            'mousewheel'     => (int) get_option('cps_mousewheel', 0),
            'keyboard'       => (int) get_option('cps_keyboard', 0),
            'lazy_load'      => (int) get_option('cps_lazy_load', 1),
            'rtl'            => (int) get_option('cps_rtl', 0),
        ]);
    }

    public function render_slider()
    {
        if (!class_exists('WooCommerce')) return 'WooCommerce is required.';

        $categories     = (array) get_option('cps_categories', []);
        $source         = get_option('cps_product_source', 'all');
        $max_products   = (int) get_option('cps_max_products', 12);
        $orderby        = get_option('cps_orderby', 'date');
        $order          = get_option('cps_order', 'DESC');

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $max_products,
            'orderby'        => $orderby,
            'order'          => $order,
        ];

        // Product Source
        if ($source === 'featured') {
            $args['tax_query'][] = [
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ];
        } elseif ($source === 'onsale') {
            $args['meta_query'][] = [
                'key'     => '_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'numeric'
            ];
        } elseif ($source === 'bestselling') {
            $args['meta_key'] = 'total_sales';
            $args['orderby']  = 'meta_value_num';
        } elseif ($source === 'toprated') {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_wc_average_rating';
        }

        if (!empty($categories)) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $categories,
            ];
        }

        $query = new WP_Query($args);

        if (!$query->have_posts()) return '<p>No products found.</p>';

        ob_start(); ?>

        <div class="cps-slider swiper">
            <div class="swiper-wrapper">
                <?php while ($query->have_posts()) : $query->the_post();
                    global $product;
                    $img = wp_get_attachment_image_url(get_post_thumbnail_id(), 'large'); ?>

                    <div class="swiper-slide cps-slide">
                        <div class="cps-card">
                            <div class="cps-bg"></div>
                            <div class="cps-image">
                                <img src="<?php echo esc_url($img); ?>" alt="<?php the_title(); ?>" loading="lazy">
                            </div>
                            <div class="cps-info">
                                <div>
                                    <h3 class="cps-title"><?php the_title(); ?></h3>
                                    <div class="cps-price"><?php echo $product->get_price_html(); ?></div>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="cps-btn">Produkte Ansehen</a>
                            </div>
                        </div>
                    </div>

                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>

            <?php if (get_option('cps_show_arrows', 1)) : ?>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            <?php endif; ?>

            <?php if (get_option('cps_show_dots', 1)) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
        </div>

<?php return ob_get_clean();
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
        $fields = [
            'cps_categories',
            'cps_product_source',
            'cps_max_products',
            'cps_orderby',
            'cps_order',
            'cps_autoplay',
            'cps_delay',
            'cps_speed',
            'cps_loop',
            'cps_pause_hover',
            'cps_centered',
            'cps_lazy_load',
            'cps_slides_mobile',
            'cps_slides_tablet',
            'cps_slides_desktop',
            'cps_slides_to_scroll',
            'cps_space_between',
            'cps_effect',
            'cps_show_arrows',
            'cps_show_dots',
            'cps_mousewheel',
            'cps_keyboard',
            'cps_rtl'
        ];

        foreach ($fields as $field) {
            register_setting('cps_settings_group', $field);
        }
    }

    public function admin_assets($hook)
    {
        if ($hook !== 'toplevel_page_cps-slider') return;

        wp_enqueue_style('cps-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css');
    }

    public function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=cps-slider">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}

new CPS_Product_Slider();
