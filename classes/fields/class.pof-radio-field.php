<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Radio Field
 *
 * @class   Panda_Options_Radio_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Radio_Field' ) ) {
	
	class Panda_Options_Radio_Field extends Panda_Options_Field {
		
		/**
		 * Print field content
		 */
				
		public function output() {
			
			$field = $this->field;
			$options = $this->options;
			$prefix = $this->prefix;
						
			if ( isset( $field->values ) ) {
				
				isset( $field->std ) ? $default = $this->sanitize( $field->std ) : $default = '';
				
				if ( ! isset( $options[$field->name] ) ) $options[$field->name] = $default;
				
				$i = 0;
																					
				foreach ( $field->values as $k=>$v ) {
	
				?>
				<input type='radio' id='<?php echo $field->name . '_' . $i; ?>' class='radio' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>]' <?php checked( esc_attr( $options[$field->name] ), $k, true ); ?> value='<?php echo esc_attr( $k ); ?>'>
					
				<label class="<?php echo $field->type; ?>-label" for="<?php echo $field->name . '_' . $i; ?>"><span></span><?php echo $this->get_sanitized_text( $v ); ?></label>
				<?php 
					$i++;
				} 
				
			}
			
		}
		
		/**
		 * Check required attributes of field
		 */
		
		public function check_field() {
			
			if ( isset( $this->field->name ) && isset( $this->field->type ) && isset( $this->field->title ) && isset( $this->field->values ) ) return true;
			
			return false;
			
		}
		
		/**
		 * Field hooks
		 */
		
		public function hooks() {
						
			add_action( 'admin_footer', function() {
			
				if ( ! wp_style_is( 'pof-radio-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-radio-field', __FILE__ ); ?>
			
					<style id="pof-radio-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' .radio:checked + label:before {background-color: ' . $admin_colors[3] . '}'; ?>
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