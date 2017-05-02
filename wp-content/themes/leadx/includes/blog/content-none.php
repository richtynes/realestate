<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

?>

<article id="post-0" <?php post_class(array('col-sm-12','blog-item', 'clearfix')); ?>>

	<header class="entry-header">

		<h3 class="entry-title"><?php esc_html_e( 'Nothing Found', 'leadx' ); ?></h3>

	</header><!-- .page-header -->

	<div class="page-content">

		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'leadx' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'leadx' ); ?></p>
			<div class="row">
			    <div class="col-md-8">
			        <?php get_search_form(); ?>        
			    </div>
			</div>
			

		<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'leadx' ); ?></p>

			<div class="row">
			    <div class="col-md-8">
			        <?php get_search_form(); ?>        
			    </div>
			</div>

		<?php endif; ?>

	</div><!-- .page-content -->
	
</article><!-- .no-results -->