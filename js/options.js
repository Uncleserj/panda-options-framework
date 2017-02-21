/*
 * Panda Options Framework
 *
 * Version: 1.0
 * Requires: jQuery v1.7+
 *
 * Author: Uncleserj <serj[at]serj[dot]pro>
 */

var $ = jQuery.noConflict();
var editor = false;

if ( $('#pof-form').attr( 'data-prefix' ) != null ) {
	var currentAjax = window[$('#pof-form').attr( 'data-prefix' ).toString()];
}


// AjaxOptions

function AjaxOptions( ajaxData, processString, completeString, successCallback, errorCallback, beforeCallback ) {
	
	ajaxData.security = currentAjax.ajaxnonce;
	ajaxData.ajaxprefix = currentAjax.ajaxprefix;
	
	jQuery.ajax({
		url: currentAjax.ajaxurl,
		type: 'POST',
		cache: true,
        timeout: 8000,
		data: ajaxData,
		beforeSend: function() { 
			$('#wp-admin-bar-pof-submit-top-button').find('div').text( processString );
			$('#successMessage, #errorMessage').hide();
			$('#loaderObject').fadeIn(300);
			
			if (beforeCallback) beforeCallback();
			
		},
	    error: function() { 
		    $('#wp-admin-bar-pof-submit-top-button').find('div').text( currentAjax.errorMessage );
			setTimeout("jQuery('#wp-admin-bar-pof-submit-top-button').find('div').text('');", 2000);
			
			$('#loaderObject').fadeOut(300, function() {
				$('#errorMessage').html(currentAjax.errorMessage);
			    $('#errorMessage').fadeIn(300);
			    setTimeout("jQuery('#errorMessage').fadeOut('slow');", 2000);
			});
			
			if (errorCallback) errorCallback();
			
		}, 
		success: function( returnedData ) {
			$('#wp-admin-bar-pof-submit-top-button').find('div').text( completeString );
			setTimeout("jQuery('#wp-admin-bar-pof-submit-top-button').find('div').text('');", 2000);
			
			if (successCallback) successCallback( returnedData );
		}
	});
}

// Ready

$(document).ready(function() {
	
	// Ajax Loader
	
	$('.pof-options').append(
		'<div id="ajax-loader-wrapper">'+
			'<div id="loaderObject">'+
				'<div class="loader-inner line-scale-pulse-out-rapid">'+
					'<div></div>'+
					'<div></div>'+
					'<div></div>'+
					'<div></div>'+
					'<div></div>'+
				'</div>'+
			'</div>'+
			'<span id="successMessage"></span>'+
			'<span id="errorMessage"></span>'+
		'</div>'
	);
		
	// Ajax Submit Options
	
    $('#pof-form').submit(function() {
	    
	    $('.grid-item').each(function() {
		    
		    if ($(this).attr('data-type') == 'editor') editor = true;
		    
	    });
	    
	    if ( editor ) tinyMCE.triggerSave();
	    	    
		$('#successMessage, #errorMessage').hide();
		
		$('#loaderObject').fadeIn(300);  
		
		$('#wp-admin-bar-pof-submit-top-button').find('div').text( currentAjax.optionsSaving );
		
		$(this).ajaxSubmit({
			error: function() {
				
				$('#wp-admin-bar-pof-submit-top-button').find('div').text( currentAjax.errorMessage );
				setTimeout("jQuery('#wp-admin-bar-pof-submit-top-button').find('div').text('');", 2000);
			     
				$('#loaderObject').fadeOut(300, function() {
					$('#errorMessage').html(currentAjax.errorMessage);
					$('#errorMessage').fadeIn(300);
			        setTimeout("jQuery('#errorMessage').fadeOut('slow');", 2000);
			    });    
			}, 
			success: function( returnedData ) {
				
				console.log(returnedData);
								
				$('#wp-admin-bar-pof-submit-top-button').find('div').text( currentAjax.successMessage );
				
				update_backup_settings_field( function() {
			     
				    $('#loaderObject').fadeOut(300, function() {
					    $('#successMessage').html(currentAjax.successMessage);
				        $('#successMessage').fadeIn(300);
				        setTimeout("jQuery('#successMessage').fadeOut('slow');", 2000);
				    });
			    
			    });
			}
		});
		
		return false;
	
	});
	
	if ( $('.backups-manager .backups').children().length ) $('.backups-manager .backups').addClass( 'border-top' );
	
	// Init jQuery Tabs
		
	$("#sections").tabs();
	
	// Insert Options Logic
	
	$('#pof-backup').on('change keyup paste mouseup', function() {

	        $('.backups-manager button.insert-options').attr( 'data-options', $( this ).val() );

	});
	
	// Bind backups buttons
	
	bind_ajax_backup_buttons();
	
	// Bind Submit Button
	
	bind_submit();
	
	// Bind Uploader Logic
	
	bind_uploader();
	
	// Init Scripts
			
	init_colorpicker();
	init_slider();
	init_knob();
	
	// Google Fonts
	
	bind_font_select();
			
});

