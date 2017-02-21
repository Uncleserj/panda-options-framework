<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Tabs Field
 *
 * @class   Panda_Options_Tabs_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Tabs_Field' ) ) {
	
	class Panda_Options_Tabs_Field extends Panda_Options_Field {
		
		/**
		 * Print field content
		 */
				
		public function output() {
			
			$field = $this->field;
			$options = $this->options;
			$prefix = $this->prefix;
						
			if ( isset( $field->values ) ) {
				
				isset( $field->std ) ? $default = intval( $field->std ) : $default = '';
				
				if ( ! isset( $options[$field->name] ) ) $options[$field->name] = $default;
				
				$i = 0;
				
				$count = count( $field->values );
							
				$width = ( 100 - ( $count - 1 ) * 0.25 ) / $count;
				
				$style = 'width: ' . $width . '%';
																					
				foreach ( $field->values as $k=>$v ) {
					
					$class = '';
		
					if ( $i == 0 && $count != 1 ) $class = 'first';
					elseif ( $i == ( $count - 1 ) && $count != 1 ) $class = 'last';
					elseif ( $count == 1 ) $class = 'first last';
	
	
				?>
				<input type='radio' id='<?php echo $field->name . '_' . $i; ?>' class='tabsradio' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>]' <?php checked( esc_attr( $options[$field->name] ), $k, true ); ?> value='<?php echo esc_attr( $k ); ?>'>
					
				<label style="<?php echo $style; ?>" class="<?php echo $field->type; ?>-label<?php echo ' ' . $class; ?>" for="<?php echo $field->name . '_' . $i; ?>"><span></span><?php echo $this->get_sanitized_text( $v ); ?></label>
				<?php 
					$i++;
				} 
			}
			
			echo '<div class="clear"></div>';
				
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
			
				if ( ! wp_style_is( 'pof-tabs-field', 'registered' ) ) {
				
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-tabs-field', __FILE__ ); ?>
			
					<style id="pof-tabs-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' .tabsradio:checked + label {background-color: ' . $admin_colors[3] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .tabsradio + label {background-color: ' . $admin_colors[1] . '}'; ?>
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