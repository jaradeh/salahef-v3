    
    <?php if ((function_exists('is_woocommerce') && is_woocommerce()) ||  is_page_template('main-page.php') || is_page() || is_home() || is_404() || is_search() || is_archive() || is_singular( 'portfolio' ) || is_single() ) {  ?> 
				</div>
			</div>
		</div>
    <?php } else { ?>
                </div> <!-- close container div - use in whislist, dokan and ...  -->
            </div>
        </div>
    </div>

    <?php } ?>

    <?php
    if(!epico_is_shop_ajax_request()) {
        get_template_part('templates/section', 'footer');
    }
    ?>

    <!-- end of wrap element -->
    </div>
    <?php if(function_exists("is_woocommerce") && epico_opt('woocommerce-notices') != '0')
    {
    	?>
	    <div id="ep_wc_notices">
	    	<div class="wc-notice-content"></div>
	    </div>
    	<?php
    }
    if(!epico_is_shop_ajax_request()) {
        wp_footer();
    }
    
    ?>
    </body>
</html>