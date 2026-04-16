<?php
if (!defined('ABSPATH')) exit;

function cps_render_admin_page() {
    $categories = get_terms([
        'taxonomy'=>'product_cat',
        'hide_empty'=>false
    ]);
?>

<div class="wrap cps-admin">

    <h1>🚀 Custom Product Slider</h1>

    <!-- Shortcode Box -->
    <div class="cps-card">
        <label>Shortcode</label>
        <input type="text" value="[custom_product_slider]" readonly onclick="this.select();">
    </div>

    <form method="post" action="options.php">
        <?php settings_fields('cps_settings_group'); ?>

        <div class="cps-grid">

            <!-- Categories -->
            <div class="cps-card">
                <h2>Categories</h2>

                <div class="cps-checkbox-grid">
                    <?php foreach($categories as $cat): ?>
                        <label>
                            <input type="checkbox" name="cps_categories[]" value="<?php echo $cat->term_id; ?>"
                            <?php checked(in_array($cat->term_id,(array)get_option('cps_categories',[]))); ?>>
                            <?php echo $cat->name; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Slider Options -->
            <div class="cps-card">
                <h2>Slider Options</h2>

                <label class="cps-switch">
                    <input type="checkbox" name="cps_autoplay" value="1" <?php checked(get_option('cps_autoplay',1)); ?>>
                    <span></span> Autoplay
                </label>

                <label>Delay</label>
                <input type="number" name="cps_delay" value="<?php echo get_option('cps_delay',3000); ?>">

                <label>Speed</label>
                <input type="number" name="cps_speed" value="<?php echo get_option('cps_speed',600); ?>">

                <label class="cps-switch">
                    <input type="checkbox" name="cps_loop" value="1" <?php checked(get_option('cps_loop',1)); ?>>
                    <span></span> Loop
                </label>

                <label class="cps-switch">
                    <input type="checkbox" name="cps_pause_hover" value="1" <?php checked(get_option('cps_pause_hover',1)); ?>>
                    <span></span> Pause on Hover
                </label>

                <label class="cps-switch">
                    <input type="checkbox" name="cps_centered" value="1" <?php checked(get_option('cps_centered',1)); ?>>
                    <span></span> Center Mode
                </label>
            </div>

            <!-- Slides -->
            <div class="cps-card">
                <h2>Slides Per View</h2>

                <label>Mobile</label>
                <input type="text" name="cps_slides_mobile" value="<?php echo get_option('cps_slides_mobile',1.2); ?>">

                <label>Tablet</label>
                <input type="text" name="cps_slides_tablet" value="<?php echo get_option('cps_slides_tablet',1.8); ?>">

                <label>Desktop</label>
                <input type="text" name="cps_slides_desktop" value="<?php echo get_option('cps_slides_desktop',2.4); ?>">

                <label>Space Between</label>
                <input type="number" name="cps_space_between" value="<?php echo get_option('cps_space_between',0); ?>">

                <label>Effect</label>
                <select name="cps_effect">
                    <option value="slide">Slide</option>
                    <option value="fade">Fade</option>
                </select>
            </div>

        </div>

        <br>
        <?php submit_button('Save Settings'); ?>
    </form>

</div>

<style>
.cps-admin {
    max-width: 1100px;
}

.cps-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.cps-card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.cps-card h2 {
    margin-bottom: 15px;
}

.cps-card input,
.cps-card select {
    width: 100%;
    margin-bottom: 10px;
}

.cps-checkbox-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 5px;
}

/* Toggle Switch */
.cps-switch {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.cps-switch input {
    display: none;
}

.cps-switch span {
    width: 40px;
    height: 20px;
    background: #ccc;
    border-radius: 20px;
    position: relative;
    display: inline-block;
}

.cps-switch span:before {
    content: '';
    width: 16px;
    height: 16px;
    background: #fff;
    position: absolute;
    top: 2px;
    left: 2px;
    border-radius: 50%;
    transition: .3s;
}

.cps-switch input:checked + span {
    background: #0073aa;
}

.cps-switch input:checked + span:before {
    transform: translateX(20px);
}
</style>