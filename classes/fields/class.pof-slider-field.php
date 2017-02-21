<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Slider Field
 *
 * @class   Panda_Options_Slider_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Slider_Field' ) ) {
	
	class Panda_Options_Slider_Field extends Panda_Options_Field {
		
		/**
		 * Field hooks
		 */
		
		public function hooks() {
					
			wp_enqueue_script( 'jquery-ui-slider' );
			
			add_action( 'admin_footer', function() {
			
				if ( ! wp_style_is( 'pof-slider-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-slider-field', __FILE__ ); ?>
			
					<style id="pof-slider-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' .pof-slider.ui-slider .ui-slider-handle {background-color: ' . $admin_colors[3] . '}'; ?>
					</style>
			
			<?php }
				
			} );
			
		}
		
		/**
		 * Print field content
		 */
				
		public function output() {
			
			$field = $this->field;
			$options = $this->options;
			$prefix = $this->prefix;
			
			isset( $field->std ) ? $default = $this->sanitize( $field->std ) : $default = 0;
			
			if ( ! isset( $options[$field->name] ) ) $options[$field->name] = $default;
			
			if ( ! isset( $field->min ) ) $field->min = 0;
			
			if ( ! isset( $field->max ) ) $field->max = 100;
			
			if ( ! isset( $field->step ) ) $field->step = 1;
			
			if ( ! isset( $field->sign ) ) $field->sign = '';
	
			if ( floatval($options[$field->name]) < floatval($field->min) ) $options[$field->name] = $field->min;
			
			if ( floatval($options[$field->name]) > floatval($field->max) ) $options[$field->name] = $field->max;
			?>
			<input type='hidden' data-min='<?php echo $field->min; ?>' data-max='<?php echo $field->max; ?>' data-step='<?php echo $field->step; ?>' id='<?php echo $field->name; ?>' data-sign='<?php echo $field->sign; ?>' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>]' value='<?php echo esc_attr( $options[$field->name] ); ?>' />
					
			<div class="pof-slider"></div> 
			<?php if ( isset( $field->desc ) ) { ?>
			<label class="<?php echo $field->type; ?>-label" for="<?php echo $field->name; ?>"><?php echo $this->get_sanitized_text( $field->desc ); ?></label>
			<?php }
		}
		
		/**
		 * Check required attributes of field
		 */
		 
		public function check_field() {
			
			if ( isset( $this->field->name ) && isset( $this->field->type ) && isset( $this->field->title ) ) return true;
			
			return false;
			
		}
		
		/**
		 * Sanitize input
		 *
		 * @param string|int|array Dirty value
		 */
		
		public function sanitize( $value ) { 
			
			$value = floatval( $value );
			
			return $value;
			
		}
		
	}
	
}