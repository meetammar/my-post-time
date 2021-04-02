<?php
/*
  Plugin Name: My Post Time
  Description: My Post Time plugin gives a functionality to add a post time options which helps the visitors to see that how much time it will take to go to read the article.
  Text Domain: cfmpt-my-post-time
  Domain Path: /languages
  Author: Ammar Ilyas
  Author URI: ammar.pro786@gmail.com
  Version: 1.0.0
*/


define( 'CFMPT_PLUGIN_SELF_DIRNAME', basename( dirname( __FILE__ ) ) );
if ( ! defined( 'CFMPT_PLUGIN_BASE_DIR' ) ) {
    define( 'CFMPT_PLUGIN_BASE_DIR', WP_PLUGIN_DIR . '/' . CFMPT_PLUGIN_SELF_DIRNAME );
}

add_action('admin_init', 'cfmpt_register_settings');

function cfmpt_register_settings() {
    register_setting('cfmpt-settings-group', 'cfmpt_options');
}

add_action('plugins_loaded', 'cfmpt_load_textdomain');
function cfmpt_load_textdomain() {
    load_plugin_textdomain('cfmpt-my-post-time', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

add_action('admin_menu', 'cfmpt_menu');
function cfmpt_menu() {
    add_options_page(
        _x( 'My Post Time', 'page title', 'cfmpt-my-post-time' ),
        _x( 'My Post Time', 'menu title', 'cfmpt-my-post-time' ),
        'manage_options',
        'cfmpt-my-post-time',
        'cfmpt_setting_page'
    );
}

function cfmpt_setting_page() {
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
        'format' => __('%s minutes to read', 'cfmpt-my-post-time'),
        'format_lt' => __('%s minute to read', 'cfmpt-my-post-time'),
        'format_lt_val' => 2
    );
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'cfmpt-my-post-time'));
    }
    $options = get_option('cfmpt_options');

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

    $format = !empty($options['format']) ? $options['format'] : __('%s minutes to read', 'cfmpt-my-post-time');
    $format_lt_val = !empty($options['format_lt_val']) ? $options['format_lt_val'] : 2;
    $format_lt = !empty($options['format_lt']) ? $options['format_lt'] : __('%s minute to read', 'cfmpt-my-post-time');
    ?>
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php echo get_admin_page_title(); ?></h2>
    <div id="poststuff">
        <div class="postbox">
            <div class="inside less">
                <h3><?php _e('Settings', 'cfmpt-my-post-time'); ?></h3>
                <form method="post" action="options.php">
                    <?php settings_fields('cfmpt-settings-group'); ?>
                    <table class="form-table">

                        <tr>
                            <th><h4><label for="cfmpt_enable"><?php echo __('Enable My Post Time: ', 'cfmpt-my-post-time') ?></label></h4></th>
                            <td>
                                <p class="description"><input type="checkbox" name="cfmpt_options[enable]" id="cfmpt_enable" <?php echo $enable; ?>> <label for="cfmpt_enable"><?php _e('Display <strong>my post time</strong> values using the template tag or the shortcode.', 'cfmpt-my-post-time') ?></label></p>
                            </td>
                        </tr>
                        <tr class="cfmpt-text-options">
                            <th><label for="words_per_minute_min"><?php echo __('Words per minute: ', 'cfmpt-my-post-time') ?></label></th>
                            <td>
                                <input type="number" min="1" step="1" placeholder="Min" class="small-text" id="words_per_minute_min" name="cfmpt_options[words_per_minute_min]" value="<?php echo $words_per_minute_min; ?>" /> &ndash; <input type="number" min="1" step="1"  placeholder="Max" class="small-text" id="words_per_minute_max" name="cfmpt_options[words_per_minute_max]" value="<?php echo $words_per_minute_max; ?>" />
                                <p class="description"><?php _e('The average adult reading speed for English text in the United States is around 250 to 300 words per minute.', 'cfmpt-my-post-time') ?></p>
                            </td>
                        </tr>
                        <tr class="cfmpt-text-options">
                            <th><label for="min_max_interval"><?php echo __('<em>Min&ndash;max</em> interval above: ', 'cfmpt-my-post-time') ?></label></th>
                            <td>
                                <input type="number" min="-1" step="1" class="small-text" id="min_max_interval" name="cfmpt_options[min_max_interval]" value="<?php echo $min_max_interval; ?>" />
                                <p class="description"><?php _e('Show interval (eg. <strong>10&ndash;12 minutes</strong>) if the average is above this number. Set to 0 to always show interval, or to -1 to never show as interval.', 'cfmpt-my-post-time') ?></p>
                            </td>
                        </tr>
                        <tr class="cfmpt-text-options">
                            <th><label for="cfmpt_format"><?php echo __('Format: ', 'cfmpt-my-post-time') ?></label></th>
                            <td>
                                <input type="text" id="cfmpt_format" name="cfmpt_options[format]" value="<?php echo $format; ?>" style="width: 300px;" />
                                <p class="description">(<?php _e('<code>%s</code> will be replaced by the calculated minutes', 'cfmpt-my-post-time'); ?>)</p>
                            </td>
                        </tr>
                        <tr class="cfmpt-text-options">
                            <th><?php echo __('<em>Lower Than</em> Format', 'cfmpt-my-post-time'); ?></th>
                            <td>
                                <?php echo sprintf(__('If average is lower than %1$s use format %2$s', 'cfmpt-my-post-time'), '<input type="number" min="1" step="1" class="small-text" id="format_lt_val" name="cfmpt_options[format_lt_val]" value="'.$format_lt_val.'" />', '<input type="text" id="cfmpt_format_lt" name="cfmpt_options[format_lt]" value="'.$format_lt.'" style="width: 300px;" />'); ?>
                                <p class="description"><?php _e('Use different format if the average is below a certain value.', 'cfmpt-my-post-time') ?></p>
                            </td>
                        </tr>
                        <tr style="border-top: 1px solid #ddd;">
                            <th><h4><label for="progressbar_enable"><?php echo __('Enable Scroll Progress Bar: ', 'cfmpt-my-post-time') ?></label></h4></th>
                            <td>
                                <p class="description"><input type="checkbox" name="cfmpt_options[progressbar_enable]" id="progressbar_enable" <?php echo $progressbar_enable; ?>> <label for="progressbar_enable"><?php _e('Show a progress bar at the top of your site\'s pages that fills in as the users scroll down.', 'cfmpt-my-post-time') ?></label></p>
                            </td>
                        </tr>
                        <tr class="cfmpt-progressbar-options">
                            <th><label for="progressbar_color"><?php echo __('Progress Bar Color', 'cfmpt-my-post-time') ?>:</label></th>
                            <td>
                                <input type="text" class="color-picker" id="progressbar_color" name="cfmpt_options[progressbar_color]" value="<?php echo esc_attr($progressbar_color) ?>" />
                            </td>
                        </tr>
                        <tr class="cfmpt-progressbar-options">
                            <th><label for="progressbar_on_homepage"><?php echo __('Show on homepage: ', 'cfmpt-my-post-time') ?></label></th>
                            <td>
                                <input type="checkbox" name="cfmpt_options[progressbar_on_homepage]" id="progressbar_on_homepage" <?php checked( $progressbar_on_homepage ); ?>>
                            </td>
                        </tr>
                        <tr class="cfmpt-progressbar-options">
                            <th><label for="progressbar_on_archives"><?php echo __('Show on archives: ', 'cfmpt-my-post-time') ?></label></th>
                            <td>
                                <input type="checkbox" name="cfmpt_options[progressbar_on_archives]" id="progressbar_on_archives" <?php checked( $progressbar_on_archives ); ?>>
                            </td>
                        </tr>
                        <tr class="cfmpt-progressbar-options">
                            <th><label for="progressbar_on_posts"><?php echo __('Show on single posts: ', 'cfmpt-my-post-time') ?></label></th>
                            <td>
                                <input type="checkbox" name="cfmpt_options[progressbar_on_posts]" id="progressbar_on_posts" <?php checked( $progressbar_on_posts ); ?>>
                            </td>
                        </tr>
                    </table>
                    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Update options', 'cfmpt-my-post-time' ); ?>" />
                </form>
            </div>
        </div>
        <div class="postbox">
            <div class="inside less"><h3><?php _e('How to use it', 'cfmpt-my-post-time'); ?></h3>
                <p><?php _e('Add the following template tag to your theme template files:', 'cfmpt-my-post-time'); ?></p>
                <p><code>&lt;?php if (function_exists('cfmpt_my_post_time')) cfmpt_my_post_time(); ?&gt;</code></p>

                <p><?php _e( 'Or, insert the <code>[cfmpt_my_post_time]</code> shortcode in any individual post or page in editor.', 'cfmpt-my-post-time' ); ?></p>
            </div>
        </div>
    </div>
    <script type='text/javascript'>
        jQuery(document).ready(function($) {
            $('#progressbar_color').wpColorPicker();

            $('#cfmpt_enable').change(function(event) {
                $('.cfmpt-text-options').toggle(this.checked);
            }).change();
            $('#progressbar_enable').change(function(event) {
                $('.cfmpt-progressbar-options').toggle(this.checked);
            }).change();
        });
    </script>

    <?php
}


