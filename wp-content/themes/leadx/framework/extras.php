<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function leadx_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
add_filter( 'wp_page_menu_args', 'leadx_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function leadx_body_classes( $classes ) {
    // Adds a class of group-blog to blogs with more than 1 published author.
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }
    // Font smoothing
	if ( get_theme_mod( 'leadx_enable_font_smoothing', 0 ) == 1 ) {
		$classes[] = 'smooth-fonts';
	}
	// Font smoothing
	if ( get_theme_mod( 'leadx_nav_show_active_dash', 'yes' ) == 'no' ) {
		$classes[] = 'no-dash';
	}
    
    return $classes;
}
add_filter( 'body_class', 'leadx_body_classes' );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function leadx_setup_author() {
    global $wp_query;

    if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
        $GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
    }
}
add_action( 'wp', 'leadx_setup_author' );

//Add filter for https bug
function leadx_check_is_ssl($url) {
if( is_ssl() )
    $url = str_replace( 'http://', 'https://', $url );
    return $url;
}
add_filter( 'wp_get_attachment_url', 'leadx_check_is_ssl' );

