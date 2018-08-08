<!-- Blog -->
<section  id="blog" class="epicoSection blogSection">
    <div class="wrap">
        <!-- blog post items -->
        <div id="content">
            <div id="blogLoop">
                <?php

                    $postpage = isset( $_GET['postpage'] ) ? (int) $_GET['postpage'] : 1;

                    $args2=array(
                        'post_type' => 'post',
                        'paged'          => $postpage
                        );

                    $main_query = new WP_Query($args2);

                    if ( $main_query->have_posts() ) {
                        
                        while ($main_query->have_posts()) { $main_query -> the_post();

                            get_template_part( 'templates/loop', 'blog' );
                        }
                    
                    } else {
                        esc_html_e('No Post Found!', 'vitrine');
                    }
                
                ?>
            </div>

            <?php if (have_posts()) { ?>

                <!-- Single Page Navigation-->
                <div class="pageNavigation clearfix">
                    <div class="navNext"><?php next_posts_link(esc_html__('&larr; Older Entries', 'vitrine')) ?></div>
                    <div class="navPrevious"><?php previous_posts_link(esc_html__('Newer Entries &rarr;', 'vitrine')) ?></div>
                </div>

            <?php } ?>
        </div>
    </div>
</section>
<!-- End Blog -->