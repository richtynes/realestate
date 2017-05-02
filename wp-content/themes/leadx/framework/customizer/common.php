<?php
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

class LeadX_Customizer_Common {
	/**
	 * The singleton manager instance
	 *
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	public function __construct( WP_Customize_Manager $wp_manager ) {
		// set the private propery to instance of wp_manager
		$this->wp_customize = $wp_manager;
		
		// register the settings/panels/sections/controls, main method
		$this->leadx_customize_common_register();

		/**
		 * Action and filters
		 */

		// render the CSS and cache it to the theme_mod when the setting is saved
		add_action( 'customize_save_after' , array( $this, 'leadx_cache_rendered_css' ) );

		// save logo width/height dimensions
		add_action( 'customize_save_logo_img' , array( $this, 'leadx_save_logo_dimensions' ), 10, 1 );

		// flush the rewrite rules after the customizer settings are saved
		add_action( 'customize_save_after', 'leadx_flush_rewrite_rules' );

		// handle the postMessage transfer method with some dynamically generated JS in the footer of the theme
		add_action( 'wp_footer', array( $this, 'leadx_customize_footer_js' ), 30 );
	}

	/**
	* This hooks into 'customize_register' (available as of WP 3.4) and allows
	* you to add new sections and controls to the Theme Customize screen.
	*
	* Note: To enable instant preview, we have to actually write a bit of custom
	* javascript. See live_preview() for more.
	*
	* @see add_action('customize_register',$func)
	*/
	public function leadx_customize_common_register () {
		/**
		 * Settings
		 */

		/**
         * Logo
         */
		$this->wp_customize->add_section( 'leadx_section_logos', array(
				'title'       => esc_html_x( 'Logo', 'backend', 'leadx' ),
				'priority'    => 22
		) );
		$this->wp_customize->add_setting( 'leadx_logo_img', array( 'default' =>  get_template_directory_uri() . '/img/logo.png', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
            $this->wp_customize,
            'leadx_logo_img',
            array(
                'priority'    => 10,
                'label'       => esc_html_x( 'Logo Image', 'backend', 'leadx' ),
                'description' => esc_html_x( 'Recommended height for the Logo is 50px.', 'backend', 'leadx' ),
                'section'     => 'leadx_section_logos',
            )
        ) );
        $this->wp_customize->add_setting( 'leadx_logo_img_transparent', array( 'default' =>  get_template_directory_uri() . '/img/logo_transparent.png', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
            $this->wp_customize,
            'leadx_logo_img_transparent',
            array(
                'priority'    => 15,
                'label'       => esc_html_x( 'Logo Image (Transparent)', 'backend', 'leadx' ),
                'description' => esc_html_x( 'Alternative Logo for the transparent header styles.', 'backend', 'leadx' ),
                'section'     => 'leadx_section_logos',
            )
        ) );
		$this->wp_customize->add_setting( 'leadx_logo2x_img', array( 'default' =>  get_template_directory_uri() . '/img/logo2x.png', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
				$this->wp_customize,
				'leadx_logo2x_img',
				array(
						'priority'    => 20,
						'label'       => esc_html_x( 'Retina Logo Image', 'backend', 'leadx' ),
						'description' => esc_html_x( '2x logo size, for screens with high DPI.', 'backend', 'leadx' ),
						'section'     => 'leadx_section_logos',
				)
		) );
		$this->wp_customize->add_setting( 'leadx_logo2x_img_transparent', array( 'default' =>  get_template_directory_uri() . '/img/logo_transparent2x.png', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
				$this->wp_customize,
				'leadx_logo2x_img_transparent',
				array(
						'priority'    => 30,
						'label'       => esc_html_x( 'Retina Logo Image (Transparent)', 'backend', 'leadx' ),
						'description' => esc_html_x( '2x logo size, for screens with high DPI.', 'backend', 'leadx' ),
						'section'     => 'leadx_section_logos',
				)
		) );
		
		$this->wp_customize->add_setting( 'leadx_logo_mobile', array( 'default' =>  get_template_directory_uri() . '/img/logo_small.png', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
				$this->wp_customize,
				'leadx_logo_mobile',
				array(
						'priority'    => 34,
						'label'       => esc_html_x( 'Sticky & Mobile Logo Image', 'backend', 'leadx' ),
						'description' => esc_html_x( 'Logo for the sticky and the mobile header.', 'backend', 'leadx' ),
						'section'     => 'leadx_section_logos',
				)
		) );
		$this->wp_customize->add_setting( 'leadx_logo2x_mobile', array( 'default' =>  get_template_directory_uri() . '/img/logo_small2x.png', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
				$this->wp_customize,
				'leadx_logo2x_mobile',
				array(
						'priority'    => 36,
						'label'       => esc_html_x( 'Sticky & Mobile Retina Logo Image', 'backend', 'leadx' ),
						'description' => esc_html_x( '2x logo size, for screens with high DPI.', 'backend', 'leadx' ),
						'section'     => 'leadx_section_logos',
				)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_logo_width', array(
			'default'				=> '',
			'sanitize_callback' 	=> 'sanitize_text_field',
			'css_map'				=> array(
				'width' => array(
					'.logo-wrapper img'
				),
			)
		) ) );
		$this->wp_customize->add_control( 'leadx_logo_width', array(
			'type'        => 'text',
			'priority'    => 38,
			'label'       => esc_html_x( 'Logo Width', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Set the Logo Width for the normal header (Default 160px).', 'backend', 'leadx' ),
			'section'     => 'leadx_section_logos',
		) );
        $this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_logo_padding_top', array(
			'default'			=> '0',
			'sanitize_callback' => 'sanitize_text_field',
			'css_map'			=> array(
				'padding-top' => array(
					'.navigation'
				),
			)
		) ) );
		$this->wp_customize->add_control( 'leadx_logo_padding_top', array(
			'type'        => 'text',
			'priority'    => 40,
			'label'       => esc_html_x( 'Logo top padding', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add padding between the logo and the top navigation area. Please include px.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_logos',
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_logo_padding_bottom', array(
			'default' => '0',
			'sanitize_callback' => 'sanitize_text_field',
			'css_map' => array(
				'padding-bottom' => array(
					'.navigation'
				),
			)
		) ) );
		$this->wp_customize->add_control( 'leadx_logo_padding_bottom', array(
			'type'        => 'text',
			'priority'    => 50,
			'label'       => esc_html_x( 'Logo bottom padding', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add padding between the logo and the bottom navigation area. Please include px.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_logos',
		) );

        /**
         * Header & Navigation
         */
        $this->wp_customize->add_section( 'leadx_section_header', array(
            'title'       => esc_html_x( 'Header &amp; Navigation', 'backend', 'leadx' ),
            'description' => esc_html_x( 'All layout and appearance settings for the header.', 'backend', 'leadx' ),
            'priority'    => 30,
        ) );
        $this->wp_customize->add_setting( 'leadx_header_style', array( 'default' => 'header-top-full', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
        $this->wp_customize->add_control( 'leadx_header_style', array(
        	'type'        => 'select',
            'priority'    => 10,
            'label'       => esc_html_x( 'Header Style', 'backend', 'leadx' ),
            'description' => esc_html_x( 'Choose one of the header styles', 'backend', 'leadx' ),
            'section'     => 'leadx_section_header',
            'choices'     => array(
                'header-top-full'			=> esc_html_x( 'Top Full Width', 'backend', 'leadx' ),
                'header-top-boxed'  		=> esc_html_x( 'Top Boxed', 'backend', 'leadx' ),
                'header-transparent-full'	=> esc_html_x( 'Transparent Full Width', 'backend', 'leadx' ),
                'header-transparent-boxed'  => esc_html_x( 'Transparent Boxed', 'backend', 'leadx' ),
                'header-boxed'  			=> esc_html_x( 'Boxed', 'backend', 'leadx' ),
                'header-stacked'			=> esc_html_x( 'Header with Topbar', 'backend', 'leadx' ),
                'header-none'				=> esc_html_x( 'No header', 'backend', 'leadx' ),

            ),
        ) );
		$this->wp_customize->add_setting( 'leadx_header_sticky', array( 'default' => 'sticky', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_header_sticky', array(
			'type'        => 'select',
			'priority'    => 25,
			'label'       => esc_html_x( 'Static or sticky header', 'backend', 'leadx' ),
			'section'     => 'leadx_section_header',
			'choices'     => array(
					'sticky' => esc_html_x( 'Sticky', 'backend', 'leadx' ),
					'static' => esc_html_x( 'Static', 'backend', 'leadx' ),
			),
		) );
        $this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_header_bg', array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_text_field',
            'css_map' => array(
                'background-color' => array(
                    '.header-top-full .navigation',
                    '.header-top-boxed .navigation',
                    '.header-boxed .boxed-wrapper',
                    '.header-stacked .navigation .menubar',
                ),
            )
        ) ) );
        $this->wp_customize->add_control( new Leadx_Customize_Alpha_Color_Control(
            $this->wp_customize,
            'leadx_header_bg',
            array(
                'priority' => 28,
                'label'    => esc_html_x( 'Header background color', 'backend', 'leadx' ),
                'section'  => 'leadx_section_header',
                'show_opacity'  => true, // Optional.
                'palette'   => array(
                    '#ffffff', // RGB, RGBa, and hex values supported
                    '#4fc198',
                    '#1b274a'
                )
            )
        ) );
        $this->wp_customize->add_setting( 'leadx_header_border_show', array( 'default' => 'no-border', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_header_border_show', array(
			'type'        => 'select',
			'priority'    => 31,
			'label'       => esc_html_x( 'Show header bottom border? (Transparent)', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Only for transparent header styles.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_header',
			'choices'     => array(
					'header-border' => esc_html_x( 'Show', 'backend', 'leadx' ),
					'no-border' 	=> esc_html_x( 'Hide', 'backend', 'leadx' ),
			),
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_header_border_color', array(
            'default' => '#d8d8d8',
            'sanitize_callback' => 'sanitize_text_field',
            'css_map' => array(
                'border-bottom-color' => array(
                    '.header-border .navigation'
                ),
            )
        ) ) );
        $this->wp_customize->add_control( new Leadx_Customize_Alpha_Color_Control(
			$this->wp_customize,
			'leadx_header_border_color',
			array(
				'priority' => 33,
				'label'    => esc_html_x( 'Header bottom border color (Transparent)', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
				'show_opacity'  => true, // Optional.
                'palette'   => array(
                    '#c9c9c9',
                    '#1b274a',
                    '#ffffff', // RGB, RGBa, and hex values supported
                )
			)
		) );
        $this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_header_sticky_bg', array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_text_field',
            'css_map' => array(
                'background-color' => array(
                    '.sticky-nav',
                ),
            )
        ) ) );
        $this->wp_customize->add_control( new Leadx_Customize_Alpha_Color_Control(
            $this->wp_customize,
            'leadx_header_sticky_bg',
            array(
                'priority'		=> 35,
                'label' 		=> esc_html_x( 'Sticky header background color', 'backend', 'leadx' ),
                'section'		=> 'leadx_section_header',
                'show_opacity'  => true, // Optional.
                'palette'   	=> array(
                    '#ffffff', // RGB, RGBa, and hex values supported
                    '#4fc198',
                    '#1b274a'
                )
            )
        ) );
        $this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_topbar_bg', array(
            'default'			=> '#ffffff',
            'sanitize_callback' => 'sanitize_text_field',
            'css_map'			=> array(
                'background-color' => array(
                    '.header-stacked .navigation .topbar',
                ),
            )
        ) ) );
        $this->wp_customize->add_control( new Leadx_Customize_Alpha_Color_Control(
            $this->wp_customize,
            'leadx_topbar_bg',
            array(
                'priority'		=> 40,
                'label' 		=> esc_html_x( 'Topbar background color', 'backend', 'leadx' ),
                'description'	=> esc_html_x( 'Only for the topbar header style.', 'backend', 'leadx' ),
                'section'		=> 'leadx_section_header',
                'show_opacity'  => true, // Optional.
                'palette'   	=> array(
                    '#ffffff', // RGB, RGBa, and hex values supported
                    '#4fc198',
                    '#1b274a'
                )
            )
        ) );
		$this->wp_customize->add_setting( 'leadx_header_show_search', array( 'default' => 'no', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_header_show_search', array(
			'type'        => 'select',
			'priority'    => 60,
			'label'       => esc_html_x( 'Show search icon in top bar', 'backend', 'leadx' ),
			'section'     => 'leadx_section_header',
			'choices'     => array(
					'no'	=> esc_html_x( 'No', 'backend', 'leadx' ),
					'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
			),
		) );
		if (function_exists('icl_get_languages')) {
			$this->wp_customize->add_setting( 'leadx_header_show_wpml', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_header_show_wpml', array(
				'type'        => 'select',
				'priority'    => 70,
				'label'       => esc_html_x( 'Show wpml language switcher in top bar', 'backend', 'leadx' ),
				'section'     => 'leadx_section_header',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' ),
				),
			) );
		}
		$this->wp_customize->add_setting( 'leadx_nav_text_align', array( 'default' => 'text-left', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_nav_text_align', array(
			'type'        => 'select',
			'priority'    => 75,
			'label'       => esc_html_x( 'Navigation menu text align', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Only for the topbar header style.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_header',
			'choices'     => array(
					'text-left' 	=> esc_html_x( 'Left', 'backend', 'leadx' ),
					'text-center'	=> esc_html_x( 'Center', 'backend', 'leadx' ),
					'text-right'	=> esc_html_x( 'Right', 'backend', 'leadx' ),
			),
		) );
        $this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_link_color', array(
			'default'			=> '#1b274a',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map'			=> array(
				'color' => array(
					'.nav-menu li a',
					'.header-search .search i',
					'.header-cart .cart i',
					'.header-language .menu .has-dropdown a',
					'.header-language .menu .has-dropdown i',
					'.header-transparent-full .sticky-nav.scrolled .nav-menu li a',
					'.header-transparent-boxed .sticky-nav.scrolled .nav-menu li a',
					'.header-transparent-full .sticky-nav.scrolled .header-search .search i',
					'.header-transparent-boxed .sticky-nav.scrolled .header-search .search i',
					'.header-transparent-full .sticky-nav.scrolled .header-cart .cart i',
					'.header-transparent-full .sticky-nav.scrolled .header-language .menu .has-dropdown a',
					'.header-transparent-full .sticky-nav.scrolled .header-language .menu .has-dropdown i',
					'.header-transparent-boxed .sticky-nav.scrolled .header-cart .cart i',
					'.header-transparent-boxed .sticky-nav.scrolled .header-language .menu .has-dropdown a',
					'.header-transparent-boxed .sticky-nav.scrolled .header-language .menu .has-dropdown i'
					
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_link_color',
			array(
				'priority' => 80,
				'label'    => esc_html_x( 'Navigation link color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_link_color_trans', array(
			'default' => '#1b274a',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.header-transparent-full .nav-menu li a',
					'.header-transparent-boxed .nav-menu li a',
					'.header-transparent-full .header-search .search i',
					'.header-transparent-boxed .header-search .search i',
					'.header-transparent-full .header-cart .cart i',
					'.header-transparent-full .header-language .menu .has-dropdown a',
					'.header-transparent-full .header-language .menu .has-dropdown i',
					'.header-transparent-boxed .header-cart .cart i',
					'.header-transparent-boxed .header-language .menu .has-dropdown a',
					'.header-transparent-boxed .header-language .menu .has-dropdown i'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_link_color_trans',
			array(
				'priority' => 85,
				'label'    => esc_html_x( 'Navigation link color (Transparent)', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
        $this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_link_hover_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.nav-menu>ul>li>a:hover'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_link_hover_color',
			array(
				'priority' => 85,
				'label'    => esc_html_x( 'Navigation link hover color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_link_active_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array (
					'.no-dash .nav-menu > ul > li.active > a'
				),
				'background-color' => array(
					'.nav-menu > ul > li.active > a:after',
					'.nav-menu > ul > li > a:hover:after'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_link_active_color',
			array(
				'priority' => 90,
				'label'    => esc_html_x( 'Navigation active link color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( 'leadx_nav_show_active_dash', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_nav_show_active_dash', array(
			'type'        => 'select',
			'priority'    => 93,
			'label'       => esc_html_x( 'Show bottom dash for active and hover links', 'backend', 'leadx' ),
			'section'     => 'leadx_section_header',
			'choices'     => array(
					'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
					'no'	=> esc_html_x( 'No', 'backend', 'leadx' ),
			),
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_dropdown_bg_color', array(
			'default' => '#f2f2f3',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'.nav-menu ul li.no-mega-menu .second-lvl>ul>li', 
					'.nav-menu ul li.no-mega-menu .second-lvl>ul>li>ul>li',
					'.nav-menu li.mega-menu .second-lvl',
					'.nav-menu li.no-mega-menu .second-lvl',
					'.nav-menu ul li.no-mega-menu .second-lvl>ul>li>ul'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_dropdown_bg_color',
			array(
				'priority' => 95,
				'label'    => esc_html_x( 'Navigation dropdown background color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_dropdown_separator_color', array(
			'default' => '#d8d8d8',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'border-bottom-color' => array(
					'.nav-menu ul ul li a',
					'.nav-menu li .second-lvl',
					'.nav-menu li.mega-menu .second-lvl',
					'.nav-menu li.mega-menu .second-lvl ul li:last-child a',
					'.nav-menu ul li.no-mega-menu .second-lvl>ul>li a',
					'.nav-menu li.no-mega-menu .second-lvl', 
					'.nav-menu ul li.no-mega-menu .second-lvl>ul>li>ul'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_dropdown_separator_color',
			array(
				'priority' => 100,
				'label'    => esc_html_x( 'Navigation dropdown separator color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_dropdown_title_color', array(
			'default' => '#4f4f4f',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.nav-menu li.mega-menu .second-lvl ul li.menu-title>a'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_dropdown_title_color',
			array(
				'priority' => 103,
				'label'    => esc_html_x( 'Navigation megamenu title color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_dropdown_link_color', array(
			'default' => '#4f4f4f',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.nav-menu ul ul li a',
					'.header-transparent-boxed .nav-menu ul ul li a',
					'.header-transparent-full .nav-menu ul ul li a'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_dropdown_link_color',
			array(
				'priority' => 105,
				'label'    => esc_html_x( 'Navigation dropdown link color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_dropdown_link_hover_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.nav-menu ul ul li a:hover'
				),
				'background-color' => array(
					'.nav-menu li.mega-menu .second-lvl ul li a:before'
				),
				'border-color' => array(
					'.nav-menu ul li.no-mega-menu .second-lvl>ul>li a:hover'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_dropdown_link_hover_color',
			array(
				'priority' => 110,
				'label'    => esc_html_x( 'Navigation dropdown link hover color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_dropdown_link_active_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.nav-menu ul ul li.active > a'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_dropdown_link_active_color',
			array(
				'priority' => 115,
				'label'    => esc_html_x( 'Navigation dropdown link active color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_mobile_header_color', array(
			'default' => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'#mobile-header'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_mobile_header_color',
			array(
				'priority' => 120,
				'label'    => esc_html_x( 'Mobile navigation header background color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_mobile_bg_color', array(
			'default' => '#1e2331',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'#mobile-navigation'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_mobile_bg_color',
			array(
				'priority' => 122,
				'label'    => esc_html_x( 'Mobile navigation dropdown background color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_mobile_toggle_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'#mobile-navigation-btn',
					'#mobile-shopping-btn'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_mobile_toggle_color',
			array(
				'priority' => 125,
				'label'    => esc_html_x( 'Mobile navigation toggle color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_mobile_link_color', array(
			'default' => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'#mobile-navigation ul li a'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_mobile_link_color',
			array(
				'priority' => 130,
				'label'    => esc_html_x( 'Mobile navigation link color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_mobile_link_active_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'#mobile-navigation ul li a:hover',
					'#mobile-navigation ul li a:hover .fa',
					'#mobile-navigation li.open > a',
					'#mobile-navigation ul li.current-menu-item > a',
					'#mobile-navigation ul li.current-menu-ancestor > a',
					'#mobile-navigation li.open > a .fa'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_mobile_link_active_color',
			array(
				'priority' => 135,
				'label'    => esc_html_x( 'Mobile navigation active & hover link color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_nav_mobile_link_separator_color', array(
			'default' => '#3b3e47',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'border-bottom-color' => array(
					'#mobile-navigation ul li a'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_nav_mobile_link_separator_color',
			array(
				'priority' => 140,
				'label'    => esc_html_x( 'Mobile navigation link separator color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_header',
			)
		) );
        /**
        * Page Title & Breadcrumbs
        */
        $this->wp_customize->add_section( 'leadx_section_page_title', array(
            'title'       => esc_html_x( 'Page Title &amp; Breadcrumbs', 'backend', 'leadx' ),
            'description' => esc_html_x( 'All layout and appearance settings for the page title and the breadcrums.', 'backend', 'leadx' ),
            'priority'    => 40,
        ) );
        $this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_pagetitle_bg', array(
            'default'			=> '#f2f2f3',
            'sanitize_callback' => 'sanitize_text_field',
            'css_map'			=> array(
                'background-color' => array(
                    '.background.color'
                ),
            )
        ) ) );
        $this->wp_customize->add_control( new Leadx_Customize_Alpha_Color_Control(
            $this->wp_customize,
            'leadx_pagetitle_bg',
            array(
                'priority'		=> 10,
                'label' 		=> esc_html_x( 'Page Title Background color', 'backend', 'leadx' ),
                'description'	=> esc_html_x( 'Only for the color background.', 'backend', 'leadx' ),
                'section'		=> 'leadx_section_page_title',
                'show_opacity'  => true, // Optional.
                'palette'   	=> array(
                    '#f2f2f3', // RGB, RGBa, and hex values supported
                    '#4fc198',
                    '#1b274a'
                )
            )
        ) );
        $this->wp_customize->add_setting( 'leadx_pagetitle_height', array( 'default' => 'x-small', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_pagetitle_height', array(
			'type'        => 'select',
			'priority'    => 20,
			'label'       => esc_html_x( 'Page title height', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Set page title height for image and color background', 'backend', 'leadx' ),
			'section'     => 'leadx_section_page_title',
			'choices'     => array(
					'large' 	=> esc_html_x( 'Large', 'backend', 'leadx' ),
					'small' 	=> esc_html_x( 'Small', 'backend', 'leadx' ),
					'x-large'	=> esc_html_x( 'Extra Large', 'backend', 'leadx' ),
					'x-small'	=> esc_html_x( 'Extra Small', 'backend', 'leadx' ),
			),
		) );
		$this->wp_customize->add_setting( 'leadx_pagetitle_pos', array( 'default' => 'text-left', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_pagetitle_pos', array(
			'type'        => 'select',
			'priority'    => 30,
			'label'       => esc_html_x( 'Page Title Alignment', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Left, center or right', 'backend', 'leadx' ),
			'section'     => 'leadx_section_page_title',
			'choices'     => array(
				'text-left' 	=> esc_html_x( 'Left', 'backend', 'leadx' ),
				'text-center'	=> esc_html_x( 'Center', 'backend', 'leadx' ),
				'text-right'	=> esc_html_x( 'Right', 'backend', 'leadx' )
			),
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_pagetitle_color', array(
			'default'			=> '#1b274a',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map'			=> array(
				'color' => array(
					'.header_text_wrapper h1'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_pagetitle_color',
			array(
				'priority' => 40,
				'label'    => esc_html_x( 'Page title color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_page_title',
			)
		) );
		$this->wp_customize->add_setting( 'leadx_show_pagetitle_separator', array( 'default' => 'no', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_show_pagetitle_separator', array(
			'type'        => 'select',
			'priority'    => 45,
			'label'       => esc_html_x( 'Show Page Title Separator?', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Show or hide page title separator for all pages.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_page_title',
			'choices'     => array(
				'yes' => esc_html_x( 'Yes', 'backend', 'leadx' ),
				'no'  => esc_html_x( 'No', 'backend', 'leadx' )
			),
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_pagetitle_underline_color', array(
			'default'	=> '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'border-color' => array(
					'.background .separator'
				),
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_pagetitle_underline_color',
			array(
				'priority' => 50,
				'label'    => esc_html_x( 'Page title separator color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_page_title',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_page_subtitle_color', array(
			'default' => '#1b274a',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.header_text_wrapper .subtitle',
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_page_subtitle_color',
			array(
				'priority' => 60,
				'label'    => esc_html_x( 'Page subtitle color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_page_title',
			)
		) );
		$this->wp_customize->add_setting( 'leadx_show_breadcrumbs', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_show_breadcrumbs', array(
			'type'        => 'select',
			'priority'    => 65,
			'label'       => esc_html_x( 'Show Breadcrumbs?', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Show or hide breadcrumbs for all pages.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_page_title',
			'choices'     => array(
				'yes' => esc_html_x( 'Yes', 'backend', 'leadx' ),
				'no'  => esc_html_x( 'No', 'backend', 'leadx' )
			),
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_page_breadcrumb_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.breadcrumb li a',
					'.breadcrumb>li+li:before'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_page_breadcrumb_color',
			array(
				'priority' => 70,
				'label'    => esc_html_x( 'Breadcrumb color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_page_title',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_page_breadcrumb_hover_color', array(
			'default' => '#2b2d34',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.breadcrumb li a:hover'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_page_breadcrumb_hover_color',
			array(
				'priority' => 70,
				'label'    => esc_html_x( 'Breadcrumb hover color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_page_title',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_page_breadcrumb_current_color', array(
			'default' => '#2b2d34',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.breadcrumb>.active'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_page_breadcrumb_current_color',
			array(
				'priority' => 80,
				'label'    => esc_html_x( 'Breadcrumb current color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_page_title',
			)
		) );
		
		/**
         * Blog
         */
		$this->wp_customize->add_section( 'leadx_section_blog', array(
				'title'       => esc_html_x( 'Blog', 'backend', 'leadx' ),
				'priority'    => 60,
		) );
		$this->wp_customize->add_setting( 'leadx_blog_title', array( 'default' => 'Our Blog', 'sanitize_callback' => false ) );
        $this->wp_customize->add_control( 'leadx_blog_title', array(
			'type'        => 'text',
			'priority'    => 10,
			'label'       => esc_html_x( 'Blog title', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Text shown in the blog header and in the breadcrumb.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_blog',
		) );
		$this->wp_customize->add_setting( 'leadx_post_title', array( 'default' => 'no', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_post_title', array(
				'type'        => 'select',
				'priority'    => 12,
				'label'       => esc_html_x( 'Use blog title as single post page title?', 'backend', 'leadx' ),
				'section'     => 'leadx_section_blog',
				'choices'     => array(
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' ),
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
				),
		) );
		$this->wp_customize->add_setting( 'leadx_blog_layout', array( 'default' => 'normal', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_blog_layout', array(
				'type'        => 'select',
				'priority'    => 15,
				'label'       => esc_html_x( 'Blog Layout', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Normal feed or masonry', 'backend', 'leadx' ),
				'section'     => 'leadx_section_blog',
				'choices'     => array(
						'masonry-2-col' => esc_html_x( 'Masonry Blog 2 Columns', 'backend', 'leadx' ),
						'masonry-3-col' => esc_html_x( 'Masonry Blog 3 Columns', 'backend', 'leadx' ),
						'normal'		=> esc_html_x( 'Normal Feed', 'backend', 'leadx'),
						'medium'		=> esc_html_x( 'Medium Images', 'backend', 'leadx'),
				),
		) );
		$this->wp_customize->add_setting( 'leadx_blog_sidebar', array( 'default' => 'right', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_blog_sidebar', array(
				'type'        => 'select',
				'priority'    => 20,
				'label'       => esc_html_x( 'Blog Sidebar Position', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Choose where to show the sidebar', 'backend', 'leadx' ),
				'section'     => 'leadx_section_blog',
				'choices'     => array(
						'right' => esc_html_x( 'Right', 'backend', 'leadx'),
						'left'	=> esc_html_x( 'Left', 'backend', 'leadx' ),
						'none'	=> esc_html_x( 'No Sidebar', 'backend', 'leadx' ),
				),
		) );
		$this->wp_customize->add_setting( 'leadx_blog_read_more', array( 'default' => 'Read more', 'sanitize_callback' => false ) );
        $this->wp_customize->add_control( 'leadx_blog_read_more', array(
			'type'        => 'text',
			'priority'    => 25,
			'label'       => esc_html_x( 'Read more text', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Change the "Read more" text link for the blog posts', 'backend', 'leadx' ),
			'section'     => 'leadx_section_blog',
		) );
		/**
         * Blog Single Post
         */
		$this->wp_customize->add_section( 'leadx_section_single_post', array(
				'title'       => esc_html_x( 'Blog Single Post', 'backend', 'leadx' ),
				'priority'    => 62,
		) );
		$this->wp_customize->add_setting( 'leadx_show_social_share', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_show_social_share', array(
				'type'        => 'select',
				'priority'    => 10,
				'label'       => esc_html_x( 'Show social share buttons.', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Shows the share buttons for social media.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_single_post',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx'),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' )
				),
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_share_background', array(
			'default' => '#1b274a',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background' => array(
					'.share-button .post-sharing'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_social_share_background',
			array(
				'priority' => 20,
				'label'    => esc_html_x( 'Social Share Buttons Background Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_single_post',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_share_icon_color', array(
			'default'			=> '#ababab',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map'			=> array(
				'border-color'	=> array(
					'.share-button .post-sharing li'
				),
				'color' 		=> array(
					'.share-button .post-sharing li a'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_social_share_icon_color',
			array(
				'priority' => 30,
				'label'    => esc_html_x( 'Social Share Buttons Icon Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_single_post',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_share_icon_hover_color', array(
			'default'			=> '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map'			=> array(
				'border-color' => array(
					'.share-button .post-sharing li:hover'
				),
				'color' => array(
					'.share-button .post-sharing li:hover a'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_social_share_icon_hover_color',
			array(
				'priority' => 40,
				'label'    => esc_html_x( 'Social Share Buttons Icon Hover Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_single_post',
			)
		) );
		$this->wp_customize->add_setting( 'leadx_show_tags', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_show_tags', array(
				'type'        => 'select',
				'priority'    => 20,
				'label'       => esc_html_x( 'Show Tags.', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Shows the tags for the post.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_single_post',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx'),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' )
				),
		) );
		$this->wp_customize->add_setting( 'leadx_show_author_details', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_show_author_details', array(
				'type'        => 'select',
				'priority'    => 30,
				'label'       => esc_html_x( 'Show Author Details.', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Shows the author section.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_single_post',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx'),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' )
				),
		) );
		$this->wp_customize->add_setting( 'leadx_show_post_navigation', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_show_post_navigation', array(
				'type'        => 'select',
				'priority'    => 40,
				'label'       => esc_html_x( 'Show prev and next post navigation.', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Shows the post navigation.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_single_post',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx'),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' )
				),
		) );
        /**
         * Theme Layout & Colors
         */
        $patterns_url = get_template_directory_uri() .'/images/patterns/';
        
		$this->wp_customize->add_section( 'leadx_section_theme_colors', array(
				'title'       => esc_html_x( 'Theme Layout &amp; Colors', 'backend', 'leadx' ),
				'priority'    => 25,
		) );
		$this->wp_customize->add_setting( 'leadx_site_layout', array( 'default' => 'full-width', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_site_layout', array(
				'type'        => 'select',
				'priority'    => 10,
				'label'       => esc_html_x( 'Site Layout', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Full width or boxed layout', 'backend', 'leadx' ),
				'section'     => 'leadx_section_theme_colors',
				'choices'     => array(
						'full-width' => esc_html_x( 'Full Width', 'backend', 'leadx' ),
						'boxed' 	=> esc_html_x( 'Boxed', 'backend', 'leadx' )
				),
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_site_bg_color', array(
			'default'			=> '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map'			=> array(
				'background-color' => array(
					'body'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_site_bg_color',
			array(
				'priority'		=> 15,
				'label' 		=> esc_html_x( 'Background color', 'backend', 'leadx' ),
				'description'	=> esc_html_x( 'Only for the boxed site layout.', 'backend', 'leadx' ),
				'section'		=> 'leadx_section_theme_colors',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_site_bg_img', array(
			'default'			=> '', 
			'sanitize_callback' => false,
			'css_map'			=> array(
				'background-image|url' => array(
					'body',
				),
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
            $this->wp_customize,
            'leadx_site_bg_img',
            array(
                'priority'    => 20,
                'label'       => esc_html_x( 'Background image', 'backend', 'leadx' ),
                'description' => esc_html_x( 'Only for the boxed site layout.', 'backend', 'leadx' ),
                'section'     => 'leadx_section_theme_colors',
            )
        ) );
        $this->wp_customize->add_setting( 'leadx_site_bg_style', array( 'default' => 'stretched', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_site_bg_style', array(
			'type'        => 'select',
			'priority'    => 25,
			'label'       => esc_html_x( 'Background style', 'backend', 'leadx' ),
			'section'     => 'leadx_section_theme_colors',
			'choices'     => array(
				'stretched' 	=> esc_html_x( 'Stretched', 'backend', 'leadx' ),
					'repeat'	=> esc_html_x( 'Repeat', 'backend', 'leadx' ),
					'fixed' 	=> esc_html_x( 'Center Fixed', 'backend', 'leadx' ),
					'repeat-x'	=> esc_html_x( 'Repeat-x', 'backend', 'leadx' ),
					'repeat-y'	=> esc_html_x( 'Repeat-y', 'backend', 'leadx' ),
					'repeat-y'	=> esc_html_x( 'Repeat-y', 'backend', 'leadx' ),
			),
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_primary_color', array(
			'default'			=> '#4fc198', 
			'sanitize_callback' => false,
			'css_map'			=> array(
				'color' => array(
					'.primary-color',
					'.vc_general.vc_tta.vc_tta-tabs .vc_tta-tabs-list .vc_tta-tab.vc_active a',
					'.vc_tta.vc_general .vc_tta-panel.vc_active .vc_tta-panel-title>a',
					'.vc_general.vc_tta .vc_tta-panel.vc_active .vc_tta-panel-heading:hover',
					'.vc_general.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title>a',
					'.vc_general.vc_tta-tabs:not([class*=vc_tta-gap]):not(.vc_tta-o-no-fill).vc_tta-tabs-position-top .vc_tta-tab.vc_active>a',
					'.blog-normal .content-wrap .entry-posted-on',
					'.icons-tabs .active .tab-title',
					'.icons-tabs .active .tab-title i',
					'.icons-tabs .tab-title:hover',
					'.icons-tabs .tab-title:hover i',
					'.text-tabs .active .tab-title',
					'.ttbase-icon-box-icon',
					'.ttbase-icon-box-one .ttbase-icon-box-one-icon',
					'.ttbase-icon-box-one-img-alt',
					'.ttbase-icon-box-two .ttbase-icon-box-two-icon',
					'.ttbase-icon-box-three .ttbase-icon-box-three-icon',
					'.ttbase-icon-box-four .ttbase-icon-box-four-icon',
					'.ttbase-icon-box-five .ttbase-icon-box-five-icon',
					'.ttbase-icon-box-seven .ttbase-icon-box-seven-icon',
					'.comment-list .author-name',
					'.content-wrap .entry-title a:hover',
					'.ttbase-recent-posts-entry-title a:hover',
					'.ttbase-latest-blog .blog-item h3 a:hover',
					'.ttbase-latest-blog .blog-item h5 a:hover',
					'cite',
					'.ttbase-testimonial-carousel .testimonial-quote:before',
					'.ttbase-testimonial-carousel .testimonial-quote:after',
					'.widget .twitter-feed .slides li:before',
					'.owl-theme .owl-nav .owl-next:hover:after',
					'.owl-theme .owl-nav .owl-prev:hover:after',
					'.btn-primary.color-3',
					'.ttbase-pricing-table .price',
					'.products li .price',
					'.product .price',
					'.quantity .input-group-btn button.btn',
					'.woocommerce-tabs > ul > li a:hover',
					'.woocommerce-tabs > ul > li.active a',
					'.star-rating:before',
					'.star-rating span',
					'#reviews .comment-text .star-rating span',
					'p.stars a',
					'p.stars a.star-1:after',
					'p.stars a.star-2:after',
					'p.stars a.star-3:after',
					'p.stars a.star-4:after',
					'p.stars a.star-5:after',
					'.header-cart .woocommerce-Price-amount'
				),
				'border-color' => array(
					'h2:after',
					'.ttbase-heading-single-line:after',
					'.vc_general.vc_tta.vc_tta-tabs .vc_tta-tabs-list .vc_tta-tab.vc_active a',
					'.vc_general.vc_tta .vc_tta-panel.vc_active .vc_tta-panel-heading',
					'.vc_general.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-heading',
					'.vc_general.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-heading:hover',
					'.vc_tta.vc_general .vc_tta-panel.vc_active .vc_tta-panel-title .vc_tta-controls-icon:before',
					'.vc_general.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-heading:hover',
					'.vc_general.vc_tta .vc_tta-panel.vc_active .vc_tta-panel-heading:hover',
					'.blog-normal .content-wrap .entry-posted-on',
					'input[type=text]:focus',
					'input[type=email]:focus',
					'input[type=tel]:focus',
					'input[type=date]:focus',
					'input[type=number]:focus',
					'textarea:focus',
					'select:focus',
					'input[type=password]:focus',
					'button',
					'.owl-theme .owl-dots .owl-dot.active span',
					'.owl-theme .owl-dots .owl-dot:hover span',
					'.btn-primary.color-3',
					'.btn-primary.style-2.color-3',
					'.btn-primary.color-4:hover',
					'.btn-primary.style-2.color-4:hover',
					'p.stars a',
					'p.stars a.star-1:after',
					'p.stars a.star-2:after',
					'p.stars a.star-3:after',
					'p.stars a.star-4:after',
					'p.stars a.star-5:after',
					'.woocommerce .widget_price_filter .ui-slider .ui-slider-handle',
					'.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle'
				),
				'border-bottom-color' => array(
					'.vc_general.vc_tta-tabs:not([class*=vc_tta-gap]):not(.vc_tta-o-no-fill).vc_tta-tabs-position-top .vc_tta-tab.vc_active>a',
					'.woocommerce-tabs > ul > li.active a'
				),
				'background-color' => array(
					'.primary-background',
					'.widget .overlay',
					'.ttbase-icon-box-six',
					'.ttbase-pricing-table.emphasis',
					'.text-tabs .tab-title:after',
					'.content-link a:hover',
					'.owl-theme .owl-dots .owl-dot:hover span',
					'.btn-primary.style-2.color-3',
					'.btn-primary.color-4:hover',
					'.btn-primary.style-2.color-4:hover',
					'.ttbase-skillbar-bar',
					'.woocommerce .products .onsale',
					'.product .onsale',
					'p.stars a:hover',
					'p.stars a.active',
					'p.stars a.active:after',
					'.woocommerce-message',
					'.woocommerce .widget_price_filter .ui-slider .ui-slider-handle',
					'.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle',
					'.woocommerce .widget_price_filter .ui-slider .ui-slider-range',
					'.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_primary_color',
			array(
				'priority' => 31,
				'label'    => esc_html_x( 'Primary color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_secondary_color', array(
			'default'			=> '#1b274a', 
			'sanitize_callback' => false,
			'css_map'			=> array(
				'color' 		=> array(
					'.secondary-color',
					'.ttbase-icon-box-six .ttbase-icon-box-six-icon',
					'.btn-primary.color-4',
					'.woocommerce-tabs > ul > li a',
					'.woocommerce table.shop_table th',
					'.woocommerce-page table.shop_table th',
					'.woocommerce table.shop_table td.actions',
					'.woocommerce table.cart a.remove',
					'.woocommerce-page table.cart a.remove',
					'.woocommerce #content table.cart a.remove',
					'.woocommerce-page #content table.cart a.remove'
				),
				'background-color' => array(
					'.secondary-background',
					'.btn-primary.color-3:hover',
					'.btn-primary.style-2.color-3:hover',
					'.btn-primary.style-2.color-4',
					'.woocommerce table.cart a.remove:hover',
					'.woocommerce-page table.cart a.remove:hover',
					'.woocommerce #content table.cart a.remove:hover',
					'.woocommerce-page #content table.cart a.remove:hover'
				),
				'border-color' => array(
					'.btn-primary.color-3:hover',
					'.btn-primary.style-2.color-3:hover',
					'.btn-primary.color-4',
					'.btn-primary.style-2.color-4'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_secondary_color',
			array(
				'priority' => 32,
				'label'    => esc_html_x( 'Secondary color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_accent_color', array(
			'default'			=> '#f2f2f3', 
			'sanitize_callback' => false,
			'css_map'			=> array(
				'color' => array(
					'.accent-color'
				),
				'background-color' => array(
					'.accent-background',
					'.ttbase-pricing-table.boxed',
					'.table-style-1 tr:nth-child(even)',
					'.content-link a',
					'blockquote',
					'.vc_general.vc_tta .vc_tta-panel .vc_tta-panel-heading:hover',
					'.content-link a',
					'.quantity .input-text',
					'.variations_form table',
					'#reviews li .comment-text',
					'.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content',
					'.woocommerce-page .widget_price_filter .price_slider_wrapper .ui-widget-content'
				),
				
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_accent_color',
			array(
				'priority' => 34,
				'label'    => esc_html_x( 'Accent color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_text_color', array(
			'default'			=> '#2e323f', 
			'sanitize_callback' => false,
			'css_map'			=> array(
				'color' => array(
					'body'
				),
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_text_color',
			array(
				'priority' => 35,
				'label'    => esc_html_x( 'Text color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_headings_color', array(
			'default'			=> '#1b274a', 
			'sanitize_callback' => false,
			'css_map'			=> array(
				'color' => array(
					'.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6',
				),
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_headings_color',
			array(
				'priority' => 38,
				'label'    => esc_html_x( 'Headings color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_link_color', array(
			'default'			=> '#4fc198', 
			'sanitize_callback' => false,
			'css_map'			=> array(
				'color' => array(
					'a',
					'.share-content .share-button',
					'.comment-list .icon-reply',
					'.products .button.add_to_cart_button',
					'.woocommerce .widget_price_filter .button',
					'.woocommerce .widget_layered_nav li.chosen a',
					'.woocommerce .widget_product_categories > ul > li.current-cat > a',
					'.woocommerce .widget_product_categories > ul > li.current-cat:after'
				),
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_link_color',
			array(
				'priority' => 40,
				'label'    => esc_html_x( 'Link color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_link_hover_color', array(
			'default'			=> '#1b274a', 
			'sanitize_callback' => false,
			'css_map'			=> array(
				'color' => array(
					'a:focus',
					'a:hover',
					'a:active',
					'.widget ul li a:before',
					'.products .button.add_to_cart_button:before',
					'.products .button.add_to_cart_button:hover',
					'.woocommerce .widget_price_filter .button:before',
					'.woocommerce .widget_price_filter .button:hover',
				),
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_link_hover_color',
			array(
				'priority' => 42,
				'label'    => esc_html_x( 'Link hover color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
			)
		) );
        $this->wp_customize->add_setting( 'leadx_button_style', array( 'default' => 'style-2', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_button_style', array(
			'type'        => 'select',
			'priority'    => 45,
			'label'       => esc_html_x( 'Button style', 'backend', 'leadx' ),
			'section'     => 'leadx_section_theme_colors',
			'choices'     => array(
				'style-1' => esc_html_x( 'Style 1', 'backend', 'leadx' ),
				'style-2' => esc_html_x( 'Style 2', 'backend', 'leadx' ),
			),
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_button_radius', array(
			'default'			=> '36px', 
			'sanitize_callback' => false,
			'css_map'			=> array(
				'border-radius | important' => array(
					'.btn-primary',
					'.menu-button',
					'gform_button',
					'.woocommerce input.button.alt'
				),
			)
		) ) );
		$this->wp_customize->add_control( 'leadx_button_radius', array(
			'type'        => 'text',
			'priority'    => 47,
			'label'       => esc_html_x( 'Button Border Radius', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Set radius for rounded corners', 'backend', 'leadx' ),
			'section'     => 'leadx_section_theme_colors',
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_primary_btn_color', array(
			'default'			=> '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map'			=> array(
				'border-color' => array(
					'.btn-primary',
					'input[type=submit]',
					'.btn-primary.active.focus',
					'.btn-primary.active:focus',
					'.btn-primary:active.focus',
					'.btn-primary:active:focus',
					'.menu-button',
					'.menu-button.active.focus',
					'.menu-button.active:focus',
					'.menu-button:active.focus',
					'.menu-button:active:focus',
					'.gform_button',
					'.gform_button.active.focus',
					'.gform_button.active:focus',
					'.gform_button:active.focus',
					'.gform_button:active:focus',
					'.woocommerce input.button.alt',
					'.btn-primary.color-2:hover',
					'.btn-primary.style-2.color-2:hover',
					'.btn-primary.style-2'
				),
				'color' => array(
					'.btn-primary',
					'.gform_button',
					'input[type=submit]',
					'.btn-primary.active.focus',
					'.btn-primary.active:focus',
					'.btn-primary:active.focus',
					'.btn-primary:active:focus',
					'.gform_button.active.focus',
					'.gform_button.active:focus',
					'.gform_button:active.focus',
					'.gform_button:active:focus',
					'.menu-button a',
					'.header-transparent-full .nav-menu li.menu-button a',
					'.header-transparent-boxed .nav-menu li.menu-button a',
					'.header-transparent-full .sticky-nav.scrolled .nav-menu li.menu-button a',
					'.header-transparent-boxed .sticky-nav.scrolled .nav-menu li.menu-button a',
					'.menu-button a.active.focus',
					'.menu-button a.active:focus',
					'.menu-button a:active.focus',
					'.menu-button a:active:focus',
					'.woocommerce input.button.alt'
				),
				'background-color' => array(
					'.btn-primary.style-2',
					'.gform_button.style-2',
					'.nav-menu li.menu-button.style-2',
					'.btn-primary.style-2.active.focus',
					'.btn-primary.style-2.active:focus',
					'.btn-primary.style-2:active.focus',
					'.btn-primary.style-2:active:focus',
					'.gform_button.style-2.active.focus',
					'.gform_button.style-2.active:focus',
					'.gform_button.style-2:active.focus',
					'.gform_button.style-2:active:focus',
					'.btn-primary.color-2:hover',
					'.btn-primary.style-2.color-2:hover'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_primary_btn_color',
			array(
				'priority' => 50,
				'label'    => esc_html_x( 'Primary button color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
			)
		) );
		
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_primary_btn_hover_color', array(
			'default'			=> '#2c3449',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map'			=> array(
				'background-color' => array(
					'.btn-primary:hover',
					'.btn-primary.active',
					'.btn-primary.focus',
					'.btn-primary:active',
					'.btn-primary:focus',
					'.btn-primary.style-2:hover',
					'.btn-primary.style-2.active',
					'.btn-primary.style-2.focus',
					'.btn-primary.style-2:active',
					'.btn-primary.style-2:focus',
					'.btn-primary[disabled]:hover',
					'.btn-primary.style-1[disabled]:hover',
					'.menu-button:hover',
					'.nav-menu li.menu-button.style-2:hover',
					'.header-transparent-full .sticky-nav.scrolled .nav-menu li.menu-button.style-2:hover',
					'.header-transparent-boxed .sticky-nav.scrolled .nav-menu li.menu-button.style-2:hover',
					'.menu-button:focus',
					'.menu-button:active',
					'.gform_button:hover',
					'.gform_button:focus',
					'.gform_button:active',
					'.woocommerce input.button.alt:hover',
					'.woocommerce input.button.alt:focus',
					'.btn-primary.style-2.color-2'
					
				),
				'border-color' => array(
					'.btn-primary:hover',
					'.btn-primary.active',
					'.btn-primary.focus',
					'.btn-primary:active',
					'.btn-primary:focus',
					'.btn-primary.style-2:hover',
					'.btn-primary.style-2.active',
					'.btn-primary.style-2.focus',
					'.btn-primary.style-2:active',
					'.btn-primary.style-2:focus',
					'.menu-button:hover',
					'.menu-button:focus',
					'.menu-button:active',
					'.nav-menu li.menu-button.style-2:hover',
					'.header-transparent-full .sticky-nav.scrolled .nav-menu li.menu-button.style-2:hover',
					'.header-transparent-boxed .sticky-nav.scrolled .nav-menu li.menu-button.style-2:hover',
					'.gform_button:hover',
					'.gform_button:focus',
					'.gform_button:active',
					'.btn-primary[disabled]:hover',
					'.woocommerce input.button.alt:hover',
					'.woocommerce input.button.alt:active',
					'.woocommerce input.button.alt:focus',
					'.btn-primary.color-2',
					'.btn-primary.style-2.color-2'
				),
				'color'	=> array(
					'.btn-primary.color-2'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_primary_btn_hover_color',
			array(
				'priority' => 55,
				'label'    => esc_html_x( 'Primary button hover color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
			)
		) );
		
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_primary_btn_text_color', array(
			'default'			=> '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map'			=> array(
				'color' => array(
					'.btn-primary.style-2',
					'.header-transparent-boxed .nav-menu li.menu-button.style-2 a',
					'.nav-menu li.menu-button.style-2 a',
					'.header-transparent-full .nav-menu li.menu-button.style-2 a',
					'.header-transparent-full .sticky-nav.scrolled .nav-menu li.menu-button.style-2 a',
					'.header-transparent-boxed .sticky-nav.scrolled .nav-menu li.menu-button.style-2 a'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_primary_btn_text_color',
			array(
				'priority'		=> 60,
				'label' 		=> esc_html_x( 'Primary button text color', 'backend', 'leadx' ),
				'description'	=> esc_html_x( 'Only for style two', 'backend', 'leadx' ),
				'section'		=> 'leadx_section_theme_colors',
			)
		) );
		
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_primary_btn_text_hover_color', array(
			'default'			=> '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map'			=> array(
				'color' => array(
					'.btn-primary:hover',
					'.btn-primary.active',
					'.btn-primary.focus',
					'.btn-primary:active',
					'.btn-primary:focus',
					'.gform_button:hover',
					'.gform_button.active',
					'.gform_button.focus',
					'.gform_button:active',
					'.gform_button:focus',
					'.woocommerce input.button.alt:hover',
					'.woocommerce input.button.alt:focus',
					'.woocommerce input.button.alt:active'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_primary_btn_text_hover_color',
			array(
				'priority' => 65,
				'label'    => esc_html_x( 'Primary button text hover color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
			)
		) );
		
        $this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_go_top_bg_color', array(
			'default'			=> '#4fc198',
			'sanitize_callback' => 'sanitize_text_field',
			'css_map'			=> array(
				'background-color' => array(
					'#go-top',
				),
			)
		) ) );
		$this->wp_customize->add_control( new Leadx_Customize_Alpha_Color_Control(
			$this->wp_customize,
			'leadx_go_top_bg_color',
			array(
				'priority'		=> 70,
				'label' 		=> esc_html_x( 'Go to top background color', 'backend', 'leadx' ),
				'section'		=> 'leadx_section_theme_colors',
				'show_opacity'  => true, // Optional.
                'palette'   	=> array(
                    leadx_hex2rgba('#4fc198',0.6), // RGB, RGBa, and hex values supported
                    '#4fc198',
                    leadx_hex2rgba('#1b274a',0.6),
                    '#1b274a' // Mix of color types = no problem
                )
			)
		) );
		
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_go_top_hover_color', array(
			'default'			=> '#1b274a',
			'sanitize_callback' => 'sanitize_text_field',
			'css_map'			=> array(
				'background-color' => array(
					'#go-top:hover',
				),
			)
		) ) );
		$this->wp_customize->add_control( new Leadx_Customize_Alpha_Color_Control(
			$this->wp_customize,
			'leadx_go_top_hover_color',
			array(
				'priority'		=> 75,
				'label' 		=> esc_html_x( 'Go to top hover color', 'backend', 'leadx' ),
				'section'		=> 'leadx_section_theme_colors',
				'show_opacity'  => true, // Optional.
                'palette'   	=> array(
                    leadx_hex2rgba('#1b274a',0.6), // RGB, RGBa, and hex values supported
                    '#4fc198',
                    leadx_hex2rgba('#4fc198',0.6), // Different spacing = no problem
                    '#1b274a' // Mix of color types = no problem
                )
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_go_top_radius', array(
			'default'			=> '34px', 
			'sanitize_callback' => false,
			'css_map'			=> array(
				'border-radius' => array(
					'#go-top'
				),
			)
		) ) );
		$this->wp_customize->add_control( 'leadx_go_top_radius', array(
			'type'        => 'text',
			'priority'    => 80,
			'label'       => esc_html_x( 'Go Top Button Border Radius', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Set radius for rounded corners', 'backend', 'leadx' ),
			'section'     => 'leadx_section_theme_colors',
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_modal_background_color', array(
			'default' => '#202229',
			'sanitize_callback' => 'sanitize_text_field',
			'css_map' => array(
				'background' => array(
					'.modal-screen',
				),
			)
		) ) );
		$this->wp_customize->add_control( new Leadx_Customize_Alpha_Color_Control(
			$this->wp_customize,
			'leadx_modal_background_color',
			array(
				'priority' => 85,
				'label'    => esc_html_x( 'Modal Popup screen background color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_theme_colors',
				'show_opacity'  => true, // Optional.
                'palette'   => array(
                    leadx_hex2rgba('#1b274a',0.8), // RGB, RGBa, and hex values supported
                    leadx_hex2rgba('#f2f2f3',0.8),
                    leadx_hex2rgba('#4fc198',0.8), // Different spacing = no problem
                    '#202229' // Mix of color types = no problem
                )
			)
		) );
		
		/**
         * Footer
         */
     	$this->wp_customize->add_section( 'leadx_section_footer', array(
				'title'       => esc_html_x( 'Footer', 'backend', 'leadx' ),
				'priority'    => 50,
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_footer_bg_color', array(
			'default' => '#1e2331',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'.site-footer',
				),
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_footer_bg_color',
			array(
				'priority' => 10,
				'label'    => esc_html_x( 'Background color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( 'leadx_footer_bg_img', array( 'default' =>  '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'footer_bg_img',
			array(
			    'priority'    => 15,
				'label'       => esc_html_x( 'Background image', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Optional background image for the footer.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_footer_top_border_color', array(
			'default' => '#1e2434',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'border-top-color' => array(
					'.top-footer-container',
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_footer_top_border_color',
			array(
				'priority' => 20,
				'label'    => esc_html_x( 'Footer top border color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_footer_title_color', array(
			'default' => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.site-footer .widget .title',
				),
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_footer_title_color',
			array(
				'priority' => 25,
				'label'    => esc_html_x( 'Footer widgets title color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( 'leadx_show_footer_title_separator', array( 'default' => 'no', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_show_footer_title_separator', array(
			'type'        => 'select',
			'priority'    => 28,
			'label'       => esc_html_x( 'Show Footer Widget Title Separator?', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Show or hide title separator for the footer widgets.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_footer',
			'choices'     => array(
				'yes' => esc_html_x( 'Yes', 'backend', 'leadx' ),
				'no'  => esc_html_x( 'No', 'backend', 'leadx' )
			),
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_footer_title_separator_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'border-color' => array(
					'.site-footer .widget .title:after',
				),
				
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_footer_title_separator_color',
			array(
				'priority' => 30,
				'label'    => esc_html_x( 'Footer widgets title separator color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_footer_text_color', array(
			'default' => '#d3d7e3',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.site-footer'
				),
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_footer_text_color',
			array(
				'priority' => 35,
				'label'    => esc_html_x( 'Text color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_footer_link_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'footer .widget ul li a',
					'.footer-bottom-right-content .menu li a'
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_footer_link_color',
			array(
				'priority' => 40,
				'label'    => esc_html_x( 'Link color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_footer_link_hover_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'footer .widget ul li a:hover',
					'footer .widget ul li a:before',
					'footer .widget .tagcloud a:hover',
					'.footer-bottom-right-content .menu li.current_page_item a',
					'.footer-bottom-right-content .menu li:hover a'
				),
				'border-color' =>array(
					'.footer-bottom-right-content .menu li.current_page_item',
					'.footer-bottom-right-content .menu li:hover'
					)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_footer_link_hover_color',
			array(
				'priority' => 45,
				'label'    => esc_html_x( 'Link hover color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_footer_link_current_color', array(
			'default' => '#4fc198',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.site-footer .current_page_item a',
				),
				'background-color' => array(
				    '.site-footer .current_page_item a:after',
					'.site-footer .current_page_item a:hover:after'
				),
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_footer_link_current_color',
			array(
				'priority' => 50,
				'label'    => esc_html_x( 'Link active color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_footer_link_separator_color', array(
			'default' => '#232838',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'border-color' => array(
					'.widget ul li',
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_footer_link_separator_color',
			array(
				'priority' => 40,
				'label'    => esc_html_x( 'Footer widgets link separator color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_footer_separator_color', array(
			'default' => '#232838',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'border-top-color' => array(
					'.bottom-footer-container',
				)
			)
		) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'leadx_footer_separator_color',
			array(
				'priority' => 55,
				'label'    => esc_html_x( 'Footer areas separator color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_footer',
			)
		) );
		$this->wp_customize->add_setting( 'leadx_footer_widgets_show', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_footer_widgets_show', array(
			'type'        => 'select',
			'priority'    => 60,
			'label'       => esc_html_x( 'Show footer widgets', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Show the widget area for the footer.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_footer',
			'choices'     => array(
					'yes'	=> esc_html_x( 'Show', 'backend', 'leadx' ),
					'no'	=> esc_html_x( 'Hide', 'backend', 'leadx' ),
			),
		) );
		$this->wp_customize->add_setting( 'leadx_footer_bottom_show', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
		$this->wp_customize->add_control( 'leadx_footer_bottom_show', array(
			'type'        => 'select',
			'priority'    => 60,
			'label'       => esc_html_x( 'Show bottom footer bar', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Show the bottom footer with copyright text.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_footer',
			'choices'     => array(
					'yes'	=> esc_html_x( 'Show', 'backend', 'leadx' ),
					'no'	=> esc_html_x( 'Hide', 'backend', 'leadx' ),
			),
		) );
		$this->wp_customize->add_setting( 'leadx_footer_text', array( 'default' => 'Copyright 2016 by themetwins. LeadX Theme crafted with love.', 'sanitize_callback' => false ) );
        $this->wp_customize->add_control( 'leadx_footer_text', array(
			'type'        => 'text',
			'priority'    => 65,
			'label'       => esc_html_x( 'Copyright Text', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Bottom line text (HTML allowed).', 'backend', 'leadx' ),
			'section'     => 'leadx_section_footer',
		) );
        /**
         * Social Media
         */
        $this->wp_customize->add_panel( 'leadx_panel_social_media', array(
			'priority'		=> 80,
			'capability'	=> 'edit_theme_options',
			'title'			=> esc_html__( 'Social Media', 'leadx' ),
		) );
     	$this->wp_customize->add_section( 'leadx_section_social_media_profiles', array(
			'title'       => esc_html_x( 'Profiles', 'backend', 'leadx' ),
			'priority'    => 10,
			'panel'		  => 'leadx_panel_social_media'
		) );
		$this->wp_customize->add_section( 'leadx_section_social_media_widget_dark', array(
			'title'       => esc_html_x( 'Social Icons Widget (Dark Style)', 'backend', 'leadx' ),
			'priority'    => 15,
			'panel'		  => 'leadx_panel_social_media'
		) );
		$this->wp_customize->add_section( 'leadx_section_social_media_widget_color', array(
			'title'       => esc_html_x( 'Social Icons Widget (Color Style)', 'backend', 'leadx' ),
			'priority'    => 20,
			'panel'		  => 'leadx_panel_social_media'
		) );
		
		$this->wp_customize->add_setting( 'leadx_social_twitter', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_twitter', array(
			'type'        => 'text',
			'priority'    => 10,
			'label'       => esc_html_x( 'Twitter', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your twitter account URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_facebook', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_facebook', array(
			'type'        => 'text',
			'priority'    => 15,
			'label'       => esc_html_x( 'Facebook', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your facebook account URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_instagram', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_instagram', array(
			'type'        => 'text',
			'priority'    => 20,
			'label'       => esc_html_x( 'Instagram', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your instagram profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_googleplus', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_googleplus', array(
			'type'        => 'text',
			'priority'    => 25,
			'label'       => esc_html_x( 'Google+', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your google plus account URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_linkedin', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_linkedin', array(
			'type'        => 'text',
			'priority'    => 30,
			'label'       => esc_html_x( 'LinkedIn', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your linkedin account URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_pinterest', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_pinterest', array(
			'type'        => 'text',
			'priority'    => 35,
			'label'       => esc_html_x( 'Pinterest', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your pinterest account URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_yelp', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_yelp', array(
			'type'        => 'text',
			'priority'    => 40,
			'label'       => esc_html_x( 'Yelp', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your yelp account URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_dribbble', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_dribbble', array(
			'type'        => 'text',
			'priority'    => 45,
			'label'       => esc_html_x( 'Dribbble', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your dribbble account URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_flickr', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_flickr', array(
			'type'        => 'text',
			'priority'    => 50,
			'label'       => esc_html_x( 'Flickr', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your flickr profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_vk', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_vk', array(
			'type'        => 'text',
			'priority'    => 55,
			'label'       => esc_html_x( 'Vk', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your vk profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_github', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_github', array(
			'type'        => 'text',
			'priority'    => 60,
			'label'       => esc_html_x( 'Github', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your github profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_tumblr', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_tumblr', array(
			'type'        => 'text',
			'priority'    => 65,
			'label'       => esc_html_x( 'Tumblr', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your tumblr profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_skype', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_skype', array(
			'type'        => 'text',
			'priority'    => 70,
			'label'       => esc_html_x( 'Skype', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your skype profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_trello', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_trello', array(
			'type'        => 'text',
			'priority'    => 75,
			'label'       => esc_html_x( 'Trello', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your trello profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_foursquare', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_foursquare', array(
			'type'        => 'text',
			'priority'    => 80,
			'label'       => esc_html_x( 'Foursquare', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your foursquare profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_renren', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_renren', array(
			'type'        => 'text',
			'priority'    => 85,
			'label'       => esc_html_x( 'Renren', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your renren profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_xing', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_xing', array(
			'type'        => 'text',
			'priority'    => 90,
			'label'       => esc_html_x( 'Xing', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your xing profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_vimeo', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_vimeo', array(
			'type'        => 'text',
			'priority'    => 95,
			'label'       => esc_html_x( 'Vimeo', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your vimeo profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_vine', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_vine', array(
			'type'        => 'text',
			'priority'    => 100,
			'label'       => esc_html_x( 'Vine', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your vine profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_youtube', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_youtube', array(
			'type'        => 'text',
			'priority'    => 105,
			'label'       => esc_html_x( 'YouTube', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your youtube profil URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( 'leadx_social_rss', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$this->wp_customize->add_control( 'leadx_social_rss', array(
			'type'        => 'text',
			'priority'    => 110,
			'label'       => esc_html_x( 'RSS Feed', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your rss feed URL', 'backend', 'leadx' ),
			'section'     => 'leadx_section_social_media_profiles',
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_dark_icon_color', array(
				'default' => '#abb0be',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'color' => array(
						'.social-icon-widget ul.dark li a'
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_dark_icon_color',
			array(
				'priority' => 10,
				'label'    => esc_html_x( 'Icon Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_dark',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_dark_bg_color', array(
				'default' => '',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background-color' => array(
						'.social-icon-widget ul.dark li'
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_dark_bg_color',
			array(
				'priority' => 15,
				'label'    => esc_html_x( 'Icon Background Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_dark',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_dark_border_color', array(
				'default' => '#343c54',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.social-icon-widget ul.dark li',
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_dark_border_color',
			array(
				'priority' => 20,
				'label'    => esc_html_x( 'Icon Border Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_dark',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_dark_icon_hover_color', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'color' => array(
						'.social-icon-widget ul.dark li:hover a'
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_dark_icon_hover_color',
			array(
				'priority' => 30,
				'label'    => esc_html_x( 'Icon Hover Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_dark',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_dark_bg_hover_color', array(
				'default' => '',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background-color' => array(
						'.social-icon-widget ul.dark li:hover'
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_dark_bg_hover_color',
			array(
				'priority' => 35,
				'label'    => esc_html_x( 'Icon Background Hover Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_dark',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_dark_border_hover_color', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.social-icon-widget ul.dark li:hover',
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_dark_border_hover_color',
			array(
				'priority' => 40,
				'label'    => esc_html_x( 'Icon Border Hover Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_dark',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_color_icon_color', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'color' => array(
						'.social-icon-widget ul.color li a'
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_color_icon_color',
			array(
				'priority' => 10,
				'label'    => esc_html_x( 'Icon Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_color',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_color_bg_color', array(
				'default' => '',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background-color' => array(
						'.social-icon-widget ul.color li'
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_color_bg_color',
			array(
				'priority' => 15,
				'label'    => esc_html_x( 'Icon Background Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_color',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_color_border_color', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.social-icon-widget ul.color li',
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_color_border_color',
			array(
				'priority' => 20,
				'label'    => esc_html_x( 'Icon Border Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_color',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_color_icon_hover_color', array(
				'default' => '#1b274a',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'color' => array(
						'.social-icon-widget ul.color li:hover a'
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_color_icon_hover_color',
			array(
				'priority' => 30,
				'label'    => esc_html_x( 'Icon Hover Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_color',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_color_bg_hover_color', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background-color' => array(
						'.social-icon-widget ul.color li:hover'
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_color_bg_hover_color',
			array(
				'priority' => 35,
				'label'    => esc_html_x( 'Icon Background Hover Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_color',
			)
		) );
		$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_social_icon_widget_color_border_hover_color', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.social-icon-widget ul.color li:hover',
					)
				)
			) ) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
		$this->wp_customize,
			'leadx_social_icon_widget_color_border_hover_color',
			array(
				'priority' => 40,
				'label'    => esc_html_x( 'Icon Border Hover Color', 'backend', 'leadx' ),
				'section'  => 'leadx_section_social_media_widget_color',
			)
		) );
		/**
         * Contact Form 7
         */
         if ( leadx_contact_form_7_installed() ) {
         	$this->wp_customize->add_panel( 'leadx_panel_wpcf7', array(
				'priority'		=> 90,
				'capability'	=> 'edit_theme_options',
				'title'			=> esc_html__( 'Contact Form 7', 'leadx' ),
			) );
         	$this->wp_customize->add_section( 'leadx_section_wpcf7_style_1', array(
				'title'       => esc_html_x( 'Style 1', 'backend', 'leadx' ),
				'priority'    => 10,
				'panel'		  => 'leadx_panel_wpcf7'
			) );
			$this->wp_customize->add_section( 'leadx_section_wpcf7_style_2', array(
				'title'       => esc_html_x( 'Style 2', 'backend', 'leadx' ),
				'priority'    => 15,
				'panel'		  => 'leadx_panel_wpcf7'
			) );
			$this->wp_customize->add_section( 'leadx_section_wpcf7_style_3', array(
				'title'       => esc_html_x( 'Style 3', 'backend', 'leadx' ),
				'priority'    => 20,
				'panel'		  => 'leadx_panel_wpcf7'
			) );
			
			//Style 1
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_1_label', array(
				'default' => '#353535',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'color' => array(
						'.wpcf7-style-1 label',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_1_label',
				array(
					'priority' => 10,
					'label'    => esc_html_x( 'Label Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_1',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_1_background', array(
				'default' => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.wpcf7-style-1 .wpcf7-select',
						'.wpcf7-style-1 .wpcf7-textarea',
						'.wpcf7-style-1 .wpcf7-text',
						'.wpcf7-style-1 .wpcf7-date',
						'.wpcf7-style-1 .bootstrap-filestyle',
						'.wpcf7-style-1 .bootstrap-filestyle .form-control',
						'.wpcf7-style-1 .checkbox-option .outer',
						'.wpcf7-style-1 .radio-option .outer',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_1_background',
				array(
					'priority' => 15,
					'label'    => esc_html_x( 'Input Background Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_1',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_1_border', array(
				'default' => '#e0e0e0',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.wpcf7-style-1 .wpcf7-select',
						'.wpcf7-style-1 .wpcf7-textarea',
						'.wpcf7-style-1 .wpcf7-text',
						'.wpcf7-style-1 .wpcf7-date',
						'.wpcf7-style-1 .bootstrap-filestyle',
						'.wpcf7-style-1 .bootstrap-filestyle .form-control',
						'.wpcf7-style-1 .checkbox-option .outer',
						'.wpcf7-style-1 .radio-option .outer',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_1_border',
				array(
					'priority' => 20,
					'label'    => esc_html_x( 'Input Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_1',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_1_border_radius', array(
				'default'			=> '34px', 
				'sanitize_callback' => false,
				'css_map'			=> array(
					'border-radius' => array(
						'.wpcf7-style-1 .wpcf7-select',
						'.wpcf7-style-1 .wpcf7-textarea',
						'.wpcf7-style-1 .wpcf7-text',
						'.wpcf7-style-1 .wpcf7-date',
						'.wpcf7-style-1 .bootstrap-filestyle',
						'.wpcf7-style-1 .bootstrap-filestyle .form-control',
						'.wpcf7-style-1 .checkbox-option .outer',
						'.wpcf7-style-1 .radio-option .outer',
						'.wpcf7-style-1 .checkbox-option .inner',
						'.wpcf7-style-1 .radio-option .inner'
					),
				)
			) ) );
			$this->wp_customize->add_control( 'leadx_contact_form_7_style_1_border_radius', array(
				'type'        => 'text',
				'priority'    => 25,
				'label'       => esc_html_x( 'Input Border Radius', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Set radius for rounded corners', 'backend', 'leadx' ),
				'section'     => 'leadx_section_wpcf7_style_1',
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_1_focus', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.wpcf7-style-1 .wpcf7-select:focus',
						'.wpcf7-style-1 .wpcf7-textarea:focus',
						'.wpcf7-style-1 .wpcf7-text:focus',
						'.wpcf7-style-1 .wpcf7-date:focus',
						'.wpcf7-style-1 .bootstrap-filestyle:focus',
						'.wpcf7-style-1 .wpcf7-select.focus',
						'.wpcf7-style-1 .wpcf7-textarea.focus',
						'.wpcf7-style-1 .wpcf7-text.focus',
						'.wpcf7-style-1 .bootstrap-filestyle.focus',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_1_focus',
				array(
					'priority' => 27,
					'label'    => esc_html_x( 'Input Focus Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_1',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_1_unchecked', array(
				'default' => '#ababab',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.wpcf7-style-1 .checkbox-option .inner'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_1_unchecked',
				array(
					'priority' => 30,
					'label'    => esc_html_x( 'Checkbox Unchecked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_1',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_1_checked', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.wpcf7-style-1 .checkbox-option.checked .inner',
						'.wpcf7-style-1 .radio-option.checked .inner',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_1_checked',
				array(
					'priority' => 35,
					'label'    => esc_html_x( 'Checkbox and Radio Checked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_1',
				)
			) );
			//Style 2
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_2_label', array(
				'default' => '#353535',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'color' => array(
						'.wpcf7-style-2 label',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_2_label',
				array(
					'priority' => 10,
					'label'    => esc_html_x( 'Label Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_2',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_2_background', array(
				'default' => '#f2f2f3',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.wpcf7-style-2 .wpcf7-select',
						'.wpcf7-style-2 .wpcf7-textarea',
						'.wpcf7-style-2 .wpcf7-text',
						'.wpcf7-style-2 .wpcf7-date',
						'.wpcf7-style-2 .bootstrap-filestyle',
						'.wpcf7-style-2 .bootstrap-filestyle .form-control',
						'.wpcf7-style-2 .checkbox-option .outer',
						'.wpcf7-style-2 .radio-option .outer',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_2_background',
				array(
					'priority' => 15,
					'label'    => esc_html_x( 'Input Background Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_2',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_2_border', array(
				'default' => '#eaeaea',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.wpcf7-style-2 .wpcf7-select',
						'.wpcf7-style-2 .wpcf7-textarea',
						'.wpcf7-style-2 .wpcf7-text',
						'.wpcf7-style-2 .wpcf7-date',
						'.wpcf7-style-2 .bootstrap-filestyle',
						'.wpcf7-style-2 .bootstrap-filestyle .form-control',
						'.wpcf7-style-2 .checkbox-option .outer',
						'.wpcf7-style-2 .radio-option .outer',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_2_border',
				array(
					'priority' => 20,
					'label'    => esc_html_x( 'Input Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_2',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_2_border_radius', array(
				'default'			=> '34px', 
				'sanitize_callback' => false,
				'css_map'			=> array(
					'border-radius' => array(
						'.wpcf7-style-2 .wpcf7-select',
						'.wpcf7-style-2 .wpcf7-textarea',
						'.wpcf7-style-2 .wpcf7-text',
						'.wpcf7-style-2 .wpcf7-date',
						'.wpcf7-style-2 .bootstrap-filestyle',
						'.wpcf7-style-2 .bootstrap-filestyle .form-control',
						'.wpcf7-style-2 .checkbox-option .outer',
						'.wpcf7-style-2 .radio-option .outer',
						'.wpcf7-style-2 .checkbox-option .inner',
						'.wpcf7-style-2 .radio-option .inner'
					),
				)
			) ) );
			$this->wp_customize->add_control( 'leadx_contact_form_7_style_2_border_radius', array(
				'type'        => 'text',
				'priority'    => 25,
				'label'       => esc_html_x( 'Input Border Radius', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Set radius for rounded corners', 'backend', 'leadx' ),
				'section'     => 'leadx_section_wpcf7_style_2',
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_2_focus', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.wpcf7-style-2 .wpcf7-select:focus',
						'.wpcf7-style-2 .wpcf7-textarea:focus',
						'.wpcf7-style-2 .wpcf7-text:focus',
						'.wpcf7-style-2 .wpcf7-date:focus',
						'.wpcf7-style-2 .bootstrap-filestyle:focus',
						'.wpcf7-style-2 .wpcf7-select.focus',
						'.wpcf7-style-2 .wpcf7-textarea.focus',
						'.wpcf7-style-2 .wpcf7-text.focus',
						'.wpcf7-style-2 .bootstrap-filestyle.focus',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_2_focus',
				array(
					'priority' => 27,
					'label'    => esc_html_x( 'Input Focus Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_2',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_2_unchecked', array(
				'default' => '#ababab',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.wpcf7-style-2 .checkbox-option .inner'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_2_unchecked',
				array(
					'priority' => 30,
					'label'    => esc_html_x( 'Checkbox Unchecked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_2',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_2_checked', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.wpcf7-style-2 .checkbox-option.checked .inner',
						'.wpcf7-style-2 .radio-option.checked .inner',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_2_checked',
				array(
					'priority' => 35,
					'label'    => esc_html_x( 'Checkbox and Radio Checked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_2',
				)
			) );
			
			//Style 3
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_3_label', array(
				'default' => '#353535',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'color' => array(
						'.wpcf7-style-3 label',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_3_label',
				array(
					'priority' => 10,
					'label'    => esc_html_x( 'Label Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_3',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_3_background', array(
				'default' => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.wpcf7-style-3 .wpcf7-select',
						'.wpcf7-style-3 .wpcf7-textarea',
						'.wpcf7-style-3 .wpcf7-text',
						'.wpcf7-style-3 .wpcf7-date',
						'.wpcf7-style-3 .bootstrap-filestyle',
						'.wpcf7-style-3 .bootstrap-filestyle .form-control',
						'.wpcf7-style-3 .checkbox-option .outer',
						'.wpcf7-style-3 .radio-option .outer',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_3_background',
				array(
					'priority' => 15,
					'label'    => esc_html_x( 'Input Background Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_3',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_3_border', array(
				'default' => '#d9d9d9',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.wpcf7-style-3 .wpcf7-select',
						'.wpcf7-style-3 .wpcf7-textarea',
						'.wpcf7-style-3 .wpcf7-text',
						'.wpcf7-style-3 .wpcf7-date',
						'.wpcf7-style-3 .bootstrap-filestyle',
						'.wpcf7-style-3 .bootstrap-filestyle .form-control',
						'.wpcf7-style-3 .checkbox-option .outer',
						'.wpcf7-style-3 .radio-option .outer',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_3_border',
				array(
					'priority' => 20,
					'label'    => esc_html_x( 'Input Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_3',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_3_focus', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.wpcf7-style-3 .wpcf7-select:focus',
						'.wpcf7-style-3 .wpcf7-textarea:focus',
						'.wpcf7-style-3 .wpcf7-text:focus',
						'.wpcf7-style-3 .wpcf7-date:focus',
						'.wpcf7-style-3 .bootstrap-filestyle:focus',
						'.wpcf7-style-3 .wpcf7-select.focus',
						'.wpcf7-style-3 .wpcf7-textarea.focus',
						'.wpcf7-style-3 .wpcf7-text.focus',
						'.wpcf7-style-3 .bootstrap-filestyle.focus',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_3_focus',
				array(
					'priority' => 27,
					'label'    => esc_html_x( 'Input Focus Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_3',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_3_unchecked', array(
				'default' => '#ababab',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.wpcf7-style-3 .checkbox-option .inner'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'contact_form_7_style_3_unchecked',
				array(
					'priority' => 30,
					'label'    => esc_html_x( 'Checkbox Unchecked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_3',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_3_checked', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.wpcf7-style-3 .checkbox-option.checked .inner',
						'.wpcf7-style-3 .radio-option.checked .inner',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_3_checked',
				array(
					'priority' => 35,
					'label'    => esc_html_x( 'Checkbox and Radio Checked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_wpcf7_style_3',
				)
			) );
			
         }
         /**
         * Gravity Forms
         */
         if ( class_exists( 'GFCommon' ) ) {
         	$this->wp_customize->add_panel( 'leadx_panel_gravityforms', array(
				'priority'		=> 90,
				'capability'	=> 'edit_theme_options',
				'title'			=> esc_html__( 'Gravity Forms', 'leadx' ),
			) );
         	$this->wp_customize->add_section( 'leadx_section_gravityforms_style_1', array(
				'title'       => esc_html_x( 'Style 1', 'backend', 'leadx' ),
				'priority'    => 10,
				'panel'		  => 'leadx_panel_gravityforms'
			) );
			$this->wp_customize->add_section( 'leadx_section_gravityforms_style_2', array(
				'title'       => esc_html_x( 'Style 2', 'backend', 'leadx' ),
				'priority'    => 15,
				'panel'		  => 'leadx_panel_gravityforms'
			) );
			$this->wp_customize->add_section( 'leadx_section_gravityforms_style_3', array(
				'title'       => esc_html_x( 'Style 3', 'backend', 'leadx' ),
				'priority'    => 20,
				'panel'		  => 'leadx_panel_gravityforms'
			) );
			
			//Style 1
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_1_label', array(
				'default' => '#353535',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'color' => array(
						'.content-area .gform_wrapper .gf-style-1 label.gfield_label',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_1_label',
				array(
					'priority' => 10,
					'label'    => esc_html_x( 'Label Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_1',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_1_background', array(
				'default' => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.content-area .gform_wrapper .gf-style-1 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])', 
						'.content-area .gform_wrapper .gf-style-1 input[type=email]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=number]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=password]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=tel]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=text]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=url]', 
						'.content-area .gform_wrapper .gf-style-1 select', 
						'.content-area .gform_wrapper .gf-style-1 textarea',
						'.content-area .gform_wrapper .gf-style-1 .ginput_container_fileupload .bootstrap-filestyle', 
						'.content-area .gform_wrapper .gf-style-1 .ginput_container_fileupload .bootstrap-filestyle .form-control', 
						'.content-area .gform_wrapper .gf-style-1 .gfield_checkbox .checkbox-option .outer', 
						'.content-area .gform_wrapper .gf-style-1 .gfield_radio .radio-option .outer'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_1_background',
				array(
					'priority' => 15,
					'label'    => esc_html_x( 'Input Background Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_1',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_1_border', array(
				'default' => '#e0e0e0',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.content-area .gform_wrapper .gf-style-1 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])', 
						'.content-area .gform_wrapper .gf-style-1 input[type=email]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=number]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=password]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=tel]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=text]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=url]', 
						'.content-area .gform_wrapper .gf-style-1 select', 
						'.content-area .gform_wrapper .gf-style-1 textarea',
						'.content-area .gform_wrapper .gf-style-1 .ginput_container_fileupload .bootstrap-filestyle', 
						'.content-area .gform_wrapper .gf-style-1 .ginput_container_fileupload .bootstrap-filestyle .form-control', 
						'.content-area .gform_wrapper .gf-style-1 .gfield_checkbox .checkbox-option .outer', 
						'.content-area .gform_wrapper .gf-style-1 .gfield_radio .radio-option .outer'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_1_border',
				array(
					'priority' => 20,
					'label'    => esc_html_x( 'Input Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_1',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_1_border_radius', array(
				'default'			=> '34px', 
				'sanitize_callback' => false,
				'css_map'			=> array(
					'border-radius' => array(
						'.content-area .gform_wrapper .gf-style-1 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])', 
						'.content-area .gform_wrapper .gf-style-1 input[type=email]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=number]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=password]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=tel]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=text]', 
						'.content-area .gform_wrapper .gf-style-1 input[type=url]', 
						'.content-area .gform_wrapper .gf-style-1 select', 
						'.content-area .gform_wrapper .gf-style-1 textarea',
						'.content-area .gform_wrapper .gf-style-1 .ginput_container_fileupload .bootstrap-filestyle', 
						'.content-area .gform_wrapper .gf-style-1 .ginput_container_fileupload .bootstrap-filestyle .form-control', 
						'.content-area .gform_wrapper .gf-style-1 .gfield_checkbox .checkbox-option .outer', 
						'.content-area .gform_wrapper .gf-style-1 .gfield_radio .radio-option .outer',
						'.content-area .gform_wrapper .gf-style-1 .gfield_checkbox .checkbox-option .inner', 
						'.content-area .gform_wrapper .gf-style-1 .gfield_radio .radio-option .inner'
					),
				)
			) ) );
			$this->wp_customize->add_control( 'leadx_gravityforms_style_1_border_radius', array(
				'type'        => 'text',
				'priority'    => 25,
				'label'       => esc_html_x( 'Input Border Radius', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Set radius for rounded corners', 'backend', 'leadx' ),
				'section'     => 'leadx_section_gravityforms_style_1',
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_1_focus', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.content-area .gform_wrapper .gf-style-1 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=email]:focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=number]:focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=password]:focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=tel]:focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=text]:focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=url]:focus', 
						'.content-area .gform_wrapper .gf-style-1 select:focus', 
						'.content-area .gform_wrapper .gf-style-1 textarea:focus',
						'.content-area .gform_wrapper .gf-style-1 .ginput_container_fileupload .bootstrap-filestyle:focus',
						'.content-area .gform_wrapper .gf-style-1 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]).focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=email].focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=number].focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=password].focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=tel].focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=text].focus', 
						'.content-area .gform_wrapper .gf-style-1 input[type=url].focus', 
						'.content-area .gform_wrapper .gf-style-1 select.focus', 
						'.content-area .gform_wrapper .gf-style-1 textarea.focus',
						'.content-area .gform_wrapper .gf-style-1 .ginput_container_fileupload .bootstrap-filestyle.focus'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_1_focus',
				array(
					'priority' => 27,
					'label'    => esc_html_x( 'Input Focus Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_1',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_1_unchecked', array(
				'default' => '#ababab',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.content-area .gform_wrapper .gf-style-1 .checkbox-option .inner'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_1_unchecked',
				array(
					'priority' => 30,
					'label'    => esc_html_x( 'Checkbox Unchecked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_1',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_1_checked', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.content-area .gform_wrapper .gf-style-1 .checkbox-option.checked .inner',
						'.content-area .gform_wrapper .gf-style-1 .radio-option.checked .inner',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_1_checked',
				array(
					'priority' => 35,
					'label'    => esc_html_x( 'Checkbox and Radio Checked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_1',
				)
			) );
			//Style 2
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_2_label', array(
				'default' => '#353535',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'color' => array(
						'.content-area .gform_wrapper .gf-style-2 label.gfield_label'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_2_label',
				array(
					'priority' => 10,
					'label'    => esc_html_x( 'Label Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_2',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_2_background', array(
				'default' => '#f2f2f3',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.content-area .gform_wrapper .gf-style-2 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])', 
						'.content-area .gform_wrapper .gf-style-2 input[type=email]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=number]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=password]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=tel]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=text]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=url]', 
						'.content-area .gform_wrapper .gf-style-2 select', 
						'.content-area .gform_wrapper .gf-style-2 textarea',
						'.content-area .gform_wrapper .gf-style-2 .ginput_container_fileupload .bootstrap-filestyle', 
						'.content-area .gform_wrapper .gf-style-2 .ginput_container_fileupload .bootstrap-filestyle .form-control', 
						'.content-area .gform_wrapper .gf-style-2 .gfield_checkbox .checkbox-option .outer', 
						'.content-area .gform_wrapper .gf-style-2 .gfield_radio .radio-option .outer'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_2_background',
				array(
					'priority' => 15,
					'label'    => esc_html_x( 'Input Background Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_2',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_2_border', array(
				'default' => '#eaeaea',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.content-area .gform_wrapper .gf-style-2 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])', 
						'.content-area .gform_wrapper .gf-style-2 input[type=email]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=number]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=password]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=tel]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=text]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=url]', 
						'.content-area .gform_wrapper .gf-style-2 select', 
						'.content-area .gform_wrapper .gf-style-2 textarea',
						'.content-area .gform_wrapper .gf-style-2 .ginput_container_fileupload .bootstrap-filestyle', 
						'.content-area .gform_wrapper .gf-style-2 .ginput_container_fileupload .bootstrap-filestyle .form-control', 
						'.content-area .gform_wrapper .gf-style-2 .gfield_checkbox .checkbox-option .outer', 
						'.content-area .gform_wrapper .gf-style-2 .gfield_radio .radio-option .outer'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_2_border',
				array(
					'priority' => 20,
					'label'    => esc_html_x( 'Input Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_2',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_2_border_radius', array(
				'default'			=> '34px', 
				'sanitize_callback' => false,
				'css_map'			=> array(
					'border-radius' => array(
						'.content-area .gform_wrapper .gf-style-2 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])', 
						'.content-area .gform_wrapper .gf-style-2 input[type=email]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=number]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=password]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=tel]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=text]', 
						'.content-area .gform_wrapper .gf-style-2 input[type=url]', 
						'.content-area .gform_wrapper .gf-style-2 select', 
						'.content-area .gform_wrapper .gf-style-2 textarea',
						'.content-area .gform_wrapper .gf-style-2 .ginput_container_fileupload .bootstrap-filestyle', 
						'.content-area .gform_wrapper .gf-style-2 .ginput_container_fileupload .bootstrap-filestyle .form-control', 
						'.content-area .gform_wrapper .gf-style-2 .gfield_checkbox .checkbox-option .outer', 
						'.content-area .gform_wrapper .gf-style-2 .gfield_radio .radio-option .outer',
						'.content-area .gform_wrapper .gf-style-2 .gfield_checkbox .checkbox-option .inner', 
						'.content-area .gform_wrapper .gf-style-2 .gfield_radio .radio-option .inner'
					),
				)
			) ) );
			$this->wp_customize->add_control( 'leadx_gravityforms_style_2_border_radius', array(
				'type'        => 'text',
				'priority'    => 25,
				'label'       => esc_html_x( 'Input Border Radius', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Set radius for rounded corners', 'backend', 'leadx' ),
				'section'     => 'leadx_section_gravityforms_style_2',
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_2_focus', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.content-area .gform_wrapper .gf-style-2 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=email]:focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=number]:focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=password]:focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=tel]:focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=text]:focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=url]:focus', 
						'.content-area .gform_wrapper .gf-style-2 select:focus', 
						'.content-area .gform_wrapper .gf-style-2 textarea:focus',
						'.content-area .gform_wrapper .gf-style-2 .ginput_container_fileupload .bootstrap-filestyle:focus',
						'.content-area .gform_wrapper .gf-style-2 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]).focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=email].focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=number].focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=password].focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=tel].focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=text].focus', 
						'.content-area .gform_wrapper .gf-style-2 input[type=url].focus', 
						'.content-area .gform_wrapper .gf-style-2 select.focus', 
						'.content-area .gform_wrapper .gf-style-2 textarea.focus',
						'.content-area .gform_wrapper .gf-style-2 .ginput_container_fileupload .bootstrap-filestyle.focus'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_2_focus',
				array(
					'priority' => 27,
					'label'    => esc_html_x( 'Input Focus Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_2',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_2_unchecked', array(
				'default' => '#ababab',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.content-area .gform_wrapper .gf-style-2 .checkbox-option .inner'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_2_unchecked',
				array(
					'priority' => 30,
					'label'    => esc_html_x( 'Checkbox Unchecked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_2',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_2_checked', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.content-area .gform_wrapper .gf-style-2 .checkbox-option.checked .inner',
						'.content-area .gform_wrapper .gf-style-2 .radio-option.checked .inner',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_2_checked',
				array(
					'priority' => 35,
					'label'    => esc_html_x( 'Checkbox and Radio Checked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_2',
				)
			) );
			
			//Style 3
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_3_label', array(
				'default' => '#353535',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'color' => array(
						'.content-area .gform_wrapper .gf-style-3 label.gfield_label'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_3_label',
				array(
					'priority' => 10,
					'label'    => esc_html_x( 'Label Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_3',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_3_background', array(
				'default' => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.content-area .gform_wrapper .gf-style-3 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])', 
						'.content-area .gform_wrapper .gf-style-3 input[type=email]', 
						'.content-area .gform_wrapper .gf-style-3 input[type=number]', 
						'.content-area .gform_wrapper .gf-style-3 input[type=password]', 
						'.content-area .gform_wrapper .gf-style-3 input[type=tel]', 
						'.content-area .gform_wrapper .gf-style-3 input[type=text]', 
						'.content-area .gform_wrapper .gf-style-3 input[type=url]', 
						'.content-area .gform_wrapper .gf-style-3 select', 
						'.content-area .gform_wrapper .gf-style-3 textarea',
						'.content-area .gform_wrapper .gf-style-3 .ginput_container_fileupload .bootstrap-filestyle', 
						'.content-area .gform_wrapper .gf-style-3 .ginput_container_fileupload .bootstrap-filestyle .form-control', 
						'.content-area .gform_wrapper .gf-style-3 .gfield_checkbox .checkbox-option .outer', 
						'.content-area .gform_wrapper .gf-style-3 .gfield_radio .radio-option .outer'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_3_background',
				array(
					'priority' => 15,
					'label'    => esc_html_x( 'Input Background Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_3',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_3_border', array(
				'default' => '#d9d9d9',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.content-area .gform_wrapper .gf-style-3 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])', 
						'.content-area .gform_wrapper .gf-style-3 input[type=email]', 
						'.content-area .gform_wrapper .gf-style-3 input[type=number]', 
						'.content-area .gform_wrapper .gf-style-3 input[type=password]', 
						'.content-area .gform_wrapper .gf-style-3 input[type=tel]', 
						'.content-area .gform_wrapper .gf-style-3 input[type=text]', 
						'.content-area .gform_wrapper .gf-style-3 input[type=url]', 
						'.content-area .gform_wrapper .gf-style-3 select', 
						'.content-area .gform_wrapper .gf-style-3 textarea',
						'.content-area .gform_wrapper .gf-style-3 .ginput_container_fileupload .bootstrap-filestyle', 
						'.content-area .gform_wrapper .gf-style-3 .ginput_container_fileupload .bootstrap-filestyle .form-control', 
						'.content-area .gform_wrapper .gf-style-3 .gfield_checkbox .checkbox-option .outer', 
						'.content-area .gform_wrapper .gf-style-3 .gfield_radio .radio-option .outer'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_3_border',
				array(
					'priority' => 20,
					'label'    => esc_html_x( 'Input Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_3',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_gravityforms_style_3_focus', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'border-color' => array(
						'.content-area .gform_wrapper .gf-style-3 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=email]:focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=number]:focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=password]:focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=tel]:focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=text]:focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=url]:focus', 
						'.content-area .gform_wrapper .gf-style-3 select:focus', 
						'.content-area .gform_wrapper .gf-style-3 textarea:focus',
						'.content-area .gform_wrapper .gf-style-3 .ginput_container_fileupload .bootstrap-filestyle:focus',
						'.content-area .gform_wrapper .gf-style-3 input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]).focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=email].focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=number].focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=password].focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=tel].focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=text].focus', 
						'.content-area .gform_wrapper .gf-style-3 input[type=url].focus', 
						'.content-area .gform_wrapper .gf-style-3 select.focus', 
						'.content-area .gform_wrapper .gf-style-3 textarea.focus',
						'.content-area .gform_wrapper .gf-style-3 .ginput_container_fileupload .bootstrap-filestyle.focus'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_gravityforms_style_3_focus',
				array(
					'priority' => 27,
					'label'    => esc_html_x( 'Input Focus Border Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_3',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_3_unchecked', array(
				'default' => '#ababab',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.content-area .gform_wrapper .gf-style-3 .checkbox-option .inner'
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'contact_form_7_style_3_unchecked',
				array(
					'priority' => 30,
					'label'    => esc_html_x( 'Checkbox Unchecked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_3',
				)
			) );
			$this->wp_customize->add_setting( new Leadx_Customize_CSS( $this->wp_customize, 'leadx_contact_form_7_style_3_checked', array(
				'default' => '#4fc198',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_map' => array(
					'background' => array(
						'.content-area .gform_wrapper .gf-style-3 .checkbox-option.checked .inner',
						'.content-area .gform_wrapper .gf-style-3 .radio-option.checked .inner',
					)
				)
			) ) );
			$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
				'leadx_contact_form_7_style_3_checked',
				array(
					'priority' => 35,
					'label'    => esc_html_x( 'Checkbox and Radio Checked Color', 'backend', 'leadx' ),
					'section'  => 'leadx_section_gravityforms_style_3',
				)
			) );
			
         }
        
        /**
         * WooCommerce
         */
        if (class_exists('Woocommerce')) {
        	$this->wp_customize->add_section( 'leadx_section_woocommerce', array(
				'title'       => esc_html_x( 'WooCommerce', 'backend', 'leadx' ),
				'priority'    => 104
			) );
			$this->wp_customize->add_setting( 'leadx_wc_title', array( 'default' => 'Shop', 'sanitize_callback' => false ) );
	        $this->wp_customize->add_control( 'leadx_wc_title', array(
				'type'        => 'text',
				'priority'    => 50,
				'label'       => esc_html_x( 'WooCommerce Title', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Text of the Titlebar for the Shop Overview & Product Detail Page.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
			) );
			$this->wp_customize->add_setting( 'leadx_header_show_cart', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_header_show_cart', array(
				'type'        => 'select',
				'priority'    => 10,
				'label'       => esc_html_x( 'Show shopping cart icon in header', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' ),
				),
			) );
			$this->wp_customize->add_setting( 'leadx_wc_main_layout', array( 'default' => 'left-sidebar', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_wc_main_layout', array(
				'type'        => 'select',
				'priority'    => 20,
				'label'       => esc_html_x( 'Main Shop Layout', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Select the Sidebar Position of the main shop layout.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
				'choices'     => array(
						'left-sidebar'	=> esc_html_x( 'Sidebar Left', 'backend', 'leadx' ),
						'right-sidebar'	=> esc_html_x( 'Sidebar Right', 'backend', 'leadx' ),
						'no-sidebar'	=> esc_html_x( 'No Sidebar', 'backend', 'leadx' ),
				),
			) );
			$this->wp_customize->add_setting( 'leadx_wc_single_layout', array( 'default' => 'no-sidebar', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_wc_single_layout', array(
				'type'        => 'select',
				'priority'    => 30,
				'label'       => esc_html_x( 'Single Product Layout', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Select the Sidebar Position of the single product page.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
				'choices'     => array(
						'left-sidebar'	=> esc_html_x( 'Sidebar Left', 'backend', 'leadx' ),
						'right-sidebar'	=> esc_html_x( 'Sidebar Right', 'backend', 'leadx' ),
						'no-sidebar'	=> esc_html_x( 'No Sidebar', 'backend', 'leadx' ),
				),
			) );
			$this->wp_customize->add_setting( 'leadx_wc_columns', array( 'default' => 'columns-3', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_wc_columns', array(
				'type'        => 'select',
				'priority'    => 40,
				'label'       => esc_html_x( 'WooCommerce Columns', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Select the columns for the shop layout.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
				'choices'     => array(
						'columns-2'	=> esc_html_x( '2 Columns', 'backend', 'leadx' ),
						'columns-3'	=> esc_html_x( '3 Columns', 'backend', 'leadx' ),
						'columns-4'	=> esc_html_x( '4 Columns', 'backend', 'leadx' ),
				),
			) );
			$this->wp_customize->add_setting( 'leadx_wc_items', array( 'default' => '12', 'sanitize_callback' => false ) );
	        $this->wp_customize->add_control( 'leadx_wc_items', array(
				'type'        => 'text',
				'priority'    => 50,
				'label'       => esc_html_x( 'Items per Shop Page', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Enter how many items you want to show on Shop pages & Categorie Pages before Pagination shows up (Default: 12).', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
			) );
			$this->wp_customize->add_setting( 'leadx_wc_second_image', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_wc_second_image', array(
				'type'        => 'select',
				'priority'    => 60,
				'label'       => esc_html_x( 'Secondary Image on Hover', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Show second image on hover of product images.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' )
				),
			) );
			$this->wp_customize->add_setting( 'leadx_wc_shop_sort', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_wc_shop_sort', array(
				'type'        => 'select',
				'priority'    => 60,
				'label'       => esc_html_x( 'Shop Sort', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Show sort-by function on Shop Pages.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' )
				),
			) );
			$this->wp_customize->add_setting( 'leadx_wc_result_count', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_wc_result_count', array(
				'type'        => 'select',
				'priority'    => 70,
				'label'       => esc_html_x( 'Shop Result Count', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Show result count on Shop Pages.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' )
				),
			) );
			$this->wp_customize->add_setting( 'leadx_wc_add_to_cart', array( 'default' => 'no', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_wc_add_to_cart', array(
				'type'        => 'select',
				'priority'    => 80,
				'label'       => esc_html_x( 'Add To Cart Button', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Show add-to-cart button on Shop Pages.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' )
				),
			) );
			$this->wp_customize->add_setting( 'leadx_wc_upsell', array( 'default' => 'no', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_wc_upsell', array(
				'type'        => 'select',
				'priority'    => 90,
				'label'       => esc_html_x( 'Upsells Products', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Show upsell products on Product Details Page.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' )
				),
			) );
			$this->wp_customize->add_setting( 'leadx_wc_related', array( 'default' => 'yes', 'sanitize_callback' => 'leadx_sanitize_choices' ) );
			$this->wp_customize->add_control( 'leadx_wc_related', array(
				'type'        => 'select',
				'priority'    => 100,
				'label'       => esc_html_x( 'Related Products', 'backend', 'leadx' ),
				'description' => esc_html_x( 'Show related products on Product Details Page.', 'backend', 'leadx' ),
				'section'     => 'leadx_section_woocommerce',
				'choices'     => array(
						'yes'	=> esc_html_x( 'Yes', 'backend', 'leadx' ),
						'no'	=> esc_html_x( 'No', 'backend', 'leadx' )
				),
			) );
		}
		
        /**
         * Custom Code (js/css)
         */
        $this->wp_customize->add_section( 'leadx_section_custom_code', array(
			'title'       => esc_html_x( 'Custom Code', 'backend', 'leadx' ),
			'priority'    => 105
		) );
		$this->wp_customize->add_setting( 'leadx_custom_css', array( 'default' => '/* enter your css here */', 'sanitize_callback' => false ) );
		$this->wp_customize->add_control( 'leadx_custom_css', array(
			'type'        => 'textarea',
			'label'       => esc_html_x( 'Custom CSS', 'backend', 'leadx' ),
			'description' => esc_html_x( 'Add your custom css code', 'backend', 'leadx' ),
			'section'     => 'leadx_section_custom_code',
		) );
		$this->wp_customize->add_setting( 'leadx_custom_js_head', array( 'sanitize_callback' => false ) );
		$this->wp_customize->add_control( 'leadx_custom_js_head', array(
			'type'        => 'textarea',
			'label'       => esc_html_x( 'Custom JavaScript (head)', 'backend', 'leadx' ),
			'description' => esc_html_x( 'You have to include the &lt;script&gt;&lt;/script&gt; tags as well.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_custom_code',
		) );
		$this->wp_customize->add_setting( 'leadx_custom_js_footer', array( 'sanitize_callback' => false ) );
		$this->wp_customize->add_control( 'leadx_custom_js_footer', array(
			'type'        => 'textarea',
			'label'       => esc_html_x( 'Custom JavaScript (footer)', 'backend', 'leadx' ),
			'description' => esc_html_x( 'You have to include the &lt;script&gt;&lt;/script&gt; tags as well.', 'backend', 'leadx' ),
			'section'     => 'leadx_section_custom_code',
		) );
	}
	
	

	/**
	 * Cache the rendered CSS after the settings are saved in the DB.
	 * This is purely a performance improvement.
	 *
	 * Used by hook: add_action( 'customize_save_after' , array( $this, 'cache_rendered_css' ) );
	 *
	 * @return void
	 */
	public function leadx_cache_rendered_css() {
		set_theme_mod( 'leadx_cached_css', $this->leadx_render_css() );
	}

	/**
	 * Get the dimensions of the logo image when the setting is saved
	 * This is purely a performance improvement.
	 *
	 * Used by hook: add_action( 'customize_save_logo_img' , array( $this, 'save_logo_dimensions' ), 10, 1 );
	 *
	 * @return void
	 */
	public function leadx_save_logo_dimensions( $setting ) {
		$logo_width_height = '';
		$img_data = getimagesize( esc_url( $setting->post_value() ) );

		if ( is_array( $img_data ) ) {
			$logo_width_height = $img_data[3];
		}

		set_theme_mod( 'leadx_logo_width_height', $logo_width_height );
	}

	/**
	 * Render the CSS from all the settings which are of type `Leadx_Customize_CSS`
	 *
	 * @return string text/css
	 */
	public function leadx_render_css() {
		$out = '';

		foreach ( $this->leadx_get_dynamic_css_settings() as $setting ) {
			$out .= $setting->leadx_setting_render_css();
		}

		return $out;
	}

	/**
	 * Get only the CSS settings of type `Leadx_Customize_CSS`.
	 *
	 * @see is_dynamic_css_setting
	 * @return array
	 */
	public function leadx_get_dynamic_css_settings() {
		return array_filter( $this->wp_customize->settings(), array( $this, 'leadx_is_dynamic_css_setting' ) );
	}

	/**
	 * Helper conditional function for filtering the settings.
	 *
	 * @see
	 * @param  mixed  $setting
	 * @return boolean
	 */
	protected static function leadx_is_dynamic_css_setting( $setting ) {
		return is_a( $setting, 'LeadX_Customize_CSS' );
	}

	/**
	 * Dynamically generate the JS for previewing the settings of type `Leadx_Customize_CSS`.
	 */
	public function leadx_customize_footer_js() {
		$settings = $this->leadx_get_dynamic_css_settings();

		ob_start();
		?>

			<script type="text/javascript">
				( function( $ ) {

				<?php
					foreach ( $settings as $key_id => $setting ) :
				?>

					wp.customize( '<?php echo esc_js($key_id); ?>', function( value ) {
						value.bind( function( newval ) {

						<?php
							foreach ( $setting->leadx_get_css_map() as $css_prop_raw => $css_selectors ) {
								extract( $setting->leadx_filter_css_property( $css_prop_raw ) );

								// background image needs a little bit different treatment
								if ( 'background-image' === $css_prop ) {
									echo 'newval = "url(" + newval + ")";' . PHP_EOL;
								}

								printf( '$( "%1$s" ).css( "%2$s", newval );%3$s', $setting->leadx_plain_selectors_for_all_groups( $css_prop_raw ), $css_prop, PHP_EOL );
							}
						?>

						} );
					} );

				<?php
					endforeach;
				?>

				} )( jQuery );
			</script>

		<?php

		echo ob_get_clean();
	}
}

