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

        <form method="post" action="options.php" class="cps-admin-box">
            <?php settings_fields('cps_settings_group'); ?>

            <h2>Product Categories</h2>
            <div class="cps-grid">
                <?php foreach ($categories as $cat): ?>
                    <label>
                        <input type="checkbox" name="cps_categories[]" value="<?php echo $cat->term_id; ?>"
                            <?php checked(in_array($cat->term_id, (array)get_option('cps_categories', []))); ?>>
                        <?php echo $cat->name; ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <h2>Display Options</h2>

            <label>
                <input type="checkbox" name="cps_arrows" value="1" <?php checked(get_option('cps_arrows', 1)); ?>>
                Show Arrows
            </label><br>

            <label>
                <input type="checkbox" name="cps_dots" value="1" <?php checked(get_option('cps_dots', 1)); ?>>
                Show Dots
            </label>

            <br><br>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>

    <style>
        .cps-admin-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 800px;
        }

        .cps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
    </style>

<?php
}
