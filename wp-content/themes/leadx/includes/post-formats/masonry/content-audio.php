<?php if ( get_post_meta( get_the_ID(), 'leadx_post_blog-audioembed', true ) != '' ) {  ?>
    <div class="content-audio">
       <?php   
            echo wp_kses(get_post_meta( get_the_ID(), 'leadx_post_blog-audioembed', true ), leadx_allowed_tags());
        ?>
    </div>
    <?php } ?>