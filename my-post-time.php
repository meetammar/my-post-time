<?php
/*
  Plugin Name: My Post Time
  Description: My Post Time plugin gives a functionality to add a post time options which helps the visitors to see that how much time it will take to go to read the article.
  Text Domain: wpcf-my-post-time
  Domain Path: /languages
  Author: Ammar Ilyas
  Author URI: ammar.pro786@gmail.com
  Version: 1.0.0
*/


define( 'WPCF_PLUGIN_SELF_DIRNAME', basename( dirname( __FILE__ ) ) );
if ( ! defined( 'WPCF_PLUGIN_BASE_DIR' ) ) {
    define( 'WPCF_PLUGIN_BASE_DIR', WP_PLUGIN_DIR . '/' . WPCF_PLUGIN_SELF_DIRNAME );
}

add_action('admin_init', 'wpcf_register_settings');

function wpcf_register_settings() {
    register_setting('wpcf-settings-group', 'wpcf_options');
}

add_action('plugins_loaded', 'wpcf_load_textdomain');
function wpcf_load_textdomain() {
    load_plugin_textdomain('wpcf-my-post-time', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

add_action('admin_menu', 'wpcf_menu');
function wpcf_menu() {
    add_options_page(
        _x( 'My Post Time', 'page title', 'wpcf-my-post-time' ),
        _x( 'My Post Time', 'menu title', 'wpcf-my-post-time' ),
        'manage_options',
        'wpcf-my-post-time',
        'wpcf_setting_page'
    );
}

function wpcf_setting_page() {
    $default_options = array(
        'enable' => '',
        'progressbar_enable' => '',
        'progressbar_color' => '#ffbb33',
        'progressbar_on_homepage' => 0,
        'progressbar_on_archives' => 0,
        'progressbar_on_posts' => 1,
        'words_per_minute_min' => 100,
        'words_per_minute_max' => 120,
        'min_max_interval' => 10,
        'format' => __('%s minutes to read', 'wpcf-my-post-time'),
        'format_lt' => __('%s minute to read', 'wpcf-my-post-time'),
        'format_lt_val' => 2
    );
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'wpcf-my-post-time'));
    }
    $options = get_option('wpcf_options');

    if (empty($options)) {
        $options = $default_options;
    }

    $enable = (isset($options['enable']) && $options['enable'] != '') ? 'checked=checked' : '';
    $progressbar_enable = (isset($options['progressbar_enable']) && $options['progressbar_enable'] != '') ? 'checked=checked' : '';
    $progressbar_color = !empty($options['progressbar_color']) ? $options['progressbar_color'] : '#ffbb33';
    $progressbar_on_homepage = !empty($options['progressbar_on_homepage']) ? true : false;
    $progressbar_on_archives = !empty($options['progressbar_on_archives']) ? true : false;
    $progressbar_on_posts = !empty($options['progressbar_on_posts']) ? true : false;

    $words_per_minute_min = !empty($options['words_per_minute_min']) ? $options['words_per_minute_min'] : 100;
    $words_per_minute_max = !empty($options['words_per_minute_max']) ? $options['words_per_minute_max'] : 120;
    $min_max_interval = isset($options['min_max_interval']) ? $options['min_max_interval'] : 10;

    $auto_archives_title = !empty($options['auto_archives_title']) ? true : false;
    $auto_excerpts = !empty($options['auto_excerpts']) ? true : false;

    $format = !empty($options['format']) ? $options['format'] : __('%s minutes to read', 'wpcf-my-post-time');
    $format_lt_val = !empty($options['format_lt_val']) ? $options['format_lt_val'] : 2;
    $format_lt = !empty($options['format_lt']) ? $options['format_lt'] : __('%s minute to read', 'wpcf-my-post-time');
    ?>
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php echo get_admin_page_title(); ?></h2>
    <div id="poststuff">
        <div class="postbox">
            <div class="inside less">
                <h3><?php _e('Settings', 'wpcf-my-post-time'); ?></h3>
                <form method="post" action="options.php">
                    <?php settings_fields('wpcf-settings-group'); ?>
                    <table class="form-table">

                        <tr>
                            <th><h4><label for="wpcf_enable"><?php echo __('Enable My Post Time: ', 'wpcf-my-post-time') ?></label></h4></th>
                            <td>
                                <p class="description"><input type="checkbox" name="wpcf_options[enable]" id="wpcf_enable" <?php echo $enable; ?>> <label for="wpcf_enable"><?php _e('Display <strong>my post time</strong> values using the template tag or the shortcode.', 'wpcf-my-post-time') ?></label></p>
                            </td>
                        </tr>
                        <tr class="wpcf-text-options">
                            <th><label for="words_per_minute_min"><?php echo __('Words per minute: ', 'wpcf-my-post-time') ?></label></th>
                            <td>
                                <input type="number" min="1" step="1" placeholder="Min" class="small-text" id="words_per_minute_min" name="wpcf_options[words_per_minute_min]" value="<?php echo $words_per_minute_min; ?>" /> &ndash; <input type="number" min="1" step="1"  placeholder="Max" class="small-text" id="words_per_minute_max" name="wpcf_options[words_per_minute_max]" value="<?php echo $words_per_minute_max; ?>" />
                                <p class="description"><?php _e('The average adult reading speed for English text in the United States is around 250 to 300 words per minute.', 'wpcf-my-post-time') ?></p>
                            </td>
                        </tr>
                        <tr class="wpcf-text-options">
                            <th><label for="min_max_interval"><?php echo __('<em>Min&ndash;max</em> interval above: ', 'wpcf-my-post-time') ?></label></th>
                            <td>
                                <input type="number" min="-1" step="1" class="small-text" id="min_max_interval" name="wpcf_options[min_max_interval]" value="<?php echo $min_max_interval; ?>" />
                                <p class="description"><?php _e('Show interval (eg. <strong>10&ndash;12 minutes</strong>) if the average is above this number. Set to 0 to always show interval, or to -1 to never show as interval.', 'wpcf-my-post-time') ?></p>
                            </td>
                        </tr>
                        <tr class="wpcf-text-options">
                            <th><label for="wpcf_format"><?php echo __('Format: ', 'wpcf-my-post-time') ?></label></th>
                            <td>
                                <input type="text" id="wpcf_format" name="wpcf_options[format]" value="<?php echo $format; ?>" style="width: 300px;" />
                                <p class="description">(<?php _e('<code>%s</code> will be replaced by the calculated minutes', 'wpcf-my-post-time'); ?>)</p>
                            </td>
                        </tr>
                        <tr class="wpcf-text-options">
                            <th><?php echo __('<em>Lower Than</em> Format', 'wpcf-my-post-time'); ?></th>
                            <td>
                                <?php echo sprintf(__('If average is lower than %1$s use format %2$s', 'wpcf-my-post-time'), '<input type="number" min="1" step="1" class="small-text" id="format_lt_val" name="wpcf_options[format_lt_val]" value="'.$format_lt_val.'" />', '<input type="text" id="wpcf_format_lt" name="wpcf_options[format_lt]" value="'.$format_lt.'" style="width: 300px;" />'); ?>
                                <p class="description"><?php _e('Use different format if the average is below a certain value.', 'wpcf-my-post-time') ?></p>
                            </td>
                        </tr>
                        <tr style="border-top: 1px solid #ddd;">
                            <th><h4><label for="progressbar_enable"><?php echo __('Enable Scroll Progress Bar: ', 'wpcf-my-post-time') ?></label></h4></th>
                            <td>
                                <p class="description"><input type="checkbox" name="wpcf_options[progressbar_enable]" id="progressbar_enable" <?php echo $progressbar_enable; ?>> <label for="progressbar_enable"><?php _e('Show a progress bar at the top of your site\'s pages that fills in as the users scroll down.', 'wpcf-my-post-time') ?></label></p>
                            </td>
                        </tr>
                        <tr class="wpcf-progressbar-options">
                            <th><label for="progressbar_color"><?php echo __('Progress Bar Color', 'wpcf-my-post-time') ?>:</label></th>
                            <td>
                                <input type="text" class="color-picker" id="progressbar_color" name="wpcf_options[progressbar_color]" value="<?php echo esc_attr($progressbar_color) ?>" />
                            </td>
                        </tr>
                        <tr class="wpcf-progressbar-options">
                            <th><label for="progressbar_on_homepage"><?php echo __('Show on homepage: ', 'wpcf-my-post-time') ?></label></th>
                            <td>
                                <input type="checkbox" name="wpcf_options[progressbar_on_homepage]" id="progressbar_on_homepage" <?php checked( $progressbar_on_homepage ); ?>>
                            </td>
                        </tr>
                        <tr class="wpcf-progressbar-options">
                            <th><label for="progressbar_on_archives"><?php echo __('Show on archives: ', 'wpcf-my-post-time') ?></label></th>
                            <td>
                                <input type="checkbox" name="wpcf_options[progressbar_on_archives]" id="progressbar_on_archives" <?php checked( $progressbar_on_archives ); ?>>
                            </td>
                        </tr>
                        <tr class="wpcf-progressbar-options">
                            <th><label for="progressbar_on_posts"><?php echo __('Show on single posts: ', 'wpcf-my-post-time') ?></label></th>
                            <td>
                                <input type="checkbox" name="wpcf_options[progressbar_on_posts]" id="progressbar_on_posts" <?php checked( $progressbar_on_posts ); ?>>
                            </td>
                        </tr>
                    </table>
                    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Update options', 'wpcf-my-post-time' ); ?>" />
                </form>
            </div>
        </div>
        <div class="postbox">
            <div class="inside less"><h3><?php _e('How to use it', 'wpcf-my-post-time'); ?></h3>
                <p><?php _e('Add the following template tag to your theme template files:', 'wpcf-my-post-time'); ?></p>
                <p><code>&lt;?php if (function_exists('wpcf_my_post_time')) wpcf_my_post_time(); ?&gt;</code></p>

                <p><?php _e( 'Or, insert the <code>[wpcf_my_post_time]</code> shortcode in any individual post or page in editor.', 'wpcf-my-post-time' ); ?></p>
            </div>
        </div>
    </div>
    <script type='text/javascript'>
        jQuery(document).ready(function($) {
            $('#progressbar_color').wpColorPicker();

            $('#wpcf_enable').change(function(event) {
                $('.wpcf-text-options').toggle(this.checked);
            }).change();
            $('#progressbar_enable').change(function(event) {
                $('.wpcf-progressbar-options').toggle(this.checked);
            }).change();
        });
    </script>

    <?php
}


