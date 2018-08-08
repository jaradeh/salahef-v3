			<!-- StartSetup -->
			<div class="wz-setup">
				<table  width="60%">
					<tr>
						<td width="20%" class="wz-toption"><?php _e('On Site Cart', $this->localizationName); ?></td>
						<td colspan="2" class="wz-tcolspan"> 
							<?php /*<div class="checkbox">
							    <input type="checkbox " id="on_site_cart" name="on_site_cart" />
							    <label class="wz-checked" for="cb"></label>
  							</div>*/ ?>
							<?php echo $this->build_form_input_checkbox('onsite_cart', array(
								//'value'			=> 'yes',
							)); ?>
	                       <div class="wz-ptable">
	                       		<p><?php _e('This option will allow your customers to add multiple Amazon Products into Cart and checkout trought Amazon\'s system with all at once.', $this->localizationName); ?></p>
	                       </div>
                       </td>
					</tr>

					<tr>
						<td width="20%" class="wz-toption"><?php _e('90 Days Cookies', $this->localizationName); ?></td>
						<td colspan="2" class="wz-tcolspan"> 
							<?php /*<div class="checkbox">
							    <input type="checkbox " id="90_days_cookies" name="90_days_cookies" />
							    <label class="wz-checked" for="cb"></label>
  							</div>*/ ?>
							<?php echo $this->build_form_input_checkbox('90day_cookie', array(
								//'value'			=> 'yes',
							)); ?>
	  						<div class="wz-ptable">
	  							  <p><?php _e('If a customer adds a product into amazon cart, it’s kept there for 90 days, and if the user continues shopping you will get the commissions also!', $this->localizationName); ?></p>
	                        </div>
                       </td>
					</tr>

					<tr>
						<td width="20%" class="wz-toption"><?php _e('Reviews Tab', $this->localizationName); ?></td>
						<td colspan="2" class="wz-tcolspan"> 
							<?php /*<div class="checkbox">
							    <input type="checkbox" id="reviews_tab" name="reviews_tab" />
							    <label for="cb"></label>
  							</div>*/ ?>
							<?php echo $this->build_form_input_checkbox('show_review_tab', array(
								//'value'			=> 'yes',
							)); ?>
  							<div class="wz-ptable">
  								<p><?php _e('Show Amazon reviews', $this->localizationName); ?></p>
  							</div>
                       </td>
					</tr>
					
					<tr>
						<td width="20%" class="wz-toption"><?php _e('Cross Selling', $this->localizationName); ?></td>
						<td colspan="2" class="wz-tcolspan"> 
							<?php /*<div class="checkbox">
							    <input type="checkbox" id="cross_selling" name="cross_selling" />
							    <label class="wz-checked" for="cb"></label>
  							</div>*/ ?>
							<?php echo $this->build_form_input_checkbox('cross_selling', array(
								//'value'			=> 'yes',
							)); ?>
  							<div class="wz-ptable">
  								<p><?php _e('Show Frequently Bought Together Products', $this->localizationName); ?></p>
  							</div>
                       </td>
					</tr>
					
					<tr>
						<td width="20%" class="wz-toption"><?php _e('Product Availability by Country Box', $this->localizationName); ?></td>
						<td colspan="2" class="wz-tcolspan"> 
							<?php /*<div class="checkbox">
							    <input type="checkbox" id="product_availability_country_box" name="product_availability_country_box" />
							    <label class="wz-checked" for="cb"></label>
  							</div>*/ ?>
							<?php echo $this->build_form_input_checkbox('product_countries', array(
								//'value'			=> 'yes',
							)); ?>
  							<div class="wz-ptable">
  								<p><?php _e('This shows if a product is available in a certain country', $this->localizationName); ?></p>
  							</div>
                       </td>
					</tr>
					
					<tr>
						<td width="20%" class="wz-toption"><?php _e('Show Coupon', $this->localizationName); ?></td>
						<td colspan="2" class="wz-tcolspan"> 
							<?php /*<div class="checkbox">
							    <input type="checkbox" id="show_coupon" name="show_coupon" />   
							    <label for="cb"></label>
  							</div>*/ ?>
							<?php echo $this->build_form_input_checkbox('frontend_show_coupon_text', array(
								//'value'			=> 'yes',
							)); ?>
  							<div class="wz-ptable">
  								<p><?php _e('This option will display coupones if they are available on products', $this->localizationName); ?></p>
  							</div>
                       </td>
					</tr>
					
					<tr>
						<td width="20%" class="wz-toption"><?php _e('Checkout E-mail', $this->localizationName); ?></td>
						<td colspan="2" class="wz-tcolspan"> 
							<?php /*<div class="checkbox">
							    <input type="checkbox" id="checkout_email" name="checkout_email" />  
							    <label class="wz-checked" for="cb"></label> 
  							</div>*/ ?>
							<?php echo $this->build_form_input_checkbox('checkout_email', array(
								//'value'			=> 'yes',
							)); ?>
  							<div class="wz-ptable">
  								<p><?php _e('Ask the user e-mail address before the checkout process happens', $this->localizationName); ?></p>
  							</div>
                       </td>
					</tr>
					
					<tr>
						<td width="20%" class="wz-toption"><?php _e('Remote Amazon Images', $this->localizationName); ?></td>
						<td colspan="2" class="wz-tcolspan"> 
							<?php /*<div class="checkbox">
							    <input type="checkbox" id="remote_amazon_images" name="remote_amazon_images" /> 
							    <label class="wz-checked" for="cb"></label>  
  							</div>*/ ?>
							<?php echo $this->build_form_input_checkbox('remote_amazon_images', array(
								//'value'			=> 'yes',
							)); ?>
  							<div class="wz-ptable">
  								<p><?php _e('This option will display all products images from Amazon CDN', $this->localizationName); ?></p>
  							</div>
                       </td>
					</tr>
					
					<tr>
						<td width="20%" class="wz-toption"><?php _e('Show Free Shipping', $this->localizationName); ?></td>
						<td colspan="2" class="wz-tcolspan"> 
							<?php /*<div class="checkbox">
							    <input type="checkbox" id="show_free_shipping" name="show_free_shipping" />   
							    <label class="wz-checked" for="cb"></label>
  							</div>*/ ?>
							<?php echo $this->build_form_input_checkbox('frontend_show_free_shipping', array(
								//'value'			=> 'yes',
							)); ?>
  							<div class="wz-ptable">
  								<p><?php _e('If a product has free shipping will be displayed in the product details page', $this->localizationName); ?></p>
  							</div>
                       </td>
					</tr>
					
				</table>
			</div>