// Init ColorPicker

function init_colorpicker() {
	$( '.pof-colorpicker' ).wpColorPicker();
}

// Init Slider

function init_slider() {
	$( '.pof-slider' ).slider({
		create: function() {
			var input = $(this).parent().find('input');
			$(this).find( '.ui-slider-handle' ).attr( 'value', input.val() + input.attr( 'data-sign' ) );
			$(this).slider( 'value', input.val() );
		},
		change: function( event, ui ) {
			var input = $(this).parent().find('input');
			$(this).find( '.ui-slider-handle' ).attr( 'value', input.val() + input.attr( 'data-sign' ) );
			input.val( ui.value );
		},
		slide: function( event, ui ) {
			var input = $(this).parent().find('input');
			$(this).find( '.ui-slider-handle' ).attr( 'value', input.val() + input.attr( 'data-sign' ) );
			input.val( ui.value );
		}
	});
	
	$( '.pof-slider' ).each(function() {
		var input = $(this).parent().find('input');
		$(this).slider( "option", "min", parseFloat( input.attr('data-min') ) );
		$(this).slider( "option", "max", parseFloat( input.attr('data-max') ) );
		$(this).slider( "option", "step", parseFloat( input.attr('data-step') ) );
	});	
}

// Init Knob

function init_knob() {
	$( '.pof-knob' ).knob({  
		'draw' : function () { 
			$( this.i ).val( this.cv + $( this.i ).attr( 'data-sign' ) ); 
			$( this.i ).css( 'font-size', '20px' );
			$( this.i ).css( 'font-weight', 'normal' );
			$( this.i ).parent().addClass('knob-holder');
		}	
	});	
}

// Bind Submit Button

function bind_submit() {
		
	$('#wp-admin-bar-pof-submit-top-button a').click( function(e) {
		
		e.preventDefault();
		
		$('#pof-form').submit();

		
	});
}

// Bind Uploader Buttons

function bind_uploader() {
	
	// Select and Submit
	
	$( '.pof-uploader-button' ).click( function(e) {
			    
	    var self = $(this);
		
		e.preventDefault();
		
        var custom_uploader = wp.media({
            title: currentAjax.uploaderTitle,
            button: {
                text: currentAjax.uploaderText
            },
            multiple: false
        })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            
            self.parent().find('.pof-preview').remove();
			self.parent().find('.pof-upload-link').remove();
			self.parent().find('.pof-uploader-button-delete').remove();
			
			var img = "<img class='pof-preview' src='" + attachment.url + "' />";
			self.parent().prepend(img);
			
			var link = "<p class='pof-upload-link'><i class='icon-link'></i> " + attachment.url + "</p>";
				
			var del = "<button class='pof-button pof-uploader-button-delete'>";
			del += "<i class='icon-block pof-uploader-icon'></i> Delete";
			del += "</button>";
		
			self.parent().append(link);
			self.parent().append(del);
			
            self.parent().find('.pof-preview').attr('src', attachment.url);
            self.parent().find('.pof-uploader').val(attachment.url);
                                    
		    $( '.pof-uploader-button-delete' ).click( function(e) {
			    
			    var self = $(this);
				
				e.preventDefault();
				
				self.parent().find('.pof-preview').remove();
				self.parent().find('.pof-upload-link').remove();
		        self.parent().find('.pof-uploader').val('');
		        self.remove();
		        		        
		        $('#pof-form').submit();
				
			});
	
            $('#pof-form').submit();

        })
        .open();
        
	});
	
	// Delete and Submit
	
    $( '.pof-uploader-button-delete' ).click( function(e) {
	    
	    var self = $(this);
		
		e.preventDefault();
		
		self.parent().find('.pof-preview').remove();
		self.parent().find('.pof-upload-link').remove();
        self.parent().find('.pof-uploader').val('');
        self.remove();
                        
        $('#pof-form').submit();
		
	});
}

