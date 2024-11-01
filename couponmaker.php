<?php

/*
Plugin Name: Wp coupon widget
Plugin URI: http://mybodybuildingcoupons.com
Description: Wp Coupon Plugin for WordPress used as a widget.Thanks for your feedback.
Version: 1.1
Author: serge raoul
Author URI: http://mybodybuildingcoupons.com
*/

/*  Copyright .. well, more like Copyleft ... 2011  Serge raoul 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/


// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_wpcoupon_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little Google search form.
	function widget_wpcoupon($args) {
		

		extract($args); // <--- ok

		// Each widget can store its own options. We keep strings here.
		$options 		= get_option('widget_wpcoupon');
		$savings 		= $options['savings'];
		$description 	= $options['description'];
		$expiration 	= $options['expiration'];
		$link		 	= $options['link'];

		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.
		echo $before_widget . $before_title . $after_title;
		$url_parts = parse_url(get_bloginfo('home'));
		
		$lnkarr = explode('.',$link);
		
echo "

	<style>

	div.couponclip {
	border: 3px dashed silver;
	width: 150px;
	}

	div.savings {
	font-family: arial; color: navy; text-align: center; font-weight:bold; font-size: 22pt;
	width: 150px; 
	}

	div.onwhat {
	text-align: center; font-size: 14pt;
	width: 150px; 
	}

	div.couponcode {
	text-align: center; font-size: 8pt;
	color: navy;
	width: 150px;
	}

	</style>


	<div class=couponclip>

		<br> <!-- save this much -->	
		<div class=savings>".$savings."</div>

		<br> <!-- savings on what -->
		<div class=onwhat>".$description."</div>

		<br> <!-- expires -->
		<div class=couponcode>Expires:<br> ".$expiration."</div>
		<br>
		
		<br> <!-- expires -->
		<div class=couponcode>Link:<br> <a href='".$link."'>$lnkarr[1]</a></div>
		<br>

	</div>


";

		echo $after_widget;
		
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_wpcoupon_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_wpcoupon');
		if ( !is_array($options) )
			$options = array(	
				
						'savings'=>"Save $1.00", // USER SHOULD BE ABLE TO OPTIONALLY GIVE THIS WIDGET A TITLE
						'description'=>__('On Any Generic Product You Want To Sell Here Now', 'widgets'), // USER SHOULD BE ABLE TO TYPE NAME
						'expiration'=>"August 18, 2008",  // USER SHOULD EXPRESS PRIDE IN NAME
						
						'link'	=>'http://www.mavendeveloper.com'
												
						);						
			
		if ( $_POST['wpcoupon-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['savings'] 	= strip_tags(stripslashes($_POST['wpcoupon-savings']));
			$options['description'] = strip_tags(stripslashes($_POST['wpcoupon-description']));
			$options['expiration'] 	= strip_tags(stripslashes($_POST['wpcoupon-expiration']));
			$options['link'] 	    = strip_tags(stripslashes($_POST['wpcoupon-link']));
						
			update_option('widget_wpcoupon', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$savings 		= htmlspecialchars($options['savings'], ENT_QUOTES);
		$description 	= htmlspecialchars($options['description'], ENT_QUOTES);
		$expiration 	= htmlspecialchars($options['expiration'], ENT_QUOTES);	
		$link 	= htmlspecialchars($options['link'], ENT_QUOTES);	
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		
		// SAVINGS
		echo '<p style="text-align:left;"><label for="wpcoupon-savings">' . __('Savings:') . ' <input style="width: 200px;" id="wpcoupon-savings" name="wpcoupon-savings" type="text" value="'.$savings.'" /></label></p>';
		
		// DESCRIPTION
		echo '<p style="text-align:left;"><label for="wpcoupon-description">' . __('Description:', 'widgets') . ' <input style="width: 300px;" id="wpcoupon-description" name="wpcoupon-description" type="text" value="'.$description.'" /></label></p>';
		
		// EXPIRES
		echo '<p style="text-align:left;"><label for="wpcoupon-expiration">' . __('Expires On:', 'widgets') . ' <input style="width: 300px;" id="wpcoupon-expiration" name="wpcoupon-expiration" type="text" value="'. $expiration.'" /></label></p>';
		
		echo '<p style="text-align:left;"><label for="wpcoupon-link">' . __('Link:', 'widgets') . ' <input style="width: 300px;" id="wpcoupon-link" name="wpcoupon-link" type="text" value="'. $link.'" /></label></p>';
		
		echo '<input type="hidden" id="wpcoupon-submit" name="wpcoupon-submit" value="1" />';
		
	} // end FCN widget_wpcoupon_control
	
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array(widget_button_name_coupon(), 'widgets'), 'widget_wpcoupon');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 450x150 pixel form.
	register_widget_control(array(widget_button_name_coupon(), 'widgets'), 'widget_wpcoupon_control', 450, 150);
}

function widget_button_name_coupon(){
	return 'wpCouponMaker';
}




// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_wpcoupon_init');

function custom_footer(){
	 
	echo '<div id="footer" class ="footer"><em style="display:block;float:right;width:200px;">couponwidget powered by</a></em>';
}

add_action ( 'wp_footer', 'custom_footer');

?>