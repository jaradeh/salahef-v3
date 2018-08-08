<?php
get_header();

//Get the sidebar option
$sidebarPos = epico_opt('sidebar-position');
$sidebar    = epico_get_meta('sidebar');

if(epico_get_meta('snap-to-scroll') == 1)
{
	$sidebar = 'no-sidebar';
}		

?>
<!-- Page Content-->
<div class="wrap" id="pageHeight">

<?php  if(function_exists('is_checkout') && is_checkout() ) {   ?>
	<div class="container">
<?php } ?>

		<?php
		$page_type = epico_get_meta( "page-type-switch" );
		
		if ( $page_type == "custom-section" ) {
			
			 if($sidebar == 'no-sidebar' ) {
 
				$wpb_vc_js_status = epico_get_meta( "_wpb_vc_js_status" );
				if ( $wpb_vc_js_status == 'false' || $wpb_vc_js_status == '' ) { ?>
	
					<!-- container div Add when Classic Editor is Enable - Visual Composer not Enable -->
					<div class="container">
	
				<?php
				} ?>

				<?php  get_template_part('templates/loop-page'); ?>

				<?php  if ( $wpb_vc_js_status == 'false' || $wpb_vc_js_status == '') {  ?>
					<!-- container div Add when Classic Editor is Enable -->
					</div>
				<?php } ?>

			<?php
			} else {

				$contentClass = 'span9';
				$sidebarContainerClass = 'span3 page-sidebar-container';
				if(1 == $sidebarPos)
					$contentClass .= ' float-right';
			?>

			<div class="pageHasSidebar container">
				<div class="row">
					<div class="<?php echo esc_attr($contentClass); ?>"><?php get_template_part('templates/loop-page'); ?></div>
					<div class="<?php echo esc_attr($sidebarContainerClass); ?>"><?php epico_get_sidebar($sidebar); ?></div>
				</div>
			</div>

			<?php }

		}  else if ( $page_type == "blog-section" ){ ?>
	
				<?php get_template_part('templates/loop-page'); ?>
	
		<?php } else { ?>

			<div class="container">
				<?php get_template_part('templates/loop-page'); ?>
			</div>

		<?php }  ?>

	<?php  if(function_exists('is_checkout') && is_checkout() ) {   ?>
		</div>
	<?php } ?>

</div>

<!-- Page Content End -->
<?php 

$footerMap = epico_get_meta("footer-map");
if ($footerMap == 1 && epico_get_meta("snap-to-scroll") != 1) {
	//Footer Map
	get_template_part('templates/section', 'location');
}


if(epico_get_meta("snap-to-scroll") != 1)
{
	$widgetized_footer = epico_get_meta("footer-widget-area");

	if ($widgetized_footer == "inherit") {

		$widgetized_footer_theme_setting = epico_opt('footer-widget-area');
		if($widgetized_footer_theme_setting)
		{
			//Footer Widgetized Area
			get_template_part('templates/section', 'widgetized_footer');
		}
		
	}
	elseif($widgetized_footer == "enable")
	{
		//Footer Widgetized Area
		get_template_part('templates/section', 'widgetized_footer');
	}
} 
?>
<?php get_footer(); ?>