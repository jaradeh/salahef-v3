<?php 

/* This template file displays the thumbnail of a post on the homepage. */

$portolio_type = isset($template_args['$portolio_type']) ? $template_args['$portolio_type'] : null ;
$portfolioLoop = isset($template_args['$portfolioLoop']) ? $template_args['$portfolioLoop'] : null ;
$count = isset($template_args['$count']) ? $template_args['$count'] : null ;
$portfolio_masonry = isset($template_args['$portfolio_masonry']) ? $template_args['$portfolio_masonry'] : null ;
$pDajax  = isset($template_args['$pDajax ']) ? $template_args['$pDajax '] : null ;

// Get post 
global $post;

/* Get options for thumbnail */
if($pDajax == true)
{
    $pDTargetCheck = 'portfolio_detail_inner';
}
else
{
    $pDTargetCheck = (get_post_meta(get_the_ID(), 'portfolio-detail-style', true)) ;
}

$pFeaturedSize  = (get_post_meta(get_the_ID(), 'portfolio-featured-size', true)) ? strtolower(preg_replace('/\s+/', '-', get_post_meta(get_the_ID(), 'portfolio-featured-size', true))) : 'square'; // Featured Size of image 
$terms    = get_the_terms( get_the_ID(), 'skills' ); // Filter terms
$background = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
$hasimages      = (has_post_thumbnail()) ? 'hasimages' : 'noimages'; // Image class for sizing
$postFormat =  get_post_format();
$pLink  = (get_post_meta(get_the_ID(), 'link-url', true)) ; 

// This Code Use For Show Iamge Place Hlder For Portfolio  When Import DummyData! :)
$thumbchk = get_post_meta($post->ID,'_thumbnail_id',false);
if(empty($thumbchk)){
    // if $thumbchk[0] is Empty 
} else {
    $thumbchk = wp_get_attachment_image_src($thumbchk[0], $pFeaturedSize, false);  // URL of Featured first slide
}

if(empty($thumbchk)){
    $hasimages = 'noimages'; 
} 


if ( $postFormat == "link")
{
    $isLink  =  true;
} else {
    $isLink  =  false;
}

if($portfolio_masonry == 'masonry')
{
    $pFeaturedSize = 'square';
}

// Add additional post classes 
$postclasses = array(
    $pFeaturedSize,
    'isotope-item',
    $hasimages
);

// Add terms to post classes for filtering 
if ($terms) : 
    foreach ($terms as $term) : 
        array_push($postclasses,'term-'.$term->term_id.' ');
    endforeach; 
endif;  


$termNames = get_the_term_list( $post->ID , 'skills', '<span>', '</span>, <span> ', ' </span>' ); // get the item skills
    
?>

<!-- The Post -->
<div <?php post_class($postclasses); ?> >
    <div class="postphoto" >
        <?php echo epico_thumbnail_post_slideshow($pFeaturedSize, $post->ID ,$post->post_name,$pDTargetCheck,$terms, $isLink , $pLink, $portfolio_masonry,$portolio_type); ?>
    </div>
    
    <?php if ( $portolio_type == "portfolio_text" ) { ?>
    
        <!-- meta data for portfolio text style -->
        <div class="portfolio_text_meta">
            <div class="right_meta">
                <div class="title">
                    <?php echo get_the_title($post->ID); ?>
                </div>
                <div class="category">
                    <?php echo $termNames; ?>
                </div>
            </div>
        </div>
        
    <?php } ?>
    
</div>


<!-- End The Post -->