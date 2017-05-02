<?php
function leadx_demo_import_files() {
	return array(
		array(
			'import_file_name'             => 'Main Demo Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo-content/content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo-content/widgets.json',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'demo-content/customizer.dat',
			'import_preview_image_url'     => 'http://ttleadx.wpengine.com/wp-content/uploads/2016/08/ttleadx_main_demo2.png',
		),
		array(
			'import_file_name'             => 'Product Demo Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo-content/product-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo-content/product-widgets.json',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'demo-content/product-customizer.dat',
			'import_preview_image_url'     => 'http://ttleadx.wpengine.com/wp-content/uploads/2016/08/ttleadx_product_demo.png',
		),
		array(
			'import_file_name'             => 'Product 2 Demo Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo-content/product2-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo-content/product2-widgets.json',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'demo-content/product2-customizer.dat',
			'import_preview_image_url'     => 'http://ttleadx.wpengine.com/wp-content/uploads/2016/08/leadx_screenshot_product2.png',
		),
		array(
			'import_file_name'             => 'Catering Demo Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo-content/catering-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo-content/catering-widgets.json',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'demo-content/catering-customizer.dat',
			'import_preview_image_url'     => 'http://ttleadx.wpengine.com/wp-content/uploads/2016/08/leadx_screenshot_catering.png',
		),
		array(
			'import_file_name'             => 'Software Demo Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo-content/software-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo-content/software-widgets.json',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'demo-content/software-customizer.dat',
			'import_preview_image_url'     => 'http://ttleadx.wpengine.com/wp-content/uploads/2016/08/leadx_screenshot_software.png',
		),
		array(
			'import_file_name'             => 'App Demo Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo-content/app-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo-content/app-widgets.json',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'demo-content/app-customizer.dat',
			'import_preview_image_url'     => 'http://ttleadx.wpengine.com/wp-content/uploads/2016/08/leadx_screenshot_software2.png',
		),
		array(
			'import_file_name'             => 'Freelancer Demo Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo-content/freelancer-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo-content/freelancer-widgets.json',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'demo-content/freelancer-customizer.dat',
			'import_preview_image_url'     => 'http://ttleadx.wpengine.com/wp-content/uploads/2016/08/eadx_screenshot_freelancer.png',
		),
		array(
			'import_file_name'             => 'Property Demo Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo-content/property-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo-content/property-widgets.json',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'demo-content/property-customizer.dat',
			'import_preview_image_url'     => 'http://ttleadx.wpengine.com/wp-content/uploads/2016/08/leadx_screenshot_property.png',
		)
	);
}
add_filter( 'pt-ocdi/import_files', 'leadx_demo_import_files' );

function leadx_after_demo_import_setup($selected_import) {
	
	if ( 'Main Demo Import' === $selected_import['import_file_name'] ) {
		// Assign menus to their locations.
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
		$mobile_menu = get_term_by( 'name', 'Mobile Menu', 'nav_menu' );
	
		set_theme_mod( 'nav_menu_locations', array(
				'main' => $main_menu->term_id,
				'mobile' => $mobile_menu->term_id
			)
		);
	
		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );
	
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
	}
	elseif ( 'Product Demo Import' === $selected_import['import_file_name'] ) {
		// Assign menus to their locations.
		$main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
		$footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );
	
		set_theme_mod( 'nav_menu_locations', array(
				'main' => $main_menu->term_id,
				'footer' => $footer_menu->term_id
			)
		);
	
		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );
	
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
	}
	else {
		// Assign menus to their locations.
		$main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
		$footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );
	
		set_theme_mod( 'nav_menu_locations', array(
				'main' => $main_menu->term_id,
				'footer' => $footer_menu->term_id
			)
		);
	
		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );
	
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
	}

}
add_action( 'pt-ocdi/after_import', 'leadx_after_demo_import_setup' );