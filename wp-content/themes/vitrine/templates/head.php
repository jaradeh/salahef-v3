<?php
$headerTextColor='';
if(function_exists('is_product_category') && is_product_category())
{
	$cat = get_queried_object();
	$catID = $cat->term_id;
	$header = false;
	
	$display_type_wc_setting = get_option('woocommerce_category_archive_display');
	$display_type_cat_setting = get_woocommerce_term_meta( $catID, 'display_type' );
	
	if( $display_type_cat_setting == 'both' || ($display_type_cat_setting == '' && $display_type_wc_setting == 'both'))
	{
		
		$header = true;
		$title =  woocommerce_page_title(false) ;
		$subTitle = strip_tags(term_description());
		$headerImageID = get_woocommerce_term_meta( $catID , "header-background-image", true );
		$headerBackgroundImage = $headerImageID ? wp_get_attachment_url( $headerImageID  ) : false;
		$headerTextColor 	= get_woocommerce_term_meta( $catID, 'header-text-color', true );

	}
}
else
{
	$headerBackgroundImage = epico_get_meta( "header-background-image", true );
	$headerTextColor = epico_get_meta("header-text-color", true );
	$header = epico_get_meta( "header-type-switch", true )== '0'? true : false;
	$titleType = epico_get_meta( "title-bar", true );
	
	if($titleType == '2')
	{
		$title    = epico_get_the_title();
		$subTitle = '';
	}
	elseif($titleType == '1')
	{
		$title = epico_get_meta( "title-text", true );;
		$subTitle  = epico_get_meta( "subtitle-text", true );
	}else
	{
		$title    = '';
		$subTitle = '';
	}
}

$style = '';
if(isset($headerBackgroundImage) && $headerBackgroundImage ){
	$style = 'style="background-image:url(' . esc_url($headerBackgroundImage) .')"';
}

$colorStyle='';
if($headerTextColor)
{
	$colorStyle = '<style>';
	$colorStyle .= '#header h1,#header .subtitle, #header ul li a,.page-breadcrumb .woocommerce-breadcrumb,.page-breadcrumb .woocommerce-breadcrumb a, .page-breadcrumb .woocommerce-breadcrumb span.delimiter { color:'. esc_attr($headerTextColor) . ';}';
	$colorStyle .= '#header ul li a:before { background-color:'. esc_attr($headerTextColor) . ';}';
	$colorStyle .= '</style>';
	echo $colorStyle;
}
$page_breadcrumb = epico_get_meta( "page_breadcrumb");

if ($header){ ?>

<div id="header" class="<?php echo ($style != '' ? 'hasbg':''); ?>" <?php echo $style; ?>>
	<div id="header-content">
    
    	<div class="page-breadcrumb">
			<?php woocommerce_breadcrumb(); ?>
    	</div>

		<?php if ( $title ) { ?>
			<h1><?php echo esc_html($title); ?></h1>
		<?php }?>

		<?php if ( $subTitle ) { ?>
			<span class="subtitle"><?php echo esc_html($subTitle) ; ?></span>
		<?php }
		
		//show categories and subcategories in shop & category page after shop-filter - ( in both display mode to show category and products)
		if (( is_shop() && get_option('woocommerce_shop_page_display') == 'both')  ||  is_product_category() ){
			
			echo '<ul class="cat-disply">';
			epico_woocommerce_product_subcategories();
			echo '</ul>';
			
		}
		?>
	</div>
</div>
<?php
} ?>



