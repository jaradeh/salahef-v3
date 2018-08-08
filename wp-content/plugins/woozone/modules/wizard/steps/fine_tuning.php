			<!-- StartSetup -->
			<div class="wz-setup">
				<table  width="60%">
					<tr>
						<td width="20%" class="wz-toption"><?php _e('Number of Images', $this->localizationName); ?></td>
						<td width="35%" class="wz-trange"> 
							<?php /*<div class="range-slider">
								<input class="range-slider__range" type="range" value="50" min="0" max="100">
								<span class="range-slider__value">0</span>
							</div>*/ ?>
							<?php echo $this->build_form_input_range('number_of_images', array(
								'min'			=> 1,
								'max'			=> 100,
								'step'			=> 1,
								'max_to'		=> array('all', __('all', $this->localizationName)),
							)); ?>
                       </td>
                       <td valign="middle">
                    		<p><?php _e('How many images to download for each products. Default is all', $this->localizationName); ?></p>
                       </td>
					</tr>
					
					<tr>
						<td width="20%" class="wz-toption"><?php _e('Variations', $this->localizationName); ?></td>
						<td width="35%" class="wz-trange"> 
							<?php /*<div class="range-slider">
								<input class="range-slider__range" type="range" value="50" min="0" max="100">
								<span class="range-slider__value">0</span>
							</div>*/ ?>
							<?php echo $this->build_form_input_range('product_variation', array(
								'min'			=> 0,
								'max'			=> 100,
								'step'			=> 1,
								'min_to'		=> array('no', __('none', $this->localizationName)),
								'max_to'		=> array('yes_all', __('all', $this->localizationName)),
								'val_to'		=> array('yes_%s', __('%s', $this->localizationName)),
							)); ?>
                       </td>
						<?php /*<td class="wz-tselect"> 
							<?php echo $this->build_form_select('product_variation', array(
								'values'		=> array(
									'no'			=> __('NO', $this->localizationName),
									'yes_1'			=> __('Yes 1 variation', $this->localizationName),
									'yes_2'			=> __('Yes 2 variations', $this->localizationName),
									'yes_3'			=> __('Yes 3 variations', $this->localizationName),
									'yes_4'			=> __('Yes 4 variations', $this->localizationName),
									'yes_5'			=> __('Yes 5 variations', $this->localizationName),
									'yes_10'		=> __('Yes 10 variations', $this->localizationName),
									'yes_all'		=> __('Yes All variations', $this->localizationName),
								),
								'css_class'		=> 'wz-dropdown',
							)); ?>
                       </td>*/ ?>
                       <td valign="middle">
                       	  <p><?php _e('Get product variations. Be carefull about All variations. One product can have a lot of variations, execution time is dramatically increased!', $this->localizationName); ?></p>
                       </td>
					</tr>
					
					<tr>
						<td width="20%" class="wz-toption"><?php _e('Spin on Import', $this->localizationName); ?></td>
						<td colspan="2" class="wz-tcolspan"> 
							<?php /*<div class="checkbox">
								<input type="checkbox" id="spin_on_import" name="cb" />
								<label class="wz-checked" for="cb"></label>
							</div>*/ ?>
							<?php echo $this->build_form_input_checkbox('spin_at_import', array(
								//'value'			=> 'yes',
							)); ?>
	                        <div class="wz-ptable">
	                       		<p><?php _e('Choose if you want to auto spin content at amazon import to avoid google finding duplicate content', $this->localizationName); ?></p>
	                        </div>
                       </td>
					</tr>

					<?php /*<tr>
						<td width="20%" class="wz-toption"><?php _e('Import in', $this->localizationName); ?></td>
						<td class="wz-tselect"> 
							<select name="import_in" class="wz-dropdown">
								<option value="use_category_from_amazon">Use Category from Amazon</option>
								<option value="">Other Sellers</option>
							</select>
  							<td valign="middle"><p><?php _e('You can create your own categories and import into them or you can let categories to be created automatically from Amazon', $this->localizationName); ?></p></td>
                       </td>
					</tr>*/ ?>

				</table>
			</div>