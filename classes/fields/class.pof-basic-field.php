<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Basic Field
 *
 * @class   Panda_Options_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Field' ) ) {
	
	abstract class Panda_Options_Field {
		
		/**
		 * Field object
		 *
		 * @access public
		 * @var array
		 */
		
		public $field;
	
		/**
		 * Options
		 *
		 * @access public
		 * @var array
		 */
		
		public $options;
	
		/**
		 * Options prefix
		 *
		 * @access public
		 * @var string
		 */
		
		public $prefix;
		
		/**
		 * CSS Class for custom styles
		 *
		 * @access public
		 * @var string
		 */
		 
		public $main_css_class;
	
		/**
		 * Is field simple or not
		 *
		 * @access public
		 * @var bool
		 */
		
		public $simple_type = false;
		
		/**
		 * Constructor
		 */
				
		public function __construct( $field = null, $options = null, $prefix = null ) {
			
			$this->main_css_class = 'pof-options';
			
			if ( $field ) $this->field = (object) $field;
			if ( $options ) $this->options = $options;
			if ( $prefix ) $this->prefix = $prefix;
			
			$this->hooks();
		}
		
		/**
		 * Sanitize text input
		 *
		 * @param string Dirty text
		 *
		 * @return string Clean output
		 */
		
		public function get_sanitized_text( $text ) {
			
			$text = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $text );
			
			global $allowedposttags;
	
			$text = wp_kses( $text, $allowedposttags );
					
			return $text;			
		}
		
		/**
		 * Create markup
		 */		
		
		public function process() {
			
			if ( $this->check_field() ) {
							
				$class = '';
				
				! $this->simple_type ? $add = ' not-simple-type' : $add = ' simple-type';
		            
	            echo '<li class="grid-item' . $add . '" id="' . $this->field->id . '" data-type="' . $this->field->type . '">';
	                        
	            if ( ! $this->simple_type ) {
	                
	                echo '<table class="form-table">';
	
	                if ( ! empty( $this->field->class ) ) {
	                        $class = ' class="' . esc_attr( $this->field->class ) . '"';
	                }
	                		
	                echo "<tr{$class}>";
	
	                if ( ! empty( $this->field->label_for ) ) {
	                    
	                    echo '<th scope="row"><label for="' . esc_attr( $this->field->label_for ) . '">' . $this->field->title . '</label></th>';
	                } else {
	                    
	                    echo '<th scope="row">' . $this->field->title . '</th>';
	                }
	
	                echo '<td>';
	                
	                $this->output();
	                
	                echo '</td>';
	                
	                echo '</tr>';
	                
	                echo '</table>';
	                
	            } else {
		            
		           $this->output();
	            }
	            
	            echo '</li>';	
            
            }		
			
		}
		
		/**
		 * Field hooks
		 */
		
		public function hooks() { return  true; }
		
		/**
		 * Sanitize input
		 */
		
		public function sanitize( $value ) { return $value; }
		
		/**
		 * Print field content
		 *
		 * @abstract
		 */
				
		abstract public function output();
		
		/**
		 * Check required attributes of field
		 *
		 * @abstract
		 *
		 * @return bool All atts right or not
		 */
		
		abstract public function check_field();
				
	}
	
}