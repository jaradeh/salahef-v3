<?php 
        
    $termNames = get_the_term_list( $id , 'skills', '<span>#', '</span>  <span>#', ' </span>' ); // get the item skills
    $title_attributes = epico_get_meta('attribute-title');
    $value_attributes = epico_get_meta('attribute-value');
	
	//check social share is Enable or not
	if(get_post_meta( get_the_ID(), "social_share_inherit", true ) == '1')
	{
		$portfolio_social_share = get_post_meta( get_the_ID(), "portfolio-social-share", true );
	}
	else
	{
		$portfolio_social_share = epico_opt("social_share_display"); //theme settings;
	}
?>

<!-- portfolio tags  &  portfolio Socail share  -->
<?php if($portfolio_social_share == 1 || $termNames != '' ||   sizeof($title_attributes) > 1 ) { ?>

    <ul class="socailshare project-detail">

        <?php if($portfolio_social_share == 1 ) { ?>
            <li class="project portfolio_social_share">
                    <!-- portfolio Socail share -->
                    <?php	ep_social_share();	?>
            </li>
        <?php } ?>

        <?php if ( is_array($title_attributes) && count($title_attributes) > 0 ) { ?>
                
            <!-- Project Detail -->
            <?php foreach( $title_attributes as $key => $title ) { ?>
        
                <?php if (!(empty($title))) { ?>
            
                    <li class="project">
                        <span class="project-title">
                            <?php echo esc_attr($title) ?>: 
                        </span>
                        <span class="project-subtitle">
                            <?php echo esc_attr($value_attributes[$key]) ?>
                        </span>
                    </li>
            
               <?php } ?>
                        
            <?php } ?>
            
        <?php  }//end if is_array ?>

        <?php if($termNames != '') { ?>
	        <li class="project">
                <span class="project-title">
                    <?php  esc_html_e('Tags:', 'vitrine') ?> 
                </span>
                <span class="project-subtitle project-skill">
                    <?php echo $termNames; ?>
                </span>
	        </li>
            <?php
        } ?>


    </ul>

<?php } ?>

