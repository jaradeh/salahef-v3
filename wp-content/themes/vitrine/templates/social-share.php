<?php
$fbshare = epico_opt('social_share_facebook');
$googleshare = epico_opt('social_share_google');
$emailshare = epico_opt('social_share_mail');
$twittershare = epico_opt('social_share_twitter');
$pinterestshare = epico_opt('social_share_pinterest');

    if( ! isset($portfolioLoop)) { 

	    // try getting featured image -  pinterest icon 
	    $featured_img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
	    if( ! $featured_img )
	    {
		    $featured_img = '';
	    }
	    else
	    {
		    $featured_img = $featured_img[0];
	    }

    } else {

        $featured_img = '';
    
    }
	
	$social_share_facebook =
						'<li class="socialLinkShortcode iconstyle facebook">
							<a href="http://www.facebook.com/sharer.php?u=' . urlencode(esc_url(get_permalink(get_the_ID()))) . '" title="' . esc_attr__("Share on Facebook!",'vitrine') .'">
								<span class="firstIcon icon icon-facebook"></span>
								<span class="SecoundIcon icon icon-facebook"></span>
							</a>
						</li> ';
	$social_share_twitter =
						'<li class="socialLinkShortcode iconstyle twitter">
							<a href="https://twitter.com/intent/tweet?original_referer=' . urlencode(esc_url(get_permalink(get_the_ID()))) . '&amp;source=tweetbutton&amp;text=' . esc_attr(urlencode(get_the_title())) . '&amp;url=' . esc_url(urlencode(get_permalink(get_the_ID()))) . '"
										title="' . esc_attr__("Share on Twitter!", 'vitrine') . '">
								<span class="firstIcon icon icon-twitter"></span>
								<span class="SecoundIcon icon icon-twitter"></span>
							</a>
						</li> ';
	$social_share_google =
						'<li class="socialLinkShortcode iconstyle google-plus">
							<a href="https://plus.google.com/share?url=' . urlencode(esc_url(get_permalink(get_the_ID()))) . '" title="' . esc_attr__("Share on Google+!",'vitrine') . '">
								<span class="firstIcon icon icon-google-plus"></span>
								<span class="SecoundIcon icon icon-google-plus"></span>
							</a>
						</li> ';
	$social_share_mail =
						'<li class="socialLinkShortcode iconstyle email">
							<a href="mailto:'.  '?subject=' . esc_html__('Check this ', 'vitrine') . get_the_permalink() .'" title="'.esc_attr__('Share by Mail!', 'vitrine') .'">
								<span class="firstIcon icon icon-envelope2"></span>
								<span class="SecoundIcon icon icon-envelope2"></span> 
							</a>
						</li>';
	$social_share_pinterest =
						'<li class="socialLinkShortcode iconstyle pinterest dddddd">
							<a href="http://pinterest.com/pin/create/button/?url=' . urlencode(esc_url(get_permalink(get_the_ID()))) . '&amp;media=' . esc_url($featured_img) . '&amp;description=' . esc_attr(urlencode(get_the_title())) . '" class="pin-it-button" count-layout="horizontal">
								<span class="firstIcon icon icon-pinterest"></span>
								<span class="SecoundIcon icon icon-pinterest"></span> 
							</a>
						</li>';
?>

	<ul class="social-icons dark"> 
	
        <!-- facebook Social share button -->
		<?php if ($fbshare == '1'){
			echo $social_share_facebook;
		} 
		?>
       <!-- google plus social share button -->
		<?php if ($googleshare == '1'){
			echo $social_share_google;
		}
		?>
        <!-- Mail To icon --> 
		<?php if ($emailshare == '1'){
			echo $social_share_mail ;
		} 
		?>
        <!-- twitter icon  --> 
		<?php if ($twittershare == '1'){
			echo $social_share_twitter;
		}
		?>
                 
        <!-- pinterest icon --> 
		<?php if ($pinterestshare == '1'){
			echo $social_share_pinterest;
		}
		?>
    </ul>

                