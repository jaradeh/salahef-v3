<?php

/*-----------------------------------------------------------------------------------*/
/*	Functions
/*-----------------------------------------------------------------------------------*/

    function epico_comment_fields($fields) {

        $commenter = wp_get_current_commenter();

        $fields['author'] = "<div class=\"input-text\"><input name=\"author\" value=\"" . esc_attr($commenter['comment_author']) . "\" type=\"text\" tabindex=\"1\"><span class=\"label\">" . esc_html__('Your Name', 'vitrine') . "</span><span class=\"graylabel\">" . esc_html__('Your Name', 'vitrine') . "</span></div>";

        $fields['email'] = "<div class=\"input-text\"><input name=\"email\" value=\"" . esc_attr($commenter['comment_author_email']). "\" type=\"text\" tabindex=\"2\"><span class=\"label\">" . esc_html__('Email', 'vitrine') . "</span><span class=\"graylabel\">" . esc_html__('Email', 'vitrine') . "</span></div>";

        $fields['url'] = "<div  class=\"input-text\"><input name=\"url\" value=\"" . esc_attr($commenter['comment_author_url']). "\" type=\"text\" tabindex=\"3\"><span class=\"label\">" . esc_html__('WEBSITE', 'vitrine') . "</span><span class=\"graylabel\">" . esc_html__('WEBSITE', 'vitrine') . "</span></div>";

        return $fields;
    }
    add_filter('comment_form_default_fields','epico_comment_fields');

    function epico_comment_submit($submit_button) {
        $submit_button = '<div class="button button-large right" title=""><p class="hoverText" data-hover="'. esc_attr__('Submit','vitrine'). ' "></p><p><input name="submit" type="submit" value="'. esc_attr__('Submit','vitrine') .'"></p></div>';
        return $submit_button;
    }
    add_filter('comment_form_submit_button','epico_comment_submit');

    function epico_comment_form_before()
    {
        echo '<div class="form-fields clearfix">';
    }

    function epico_comment_form_after()
    {
        echo '</div>';
    }

    add_action('comment_form_before_fields', 'epico_comment_form_before');
    add_action('comment_form_after_fields', 'epico_comment_form_after');

    //Comment styling

    function epico_theme_comment($comment, $args, $depth) {

        $isByAuthor = false;

        if($comment->comment_author_email == get_the_author_meta('email')) {
            $isByAuthor = true;
        }

        $GLOBALS['comment'] = $comment; ?>

        <li>
            <div id="comment-<?php comment_ID() ?>" <?php comment_class('clearfix'); ?> data-id="<?php comment_ID(); ?>">
                <div class="comment-image">
                    <?php echo get_avatar($comment,$size='64'); ?>
                </div>
                <div class="comment-content">
                    <div class="comment-meta">
                        <?php printf(__('<cite>%s</cite>', 'vitrine'), get_comment_author_link()) ?>
                        <?php if($isByAuthor) { ?><span class="author-tag"><?php esc_html_e('(Author)','vitrine') ?></span><?php } ?>
                        <a class="comment-date" href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s', 'vitrine'), get_comment_date( 'M j, Y,    ' ),  get_comment_time('G:i')) ?></a>
                        <?php edit_comment_link(__('(Edit)', 'vitrine'),'  ','');?>
                    </div>
                    <div class="comment-text">
                        <?php 
                            comment_text();
                            comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
                        ?>
                    </div>
                </div>
                <div class="line"></div>
            </div>

    <?php
    }

    function epico_theme_list_pings($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment; ?>
        <li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
    <?php }


    // Do not delete these lines
    if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
        die ('Please do not load this page directly. Thanks!');

    if ( post_password_required() ) { ?>
        <p class="nocomments"><?php esc_html_e('This post is password protected. Please enter password to view comments.', 'vitrine') ?></p>
    <?php
        return;
    }

/*-----------------------------------------------------------------------------------*/
/*	Display the comments + Pings
/*-----------------------------------------------------------------------------------*/

if ( have_comments() ) { // if there are comments ?>
    <div class="comments-wrap">


	<?php if (!empty($comments_by_type['pingback'])) { ?>

        <h4 id="pings"><?php esc_html_e('Pings for this post', 'vitrine') ?></h4>
	   
	    <ol class="ping_list">
		
             <?php wp_list_comments('type=pings&callback=epico_theme_list_pings'); ?>
	    </ol>

	<?php } ?> 

    <?php if (!empty($comments_by_type['comment'])) { ?>

	    <ul class="comments-list">
		 
             <?php wp_list_comments('type=comment&avatar_size=64&callback=epico_theme_comment'); ?>
	    </ul>

    <?php } else {  ?>

			<ul class="comments-list">
                <?php wp_list_comments('type=comment&avatar_size=64&callback=epico_theme_comment'); ?>
            </ul>

    <?php } ?>


        <div class="navigation">
            <div class="alignleft"><?php previous_comments_link(); ?></div>
            <div class="alignright"><?php next_comments_link(); ?></div>
        </div>

    </div>
<?php

    //Deal with closed comments
    if (!comments_open()) { // if the post has comments but comments are now closed ?>

            <?php if (is_single()) { ?>
                <p class="nocomments"><?php esc_html_e('Comments are now closed.', 'vitrine'); ?></p>
            <?php
            } else{ ?>
                <p class="nocomments"><?php esc_html_e('Comments are now closed for this article.', 'vitrine'); ?></p>
            <?php } ?>

    <?php }

}
else //There are no comments
{
    //If there are no comments so far and comments are open
    if(comments_open())
    {
        if (is_single()) { ?>
            <p class="nocomments"><?php esc_html_e('No comments so far.', 'vitrine'); ?></p>
        <?php
        } else{ ?>
            <p class="nocomments"><?php esc_html_e('There are no comments for this article.', 'vitrine'); ?></p>
        <?php
        }
    }
    else
    {
        if (is_single()) { ?>
            <p class="nocomments"><?php esc_html_e('Comments are closed.', 'vitrine'); ?></p>
        <?php
        } else{ ?>
            <p class="nocomments"><?php esc_html_e('Comments are closed for this article.', 'vitrine'); ?></p>
        <?php
        }
    }

} // if there are comments

//Comment Form
if ( !comments_open() ) return;
?>
<div id="respond-wrap">
<?php comment_form(array( 
    'comment_notes_before' => '<p>'. esc_html__('Your email address will not be published. Website Field Is Optional.', 'vitrine') .'</p>',
    'comment_field' => "<div class=\"input-textarea\"><textarea rows=\"10\" cols=\"58\" name=\"comment\" tabindex=\"4\"></textarea><span class=\"label\">" . esc_html__('COMMENT', 'vitrine') . "</span><span class=\"graylabel\">" . esc_html__('COMMENT', 'vitrine') . "</span></div>"
)); ?>
</div>
