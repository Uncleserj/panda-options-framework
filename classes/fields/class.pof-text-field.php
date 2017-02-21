<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Text Field
 *
 * @class   Panda_Options_Text_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Text_Field' ) ) {
	
	class Panda_Options_Text_Field extends Panda_Options_Field {
		
		/**
		 * Print field content
		 */
						
		public function output() {
			
			$field = $this->field;
			$options = $this->options;
			$prefix = $this->prefix;
			
			isset( $field->std ) ? $default = $this->sanitize( $field->std ) : $default = '';
			
			if ( ! isset( $options[$field->name] ) ) $options[$field->name] = $default;
			isset( $field->placeholder ) ? $placeholder = $this->get_sanitized_text( $field->placeholder ) : $placeholder = '';
			?>
			<input type='text' id='<?php echo $field->name; ?>' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>]' value='<?php echo esc_attr( $options[$field->name] ); ?>' placeholder='<?php echo $placeholder; ?>' />
			
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
		 * Field hooks
		 */
		
		public function hooks() {
						
			add_action( 'admin_footer', function() {
			
				if ( ! wp_style_is( 'pof-text-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-text-field', __FILE__ ); ?>
			
					<style id="pof-text-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' input[type=text]:focus {border-color: ' . $admin_colors[0] . ';-webkit-box-shadow: 0 0 2px ' . $admin_colors[0] . ';box-shadow: 0 0 2px ' . $admin_colors[0] . '}'; ?>
					</style>
			
			<?php }
				
			} );
			
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