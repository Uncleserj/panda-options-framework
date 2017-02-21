<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Editor Field
 *
 * @class   Panda_Options_Editor_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Editor_Field' ) ) {
	
	class Panda_Options_Editor_Field extends Panda_Options_Field {
		
		/**
		 * Print field content
		 */
				
		public function output() {
			
			$field = $this->field;
			$options = $this->options;
			$prefix = $this->prefix;
			
			isset( $field->std ) ? $default = $this->sanitize( $field->std ) : $default = '';
			
			if ( ! isset( $options[$field->name] ) ) $options[$field->name] = $default;
			
			isset( $field->atts ) ? $atts = $field->atts : $atts = array();
			
			$settings = shortcode_atts( array( 
				'quicktags' => true, 
				'media_buttons' => false,
				'textarea_rows' => 5
			), $atts );
			
			$settings['textarea_name'] = POF . $prefix . '[' . $field->name . ']';
			
			wp_editor( $options[$field->name], $field->name, $settings );
			?>
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
			
			$value = $this->get_sanitized_text( $value );
			
			return $value;
			
		}
		
	}
	
}