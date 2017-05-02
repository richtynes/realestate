<?php
add_filter( 'rwmb_meta_boxes', function( $meta_boxes )
{
	
	/* ----------------------------------------------------- */
	// Page Settings
	/* ----------------------------------------------------- */
	
	$meta_boxes[] = array(
		'id'			=> 'pagesettings',
		'title' 		=> esc_html__('Page Settings','leadx'),
		'post_types'	=> array( 'page', 'post', 'team', 'service', 'portfolio' ),
		'context'		=> 'normal',
		'priority'		=> 'high',

		'tabs'      	=> array(
            'header'	=> array(
                'label' => esc_html__( 'Header', 'leadx' ),
            ),
            'footer'	=> array(
                'label' => esc_html__( 'Footer', 'leadx' ),
            ),
        	'content'	=> array(
                'label' => esc_html__( 'Content', 'leadx' ),
            ),
            'logo'	=> array(
                'label' => esc_html__( 'Logo', 'leadx' ),
            ),
        ),

        // Tab style: 'default', 'box' or 'left'. Optional
        'tab_style' => 'default',
		
		// List of meta fields
		'fields' => array(
				array(
						'name'		=> esc_html__( 'Logo Image', 'leadx' ),
						'id'		=> "leadx_post_logo_img",
						'type'		=> 'image_advanced',
						'desc'		=> esc_html__( 'Overwrites default logo from customizer.', 'leadx' ),
						'tab'		=> 'logo'
				),
				array(
						'name'		=> esc_html__( 'Logo Image (Transparent)', 'leadx' ),
						'id'		=> "leadx_post_logo_img_transparent",
						'type'		=> 'image_advanced',
						'desc'		=> esc_html__( 'Overwrites alternative transparent logo from customizer.', 'leadx' ),
						'tab'		=> 'logo'
				),
				array(
						'id'		=> "leadx_post_logo_divider1",
						'type'		=> 'divider',
						'tab'		=> 'logo'
				),
				array(
						'name'		=> esc_html__( 'Retina Logo Image', 'leadx' ),
						'id'		=> "leadx_post_logo2x_img",
						'type'		=> 'image_advanced',
						'desc'		=> esc_html__( 'Overwrites default 2x size logo from customizer.', 'leadx' ),
						'tab'		=> 'logo'
				),
				array(
						'name'		=> esc_html__( 'Retina Logo Image (Transparent)', 'leadx' ),
						'id'		=> "leadx_post_logo2x_img_transparent",
						'type'		=> 'image_advanced',
						'desc'		=> esc_html__( 'Overwrites alternative transparent 2x size logo from customizer.', 'leadx' ),
						'tab'		=> 'logo'
				),
				array(
						'id'		=> "leadx_post_logo_divider2",
						'type'		=> 'divider',
						'tab'		=> 'logo'
				),
				array(
						'name'		=> esc_html__( 'Sticky & Mobile Logo Image', 'leadx' ),
						'id'		=> "leadx_post_logo_mobile",
						'type'		=> 'image_advanced',
						'desc'		=> esc_html__( 'Overwrites default sticky and mobile logo from customizer.', 'leadx' ),
						'tab'		=> 'logo'
				),
				array(
						'name'		=> esc_html__( 'Sticky & Mobile Retina Logo Image', 'leadx' ),
						'id'		=> "leadx_post_logo2x_mobile",
						'type'		=> 'image_advanced',
						'desc'		=> esc_html__( 'Overwrites sticky and mobile 2x size logo from customizer.', 'leadx' ),
						'tab'		=> 'logo'
				),
				array(
					'name'		=> esc_html__( 'Header Style', 'leadx' ),
					'id'		=> "leadx_post_header_style",
					'type'		=> 'select',
					'options'	=> array(
						''							=> esc_html__( 'Default (set in Customizer)', 'leadx' ),
						'header-top-full'			=> esc_html__( 'Top Full Width', 'leadx' ),
						'header-top-boxed'	    	=> esc_html__( 'Top Boxed', 'leadx' ),
						'header-transparent-full'	=> esc_html__( 'Transparent Full Width', 'leadx' ),
						'header-transparent-boxed'	=> esc_html__( 'Transparent Boxed', 'leadx' ),
						'header-boxed'				=> esc_html__( 'Boxed', 'leadx' ),
                        'header-stacked'        	=> esc_html__( 'Logo above top bar', 'leadx' ),
                        'header-none'				=> esc_html__( 'No Header', 'leadx' )
					),
					'multiple'	=> false,
					'std'		=> array( '' ),
					'desc'		=> esc_html__( 'Choose your Header Style for this Page', 'leadx' ),
					'tab'		=> 'header'
				),
				array(
						'name'		=> esc_html__( 'Show Bottom Border For Transparent Headers?', 'leadx' ),
						'id'		=> "leadx_post_header_border_show",
						'type'		=> 'select',
						'options'	=> array(
							''					=> esc_html__( 'Default (set in Customizer)', 'leadx' ),
							'header-border'		=> esc_html__( 'Show', 'leadx' ),
							'no-border'			=> esc_html__( 'Hide', 'leadx' )
						),
						'multiple'	=> false,
						'std'		=> array( '' ),
						'desc'		=> esc_html__( 'Enable or Disable the bottom border for the transparent header styles.', 'leadx' ),
						'tab'		=> 'header',
						'visible'	=> array(
							array( 'leadx_post_header_style', 'in', array('header-transparent-full', 'header-transparent-boxed')),
						)
				),
			array(
					'name'		=> esc_html__( 'Transparent Header Bottom Border Color', 'leadx' ),
					'id'		=> "leadx_post_header_bottom_border_color",
					'type'		=> 'color',
					'desc'		=> esc_html__( 'Overwrite default color', 'leadx' ),
					'std'		=> '',
					'tab'		=> 'header',
					'visible'	=> array(
						array( 'leadx_post_header_style', 'in', array('header-transparent-full', 'header-transparent-boxed')),
						array( 'leadx_post_header_border_show', '!=', 'no-border')
					)
			),
			array(
					'id'		=> "leadx_post_divider1",
					'type'		=> 'divider',
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
				'name'		=> esc_html__( 'Individual Page Menu', 'leadx' ),
				'id'		=> "leadx_post_header_menu",
				'desc'		=> esc_html__( 'Set a different navigation menu only for this page.', 'leadx' ),
				'type'    	=> 'taxonomy_advanced',
				'tab'		=> 'header',
				'taxonomy' 	=> 'nav_menu',
				'field_type'=> 'select'
			),
			array(
					'id'		=> "leadx_post_divider2",
					'type'		=> 'divider',
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Show or hide Page Title', 'leadx' ),
					'id'		=> "leadx_post_header",
					'type'		=> 'select',
					'options'	=> array(
						'show'		=> esc_html__( 'Show', 'leadx' ),
						'hide'		=> esc_html__( 'Hide', 'leadx' )
					),
					'multiple'	=> false,
					'std'		=> array( 'show' ),
					'desc'		=> esc_html__( 'Enable or Disable the page title on this Page.', 'leadx' ),
					'tab'		=> 'header',
			),
			array(
					'name'		=> esc_html__( 'Page Title Background', 'leadx' ),
					'id'		=> "leadx_post_header_background",
					'type'		=> 'select',
					'options'	=> array(
						'color'     => esc_html__( 'Color', 'leadx' ),
                        'image'     => esc_html__( 'Image', 'leadx' ),
                        'slider'	=> esc_html__( 'Slider', 'leadx' ),
					),
					'multiple'	=> false,
					'std'		=> array( 'color' ),
					'desc'		=> esc_html__( 'Set the background for the page title to slider, image or color.', 'leadx' ),
					'tab'		=> 'header',
					'hidden'	=> array('leadx_post_header', '!=', 'show')
					
			),
			array(
					'name'		=> esc_html__( 'Page Title Height', 'leadx' ),
					'id'		=> "leadx_post_header_height",
					'type'		=> 'select',
					'options'	=> array(
						''			=> esc_html__( 'Default (set in Customizer)', 'leadx' ),
                        'large'     => esc_html__( 'Large', 'leadx' ),
						'small'		=> esc_html__( 'Small', 'leadx' ),
						'x-large'	=> esc_html__( 'Extra Large', 'leadx' ),
                        'x-small'   => esc_html__( 'Extra Small', 'leadx' )
					),
					'multiple'	=> false,
					'std'		=> array( '' ),
					'desc'		=> esc_html__( 'Set the page title height (Extra Small only for color background).', 'leadx' ),
					'tab'		=> 'header',
					'visible'	=> array(
						array( 'leadx_post_header_background', 'in', array('image', 'color')),
						array( 'leadx_post_header', 'show')
					)
					
			),
			array(
					'name'		=> esc_html__( 'Page Title Background Image', 'leadx' ),
					'id'		=> "leadx_post_header_image",
					'type'		=> 'image_advanced',
					'desc'		=> esc_html__( 'Upload page title image.', 'leadx' ),
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header_background', 'image'),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Parallax Effect', 'leadx' ),
					'id'		=>  "leadx_post_header_image_parallax",
					'type'		=> 'checkbox',
					'desc'		=> esc_html__( 'Add parallax effect to background image', 'leadx' ),
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header_background', 'image'),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Image Overlay Color', 'leadx' ),
					'id'		=> "leadx_post_pagetitle_image_overlay_color",
					'type'		=> 'color',
					'desc' => esc_html__( 'Add overlay color to image background', 'leadx' ),
					'std'		=> '',
					'tab'  => 'header',
					'visible' => array(
					    array( 'leadx_post_header_background', 'image'),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Image Overlay Opacity', 'leadx' ),
					'id'		=> "leadx_post_pagetitle_image_overlay_opacity",
					'type'		=> 'select',
					'options'	=> array(
						'1'			=> esc_html__( '0%', 'leadx' ),
						'0.9'		=> esc_html__( '10%', 'leadx' ),
						'0.8'		=> esc_html__( '20%', 'leadx' ),
						'0.7'		=> esc_html__( '30%', 'leadx' ),
						'0.6'		=> esc_html__( '40%', 'leadx' ),
						'0.5'		=> esc_html__( '50%', 'leadx' ),
						'0.4'		=> esc_html__( '60%', 'leadx' ),
						'0.3'		=> esc_html__( '70%', 'leadx' ),
						'0.2'		=> esc_html__( '80%', 'leadx' ),
						'0.1'		=> esc_html__( '90%', 'leadx' ),
						'0'			=> esc_html__( '100%', 'leadx' ),
					),
					'multiple'	=> false,
					'std'		=> '0.7',
					'tab'  => 'header',
					'visible' => array(
						array( 'leadx_post_header_background', 'image'),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Revolution Slider Shortcode', 'leadx' ),
					'id'		=> "leadx_post_header_slider",
					'type'		=> 'text',
					'desc'		=> esc_html__('[rev_slider id="sliderId"]', 'leadx'),
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header_background', 'slider'),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Page Title Background Color', 'leadx' ),
					'id'		=> "leadx_post_header_color",
					'type'		=> 'color',
					'desc'		=> esc_html__( 'Overwrite default color', 'leadx' ),
					'std'		=> '',
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header_background', 'color'),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'id'		=> "leadx_post_divider3",
					'type'		=> 'divider',
					'tab'		=> 'header',
					'visible'	=> array(
						array( 'leadx_post_header_background', 'in', array('image', 'color')),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Page Title', 'leadx' ),
					'id'		=> "leadx_post_page_title",
					'type'		=> 'text',
					'desc'		=> esc_html__( 'Overwrite the page title', 'leadx' ),
					'tab'		=> 'header',
					'visible'	=> array(
						array( 'leadx_post_header_background', 'in', array('image', 'color')),
						array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Page Title Alignment', 'leadx' ),
					'id'		=> "leadx_post_pagetitle_pos",
					'type'		=> 'select',
					'options'	=> array(
						''					=> esc_html__( 'Default (set in Customizer)', 'leadx' ),
						'text-left'			=> esc_html__( 'Left', 'leadx' ),
                        'text-center'   	=> esc_html__( 'Center', 'leadx' ),
						'text-right'		=> esc_html__( 'Right', 'leadx' )
					),
					'multiple'	=> false,
					'std'		=> array( '' ),
					'desc'		=> esc_html__( 'Set the page title alignment.', 'leadx' ),
					'tab'		=> 'header',
					'visible'	=> array(
						array( 'leadx_post_header_background', 'in', array('image', 'color')),
						array( 'leadx_post_header_height', 'in', array('', 'large', 'small', 'x-large')),
						array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Page Title Color', 'leadx' ),
					'id'		=> "leadx_post_pagetitle_color",
					'type'		=> 'color',
					'desc'		=> esc_html__( 'Overwrite default color', 'leadx' ),
					'std'		=> '',
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header_background', 'in', array('image', 'color')),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Page Title Separator Color', 'leadx' ),
					'id'		=> "leadx_post_pagetitle_underline_color",
					'type'		=> 'color',
					'desc'		=> esc_html__( 'Overwrite default color', 'leadx' ),
					'std'		=> '',
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header_background', 'in', array('image', 'color')),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Page Subtitle', 'leadx' ),
					'id'		=> "leadx_post_page_subtitle",
					'type'		=> 'text',
					'desc'		=> esc_html__( 'Set the page subtitle (only for extra large height).', 'leadx' ),
					'tab'		=> 'header',
					'visible'	=> array(
						array( 'leadx_post_header_background', 'in', array('image', 'color')),
						array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Page Subtitle Color', 'leadx' ),
					'id'		=> "leadx_post_page_subtitle_color",
					'type'		=> 'color',
					'desc'		=> esc_html__( 'Overwrite default color', 'leadx' ),
					'std'		=> '',
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header_background', 'in', array('image', 'color')),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'id'		=> "leadx_post_divider4",
					'type'		=> 'divider',
					'tab'		=> 'header',
					'visible'	=> array(
						array( 'leadx_post_header_background', 'in', array('image', 'color')),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Hide Breadcrumbs', 'leadx' ),
					'id'		=> "leadx_post_breadcrumbs_hide",
					'type'		=> 'checkbox',
					'desc'		=> esc_html__( 'Check to hide breadcrumbs for this page', 'leadx' ),
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header_background', 'in', array('image', 'color')),
					    array( 'leadx_post_header', 'show')
					)
			),
			array(
					'name'		=> esc_html__( 'Breadcrumbs Color', 'leadx' ),
					'id'		=>  "leadx_post_breadcrumb_color",
					'type'		=> 'color',
					'desc'		=> esc_html__( 'Overwrite default color', 'leadx' ),
					'std'		=> '',
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header_background', 'in', array('image', 'color')),
					    array( 'leadx_post_header', 'show'),
					    array( 'leadx_post_breadcrumbs_hide', false )
					)
			),
			array(
					'name'		=> esc_html__( 'Current Breadcrumb Color', 'leadx' ),
					'id'		=>  "leadx_post_breadcrumb_current_color",
					'type'		=> 'color',
					'desc'		=> esc_html__( 'Overwrite default color', 'leadx' ),
					'std'		=> '',
					'tab'		=> 'header',
					'visible'	=> array(
					    array( 'leadx_post_header_background', 'in', array('image', 'color')),
					    array( 'leadx_post_header', 'show'),
					    array( 'leadx_post_breadcrumbs_hide', false )
					)
			),
			array(
					'name'		=> esc_html__( 'Footer Widgets', 'leadx' ),
					'id'		=> "leadx_post_footer_widgets",
					'type'		=> 'select',
					'options'	=> array(
						'show'		=> esc_html__('Enable', 'leadx'),
						'hide'		=> esc_html__('Disable', 'leadx')
					),
					'multiple'	=> false,
					'std'		=> array( 'show' ),
					'desc'		=> esc_html__( 'Enable or disable the Footer Widgets on this Page.', 'leadx' ),
					'tab'		=> 'footer',
			),
			array(
					'name'		=> esc_html__( 'Footer Copyright', 'leadx' ),
					'id'		=> "leadx_post_footer_copyright",
					'type'		=> 'select',
					'options'	=> array(
						'show'		=> esc_html__('Enable', 'leadx'),
						'hide'		=> esc_html__('Disable', 'leadx')
					),
					'multiple'	=> false,
					'std'		=> array( 'show' ),
					'desc'		=> esc_html__( 'Enable or disable the Footer Copyright Section on this Page.', 'leadx' ),
					'tab'		=> 'footer',
			),
			array(
					'name'		=> esc_html__( 'Page Background Color', 'leadx' ),
					'id'		=> "leadx_post_site_bg_color",
					'type'		=> 'color',
					'desc'		=> esc_html__( 'Overwrite default color', 'leadx' ),
					'std'		=> '',
					'tab'		=> 'content'
				),
			array(
					'name'		=> esc_html__( 'Content Top Padding', 'leadx' ),
					'id'		=>  "leadx_post_top_padding",
					'type'		=> 'select',
					'options'	=> array(
						'pdt0'		=> '0',
						'pdt5'		=> '5px',
						'pdt10'		=> '10px',
						'pdt15'		=> '15px',
						'pdt25'		=> '25px',
						'pdt35'		=> '35px',
						'pdt50'		=> '50px',
						'pdt75'		=> '75px',
					),
					'multiple'	=> false,
					'std'		=> array( 'pdt75' ),
					'desc'		=> esc_html__( 'Set the top padding for the content.', 'leadx' ),
					'tab'		=> 'content',
			),
			array(
					'name'		=> esc_html__( 'Content Bottom Padding', 'leadx' ),
					'id'		=> "leadx_post_bottom_padding",
					'type'		=> 'select',
					'options'	=> array(
						'pdb0'		=> '0',
						'pdb5'		=> '5px',
						'pdb10'		=> '10px',
						'pdb15'		=> '15px',
						'pdb25'		=> '25px',
						'pdb35'		=> '35px',
						'pdb50'		=> '50px',
						'pdb75'		=> '75px',
					),
					'multiple'	=> false,
					'std'		=> array( 'pdb75' ),
					'desc'		=> esc_html__( 'Set the bottom padding for the content.', 'leadx' ),
					'tab'		=> 'content',
			),
			array(
					'name'		=> esc_html__( 'Sidebar', 'leadx' ),
					'id'		=> "leadx_post_content_sidebar",
					'type'		=> 'select',
					'options'	=> array(
						'show'		=> esc_html__('Enable','leadx'),
						'hide'		=> esc_html__('Disable','leadx')
					),
					'multiple'	=> false,
					'std'		=> array( 'show' ),
					'desc'		=> esc_html__( 'Enable or Disable the Sidebar for this Post.', 'leadx' ),
					'tab'		=> 'content',
					'visible'	=> array(
					    array( 'post_type', 'in', array('service', 'team')),
					)
			),
		)
	);
	
	
	/* ----------------------------------------------------- */
	// Blog Metaboxes
	/* ----------------------------------------------------- */
	
	// Link Post Format
	$meta_boxes[] = array(
		'id'		=> 'blog-link',
		'title' 	=> esc_html__( 'Link Settings', 'leadx' ),
		'pages' 	=> array( 'post' ),
		'context'	=> 'normal',
		'priority'	=> 'high',
		'visible'	=> array( 'post_format', 'link' ),
		// List of meta fields
		'fields'	=> array(
			array(
				'name'		=> esc_html__( 'URL', 'leadx' ),
				'id'		=> 'leadx_post_blog-link',
				'desc'		=> esc_html__( 'Enter a URL for your link post format. (Don\'t forget the http://)', 'leadx' ),
				'clone'		=> false,
				'type'		=> 'text',
				'std'		=> ''
			)
		)
	);

	// Quote Post Format
	$meta_boxes[] = array(
		'id'		=> 'blog-quote',
		'title' 	=> esc_html__( 'Quote Settings', 'leadx' ),
		'pages' 	=> array( 'post' ),
		'context'	=> 'normal',
		'priority'	=> 'high',
		'visible'	=> array( 'post_format', 'quote' ),
		// List of meta fields
		'fields'	=> array(
			array(
				'name'		=> esc_html__( 'Quote', 'leadx' ),
				'id'		=> 'leadx_post_blog-quote',
				'desc'		=> esc_html__( 'Please enter the text for your quote here.', 'leadx' ),
				'clone'		=> false,
				'type'		=> 'textarea',
				'std'		=> ''
			),
			array(
				'name'		=> esc_html__( 'Quote Source', 'leadx' ),
				'id'		=> 'leadx_post_blog-quotesource',
				'desc'		=> esc_html__( 'Please enter the Source of the Quote here.', 'leadx' ),
				'clone'		=> false,
				'type'		=> 'text',
				'std'		=> ''
			)
		)
	);

	// Video Post Format
	$meta_boxes[] = array(
		'id'		=> 'blog-video',
		'title' 	=> esc_html__( 'Video Settings', 'leadx' ),
		'pages' 	=> array( 'post' ),
		'context'	=> 'normal',
		'priority'	=> 'high',
		'visible'	=> array( 'post_format', 'video' ),
		// List of meta fields
		'fields'	=> array(
			array(
				'name'		=> esc_html__( 'Video Source', 'leadx' ),
				'id'		=> 'leadx_post_blog-videosource',
				'type'		=> 'select',
				'options'	=> array(
					'videourl'		=> esc_html__( 'Video URL', 'leadx' ),
					'embedcode'		=> esc_html__( 'Embed Code', 'leadx' )
				),
				'multiple'	=> false,
				'std'		=> array( 'videourl' ),
			),
			array(
				'name'		=> esc_html__( 'Video URL', 'leadx' ),
				'id'		=> 'leadx_post_blog-videourl',
				'desc'		=> sprintf( wp_kses(__( 'You can just insert the URL of the %s. If you fill out this field, it will be shown instead of the Slider. Notice: The Preview Image will be the Image set as Featured Image.', 'leadx' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), '<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">Supported Video Site</a>' ),
				'type'		=> 'textarea',
				'std'		=> '',
				'hidden'	=> array( 'leadx_post_blog-videosource', 'embedcode' ),
			),
			array(
				'name'		=> esc_html__( 'Video Embed Code', 'leadx' ),
				'id'		=> 'leadx_post_blog-videoembed',
				'desc'		=> esc_html__( 'Insert the full embed code.', 'leadx' ),
				'clone'		=> false,
				'type'		=> 'textarea',
				'std'		=> '',
				'hidden'	=> array( 'leadx_post_blog-videosource', 'videourl' ),
			),
		)
	);

	// Audio Post Format
	$meta_boxes[] = array(
		'id'		=> 'blog-audio',
		'title' 	=> esc_html__( 'Audio Settings', 'leadx' ),
		'pages' 	=> array( 'post' ),
		'context'	=> 'normal',
		'priority'	=> 'high',
		'visible'	=> array( 'post_format', 'audio' ),
		// List of meta fields
		'fields'	=> array(
			array(
				'name'		=> esc_html__( 'Audio Embed Code', 'leadx' ),
				'id'		=> 'leadx_post_blog-audioembed',
				'desc'		=> esc_html__( 'Insert the full audio embed code here.', 'leadx' ),
				'clone'		=> false,
				'type'		=> 'textarea',
				'std'		=> ''
			),
		)
	);

	// Gallery Post Format
	$meta_boxes[] = array(
		'id'		=> 'blog-gallery',
		'title' 	=> esc_html__( 'Gallery Settings', 'leadx' ),
		'pages' 	=> array( 'post' ),
		'context'	=> 'normal',
		'priority'	=> 'high',
		'visible'	=> array( 'post_format', 'gallery' ),
		// List of meta fields
		'fields'	=> array(
			array(
				'name'				=> esc_html__( 'Gallery', 'leadx' ),
				'desc'				=> esc_html__( 'You can upload up to 50 gallery images for a slideshow', 'leadx' ),
				'id'				=> 'leadx_post_blog-gallery',
				'type'				=> 'image_advanced',
				'max_file_uploads'	=> 50,
			)
		)
	);
	
	/* ----------------------------------------------------- */
	// Portfolio Metaboxes
	/* ----------------------------------------------------- */
	if ( post_type_exists( 'portfolio' ) ) {
		$meta_boxes[] = array(
			'id'		=> 'portfolio_info',
			'title' 	=> esc_html__('Portfolio Settings','leadx'),
			'pages' 	=> array( 'portfolio' ),
			'context'	=> 'normal',

			'tabs'      => array(
				'portfolio' 	=> array(
	                'label' 	=> esc_html__( 'Portfolio Configuration', 'leadx' ),
	            ),
	            'slides'		=> array(
	                'label' 	=> esc_html__( 'Portfolio Slides', 'leadx' ),
	            ),
	            'video' 		=> array(
	                'label' 	=> esc_html__( 'Portfolio Video', 'leadx' ),
	            ),
	        ),

	        // Tab style: 'default', 'box' or 'left'. Optional
	        'tab_style' => 'default',
			
			'fields'	=> array(
				
				array(
					'name'		=> esc_html__('Detail Layout','leadx'),
					'id'		=> 'leadx_post_portfolio-detaillayout',
					'desc'		=> esc_html__('Choose your Layout for the Portfolio Detail Page.','leadx'),
					'type'		=> 'select',
					'options'	=> array(
						'wide'			=> esc_html__('Full Width (Slider)','leadx'),
						'wide-ns'		=> esc_html__('Full Width (No Slider)','leadx'),
						'sidebyside'	=> esc_html__('Side By Side (Slider)','leadx'),
						'sidebyside-ns'	=> esc_html__('Side By Side (No Slider)','leadx')
					),
					'multiple'	=> false,
					'std'		=> array( 'no' ),
					'tab'		=> 'portfolio',
				),
				array(
					'name'		=> esc_html__('Client','leadx'),
					'id'		=> 'leadx_post_portfolio-client',
					'desc'		=> esc_html__('The Client is shown on the Portfolio Detail Page. You can leave this empty to hide it.','leadx'),
					'clone'		=> false,
					'type'		=> 'text',
					'std'		=> '',
					'tab'		=> 'portfolio',
				),
				array(
					'name'		=> esc_html__('Project link','leadx'),
					'id'		=> 'leadx_post_portfolio-link',
					'desc'		=> esc_html__('URL Link to your Project (Do not forget the http://). This will be shown on the Portfolio Detail Page. You can leave this empty to hide it.','leadx'),
					'clone'		=> false,
					'type'		=> 'text',
					'std'		=> '',
					'tab'		=> 'portfolio',
				),
				array(
					'name'		=> esc_html__('Show Project Details?','leadx'),
					'id'		=>  "leadx_post_portfolio-details",
					'type'		=> 'checkbox',
					'std'		=> true,
					'tab'		=> 'portfolio',
				),
				array(
					'name'		=> esc_html__('Show Related Projects?','leadx'),
					'id'		=>  "leadx_post_portfolio-relatedposts",
					'type'		=> 'checkbox',
					'desc'		=> '',
					'std'		=> false,
					'tab'		=> 'portfolio',
				),
				array(
					'name'		=> esc_html__('Masonry Size','leadx'),
					'id'		=> 'leadx_post_portfolio-size',
					'desc'		=> esc_html__('Only relevant when the portfolio is displayed in masonry format.','leadx'),
					'type'		=> 'select',
					'options'	=> array(
						'regular'	=> esc_html__('Regular','leadx'),
						'wide'		=> esc_html__('Wide','leadx'),
						'tall'		=> esc_html__('Tall','leadx'),
						'widetall'	=> esc_html__('Wide & Tall','leadx')
					),
					'multiple'	=> false,
					'std'		=> array( 'regular' ),
					'tab'		=> 'portfolio',
				),
				array(
					'name'				=> esc_html__('Project Slider Images','leadx'),
					'desc'				=> esc_html__('You can upload up to 50 project images for a slideshow, or only one image to display a single image. Notice: The Preview Image (on Overview, Shortcodes & Related Projects) will be the Image set as Featured Image.','leadx'),
					'id'				=> 'leadx_post_screenshot',
					'type'				=> 'image_advanced',
					'max_file_uploads'	=> 50,
					'tab'				=> 'slides',
				),
				array(
					'name'		=> esc_html__('Video Source','leadx'),
					'id'		=> 'leadx_post_source',
					'type'		=> 'select',
					'options'	=> array(
						'videourl'		=> esc_html__('Video URL','leadx'),
						'embedcode'		=> esc_html__('Embed Code','leadx')
					),
					'multiple'	=> false,
					'std'		=> array( 'no' ),
					'tab'		=> 'video',
				),
				array(
					'name'		=> esc_html__('Video URL','leadx'),
					'id'		=> 'leadx_post_videourl',
					'desc'  	=> sprintf( wp_kses(__( 'You can just insert the URL of the %s. If you fill out this field, it will be shown instead of the Slider. Notice: The Preview Image will be the Image set as Featured Image.', 'leadx' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), '<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">Supported Video Site</a>' ),
					'type' 		=> 'textarea',
					'std' 		=> "",
					'cols' 		=> "40",
					'rows' 		=> "8",
					'tab'		=> 'video',
					'visible'	=> array( 'leadx_post_source', 'videourl' ),
				),
				array(
					'name'		=> esc_html__('Embed Code','leadx'),
					'id'		=> 'leadx_post_embed',
					'desc'		=> esc_html__('Insert your own Embed Code. If you fill out this field, it will be shown instead of the Slider. Notice: The Preview Image will be the Image set as Featured Image.','leadx'),
					'type' 		=> 'textarea',
					'std' 		=> "",
					'cols' 		=> "40",
					'rows' 		=> "8",
					'tab'		=> 'video',
					'visible'	=> array( 'leadx_post_source', 'embedcode' ),
				)
			)
		);
	}
	
	/* ----------------------------------------------------- */
	// Testimonial Metaboxes
	/* ----------------------------------------------------- */
	if ( post_type_exists( 'testimonial' ) ) {
		$meta_boxes[] = array(
			'id'		=> 'testimonialsettings',
			'title' 	=> esc_html__('Testimonial Settings','leadx'),
			'pages' 	=> array( 'testimonial' ),
			'context'	=> 'normal',
			'priority'	=> 'high',
		
			// List of meta fields
			'fields' => array(
				array(
					'name'	=> esc_html__( 'Author', 'leadx' ),
					'desc'	=> esc_html__( 'Enter the author of this testimonial', 'leadx' ),
					'id'	=> 'leadx_post_testimonial-author',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'Company', 'leadx' ),
					'desc'	=> esc_html__( 'Enter the company of this testimonial', 'leadx' ),
					'id'	=> 'leadx_post_testimonial-company',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'Company URL', 'leadx' ),
					'desc'	=> esc_html__( 'Enter the company url of this testimonial', 'leadx' ),
					'id'	=> 'leadx_post_testimonial-companyurl',
					'type'	=> 'text'
				)
			)
		);
	}
	
	/* ----------------------------------------------------- */
	// Team Metaboxes
	/* ----------------------------------------------------- */
	if ( post_type_exists( 'team' ) ) {
		$meta_boxes[] = array(
			'id'		=> 'teamsettings',
			'title' 	=> esc_html__( 'Team Member Settings', 'leadx' ),
			'pages' 	=> array( 'team' ),
			'context'	=> 'normal',
			'priority'	=> 'high',
		
			// List of meta fields
			'fields' => array(
				array(
					'name'	=> esc_html__( 'Position', 'leadx' ),
					'desc'	=> esc_html__( 'Enter the job position of the team member', 'leadx' ),
					'id'	=> 'leadx_post_team-position',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'Twitter Profile', 'leadx' ),
					'desc'	=> esc_html__( 'Enter your twitter profile url', 'leadx' ),
					'id'	=> 'leadx_post_team-twitter',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'Facebook Profile', 'leadx' ),
					'desc'	=> esc_html__( 'Enter your facebook profile url', 'leadx' ),
					'id'	=> 'leadx_post_team-facebook',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'Google+ Profile', 'leadx' ),
					'desc'	=> esc_html__( 'Enter your google plus profile url', 'leadx' ),
					'id'	=> 'leadx_post_team-googleplus',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'Instagram Profile', 'leadx' ),
					'desc'	=> esc_html__( 'Enter your instagram profile url', 'leadx' ),
					'id'	=> 'leadx_post_team-instagram',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'LinkedIn Profile', 'leadx' ),
					'desc'	=> esc_html__( 'Enter your linkedin profile url', 'leadx' ),
					'id'	=> 'leadx_post_team-linkedin',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'Dribbble Profile', 'leadx' ),
					'desc'	=> esc_html__( 'Enter your dribbble profile url', 'leadx' ),
					'id'	=> 'leadx_post_team-dribbble',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'Skype Profile', 'leadx' ),
					'desc'	=> esc_html__( 'Enter your skype profile url', 'leadx' ),
					'id'	=> 'leadx_post_team-skype',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'Phone Number', 'leadx' ),
					'desc'	=> esc_html__( 'Enter your phone number', 'leadx' ),
					'id'	=> 'leadx_post_team-phone',
					'type'	=> 'text'
				),
				array(
					'name'	=> esc_html__( 'E-Mail', 'leadx' ),
					'desc'	=> esc_html__( 'Enter your mail', 'leadx' ),
					'id'	=> 'leadx_post_team-mail',
					'type'	=> 'text'
				)
			)
		);
	}
	
	/* ----------------------------------------------------- */
	// Service Metaboxes
	/* ----------------------------------------------------- */
	if ( post_type_exists( 'service' ) ) {
		$meta_boxes[] = array(
			'id'		=> 'service_show_featured_image',
			'title' 	=> esc_html__('Hide Featured Image?','leadx'),
			'pages' 	=> array( 'service' ),
			'context'	=> 'side',
			'priority'	=> 'low',
			// List of meta fields
			'fields'	=> array(
				array(
					'name'		=> '',
					'id'		=> "leadx_post_service_featured_image",
					'type'		=> 'checkbox',
					'desc'		=> '',
					'std'		=> false
				),
			)
		);
	}
	
	return $meta_boxes;
} );