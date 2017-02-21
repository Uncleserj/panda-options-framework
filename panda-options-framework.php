<?php
/**
 * @package           PandaOptionsFramework
 *
 * @wordpress-plugin
 * Plugin Name:       Panda Options Framework
 * Description:       Theme Options Plugin
 * Version:           1.0
 * Author:            Uncleserj
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       panda-options
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

$version = '1.0';

define( 'POF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'POF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'POF' ) ) define( 'POF', 'settings_for' );

if ( ! defined( 'POF_LANG' ) ) define( 'POF_LANG', 'panda-options' );

if ( ! isset( $GLOBALS[ 'pof_highest_version' ] ) ) $GLOBALS[ 'pof_highest_version' ] = $version;

/**
 * Get uri of options folder
 */
 
if ( ! function_exists( 'pof_get_uri' ) ) {
	
	function pof_get_uri() {
 
		$dirname        = wp_normalize_path( dirname( __FILE__ ) );
		$plugin_dir     = wp_normalize_path( WP_PLUGIN_DIR );
		$located_plugin = ( preg_match( '#'. $plugin_dir .'#', $dirname ) ) ? true : false;
		$directory      = ( $located_plugin ) ? $plugin_dir : get_template_directory();
		$directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_template_directory_uri();
		$basename       = str_replace( wp_normalize_path( $directory ), '', $dirname );
		$uri            = $directory_uri . $basename;
		
		return trailingslashit( $uri );

	}
	
}

/**
 * Get path of options folder
 */

if ( ! function_exists( 'pof_get_dir' ) ) {
	
	function pof_get_dir() {
 
		$dirname        = wp_normalize_path( dirname( __FILE__ ) );
		$plugin_dir     = wp_normalize_path( WP_PLUGIN_DIR );
		$located_plugin = ( preg_match( '#'. $plugin_dir .'#', $dirname ) ) ? true : false;
		$directory      = ( $located_plugin ) ? $plugin_dir : get_template_directory();
		$directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_template_directory_uri();
		$basename       = str_replace( wp_normalize_path( $directory ), '', $dirname );
		$dir            = $directory . $basename;
		
		return trailingslashit( $dir );

	}
	
}

/**
 * Load latest class
 */
	
if ( version_compare( $GLOBALS[ 'pof_highest_version' ], $version, '<' ) || $GLOBALS[ 'pof_highest_version' ] == $version ) {
	
	setlocale( LC_TIME, get_locale() );
		
	$GLOBALS[ 'pof_highest_version' ] = $version;
	
	$GLOBALS[ 'pof_true_class' ] = pof_get_dir() . 'classes/class.pof.php';
		
	$GLOBALS[ 'pof_base_url' ] = pof_get_uri();
			
}

/**
 * Init Class Path
 */

add_action( 'init',

	function() {
						
		require_once( $GLOBALS[ 'pof_true_class' ] );
		
		load_plugin_textdomain( 'panda-options', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
		if ( is_admin() ) {
						
			wp_enqueue_style( 'pof-admin-css', $GLOBALS[ 'pof_base_url' ] . 'css/options.css', array(), null, 'all' );
			
			wp_enqueue_script( 'pof-ajax', $GLOBALS[ 'pof_base_url' ] . 'js/options.js', array( 'jquery', 'wp-color-picker' ), null, true );
			
		}
				
	}
	
);

/**
 * Init Panda Options Framework
 */

add_action( 'init', 'panda_options_framework_plugin' );

function panda_options_framework_plugin() {
		
	$fields = null;
		
	if ( locate_template( 'fields.php' ) ) $fields = include_once( locate_template( 'fields.php' ) );
			
	$ops = new Panda_Options( $fields );
	
	/** 
	 * Examples
	 */
				
	// $ops->menu( 'Theme Options', 'Theme Options', null, true ); /* Customize menu item (icon: dashicon or url) */
	
	// $ops->first_section_hidden( false ); /* Hide first section if only one appear (True by default) */
	
	// $ops->content_max_width( '100%' ); /* Content maximum width (css value) */
	
	// $ops->fields_max_width( '680px' ); /* Fields maximum width (css value) */
	
	// $ops->title_width( '140px' ); /* Title width (css value) Default: 20% */
	
	// $ops->content_align( 'left' ); /* Content alignment (left, right or center) Default: center */
		
	// $ops->status_bar_hidden( true ); /* Hide status bar (False by default) */

}
 
/**
 * Global function: get option by name in theme
 *
 * @param string $options_name Name of option
 * @param string $prefix Options prefix
 * @return string|array|null Option
 */

if ( ! function_exists( 'get_ops' ) ) {

	function get_ops( $options_name, $prefix = null ) {
		
		$prefix ? $prefix = '_' . $prefix : $prefix = '_theme';
		
		$ops = get_option( POF . $prefix );
		
		if ( isset( $ops[$options_name] ) ) {
			
			return $ops[$options_name];
		} 
		
		return null;
	}

}

/**
 * Global function: print option by name in theme
 *
 * @param string $options_name Name of option
 * @param string $prefix Options prefix
 */
 
if ( ! function_exists( 'the_ops' ) ) {
	
	function the_ops( $options_name, $prefix = null ) {
		
		echo get_ops( $options_name, $prefix );
	}
	
}