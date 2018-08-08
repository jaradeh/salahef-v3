<?php
    $checkTitle = get_post_meta( get_the_ID(), "title-bar", true );
    $title = get_post_meta( get_the_ID(), "title-text", true );
    $subTitle = get_post_meta( get_the_ID() , "subtitle-text", true );
?>

<?php if ( ( $checkTitle == 1 ) && ! empty( $title )) { ?>
    
    <!-- echo custom title and subtitle  -->
    <div class="title">
        
        <?php  echo esc_attr($title);?>
            
        <?php if ( ! empty( $subTitle ) ) { ?>
            <div class="subtitle"><?php echo esc_attr($subTitle) ; ?></div>
        <?php } ?>
        
    </div>
    
<?php } else if ( $checkTitle ==  2 ) { ?> 
    
    <!-- echo portfolio post name as title -->
    <div class="title">     
        <?php the_title(); ?>
    </div>
    
<?php } ?>