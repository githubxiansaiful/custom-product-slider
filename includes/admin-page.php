<?php
if (!defined('ABSPATH')) exit;

function cps_render_admin_page()
{
    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ]);

    // Get saved custom CSS
    $custom_css = get_option('cps_custom_css', '');
?>

    <div class="wrap cps-admin">

        <h1>🚀 Custom Product Slider</h1>
        <p class="subtitle">Configure your beautiful WooCommerce product slider with powerful options</p>

        <!-- Shortcode -->
        <div class="cps-card" style="margin-bottom: 32px;">
            <h2>📋 Shortcode</h2>
            <div class="shortcode-box" onclick="this.select(); document.execCommand('copy');">
                [custom_product_slider]
            </div>
            <p style="margin: 8px 0 0; font-size: 13px; color: #646970;">
                Copy this shortcode and paste it anywhere on your site.
            </p>
        </div>

        <form method="post" action="options.php">
            <?php settings_fields('cps_settings_group'); ?>

            <!-- Tabs -->
            <div class="cps-tabs">
                <div class="cps-tab active" data-tab="general">General</div>
                <div class="cps-tab" data-tab="display">Display</div>
                <div class="cps-tab" data-tab="navigation">Navigation & Controls</div>
                <div class="cps-tab" data-tab="customcss">Custom CSS</div>
            </div>

            <!-- Tab 1: General -->
            <div id="tab-general" class="cps-tab-content active">
                <div class="cps-grid" style="grid-template-columns: 1fr 1fr; gap: 28px;">
                    <!-- Categories -->
                    <div class="cps-card">
                        <h2>📂 Categories</h2>
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

                    <!-- Product Source -->
                    <div class="cps-card">
                        <h2>🔍 Product Source</h2>
                        <label>Show Products From</label>
                        <select name="cps_product_source">
                            <option value="all" <?php selected(get_option('cps_product_source', 'all'), 'all'); ?>>All Products</option>
                            <option value="featured" <?php selected(get_option('cps_product_source'), 'featured'); ?>>Featured Products</option>
                            <option value="onsale" <?php selected(get_option('cps_product_source'), 'onsale'); ?>>On Sale Products</option>
                            <option value="bestselling" <?php selected(get_option('cps_product_source'), 'bestselling'); ?>>Best Selling</option>
                            <option value="toprated" <?php selected(get_option('cps_product_source'), 'toprated'); ?>>Top Rated</option>
                        </select>

                        <label>Maximum Number of Products</label>
                        <input type="number" name="cps_max_products" min="1" value="<?php echo esc_attr(get_option('cps_max_products', 12)); ?>">
                        <span class="cps-help">How many products to load in total (recommended: 8–20)</span>

                        <label>Order By</label>
                        <select name="cps_orderby">
                            <option value="date" <?php selected(get_option('cps_orderby', 'date'), 'date'); ?>>Date</option>
                            <option value="title" <?php selected(get_option('cps_orderby'), 'title'); ?>>Title</option>
                            <option value="price" <?php selected(get_option('cps_orderby'), 'price'); ?>>Price</option>
                            <option value="popularity" <?php selected(get_option('cps_orderby'), 'popularity'); ?>>Popularity (Sales)</option>
                            <option value="rating" <?php selected(get_option('cps_orderby'), 'rating'); ?>>Average Rating</option>
                        </select>

                        <label>Order</label>
                        <select name="cps_order">
                            <option value="DESC" <?php selected(get_option('cps_order', 'DESC'), 'DESC'); ?>>Descending</option>
                            <option value="ASC" <?php selected(get_option('cps_order'), 'ASC'); ?>>Ascending</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Display -->
            <div id="tab-display" class="cps-tab-content">
                <div class="cps-grid" style="grid-template-columns: 1fr 1fr; gap: 28px;">
                    <div class="cps-card">
                        <h2>📏 Slides Per View</h2>
                        <label>Mobile</label>
                        <input type="text" name="cps_slides_mobile" value="<?php echo esc_attr(get_option('cps_slides_mobile', '1.2')); ?>">

                        <label>Tablet</label>
                        <input type="text" name="cps_slides_tablet" value="<?php echo esc_attr(get_option('cps_slides_tablet', '1.8')); ?>">

                        <label>Desktop</label>
                        <input type="text" name="cps_slides_desktop" value="<?php echo esc_attr(get_option('cps_slides_desktop', '2.4')); ?>">

                        <label>Slides to Scroll</label>
                        <input type="number" name="cps_slides_to_scroll" min="1" value="<?php echo esc_attr(get_option('cps_slides_to_scroll', 1)); ?>">
                        <span class="cps-help">How many slides move at once when navigating</span>

                        <label>Space Between Slides (px)</label>
                        <input type="number" name="cps_space_between" value="<?php echo esc_attr(get_option('cps_space_between', 20)); ?>">
                    </div>

                    <div class="cps-card">
                        <h2>🎚️ Slider Behavior</h2>
                        <?php
                        $toggles = [
                            'Autoplay' => 'cps_autoplay',
                            'Loop' => 'cps_loop',
                            'Pause on Hover' => 'cps_pause_hover',
                            'Center Mode' => 'cps_centered',
                            'Lazy Loading' => 'cps_lazy_load',
                        ];
                        foreach ($toggles as $label => $option) : ?>
                            <div class="cps-toggle">
                                <label><?php echo $label; ?></label>
                                <label class="cps-switch">
                                    <input type="checkbox" name="<?php echo $option; ?>" value="1"
                                        <?php checked(get_option($option, 1)); ?>>
                                    <span class="cps-slider"></span>
                                </label>
                            </div>
                        <?php endforeach; ?>

                        <label>Autoplay Delay (ms)</label>
                        <input type="number" name="cps_delay" value="<?php echo esc_attr(get_option('cps_delay', 3000)); ?>">

                        <label>Transition Speed (ms)</label>
                        <input type="number" name="cps_speed" value="<?php echo esc_attr(get_option('cps_speed', 600)); ?>">
                    </div>
                </div>
            </div>

            <!-- Tab 3: Navigation & Controls -->
            <div id="tab-navigation" class="cps-tab-content">
                <div class="cps-grid" style="grid-template-columns: 1fr 1fr; gap: 28px;">
                    <div class="cps-card">
                        <h2>🧭 Navigation</h2>
                        <div class="cps-toggle">
                            <label>Show Navigation Arrows</label>
                            <label class="cps-switch">
                                <input type="checkbox" name="cps_show_arrows" value="1" <?php checked(get_option('cps_show_arrows', 1)); ?>>
                                <span class="cps-slider"></span>
                            </label>
                        </div>
                        <div class="cps-toggle">
                            <label>Show Pagination Dots</label>
                            <label class="cps-switch">
                                <input type="checkbox" name="cps_show_dots" value="1" <?php checked(get_option('cps_show_dots', 1)); ?>>
                                <span class="cps-slider"></span>
                            </label>
                        </div>
                        <div class="cps-toggle">
                            <label>Mousewheel Control</label>
                            <label class="cps-switch">
                                <input type="checkbox" name="cps_mousewheel" value="1" <?php checked(get_option('cps_mousewheel', 0)); ?>>
                                <span class="cps-slider"></span>
                            </label>
                        </div>
                        <div class="cps-toggle">
                            <label>Keyboard Control</label>
                            <label class="cps-switch">
                                <input type="checkbox" name="cps_keyboard" value="1" <?php checked(get_option('cps_keyboard', 0)); ?>>
                                <span class="cps-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="cps-card">
                        <h2>🌍 Advanced</h2>
                        <div class="cps-toggle">
                            <label>RTL (Right to Left)</label>
                            <label class="cps-switch">
                                <input type="checkbox" name="cps_rtl" value="1" <?php checked(get_option('cps_rtl', 0)); ?>>
                                <span class="cps-slider"></span>
                            </label>
                        </div>

                        <label>Transition Effect</label>
                        <select name="cps_effect">
                            <option value="slide" <?php selected(get_option('cps_effect'), 'slide'); ?>>Slide</option>
                            <option value="fade" <?php selected(get_option('cps_effect'), 'fade'); ?>>Fade</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- New Tab 4: Custom CSS -->
            <div id="tab-customcss" class="cps-tab-content">
                <div class="cps-card">
                    <h2>🎨 Custom Frontend CSS</h2>
                    <p style="margin-bottom: 15px; color: #646970;">
                        Add your custom CSS here to style the slider. This will be added to the frontend.
                    </p>
                    <textarea name="cps_custom_css" id="cps_custom_css_editor" style="width:100%; height:400px;"><?php echo esc_textarea($custom_css); ?></textarea>
                    <p class="cps-help">
                        Tip: Target classes like <code>.cps-slider</code>, <code>.cps-card</code>, <code>.cps-title</code>, etc.
                    </p>
                </div>
            </div>

            <br><br>
            <?php submit_button('Save All Settings', 'primary large'); ?>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.cps-tab');
            const contents = document.querySelectorAll('.cps-tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));

                    tab.classList.add('active');
                    document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
                });
            });

            // Initialize CodeMirror for Custom CSS
            if (typeof wp !== 'undefined' && wp.codeEditor) {
                const editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
                editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
                    mode: 'css',
                    lineNumbers: true,
                    theme: 'wordpress',
                    indentUnit: 4,
                    tabSize: 4,
                    autoCloseBrackets: true
                });
                wp.codeEditor.initialize(document.getElementById('cps_custom_css_editor'), editorSettings);
            }
        });
    </script>

<?php
}