add_action('wp_footer', 'cfmpt_frontend');
function cfmpt_frontend() {
    $options = get_option('cfmpt_options');
    $plugin_activated = (isset($options['enable'])) && ($options['enable'] == 'on') ? true : false;
    $progressbar_activated = (isset($options['progressbar_enable'])) && ($options['progressbar_enable'] == 'on') ? true : false;
    $progressbar_on_homepage = !empty($options['progressbar_on_homepage']) ? true : false;
    $progressbar_on_archives = !empty($options['progressbar_on_archives']) ? true : false;
    $progressbar_on_posts = !empty($options['progressbar_on_posts']) ? true : false;

    $allowed_post_types = apply_filters( 'cfmpt_progressbar_post_types', array('post', 'page') );

    $style = 'progress.reading-progress::-webkit-progress-value {background-color: ' . $options['progressbar_color'] . ';}progress.reading-progress::-moz-progress-bar {background-color: ' . $options['progressbar_color'] . ';}';
    if (!empty($options) && $progressbar_activated) {
        $show_progressbar = false;
        wp_reset_postdata();
        if (is_front_page() && $progressbar_on_homepage) $show_progressbar = true;
        if (is_archive() && in_array(get_post_type(), $allowed_post_types) && $progressbar_on_archives) $show_progressbar = true;
        if (!is_front_page() && is_singular() && in_array(get_post_type(), $allowed_post_types) && $progressbar_on_posts) $show_progressbar = true;
        if (apply_filters( 'cfmpt_progressbar_display', $show_progressbar ) ) {
            ?><style><?php echo $style; ?></style><progress class="reading-progress" value="0" max="0"></progress><?php

            // this enqueues them in the footer
            wp_enqueue_script('cfmpt-my-post-time', plugins_url('/scripts/js/cfmpt-my-post-time.js', __FILE__), array('jquery'));
            wp_enqueue_style('cfmpt-my-post-time', plugins_url('/scripts/css/cfmpt-my-post-time.css', __FILE__));
            wp_localize_script( 'cfmpt-my-post-time', 'cfmpt_mpt', array(
                'progressbar_content_selector' => apply_filters( 'cfmpt_progressbar_content_selector', '' )
            ) );
        }
    }
}

