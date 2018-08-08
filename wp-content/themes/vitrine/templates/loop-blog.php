<?php 
    $attachment_id = 6;
    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
    
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
<div class="blog_loop_item">

        <div  <?php post_class('togglePost'); ?> id="post_<?php the_ID(); ?>">
            <!-- Desktop Blog -->
            <div class="desktopBlog">
                <div class="container">
            
                    <?php if (get_post_meta( get_the_ID() ,'media', true)!='quote') { ?>
                        <div class="blogAccordion accordionClosed" data-value="0" <?php if (!empty($image[0])) { ?>  style="background-image: url('<?php  echo esc_url($image[0]); ?> ')" <?php } ?>>
                     <?php } else { ?>
                        <div class="blogAccordion accordionClosed quoteItem"  data-value="0"  <?php if (!empty($image[0])) { ?>  style="background-image: url('<?php  echo esc_url($image[0]); ?> ')" <?php } ?>>
                    <?php } ?>

                        <div class="accordion_box2">
                            <div class="accordion_title" >

                                <?php if (get_post_meta( get_the_ID() ,'media', true)!='quote') { ?>
                                    <!-- blog Post date - day -->
                                    <span class="day"><?php echo ( get_the_time('d') ); ?></span>
                                <?php } else { ?>
                                     <!-- Quote blog icon -->
                                    <span class="day icon ep-icon icon-quotes-right"></span>
                                <?php } ?>

                            </div>
                        </div>

                    
                        <?php if (get_post_meta( get_the_ID() ,'media', true)!='quote') { ?>

                            <div class="accordion_box10">
                                <!-- blog Post date -->
                                <div class="leftBorder">

                                    <!-- Post title  -->
                                    <div class="blogTitle">
                                        <?php the_title(); ?>
                                    </div>
                            
                                    <div class="monthYear">
                                        <span class="month"><?php echo ( get_the_time('M') ); ?></span>
                                        <span class="year"><?php echo( get_the_time('Y') ); ?></span>
                                    </div>
                                                                
                                </div>
                            </div>


                        <?php } else { ?>

                            <?php
 
                                //If it is Quote
                                $quote_content = get_post_meta( get_the_ID(), "quote_content", true );
                                $quote_author = get_post_meta( get_the_ID(), "quote_author", true ); 
   
                            ?>

                            <div class="accordion_box10">
                                <!-- blog Post Quote Icon -->
                                <div class="leftBorder">

                                    <!-- Post title  -->
                                    <div class="blogTitle">
                                        <?php echo esc_attr($quote_author); ?>
                                    </div>
                            
                                </div>
                            </div>

                        <?php } ?>

                        <div class="accordion_content">
                            <!-- blog Post text -->
                            <p>
                                
                                <?php if (get_post_meta( get_the_ID() ,'media', true)!='quote') { ?>
                                
                                    <!-- Post Content  -->
                                    <?php $excerpt = get_the_excerpt();
                                        echo $excerpt;
                                    ?>
                               
                                <?php } else { ?>

                                    <!-- Post Content  -->
                                    <div class="quote_content">
                                        <span class="icon ep-icon icon-quotes-left"></span>
                                        <?php echo esc_attr($quote_content); ?>
                                    </div>

                                <?php } ?>

                                <a class="moretag hidden-phone" href="<?php the_permalink(get_the_ID()) ?>"><?php esc_html_e('more details', 'vitrine') ?> </a>
                            </p>
                        
                            <div class="readmoreLine"></div>
                                <a class="moretag visible-phone" href="<?php the_permalink(get_the_ID()) ?>"><?php esc_html_e('more details', 'vitrine') ?> </a>

                            <?php if( $socialshare == 1 ) { ?>
                                <div class="blog_social_share hidden-phone">
                                    <!-- social share buttons -->
                                   <?php ep_social_share(); ?>
                                </div>
                            <?php } ?>

                        </div>
                    
                        <!-- gray Overlay -->
                        <div class="grayOverlay"></div>
                    
                        <div class="clearfix"></div>

                        <!-- Toggle Opening Handel  -->
                        <div class="plus span12"></div>
                        <div class="minus span12"></div>

                    </div>
                
                </div>
            </div>
        </div>
</div>