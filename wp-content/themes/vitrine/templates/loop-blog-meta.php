<div class="post-meta">
    <span class="post-categories"><?php the_category(', '); ?></span>
    <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <div class="post-info-container clearfix">
        <div class="post-info">
            <span class="post-date"><?php echo get_the_date(); ?></span>
            <span class="post-info-separator">/</span>
            <span class="post-author ep-icon icon-user" data-name="user"><?php the_author_posts_link(); ?></span>
            <span class="post-info-separator">/</span>
            <span class="post-comments ep-icon icon-bubble" data-name="bubble"><?php if(comments_open()) comments_popup_link( 'No comments', '1', '%', 'comments-link', ''); ?></span>
        </div>
    </div>
</div>
<?php 
    // blog Post text excerpt
    the_excerpt();
?>
    <div class="redmore_line"></div>
    
    <!-- post link button -->
    <a class="readmore_button ep_button style1 transparent left" href="<?php the_permalink(); ?>" title="">
        <span class="txt" data-hover="<?php  esc_attr_e('Read More', 'vitrine') ?>">
            <?php  esc_html_e('Read More', 'vitrine') ?> 
        </span>
    </a>  