add_action('admin_enqueue_scripts', 'enqueue_admin_dependencies');
function enqueue_admin_dependencies() {
    $screen = get_current_screen();
    if ($screen->id == 'settings_page_cfmpt-mpt') {
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
    }
}

function cfmpt_my_post_time( $echo = true ) {
    $cfmpt_options = get_option('cfmpt_options');
    $output = '';
    $enable = (isset($cfmpt_options['enable'])) && ($cfmpt_options['enable'] == 'on') ? true : false;

    if (empty($cfmpt_options) || !$enable)
        return;

    global $post;
    $format = $cfmpt_options['format'];
    $format_lt = $cfmpt_options['format_lt'];
    $format_lt_val = $cfmpt_options['format_lt_val'];
    $words_per_minute_min = $cfmpt_options['words_per_minute_min'];
    $words_per_minute_max = $cfmpt_options['words_per_minute_max'];
    $words_per_minute_avg = round(($words_per_minute_min + $words_per_minute_max) / 2);
    $word_count = str_word_count(strip_tags(get_post_field('post_content', $post->ID))); // strip_shortcodes() ?
    $read_min = ceil($word_count / $words_per_minute_max);
    $read_max = ceil($word_count / $words_per_minute_min);
    $read_avg = ceil($word_count / $words_per_minute_avg);

    $interval_above = ($cfmpt_options['min_max_interval'] == -1 ? 9999 : $cfmpt_options['min_max_interval']);

    if ($read_avg < $format_lt_val) $format = $format_lt;

    if ($read_avg > $interval_above && $read_min != $read_max) {
        $output = sprintf($format, $read_min . '&ndash;' . $read_max);
    } else {
        $output = sprintf($format, $read_avg);
    }

    $output = apply_filters( 'cfmpt_output', $output, $read_min, $read_max, $read_avg );

    if (!$echo)
        return $output;

    echo $output;
}

function cfmpt_time_to_read_shortcode() {
    return cfmpt_my_post_time( false );
}
add_shortcode('cfmpt_my_post_time', 'cfmpt_time_to_read_shortcode');
add_shortcode('time_to_read', 'cfmpt_time_to_read_shortcode');
