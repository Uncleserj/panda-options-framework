<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Googlefont Field
 *
 * @class   Panda_Options_Googlefont_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Googlefont_Field' ) ) {
	
	class Panda_Options_Googlefont_Field extends Panda_Options_Field {
		
		/**
		 * Ajax init
		 */
				
		public function ajax_init() {
						
			add_action( 'wp_ajax_ajax_get_google_font_variants', array( $this, 'ajax_get_google_font_variants' ) );

		}
		
		/**
		 * Ajax: get google fonts weight
		 */
		
		public function ajax_get_google_font_variants() {
			
			check_ajax_referer( POF, 'security' );
			
			$family = $_POST['family'];
			
			echo $this->get_google_font_variants( $family );
			
			wp_die();	
		}
		
		/**
		 * Field hooks
		 */
			
		public function hooks() {
			
			add_action( 'pof_googlefont_ajax', array( $this, 'ajax_init' ) );
			
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
				
				if ( ! wp_style_is( 'pof-googlefont-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-googlefont-field', __FILE__ ); ?>
			
					<style id="pof-googlefont-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' .pof-google-font-container .wp-color-result {border-color: ' . $admin_colors[3] . ' !important}'; ?>
						<?php echo '.' . $this->main_css_class . ' .pof-google-font-container select {background-color: ' . $admin_colors[1] . ' !important}'; ?>
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
			
			$truefonts = array('0' => __( 'Not choosen', POF_LANG ) );
			
			$weight = array( '0' => '0' );
			
			if ( file_exists( trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) ) . 'googlefonts.php' ) ) {
			
				$fonts = require( trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) ) . 'googlefonts.php' );
				
				if ( $fonts ) {
					
					$gf = array();
					
					$fonts = json_decode( $fonts );
					
					$i = 0;
													
					foreach ( $fonts->items as $item ) {
												
						$gf[$item->family . ', ' . $item->category] = $item->family;
																		
						if ( isset( $options[$field->name]['face'] ) && $options[$field->name]['face'] == $item->family . ', ' . $item->category) {
											
							$weight = array_merge( $weight, $item->variants );
							
						}
						
						$i++;
						
					}
														
					$truefonts = array_merge( $truefonts, $gf );
					
				}
			}
			
			?>
			<div class="pof-google-font-container">
			<?php
			
			if ( ! isset( $options[$field->name] ) ) $options[$field->name] = '';
						
				?>
				<?php if ( ! isset( $options[$field->name ]['color'] ) || ! $this->check_color( $options[$field->name ]['color'] ) ) $options[$field->name]['color'] = null; ?>
				
				<input class='pof-colorpicker' type='text' id='<?php echo $field->name . '_color'; ?>' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>][color]' value='<?php echo esc_attr( $options[$field->name]['color'] ); ?>' />
				
				<select class='select google-font-family' id='<?php echo $field->name . '_face'; ?>' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>][face]' style='background-color:#3369E8;'>
				<?php
				$i = 0;
																					
				foreach ( $truefonts as $k=>$v ) { ?>
				
					<?php if ( ! isset( $options[$field->name ]['face'] ) ) $options[$field->name]['face'] = '0'; ?>
				
					<option id='<?php echo $field->name . '_' . $i; ?>' <?php selected( esc_attr( $options[$field->name]['face'] ), $k, true ); ?> value='<?php echo esc_attr( $k ); ?>'><?php echo esc_attr( $v ); ?></option>	
				
				<?php 
					$i++;
				} ?>
				
				</select>
	
				<select class='select google-font-weight' id='<?php echo $field->name . '_weight'; ?>' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>][weight]' style='background-color:#D50F25;'>
				<?php
				$i = 0;
																					
				foreach ( $weight as $k=>$v ) { ?>
				
					<?php if ( ! isset( $options[$field->name ]['weight'] ) ) $options[$field->name]['weight'] = '0'; ?>
				
					<option id='<?php echo $field->name . '_' . $i; ?>' <?php selected( esc_attr( $options[$field->name]['weight'] ), $v, true ); ?> value='<?php echo $v; ?>'><?php echo $this->translate_google_variant( $v ); ?></option>	
				
				<?php 
					$i++;
				} ?>
				
				</select>
				
				<select class='select' id='<?php echo $field->name . '_size'; ?>' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>][size]' style='background-color:#EEB211;'>
				<?php
																					
				for ( $i = 8; $i < 113; $i++ ) { ?>
				
					<?php if ( ! isset( $options[$field->name ]['size'] ) ) $options[$field->name]['size'] = '14px'; ?>
				
					<option id='<?php echo $field->name . '_' . $i; ?>' <?php selected( esc_attr( $options[$field->name]['size'] ), $i . 'px', true ); ?> value='<?php echo esc_attr( $i . 'px' ); ?>'><?php echo esc_attr( $i . 'px' ); ?></option>	
				
				<?php
				} ?>
				
				</select>
				<?php if ( isset( $field->desc ) ) { ?>
				<label class="<?php echo $field->type; ?>-label" for="<?php echo $field->name; ?>"><?php echo $this->get_sanitized_text( $field->desc ); ?></label>
				<?php }
			?>
			</div>
			<?php
			
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
		 * Get true google weight
		 *
		 * @param string Weight
		 */
		
		public function translate_google_variant( $variant ) {
				
			$name = __( 'Not choosen', POF_LANG );
			
			switch ( $variant ) {
				case '100':
					$name	 = 'Thin 100';
					break;
				case '100italic':
					$name	 = 'Thin 100 Italic';
					break;
				case '200':
					$name	 = 'Extra-Light 200';
					break;
				case '200italic':
					$name	 = 'Extra-Light 200 Italic';
					break;
				case '300':
					$name	 = 'Light 300';
					break;
				case '300italic':
					$name	 = 'Light 300 Italic';
					break;
				case 'regular':
					$name	 = 'Normal 400';
					break;
				case 'italic':
					$name	 = 'Normal 400 Italic';
					break;
				case '500':
					$name	 = 'Medium 500';
					break;
				case '500italic':
					$name	 = 'Medium 500 Italic';
					break;
				case '600':
					$name	 = 'Semi-Bold 600';
					break;
				case '600italic':
					$name	 = 'Semi-Bold 600 Italic';
					break;
				case '700':
					$name	 = 'Bold 700';
					break;
				case '700italic':
					$name	 = 'Bold 700 Italic';
					break;
				case '800':
					$name	 = 'Extra-Bold 800';
					break;
				case '800italic':
					$name	 = 'Extra-Bold 800 Italic';
					break;
				case '900':
					$name	 = 'Ultra-Bold 900';
					break;
				case '900italic':
					$name	 = 'Ultra-Bold 900 Italic';
					break;
			}
			
			return $name;
		}
		
		/**
		 * Get font weights based on font family
		 *
		 * @param string Font family
		 */
		
		
		public function get_google_font_variants( $family ) {
			
			$weight = array( '0' => '0' );
					
			if ( file_exists( trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) ) . 'googlefonts.php' ) ) {
			
				$fonts = require( trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) ) . 'googlefonts.php' );
				
				if ( $fonts ) {
									
					$fonts = json_decode( $fonts );
																	
					foreach ( $fonts->items as $item ) {
						
						if ( $item->family == $family ) { 
							
							$weight = array_merge( $weight, $item->variants );
													
						}
											
					}
													
				}
			}
			
			ob_start();
			
			foreach ( $weight as $k=>$v ) { ?>
				
				<option value='<?php echo $v; ?>'><?php echo $this->translate_google_variant( $v ); ?></option>	
			
			<?php $i++;
			
			}
			
			$output = ob_get_clean();
			
			return $output;
			
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
			
			foreach ( $value as $k => $v ) {
				
				$v = $this->get_sanitized_text( $v );
				
				if ( $k == 'color' ) $v = $this->check_color( $v );
				
				$value[ $k ] = $v;
			}

			return $value;
			
		}
		
	}
	
}