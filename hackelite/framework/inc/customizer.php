<?php
/**
 * Created by PhpStorm.
 * User: HUNGTX
 * Date: 4/1/2015
 * Time: 4:44 PM
 */

if(!function_exists('WP_Filesystem')){
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}
if(!defined('INWAVE_MAIN_COLOR')){
    define('INWAVE_MAIN_COLOR', '#2980b9');
}

class Inwave_Customizer {
	function __construct() {
		global $inwave_theme_option;

		/** Dynamic custom css*/

		add_action( 'inwave_of_save_options_after', array( $this, 'store_customize_file' ) );
		add_action( 'after_switch_theme', array( $this, 'store_customize_file' ), 99 );

		if ( ! is_admin() ) {
			/* Append panel setting to footer*/
			if ( Inwave_Helper::getThemeOption( 'show_setting_panel' ) ) {
				add_action( 'wp_footer', array( $this, 'append_options' ) );
			}
		}
	}

	public static function getCustomThemePath() {
		$uploads = wp_upload_dir();

		return $uploads['basedir'] . '/' . get_template();
	}

	public static function getColorFilePath() {
		return self::getCustomThemePath() . '/color.css';
	}

	public static function getCustomizeFilePath() {
		return self::getCustomThemePath() . '/customize.css';
	}

	public static function getColorFileUrl() {
		$color        = Inwave_Helper::getThemeOption( 'primary_color' );
		if ( $color && $color != INWAVE_MAIN_COLOR ) {
			$uploads           = wp_upload_dir();
			$theme_folder_name = get_template();

			return $uploads['baseurl'] . '/' . $theme_folder_name . '/color.css';
		} else {
			return get_template_directory_uri() . '/assets/css/color.css';
		}
	}

	public static function getCustomizeFileUrl() {
		$uploads           = wp_upload_dir();
		$theme_folder_name = get_template();

		return $uploads['baseurl'] . '/' . $theme_folder_name . '/customize.css';
	}

	/* Append panel setting to footer*/
	function append_options() {
		get_template_part( 'blocks/panel-settings' );
	}


	/** return/echo css custom and css configuration */
	static function store_customize_file(){
		WP_Filesystem();
		global $wp_filesystem;
		$custom_folder_path = self::getCustomThemePath();

		if ( ! $wp_filesystem->is_dir($custom_folder_path ) ) {

			if ( ! wp_mkdir_p($custom_folder_path ) ) {
				return false;
			}
		}

		//primary color
		$primary_color = Inwave_Helper::getThemeOption( 'primary_color' );
		if ( $primary_color && $primary_color != INWAVE_MAIN_COLOR ) {
			$colorText       = trim( $wp_filesystem->get_contents( get_template_directory() . '/assets/css/color.css' ) );
			$colorText       = str_replace( INWAVE_MAIN_COLOR, $primary_color, $colorText );
			$color_file_path = self::getColorFilePath();
			$wp_filesystem->put_contents( $color_file_path, $colorText );
		}

		//customize css
		$customText = inwave_custom_file_css();
		$customize_file_path = self::getCustomizeFilePath();
		$wp_filesystem->put_contents($customize_file_path, $customText);

	}

}
new Inwave_Customizer();