// Bind Backups Buttons

function bind_ajax_backup_buttons() {
	
	// Ajax create backup
	
	$('.backups-manager button.create-options').on( 'click', function( event ) {
		
		unbind_ajax_backup_buttons();

		event.preventDefault();
		
		var value = $(this).attr('data-options');
						
		var data = { action: 'ajax_backup_create', value: value };
		
		var success = function() {
					
			update_backups_list( function() {
				
				$('#loaderObject').fadeOut(300, function() {
					$('#successMessage').html(currentAjax.backupCreated);
			    	$('#successMessage').fadeIn(300);
					setTimeout("jQuery('#successMessage').fadeOut('slow');", 2000);
				});
				
			});
			
		}
		
		AjaxOptions( data, currentAjax.backupCreating, currentAjax.backupCreated, success );
		
	});
	
	// Ajax restore options
	
	$('.backups-manager button.restore-options').on( 'click', function( event ) {
			
		event.preventDefault();
		
		var timeStamp = $(this).attr('id');
		
		var data = { action: 'ajax_backup_restore', time_stamp: timeStamp };
		
		var success = function( returnedData ) {
			
			$('#loaderObject').fadeOut(300, function() {
				$('#successMessage').html(currentAjax.optionsRestored);
			    $('#successMessage').fadeIn(300);
			    setTimeout( function() {
				    $('#successMessage').fadeOut('slow');
				    location.reload( true );
				    }, 2000
				);
			});
			
		}
		
		AjaxOptions( data, currentAjax.optionsRestoring, currentAjax.optionsRestored, success );
		
	});
	
	// Ajax insert options
	
	$('.backups-manager button.insert-options').on( 'click', function( event ) {
			
		unbind_ajax_backup_buttons();

		event.preventDefault();
		
		var value = $(this).attr('data-options');

		var data = { action: 'ajax_backup_insert', value: value };
		
		var success = function( returnedData ) {
			
			$('#loaderObject').fadeOut(300, function() {
				$('#successMessage').html(currentAjax.optionsRestored);
			    $('#successMessage').fadeIn(300);
			    setTimeout( function() {
				    $('#successMessage').fadeOut('slow');
				    location.reload( true );
				    }, 2000
				);
			});
			
		}
		
		AjaxOptions( data, currentAjax.optionsRestoring, currentAjax.optionsRestored, success );

	});
	
	// Ajax delete backup
	
	$('.backups-manager button.delete-options').on( 'click', function( event ) {
			
		event.preventDefault();
		
		var timeStamp = $(this).attr('id');
		
		var data = { action: 'ajax_backup_delete', time_stamp: timeStamp };
		
		var success = function( returnedData ) {
			
			update_backups_list( function() {
				
				$('#loaderObject').fadeOut(300, function() {
					$('#successMessage').html(currentAjax.backupDeleted);
			    	$('#successMessage').fadeIn(300);
					setTimeout("jQuery('#successMessage').fadeOut('slow');", 2000);
				});
				
			});
			
		}
		
		AjaxOptions( data, currentAjax.backupDeleting, currentAjax.backupDeleted, success );
		
	});

}

// Ajax update backups list

