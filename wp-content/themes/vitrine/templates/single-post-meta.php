<div class="post-meta">
    <div class="post-date-title clearfix">
        <h1 class="post-title"><?php the_title(); ?></h1>
        
        <span class="post-info">
            <span class="post-categories"><?php the_category(', '); ?></span>
            <span class="post-info-separator">/</span>
            <span class="post-date"><?php echo get_the_date(); ?></span>
            <span class="post-info-separator">/</span>
            <span class="post-author ep-icon icon-user" data-name="user"><?php the_author_posts_link(); ?></span>
        </span>
    
    </div>
</div>