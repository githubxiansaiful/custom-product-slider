<?php
if (!defined('ABSPATH')) exit;

function cps_render_admin_page()
{
    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ]);
?>

    <div class="wrap cps-admin">

        <h1>🚀 Custom Product Slider</h1>
        <p class="subtitle">Configure your beautiful WooCommerce product slider</p>

        <!-- Shortcode Card -->
        <div class="cps-card" style="margin-bottom: 32px;">
            <h2>📋 Shortcode</h2>
            <div class="shortcode-box" onclick="this.select(); document.execCommand('copy');">
                [custom_product_slider]
            </div>
            <p style="margin: 8px 0 0; font-size: 13px; color: #646970;">
                Copy and paste this shortcode anywhere on your site.
            </p>
        </div>

        <form method="post" action="options.php">
            <?php settings_fields('cps_settings_group'); ?>

            <div class="cps-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px;">

                <!-- Categories -->
                <div class="cps-card">
                    <h2>📂 Categories to Show</h2>
                    <div class="cps-checkbox-grid">
                        <?php foreach ($categories as $cat): ?>
                            <label>
                                <input type="checkbox"
                                    name="cps_categories[]"
                                    value="<?php echo esc_attr($cat->term_id); ?>"
                                    <?php checked(in_array($cat->term_id, (array)get_option('cps_categories', []))); ?>>
                                <?php echo esc_html($cat->name); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?php if (empty($categories)): ?>
                        <p style="color:#d63638; font-size:13px;">No product categories found.</p>
                    <?php endif; ?>
                </div>

                <!-- Slider Behavior -->
                <div class="cps-card">
                    <h2>▶️ Slider Behavior</h2>

                    <div class="cps-toggle">
                        <label for="cps_autoplay">Autoplay</label>
                        <label class="cps-switch">
                            <input type="checkbox" id="cps_autoplay" name="cps_autoplay" value="1"
                                <?php checked(get_option('cps_autoplay', 1)); ?>>
                            <span class="cps-slider"></span>
                        </label>
                    </div>

                    <label>Autoplay Delay (ms)</label>
                    <input type="number" name="cps_delay" value="<?php echo esc_attr(get_option('cps_delay', 3000)); ?>">

                    <label>Transition Speed (ms)</label>
                    <input type="number" name="cps_speed" value="<?php echo esc_attr(get_option('cps_speed', 600)); ?>">

                    <div class="cps-toggle">
                        <label>Loop</label>
                        <label class="cps-switch">
                            <input type="checkbox" name="cps_loop" value="1"
                                <?php checked(get_option('cps_loop', 1)); ?>>
                            <span class="cps-slider"></span>
                        </label>
                    </div>

                    <div class="cps-toggle">
                        <label>Pause on Hover</label>
                        <label class="cps-switch">
                            <input type="checkbox" name="cps_pause_hover" value="1"
                                <?php checked(get_option('cps_pause_hover', 1)); ?>>
                            <span class="cps-slider"></span>
                        </label>
                    </div>

                    <div class="cps-toggle">
                        <label>Center Mode</label>
                        <label class="cps-switch">
                            <input type="checkbox" name="cps_centered" value="1"
                                <?php checked(get_option('cps_centered', 1)); ?>>
                            <span class="cps-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Slides & Layout -->
                <div class="cps-card">
                    <h2>📏 Slides & Layout</h2>

                    <label>Slides per View - Mobile</label>
                    <input type="text" name="cps_slides_mobile" value="<?php echo esc_attr(get_option('cps_slides_mobile', '1.2')); ?>">

                    <label>Slides per View - Tablet</label>
                    <input type="text" name="cps_slides_tablet" value="<?php echo esc_attr(get_option('cps_slides_tablet', '1.8')); ?>">

                    <label>Slides per View - Desktop</label>
                    <input type="text" name="cps_slides_desktop" value="<?php echo esc_attr(get_option('cps_slides_desktop', '2.4')); ?>">

                    <label>Space Between Slides (px)</label>
                    <input type="number" name="cps_space_between" value="<?php echo esc_attr(get_option('cps_space_between', 20)); ?>">

                    <label>Transition Effect</label>
                    <select name="cps_effect">
                        <option value="slide" <?php selected(get_option('cps_effect'), 'slide'); ?>>Slide</option>
                        <option value="fade" <?php selected(get_option('cps_effect'), 'fade'); ?>>Fade</option>
                    </select>
                </div>

            </div>

            <br><br>
            <?php submit_button('Save All Settings', 'primary large'); ?>
        </form>

    </div>

<?php
}