function update_backups_list( callback ) {

	var data = { action: 'ajax_backup_update_list' };
	
	var success = function( returnedData ) {
		
		$('.backups-manager .backups').html(returnedData);
		if ( $('.backups-manager .backups').children().length ) {
			$('.backups-manager .backups').addClass( 'border-top' );
		} else {
			$('.backups-manager .backups').removeClass( 'border-top' );
		}
		bind_ajax_backup_buttons();
		callback();
	}
	
	AjaxOptions( data, currentAjax.updatingBackups, currentAjax.updatedBackups, success );
	
}

// Ajax update backup textarea field

function update_backup_settings_field( callback ) {

	var data = { action: 'ajax_backup_update_settings_field' };
	
	var success = function( returnedData ) {
		
		$('#pof-backup').html(returnedData);
		$('.backups-manager button.create-options').attr('data-options', returnedData);
		$('.backups-manager button.insert-options').attr('data-options', returnedData);
		callback();
	}
	
	AjaxOptions( data, currentAjax.updatingOptions, currentAjax.successMessage, success );
	
}

// Google Fonts

function bind_font_select() {
	
	$('.google-font-family').change(function() {
		
		var select = $(this);
		
		var data = { action: 'ajax_get_google_font_variants', family: $(this).find('option:selected').text() };
		
		var success = function( returnedData ) {
						
			select.parent().find('.google-font-weight').html(returnedData);
			$('#loaderObject').fadeOut(300);

		}
		
		AjaxOptions( data, '', '', success );
	});
}

// Remove Uploader Click Events

function unbind_uploader() {
	
	$( '.pof-uploader-button' ).off( 'click' );
	$( '.pof-uploader-button-delete' ).off( 'click' );
}

// Remove Backup Click Events

function unbind_ajax_backup_buttons() {
	
	$('.backups-manager button.create-options').off( 'click' );
	$('.backups-manager button.insert-options').off( 'click' );
	$('.backups-manager button.restore-options').off( 'click' );
	$('.backups-manager button.delete-options').off( 'click' );
}

/*!jQuery Knob*/
/**
 * Downward compatible, touchable dial
 *
 * Version: 1.2.12
 * Requires: jQuery v1.7+
 *
 * Copyright (c) 2012 Anthony Terrien
 * Under MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * Thanks to vor, eskimoblood, spiffistan, FabrizioC
 */
