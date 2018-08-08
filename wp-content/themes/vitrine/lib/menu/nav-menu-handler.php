<?php 
require_once(ABSPATH . 'wp-admin/includes/nav-menu.php');

//Hanler of wp admin menu-nav output
class epico_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit 
{

    function start_el(&$output, $object, $depth=0, $args= array() , $current_object_id = 0) {

        $item_output = '';

        parent::start_el($item_output, $object, $depth, $args, $current_object_id);

        $new_fields = apply_filters( 'nav_menu_item_additional_fields','', $item_output, $object, $depth, $args, $current_object_id);

        // Inject $new_fields before: <div class="menu-item-actions description-wide submitbox">
        if ($new_fields) {
            $item_output = preg_replace('/(?=<fieldset[^>]+class="[^"]*field-move)/', $new_fields, $item_output);
        }

        $output .= $item_output;

    }

}