add_action('wp_footer', 'wpcf_frontend');
function wpcf_frontend() {
    $options = get_option('wpcf_options');
    $plugin_activated = (isset($options['enable'])) && ($options['enable'] == 'on') ? true : false;
    $progressbar_activated = (isset($options['progressbar_enable'])) && ($options['progressbar_enable'] == 'on') ? true : false;
    $progressbar_on_homepage = !empty($options['progressbar_on_homepage']) ? true : false;
    $progressbar_on_archives = !empty($options['progressbar_on_archives']) ? true : false;
    $progressbar_on_posts = !empty($options['progressbar_on_posts']) ? true : false;

    $allowed_post_types = apply_filters( 'wpcf_progressbar_post_types', array('post', 'page') );

    $style = 'progress.reading-progress::-webkit-progress-value {background-color: ' . $options['progressbar_color'] . ';}progress.reading-progress::-moz-progress-bar {background-color: ' . $options['progressbar_color'] . ';}';
    if (!empty($options) && $progressbar_activated) {
        $show_progressbar = false;
        wp_reset_postdata();
        if (is_front_page() && $progressbar_on_homepage) $show_progressbar = true;
        if (is_archive() && in_array(get_post_type(), $allowed_post_types) && $progressbar_on_archives) $show_progressbar = true;
        if (!is_front_page() && is_singular() && in_array(get_post_type(), $allowed_post_types) && $progressbar_on_posts) $show_progressbar = true;
        if (apply_filters( 'wpcf_progressbar_display', $show_progressbar ) ) {
            ?><style><?php echo $style; ?></style><progress class="reading-progress" value="0" max="0"></progress><?php

            // this enqueues them in the footer
            wp_enqueue_script('wpcf-my-post-time', plugins_url('/scripts/js/wpcf-my-post-time.js', __FILE__), array('jquery'));
            wp_enqueue_style('wpcf-my-post-time', plugins_url('/scripts/css/wpcf-my-post-time.css', __FILE__));
            wp_localize_script( 'wpcf-my-post-time', 'wpcf_mpt', array(
                'progressbar_content_selector' => apply_filters( 'wpcf_progressbar_content_selector', '' )
            ) );
        }
    }
}

