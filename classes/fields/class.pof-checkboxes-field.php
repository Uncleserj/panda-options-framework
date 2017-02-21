<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Checkboxes Field
 *
 * @class   Panda_Options_Checkboxes_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Checkboxes_Field' ) ) {
	
	class Panda_Options_Checkboxes_Field extends Panda_Options_Field {
				
		/**
		 * Print field content
		 */
		 
		public function output() {
			
			$field = $this->field;
			$options = $this->options;
			$prefix = $this->prefix;
			
			if ( isset( $field->values ) ) {
				
				isset( $field->std ) ? $default = $this->sanitize( $field->std ) : $default = array();
				
				if ( ! isset( $options[$field->name] ) ) $options[$field->name] = $default;
												
				$i = 0;
																					
				foreach ( $field->values as $k=>$v ) {
					
					if ( ! isset( $options[$field->name][$k] ) || $options[$field->name][$k] != '1' ) $options[$field->name][$k] = 0;
	
				?>
				<div class="checkbox-holder">
					<input type='hidden' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>][<?php echo esc_attr( $this->get_sanitized_text( $k ) ); ?>]' value='0'>
					<input type='checkbox' id='<?php echo $field->name . '_' . $i; ?>' class='checkbox' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>][<?php echo esc_attr( $this->get_sanitized_text( $k ) ); ?>]' <?php checked( intval( $options[$field->name][$k] ), 1, true ); ?> value='1'>
					
					<label class="<?php echo $field->type; ?>-label" for="<?php echo $field->name . '_' . $i; ?>"><span></span><?php echo $this->get_sanitized_text( $v ); ?></label>
				</div>
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
			
				if ( ! wp_style_is( 'pof-checkbox-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-checkbox-field', __FILE__ ); ?>
					
					<style id="u-theme-checkbox-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' .checkbox:checked + label:before {background-color: ' . $admin_colors[3] . '}'; ?>
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
						
			foreach ( $value as $k => $v )  { 
				
				$v = $this->get_sanitized_text( $v );
				
				$value[ $k ] = $v; 
				
			}

			return $value;
			
		}
		
	}
	
}