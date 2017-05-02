<?php
function leadx_js_custom() {
	
	?>

	<script type="text/javascript">
	jQuery(document).ready(function($){
		"use strict";

	    <?php if ( class_exists('Woocommerce') ) { ?>
		    <?php if(get_theme_mod('leadx_wc_second_image') != 'no') { ?>
    			/* WooCommerce: Second Image on Hover */
    			$( 'ul.products li.pif-has-gallery a:first-child' ).hover( function() {
    				$( this ).children( '.secondary-image' ).stop().animate({'opacity' : 1}, 'fast');
    			}, function() {
    				$( this ).children( '.secondary-image' ).stop().animate({'opacity' : 0}, 'fast');
    			});		
		    <?php } ?>
	    <?php } ?>
	});
	</script>
	
<?php 
}
add_action( 'wp_footer', 'leadx_js_custom', 100 );
?>