!function(a){"object"==typeof exports?module.exports=a(require("jquery")):"function"==typeof define&&define.amd?define(["jquery"],a):a(jQuery)}(function(a){"use strict";var b={},c=Math.max,d=Math.min;b.c={},b.c.d=a(document),b.c.t=function(a){return a.originalEvent.touches.length-1},b.o=function(){var c=this;this.o=null,this.$=null,this.i=null,this.g=null,this.v=null,this.cv=null,this.x=0,this.y=0,this.w=0,this.h=0,this.$c=null,this.c=null,this.t=0,this.isInit=!1,this.fgColor=null,this.pColor=null,this.dH=null,this.cH=null,this.eH=null,this.rH=null,this.scale=1,this.relative=!1,this.relativeWidth=!1,this.relativeHeight=!1,this.$div=null,this.run=function(){var b=function(a,b){var d;for(d in b)c.o[d]=b[d];c._carve().init(),c._configure()._draw()};if(!this.$.data("kontroled")){if(this.$.data("kontroled",!0),this.extend(),this.o=a.extend({min:void 0!==this.$.data("min")?this.$.data("min"):0,max:void 0!==this.$.data("max")?this.$.data("max"):100,stopper:!0,readOnly:this.$.data("readonly")||"readonly"===this.$.attr("readonly"),cursor:this.$.data("cursor")===!0&&30||this.$.data("cursor")||0,thickness:this.$.data("thickness")&&Math.max(Math.min(this.$.data("thickness"),1),.01)||.35,lineCap:this.$.data("linecap")||"butt",width:this.$.data("width")||200,height:this.$.data("height")||200,displayInput:null==this.$.data("displayinput")||this.$.data("displayinput"),displayPrevious:this.$.data("displayprevious"),fgColor:this.$.data("fgcolor")||"#87CEEB",inputColor:this.$.data("inputcolor"),font:this.$.data("font")||"Arial",fontWeight:this.$.data("font-weight")||"bold",inline:!1,step:this.$.data("step")||1,rotation:this.$.data("rotation"),draw:null,change:null,cancel:null,release:null,format:function(a){return a},parse:function(a){return parseFloat(a)}},this.o),this.o.flip="anticlockwise"===this.o.rotation||"acw"===this.o.rotation,this.o.inputColor||(this.o.inputColor=this.o.fgColor),this.$.is("fieldset")?(this.v={},this.i=this.$.find("input"),this.i.each(function(b){var d=a(this);c.i[b]=d,c.v[b]=c.o.parse(d.val()),d.bind("change blur",function(){var a={};a[b]=d.val(),c.val(c._validate(a))})}),this.$.find("legend").remove()):(this.i=this.$,this.v=this.o.parse(this.$.val()),""===this.v&&(this.v=this.o.min),this.$.bind("change blur",function(){c.val(c._validate(c.o.parse(c.$.val())))})),!this.o.displayInput&&this.$.hide(),this.$c=a(document.createElement("canvas")).attr({width:this.o.width,height:this.o.height}),this.$div=a('<div style="'+(this.o.inline?"display:inline;":"")+"width:"+this.o.width+"px;height:"+this.o.height+'px;"></div>'),this.$.wrap(this.$div).before(this.$c),this.$div=this.$.parent(),"undefined"!=typeof G_vmlCanvasManager&&G_vmlCanvasManager.initElement(this.$c[0]),this.c=this.$c[0].getContext?this.$c[0].getContext("2d"):null,!this.c)throw{name:"CanvasNotSupportedException",message:"Canvas not supported. Please use excanvas on IE8.0.",toString:function(){return this.name+": "+this.message}};return this.scale=(window.devicePixelRatio||1)/(this.c.webkitBackingStorePixelRatio||this.c.mozBackingStorePixelRatio||this.c.msBackingStorePixelRatio||this.c.oBackingStorePixelRatio||this.c.backingStorePixelRatio||1),this.relativeWidth=this.o.width%1!==0&&this.o.width.indexOf("%"),this.relativeHeight=this.o.height%1!==0&&this.o.height.indexOf("%"),this.relative=this.relativeWidth||this.relativeHeight,this._carve(),this.v instanceof Object?(this.cv={},this.copy(this.v,this.cv)):this.cv=this.v,this.$.bind("configure",b).parent().bind("configure",b),this._listen()._configure()._xy().init(),this.isInit=!0,this.$.val(this.o.format(this.v)),this._draw(),this}},this._carve=function(){if(this.relative){var a=this.relativeWidth?this.$div.parent().width()*parseInt(this.o.width)/100:this.$div.parent().width(),b=this.relativeHeight?this.$div.parent().height()*parseInt(this.o.height)/100:this.$div.parent().height();this.w=this.h=Math.min(a,b)}else this.w=this.o.width,this.h=this.o.height;return this.$div.css({width:this.w+"px",height:this.h+"px"}),this.$c.attr({width:this.w,height:this.h}),1!==this.scale&&(this.$c[0].width=this.$c[0].width*this.scale,this.$c[0].height=this.$c[0].height*this.scale,this.$c.width(this.w),this.$c.height(this.h)),this},this._draw=function(){var a=!0;c.g=c.c,c.clear(),c.dH&&(a=c.dH()),a!==!1&&c.draw()},this._touch=function(a){var d=function(a){var b=c.xy2val(a.originalEvent.touches[c.t].pageX,a.originalEvent.touches[c.t].pageY);b!=c.cv&&(c.cH&&c.cH(b)===!1||(c.change(c._validate(b)),c._draw()))};return this.t=b.c.t(a),d(a),b.c.d.bind("touchmove.k",d).bind("touchend.k",function(){b.c.d.unbind("touchmove.k touchend.k"),c.val(c.cv)}),this},this._mouse=function(a){var d=function(a){var b=c.xy2val(a.pageX,a.pageY);b!=c.cv&&(c.cH&&c.cH(b)===!1||(c.change(c._validate(b)),c._draw()))};return d(a),b.c.d.bind("mousemove.k",d).bind("keyup.k",function(a){if(27===a.keyCode){if(b.c.d.unbind("mouseup.k mousemove.k keyup.k"),c.eH&&c.eH()===!1)return;c.cancel()}}).bind("mouseup.k",function(a){b.c.d.unbind("mousemove.k mouseup.k keyup.k"),c.val(c.cv)}),this},this._xy=function(){var a=this.$c.offset();return this.x=a.left,this.y=a.top,this},this._listen=function(){return this.o.readOnly?this.$.attr("readonly","readonly"):(this.$c.bind("mousedown",function(a){a.preventDefault(),c._xy()._mouse(a)}).bind("touchstart",function(a){a.preventDefault(),c._xy()._touch(a)}),this.listen()),this.relative&&a(window).resize(function(){c._carve().init(),c._draw()}),this},this._configure=function(){return this.o.draw&&(this.dH=this.o.draw),this.o.change&&(this.cH=this.o.change),this.o.cancel&&(this.eH=this.o.cancel),this.o.release&&(this.rH=this.o.release),this.o.displayPrevious?(this.pColor=this.h2rgba(this.o.fgColor,"0.4"),this.fgColor=this.h2rgba(this.o.fgColor,"0.6")):this.fgColor=this.o.fgColor,this},this._clear=function(){this.$c[0].width=this.$c[0].width},this._validate=function(a){var b=~~((a<0?-.5:.5)+a/this.o.step)*this.o.step;return Math.round(100*b)/100},this.listen=function(){},this.extend=function(){},this.init=function(){},this.change=function(a){},this.val=function(a){},this.xy2val=function(a,b){},this.draw=function(){},this.clear=function(){this._clear()},this.h2rgba=function(a,b){var c;return a=a.substring(1,7),c=[parseInt(a.substring(0,2),16),parseInt(a.substring(2,4),16),parseInt(a.substring(4,6),16)],"rgba("+c[0]+","+c[1]+","+c[2]+","+b+")"},this.copy=function(a,b){for(var c in a)b[c]=a[c]}},b.Dial=function(){b.o.call(this),this.startAngle=null,this.xy=null,this.radius=null,this.lineWidth=null,this.cursorExt=null,this.w2=null,this.PI2=2*Math.PI,this.extend=function(){this.o=a.extend({bgColor:this.$.data("bgcolor")||"#EEEEEE",angleOffset:this.$.data("angleoffset")||0,angleArc:this.$.data("anglearc")||360,inline:!0},this.o)},this.val=function(a,b){return null==a?this.v:(a=this.o.parse(a),void(b!==!1&&a!=this.v&&this.rH&&this.rH(a)===!1||(this.cv=this.o.stopper?c(d(a,this.o.max),this.o.min):a,this.v=this.cv,this.$.val(this.o.format(this.v)),this._draw())))},this.xy2val=function(a,b){var e,f;return e=Math.atan2(a-(this.x+this.w2),-(b-this.y-this.w2))-this.angleOffset,this.o.flip&&(e=this.angleArc-e-this.PI2),this.angleArc!=this.PI2&&e<0&&e>-.5?e=0:e<0&&(e+=this.PI2),f=e*(this.o.max-this.o.min)/this.angleArc+this.o.min,this.o.stopper&&(f=c(d(f,this.o.max),this.o.min)),f},this.listen=function(){var e,f,h,i,b=this,g=function(a){a.preventDefault();var g=a.originalEvent,h=g.detail||g.wheelDeltaX,i=g.detail||g.wheelDeltaY,j=b._validate(b.o.parse(b.$.val()))+(h>0||i>0?b.o.step:h<0||i<0?-b.o.step:0);j=c(d(j,b.o.max),b.o.min),b.val(j,!1),b.rH&&(clearTimeout(e),e=setTimeout(function(){b.rH(j),e=null},100),f||(f=setTimeout(function(){e&&b.rH(j),f=null},200)))},j=1,k={37:-b.o.step,38:b.o.step,39:b.o.step,40:-b.o.step};this.$.bind("keydown",function(e){var f=e.keyCode;if(f>=96&&f<=105&&(f=e.keyCode=f-48),h=parseInt(String.fromCharCode(f)),isNaN(h)&&(13!==f&&8!==f&&9!==f&&189!==f&&(190!==f||b.$.val().match(/\./))&&e.preventDefault(),a.inArray(f,[37,38,39,40])>-1)){e.preventDefault();var g=b.o.parse(b.$.val())+k[f]*j;b.o.stopper&&(g=c(d(g,b.o.max),b.o.min)),b.change(b._validate(g)),b._draw(),i=window.setTimeout(function(){j*=2},30)}}).bind("keyup",function(a){isNaN(h)?i&&(window.clearTimeout(i),i=null,j=1,b.val(b.$.val())):b.$.val()>b.o.max&&b.$.val(b.o.max)||b.$.val()<b.o.min&&b.$.val(b.o.min)}),this.$c.bind("mousewheel DOMMouseScroll",g),this.$.bind("mousewheel DOMMouseScroll",g)},this.init=function(){(this.v<this.o.min||this.v>this.o.max)&&(this.v=this.o.min),this.$.val(this.v),this.w2=this.w/2,this.cursorExt=this.o.cursor/100,this.xy=this.w2*this.scale,this.lineWidth=this.xy*this.o.thickness,this.lineCap=this.o.lineCap,this.radius=this.xy-this.lineWidth/2,this.o.angleOffset&&(this.o.angleOffset=isNaN(this.o.angleOffset)?0:this.o.angleOffset),this.o.angleArc&&(this.o.angleArc=isNaN(this.o.angleArc)?this.PI2:this.o.angleArc),this.angleOffset=this.o.angleOffset*Math.PI/180,this.angleArc=this.o.angleArc*Math.PI/180,this.startAngle=1.5*Math.PI+this.angleOffset,this.endAngle=1.5*Math.PI+this.angleOffset+this.angleArc;var a=c(String(Math.abs(this.o.max)).length,String(Math.abs(this.o.min)).length,2)+2;this.o.displayInput&&this.i.css({width:(this.w/2+4>>0)+"px",height:(this.w/3>>0)+"px",position:"absolute","vertical-align":"middle","margin-top":(this.w/3>>0)+"px","margin-left":"-"+(3*this.w/4+2>>0)+"px",border:0,background:"none",font:this.o.fontWeight+" "+(this.w/a>>0)+"px "+this.o.font,"text-align":"center",color:this.o.inputColor||this.o.fgColor,padding:"0px","-webkit-appearance":"none"})||this.i.css({width:"0px",visibility:"hidden"})},this.change=function(a){this.cv=a,this.$.val(this.o.format(a))},this.angle=function(a){return(a-this.o.min)*this.angleArc/(this.o.max-this.o.min)},this.arc=function(a){var b,c;return a=this.angle(a),this.o.flip?(b=this.endAngle+1e-5,c=b-a-1e-5):(b=this.startAngle-1e-5,c=b+a+1e-5),this.o.cursor&&(b=c-this.cursorExt)&&(c+=this.cursorExt),{s:b,e:c,d:this.o.flip&&!this.o.cursor}},this.draw=function(){var c,a=this.g,b=this.arc(this.cv),d=1;a.lineWidth=this.lineWidth,a.lineCap=this.lineCap,"none"!==this.o.bgColor&&(a.beginPath(),a.strokeStyle=this.o.bgColor,a.arc(this.xy,this.xy,this.radius,this.endAngle-1e-5,this.startAngle+1e-5,!0),a.stroke()),this.o.displayPrevious&&(c=this.arc(this.v),a.beginPath(),a.strokeStyle=this.pColor,a.arc(this.xy,this.xy,this.radius,c.s,c.e,c.d),a.stroke(),d=this.cv==this.v),a.beginPath(),a.strokeStyle=d?this.o.fgColor:this.fgColor,a.arc(this.xy,this.xy,this.radius,b.s,b.e,b.d),a.stroke()},this.cancel=function(){this.val(this.v)}},a.fn.dial=a.fn.knob=function(c){return this.each(function(){var d=new b.Dial;d.o=c,d.$=a(this),d.run()}).parent()}});
