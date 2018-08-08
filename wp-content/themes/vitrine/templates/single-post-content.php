<?php 
    $sidebar=epico_opt('blog-sidebar-position');//to make header gallery and video fill in span8
    if($sidebar=='no-sidebar'){?>
        <div class="row"><div class="span8 offset2 single-post-metas">
    <?php }
    //check social share is Enable or not
	if(get_post_meta( get_the_ID(), "social_share_inherit", true ) == '1')
	{
		$socialshare = get_post_meta( get_the_ID(), "post-social-share", true );
	}
	else
	{
		$socialshare = epico_opt("social_share_display"); //theme settings;
	}
?>

    <div class="social-tag">
            <div class="post-tags"><?php if(has_tag()){ ?> <span class="tagsTitle"> <?php esc_html_e('TAGS:', 'vitrine'); ?> </span> <?php }the_tags('     #','    #','');?></div>
				<div class="social_share_container">
                    <!-- blog Socail share -->
                    <?php ep_social_share(); ?>
                </div>
        </div>
        <hr/>
        <?php
        $prevPost = get_previous_post();
        $nextPost = get_next_post();
        $prevtitle="";
        $nexttitle="";
        $prevthumbnail[0]="";
        $nextthumbnail[0]="";
        if ($prevPost){
            $prevtitle=get_the_title($prevPost); 
            if ( has_post_thumbnail($prevPost->ID)){
                $prevthumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($prevPost->ID), 'epico_blog_navigation');
            }
        }
        if($nextPost){
            $nexttitle=get_the_title($nextPost);
            if ( has_post_thumbnail($nextPost->ID) ){
                $nextthumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($nextPost->ID), 'epico_blog_navigation');
            }
        }
        
    ?>

    <!-- nav box -->
        <div class="nav_box">
            <?php echo next_post_link('%link', '<div class="nextNav"><div class="bg" style="background-image:url('. esc_url($nextthumbnail[0]) .');background-color:#ddd;"></div><span class="postTitle" title="'. esc_attr__('Newer Posts', 'vitrine').'">'.$nexttitle.'</span></div>');?>
            <?php echo previous_post_link('%link', '<div class="prevNav"><div class="bg" style="background-image:url('. esc_url($prevthumbnail[0]) .');background-color:#ddd;"></div><span class="postTitle" title="'. esc_attr__('Older Posts', 'vitrine').'">'.$prevtitle.'</span></div>'); ?>
        </div>


    <div class="commentWrap" id="comment-text">
        <?php
            $num_comments = get_comments_number();?>
            
            <?php
            if ( $num_comments != 0 ) {  ?>
            <div class="commentsCount">        
            <?php if ( $num_comments < 10 ) {
                      echo "0". esc_html($num_comments) .'   '; //prints zero before <10 nums
                  }else{
                      echo esc_html($num_comments) .'   '; 
                  }
                
                  if ( $num_comments == 1 ) {//Comments text compatibility check
                      esc_html_e('comment', 'vitrine');
                  }else {
                      esc_html_e('comments', 'vitrine');
                  } ?>
             </div>
          <?php }
              comments_template('', true); ?>
    </div>
    <?php
    if($sidebar=='no-sidebar'){?>
         </div></div>
    <?php } ?>
