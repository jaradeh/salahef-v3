<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version      3.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! comments_open() ) {
	return;
}

# add 'row' that wrap feilds

add_action('comment_form_before_fields', 'epico_comment_before_fields');
add_action('comment_form_after_fields', 'epico_comment_after_fields');

add_action('comment_form_logged_in', 'epico_comment_before_fields');
add_action('comment_form_logged_in_after', 'epico_comment_after_fields');

function epico_wc_comment_submit($submit_button) {
    $submit_button = '<div class="button button-large right" title=""><p class="hoverText" data-hover="'.esc_attr__('Submit','vitrine').' "></p><p>' . $submit_button .'</p></div>';
    return $submit_button;
}
add_filter('comment_form_submit_button','epico_wc_comment_submit');

?>
<div class="review-container">
	<div id="reviews">
		<div id="comments">
			<h2><?php
				if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) )
					printf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'vitrine' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
				else
					_e( 'Reviews', 'vitrine' );
			?></h2>

			<?php if ( have_comments() ) : ?>

				<ol class="commentlist">
					<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
				</ol>

				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
					echo '<nav class="woocommerce-pagination">';
					paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
						'prev_text' => '&larr;',
						'next_text' => '&rarr;',
						'type'      => 'list',
					) ) );
					echo '</nav>';
				endif; ?>

			<?php else : ?>

				<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'vitrine' ); ?></p>

			<?php endif; ?>
		</div>

		<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

			<div id="review_form_wrapper">
				<div id="review_form">
					<?php
		                
						$commenter = wp_get_current_commenter();

						$comment_form = array(
							'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'vitrine' ) : esc_html__( 'Be the first to review', 'vitrine' ) . ' &ldquo;' . get_the_title() . '&rdquo;',
							'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'vitrine' ),
							'title_reply_before'   => '<span id="reply-title" class="comment-reply-title">',
							'title_reply_after'    => '</span>',
							'fields'               => array(
		                            
		                        // Edit Name feild 
								'author' => '<div class="comment-form-author">' .
													'<input id="author" name="author" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" />' .
		                                            '<span class="label">'. esc_html__( 'Name', 'vitrine' ) . '</span>'.
		                                            '<span class="graylabel"> '. esc_html__( 'Name', 'vitrine' ) . '</span>'.
		                                        '</div>',
		                                    
		                            // Edit Email feild 
								'email'  => '<div class="form-group comment-form-email">' .
													'<input id="email" name="email" type="text" class="form-control" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" />' .
		                                            '<span class="label">'. esc_html__( 'Email', 'vitrine' ) . '</span>'.
		                                            '<span class="graylabel"> '. esc_html__( 'Email', 'vitrine' ) . '</span>'.
		                                        '</div>',  
							),
							'label_submit'  => esc_attr__( 'Submit', 'vitrine' ),
							'logged_in_as'  => '',
							'comment_field' => '',								
						);

						if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
		                    
							$comment_form['comment_field'] = '
		                        
		                        <p class="comment-form-rating">
		                            <label for="rating">' . esc_html__( 'Your Rating', 'vitrine' ) .'</label>
		                            <select name="rating" id="rating">
									    <option value="">' . esc_html__( 'Rate&hellip;', 'vitrine' ) . '</option>
									    <option value="5">' . esc_html__( 'Perfect', 'vitrine' ) . '</option>
									    <option value="4">' . esc_html__( 'Good', 'vitrine' ) . '</option>
									    <option value="3">' . esc_html__( 'Average', 'vitrine' ) . '</option>
									    <option value="2">' . esc_html__( 'Not that bad', 'vitrine' ) . '</option>
									    <option value="1">' . esc_html__( 'Very Poor', 'vitrine' ) . '</option>
								    </select>
		                                
		                        </p>';
						}

						if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
                            $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( 'You must be <a href="%s">logged in</a> to post a review.', esc_url( $account_page_url ) ) . '</p>';
						}
		                        
		                // Edit Your review textarea feild 
		                $comment_form['comment_field'] .= '<div class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" class="form-control autogrow"></textarea>
		                                                        <span class="label">'. esc_html__( 'Your Review', 'vitrine' ) . '</span>
		                                                        <span class="graylabel">'. esc_html__( 'Your Review', 'vitrine' ) . '</span>
		                                                    </div>';

						comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
		                    
					?>
				</div>
			</div>

		<?php else : ?>

			<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'vitrine' ); ?></p>

		<?php endif; ?>

		<div class="clear"></div>
	</div>
</div>
