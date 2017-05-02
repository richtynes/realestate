<?php
/**
 * Template for Team Category
 *
 */

get_header(); ?>

<section class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <?php 
                        $value = get_query_var('team_category');
                        
                    	echo do_shortcode( '[ttbase_team_grid showfilter="no" filters="' . $value . '" border=""]' );
                    ?>
                </div>
                
                <div class="row">
        		    <?php leadx_paging_nav(); ?>
        		</div>
		
            </div>
        </div>
    </div>
</section>

<?php

get_footer();

?>
