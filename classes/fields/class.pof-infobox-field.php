<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Infobox Field
 *
 * @class   Panda_Options_Infobox_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Infobox_Field' ) ) {
	
	class Panda_Options_Infobox_Field extends Panda_Options_Field {
		
		/**
		 * Is field simple or not
		 *
		 * @access public
		 * @var bool
		 */
		
		public $simple_type = true;
		
		/**
		 * Print field content
		 */		
				
		public function output() {
			
			$field = $this->field;
			
			$align = 'center';
			
			if ( isset( $field->align ) ) {
								
				switch ( $field->align ) {
					
					case 'left': $align = 'left';break;
					case 'right': $align = 'right';break;
					case 'center': $align = 'center';break;
					default: $align = null;break;
					
				}
							
			}
			
			if ( $align ) $align_style = ' style="text-align:' . $align . ';"';
			?>
			<div class="infobox">
			<?php
				?>
				<h2<?php echo $align_style; ?> data-align="<?php echo $align; ?>"><i class="icon-info"></i><?php echo $this->get_sanitized_text( $field->title ); ?></h2>
				<?php
				?>
					<div class="info-content"><?php echo $this->get_sanitized_text( $field->html ); ?></div>
			</div>
			<?php		
		}
		
		/**
		 * Check required attributes of field
		 */
		 
		public function check_field() { return true; }
		
		/**
		 * Field hooks
		 */
		
		public function hooks() {
						
			add_action( 'admin_footer', function() {
			
				if ( ! wp_style_is( 'pof-infobox-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-infobox-field', __FILE__ ); ?>
			
					<style id="u-theme-infobox-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' .infobox {background-color: ' . $admin_colors[3] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .infobox {border-color: ' . $admin_colors[1] . '}'; ?>
					</style>
			
			<?php }
				
			} );
			
		}
		
	}
	
}
?>
