<?php
if (!defined('ABSPATH')) exit;

function cps_render_admin_page()
{
    $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
?>

    <div class="wrap">
        <h1>🚀 Custom Product Slider</h1>

        <div style="background:#fff;padding:15px;margin-bottom:20px;">
            <strong>Shortcode:</strong>
            <input type="text" value="[custom_product_slider]" readonly onclick="this.select();">
        </div>

        <form method="post" action="options.php">
            <?php settings_fields('cps_settings_group'); ?>

            <h2>Categories</h2>
            <?php foreach ($categories as $cat): ?>
                <label>
                    <input type="checkbox" name="cps_categories[]" value="<?php echo $cat->term_id; ?>"
                        <?php checked(in_array($cat->term_id, (array)get_option('cps_categories', []))); ?>>
                    <?php echo $cat->name; ?>
                </label><br>
            <?php endforeach; ?>

            <h2>Slider Options</h2>

            <label><input type="checkbox" name="cps_autoplay" value="1" <?php checked(get_option('cps_autoplay', 1)); ?>> Autoplay</label><br>

            Delay <input type="number" name="cps_delay" value="<?php echo get_option('cps_delay', 3000); ?>"><br>

            Speed <input type="number" name="cps_speed" value="<?php echo get_option('cps_speed', 600); ?>"><br>

            <label><input type="checkbox" name="cps_loop" value="1" <?php checked(get_option('cps_loop', 1)); ?>> Loop</label><br>

            <label><input type="checkbox" name="cps_pause_hover" value="1" <?php checked(get_option('cps_pause_hover', 1)); ?>> Pause on Hover</label><br>

            <label><input type="checkbox" name="cps_centered" value="1" <?php checked(get_option('cps_centered', 1)); ?>> Center Mode</label><br>

            <h3>Slides Per View</h3>

            Mobile <input type="text" name="cps_slides_mobile" value="<?php echo get_option('cps_slides_mobile', 1.2); ?>"><br>
            Tablet <input type="text" name="cps_slides_tablet" value="<?php echo get_option('cps_slides_tablet', 1.8); ?>"><br>
            Desktop <input type="text" name="cps_slides_desktop" value="<?php echo get_option('cps_slides_desktop', 2.4); ?>"><br>

            Space Between <input type="number" name="cps_space_between" value="<?php echo get_option('cps_space_between', 0); ?>"><br>

            Effect
            <select name="cps_effect">
                <option value="slide">Slide</option>
                <option value="fade">Fade</option>
            </select>

            <br><br>
            <?php submit_button(); ?>

        </form>
    </div>

<?php } ?>