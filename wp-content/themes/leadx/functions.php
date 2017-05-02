<?php
/**
 * LeadX functions and definitions
 *
 */

/**
 * Define theme folder URL, saves querying the template directory multiple times.
 */
define('LEADX_THEME_DIRECTORY', esc_url(trailingslashit( get_template_directory_uri() )));

/**
 * Theme setup and custom theme supports.
 */
require_once get_template_directory() . '/framework/setup.php';

/**
 * Init Visual Composer
 */
if( function_exists('vc_set_as_theme') ){
    include_once get_template_directory() . '/framework/visual-composer/vc_init.php';
}
/**
 * Fix for metaboxes when TTBase Framework is deactivated
 */
if ( ! function_exists( 'rwmb_meta' ) ) {
    function rwmb_meta( $key, $args = '', $post_id = null ) {
        return false;
    }
}
