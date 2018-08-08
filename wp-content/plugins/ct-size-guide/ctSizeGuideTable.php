<?php

/**
 * adds the 'edit table' meta box
 * @author jacek
 */
class ctSizeGuideTable
{
    /**
     * Init object
     */

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'editSizeGuideTable'));
        add_action('add_meta_boxes', array($this, 'editSizeGuideSettings'));
        add_action('save_post_ct_size_guide', array($this, 'saveSizeGuideTable'));
        add_action('edit_post_ct_size_guide', array($this, 'saveSizeGuideTable'));
    }

    /**
     * Add meta box for size guide
     */

    public function editSizeGuideTable()
    {
        add_meta_box('ct_sizeguidetable', __('Create/modify size guide table', 'ct_sgp'), array(
            $this,
            'renderSizeGuideTableMetaBox'
        ), 'ct_size_guide', 'normal', 'high');
    }

    /**
     * Size Guide Meta box
     *
     * @param $post
     */

    public function renderSizeGuideTableMetaBox($post)
    {
        wp_nonce_field('size_guide_meta_box', 'size_guide_meta_box_nonce');

        $current = get_current_screen()->action;

        $newpost = ($current == 'add');

        $defaultTable = array(
            array('Size', 'Bust', 'Waist', 'Hips'),
            array('8', '32', '25', '35'),
            array('10', '34', '27', '37'),
            array('12', '36', '29', '39'),
        );
        $defaultTitle = 'Table title';
        $defaultCaption = 'Table caption';

        if (!$newpost) {
            $post_id = $post->ID;
            $meta_table = get_post_meta($post_id, '_ct_sizeguide');
            $meta_table = $meta_table[0];
        } else {
            $meta_table[0] = array(
                'title' => $defaultTitle,
                'table' => $defaultTable,
                'caption' => $defaultCaption
            );
        }

        foreach ($meta_table as $key => $table) {

            if ( empty( $table['table'] ) ) {
                continue;
            }
	        $this->sizeGuideTablePreTemplate( $table, $key, '' );
	        $this->sizeGuideTableTemplate($table, $key, '');

        }

	    $this->sizeGuideTablePostTemplate( $meta_table[0], 0, '' );
    }

    public function sizeGuideTablePreTemplate($table, $key, $class = ''){

        if ( 0 == $key ) {
	        echo '<div class="ct_single_size_table' . ($class ? ' ' . $class : '') . '">';
        }

	    echo '<p class="sg-sizeGuide-title-above"><strong>' . __('Text above table', 'ct-sgp') . '</strong></p>';

	    $args_title = array(
		    'textarea_name' => 'ct_size_guide[' . $key . '][title]',
		    'textarea_rows' => 2
	    );

	    $title = isset( $table['title'] ) ? $table['title'] : '';
	    wp_editor( $title, 'size_table_caption' . $key, $args_title);
    }

    public function sizeGuideTablePostTemplate($table, $key, $class = ''){

        echo '<div id="ct-sizeGuide-tableControl">';
        echo    '<button type="button" class="button ct-addTable">' . __( 'Add Table', 'ct_sgp' ) . '</button>';
        echo    '<button type="button" class="button ct-delTable"><i class="free free-uniE905" aria-hidden="true"></i>' . __( 'Remove Table', 'ct_sgp' ) . '</button>';
        echo '</div>';

	    echo '<p class="sg-sizeGuide-title-below"><strong>' . __('Table caption', 'ct-sgp') . '</strong></p>';

	    $args_caption = array(
		    'textarea_name' => 'ct_size_guide[' . $key . '][caption]',
		    'textarea_rows' => 2
	    );

	    wp_editor($table['caption'], 'size_table_title' . $key, $args_caption);
	    echo '</div>';
    }

	/**
	 * Render table
	 *
	 * @param $table
	 * @param $key
	 * @param string $class
	 */
	public function sizeGuideTableTemplate($table, $key, $class = '')
    {
        echo '<br>';

        echo '<textarea class="ct_edit_table" name="ct_size_guide[' . $key . '][table]" style="display:none">';
        $table_array = json_encode($table['table']);
        echo $table_array;
        echo '</textarea>';
    }

    /**
     * Add size guide metabox settings
     */

    public function editSizeGuideSettings()
    {
        add_meta_box('ct_sizeguidesettings', __('Size guide settings', 'ct_sgp'), array($this, 'renderSizeGuideSettingsMetaBox'), 'ct_size_guide', 'normal', 'high');
    }

    /**
     * Meta box
     *
     * @param $post
     */

    public function renderSizeGuideSettingsMetaBox($post)
    {
        $current = get_current_screen()->action;

        $newpost = ($current == 'add');

        if (!$newpost) {
            $post_id = $post->ID;
        } else {
            $post_id = 'new';
        }

        wp_nonce_field('size_guide_settings_meta_box', 'size_guide_settings_meta_box_nonce');

        echo '<div class="sg-single-setting"><label>' . __('Open guide with:', 'ct-sgp') . '</label> <select name="size_guide_settings[wc_size_guide_button_style]" class="chosen_select">
                    <option value="global" ' . $this->getSelected($post_id, 'wc_size_guide_button_style', 'global') . '>' . __('Use global settings', 'ct-sgp') . '</option>
                    <option value="ct-trigger-link" ' . $this->getSelected($post_id, 'wc_size_guide_button_style', 'ct-trigger-link') . '>' . __('Link', 'ct-sgp') . '</option>
                    <option value="ct-trigger-button" ' . $this->getSelected($post_id, 'wc_size_guide_button_style', 'ct-trigger-button') . '>' . __('Button', 'ct-sgp') . '</option>
               </select><small>   ' . __('Chose whether to display a simple link or a button to open the size guide.', 'ct-sgp') . '</small></div>';

        echo '<div class="sg-single-setting"><label>' . __('Button/link position:', 'ct-sgp') . '</label> <select name="size_guide_settings[wc_size_guide_button_position]" class="chosen_select">
                    <option value="global" ' . $this->getSelected($post_id, 'wc_size_guide_button_position', 'global') . '>' . __('Use global settings', 'ct-sgp') . '</option>
                    <option value="ct-position-price" ' . $this->getSelected($post_id, 'wc_size_guide_button_position', 'ct-position-price') . '>' . __('Under Price', 'ct-sgp') . '</option>
                    <option value="ct-position-summary" ' . $this->getSelected($post_id, 'wc_size_guide_button_position', 'ct-position-summary') . '>' . __('Above the product summary tabs', 'ct-sgp') . '</option>
                    <option value="ct-position-add-to-cart" ' . $this->getSelected($post_id, 'wc_size_guide_button_position', 'ct-position-add-to-cart') . '>' . __('After Add To Cart button', 'ct-sgp') . '</option>
				    <option value="ct-position-info" ' . $this->getSelected($post_id, 'wc_size_guide_button_position', 'ct-position-info') . '>' . __('After Product Info', 'ct-sgp') . '</option>
                    <option value="ct-position-tab" ' . $this->getSelected($post_id, 'wc_size_guide_button_position', 'ct-position-tab') . '>' . __('Make it a tab', 'ct-sgp') . '</option>
                    <option value="ct-position-shortcode" ' . $this->getSelected($post_id, 'wc_size_guide_button_position', 'ct-position-shortcode') . '>Embed manually (shortcode)</option>
               </select><small>   ' . __('For manual embed, [ct_size_guide] shortcode can be placed anywhere you want. More info can be found <a href="http://createit.support/documentation/size-guide/#doc-7007" target="_blank">here</a>', 'ct-sgp') . '</small></div>';
        ?>

        <div>
            <?php
            echo '<div class="sg-single-setting"><label>' . __('Button/link hook priority:', 'ct-sgp') . '</label>
            <select name="size_guide_settings[wc_size_guide_button_priority_dropdown]" class="chosen_select" id="wc_size_guide_button_priority_dropdown">
                <option value="global" ' . $this->getSelected($post_id, 'wc_size_guide_button_priority_dropdown', 'global') . '>' . __('Use global settings', 'ct-sgp') . '</option>
                <option value="individual_priority" ' . $this->getSelected($post_id, 'wc_size_guide_button_priority_dropdown', 'individual_priority') . '>' . __('Use individual settings', 'ct-sgp') . '</option>
            </select>
            <small>' . __('Chose whether to use global or individual option to set priority of the action that outputs the button/link. Using this you can adjust the position - check the <a href="http://createit.support/documentation/size-guide/#button-priority">documentation</a> for more information.', 'ct-sgp') . '</small></div>'; ?>

            <script>
                (function ($) {
                    $(document).ready(function () {

                        $("#wc_size_guide_button_priority_dropdown option:selected").each(function () {
                            var selected = $(this).val();
                            if (selected == 'individual_priority') {
                                $("#individual_priority_chosen").css({"display": "block"});
                                var input = $("#individual_priority").val();
                                if (input == 'global') {
                                    $("#individual_priority").val('');
                                }
                            } else {
                                $("#individual_priority_chosen").css({"display": "none"});
                                $("#individual_priority").val('global');
                            }
                        });

                        $("#wc_size_guide_button_priority_dropdown").change(function () {
                            var selected = $(this).val();
                            if (selected == 'individual_priority') {
                                $("#individual_priority_chosen").css({"display": "block"});
                                var input = $("#individual_priority").val();
                                if (input == 'global') {
                                    $("#individual_priority").val('');
                                }
                            } else {
                                $("#individual_priority_chosen").css({"display": "none"});
                                $("#individual_priority").val('global');
                            }
                        });
                    });
                })(jQuery);
            </script>

            <?php
            echo '
            <div class="sg-single-setting" id="individual_priority_chosen" style="display:none"><label>' . __('Individual priority:', 'ct-sgp') . '</label>
            <input type="text" name="size_guide_settings[wc_size_guide_button_priority]" value="' . $this->getNumberValue($post_id, 'wc_size_guide_button_priority', 'global') . '" class="chosen_input" id="individual_priority">
            <small>' . __('Type individual priority.', 'ct-sgp') . '</small></div>';
            ?>
        </div>

        <div>
            <?php
            echo '<div class="sg-single-setting"><label>' . __('Button/link label:', 'ct-sgp') . '</label>
            <select name="size_guide_settings[wc_size_guide_button_label_dropdown]" class="chosen_select" id="wc_size_guide_button_label_dropdown">
                <option value="global" ' . $this->getSelected($post_id, 'wc_size_guide_button_label_dropdown', 'global') . '>' . __('Use global settings', 'ct-sgp') . '</option>
                <option value="individual_label" ' . $this->getSelected($post_id, 'wc_size_guide_button_label_dropdown', 'individual_label') . '>' . __('Use individual settings', 'ct-sgp') . '</option>
            </select>
            <small> ' . __("Chose whether to use global or individual option to display name of the size guide button/link's label.", 'ct-sgp') . '</small></div>'; ?>

            <script>
                (function ($) {
                    $(document).ready(function () {

                        $("#wc_size_guide_button_label_dropdown option:selected").each(function () {
                            var selected = $(this).val();
                            if (selected == 'individual_label') {
                                $("#individual_label_chosen").css({"display": "block"});
                                var input = $("#individual_label").val();
                                if (input == 'global') {
                                    $("#individual_label").val('');
                                }
                            } else {
                                $("#individual_label_chosen").css({"display": "none"});
                                $("#individual_label").val('global');
                            }
                        });

                        $("#wc_size_guide_button_label_dropdown").change(function () {
                            var selected = $(this).val();
                            if (selected == 'individual_label') {
                                $("#individual_label_chosen").css({"display": "block"});
                                var input = $("#individual_label").val();
                                if (input == 'global') {
                                    $("#individual_label").val('');
                                }
                            } else {
                                $("#individual_label_chosen").css({"display": "none"});
                                $("#individual_label").val('global');
                            }
                        });
                    });
                })(jQuery);
            </script>

            <?php
            echo '
            <div class="sg-single-setting" id="individual_label_chosen" style="display:none"><label>' . __('Individual label:', 'ct-sgp') . '</label>
            <input type="text" name="size_guide_settings[wc_size_guide_button_label]" value="' . $this->getNumberValue($post_id, 'wc_size_guide_button_label', 'global') . '" class="chosen_input" id="individual_label">
            <small>' . __('Type button/link label.', 'ct-sgp') . ' </small></div>'; ?>
        </div>

        <?php
        echo '<div class="sg-single-setting"><label>' . __('Button/link align:', 'ct-sgp') . '</label> <select name="size_guide_settings[wc_size_guide_button_align]" class="chosen_select">
                    <option value="global" ' . $this->getSelected($post_id, 'wc_size_guide_button_align', 'global') . '>' . __('Use global settings', 'ct-sgp') . '</option>
                    <option value="left" ' . $this->getSelected($post_id, 'wc_size_guide_button_align', 'left') . '>' . __('Left', 'ct-sgp') . '</option>
                    <option value="right" ' . $this->getSelected($post_id, 'wc_size_guide_button_align', 'right') . '>' . __('Right', 'ct-sgp') . '</option>
               </select></div>';

        echo '<div class="sg-single-setting"><label>' . __('Button/link clearing:', 'ct-sgp') . '</label> <select name="size_guide_settings[wc_size_guide_button_clear]" class="chosen_select">
                    <option value="global" ' . $this->getSelected($post_id, 'wc_size_guide_button_clear', 'global') . '>' . __('Use global settings', 'ct-sgp') . '</option>
                    <option value="yes" ' . $this->getSelected($post_id, 'wc_size_guide_button_clear', 'yes') . '>' . __('Yes', 'ct-sgp') . '</option>
                    <option value="no" ' . $this->getSelected($post_id, 'wc_size_guide_button_clear', 'no') . '>' . __('No', 'ct-sgp') . '</option>
               </select><small>   ' . __('Allow floating elements on the sides of the link/button?', 'ct-sgp') . '</small></div>';

        ?>
        <div>
            <?php
            echo '<div class="sg-single-setting"><label>' . __('Button class:', 'ct-sgp') . '</label>
                    <select name="size_guide_settings[wc_size_guide_button_class_dropdown]" class="chosen_select" id="wc_size_guide_button_class_dropdown">
                        <option value="global" ' . $this->getSelected($post_id, 'wc_size_guide_button_class_dropdown', 'global') . '>' . __('Use global settings', 'ct-sgp') . '</option>
                        <option value="individual_class" ' . $this->getSelected($post_id, 'wc_size_guide_button_class_dropdown', 'individual_class') . '>' . __('Use individual settings', 'ct-sgp') . '</option>
                    </select>
                    <small>' . __("Chose whether to use global or individual option to set custom class of the size guide button.", 'ct-sgp') . '</small></div>'; ?>

            <script>
                (function ($) {
                    $(document).ready(function () {

                        $("#wc_size_guide_button_class_dropdown option:selected").each(function () {
                            var selected = $(this).val();
                            if (selected == 'individual_class') {
                                $("#individual_class_chosen").css({"display": "block"});
                                var input = $("#individual_class").val();
                                if (input == 'global') {
                                    $("#individual_class").val('');
                                }

                            } else {
                                $("#individual_class_chosen").css({"display": "none"});
                                $("#individual_class").val('global');
                            }
                        });

                        $("#wc_size_guide_button_class_dropdown").change(function () {
                            var selected = $(this).val();
                            if (selected == 'individual_class') {
                                $("#individual_class_chosen").css({"display": "block"});
                                var input = $("#individual_class").val();
                                if (input == 'global') {
                                    $("#individual_class").val('');
                                }
                            } else {
                                $("#individual_class_chosen").css({"display": "none"});
                                $("#individual_class").val('global');
                            }
                        });
                    });
                })(jQuery);
            </script>

            <?php
            echo '
                    <div class="sg-single-setting" id="individual_class_chosen" style="display:none"><label>' . __('Individual class:', 'ct-sgp') . '</label>
                    <input type="text" name="size_guide_settings[wc_size_guide_button_class]" value="' . $this->getNumberValue($post_id, 'wc_size_guide_button_class', 'global') . '" class="chosen_input" id="individual_class">
                    <small>' . __('Add a custom class to the button. Default class which we use is button_sg', 'ct-sgp') . '</small></div>'; ?>
        </div>
        <?php

        ?>
        <div>
            <?php
            echo '<div class="sg-single-setting"><label>' . __('Margins of the link/button:', 'ct-sgp') . '</label>
            <select name="size_guide_settings[wc_size_guide_button_margins_dropdown]" class="chosen_select" id="wc_size_guide_button_margins_dropdown">
                   <option value="global" ' . $this->getSelected($post_id, 'wc_size_guide_button_margins_dropdown', 'global') . '>' . __('Use global settings', 'ct-sgp') . '</option>
                   <option value="individual_margins" ' . $this->getSelected($post_id, 'wc_size_guide_button_margins_dropdown', 'individual_margins') . '>' . __('Use individual settings', 'ct-sgp') . '</option>
                   </select>
                   <small>' . __("Chose whether to use global or individual option to set button/link margins.", 'ct-sgp') . '</small></div>'; ?>
            <script>
                (function ($) {
                    $(document).ready(function () {

                        $("#wc_size_guide_button_margins_dropdown option:selected").each(function () {
                            var selected = $(this).val();
                            if (selected == 'individual_margins') {
                                $("#individual_margins_chosen").css({"display": "block"});
                                var input_left = $("#individual_margins_left").val();

                                if (input_left == 'global') {
                                    $("#individual_margins_left").val('');
                                    $("#individual_margins_top").val('');
                                    $("#individual_margins_right").val('');
                                    $("#individual_margins_bottom").val('');
                                }
                            } else {
                                $("#individual_margins_chosen").css({"display": "none"});
                                $("#individual_margins_left").val('global');
                                $("#individual_margins_top").val('global');
                                $("#individual_margins_right").val('global');
                                $("#individual_margins_bottom").val('global');
                            }
                        });

                        $("#wc_size_guide_button_margins_dropdown").change(function () {
                            var selected = $(this).val();

                            if (selected == 'individual_margins') {
                                $("#individual_margins_chosen").css({"display": "block"});
                                var input_left = $("#individual_margin_left").val();
                                if (input_left == 'global') {
                                    $("#individual_margin_left").val('');
                                    $("#individual_margin_top").val('');
                                    $("#individual_margin_right").val('');
                                    $("#individual_margin_bottom").val('');
                                }
                            } else {
                                $("#individual_margins_chosen").css({"display": "none"});
                                $("#individual_margin_left").val('global');
                                $("#individual_margin_top").val('global');
                                $("#individual_margin_right").val('global');
                                $("#individual_margin_bottom").val('global');
                            }
                        });
                    });
                })(jQuery);
            </script>

            <?php
            echo '
               <div class="sg-single-setting" id="individual_margins_chosen" style="display:none">

               <div class="ct-number-input"><input type="text" value="' . $this->getNumberValue($post_id, 'wc_size_guide_button_margin_left', 'global') . '" name="size_guide_settings[wc_size_guide_button_margin_left]" class="chosen_input" id="individual_margin_left" placeholder="0"><span>' . __('Margin left', 'ct-sgp') . '</span></div>
               <div class="ct-number-input"><input type="text" value="' . $this->getNumberValue($post_id, 'wc_size_guide_button_margin_top', 'global') . '" name="size_guide_settings[wc_size_guide_button_margin_top]" class="chosen_input" id="individual_margin_top" placeholder="0"><span>' . __('Margin top', 'ct-sgp') . '</span></div>
               <div class="ct-number-input"><input type="text" value="' . $this->getNumberValue($post_id, 'wc_size_guide_button_margin_right', 'global') . '" name="size_guide_settings[wc_size_guide_button_margin_right]" class="chosen_input" id="individual_margin_right" placeholder="0"><span>' . __('Margin right', 'ct-sgp') . '</span></div>
               <div class="ct-number-input"><input type="text" value="' . $this->getNumberValue($post_id, 'wc_size_guide_button_margin_bottom', 'global') . '" name="size_guide_settings[wc_size_guide_button_margin_bottom]" class="chosen_input" id="individual_margin_bottom" placeholder="0"><span>' . __('Margin bottom', 'ct-sgp') . '</span></div>
               <div>&nbsp;</div></div>';
            ?>
        </div>
        <?php
        //If there is not changed color, it takes global color which is black.
        $globalColor = new ctSizeGuideSettings();
        $globalColor = $globalColor->wcSizeGuidePopupOverlayColorGlobal();
        echo '<div class="sg-single-setting"><label>' . __('Popup overlay color:', 'ct-sgp') . '</label><input type="text" name="size_guide_settings[wc_size_guide_overlay_color]" class="ct-sg-color" value="' . $this->getNumberValue($post_id, 'wc_size_guide_overlay_color', $globalColor) . '"><small>' . __('Click to pick the color of the popup background overlay. Get global option by clicking clear and update', 'ct-sgp') . '</small></div>';

        ?>
        <div>
            <?php
            echo '<div class="sg-single-setting"><label>' . __('Popup window content paddings:', 'ct-sgp') . '</label>
        <select name="size_guide_settings[wc_size_guide_modal_padding_dropdown]" class="chosen_select" id="wc_size_guide_modal_padding_dropdown">
                 <option value="global" ' . $this->getSelected($post_id, 'wc_size_guide_modal_padding_dropdown', 'global') . '>' . __('Use global settings', 'ct-sgp') . '</option>
                 <option value="individual_paddings" ' . $this->getSelected($post_id, 'wc_size_guide_modal_padding_dropdown', 'individual_paddings') . '>' . __('Use individual settings', 'ct-sgp') . '</option>
                 </select>
                 <small>' . __("Chose whether to use global or individual option to set popup window content paddings.", 'ct-sgp') . '</small></div>'; ?>

            <script>
                (function ($) {
                    $(document).ready(function () {

                        $("#wc_size_guide_modal_padding_dropdown option:selected").each(function () {
                            var selected = $(this).val();
                            if (selected == 'individual_paddings') {
                                $("#individual_paddings_chosen").css({"display": "block"});
                                var input_left;
                                input_left = $("#individual_padding_left").val();
                                if (input_left == 'global') {
                                    $("#individual_padding_left").val('');
                                    $("#individual_padding_top").val('');
                                    $("#individual_padding_right").val('');
                                    $("#individual_padding_bottom").val('');
                                }
                            } else {
                                $("#individual_paddings_chosen").css({"display": "none"});
                                $("#individual_padding_left").val('global');
                                $("#individual_padding_top").val('global');
                                $("#individual_padding_right").val('global');
                                $("#individual_padding_bottom").val('global');
                            }
                        });

                        $("#wc_size_guide_modal_padding_dropdown").change(function () {
                            var selected = $(this).val();

                            if (selected == 'individual_paddings') {
                                $("#individual_paddings_chosen").css({"display": "block"});
                                var input_left;
                                input_left = $("#individual_padding_left").val();
                                if (input_left == 'global') {
                                    $("#individual_padding_left").val('');
                                    $("#individual_padding_top").val('');
                                    $("#individual_padding_right").val('');
                                    $("#individual_padding_bottom").val('');
                                }
                            } else {
                                $("#individual_paddings_chosen").css({"display": "none"});
                                $("#individual_padding_left").val('global');
                                $("#individual_padding_top").val('global');
                                $("#individual_padding_right").val('global');
                                $("#individual_padding_bottom").val('global');
                            }
                        });
                    });
                })(jQuery);
            </script>

            <?php
            echo '
        <div class="sg-single-setting" id="individual_paddings_chosen" style="display:none">
        <div class="ct-number-input"><input type="text" value="' . $this->getNumberValue($post_id, 'wc_size_guide_modal_padding_left', 'global') . '" name="size_guide_settings[wc_size_guide_modal_padding_left]" class="chosen_input" id="individual_padding_left"><span>' . __('Padding left', 'ct-sgp') . '</span></div>
        <div class="ct-number-input"><input type="text" value="' . $this->getNumberValue($post_id, 'wc_size_guide_modal_padding_top', 'global') . '" name="size_guide_settings[wc_size_guide_modal_padding_top]" class="chosen_input" id="individual_padding_top"><span>' . __('Padding top', 'ct-sgp') . '</span></div>
        <div class="ct-number-input"><input type="text" value="' . $this->getNumberValue($post_id, 'wc_size_guide_modal_padding_right', 'global') . '" name="size_guide_settings[wc_size_guide_modal_padding_right]" class="chosen_input" id="individual_padding_right"><span>' . __('Padding right', 'ct-sgp') . '</span></div>
        <div class="ct-number-input"><input type="text" value="' . $this->getNumberValue($post_id, 'wc_size_guide_modal_padding_bottom', 'global') . '" name="size_guide_settings[wc_size_guide_modal_padding_bottom]" class="chosen_input" id="individual_padding_bottom"><span>' . __('Padding bottom', 'ct-sgp') . '</span></div>
        <div>&nbsp;</div></div>';
            ?>

        </div>

        <?php
    }

    /**
     * Gets the post - size guide's settings input values if there was selected individual option. If the post - size guide, is a new one, then it takes values from global options
     * @param $id - post/size guide's id
     * @param $opt - key name - option
     * @param string $default - default value - now it is global option from Woocommerce Settings
     * @return mixed|string|void
     */

    protected function getNumberValue($id, $opt, $default = 'null')
    {
        if ($id != 'new') {
            $val = get_post_meta($id, '_ct_sizeguidesettings');

            if ($val === '' || !isset($val[0])) {           //If settings are empty or are not set, every option takes global value
                $val = get_option($opt, $default);
            } else {                                        //Option takes inputed value
                $val = $val[0];
                $val = $val[$opt];

                if ($val == "" || !isset($val)) {
                    $val = $default;
                    if ($default == 'global') {
                        $val = get_option($opt, $default);
                    }
                }
            }
        } else {                                            //If it's new post - size guide, it takes default value
            $val = get_option($opt, $default);
        }

        return $val;
    }

    /**
     * Gets the post - size guide's settings. Checkes whether selected options of size guides are global or individual.
     * @param $id - post/size guide's id
     * @param $opt - key name - option
     * @param $val - value of selected option
     * @return string - 'selected="selected"' or empty string
     */

    protected function getSelected($id, $opt, $val)
    {
        $selected = '';                                         //Default $selected is empty
        if ($id != 'new') {
            $a = get_post_meta($id, '_ct_sizeguidesettings');

            if (!isset($a[0])) {                                //If there is no value in key, it returns empty string
                return '';
            }
            $a = $a[0];
            @$a = $a[$opt];                                      //$a[0][$opt] ex. a[0]['wc_size_guide_button_label'] => string 'Size Guide' or 'global'

            if ($a == $val) {                                   //If $a ex. 'wc_size_guide_button_label' == third value from called method, means that this option is selected
                $selected = 'selected="selected"';
            }

        } else {                                                //If it is new post - size guide, checks whether third value from called method == 'global', if yes, means that this option is selected
            if ($val == 'global') {
                $selected = 'selected="selected"';
            }
        }
        return $selected;
    }

    /**
     * Store data
     *
     * @param $post_id
     */

    public function saveSizeGuideTable($post_id)
    {
        if (!isset($_POST['size_guide_meta_box_nonce']) || !isset($_POST['size_guide_settings_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['size_guide_meta_box_nonce'], 'size_guide_meta_box') || !wp_verify_nonce($_POST['size_guide_settings_meta_box_nonce'], 'size_guide_settings_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['size_guide']) && 'page' == $_POST['size_guide']) {

            if (!current_user_can('edit_page', $post_id)) {
                return;
            }

        } else {

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        if (!isset($_POST['ct_size_guide'])) {
            return;
        }

        $sizeguide = $_POST['ct_size_guide'];
        $sgsettings = $_POST['size_guide_settings'];

	    foreach ( $sizeguide as $key => $val ) {
		    $sizeguide[$key]['table'] = json_decode(stripslashes($sizeguide[$key]['table']));
	    }

	    update_post_meta($post_id, '_ct_sizeguide', $sizeguide);
        update_post_meta($post_id, '_ct_sizeguidesettings', $sgsettings);
    }
}

new ctSizeGuideTable();