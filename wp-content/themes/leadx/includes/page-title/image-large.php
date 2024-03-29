<?php
$id = leadx_get_the_post_id();
$page_title_pos = get_post_meta( $id, 'leadx_post_pagetitle_pos', true );
if ( $page_title_pos == '' ) {
	$page_title_pos = get_theme_mod( 'leadx_pagetitle_pos', 'text-left' );
}
$page_title = leadx_get_page_title($id);

$image = get_post_meta( $id, 'leadx_post_header_image', true );
$image_url = wp_get_attachment_image_src( $image, 'full' );
if (get_post_meta( $id, 'leadx_post_header_image_parallax', true ) == true ) {
	$image_style = ' data-parallax-image="' . esc_url( $image_url[0] ) . '"';
    $parallax = ' parallax-section';
}else {
    $image_style = ' style="background: url(' . esc_url( $image_url[0] ) . ') no-repeat 50% 50%;background-size:cover;"';
	$parallax = '';
}

$pagetitle_color = get_post_meta( $id, 'leadx_post_pagetitle_color', true );
$pagetitle_style = ($pagetitle_color != '') ? ' style="color:'.esc_attr($pagetitle_color).';"' : '';

$pagetitle_underline_color = get_post_meta( $id, 'leadx_post_pagetitle_underline_color', true );
$pagetitle_underline_style = ($pagetitle_underline_color != '') ? ' style="border-color:'.esc_attr($pagetitle_underline_color).';"' : '';

$breadcrumbs = (get_post_meta( $id, 'leadx_post_breadcrumbs_hide', true) != true) ? '' : 'no-breadcrumbs';

$overlay_opacity = get_post_meta( $id, 'leadx_post_pagetitle_image_overlay_opacity', true );
$overlay_opacity = ($overlay_opacity != '') ? $overlay_opacity : 1;
$overlay_color = get_post_meta( $id, 'leadx_post_pagetitle_image_overlay_color', true );
$overlay_color = ($overlay_color != '') ? ' style="background:' . leadx_hex2rgba( $overlay_color, $overlay_opacity ) . ';"' : ''; 

$shortcodeImage = '<div class="background large image ' . sanitize_html_class( $parallax ) . ' ' . sanitize_html_class( $page_title_pos ) . ' ' . sanitize_html_class($breadcrumbs) . '"' . $image_style . '>';
$shortcodeImage .= '<span class="overlay"' . $overlay_color . '"></span>';
$shortcodeImage .= '<div class="container">';
$shortcodeImage .= '<div class="row header_text_wrapper v-align"><div class="col-xs-12">';
$shortcodeImage .= '<h1' . $pagetitle_style . '>' . esc_attr( $page_title ) . '</h1>';
$show_separator = get_theme_mod('leadx_show_pagetitle_separator', 'yes');
if ($show_separator == 'yes') {
	$shortcodeImage .= '<span class="separator"' . $pagetitle_underline_style . '></span>';
}
$shortcodeImage .= '</div></div><div class="row"><div class="col-xs-12">' . leadx_breadcrumbs() . '</div></div></div></div>';
echo do_shortcode($shortcodeImage);