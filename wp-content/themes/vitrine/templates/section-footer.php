<?php

//footer style
$style = epico_opt('footerStyle');

if ( $style == "0") {
    $style = "light";
} else {
    $style = "dark";
}

$headerType = epico_opt('header-type');
    
?>

<footer class="footer-bottom <?php echo esc_attr($style);?>">
    <div class="wrap">
        
        <!-- Footer Content   -->
        <div class="footer_content<?php if ( !(epico_opt('footer-copyright'))) { ?> nocopyRight<?php } ?> <?php if ( epico_opt('footerFullwidth') == "0") { ?>container<?php } ?>">
                
            <div class="footer_content_left">
                <?php if ( epico_opt('footer-copyright') ) { ?>
                    <div class="copyright_logo">
                        <!-- Footer CopyRight   -->
                        <div class="copyright">
                                <?php epico_eopt('footer-copyright'); ?>
                        </div>                   
                    </div>
                 <?php }

                    wp_nav_menu(array(
                        'container' =>'',
                        'menu_class' => 'clearfix simple-menu ' . $style,
                        'before'     => '',
                        'theme_location' => 'footer-nav',
                        'walker'     => new epico_Simple_Nav_Walker(),
                        'fallback_cb' => false , 
                        'after' => ''
                    ));
                ?>
            </div>
            
            <div class="footer_content_right">
                
                <!-- Footer Social Link  -->
                <ul class="social-icons">
                                
                    <?php
                        epico_socialLink('social_facebook_url', esc_html__('Facebook', 'vitrine'), 'icon-facebook' , 'facebook');//Facebook
                        epico_socialLink('social_twitter_url', esc_html__('Twitter', 'vitrine'), 'icon-twitter' , 'twitter'); // Twitter
                        epico_socialLink('social_vimeo_url', esc_html__('Vimeo', 'vitrine'), 'icon-vimeo' , 'vimeo'); // Vimeo
                        epico_socialLink('social_youtube_url', esc_html__('YouTube', 'vitrine'), 'icon-youtube' , 'youtube'); // Youtube
                        epico_socialLink('social_googleplus_url', esc_html__('Google+', 'vitrine'), 'icon-google-plus' , 'google-plus'); //Google+
                        epico_socialLink('social_dribbble_url', esc_html__('Dribbble', 'vitrine'), 'icon-dribbble', 'dribbble');//Dribbble
                        epico_socialLink('social_tumblr_url', esc_html__('Tumblr', 'vitrine'), 'icon-tumblr', 'tumblr');//Tumblr
                        epico_socialLink('social_linkedin_url', esc_html__('Linkedin', 'vitrine'), 'icon-linkedin', 'linkedin');//Linkedin
                        epico_socialLink('social_flickr_url', esc_html__('Flickr', 'vitrine'), 'icon-flickr', 'flickr');//flickr
                        epico_socialLink('social_github_url', esc_html__('Github', 'vitrine'), 'icon-github' , 'github5');//github
                        epico_socialLink('social_lastfm_url', esc_html__('Last.fm', 'vitrine'), 'icon-lastfm', 'lastfm');//lastfm
                        epico_socialLink('social_paypal_url', esc_html__('Paypal', 'vitrine'), 'icon-paypal', 'paypal');//paypal
                        if(epico_opt('rss_url') == '0'){
                        epico_socialLink('social_rss_url', esc_html__('RSS', 'vitrine'), 'icon-feed', 'feed');//rss
                        }
                        epico_socialLink('social_skype_url', esc_html__('Skype', 'vitrine'), 'icon-skype' , 'skype');//skype
                        epico_socialLink('social_wordpress_url', esc_html__('WordPress', 'vitrine'), 'icon-wordpress', 'wordpress');//wordpress
                        epico_socialLink('social_yahoo_url', esc_html__('Yahoo', 'vitrine'), 'icon-yahoo' , 'yahoo');//Yahoo
                        epico_socialLink('social_deviantart_url', esc_html__('DeviantArt Profile', 'vitrine'), 'icon-deviantart', 'deviantart');//Deviantart
                        epico_socialLink('social_steam_url', esc_html__('Steam', 'vitrine'), 'icon-steam', 'steam');//steam
                        epico_socialLink('social_reddit_url', esc_html__('Reddit', 'vitrine'), 'icon-reddit-alien' , 'reddit-alien');//reddit
                        epico_socialLink('social_stumbleupon_url', esc_html__('StumbleUpon', 'vitrine'), 'icon-stumbleupon' , 'stumbleupon');//stumbleupon
                        epico_socialLink('social_pinterest_url', esc_html__('Pinterest', 'vitrine'), 'icon-pinterest', 'pinterest');//Pinterest
                        epico_socialLink('social_xing_url', esc_html__('Xing', 'vitrine'), 'icon-xing', 'xing');//xing
                        epico_socialLink('social_blogger_url', esc_html__('Blogger', 'vitrine'), 'icon-blogger', 'blogger');//blogger
                        epico_socialLink('social_soundcloud_url', esc_html__('SoundCloud', 'vitrine'), 'icon-soundcloud', 'soundcloud');//soundcloud
                        epico_socialLink('social_delicious_url', esc_html__('Delicious', 'vitrine'), 'icon-delicious', 'delicious');//delicious
                        epico_socialLink('social_foursquare_url', esc_html__('Foursquare', 'vitrine'), 'icon-foursquare', 'foursquare');//foursquare
                        epico_socialLink('social_instagram_url', esc_html__('Instagram', 'vitrine'), 'icon-instagram', 'instagram');//instagram
                        epico_socialLink('social_behance_url', esc_html__('Behance', 'vitrine'), 'icon-behance', 'behance');//Behance
                        epico_socialLink('social_custom1_url', 'social_custom1_title', 'icon-custom1', 'custom1');//Custom 1
                        epico_socialLink('social_custom2_url', 'social_custom2_title', 'icon-custom2', 'custom2');//Custom 2
                    ?>

                </ul>
                    
            </div>
        </div>
    </div>
</footer>