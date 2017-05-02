<?php
/**
 * Declaring menus & widgets
 *
 */

/**
 * Register menus
 */
if(!( function_exists('leadx_register_nav_menus') )){
    function leadx_register_nav_menus() {
        register_nav_menus(
            array(
                'main'  => esc_html__( 'Main Menu', 'leadx' ),
                'mobile'  => esc_html__( 'Mobile Menu', 'leadx' )
            )
        );
    }
    add_action( 'init', 'leadx_register_nav_menus' );
}

/**
 * Register sidebars and footer widgets
 */
if(! function_exists('leadx_widgets_init')) {
    function leadx_widgets_init()
    {
        //Sidebars
        register_sidebar(array(
            'name' => esc_html__('Blog Sidebar', 'leadx'),
            'id' => 'sidebar-blog',
            'description' => esc_html__('Sidebar for the blog', 'leadx'),
            'before_widget' => '<aside id="%1$s" class="sidebar widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h5 class="title">',
            'after_title' => '</h5>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Page Sidebar', 'leadx'),
            'id' => 'sidebar-page',
            'description' => esc_html__('Sidebar for the page with sidebar template', 'leadx'),
            'before_widget' => '<aside id="%1$s" class="sidebar widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h5 class="title">',
            'after_title' => '</h5>',
        ));
        
        if( class_exists('Woocommerce') ){
            register_sidebar(array(
                'name' => esc_html__('Shop Sidebar', 'leadx'),
                'id' => 'sidebar-shop',
                'description' => esc_html__('Sidebar for the shop', 'leadx'),
                'before_widget' => '<aside id="%1$s" class="sidebar widget %2$s">',
                'after_widget' => '</aside>',
                'before_title' => '<h5 class="title">',
                'after_title' => '</h5>',
            ));
        }
        //Header
        register_sidebar(array(
            'name' => esc_html__('Header', 'leadx'),
            'id' => 'header-widgets',
            'description' => esc_html__('Header Topbar Widget Area', 'leadx'),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget' => '</div>'
        ));

        //Footer
        register_sidebar(array(
            'name' => esc_html__('Footer One', 'leadx'),
            'id' => 'footer-1',
            'description' => esc_html__('Add content to the footer', 'leadx'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="title">',
            'after_title' => '</h4>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Footer Two', 'leadx'),
            'id' => 'footer-2',
            'description' => esc_html__('Add content to the footer', 'leadx'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="title">',
            'after_title' => '</h4>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Footer Three', 'leadx'),
            'id' => 'footer-3',
            'description' => esc_html__('Add content to the footer', 'leadx'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="title">',
            'after_title' => '</h4>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Footer Four', 'leadx'),
            'id' => 'footer-4',
            'description' => esc_html__('Add content to the footer', 'leadx'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="title">',
            'after_title' => '</h4>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Footer Bottom Right', 'leadx'),
            'id' => 'footer-bottom-right',
            'description' => esc_html__('Footer Bottom Widget Area', 'leadx'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '',
            'after_title' => '',
        ));
    }

}
add_action( 'widgets_init', 'leadx_widgets_init' );