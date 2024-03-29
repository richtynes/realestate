<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 */
?>

<?php
$id = leadx_get_the_post_id();

$footer_style = (get_theme_mod('leadx_show_footer_title_separator','no') != 'yes') ? ' no-separator' : '';

$footer_text = get_theme_mod( 'leadx_footer_text', esc_html__('Copyright 2016 by themetwins. LeadX Theme crafted with love.', 'leadx') );
$footer_text = leadx_translate_theme_mod('leadx_footer_text', $footer_text);
?>


<footer class="site-footer <?php echo sanitize_html_class($footer_style); ?>">
    <?php if( get_theme_mod( 'leadx_footer_widgets_show', 'yes' ) == 'yes' && get_post_meta( $id, 'leadx_post_footer_widgets', true ) != 'hide' ) { ?>
    <div class="top-footer-container">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-lg-3">
                <?php if ( is_active_sidebar( 'footer-1' ) ) { ?>
					<?php dynamic_sidebar( 'footer-1' ); ?>
                <?php } ?>
              </div>
              <div class="col-xs-12 col-sm-6 col-lg-3">
                <?php if ( is_active_sidebar( 'footer-2' ) ) { ?>
					<?php dynamic_sidebar( 'footer-2' ); ?>
                <?php } ?>
              </div>
              <!-- Add the extra clearfix for only the required viewport -->
              <div class="clearfix visible-sm-block visible-md-block"></div>
              <div class="col-xs-12 col-sm-6 col-lg-3">
                <?php if ( is_active_sidebar( 'footer-3' ) ) { ?>
					<?php dynamic_sidebar( 'footer-3' ); ?>
                <?php } ?>
              </div>
			  <div class="col-xs-12 col-sm-6 col-lg-3">
                <?php if ( is_active_sidebar( 'footer-4' ) ) { ?>
					<?php dynamic_sidebar( 'footer-4' ); ?>
                <?php } ?>
              </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if( get_theme_mod( 'leadx_footer_bottom_show', 'yes' ) == 'yes' && get_post_meta( $id, 'leadx_post_footer_copyright', true ) != 'hide' ) { ?>
    <div class="bottom-footer-container">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="site-info">
                        <?php echo wp_kses($footer_text, leadx_allowed_tags()); ?>
                    </div><!-- .site-info -->   
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="footer-bottom-right-content pull-right" >
                        <?php if ( is_active_sidebar( 'footer-bottom-right' ) ) { ?>
        					<?php dynamic_sidebar( 'footer-bottom-right' ); ?>
                        <?php } ?>    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</footer>
<!-- Go Top Links -->
<a href="#" id="go-top"><i class="fa fa-angle-up"></i></a>

</div><!-- #page -->
<?php
global $leadx_modal_content;
echo do_shortcode($leadx_modal_content);

wp_footer(); 

?>

</body>

</html>
