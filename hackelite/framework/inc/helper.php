<?php

/**
 * Created by PhpStorm.
 * User: Inwavethemes
 * Date: 4/7/2015
 * Time: 8:58 AM
 */
class Inwave_Helper {

    /**
     * CUT STRING BY CHARACTERS FUNCTION
     * @param $text
     * @param int $length
     * @param string $replacer
     * @param bool $isStrips
     * @param string $stringtags
     * @return string
     */
    public static function substring($text, $length = 100, $isStrips = false, $replacer = '...', $stringtags = '') {

        if ($isStrips) {
            $text = preg_replace('/\<p.*\>/Us', '', $text);
            $text = str_replace('</p>', '<br/>', $text);
            $text = strip_tags($text, $stringtags);
        }

        if (function_exists('mb_strlen')) {
            if (mb_strlen($text) < $length)
                return $text;
            $text = mb_substr($text, 0, $length);
        }else {
            if (strlen($text) < $length)
                return $text;
            $text = substr($text, 0, $length);
        }

        return $text . $replacer;
    }

    /**
     * CUT STRING BY WORDS FUNCTION
     * @param $text
     * @param int $length
     * @param string $replacer
     * @param bool $isStrips
     * @param string $stringtags
     * @return string
     */
    public static function substrword($text, $length = 100, $isStrips = false, $replacer = '...', $stringtags = '') {
        if ($isStrips) {
            $text = preg_replace('/\<p.*\>/Us', '', $text);
            $text = str_replace('</p>', '<br/>', $text);
            $text = strip_tags($text, $stringtags);
        }
        $tmp = explode(" ", $text);

        if (count($tmp) < $length)
            return $text;

        $text = implode(" ", array_slice($tmp, 0, $length)) . $replacer;

        return $text;
    }

    public static function getConfig() {
        global $inwave_theme_option;

        return $inwave_theme_option;
    }

    public static function setConfig($config) {
        global $inwave_theme_option;

        $inwave_theme_option = $config;
    }

    public static function getPostOption($key, $theme_config_key = null, $single = true, $post_id = null) {

        if (!$post_id) {
            $post = get_post();
            if ($post) {
                $post_id = $post->ID;
            }

            if (function_exists('is_shop')) {
                if (is_shop()) {
                    $post_id = get_option('woocommerce_shop_page_id');
                }
            } elseif (is_home()) {
                $post_id = -1;
            } elseif (is_search()) {
                $post_id = -1;
            }
            if (!$post_id && function_exists('bp_is_current_component')) {
                $page_array = get_option('bp-pages');
                if (bp_is_current_component('activity')) {
                    $post_id = $page_array['activity'];
                } elseif (bp_is_current_component('groups')) {
                    $post_id = $page_array['groups'];
                } elseif (bp_is_current_component('members')) {
                    $post_id = $page_array['members'];
                }
            }
        }
        $value = '';
        if ($post_id && $key) {
            $value = get_post_meta($post_id, 'inwave_' . $key, $single);
        }

        if (!$value && $theme_config_key) {
            global $inwave_theme_option;
            $value = isset($inwave_theme_option[$theme_config_key]) ? $inwave_theme_option[$theme_config_key] : '';
        }

        return $value;
    }

    public static function getThemeOption($key, $default = '') {
        global $inwave_theme_option;
        if (isset($inwave_theme_option[$key])) {
            return $inwave_theme_option[$key];
        }

        return $default;
    }

    public static function getPanelSetting($key) {
        static $panel_settings;
        $panel_setting_keys = array(
            'layout' => array('body_layout', ''),
            'mainColor' => array('primary_color', 'main_color'),
            'bgColor' => array('bg_color', ''),
        );
        global $inwave_theme_option;

        $value = '';
        if (!isset($panel_setting_keys[$key])) {
            return $value;
        }

        $value = self::getPostOption($panel_setting_keys[$key][1], $panel_setting_keys[$key][0]);
        $template = get_template();
        if ($inwave_theme_option['show_setting_panel'] && isset($_COOKIE[$template . '-setting']) && $_COOKIE[$template . '-setting']) {
            if (!$panel_settings) {
                $panel_settings = str_replace('\"', '"', $_COOKIE[$template . '-setting']);
                $panel_settings = @json_decode($panel_settings);
            }

            if (isset($panel_settings->$key) && $panel_settings->$key) {
                $value = $panel_settings->$key;
            }
        }

        return $value;
    }

    public static function getRevoSlider() {
        if (!class_exists('RevSliderFront')) {
            return array();
        }

        global $wpdb;

        // safe query: no input data
        $rs = $wpdb->get_results(
                "
            SELECT alias, title
            FROM " . $wpdb->prefix . "revslider_sliders
            ORDER BY title ASC LIMIT 100
            "
        );
        $sliders = array(
            '' => __('No Slider', 'injob')
        );
        if ($rs) {
            foreach ($rs as $slider) {
                $sliders[$slider->alias] = $slider->title;
            }
        }
        return $sliders;
    }

}
