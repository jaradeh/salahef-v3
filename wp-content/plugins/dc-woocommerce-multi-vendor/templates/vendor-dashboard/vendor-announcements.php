<?php
/**
 * The template for displaying vendor dashboard
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/vendor-announcements.php
 *
 * @author 		WC Marketplace
 * @package 	WCMp/Templates
 * @version   2.3.0
 */
global $WCMp;
?>
<div class="col-md-12">
<input type="hidden" name="wcmp_msg_tab_to_be_refrash" id="wcmp_msg_tab_to_be_refrash" value="" />
<input type="hidden" name="wcmp_msg_tab_to_be_refrash2" id="wcmp_msg_tab_to_be_refrash2" value="" />
<input type="hidden" name="wcmp_msg_tab_to_be_refrash3" id="wcmp_msg_tab_to_be_refrash3" value="" />
<div id = "tabs-1">
    <ul class="wcmp_msg_tab_nav">
        <li data-element="_all"><a href = "#wcmp_msg_tab_1"><?php _e('All', 'dc-woocommerce-multi-vendor'); ?></a></li>
        <li data-element="_read"><a href = "#wcmp_msg_tab_2"><?php _e('Read', 'dc-woocommerce-multi-vendor'); ?></a></li>
        <li data-element="_unread" ><a href = "#wcmp_msg_tab_3"><?php _e('Unread', 'dc-woocommerce-multi-vendor'); ?></a></li>
        <li data-element="_archive"><a href = "#wcmp_msg_tab_4"><?php _e('Trash', 'dc-woocommerce-multi-vendor'); ?></a></li>
    </ul>
    <!--...................... start tab1 .......................... -->
    <div id = "wcmp_msg_tab_1" data-element="_all">
        <div class="msg_container" >			
            <?php
            if(isset($vendor_announcements['all'])){
                $all = $vendor_announcements['all'];
            }else{
                $all = array();
            }
            //show all messages
            $WCMp->template->get_template('vendor-dashboard/vendor-announcements/vendor-announcements-all.php',array('posts_array'=>$all));
            ?>			
        </div>
    </div>
    <!--...................... end of tab1 .......................... -->
    <!--...................... start tab2 .......................... -->
    <div id = "wcmp_msg_tab_2" data-element="_read">
        <div class="msg_container" >							
            <?php
            if(isset($vendor_announcements['read'])){
                $read = $vendor_announcements['read'];
            }else{
                $read = array();
            }
            //show read messages
            $WCMp->template->get_template('vendor-dashboard/vendor-announcements/vendor-announcements-read.php',array('posts_array'=>$read));
            ?>			
        </div>
    </div>
    <!--...................... end of tab2 .......................... -->
    <!--...................... start tab3 .......................... -->
    <div id = "wcmp_msg_tab_3" data-element="_unread">
        <div class="msg_container" >				
            <?php
            if(isset($vendor_announcements['unread'])){
                $unread = $vendor_announcements['unread'];
            }else{
                $unread = array();
            }
            //show unread messages
            $WCMp->template->get_template('vendor-dashboard/vendor-announcements/vendor-announcements-unread.php',array('posts_array'=>$unread));
            ?>				
        </div>
    </div>
    <!--...................... end of tab3 .......................... -->
    <!--...................... start tab4 .......................... -->
    <div id = "wcmp_msg_tab_4" data-element="_archive">
        <div class="msg_container">				
            <?php
            if(isset($vendor_announcements['deleted'])){
                $deleted = $vendor_announcements['deleted'];
            }else{
                $deleted = array();
            }
            //show unread messages
            $WCMp->template->get_template('vendor-dashboard/vendor-announcements/vendor-announcements-archive.php',array('posts_array'=>$deleted));
            ?>				
        </div>
    </div>
    <!--...................... end of tab4 .......................... -->
</div>
</div>