add_action('admin_enqueue_scripts', 'enqueue_admin_dependencies');
function enqueue_admin_dependencies() {
    $screen = get_current_screen();
    if ($screen->id == 'settings_page_wpcf-mpt') {
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
    }
}

function wpcf_my_post_time( $echo = true ) {
    $wpcf_options = get_option('wpcf_options');
    $output = '';
    $enable = (isset($wpcf_options['enable'])) && ($wpcf_options['enable'] == 'on') ? true : false;

    if (empty($wpcf_options) || !$enable)
        return;

    global $post;
    $format = $wpcf_options['format'];
    $format_lt = $wpcf_options['format_lt'];
    $format_lt_val = $wpcf_options['format_lt_val'];
    $words_per_minute_min = $wpcf_options['words_per_minute_min'];
    $words_per_minute_max = $wpcf_options['words_per_minute_max'];
    $words_per_minute_avg = round(($words_per_minute_min + $words_per_minute_max) / 2);
    $word_count = str_word_count(strip_tags(get_post_field('post_content', $post->ID))); // strip_shortcodes() ?
    $read_min = ceil($word_count / $words_per_minute_max);
    $read_max = ceil($word_count / $words_per_minute_min);
    $read_avg = ceil($word_count / $words_per_minute_avg);

    $interval_above = ($wpcf_options['min_max_interval'] == -1 ? 9999 : $wpcf_options['min_max_interval']);

    if ($read_avg < $format_lt_val) $format = $format_lt;

    if ($read_avg > $interval_above && $read_min != $read_max) {
        $output = sprintf($format, $read_min . '&ndash;' . $read_max);
    } else {
        $output = sprintf($format, $read_avg);
    }

    $output = apply_filters( 'wpcf_output', $output, $read_min, $read_max, $read_avg );

    if (!$echo)
        return $output;

    echo $output;
}

function wpcf_time_to_read_shortcode() {
    return wpcf_my_post_time( false );
}
add_shortcode('wpcf_my_post_time', 'wpcf_time_to_read_shortcode');
add_shortcode('time_to_read', 'wpcf_time_to_read_shortcode');
