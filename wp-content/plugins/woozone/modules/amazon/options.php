<?php
/**
 * Dummy module return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		0.1 - in development mode
 */

include_once( 'options-func.php');
global $WooZone;

echo json_encode(array(
	$tryed_module['db_alias'] => array(
		
		/* define the form_sizes  box */
		'amazon' => array(
			'title' => 'Amazon settings',
			'icon' => '{plugin_folder_uri}images/amazon.png',
			'size' => 'grid_4', // grid_1|grid_2|grid_3|grid_4
			'header' => true, // true|false
			'toggler' => false, // true|false
			'buttons' => true, // true|false
			'style' => 'panel', // panel|panel-widget
			
				// tabs
				'tabs'	=> array(
					'__tab1'	=> array(__('Amazon SETUP', $WooZone->localizationName), 'protocol, country, AccessKeyID, SecretAccessKey, AffiliateId, main_aff_id, buttons, help_required_fields, help_available_countries, amazon_requests_rate'),
					'__tab2'	=> array(__('Plugin SETUP', $WooZone->localizationName), 'disable_amazon_checkout, gdpr_rules_is_activated, products_force_delete, onsite_cart, cross_selling, cross_selling_nbproducts, cross_selling_choose_variation, checkout_type, checkout_email, checkout_email_mandatory, export_checkout_emails, 90day_cookie, remove_gallery, remove_featured_image_from_gallery, show_short_description, redirect_time, show_review_tab, redirect_checkout_msg, product_buy_is_amazon_url, product_url_short, frontend_show_free_shipping, frontend_show_coupon_text, charset, services_used_forip, product_buy_text, remote_amazon_images, images_sizes_allowed, productinpost_additional_images, productinpost_extra_css, product_countries, product_countries_main_position, product_countries_maincart, product_countries_countryflags, product_buy_button_open_in, asof_font_size, delete_attachments_at_delete_post, cache_remote_images, product_offerlistingid_missing_external, product_offerlistingid_missing_delete'),
					'__tab3'	=> array(__('Import SETUP', $WooZone->localizationName), 'price_setup, merchant_setup, product_variation, import_price_zero_products, default_import, import_type, ratio_prod_validate, item_attribute, selected_attributes, attr_title_normalize, cron_number_of_images, number_of_images, rename_image, spin_at_import, spin_max_replacements, create_only_parent_category, variation_force_parent, import_product_offerlistingid_missing, import_product_variation_offerlistingid_missing'),
					'__tab4'	=> array(__('BUG Fixes', $WooZone->localizationName), ''),
					'__tab5'	=> array(__('DEBUG', $WooZone->localizationName), 'debug_bar_activate, debug_ip'),
					'__tab6'	=> array(__('String Translation', $WooZone->localizationName), 'string_trans'),
				),
			
			// create the box elements array
			'elements' => array(

				'_badges_box' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Badges / Flags',
					'html' => WooZone_optfunc_badges_box( '__tab2' )
				),

				'disable_amazon_checkout' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Disable amazon checkout?',
					'desc' => 'Choose Yes if you want to disable the redirect fuction to the amazon checkout.<br /><div style="color: red;">You need to take care of the checkout and shipment process and provide another way of making commisions though amazon, by implementing a custom solution.<br />
Clients can still add the products from amazon into your cart on your website, but you need to process their orders and then order yourself the products to amazon manually (using a credit card) and then make yourself the shippments to your clients.<br />
Basically, your amazon products will be just like regular woocommerce products which you can re-sell.</div>',
					'options' => array(
						'no' => 'NO',
						'yes' => 'YES'
					)
				),

				'gdpr_rules_is_activated' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Activate GDPR Compliance?',
					'desc' => 'On 25 May 2018, EU’s General Data Protection Regulation (GDPR) will come into force. <div style="color: red; font-weight: bold;">You need to set this option on "NO" if you know that your website needs to be compliant with GDPR rules.</div>',
					'options' => array(
						'no' => 'NO',
						'yes' => 'YES'
					)
				),

				'products_force_delete' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Product : Delete | Move to Trash',
					'desc' => '<strong>Choose YES</strong> if you want to actually <span style="color: red;">remove product (or a variation)</span> when: a) bug fix "Delete all products with price zero", b) synchronization process doesn\'t find a product or a variation. <br />If you <strong>choose NO</strong>, then it will <span style="color: red;">only be moved to trash</span> - depending on the setting for <strong>Put amazon products in trash when syncing after</strong> from Bug FIXES tab.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),

				'services_used_forip' => array(
					'type' => 'select',
					'std' => 'www.geoplugin.net',
					'size' => 'large',
					'force_width' => '380',
					'title' => 'External server country detection or use local:',
					'desc' => 'We use an external server for detecting client country per IP address or you can try local IP detection. ( www.telize.com was shut down on November 15th, 2015 || api.hostip.info not working anymore )',
					'options' => array(
						'local_csv'                 => 'Local IP detection (plugin local csv file with IP range lists)',
						//'api.hostip.info'           => 'api.hostip.info',
						'www.geoplugin.net' 		=> 'www.geoplugin.net',
						//'www.telize.com'			=> 'www.telize.com',
						'ipinfo.io' 				=> 'ipinfo.io',
					)
				),
				
				'charset' 	=> array(
					'type' 		=> 'text',
					'std' 		=> '',
					'size' 		=> 'large',
					'force_width'=> '400',
					'title' 	=> __('Server Charset:', $WooZone->localizationName),
					'desc' 		=> __('Server Charset (used by php-query class)', $WooZone->localizationName)
				),

				'product_buy_is_amazon_url' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Show Amazon Url as Buy Url',
					'desc' => 'If you choose YES then the product buy url will be the original amazon product url (the On-site Cart option must be set to "No" also in order for this to work!).',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),

				'product_url_short' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Get Product Short Url',
					'desc' => 'If you choose YES then we\'ll generate and use a product short url (using bitly api) when the product details page product on frontend is accessed. <br/><span style="color: red;">In order for this to work, you need to have option "Show Amazon Url as Buy Url" set to YES, and "On-site Cart" option must be set to "No" (so it works when you use external woocommerce products) and you also must authorize bitly account in module (bottom AUTH section in the bitly module).</span>',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),

				/*'frontend_show_free_shipping' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Show Free Shipping',
					'desc' => 'Show Free Shipping text on frontend.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),*/
				'frontend_show_coupon_text' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Show Coupon',
					'desc' => 'Show Coupon text on frontend.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				'onsite_cart' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'On-site Cart',
					'desc' => 'This option will allow your customers to add multiple Amazon Products into Cart and checkout trought Amazon\'s system with all at once.<br/><span style="color: red;">If you set this option to "No" all the simple/variable woocommerce products will set as <strong>external</strong></span>',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				/*'checkout_type' => array(
					'type' => 'select',
					'std' => '_self',
					'size' => 'large',
					'force_width' => '200',
					'title' => 'Checkout type:',
					'desc' => 'This option will allow you to setup how the Amazon Checkout process will happen. If you wish to open the amazon products into a new tab, or in the same tab.',
					'options' => array(
						'_self' => 'Self - into same tab',
						'_blank' => 'Blank - open new tab'
					)
				),*/
				
				'checkout_email' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Checkout E-mail:',
					'desc' => 'Ask the user e-mail address before the checkout process (redirect to amazon) happens and store it for later export in CSV format.',
					'options' => array(
						'no' => 'NO',
						'yes' => 'YES'
					)
				),
				
				'checkout_email_mandatory' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Checkout E-mail Mandatory:',
					'desc' => 'Make "Checkout E-mail" option above mandatory in order to checkout.',
					'options' => array(
						'no' => 'NO',
						'yes' => 'YES'
					)
				),
				
				'export_checkout_emails' => array(
					'type' => 'html',
					'html' => '<div class="panel-body WooZone-panel-body WooZone-form-row  __tab2 " style="display: block;">
						<label for="export_checkout_emails" class="WooZone-form-label">Export Checkout Emails:</label>
						<div class="WooZone-form-item">
							<a href="'. ( admin_url( 'admin.php?page=' . WooZone()->alias ) ) .'&do=export_emails#!/amazon" id="export_checkout_emails" class="WooZone-form-button-small WooZone-form-button-info">Export Emails</a>
							<span class="WooZone-form-note">Export as CSV checkout emails sent by customers.</span>
						</div>
					</div>',
				),
				
				'item_attribute' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Import Attributes',
					'desc' => 'This option will allow to import or not the product item attributes.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				'selected_attributes' 	=> array(
					'type' 		=> 'multiselect_left2right',
					'std' 		=> array(),
					'size' 		=> 'large',
					'rows_visible'	=> 18,
					'force_width'=> '300',
					'title' 	=> __('Select attributes', $WooZone->localizationName),
					'desc' 		=> __('Choose what attributes to be added on import process.', $WooZone->localizationName),
					'info'		=> array(
						'left' => 'All Amazon Attributes list',
						'right' => 'Your chosen items from list'
					),
					'options' 	=> WooZone_attributesList()
				),
				
				'attr_title_normalize' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Beautify attribute title',
					'desc' => 'separate attribute title words by space',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				'90day_cookie' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => '90 days cookies',
					'desc' => 'This option will activate the 90 days cookies feature',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				'price_setup' => array(
					'type' => 'select',
					'std' => 'only_amazon',
					'size' => 'large',
					'force_width' => '290',
					'title' => 'Prices setup',
					'desc' => 'Get product offer price from Amazon or other Amazon sellers.',
					'options' => array(
						'only_amazon' => 'Only Amazon',
						'amazon_or_sellers' => 'Amazon OR other sellers (get lowest price)'
					)
				),
				
				'merchant_setup' => array(
					'type' => 'select',
					'std' => 'amazon_or_sellers',
					'size' => 'large',
					'force_width' => '290',
					'title' => 'Import product from merchant',
					'desc' => 'Get products: A. only from Amazon or B. from (Amazon and other sellers).<br /><div style="color: red;">ATTENTION: If you choose "Only Amazon" then only product which have Amazon among their sellers will be imported!</div>',
					'options' => array(
						'only_amazon' => 'Only Amazon',
						'amazon_or_sellers' => 'Amazon and other sellers'
					)
				),
				
				'import_price_zero_products' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Import products with price 0',
					'desc' => 'Choose Yes if you want to import products with price 0',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),

				'product_variation' => array(
					'type' => 'select',
					'std' => 'yes_5',
					'size' => 'large',
					'force_width' => '160',
					'title' => 'Variation',
					'desc' => 'Get product variations. Be carefull about <code>Yes All variations</code> one product can have a lot of variation, execution time is dramatically increased!',
					'options' => WooZone_variation_number(),
				),
				
				'default_import' => array(
					'type' => 'select',
					'std' => 'publish',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Import as',
					'desc' => 'Default import products with status "publish" or "draft"',
					'options' => array(
						'publish' => 'Publish',
						'draft' => 'Draft'
					)
				),
				
				'import_type' => array(
					'type' => 'select',
					'std' => 'default',
					'size' => 'large',
					'force_width' => '280',
					'title' => 'Image Import type',
					'options' => array(
						'default' => 'Default - download images at import',
						'asynchronous' => 'Asynchronous image download'
					)
				),
				'ratio_prod_validate' 	=> array(
					'type' 		=> 'select',
					'std'		=> 90,
					'size' 		=> 'large',
					'title' 	=> __('Ratio product validation:', $WooZone->localizationName),
					'force_width'=> '100',
					'desc' 		=> __('The minimum percentage of total assets download (product + variations) from which a product is considered valid!', $WooZone->localizationName),
					'options'	=> $WooZone->doRange( range(10, 100, 5) )
				),
				'cron_number_of_images' => array(
					'type' => 'text',
					'std' => '100',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Cron number of images',
					'desc' => 'The number of images your cronjob (for downloading product assets) will download at each execution. If you are using remote images option, there will be no need to download assets.'
				),
				'number_of_images' => array(
					'type' => 'text',
					'std' => 'all',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Number of images',
					'desc' => 'How many images to download for each product. Default is <code>all</code>. Also, for each product variation (variation child) only one image is downloaded.'
				),
				/*'number_of_images_variation' => array(
					'type' => 'text',
					'std' => 'all',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Number of images for variation',
					'desc' => 'How many images to download for each product variation. Default is <code>all</code>'
				),*/
				'rename_image' => array(
					'type' => 'select',
					'std' => 'product_title',
					'size' => 'large',
					'force_width' => '130',
					'title' => 'Image names',
					'options' => array(
						'product_title' => 'Product title',
						'random' => 'Random number'
					)
				),

				'remove_gallery' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Gallery',
					'desc' => 'Show gallery in product description.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				 'remove_featured_image_from_gallery' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Remove featured image from product gallery',
					'desc' => 'Remove featured image from product gallery if the theme does not support it',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				'show_short_description' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Product Short Description',
					'desc' => 'Show product short description.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				'show_review_tab' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Review tab',
					'desc' => 'Show Amazon reviews tab in product description.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				'redirect_checkout_msg' => array(
					'type' => 'textarea',
					'std' => 'You will be redirected to {amazon_website} to complete your checkout!',
					'size' => 'large',
					'force_width' => '160',
					'title' => 'Checkout message',
					'desc' => 'Message for checkout redirect box.'
				),
				'redirect_time' => array(
					'type' => 'text',
					'std' => '3',
					'size' => 'large',
					'force_width' => '120',
					'title' => 'Redirect in',
					'desc' => 'How many seconds to wait before redirect to Amazon!'
				),
				
				'product_buy_text'   => array(
					'type'      => 'text',
					'std'       => '',
					'size'      => 'large',
					'force_width'=> '400',
					'title'     => __('Button buy text', $WooZone->localizationName),
					'desc'      => __('(global) This text will be shown on the button linking to the external product. (global) = all external products; external products = those with "On-site Cart" option value set to "No"', $WooZone->localizationName)
				),
							
				'product_buy_button_open_in' => array(
					'type' => 'select',
					'std' => '_self',
					'size' => 'large',
					'force_width' => '200',
					'title' => 'Product buy button open in:',
					'desc' => 'This option will allow you to setup how the product buy button will work. You can choose between opening in the same tab or in a new tab.' ,
					'options' => array(
						'_self' => 'Same tab',
						'_blank' => 'New tab'
					)
				),
				
				'spin_at_import' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Spin on Import',
					'desc' => 'Choose YES if you want to auto spin post, page content at amazon import',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				'spin_max_replacements' => array(
					'type' => 'select',
					'std' => '10',
					'force_width' => '150',
					'size' => 'large',
					'title' => 'Spin max replacements',
					'desc' => 'Choose the maximum number of replacements for auto spin post, page content at amazon import.',
					'options' => array(
						'10' 		=> '10 replacements',
						'30' 		=> '30 replacements',
						'60' 		=> '60 replacements',
						'80' 		=> '80 replacements',
						'100' 		=> '100 replacements',
						'0' 		=> 'All possible replacements',
					)
				),
				
				'create_only_parent_category' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Create only parent categories on Import',
					'desc' => 'This option will create only parent categories from Amazon on import instead of the whole category tree',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				/*'selected_category_tree' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Create only selected category tree on Import',
					'desc' => 'This option will create only selected categories based on browsenodes on import instead of the whole category tree',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),*/
				
				'variation_force_parent' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Force import parent if is variation',
					'desc' => 'This option will force import parent if the product is a variation child.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				/* remote amazon images */
				'remote_amazon_images' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Remote amazon images',
					'desc' => 'Choose YES if you don\'t want to download on your local server the amazon images for products, but use them external.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				'images_sizes_allowed' 	=> array(
					'type' 		=> 'multiselect_left2right',
					'std' 		=> array(), //array('thumbnail', 'medium', 'shop_thumbnail', 'shop_catalog'),
					'size' 		=> 'large',
					'rows_visible'	=> 8,
					'force_width'=> '150',
					'title' 	=> __('Select remote image sizes', $WooZone->localizationName),
					'desc' 		=> __('Choose what remote image sizes you want.', $WooZone->localizationName),
					'info'		=> array(
						'left' => 'All image sizes',
						'right' => 'Your chosen image sizes from list'
					),
					'options' 	=> WooZone_imageSizes()
				),
				
				/*'clean_duplicate_attributes' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Clean duplicate attributes',
					'desc' => 'Clean duplicate attributes.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),*/
			   
				'clean_duplicate_attributes_now' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Clean duplicate attributes Now',
					'html' => WooZone_attributes_clean_duplicate( '__tab4' )
				),
				
				'clean_duplicate_category_slug_now' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Clean duplicate category slug Now',
					'html' => WooZone_category_slug_clean_duplicate( '__tab4' )
				),
				
				'delete_all_zero_priced_products' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Delete all products with price zero',
					'html' => WooZone_delete_zeropriced_products( '__tab4' )
				),
				
				'clean_orphaned_amz_meta' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Clean orphaned Amz meta Now',
					'html' => WooZone_clean_orphaned_amz_meta( '__tab4' )
				),
				
				'clean_orphaned_products_assets' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Clean orphaned WooZone Product Assets Now',
					'html' => WooZone_clean_orphaned_prod_assets( '__tab4' )
				),
				
				'clean_orphaned_products_assets_wp' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Clean orphaned Wordpress Product Attachments Now',
					'html' => WooZone_clean_orphaned_prod_assets_wp( '__tab4' )
				),
				
				'fix_product_attributes_now' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Fix Product Attributes (after woocommerce 2.4 update)',
					'html' => WooZone_fix_product_attributes( '__tab4' )
				),
				
				'fix_node_children' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Clear Search old Node Childrens',
					'html' => WooZone_fix_node_childrens( '__tab4' )
				),
				
				/* Amazon Config */
				'protocol' => array(
					'type' => 'select',
					'std' => '',
					'size' => 'large',
					'force_width' => '200',
					'title' => 'Request Type',
					'desc' => 'How the script should make the request to Amazon API.',
					'options' => array(
						'auto' => 'Auto Detect',
						'soap' => 'SOAP',
						'xml' => 'XML (over cURL, streams, fsockopen)'
					)
				),

				'country' => array(
					'type' => 'select',
					'std' => '',
					'size' => 'large',
					'force_width' => '150',
					'title' => 'Amazon location',
					'desc' => 'All possible amazon stores',
					'options' => WooZone_amazon_countries( '__tab1', '__subtab1', 'country' )
				),
				
				'help_required_fields' => array(
					'type' => 'message',
					'status' => 'info',
					'html' => 'The following fields are required in order to send requests to Amazon and retrieve data about products and listings. If you do not already have access keys set up, please visit the <a href="https://aws-portal.amazon.com/gp/aws/developer/account/index.html?ie=UTF8&amp;action=access-key#access_credentials" target="_blank">AWS Account Management</a> page to create and retrieve them.'
				),

				'panel_multiple_amazon_keys' => array(
					'type' 		=> 'app',
					'path' 		=> '{plugin_folder_path}amzmultikeys/panel.php',
				),

				/*
				'AccessKeyID' => array(
					'type' => 'text',
					'std' => '',
					'size' => 'large',
					'title' => 'Access Key ID',
					'force_width' => '250',
					'desc' => 'Are required in order to send requests to Amazon API.'
				),
				'SecretAccessKey' => array(
					'type' => 'text',
					'std' => '',
					'size' => 'large',
					'force_width' => '400',
					'title' => 'Secret Access Key',
					'desc' => 'Are required in order to send requests to Amazon API.'
				),
				'buttons' => array(
					'type' => 'buttons',
					'options' => array(
						'check_amz' => array(
							'type' => 'button',
							'value' => 'Check Amazon AWS Keys',
							'color' => 'info',
							'action' => 'WooZoneCheckAmzKeys'
						)
					)
				),
				*/
				'AffiliateId' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Affiliate Information',
					'html' => WooZoneAffIDsHTML( '__tab1' )
				),
				'main_aff_id' => array(
					'type' => 'select',
					'std' => '',
					'force_width' => '150',
					'size' => 'large',
					'title' => 'Main Affiliate ID',
					'desc' => 'This Affiliate id will be use in API request and if user are not from any of available amazon country.',
					'options' => WooZone_amazon_countries( '__tab1', '__subtab1', 'main_aff_id' )
				),
				'help_available_countries' => array(
					'type' => 'message',
					'status' => 'info',
					'html' => '
							<strong>Available countries: &nbsp;</strong>
							'.WooZone_amazon_countries( '__tab1', '__subtab1', 'string' ).'
						'
				),
				'amazon_requests_rate' => array(
					'type' => 'select',
					'std' => '1',
					'force_width' => '200',
					'size' => 'large',
					'title' => 'Amazon requests rate',
					'desc' => 'The number of <a href="https://affiliate-program.amazon.com/gp/advertising/api/detail/faq.html" target="_blank">amazon requests per second</a> based on 30-day sales for your account.',
					'options' => array(
						'0.10' => '1 req per 10sec',
						'0.20' => '1 req per 5sec',
						'0.25' => '1 req per 4sec',
						'0.5' => '1 req per 2sec',
						'1' => '1 req per sec - till 2299$',
						'2' => '2 req per sec - till 9999$',
						'3' => '3 req per sec - till 19999$',
						'5' => '5 req per sec - from 20000$',
					)
				),
				
				'fix_issue_request_amazon_now' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Fix Request Amazon Issue',
					'html' => WooZone_fix_issue_request_amazon( '__tab4' )
				),
				
				'fix_issue_sync' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Sync Issue',
					'html' => WooZone_fix_issue_sync( '__tab4' )
				),

				'reset_products_stats_now' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Reset products stats',
					'html' => WooZone_reset_products_stats( '__tab4' )
				),
				
				'options_prefix_change_now' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Version 9.0 options prefix change',
					'html' => WooZone_options_prefix_change( '__tab4' )
				),
				
				'unblock_cron' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Unblock CRON jobs',
					'html' => WooZone_unblock_cron( '__tab4' )
				),
				
				/* Product in post */
				'productinpost_additional_images' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Product in post: Show Additional Images',
					'desc' => 'Product in post: Show Additional Images',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				'productinpost_extra_css' => array(
					'type' => 'textarea',
					'std' => '',
					'size' => 'large',
					'force_width' => '560',
					'title' => 'Product in post: Extra CSS',
					'desc' => 'Product in post: Extra CSS for frontend boxes' . PHP_EOL . '<div style="height: 100px; overflow: auto;"><pre>' . WooZone_productinpost_extra_css() . '</pre></div>'
				),
				
				/* product available countries */
				'product_countries' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Activate Product Availability by Country Box',
					'desc' => 'Choose YES if you want to activate product Availability by countries functionality',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				'product_countries_main_position' => array(
					'type' => 'select',
					'std' => 'before_add_to_cart',
					'size' => 'large',
					'force_width' => '500',
					'title' => 'Product Availability by <br/> Country Box',
					'desc' => 'This box will be positioned on product details page. Select where to display it:',
					'options' => array(
						'before_title_and_thumb'			=> 'Before Title and Thumb',
						'before_add_to_cart'					=> 'Before Add to Cart Button',
						'before_woocommerce_tabs'	=> 'Before Woocommerce Tabs',
						'as_woocommerce_tab'			=> 'As New Woocommerce Tab - COUNTRIES AVAILABLITY',
					)
				),
				'product_countries_maincart' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Show Country Flag on Cart Page?',
					'desc' => 'Choose YES if you want to show the current selected country for each product on cart page',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				'product_countries_countryflags' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Country Flags as Links?',
					'desc' => 'Choose YES if you want to show the country flags as links, on product details page.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				/*'product_countries_where' 	=> array(
					'type' 		=> 'multiselect_left2right',
					'std' 			=> array('maincart', 'minicart'),
					'size' 		=> 'large',
					'rows_visible'	=> 2,
					'force_width'=> '300',
					'title' 	=> __('Where product current selected country is showed?', $WooZone->localizationName),
					'desc' 		=> __('Choose where you want to have an indicator of product current selected country', $WooZone->localizationName),
					'info'		=> array(
						'left' => 'Extra zones',
						'right' => 'Your chosen extra zones'
					),
					'options' 	=> array(
						'maincart'			=> 'frontend main cart page',
						'minicart'			=> 'frontend mini cart box'
					)
				),*/

				'asof_font_size' => array(
					'type' => 'select',
					'std' => '0.6',
					'size' => 'large',
					'force_width' => '100',
					'title' => '"As Of" text font size',
					'desc' => 'Choose the font size (in em) for "as of" text',
					'options' => WooZone_asof_font_size()
				),
				
				'delete_attachments_at_delete_post' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Delete attachments also when you delete product?',
					'desc' => '<span style="color: red;">ATTENTION: If you choose YES, then all product attachements will be removed from database (and from your hard-drive if don\'t use the "remote images" option). So you must be sure that you\'re product attachments aren\'t used in other posts, without being directly attached to them.</span>',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				'cross_selling' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Cross-selling',
					'desc' => 'Show Frequently Bought Together box.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),
				
				'cross_selling_nbproducts' => array(
					'type' => 'select',
					'std' => '3',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Cross-selling Nb Products',
					'desc' => 'Choose how many products do you want to display in your "Frequently Bought Together box" box.',
					'options' => $WooZone->doRange( range(3, 10, 1) )
				),

				'cross_selling_choose_variation' => array(
					'type' => 'select',
					'std' => 'first',
					'size' => 'large',
					'force_width' => '200',
					'title' => 'Cross-selling Variable Product',
					'desc' => 'If we encounter variable products when we try to build the cross sell box, we must choose one of their coresponding variation children to be, because you cannot buy main variable products, but only one of their variations. We also don\'t take into consideration variations without a valid non-zero price. So choose here which variation should we get for each encountered variable product.',
					'options' => array(
						'first' => 'First variation',
						'lowest_price' => 'Lowest price variation',
						'highest_price' => 'Highest price variation'
					)
				),
				
				'string_trans' => array(
					'type' => 'translation',
					'std' => '',
					'size' => 'large',
					'force_width' => '160',
					'title' => 'Strings',
					'options' => WooZone()->expressions,
					'desc' => 'Using this option you can translate WooZone strings.'
				),

				//:: offerlistingid related
				'import_product_offerlistingid_missing' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Import products with missing offerListingId',
					'desc' => 'Choose Yes if you want to import amazon products which don\'t have an offerListingId. <br/><span style="color: red;">When importing products, this should filter some of the products existent in amazon stores, but which aren\'t currently available to be bought.</span> <br />According to amazon docs: <a href="https://docs.aws.amazon.com/AWSECommerceService/latest/DG/CheckingforanOfferListingID.html" target="_blank" style="font-weight: bold;">If an item is for sale, it has an offer listing ID</a>',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),

				'import_product_variation_offerlistingid_missing' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Import product variations with missing offerListingId',
					'desc' => 'Choose Yes if you want to import amazon product variations (for variable products) which don\'t have an offerListingId. <br/><span style="color: red;">When importing products, this should filter some of the product variations (for variable products) existent in amazon stores, but which aren\'t currently available to be bought.</span> <br />According to amazon docs: <a href="https://docs.aws.amazon.com/AWSECommerceService/latest/DG/CheckingforanOfferListingID.html" target="_blank" style="font-weight: bold;">If an item is for sale, it has an offer listing ID</a>',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),

				'product_offerlistingid_missing_external' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Products with missing offerListingId => External',
					'desc' => 'Choose Yes if you want to convert all amazon products which don\'t have an offerListingId to product type EXTERNAL. <br/><span style="color: red;">For this to work, you need to have the "SYNCHRONISATION" module activated and SYNCHRONISATION SETTINGS must have Price checked to be synced</span> <br />According to amazon docs: <a href="https://docs.aws.amazon.com/AWSECommerceService/latest/DG/CheckingforanOfferListingID.html" target="_blank" style="font-weight: bold;">If an item is for sale, it has an offer listing ID</a>',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),

				'product_offerlistingid_missing_delete' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Products with missing offerListingId => Delete | Trash',
					'desc' => 'This action is influenced by "Product : Delete | Move to Trash" option /Plugin SETUP tab. <br />Choose Yes if you want to ( remove | put in trash ) an amazon product (or just a variation) which don\'t have an offerListingId, when syncing it. <br/><span style="color: red;">For this to work, you need to have the "SYNCHRONISATION" module activated</span> <br />According to amazon docs: <a href="https://docs.aws.amazon.com/AWSECommerceService/latest/DG/CheckingforanOfferListingID.html" target="_blank" style="font-weight: bold;">If an item is for sale, it has an offer listing ID</a>',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),

				'reset_sync_stats_now' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => 'Reset SYNC stats',
					'html' => WooZone_reset_sync_stats( '__tab4' )
				),


				// DEBUG
				'debug_bar_activate' => array(
					'type' => 'select',
					'std' => 'yes',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Activate WooZone Debug Bar',
					'desc' => 'Choose Yes if you want to activate the Woozone Debug Bar.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),

				'debug_ip' => array(
					'type' => 'textarea',
					'std' => '',
					'size' => 'large',
					'force_width' => '160',
					'title' => 'Debug IP List',
					'desc' => 'You need to enter the IPs (separated by comma) for which you want to activate the plugin debug mode.<br/><em>For now debug mode only display the amazon response message for "frequently bought togheter" or "cross sell" frontend box.</em>'
				),

				/*'_load_javascript' => array(
					'type' => 'html',
					'std' => '',
					'size' => 'large',
					'title' => '',
					'html' => "
					<script>
						//WooZone.aateam_tooltip();
					</script>
					",
				),*/

			)
		)
	)
));