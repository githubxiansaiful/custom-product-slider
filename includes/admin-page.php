<?php
if (!defined('ABSPATH')) exit;

function cps_render_admin_page()
{

    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false
    ]);
?>

    <div class="wrap cps-admin">

        <h1>🚀 Custom Product Slider</h1>

        <div class="cps-card">
            <label><strong>Shortcode</strong></label>
            <input type="text" value="[custom_product_slider]" readonly onclick="this.select();">
        </div>

        <form method="post" action="options.php">
            <?php settings_fields('cps_settings_group'); ?>

            <div class="cps-grid">

                <!-- Categories -->
                <div class="cps-card">
                    <h2>Categories</h2>

                    <div class="cps-checkbox-grid">
                        <?php foreach ($categories as $cat): ?>
                            <label>
                                <input type="checkbox" name="cps_categories[]" value="<?php echo esc_attr($cat->term_id); ?>"
                                    <?php checked(in_array($cat->term_id, (array)get_option('cps_categories', []))); ?>>
                                <?php echo esc_html($cat->name); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Slider Options -->
                <div class="cps-card">
                    <h2>Slider Options</h2>

                    <label><input type="checkbox" name="cps_autoplay" value="1" <?php checked(get_option('cps_autoplay', 1)); ?>> Autoplay</label>

                    <label>Delay</label>
                    <input type="number" name="cps_delay" value="<?php echo esc_attr(get_option('cps_delay', 3000)); ?>">

                    <label>Speed</label>
                    <input type="number" name="cps_speed" value="<?php echo esc_attr(get_option('cps_speed', 600)); ?>">

                    <label><input type="checkbox" name="cps_loop" value="1" <?php checked(get_option('cps_loop', 1)); ?>> Loop</label>

                    <label><input type="checkbox" name="cps_pause_hover" value="1" <?php checked(get_option('cps_pause_hover', 1)); ?>> Pause on Hover</label>

                    <label><input type="checkbox" name="cps_centered" value="1" <?php checked(get_option('cps_centered', 1)); ?>> Center Mode</label>
                </div>

                <!-- Slides -->
                <div class="cps-card">
                    <h2>Slides Per View</h2>

                    <label>Mobile</label>
                    <input type="text" name="cps_slides_mobile" value="<?php echo esc_attr(get_option('cps_slides_mobile', 1.2)); ?>">

                    <label>Tablet</label>
                    <input type="text" name="cps_slides_tablet" value="<?php echo esc_attr(get_option('cps_slides_tablet', 1.8)); ?>">

                    <label>Desktop</label>
                    <input type="text" name="cps_slides_desktop" value="<?php echo esc_attr(get_option('cps_slides_desktop', 2.4)); ?>">

                    <label>Space Between</label>
                    <input type="number" name="cps_space_between" value="<?php echo esc_attr(get_option('cps_space_between', 0)); ?>">

                    <label>Effect</label>
                    <select name="cps_effect">
                        <option value="slide" <?php selected(get_option('cps_effect'), 'slide'); ?>>Slide</option>
                        <option value="fade" <?php selected(get_option('cps_effect'), 'fade'); ?>>Fade</option>
                    </select>
                </div>

            </div>

            <br>
            <?php submit_button('Save Settings'); ?>
        </form>

    </div>

<?php
}