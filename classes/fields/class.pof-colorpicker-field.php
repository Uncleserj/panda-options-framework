<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Colorpicker Field
 *
 * @class   Panda_Options_Colorpicker_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Colorpicker_Field' ) ) {
	
	class Panda_Options_Colorpicker_Field extends Panda_Options_Field {
		
		/**
		 * Field hooks
		 */
		
		public function hooks() {
		
			wp_enqueue_style( 'wp-color-picker' );
			
			add_action( 'admin_footer', function() {
			
				if ( ! wp_style_is( 'pof-colorpicker-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-colorpicker-field', __FILE__ ); ?>
			
					<style id="pof-colorpicker-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' .wp-color-result {background-color: ' . $admin_colors[0] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .wp-color-result:hover {background-color: ' . $admin_colors[0] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .wp-color-result:focus {background-color: ' . $admin_colors[0] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .wp-picker-clear {background-color: ' . $admin_colors[3] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .wp-picker-clear:hover {background-color: ' . $admin_colors[1] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .wp-picker-container.wp-picker-active .wp-picker-input-wrap {background-color: ' . $admin_colors[0] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .wp-picker-container.wp-picker-active .wp-picker-holder {background-color: ' . $admin_colors[0] . '}'; ?>
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
			
			isset( $field->std ) ? $default = $this->sanitize( $field->std ) : $default = null;
			
			if ( ! isset( $options[$field->name] ) ) $options[$field->name] = $default;
			?>
			<input class='pof-colorpicker' type='text' id='<?php echo $field->name; ?>' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>]' value='<?php echo esc_attr( $options[$field->name] ); ?>' />
			<?php if ( isset( $field->desc ) ) { ?>
			<label class="<?php echo $field->type; ?>-label" for="<?php echo $field->name; ?>"><?php echo $this->get_sanitized_text( $field->desc ); ?></label>
			<?php }
		}
		
		/**
		 * Check HEX color
		 *
		 * @param string HEX Value
		 */
		
		public function check_color( $color ) { 
		     
		    if ( preg_match( '/^#[a-f0-9]{6}$/i', $color ) ) {
			        
		        return $color;
		        
		    }
		     
		    return null;
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
			
			$value = $this->check_color( $value );
			
			return $value;
			
		}
		
	}
	
}