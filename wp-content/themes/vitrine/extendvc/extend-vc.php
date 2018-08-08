<?php

    function epico_vc_enqueue_main_css_forever() {
        wp_enqueue_style('js_composer_front');
    }

    add_action('wp_enqueue_scripts', 'epico_vc_enqueue_main_css_forever');

    function epico_vc_icons_field ($settings, $value) {

        $icons = array('home','home2','home3','home4','home5','home6','bathtub','toothbrush','bed','couch','chair','city','apartment','pencil','pencil2','pen','pencil3','eraser','pencil4','pencil5','feather','feather2','feather3','pen2','pen-add','pen-remove','vector','pen3','blog','brush','brush2','spray','paint-roller','stamp','tape','desk-tape','texture','eye-dropper','palette','color-sampler','bucket','gradient','gradient2','magic-wand','magnet','pencil-ruler','pencil-ruler2','compass','aim','gun','bottle','drop','drop-crossed','drop2','snow','snow2','fire','lighter','knife','dagger','tissue','toilet-paper','poop','umbrella','umbrella2','rain','tornado','wind','fan','contrast','sun-small','sun','sun2','moon','cloud','cloud-upload','cloud-download','cloud-rain','cloud-hailstones','cloud-snow','cloud-windy','sun-wind','cloud-fog','cloud-sun','cloud-lightning','cloud-sync','cloud-lock','cloud-gear','cloud-alert','cloud-check','cloud-cross','cloud-crossed','cloud-database','database','database-add','database-remove','database-lock','database-refresh','database-check','database-history','database-upload','database-download','server','shield','shield-check','shield-alert','shield-cross','lock','rotation-lock','unlock','key','key-hole','toggle-off','toggle-on','cog','cog2','wrench','screwdriver','hammer-wrench','hammer','saw','axe','axe2','shovel','pickaxe','factory','factory2','recycle','trash','trash2','trash3','broom','game','gamepad','joystick','dice','spades','diamonds','clubs','hearts','heart','star','star-half','star-empty','flag','flag2','flag3','mailbox-full','mailbox-empty','at-sign','envelope','envelope-open','paperclip','paper-plane','reply','reply-all','inbox','inbox2','outbox','box','archive','archive2','drawers','drawers2','drawers3','eye','eye-crossed','eye-plus','eye-minus','binoculars','binoculars2','hdd','hdd-down','hdd-up','floppy-disk','disc','tape2','printer','shredder','file-empty','file-add','file-check','file-lock','files','copy','compare','folder','folder-search','folder-plus','folder-minus','folder-download','folder-upload','folder-star','folder-heart','folder-user','folder-shared','folder-music','folder-picture','folder-film','scissors','paste','clipboard-empty','clipboard-pencil','clipboard-text','clipboard-check','clipboard-down','clipboard-left','clipboard-alert','clipboard-user','register','enter','exit','papers','news','reading','typewriter','document','document2','graduation-hat','license','license2','medal-empty','medal-first','medal-second','medal-third','podium','trophy','trophy2','music-note','music-note2','music-note3','playlist','playlist-add','guitar','trumpet','album','shuffle','repeat-one','repeat','headphones','headset','loudspeaker','equalizer','theater','3d-glasses','ticket','presentation','play','film-play','clapboard-play','media','film','film2','surveillance','surveillance2','camera','camera-crossed','camera-play','time-lapse','record','camera2','camera-flip','panorama','time-lapse2','shutter','shutter2','face-detection','flare','convex','concave','picture','picture2','picture3','pictures','book','audio-book','book2','bookmark','bookmark2','label','library','library2','contacts','profile','portrait','portrait2','user','user-plus','user-minus','user-lock','users','users2','users-plus','users-minus','group-work','woman','man','baby','baby2','baby3','baby-bottle','walk','hand-waving','jump','run','woman2','man2','man-woman','height','weight','scale','button','bow-tie','tie','socks','shoe','shoes','hat','pants','shorts','flip-flops','shirt','hanger','laundry','store','haircut','store-24','barcode','barcode2','barcode3','cashier','bag','bag2','cart','cart-empty','cart-full','cart-plus','cart-plus2','cart-add','cart-remove','cart-exchange','tag','tags','receipt','wallet','credit-card','cash-dollar','cash-euro','cash-pound','cash-yen','bag-dollar','bag-euro','bag-pound','bag-yen','coin-dollar','coin-euro','coin-pound','coin-yen','calculator','calculator2','abacus','vault','telephone','phone-lock','phone-wave','phone-pause','phone-outgoing','phone-incoming','phone-in-out','phone-error','phone-sip','phone-plus','phone-minus','voicemail','dial','telephone2','pushpin','pushpin2','map-marker','map-marker-user','map-marker-down','map-marker-check','map-marker-crossed','radar','compass2','map','map2','location','road-sign','calendar-empty','calendar-check','calendar-cross','calendar-31','calendar-full','calendar-insert','calendar-text','calendar-user','mouse','mouse-left','mouse-right','mouse-both','keyboard','keyboard-up','keyboard-down','delete','spell-check','escape','enter2','screen','aspect-ratio','signal','signal-lock','signal-80','signal-60','signal-40','signal-20','signal-0','signal-blocked','sim','flash-memory','usb-drive','phone','smartphone','smartphone-notification','smartphone-vibration','smartphone-embed','smartphone-waves','tablet','tablet2','laptop','laptop-phone','desktop','launch','new-tab','window','cable','cable2','tv','radio','remote-control','power-switch','power','power-crossed','flash-auto','lamp','flashlight','lampshade','cord','outlet','battery-power','battery-empty','battery-alert','battery-error','battery-low1','battery-low2','battery-low3','battery-mid1','battery-mid2','battery-mid3','battery-full','battery-charging','battery-charging2','battery-charging3','battery-charging4','battery-charging5','battery-charging6','battery-charging7','chip','chip-x64','chip-x86','bubble','bubbles','bubble-dots','bubble-alert','bubble-question','bubble-text','bubble-pencil','bubble-picture','bubble-video','bubble-user','bubble-quote','bubble-heart','bubble-emoticon','bubble-attachment','phone-bubble','quote-open','quote-close','dna','heart-pulse','pulse','syringe','pills','first-aid','lifebuoy','bandage','bandages','thermometer','microscope','brain','beaker','skull','bone','construction','construction-cone','pie-chart','pie-chart2','graph','chart-growth','chart-bars','chart-settings','cake','gift','balloon','rank','rank2','rank3','crown','lotus','diamond','diamond2','diamond3','diamond4','linearicons','teacup','teapot','glass','bottle2','glass-cocktail','glass2','dinner','dinner2','chef','scale2','egg','egg2','eggs','platter','steak','hamburger','hotdog','pizza','sausage','chicken','fish','carrot','cheese','bread','ice-cream','ice-cream2','candy','lollipop','coffee-bean','coffee-cup','cherry','grapes','citrus','apple','leaf','landscape','pine-tree','tree','cactus','paw','footprint','speed-slow','speed-medium','speed-fast','rocket','hammer2','balance','briefcase','luggage-weight','dolly','plane','plane-crossed','helicopter','traffic-lights','siren','road','engine','oil-pressure','coolant-temperature','car-battery','gas','gallon','transmission','car','car-wash','car-wash2','bus','bus2','car2','parking','car-lock','taxi','car-siren','car-wash3','car-wash4','ambulance','truck','trailer','scale-truck','train','ship','ship2','anchor','boat','bicycle','bicycle2','dumbbell','bench-press','swim','football','baseball-bat','baseball','tennis','tennis2','ping-pong','hockey','8ball','bowling','bowling-pins','golf','golf2','archery','slingshot','soccer','basketball','cube','3d-rotate','puzzle','glasses','glasses2','accessibility','wheelchair','wall','fence','wall2','icons','resize-handle','icons2','select','select2','site-map','earth','earth-lock','network','network-lock','planet','happy','smile','grin','tongue','sad','wink','dream','shocked','shocked2','tongue2','neutral','happy-grin','cool','mad','grin-evil','evil','wow','annoyed','wondering','confused','zipped','grumpy','mustache','tombstone-hipster','tombstone','ghost','ghost-hipster','halloween','christmas','easter-egg','mustache2','mustache-glasses','pipe','alarm','alarm-add','alarm-snooze','alarm-ringing','bullhorn','hearing','volume-high','volume-medium','volume-low','volume','mute','lan','lan2','wifi','wifi-lock','wifi-blocked','wifi-mid','wifi-low','wifi-low2','wifi-alert','wifi-alert-mid','wifi-alert-low','wifi-alert-low2','stream','stream-check','stream-error','stream-alert','communication','communication-crossed','broadcast','antenna','satellite','satellite2','mic','mic-mute','mic2','spotlights','hourglass','loading','loading2','loading3','refresh','refresh2','undo','redo','jump2','undo2','redo2','sync','repeat-one2','sync-crossed','sync2','repeat-one3','sync-crossed2','return','return2','refund','history','history2','self-timer','clock','clock2','clock3','watch','alarm2','alarm-add2','alarm-remove','alarm-check','alarm-error','timer','timer-crossed','timer2','timer-crossed2','download','upload','download2','upload2','enter-up','enter-down','enter-left','enter-right','exit-up','exit-down','exit-left','exit-right','enter-up2','enter-down2','enter-vertical','enter-left2','enter-right2','enter-horizontal','exit-up2','exit-down2','exit-left2','exit-right2','cli','bug','code','file-code','file-image','file-zip','file-audio','file-video','file-preview','file-charts','file-stats','file-spreadsheet','link','unlink','link2','unlink2','thumbs-up','thumbs-down','thumbs-up2','thumbs-down2','thumbs-up3','thumbs-down3','share','share2','share3','magnifier','file-search','find-replace','zoom-in','zoom-out','loupe','loupe-zoom-in','loupe-zoom-out','cross','menu','list','list2','list3','menu2','list4','menu3','exclamation','question','check','cross2','plus','minus','percent','chevron-up','chevron-down','chevron-left','chevron-right','chevrons-expand-vertical','chevrons-expand-horizontal','chevrons-contract-vertical','chevrons-contract-horizontal','arrow-up','arrow-down','arrow-left','arrow-right','arrow-up-right','arrows-merge','arrows-split','arrow-divert','arrow-return','expand','contract','expand2','contract2','move','tab','arrow-wave','expand3','expand4','contract3','notification','warning','notification-circle','question-circle','menu-circle','checkmark-circle','cross-circle','plus-circle','circle-minus','percent-circle','arrow-up-circle','arrow-down-circle','arrow-left-circle','arrow-right-circle','chevron-up-circle','chevron-down-circle','chevron-left-circle','chevron-right-circle','backward-circle','first-circle','previous-circle','stop-circle','play-circle','pause-circle','next-circle','last-circle','forward-circle','eject-circle','crop','frame-expand','frame-contract','focus','transform','grid','grid-crossed','layers','layers-crossed','toggle','rulers','ruler','funnel','flip-horizontal','flip-vertical','flip-horizontal2','flip-vertical2','angle','angle2','subtract','combine','intersect','exclude','align-center-vertical','align-right','align-bottom','align-left','align-center-horizontal','align-top','square','plus-square','minus-square','percent-square','arrow-up-square','arrow-down-square','arrow-left-square','arrow-right-square','chevron-up-square','chevron-down-square','chevron-left-square','chevron-right-square','check-square','cross-square','menu-square','prohibited','circle','radio-button','ligature','text-format','text-format-remove','text-size','bold','italic','underline','strikethrough','highlight','text-align-left','text-align-center','text-align-right','text-align-justify','line-spacing','indent-increase','indent-decrease','text-wrap','pilcrow','direction-ltr','direction-rtl','page-break','page-break2','sort-alpha-asc','sort-alpha-desc','sort-numeric-asc','sort-numeric-desc','sort-amount-asc','sort-amount-desc','sort-time-asc','sort-time-desc','sigma','pencil-line','hand','pointer-up','pointer-right','pointer-down','pointer-left','finger-tap','fingers-tap','reminder','fingers-crossed','fingers-victory','gesture-zoom','gesture-pinch','fingers-scroll-horizontal','fingers-scroll-vertical','fingers-scroll-left','fingers-scroll-right','hand2','pointer-up2','pointer-right2','pointer-down2','pointer-left2','finger-tap2','fingers-tap2','reminder2','gesture-zoom2','gesture-pinch2','fingers-scroll-horizontal2','fingers-scroll-vertical2','fingers-scroll-left2','fingers-scroll-right2','fingers-scroll-vertical3','border-style','border-all','border-outer','border-inner','border-top','border-horizontal','border-bottom','border-left','border-vertical','border-right','border-none','ellipsis',
        'asterisk','plus2','question2','minus2','glass3','music','search','envelope-o','heart2','star2','star-o','user2','film3','th-large','th','th-list','check2','close','remove','times','search-plus','search-minus','power-off','signal2','cog3','gear','trash-o','home7','file-o','clock-o','road2','download3','arrow-circle-o-down','arrow-circle-o-up','inbox3','play-circle-o','repeat2','rotate-right','refresh3','list-alt','lock2','flag4','headphones2','volume-off','volume-down','volume-up','qrcode','barcode4','tag2','tags2','book3','bookmark3','print','camera3','font','bold2','italic2','text-height','text-width','align-left2','align-center','align-right2','align-justify','list5','dedent','outdent','indent','video-camera','image','photo','picture-o','pencil6','map-marker2','adjust','tint','edit','pencil-square-o','share-square-o','check-square-o','arrows','step-backward','fast-backward','backward','play2','pause','stop','forward','fast-forward','step-forward','eject','chevron-left2','chevron-right2','plus-circle2','minus-circle','times-circle','check-circle','question-circle2','info-circle','crosshairs','times-circle-o','check-circle-o','arrow-left2','arrow-right2','arrow-up2','arrow-down2','mail-forward','share4','expand5','compress','exclamation-circle','gift2','leaf2','fire2','eye2','eye-slash','exclamation-triangle','warning2','plane2','calendar','random','comment','magnet2','chevron-up2','chevron-down2','retweet','shopping-cart','folder2','folder-open','arrows-v','arrows-h','bar-chart','bar-chart-o','twitter-square','facebook-square','camera-retro','key2','cogs','gears','comments','thumbs-o-up','thumbs-o-down','star-half2','heart-o','sign-out','linkedin-square','thumb-tack','external-link','sign-in','trophy3','github-square','upload3','lemon-o','phone2','square-o','bookmark-o','phone-square','twitter','facebook','facebook-f','github','unlock2','credit-card2','feed','rss','hdd-o','bullhorn2','bell-o','certificate','hand-o-right','hand-o-left','hand-o-up','hand-o-down','arrow-circle-left','arrow-circle-right','arrow-circle-up','arrow-circle-down','globe','wrench2','tasks','filter','briefcase2','arrows-alt','group','users3','chain','link3','cloud2','flask','cut','scissors2','copy2','files-o','paperclip2','floppy-o','save','square2','bars','navicon','reorder','list-ul','list-ol','strikethrough2','underline2','table','magic','truck2','pinterest','pinterest-square','google-plus-square','google-plus','money','caret-down','caret-up','caret-left','caret-right','columns','sort','unsorted','sort-desc','sort-down','sort-asc','sort-up','envelope2','linkedin','rotate-left','undo3','gavel','legal','dashboard','tachometer','comment-o','comments-o','bolt','flash','sitemap','umbrella3','clipboard','paste2','lightbulb-o','exchange','cloud-download2','cloud-upload2','user-md','stethoscope','suitcase','bell','coffee','cutlery','file-text-o','building-o','hospital-o','ambulance2','medkit','fighter-jet','beer','h-square','plus-square2','angle-double-left','angle-double-right','angle-double-up','angle-double-down','angle-left','angle-right','angle-up','angle-down','desktop2','laptop2','tablet3','mobile','mobile-phone','circle-o','quote-left','quote-right','spinner','circle2','mail-reply','reply2','github-alt','folder-o','folder-open-o','smile-o','frown-o','meh-o','gamepad2','keyboard-o','flag-o','flag-checkered','terminal','code2','mail-reply-all','reply-all2','star-half-empty','star-half-full','star-half-o','location-arrow','crop2','code-fork','chain-broken','unlink3','info','exclamation2','superscript','subscript','eraser2','puzzle-piece','microphone','microphone-slash','shield2','calendar-o','fire-extinguisher','rocket2','maxcdn','chevron-circle-left','chevron-circle-right','chevron-circle-up','chevron-circle-down','html5','css3','anchor2','unlock-alt','bullseye','ellipsis-h','ellipsis-v','rss-square','play-circle2','ticket2','minus-square2','minus-square-o','level-up','level-down','check-square2','pencil-square','external-link-square','share-square','compass3','caret-square-o-down','toggle-down','caret-square-o-up','toggle-up','caret-square-o-right','toggle-right','eur','euro','gbp','dollar','usd','inr','rupee','cny','jpy','rmb','yen','rouble','rub','ruble','krw','won','bitcoin','btc','file','file-text','sort-alpha-asc2','sort-alpha-desc2','sort-amount-asc2','sort-amount-desc2','sort-numeric-asc2','sort-numeric-desc2','thumbs-up4','thumbs-down4','youtube-square','youtube','xing','xing-square','youtube-play','dropbox','stack-overflow','instagram','flickr','adn','bitbucket','bitbucket-square','tumblr','tumblr-square','long-arrow-down','long-arrow-up','long-arrow-left','long-arrow-right','apple2','windows','android','linux','dribbble','skype','foursquare','trello','female','male','gittip','gratipay','sun-o','moon-o','archive3','bug2','vk','weibo','renren','pagelines','stack-exchange','arrow-circle-o-right','arrow-circle-o-left','caret-square-o-left','toggle-left','dot-circle-o','wheelchair2','vimeo-square','try','turkish-lira','plus-square-o','space-shuttle','slack','envelope-square','wordpress','openid','bank','institution','university','graduation-cap','mortar-board','yahoo','google','reddit','reddit-square','stumbleupon-circle','stumbleupon','delicious','digg','pied-piper-pp','pied-piper-alt','drupal','joomla','language','fax','building','child','paw2','spoon','cube2','cubes','behance','behance-square','steam','steam-square','recycle2','automobile','car3','cab','taxi2','tree2','spotify','deviantart','soundcloud','database2','file-pdf-o','file-word-o','file-excel-o','file-powerpoint-o','file-image-o','file-photo-o','file-picture-o','file-archive-o','file-zip-o','file-audio-o','file-sound-o','file-movie-o','file-video-o','file-code-o','vine','codepen','jsfiddle','life-bouy','life-buoy','life-ring','life-saver','support','circle-o-notch','ra','rebel','resistance','empire','ge','git-square','git','hacker-news','y-combinator-square','yc-square','tencent-weibo','qq','wechat','weixin','paper-plane2','send','paper-plane-o','send-o','history3','circle-thin','header','paragraph','sliders','share-alt','share-alt-square','bomb','futbol-o','soccer-ball-o','tty','binoculars3','plug','slideshare','twitch','yelp','newspaper-o','wifi2','calculator3',' paypal','google-wallet','cc-visa','cc-mastercard','cc-discover','cc-amex','cc-paypal','cc-stripe','bell-slash','bell-slash-o','trash4','copyright','at','eyedropper','paint-brush','birthday-cake','area-chart','pie-chart3','line-chart','lastfm','lastfm-square','toggle-off2','toggle-on2','bicycle3','bus3','ioxhost','angellist','cc','ils','shekel','sheqel','meanpath','buysellads','connectdevelop','dashcube','forumbee','leanpub','sellsy','shirtsinbulk','simplybuilt','skyatlas','cart-plus3','cart-arrow-down','diamond5','ship3','user-secret','motorcycle','street-view','heartbeat','venus','mars','mercury','intersex','transgender','transgender-alt','venus-double','mars-double','venus-mars','mars-stroke','mars-stroke-v','mars-stroke-h','neuter','genderless','facebook-official','pinterest-p','whatsapp','server2','user-plus2','user-times','bed2','hotel','viacoin','train2','subway','medium','y-combinator','yc','optin-monster','opencart','expeditedssl','battery','battery-4','battery-full2','battery-3','battery-three-quarters','battery-2','battery-half','battery-1','battery-quarter','battery-0','battery-empty2','mouse-pointer','i-cursor','object-group','object-ungroup','sticky-note','sticky-note-o','cc-jcb','cc-diners-club','clone','balance-scale','hourglass-o','hourglass-1','hourglass-start','hourglass-2','hourglass-half','hourglass-3','hourglass-end','hourglass2','hand-grab-o','hand-rock-o','hand-paper-o','hand-stop-o','hand-scissors-o','hand-lizard-o','hand-spock-o','hand-pointer-o','hand-peace-o','trademark','registered','creative-commons','gg','gg-circle','tripadvisor','odnoklassniki','odnoklassniki-square','get-pocket','wikipedia-w','safari','chrome','firefox','opera','internet-explorer','television','tv2','contao','500px','amazon','calendar-plus-o','calendar-minus-o','calendar-times-o','calendar-check-o','industry','map-pin','map-signs','map-o','map3','commenting','commenting-o','houzz','vimeo','black-tie','fonticons','reddit-alien','edge','credit-card-alt','codiepie','modx','fort-awesome','usb','product-hunt','mixcloud','scribd','pause-circle2','pause-circle-o','stop-circle2','stop-circle-o','shopping-bag','shopping-basket','hashtag','bluetooth','bluetooth-b','percent2','gitlab','wpbeginner','wpforms','envira','universal-access','wheelchair-alt','question-circle-o','blind','audio-description','volume-control-phone','braille','assistive-listening-systems','american-sign-language-interpreting','asl-interpreting','deaf','deafness','hard-of-hearing','glide','glide-g','sign-language','signing','low-vision','viadeo','viadeo-square','snapchat','snapchat-ghost','snapchat-square','pied-piper','first-order','yoast','themeisle','google-plus-circle','google-plus-official','fa','font-awesome','handshake-o','envelope-open2','envelope-open-o','linode','address-book','address-book-o','address-card','vcard','address-card-o','vcard-o','user-circle','user-circle-o','user-o','id-badge','drivers-license','id-card','drivers-license-o','id-card-o','quora','free-code-camp','telegram','thermometer2','thermometer-4','thermometer-full','thermometer-3','thermometer-three-quarters','thermometer-2','thermometer-half','thermometer-1','thermometer-quarter','thermometer-0','thermometer-empty','shower','bath','bathtub2','s15','podcast','window-maximize','window-minimize','window-restore','times-rectangle','window-close','times-rectangle-o','window-close-o','bandcamp','grav','etsy','imdb','ravelry','eercast','microchip','snowflake-o','superpowers','wpexplorer','meetup','search2','quotes-right','quotes-left','blogger','brand','social');

        foreach( $icons as $icon ) {
            $icon_fields[] = sprintf('<span class="ep-icon icon-%s" data-name="%s"></span>', esc_attr($icon) , esc_attr($icon) );
        }

       return '<div class="my_param_block ep-icon-container">'
                .implode( $icon_fields )
                .'<input type="text" name="'.esc_attr($settings['param_name'])
                .'" class="wpb_vc_param_value wpb-textinput ep-input-vc-icon hidden-field-value '
                .esc_attr($settings['param_name']).' '.esc_attr($settings['type']).'_field" type="text" value="'. esc_attr($value) .'" />'
             .'</div>';
    }

    function epico_vc_date_field ($settings, $value) {

        return '<div>'
            .'<div class="label">'. esc_attr($settings['label']).'</div>'
                .'<input type="datetime-local" name="'.esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-textinput ' .
             esc_attr( $settings['param_name'] ) . ' ' .
             esc_attr( $settings['type'] ) . '_field" value="'. esc_attr($value) .'" />'
               
            .'</div>';
    }

    function epico_vc_imageselect_field ($settings, $value) {
       $options = $settings['value'];
        
        foreach( $options as $optionkey => $optionvalue ) {
            $image_options[] = sprintf('<span class="ep-image image-%s" data-name="%s"><img src="%s.png"></span>', esc_attr($optionvalue) , esc_attr($optionvalue) , esc_url(get_template_directory_uri() . '/lib/admin/img/vcimages/' . $optionkey) );
        }

       return '<div class="my_param_block ep-imageselect-container ' . (array_key_exists('class',$settings) ? $settings['class'] : '') .'">'
                .implode( $image_options )
                .'<input type="text" name="'.esc_attr($settings['param_name'])
                .'" class="wpb_vc_param_value wpb-textinput ep-input-vc-imageselect hidden-field-value '
                .esc_attr($settings['param_name']).' '.esc_attr($settings['type']).'_field" type="text" value="'. esc_attr($value) .'"/>'
             .'</div>';
    }
    
    function epico_vc_range_field ($settings, $value) {

        return '<div class="my_param_block ep-rangefield-container field clear-after">'
            .'<div class="label">'. esc_attr($settings['label']).'</div>'
                .'<input type="range" start="'.esc_attr($settings['min']).'" min="'.esc_attr($settings['min']).'" max="'.esc_attr($settings['max']).'" step="'.esc_attr($settings['step']).'"  value="'. esc_attr($value) .'" />'
                .'<input type="text" name="'.esc_attr($settings['param_name'])
                .'" class="wpb_vc_param_value wpb-textinput ep-input-vc-range hidden-field-value '
                .esc_attr($settings['param_name']).' '.esc_attr($settings['type']) . '_field" type="text" value="'. esc_attr($value) .'"/>'
            .'</div>';
    }

    function epico_vc_multi_select ($settings, $value) {
        $items = $settings['options'];
        $options = array();
        foreach( $items as $optionkey => $optionvalue ) {
            $options[] = sprintf('<input type="checkbox" name="%s" value="%s" class="ep-checkbox-field"> <span class="checkbox-label">%s</span>', $optionvalue , $optionkey , $optionvalue );
        }

        return '<div class="my_param_block ep-checkbox-container field clear-after">'
                .implode( $options )
                .'<input type="text" name="'.esc_attr($settings['param_name'])
                .'" class="wpb_vc_param_value wpb-textinput ep-input-vc-checkbox hidden-field-value '
                .esc_attr($settings['param_name']).' '.esc_attr($settings['type']) . '_field" type="text" value="'. esc_attr($value) .'"/>'
            .'</div>';
    }

	if ( ! function_exists( 'js_composer_bridge_admin' ) ) {
    
		function epico_js_composer_css_admin() {
			if(!isset($_GET['page']) || $_GET['page'] != 'theme_settings')
			{
				// presscore stuff
				wp_enqueue_style( 'epico_vc_extend_css', get_template_directory_uri() . '/lib/admin/css/vc-extend.css');			
			}


		}
	}
    
	add_action( 'admin_enqueue_scripts', 'epico_js_composer_css_admin', 15 );

// Removing frontend editor
if(function_exists('vc_disable_frontend')){
    vc_disable_frontend();
}

// animation array
$animations = array(
	"None" => "none",
	"Fade in" => "fade-in",
	"Fade in from top" => "fade-in-top",
	"Fade in from left" => "fade-in-left",
	"Fade in from right" => "fade-in-right",
	"Fade in from bottom" => "fade-in-bottom",
    "Grow In" => "grow-in"
);

$fontsize = array (
    "20" => "20",
    "24" => "24",
    "32" => "32",
    "40" => "40",
    "48" => "48",
    "60" => "60",
    "80" => "80",
    "100" => "100",
);

$Customfontsize = array (
    "20" => "20",
    "24" => "24",
    "32" => "32",
    "40" => "40",
    "48" => "48",
    "60" => "60",
    "80" => "80",
    "100" => "100",
);

$contentfontsize = array (
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "18" => "18",
    "19" => "19",
    "20" => "20",
    "22" => "22",
    "24" => "24",
);

$contdownFontSize = array (
    "28" => "28",
    "29" => "29",
    "30" => "30",
    "31" => "31",
    "32" => "32",
    "33" => "33",
    "34" => "34",
    "35" => "35",
    "36" => "36",
    "37" => "37",
    "38" => "38",
    "39" => "39",
    "40" => "40",
    "41" => "41",
    "42" => "42",
    "43" => "43",
    "44" => "44",
    "45" => "45",
    "46" => "46",
    "47" => "47",
    "48" => "48",
    "49" => "49",
    "50" => "50",
);

//all of social icons
$socialIcon = array (
    'Facebook' => 'facebook',
    'Twitter' => 'twitter',
    'Vimeo' =>'vimeo',
    'YouTube' => 'youtube-play',
    'Google+' =>'google-plus',
    'Dribbble' =>  'dribbble',
    'Tumblr' => 'tumblr',
    'linkedin' => 'linkedin',
    'Flickr' =>  'flickr',
    //'Forrst' => 'forrst',
    'Github'  => 'github',
    'Last.fm' => 'lastfm',
    'Paypal' => 'paypal',
    'RSS' =>  'feed',
    'Skype' =>  'skype',
    'WordPress' =>  'wordpress',
    'Yahoo' =>  'yahoo',
    'Steam' => 'steam',
    'Reddit' =>  'reddit-alien',
    'StumbleUpon' => 'stumbleupon',
    'Pinterest' => 'pinterest',
    'DeviantArt' => 'deviantart',
    'Xing'  => 'xing',
    'Blogger' => 'blogger',
    'SoundCloud'  => 'soundcloud',
    'Delicious' =>  'delicious',
    'Foursquare'  => 'foursquare',
    'Instagram'  => 'instagram',
    'Behance'  => 'behance',
	'Custom Social Network'=> 'custom',
);

//List of social icons with dark/light option
$socialIconDarkLight = array (
    'facebook',
    'twitter',
    'vimeo',
    'youtube-play',
    'google-plus',
    'dribbble',
    'tumblr',
    'linkedin',
    'flickr',
    'github',
    'lastfm',
    'paypal',
    'feed',
    'skype',
    'wordpress',
    'yahoo',
    'steam',
    'reddit-alien',
    'stumbleupon',
    'pinterest',
    'deviantart',
    'xing',
    'blogger',
    'soundcloud',
    'delicious',
    'foursquare',
    'instagram',
    'behance',
);

$portfolio_skills = array();
$cat_args = array(
    'orderby'       => 'term_id', 
    'order'         => 'ASC',
    'hide_empty'    => false,
);

$terms = get_terms('skills', $cat_args);
if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ // check is portfolio plugin is active or not 
    foreach($terms as $taxonomy) {
         $portfolio_skills[$taxonomy->slug] = $taxonomy->name;
    }
}

//------ Fetch gallery categories-------
$gallery_cats = array();
$cat_args = array(
    'orderby'       => 'term_id', 
    'order'         => 'ASC',
    'hide_empty'    => false,
);

$terms = get_terms('gallery_cat', $cat_args);
if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ // check is gallery plugin is active or not 
    foreach($terms as $taxonomy){
            $gallery_cats[$taxonomy->slug] = $taxonomy->name;
    }
}

//Remove animation from VC shortcodes
vc_remove_param('vc_icon', 'css_animation');
vc_remove_param('vc_separator', 'css_animation');
vc_remove_param('vc_text_separator', 'css_animation');
vc_remove_param('vc_facebook', 'css_animation');
vc_remove_param('vc_tweetmeme', 'css_animation');
vc_remove_param('vc_googleplus', 'css_animation');
vc_remove_param('vc_pinterest', 'css_animation');
vc_remove_param('vc_single_image', 'css_animation');
vc_remove_param('vc_tta_tabs', 'css_animation');
vc_remove_param('vc_tta_tour', 'css_animation');
vc_remove_param('vc_tta_accordion', 'css_animation');
vc_remove_param('vc_tta_accordion', 'css_animation');
vc_remove_param('vc_gmaps', 'css_animation');
vc_remove_param('vc_flickr', 'css_animation');
vc_remove_param('vc_basic_grid', 'css_animation');
vc_remove_param('vc_masonry_grid', 'css_animation');
vc_remove_param('vc_zigzag', 'css_animation');

/*-----------------------------------------------------------------------------------*/
/* Section
/*-----------------------------------------------------------------------------------*/
 // Remove VC Button Element
vc_remove_element( 'vc_section' );

/*-----------------------------------------------------------------------------------*/
/*  hoverbox 
/*-----------------------------------------------------------------------------------*/
 // Remove VC hoverbox  Element
vc_remove_element( 'vc_hoverbox' );

/*-----------------------------------------------------------------------------------*/
/* Pricing box
/*-----------------------------------------------------------------------------------*/
vc_remove_param('mnky_pricing_box', 'css_animation');
vc_remove_param('mnky_pricing_box', 'css_animation_delay');

/*-----------------------------------------------------------------------------------*/
/* Accordion
/*-----------------------------------------------------------------------------------*/

// remove param style
vc_remove_param('vc_tta_accordion' , 'style');

// remove param shape
vc_remove_param('vc_tta_accordion' , 'shape');

// remove param color
vc_remove_param('vc_tta_accordion' , 'color');

// remove param no_fill
vc_remove_param('vc_tta_accordion' , 'no_fill');

// remove param spacing
vc_remove_param('vc_tta_accordion' , 'spacing');

// remove param gap
vc_remove_param('vc_tta_accordion' , 'gap');

// remove param autoplay
vc_remove_param('vc_tta_accordion' , 'autoplay');

// remove param icon
vc_remove_param('vc_tta_accordion' , 'c_icon');

/*-----------------------------------------------------------------------------------*/
/* Tabs
/*-----------------------------------------------------------------------------------*/

// remove param style
vc_remove_param('vc_tta_tabs' , 'style');

// remove param tab
 vc_remove_param('vc_tta_tabs' , 'title'); 

// remove param shape
vc_remove_param('vc_tta_tabs' , 'shape');

// remove param shape
vc_remove_param('vc_tta_tabs' , 'alignment');

// remove param color
vc_remove_param('vc_tta_tabs' , 'color');

// remove param no_fill_content_area
vc_remove_param('vc_tta_tabs' , 'no_fill_content_area');

// remove param spacing
vc_remove_param('vc_tta_tabs' , 'spacing');

// remove param gap
vc_remove_param('vc_tta_tabs' , 'gap');

// remove param pagination_style
vc_remove_param('vc_tta_tabs' , 'pagination_style');

// remove param pagination_color
vc_remove_param('vc_tta_tabs' , 'pagination_color');



vc_add_param("vc_tta_tabs",
	array(
		'type' => 'dropdown',
		'param_name' => 'alignment',
		'value' => array(
			esc_html__( 'Center', 'vitrine' ) => 'center',
			esc_html__( 'Left', 'vitrine' ) => 'left',
			esc_html__( 'Right', 'vitrine' ) => 'right',
		),
		'heading' => esc_html__( 'Alignment', 'vitrine' ),
		'description' => esc_html__( 'Select tabs section title alignment.', 'vitrine' ),
));

vc_add_param("vc_tta_tabs",
    array(
        'type' => 'dropdown',
        'param_name' => 'style',
        'value' => array(
            esc_html__('Dark', 'vitrine')   => 'dark',
           esc_html__( 'Light', 'vitrine')  => 'light',
        ),
		 'heading' => esc_html__('Style', 'vitrine'),
        'description' =>  esc_html__('dark and light style of tab', 'vitrine'),
));
vc_add_param("vc_tta_tabs",
    array(
        'type' => 'dropdown',
        'param_name' => 'shape',
        'value' => array(
            esc_html__( 'Before title', 'vitrine' ) => 'left',
			esc_html__( 'After of title', 'vitrine' ) => 'right',
            esc_html__( 'Top of title', 'vitrine' ) => 'top',
        ),
		 'heading' => esc_html__('Icon position', 'vitrine'),
        'description' =>  esc_html__('Select icon position for tabs section.', 'vitrine'),
));

/*-----------------------------------------------------------------------------------*/
/* Tour
/*-----------------------------------------------------------------------------------*/
// remove param style
vc_remove_param('vc_tta_tour' , 'style');

// remove param shape
vc_remove_param('vc_tta_tour' , 'shape');

// remove param color
vc_remove_param('vc_tta_tour' , 'color');

// remove param no_fill_content_area
vc_remove_param('vc_tta_tour' , 'no_fill_content_area');

// remove param spacing
vc_remove_param('vc_tta_tour' , 'spacing');

// remove param gap
vc_remove_param('vc_tta_tour' , 'gap');

// remove param controls_size
vc_remove_param('vc_tta_tour' , 'controls_size');

// remove param pagination_style
vc_remove_param('vc_tta_tour' , 'pagination_style');

// remove param pagination_color
vc_remove_param('vc_tta_tour' , 'pagination_color');


/*-----------------------------------------------------------------------------------*/
/* Empty_Space
/*-----------------------------------------------------------------------------------*/
//Empty_Space - height on responsive mode
vc_add_param("vc_empty_space", array(
    "type" => "textfield",
    "heading" => esc_html__("Height on responsive mode", "vitrine"),
    "param_name" => "responsive_height",
    "description" => esc_html__("Enter empty space height on responsive mode (Note: CSS measurement units allowed).","vitrin"),
    'group'	=> esc_html__( "Responsive",  "vitrine"),
));


/*-----------------------------------------------------------------------------------*/
/* Row
/*-----------------------------------------------------------------------------------*/

// remove VC default row animation
vc_remove_param( 'vc_row', 'css_animation');

// remove content placement
vc_remove_param( 'vc_row', 'gap');

// remove parallax speed of video
vc_remove_param( 'vc_row', 'parallax_speed_video');

// remove parallax speed of bg
vc_remove_param( 'vc_row', 'parallax_speed_bg');

// remove font color
vc_remove_param('vc_row', 'font_color');

// remove margin bottom
vc_remove_param('vc_row', 'margin_bottom');

// remove bg color
vc_remove_param('vc_row', 'bg_color');

// remove bg image
vc_remove_param('vc_row', 'bg_image');

// remove row padding
vc_remove_param( 'vc_row', 'padding' );

//remove image repeat Option
vc_remove_param( 'vc_row', 'bg_image_repeat' );

//remove css option
vc_remove_param( 'vc_row', 'css' );

//remove class option
vc_remove_param( 'vc_row', 'el_class' );

//remove full-width option
vc_remove_param( 'vc_row', 'full_width');

//remove parallax option
vc_remove_param( 'vc_row', 'parallax');

//remove parallax image
vc_remove_param( 'vc_row', 'parallax_image');

//remove row id
vc_remove_param( 'vc_row', 'el_id');

//remove row video bg
vc_remove_param( 'vc_row', 'video_bg');

//remove row video bg
vc_remove_param( 'vc_row', 'video_bg_url');

//remove row video bg
vc_remove_param( 'vc_row', 'full_height');

// remove content placement
vc_remove_param( 'vc_row', 'content_placement');

// remove parallax type
vc_remove_param( 'vc_row', 'video_bg_parallax');

// remove parallax type
vc_remove_param( 'vc_row', 'equal_height');

// remove parallax type
vc_remove_param( 'vc_row', 'columns_placement');

$row_setting = array (
  "name" => "Row, Parallax, Video",
  'show_settings_on_create' => true,
  "weight" => 10,
);
vc_map_update('vc_row', $row_setting);

$separator_setting = array (
  'show_settings_on_create' => true,
  "controls"	=> '',
  "weight" => 9,
);
vc_map_update('vc_separator', $separator_setting);

vc_add_param("vc_row", array(
    "type" => "dropdown",
    "class" => "",
    "holder" => "span",
    "show_settings_on_create"=>true,
    "heading" => esc_html__("Container Type", "vitrine"),
    "param_name" => "row_type",
    "description" => esc_html__("Choose different types of containers and set the options.", "vitrine"),
    "value" => array(
        "Row" => "row",
        "Parallax" => "parallax",
        "Interactive background" => "interactive_background",
        "Video" => "video",
    ),
));

vc_add_param("vc_row", array(
    "type" => "vc_multiselect",
    "class" => "",
    "heading" => esc_html__("Mute video", "vitrine"), 
    "param_name" => "sound",
    "options" => array("off" => "Mute video"),
    "description" => esc_html__("Mute video of background", "vitrine"),
    "value" => "",
    "dependency" => array(
        'element' => "row_type", 
        'value' => array('video')
    )
));  

vc_add_param("vc_row", array(
    "type" => "checkbox",
    "class" => "",
    "heading" => esc_html__("Equal height", "vitrine"), 
    "param_name" => "equal_height",
    "description" => esc_html__("If checked columns will be set to equal height.", "vitrine"),
    "value" => "",
    'group'		=> esc_html__( "Inner Columns",  "vitrine"),
));  

vc_add_param("vc_row", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Columns gap", "vitrine"),
    "param_name" => "gap",
    "description" => esc_html__("Select gap between columns in row.", "vitrine"),
    "value" => array(
        "30px - default" => "30",
        "0px" => "zero",
        "1px" => "1",
        "5px" => "5",
        "10px" => "10",
        "15px" => "15",
        "20px" => "20",
        "25px" => "25",
        "35px" => "35", 
    ),
    'group'		=> esc_html__( "Inner Columns",  "vitrine"),
));

vc_add_param("vc_row", array(
    "type" => "checkbox",
    "class" => "",
    "heading" => esc_html__("Full height", "vitrine"), 
    "param_name" => "full_height",
    "description" => esc_html__("If checked row will be set to full height.", "vitrine"),
    "value" => "",
));  

vc_add_param("vc_row", array(
    "type" => "dropdown",
    "class" => "",
    'heading' => esc_html__( 'Columns position', 'vitrine' ),
    "param_name" => "columns_placement",
    'description' => esc_html__( 'Select columns position within row.', 'vitrine' ),
    'value' => array(
	    esc_html__( 'Middle', 'vitrine' ) => 'middle',
	    esc_html__( 'Top', 'vitrine' ) => 'top',
	    esc_html__( 'Bottom', 'vitrine' ) => 'bottom',
    ),
    'dependency' => array(
		'element' => 'full_height',
		'not_empty' => true,
	),
));

// row spacing - Padding
vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Padding Top", "vitrine"),
    "param_name" => "row_padding_top",
    "description" => esc_html__("insert top padding for current row . example : 200 ", "vitrine"),
    'group'		=> esc_html__( "Padding",  "vitrine"),
));

vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Padding Bottom", "vitrine"),
    "param_name" => "row_padding_bottom",
    "description" => esc_html__("Insert bottom padding for current row . example : 200", "vitrine"),
    'group'		=> esc_html__( "Padding",  "vitrine"),
));

vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Padding Right", "vitrine"),
    "param_name" => "row_padding_right",
    "description" => esc_html__("Insert Right padding for current row . example : 200", "vitrine"),
    'group'		=> esc_html__( "Padding",  "vitrine"),
));

vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Padding Left", "vitrine"),
    "param_name" => "row_padding_left",
     "description" => esc_html__("Insert left padding for current row . example : 200", "vitrine"),
    'group'		=> esc_html__( "Padding",  "vitrine"),
));

// row spacing - Padding on mobile
vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Top Padding on responsive", "vitrine"),
    "param_name" => "row_responsive_padding_top",
    "description" => esc_html__("insert top padding for current row . example : 50 ", "vitrine"),
    'group'		=> esc_html__( "Padding",  "vitrine"),
));

vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Bottom Padding on responsive", "vitrine"),
    "param_name" => "row_responsive_padding_bottom",
    "description" => esc_html__("Insert bottom padding for current row . example : 50", "vitrine"),
    'group'		=> esc_html__( "Padding",  "vitrine"),
));

vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Right Padding on responsive", "vitrine"),
    "param_name" => "row_responsive_padding_right",
    "description" => esc_html__("Insert Right padding for current row . example : 50", "vitrine"),
    'group'		=> esc_html__( "Padding",  "vitrine"),
));

vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Left Padding on responsive", "vitrine"),
    "param_name" => "row_responsive_padding_left",
     "description" => esc_html__("Insert left padding for current row . example : 50", "vitrine"),
    'group'		=> esc_html__( "Padding",  "vitrine"),
));

// row spacing - margin
vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Margin Top", "vitrine"),
    "param_name" => "row_margin_top",
     "description" => esc_html__("Insert top margin for current row . example : 200", "vitrine"),
    'group'		=> esc_html__( "Margin",  "vitrine"),
));

vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Margin Bottom", "vitrine"),
    "param_name" => "row_margin_bottom",
    "description" => esc_html__("Insert bottom margin for current row . example : 200", "vitrine"),
    'group'		=> esc_html__( "Margin",  "vitrine"),
));


// row spacing - margin on mobile
vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Top Margin on responsive", "vitrine"),
    "param_name" => "row_responsive_margin_top",
    "description" => esc_html__("insert top margin for current row . example : 50 ", "vitrine"),
    'group'		=> esc_html__( "Margin",  "vitrine"),
));

vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Bottom Margin on responsive", "vitrine"),
    "param_name" => "row_responsive_margin_bottom",
    "description" => esc_html__("Insert bottom margin for current row . example : 50", "vitrine"),
    'group'		=> esc_html__( "Margin",  "vitrine"),
));

vc_add_param("vc_row", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Type", "vitrine"),
    "param_name" => "type",
    "description" => esc_html__("Full-width will use all of your screen width, while Boxed will created an invisible box in middle of your screen.", "vitrine"),
    "value" => array(
        "Boxed" => "grid",
        "Full Width" => "full_width",
    ),
    "dependency" => Array(
        'element' => "row_type", 
        'value' => array('row')
    )
));
                        
//background image 
vc_add_param("vc_row", array(
    "type" => "attach_image",
    "class" => "",
    "heading" => esc_html__("Image URL", "vitrine"),
    "param_name" => "background_img_id",
    "description" => esc_html__("Choose an image to be used as this section's background.", "vitrine"),
    "value" => "",
    "dependency" => Array(
        'element' => "row_type", 
        'value' => array('row','parallax', 'interactive_background')
       
    )  
));

vc_add_param("vc_row", array(
    "type" => "dropdown",
    "heading" => esc_html__("background image position", "vitrine"),
    "param_name" => "background_position",
    "description" => esc_html__("Choose background position for bckground image.", "vitrine"),
    "value" => array(
 		"Center Center" => "center center",
 		"Center Top" => "center top",
 		"Center Bottom" => "center bottom",
 		"Left Top" => "left top",
 		"Left Center" => "left center",
 		"Left Bottom" => "left bottom",
 		"Right Top" => "right top",
 		"Right Center" => "right center",
 		"Right Bottom" => "right bottom",
    ),
    "dependency" => Array(
        'element' => "row_type", 
        'value' => array('parallax','row')
    )
));

//background color
vc_add_param("vc_row", array(
	"type" => "colorpicker",
	"class" => "",
    "heading" => esc_html__("Background Color", "vitrine"),
	"param_name" => "background_color",
    "description" => esc_html__("Choose a color to be used as this section's background. Please noticed that background color, has higher priority than background image.", "vitrine"),
	"value" => "",
	"description" => "",
	"dependency" => Array(
        'element' => "row_type", 
        'value' => array('row','expandable','content_menu')
    )
));

// Add min height For row 
vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => esc_html__("Minimum Height", "vitrine"),
    "param_name" => "min_height",
    "description" => esc_html__("Insert minimum height for parallax section . example : 550", "vitrine"),
    "dependency" => Array(
        'element' => "row_type", 
        'value' => array('parallax','interactive_background')
    )
));

// parallax speed
vc_add_param("vc_row", array(
    "type" => "textfield",
    "class" => "",
    "heading" => esc_html__("Parallax Speed",  "vitrine"),
    "param_name" => "parallax_speed",
    "description" => esc_html__("parallax speed 0 = page scrolling speed ,parallax speed above 0 = parallax is faster than page scrolling speed. Enter a number between 0 - 100", "vitrine"),
    "value" => "100",
    "dependency" => Array(
        'element' => "row_type", 
        'value' => array('parallax')
    )
));

// video webm
vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => "Video background (webm) file url",
	"value" => "",
	"param_name" => "video_webm",
	"description" => "",
    "dependency" => Array(
        'element' => "row_type", 
        'value' => array('video')
    )
));

// video Mp4
vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => "Video background (mp4) file url",
	"value" => "",
	"param_name" => "video_mp4",
	"description" => "",
	"dependency" => Array(
        'element' => "row_type", 
        'value' => array('video')
    )
));

//Video Preview Image 
vc_add_param("vc_row", array(
    "type" => "attach_image",
    "class" => "",
    "heading" => esc_html__("Video Preview Image", "vitrine"),
    "param_name" => "video_image",
    "value" => "",
    "description" => esc_html__("Enter an image address which will be shown instead of video in tablet and mobile devices. Also it will be shown if the video does not load correctly.", "vitrine"),
    "dependency" => Array(
        'element' => "row_type", 
        'value' => array('video')
       
    )  
));

// video height
vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => "Video Section Height",
	"value" => "",
	"param_name" => "video_height",
	"description" => esc_html__("Use pixels (px). example :  550px", "vitrine"),
	"dependency" => Array(
        'element' => "row_type", 
        'value' => array('video')
    )
));

// Overlay Texture
vc_add_param("vc_row", array(
	"type" => "vc_imageselect",
	"class" => "textures",
	"heading" => esc_html__("Overlay Texture",  "vitrine"),
	"param_name" => "overlay_texture",
    "value" => array(
		"none" => "texture1",
        "texture2" => "texture2",
        "texture3" => "texture3",
        "texture4" => "texture4",
        "texture5" => "texture5",
        "texture6" => "texture6",
        "texture7" => "texture7",
        "texture8" => "texture8",
    ),
	"dependency" => array(
        "element" => "row_type",
        'value' => array('video','parallax','interactive_background'),
	)
));

// Overlay color
vc_add_param("vc_row", array(
	"type" => "colorpicker",
	"class" => "",
	"heading" => esc_html__("Overlay Color", "vitrine"),
	"param_name" => "overlay_color",
	"value" => "",
	"description" => esc_html__("Select optional overlay color.", "vitrine") , 
    "dependency" => array(
		"element" => "row_type",
        'value' => array('video','parallax','interactive_background'),
	)
    
));

vc_add_param("vc_row", array(
    'type' => 'textfield',
    'heading' => esc_html__( 'Extra class name', 'vitrine' ),
    'param_name' => 'el_class',
    'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'vitrine' )
));

vc_add_param("vc_row",array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Animation", "vitrine"),
    "param_name" => "row_animation",
    "description" => esc_html__("Select animation type", "vitrine") ,
    "value" => $animations,
    "group" => esc_html__('Animation','vitrine')
));

vc_add_param("vc_row",array(
    "type" => "textfield",
    "class" => "",
    "heading" => esc_html__("Animation Delay", "vitrine"),
    "param_name" => "row_animation_delay",
    "value" => "",
    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
    "group" => esc_html__('Animation','vitrine')
));

vc_add_param("vc_row",array(
    "type" => "vc_multiselect",
    "class" => "",
    "heading" => esc_html__("Animation in Responsive", "vitrine"),
    "param_name" => "responsive_animation",
    "options" => array("disable" => "Disable animation"),
    "value" => "disable",
    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
    "group" => esc_html__('Animation','vitrine')
));

/*-----------------------------------------------------------------------------------*/
/* VC Column
/*-----------------------------------------------------------------------------------*/
vc_remove_param('vc_column', 'css_animation');
vc_add_param("vc_column",array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Vertical Position", "vitrine"),
    "param_name" => "vertical_position",
    "description" => esc_html__("Specify the vertical position of the content. ( works only On Equal height row)", "vitrine") ,
        'value' => array(
            esc_html__( 'Top', 'vitrine' ) => 'top',
            esc_html__( 'Middle', 'vitrine' ) => 'middle',
            esc_html__( 'Bottom', 'vitrine' ) => 'bottom',
    ),
));


/*-----------------------------------------------------------------------------------*/
/* VC Column
/*-----------------------------------------------------------------------------------*/


vc_add_param("vc_column_inner",array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Vertical Position", "vitrine"),
    "param_name" => "vertical_position",
    "description" => esc_html__("Specify the vertical position of the content. ( works only On Equal height row)", "vitrine") ,
        'value' => array(
            esc_html__( 'Top', 'vitrine' ) => 'top',
            esc_html__( 'Middle', 'vitrine' ) => 'middle',
            esc_html__( 'Bottom', 'vitrine' ) => 'bottom',
    ),
));


/*-----------------------------------------------------------------------------------*/
/*  contact form 7
/*-----------------------------------------------------------------------------------*/

    vc_add_param("contact-form-7", array(
        "type" => "dropdown",
        "class" => "",
        'heading' => esc_html__('Style', 'vitrine' ),
        'param_name' => 'html_class',
        'description' => esc_html__( 'Select contact form style.', 'vitrine' ),
        "value" => array(
            "Dark" => "dark_styles",
            "Light" => "light_styles"
        ),
    ));      

    // remove param title
    vc_remove_param('contact-form-7' , 'title');
      

/*-----------------------------------------------------------------------------------*/
/* VC block text
/*-----------------------------------------------------------------------------------*/

$column_text_setting = array (
  "weight" => 9,
);
vc_map_update('vc_column_text', $column_text_setting);

vc_remove_param('vc_column_text', 'css_animation');
vc_add_param("vc_column_text",array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Animation", "vitrine"),
    "param_name" => "text_animation",
    "description" => esc_html__("Select animation type", "vitrine") ,
    "value" => $animations,
    "group" => esc_html__('Animation','vitrine')
));

vc_add_param("vc_column_text",array(
    "type" => "textfield",
    "class" => "",
    "heading" => esc_html__("Animation Delay", "vitrine"),
    "param_name" => "text_animation_delay",
    "value" => "",
    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
    "group" => esc_html__('Animation','vitrine')
));

vc_add_param("vc_column_text",array(
    "type" => "vc_multiselect",
    "class" => "",
    "heading" => esc_html__("Animation in Responsive", "vitrine"),
    "param_name" => "responsive_animation",
    "options" => array("disable" => "Disable animation"),
    "value" => "disable",
    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
    "group" => esc_html__('Animation','vitrine')
));

/*-----------------------------------------------------------------------------------*/
/* Separator
/*-----------------------------------------------------------------------------------*/

vc_remove_param('vc_separator', 'style');
vc_remove_param('vc_separator', 'align');
vc_remove_param('vc_separator', 'el_width');
vc_remove_param('vc_separator', 'el_class');
vc_remove_param('vc_separator', 'accent_color');
vc_remove_param('vc_separator', 'color');
vc_remove_param('vc_separator', 'border_width');
vc_remove_param('vc_separator', 'css'); // remove design tab options

vc_add_param("vc_separator", array(
    "type" => "colorpicker",
    "holder" => "div",
    "class" => "",
    "heading" => esc_html__("Separator's Color", "vitrine"),
    "param_name" => "color",
    "value" => "",
    "description" => esc_html__("Select optional Separator's color ", "vitrine") ,  
));

// Separator
vc_add_param("vc_separator", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Separator Size", "vitrine"), 
    "param_name" => "size",
    "description" => esc_html__("Choose the size of separator", "vitrine") ,
    "value" => array(
        "Full Width" => "full",
        "Small" => "small",
        "Small Center" => "small-center",
        "Extra Small" => "extra-small", 
        "Extra Small Center" => "extra-small-center",
        "Medium" => "medium", 
        "Medium Center" => "medium-center"
        )
));

vc_add_param("vc_separator", array(
    "type" => "textfield",
    "heading" => esc_html__("Margin Top", "vitrine"),
    "param_name" => "separator_margin_top",
    "description" => esc_html__("Insert top margin for current separator . example : 200", "vitrine"),
    'group'		=> esc_html__( "Margin",  "vitrine"),
));

vc_add_param("vc_separator", array(
    "type" => "textfield",
    "heading" => esc_html__("Margin Bottom", "vitrine"),
    "param_name" => "separator_margin_bottom",
    "description" => esc_html__("Insert bottom margin for current separator . example : 200", "vitrine"),
    'group'		=> esc_html__( "Margin",  "vitrine"),
));

vc_add_param("vc_separator", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Border style", "vitrine"), 
    "param_name" => "border_style",
    "description" => esc_html__("Select border style", "vitrine") ,
    "value" => array(
        "Solid" => "solid",
        "Dashed" => "dashed",
        "Dotted" => "dotted",
        "Double" => "double",
        "Groove" => "groove",
        "Inset" => "inset",
        "Outset" => "outset",
        "Ridge" => "ridge",
    ),
));

vc_add_param("vc_separator", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Separator Size", "vitrine"), 
    "param_name" => "pxthickness",
    "description" => esc_html__("Select thickness of separator", "vitrine") ,
    "value" => array(
        "1px" => "1",
        "2px" => "2",
        "3px" => "3",
        "4px" => "4",
        "5px" => "5",
        "6px" => "6",
        "7px" => "7",
        "8px" => "8",
        "9px" => "9",
        "10px" => "10", 
    ),
)); 

/*-----------------------------------------------------------------------------------*/
/* Title separator
/*-----------------------------------------------------------------------------------*/

$text_separator_setting = array (
  "weight" => 9,
);
vc_map_update('vc_text_separator', $text_separator_setting);

vc_remove_param('vc_text_separator', 'color');
vc_remove_param('vc_text_separator', 'accent_color');
vc_remove_param('vc_text_separator', 'style');
vc_remove_param('vc_text_separator', 'el_width');
vc_remove_param('vc_text_separator', 'el_class');
vc_remove_param('vc_text_separator', 'border_width');
vc_remove_param('vc_text_separator', 'align');
vc_remove_param('vc_text_separator', 'css'); // remove design tab options

vc_add_param("vc_text_separator", array(
    "type" => "colorpicker",
    "holder" => "div",
    "icon" => "icon-wpb-text-separator",
    "class" => "",
    "heading" => esc_html__("Separator's Color", "vitrine"),
    "param_name" => "color",
    "value" => "",
    "description" => esc_html__("Select optional Separator's color ", "vitrine") ,  
));

vc_add_param("vc_text_separator", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Border Style", "vitrine"), 
    "param_name" => "border_style",
    "description" => esc_html__("Select border style", "vitrine") ,
    "value" => array(
        "Solid" => "solid",
        "Dashed" => "dashed",
        "Dotted" => "dotted",
        "Double" => "double",
        "Groove" => "groove",
        "Inset" => "inset",
        "Outset" => "outset",
        "Ridge" => "ridge",
    ),
));

vc_add_param("vc_text_separator", array(
    "type" => "colorpicker",
    "holder" => "",
    "class" => "",
    "heading" => esc_html__("Title's Color", "vitrine"),
    "param_name" => "title_color",
    "value" => "",
    "description" => esc_html__("Select optional title color.", "vitrine") ,  
));

vc_add_param("vc_text_separator", array(
    "type" => "dropdown",
    "holder" => "",
    "class" => "",
    "heading" => esc_html__("Title font-weight", "vitrine"), 
    "param_name" => "title_weight",
    "description" => esc_html__("Select title font weight", "vitrine") ,
    "value" => array(
        "Bold" => "bold",
        "Normal" => "normal",
    ),
));

vc_add_param("vc_text_separator", array(
    "type" => "textfield",
    "heading" => esc_html__("Margin Top", "vitrine"),
    "param_name" => "separator_margin_top",
    "description" => esc_html__("Insert top margin for current separator . example : 200", "vitrine"),
    'group'		=> esc_html__( "Margin",  "vitrine"),
));

vc_add_param("vc_text_separator", array(
    "type" => "textfield",
    "heading" => esc_html__("Margin Bottom", "vitrine"),
    "param_name" => "separator_margin_bottom",
    "description" => esc_html__("Insert bottom margin for current separator . example : 200", "vitrine"),
    'group'		=> esc_html__( "Margin",  "vitrine"),
));

vc_add_param("vc_text_separator", array(
    "type" => "dropdown",
    "holder" => "",
    "class" => "",
    "heading" => esc_html__("Separator line thickness", "vitrine"), 
    "param_name" => "pxthickness",
    "description" => esc_html__("Select thickness of separator", "vitrine") ,
    "value" => array(
        "1px" => "1",
        "2px" => "2",
        "3px" => "3",
        "4px" => "4",
        "5px" => "5",
        "6px" => "6",
        "7px" => "7",
        "8px" => "8",
        "9px" => "9",
        "10px" => "10", 
    ),
));

vc_add_param("vc_text_separator", array(
    "type" => "dropdown",
    "holder" => "",
    "class" => "",
    "heading" => esc_html__("Title Font size", "vitrine"), 
    "param_name" => "title_font_size",
    "description" => esc_html__("Select separator title font size", "vitrine") ,
    "value" => $Customfontsize,
)); 

vc_add_param("vc_text_separator", array(
    "type" => "dropdown",
    "holder" => "",
    "class" => "",
    "heading" => esc_html__("Enable border right/left For Title", "vitrine"), 
    "param_name" => "title_border_enable",
    "description" => esc_html__("Enable or Disbale right/left title border", "vitrine") ,
    "value" => array(
       "Enable" => "enable",
	   "Disable" => "disable"
    ),
)); 

/*-----------------------------------------------------------------------------------*/
/* toggle
/*-----------------------------------------------------------------------------------*/

vc_remove_param('vc_toggle', 'size');
vc_remove_param('vc_toggle', 'color');
vc_remove_param('vc_toggle', 'style');
vc_remove_param('vc_toggle', 'css_animation');

$vc_toggle_setting = array (
  "weight" => 9,
);
vc_map_update('vc_toggle', $vc_toggle_setting);

/*-----------------------------------------------------------------------------------*/
/* Testimonials
/*-----------------------------------------------------------------------------------*/
vc_map( 
    array(
        "name" => "Testimonial",
        "base" => "testimonial",
        "category" => 'By Epico',
        "weight" => 9,
        "admin_enqueue_css" => array(get_template_directory_uri().'/lib/admin/css/vc-extend.css'),        
        "icon" => "icon-wpb-testimonial",
        "as_parent" => array('only' => 'testimonial_item'),
        "js_view" => 'VcColumnView',
        "content_element" => true,
        "params" => array(
		array (
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Layout", "vitrine"), 
                "param_name" => "layout",
                "description" => esc_html__("Select testimonial layout.", "vitrine") ,
                "value" => array(
                    "Style1" => "style1",
                    "Style2" => "style2"
                ),
            ),
            array (
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Style", "vitrine"), 
                "param_name" => "style",
                "description" => esc_html__("Select testimonial style.", "vitrine") ,
                "value" => array(
                    "Dark" => "dark",
                    "Light" => "light"
                ),
            ),
			  
            array(
                "type" => "vc_imageselect",
                "class" => "presets",
                "heading" => esc_html__("Color Presets", "vitrine"),
                "param_name" => "testimonial_color_preset",
                "description" => esc_html__("Select testimonial color.", "vitrine") , 
                    "value" => array(
                        "c0392b" => "c0392b",
                        "e74c3c" => "e74c3c",
                        "d35400" => "d35400",
                        "e67e22" => "e67e22",
                        "f39c12" => "f39c12",
                        "f1c40f" => "f1c40f",
                        "1abc9c" => "1abc9c",
                        "2ecc71" => "2ecc71",
                        "3498db" => "3498db",
                        "01558f" => "01558f",
                        "9b59b6" => "9b59b6",
                        "ecf0f1" => "ecf0f1",
                        "bdc3c7" => "bdc3c7",
                        "7f8c8d" => "7f8c8d",
                        "95a5a6" => "95a5a6",
                        "34495e" => "34495e",
                        "2e2e2e" => "2e2e2e",
                        "custom-color" => "custom"
                ),
				'dependency' => array(
					'element' => 'layout',
					'value' => 'style1',
				),
				
            ),
			array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("column", "vitrine"),
                "param_name" => "visible_items",
                "description" => esc_html__("Select columns each row", "vitrine") ,
				"value" => array(
				 	"1" => "1",
					"2" => "2",
                    "3" => "3",
					"4" => "4",
				 ),
				'dependency' => array(
					'element' => 'layout',
					'value' => 'style2',
				),
            ),
            array(
                "type" => "colorpicker",
                "class" => "",
                "heading" => esc_html__("Custom testimonial Color", "vitrine"),
                "param_name" => "testimonial_color",
                "value" => "",
                "description" => esc_html__("Enter a testimonial color", "vitrine") , 
                "dependency" => Array(
                    'element' => "testimonial_color_preset", 
                    'value' => "custom"
                ) 
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Animation", "vitrine"),
                "param_name" => "testimonial_animation",
                "description" => esc_html__("Select animation type", "vitrine") ,
                "value" => $animations,
                "group" => esc_html__('Animation','vitrine')
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => esc_html__("Animation Delay", "vitrine"),
                "param_name" => "testimonial_animation_delay",
                "value" => "",
                "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                "group" => esc_html__('Animation','vitrine')
            ),
            array(
			    "type" => "vc_multiselect",
			    "class" => "",
			    "heading" => esc_html__("Animation in Responsive", "vitrine"),
			    "param_name" => "responsive_animation",
			    "options" => array("disable" => "Disable animation"),
			    "value" => "disable",
			    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
			    "group" => esc_html__('Animation','vitrine')
			)
        )
    ) 
);


vc_map( 
    array(
		"name" => "Testimonial Item",
		"base" => "testimonial_item",
		"category" => 'By Epico',
        "weight" => 9,
        "admin_enqueue_css" => array(get_template_directory_uri().'/lib/admin/css/vc-extend.css'),        
		"icon" => "icon-wpb-testimonial-item",
        "as_child" => array('only' => 'testimonial'),
        "content_element" => true,
		"params" => array(
            array(
                "type" => "attach_image",
                "class" => "",
                "heading" => esc_html__("Image URL", "vitrine"),
                "param_name" => "image_url",
                "value" => "",
                "description" => esc_html__("URL of the image", "vitrine")
            ),
            array(
                "type" => "textfield",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Name", "vitrine"),
                "param_name" => "author",
            ),
            array(
				"type" => "textfield",
				"admin_label" => true,
				"class" => "",
				"heading" => esc_html__("Title", "vitrine"),
				"param_name" => "job",
			),
            array(
                "type" => "textarea",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Statement", "vitrine"),
                "param_name" => "text",
                "description" => esc_html__("Type down the statement.", "vitrine") ,   
            ),
		)
    ) 
);

/*-----------------------------------------------------------------------------------*/
/* Horizontal progress bar
/*-----------------------------------------------------------------------------------*/

vc_map( array(
        "name" => "Progress Bar",
        "base" => "progressbar",
        "icon" => "icon-wpb-progressbar",
        "category" => 'By Epico',
        "weight" => 9,
        "params" => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__("Title", "vitrine"),
                "param_name" => "title",
                "value" => "",
                "description" => "",
				"admin_label" => true,
            ),
            array(
                "type" => "colorpicker",
                "holder" => "div",
				"admin_label" => false,
                "class" => "",
                "heading" => esc_html__("Title Color", "vitrine"),
                "param_name" => "title_color",
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
				"admin_label" => true,
                "heading" => esc_html__("Percentage", "vitrine"),
                "param_name" => "percent",
                "value" => "",
                "description" => esc_html__("If you want it to show 85% progression, just type 85 in this box.", "vitrine")
            ),
            array(
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__("Progressed Section Color", "vitrine"),
                "param_name" => "active_bg_color",
                "description" => esc_html__("Select a color to be set as progress bar progressed section color.", "vitrine")
            ),
            array(
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
				"admin_label" => false,
                "heading" => esc_html__("Non-progressed Section Color", "vitrine"),
                "param_name" => "inactive_bg_color",
                "description" => esc_html__("Select a color to be set as progress bar progressed section color.", "vitrine")
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
				"admin_label" => true,
                "heading" => esc_html__("Animation", "vitrine"),
                "param_name" => "progressbar_animation",
                "description" => esc_html__("Select animation type", "vitrine") ,
                 "value" => $animations,
                 "group" => esc_html__('Animation','vitrine')
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
				"admin_label" => false,
                "heading" => esc_html__("Animation Delay", "vitrine"),
                "param_name" => "progressbar_animation_delay",
                "value" => "",
                "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                "group" => esc_html__('Animation','vitrine')
            ),
			array(
			    "type" => "vc_multiselect",
			    "class" => "",
			    "heading" => esc_html__("Animation in Responsive", "vitrine"),
			    "param_name" => "responsive_animation",
			    "options" => array("disable" => "Disable animation"),
			    "value" => "disable",
			    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
			    "group" => esc_html__('Animation','vitrine')
			)
        )
) );


/*-----------------------------------------------------------------------------------*/
/* Horizontal progress bar
/*-----------------------------------------------------------------------------------*/

vc_map( array(
		"name" => "Progress Bar",
		"base" => "progressbar",
		"icon" => "icon-wpb-progressbar",
		"category" => 'By Epico',
        "weight" => 9,
		"params" => array(
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => esc_html__("Title", "vitrine"),
				"param_name" => "title",
                "value" => "",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => esc_html__("Title Color", "vitrine"),
				"param_name" => "title_color",
				"description" => ""
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => esc_html__("Percentage", "vitrine"),
				"param_name" => "percent",
                "value" => "",
				"description" => esc_html__("For example if you want to enter 85% just enter 85 .", "vitrine")
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => esc_html__("Active Background Color", "vitrine"),
				"param_name" => "active_bg_color",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => esc_html__("Inactive Background Color", "vitrine"),
				"param_name" => "inactive_bg_color",
				"description" => ""
			),
             array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html__("Animation", "vitrine"),
					"param_name" => "progressbar_animation",
					"description" => esc_html__("Select animation type", "vitrine") ,
					"value" => $animations,
					"group" => esc_html__('Animation','vitrine')
				),
                array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html__("Animation Delay", "vitrine"),
					"param_name" => "progressbar_animation_delay",
					"value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				)
		)
) );


/*-----------------------------------------------------------------------------------*/
/* Portfolio List
/*-----------------------------------------------------------------------------------*/

vc_map( array(
        "name" => "Portfolio",
		"base" => "portfolio",
		"icon" => "icon-wpb-portfolio",
		"category" => 'By Epico',
        "weight" => 9,
		"params" => array(
			array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Grid Layout", "vitrine"),
                "param_name" => "portfolio_masonry",
                "value" => array(
					"Fixed Layout" => "perfectMasonry",
					"Masonary Layout" => "masonry"
                ),
				"description" => ""
            ),
			array(
                "type" => "vc_imageselect",
				"class" => "portfoliotype",
                "admin_label" => true,
                "heading" => esc_html__("Portfolio Type", "vitrine"),
				"param_name" => "type",
                "value" => array(
                    "portfolio_space" => "portfolio_space",
                    "portfolio_no_space" => "portfolio_no_space",
                    "portfolio_text" => "portfolio_text",
				),
				"description" => "",
			),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Show title", "vitrine"), 
                "param_name" => "title_bar",
                "description" => esc_html__("Show or hide the title", "vitrine") ,
                "value" => array(
                    esc_html__('Do not show title', "vitrine")  => 'hide',
                     esc_html__('Show custom title', "vitrine") => 'show'
                ),              
            ),
            array(
				"type" => "textfield",
				"class" => "",
                "admin_label" => true,
				"heading" => esc_html__("Title", "vitrine"),
				"param_name" => "title_text",
                "value" => "",
				"description" => esc_html__("Choose a title to be shown at the beginning of portfolio section", "vitrine") ,
                "dependency" => Array(
                    'element' => "title_bar", 
                    'value' => 'show'
                 )
			),
            array(
				"type" => "textfield",
				"class" => "",
                "admin_label" => true,
				"heading" => esc_html__("Subtitle", "vitrine"),
				"param_name" => "subtitle_text",
                "value" => "",
				"description" => esc_html__("Choose a subtitle to be shown at the beginning of portfolio section", "vitrine") ,
                 "dependency" => Array(
                    'element' => "title_bar", 
                    'value' => 'show'
                 )
			),
            array(
				"type" => "dropdown",
		        "admin_label" => true,
				"class" => "",
				"heading" => esc_html__("Filter Display", "vitrine"),
				"param_name" => "filter_display",
				"value" => array(
					esc_html__("Show", "vitrine") => "show",
					esc_html__("Hide", "vitrine") => "hide"
				),
				"description" => "",
			),
            array(
				"type" => "dropdown",
		        "admin_label" => true,
				"class" => "",
				"heading" => esc_html__("Filter Style", "vitrine"),
				"param_name" => "filter_style",
				"value" => array(
					"Standard" => "standard",
                    "Toggle" => "toggle"
				),
				"description" => "",
			),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Filter toggle beginning state", "vitrine"),
                "param_name" => "filter_toggle_state",
                "value" => array(
                    "Close"  => "close",
                    "Open"  => "open"
                ),
                "description" => "",
                "dependency" => Array(
                    'element' => "filter_style", 
                    'value' => 'toggle'
                 )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Portfolio Categories", "vitrine"),
                "param_name" => "portfolio_filter",
                "value" => array(
                    "All"  => "all",
                    "Custom"  => "custom"
                ),
                "description" => "",
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Portfolio Custom Categories", "vitrine"),
                "param_name" => "filters",
                "options" => $portfolio_skills,
                "description" => esc_html__("Selected categories to be shown in portfolio section", "vitrine"),
                "value" => "",
                 "dependency" => Array(
                    'element' => "portfolio_filter", 
                    'value' => 'custom'
                 )
            ),
            array(
                "type" => "vc_rangefield",
                "label" => "items",
                "admin_label" => true,
                "heading" => esc_html__("Portfolio Post Per Section", "vitrine"),
                "param_name" => "portfolio_posts_page",
                'min'   => '1',
                'max'   => '30',
                'step'   => '1',
                'value' => '12',
                "description" => "Choose the initial number of portfolio items to be shown before clicking load more button.",
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Style", "vitrine"),
                "param_name" => "filter_loadmore_style",
                "value" => array(
                    "Dark"  => "darkStyle",
                    "Light"  => "lightStyle"
                ),
                "description" => "dark and light styles for filter bar , load more button and title and subtitle",
            ),
             array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Hover type", "vitrine"),
                "param_name" => "portfolio_hover",
                "value" => array(
                    "Creative"  => "creativeType",
                    "Border Style"  => "borderType",
                    "Simple"  => "simpleType"
                ),
                "description" => "",
                'group'=> esc_html__('On-hover','vitrine'),
                "dependency" => Array(
                    'element' => "type", 
                    'value' => array("portfolio_space" ,  "portfolio_no_space")
                )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Hover style", "vitrine"),
                "param_name" => "portfolio_hover_style",
                "value" => array(
                    "Light"  => "lightStyle",
                    "Dark"  => "darkStyle"
                ),
                "description" => "",
                'group'=> esc_html__('On-hover','vitrine')
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Display like Button On hover", "vitrine"),
                "param_name" => "portfolio_hover_like_button",
                "value" => array(
					"Show" => "show",
					"Hide" => "hide"
                ),
                "description" => "",
                'group'=> esc_html__('On-hover','vitrine')
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Entrance Animation", "vitrine"), 
                "param_name" => "enterance_animation",
                "description" => esc_html__("How you want your items to enter the page", "vitrine") ,
                "value" => array(
                    esc_html__('FadeIn From Bottom', "vitrine") => 'fadeInFromBottom',
                    esc_html__('FadeIn From Top', "vitrine") => 'fadeInFromTop',
                    esc_html__('FadeIn From Right', "vitrine")=> 'fadeInFromRight',
                    esc_html__('FadeIn From Left', "vitrine") => 'fadeInFromLeft',
                    esc_html__('Zoom-in', "vitrine")  => 'zoomIn',
                    esc_html__('No Animation', "vitrine")  => 'default',
                ),
                "group" => esc_html__('Animation','vitrine')              
            ),
         	array(
			    "type" => "vc_multiselect",
			    "class" => "",
			    "heading" => esc_html__("Animation in Responsive", "vitrine"),
			    "param_name" => "responsive_animation",
			    "options" => array("disable" => "Disable animation"),
			    "value" => "disable",
			    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
			    "group" => esc_html__('Animation','vitrine')
			)
         )
    )
);

/*-----------------------------------------------------------------------------------*/
/* Portfolio inner
/*-----------------------------------------------------------------------------------*/

vc_map( array(
        "name" => "Portfolio Inner",
        "base" => "portfolio_inner",
        "icon" => "icon-wpb-portfolio-inner",
        "category" => 'By Epico',
        "weight" => 9,
        "params" => array(
        	array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Grid Layout", "vitrine"),
                "param_name" => "portfolio_masonry",
                "value" => array(
					"Fixed Layout" => "perfectMasonry",
					"Masonary Layout" => "masonry"
                ),
				"description" => ""
            ),
            array(
                "type" => "vc_imageselect",
                "class" => "portfoliotype",
                "admin_label" => true,
                "heading" => esc_html__("Portfolio Type", "vitrine"),
                "param_name" => "type",
                "value" => array(
                    "portfolio_space" => "portfolio_space",
                    "portfolio_no_space" => "portfolio_no_space",
                    "portfolio_text" => "portfolio_text",
                ),
                "description" => "",
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Show Title", "vitrine"), 
                "param_name" => "title_bar",
                "description" => esc_html__("Show or hide the title", "vitrine") ,
                "value" => array( 
				     esc_html__('Do not show title', "vitrine")  => 'hide',
                     esc_html__('Show custom title', "vitrine") => 'show'
                   
                ),              
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Title", "vitrine"),
                "param_name" => "title_text",
                "value" => "",
                "description" => esc_html__("Choose a title to be shown at the beginning of portfolio section", "vitrine") ,
                "dependency" => Array(
                    'element' => "title_bar", 
                    'value' => 'show'
                 )
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Subtitle", "vitrine"),
                "param_name" => "subtitle_text",
                "value" => "",
                "description" => esc_html__("Choose a subtitle to be shown at the beginning of portfolio section", "vitrine") ,
                 "dependency" => Array(
                    'element' => "title_bar", 
                    'value' => 'show'
                 )
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Filter Display", "vitrine"),
                "param_name" => "filter_display",
                "value" => array(
                    "Show" => "show",
                    "Hide" => "hide"
                ),
                "description" => "",
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Filter Style", "vitrine"),
                "param_name" => "filter_style",
                "value" => array(
                    "Standard" => "standard",
                    "Toggle" => "toggle"
                ),
                "description" => "",
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Filter toggle beginning state", "vitrine"),
                "param_name" => "filter_toggle_state",
                "value" => array(
                    "Close"  => "close",
                    "Open"  => "open"
                ),
                "description" => "",
                "dependency" => Array(
                    'element' => "filter_style", 
                    'value' => 'toggle'
                 )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Portfolio Categories", "vitrine"),
                "param_name" => "portfolio_filter",
                "value" => array(
                    "All"  => "all",
                    "Custom"  => "custom"
                ),
                "description" => "",
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Portfolio custom Categories", "vitrine"),
                "param_name" => "filters",
                "options" => $portfolio_skills,
                "description" => "Selected categories to be shown in portfolio section",
                "value" => "",
                 "dependency" => Array(
                    'element' => "portfolio_filter", 
                    'value' => 'custom'
                 )
            ),
            array(
                "type" => "vc_rangefield",
                "label" => "items",
                "admin_label" => true,
                "heading" => esc_html__("Portfolio Post Per Section", "vitrine"),
                "param_name" => "portfolio_posts_page",
                'min'   => '1',
                'max'   => '30',
                'step'   => '1',
                'value' => '12',
                "description" =>esc_html__( "Choose the initial number of portfolio items to be shown before clicking load more button.", "vitrine"),
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("style", "vitrine"),
                "param_name" => "filter_loadmore_style",
                "value" => array(
                    "Dark"  => "darkStyle",
                    "Light"  => "lightStyle"
                ),
                "description" => "dark and light styles for filter bar , load more button and title and subtitle",
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Hover type", "vitrine"),
                "param_name" => "portfolio_hover",
                "value" => array(
                    "Creative"  => "creativeType",
                    "Border Style"  => "borderType",
                    "Simple"  => "simpleType"
                ),
                "description" => "",
                'group'=> esc_html__('On-hover','vitrine') 
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Hover style", "vitrine"),
                "param_name" => "portfolio_hover_style",
                "value" => array(
                    "Light"  => "lightStyle",
                    "Dark"  => "darkStyle"
                ),
                "description" => "",
                'group'=> esc_html__('On-hover','vitrine') 
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Display like Button On hover", "vitrine"),
                "param_name" => "portfolio_hover_like_button",
                "value" => array(
                    "Show" => "show",
                    "Hide" => "hide"
                ),
                "description" => "",
                'group'=> esc_html__('On-hover','vitrine') 
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Entrance animation", "vitrine"), 
                "param_name" => "enterance_animation",
                "description" => esc_html__("How you want your items to enter the page", "vitrine") ,
                "value" => array(
                    esc_html__('FadeIn From Bottom', "vitrine") => 'fadeInFromBottom',
                    esc_html__('FadeIn From Top', "vitrine") => 'fadeInFromTop',
                    esc_html__('FadeIn From Right', "vitrine") => 'fadeInFromRight',
                    esc_html__('FadeIn From Left', "vitrine")=> 'fadeInFromLeft',
                    esc_html__('Zoom-in', "vitrine")  => 'zoomIn',
                    esc_html__('No Animation', "vitrine") => 'default',
                ),
                "group" => esc_html__('Animation','vitrine')              
            ),
            array(
			    "type" => "vc_multiselect",
			    "class" => "",
			    "heading" => esc_html__("Animation in Responsive", "vitrine"),
			    "param_name" => "responsive_animation",
			    "options" => array("disable" => "Disable animation"),
			    "value" => "disable",
			    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
			    "group" => esc_html__('Animation','vitrine')
			)
         )
    )
);

/*-----------------------------------------------------------------------------------*/
/* SoundCloud
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "SoundCloud",
		"base" => "audio_soundcloud",
		"category" => 'By Epico',
		"icon" => "icon-wpb-soundcloud",
        "weight" => 9,
		"params" => array(
				array(
					"type" => "textfield",
					"holder" => "",
                    "admin_label" => true,
					"class" => "",
					"heading" => esc_html__("SoundCloud URL", "vitrine"),
					"param_name" => "soundcloud_id",
					"value" => "",
					"description" => esc_html__("Enter SoundCloud track URL here", "vitrine")
				),
                array(
					"type" => "dropdown",
					"class" => "",
                    "admin_label" => true,
					"heading" => esc_html__("SoundCloud Player Style", "vitrine"),
					"param_name" => "soundcloud_style",
					"value" => array(
                        "Full background album art" => "full_width_thumbnail",
                        "Thumbnail album art" => "small_thumbnail",
                    ),
					"description" => esc_html__("Choose a style for SoundCloud element.", "vitrine")
				    ),		
			    
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
                    "admin_label" => true,
					"heading" => esc_html__("SoundCloud Player Height", "vitrine"),
					"param_name" => "soundcloud_height",
					"value" => "",
					"description" => esc_html__("Enter SoundCloud height, for example 300.", "vitrine"),
                    "dependency" => Array(
                        'element' => 'soundcloud_style', 
                        'value' => 'full_width_thumbnail',
                     )
                  ),array(
					"type" => "colorpicker",
				    "holder" => "div",
                    "admin_label" => true,
				    "class" => "",
				    "heading" => esc_html__("Player Color", "vitrine"),
				    "param_name" => "soundcloud_color",
				    "description" => "",
                    "dependency" => Array(
                        'element' => 'soundcloud_style', 
                        'value' => 'small_thumbnail',
                     )
                  ),
			),
		)
	) ;

/*-----------------------------------------------------------------------------------*/
/* Embed Video 
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "Embed Video",
		"base" => "embed_video",
		"category" => 'By Epico',
        "icon" => "icon-wpb-embed_video",
        "weight" => 9,
		"params" => array(
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Video Display Type", "vitrine"),
                "param_name" => "video_display_type",
                "value" => array(
                    "local Video" => "local_video",
	                "Embedded Video (Youtube)" => "embeded_video_youtube",
                    "Embedded Video (Vimeo)" => "embeded_video_vimeo",
                    "Local Video Popup" => "local_video_popup",
                    "Embedded Video  ( Youtube Popup )" => "embeded_video_youtube_popup",
                    "Embedded Video ( Vimeo Popup )" => "embeded_video_vimeo_popup",
                ),
                "description" => esc_html__("Select video type.", "vitrine"),
            ),
            array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Play Button Alignment", "vitrine"), 
                    "param_name" => "alignment",
                    "description" => esc_html__("Select Video Play Button alignment.", "vitrine") ,
                    "value" => array(
                        "center" => "center", 
						"left" => "left",
                        "right" => "right"
                    ),
					"dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('local_video_popup','embeded_video_youtube_popup', 'embeded_video_vimeo_popup'),
                )
                ),
            array(
			    'type' => 'dropdown',
			    'heading' => esc_html__( 'Video Aspect Ratio', 'vitrine' ),
			    'param_name' => 'el_aspect',
			    'value' => array(
				    '16:9' => '169',
				    '4:3' => '43',
				    '2.35:1' => '235',
			    ),
			    'description' => esc_html__( 'Select video aspect ratio.', 'vitrine' ),
                "dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('embeded_video_youtube','embeded_video_vimeo'),
                )
		    ),
            array(
				"type" => "dropdown",
				"admin_label" => true,
				"class" => "",
				"heading" => esc_html__("Auto-play", "vitrine"),
				"param_name" => "video_autoplay",
				"value" => array(
                    "Enable" => "enable",
		            "Disable" => "disable"
	            ),
				"description" => esc_html__("Enable or disable video auto-play.", "vitrine") ,   
                "dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('local_video','local_video_popup','embeded_video_youtube_popup', 'embeded_video_vimeo_popup'),
                )
		    ),
            array(
                "type" => "attach_image",
                "class" => "",
                "heading" => esc_html__("Video Poster Image", "vitrine"),
                "param_name" => "video_poster_image",
                "description" => "This image will bw shown while the video is loading.",
				"admin_label" => false,
                "value" => "",
                 "dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('local_video','local_video_popup'),
                )
            ),
            array(
                "type" => "attach_image",
                "class" => "",
                "heading" => esc_html__("Video Cover Image", "vitrine"),
                "param_name" => "video_background_image",
                "description" => "Cover image of video shortcode(Optional).",
                "value" => "",
                "dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('local_video_popup','embeded_video_youtube_popup','embeded_video_vimeo_popup'),
                )
            ), 
            array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => esc_html__("Self Hosted Video (.webm video type)", "vitrine"),
				"param_name" => "video_webm",
				"value" => "",
				"admin_label" => false,
				"description" => "Please provide a URL to all of the video types",
                "dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('local_video','local_video_popup'),
                )
			),
            array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => esc_html__("Self Hosted Video (.mp4 video type)", "vitrine"),
				"param_name" => "video_mp4",
				"value" => "",
				"admin_label" => false,
				"description" => esc_html__("Please provide a URL to all of the video types", "vitrine"),
                "dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('local_video','local_video_popup'),
                )
			),
            array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => esc_html__("Self Hosted Video (.ogv video type)", "vitrine"),
				"param_name" => "video_ogv",
				"value" => "",
				"admin_label" => false,
				"description" => esc_html__("Please provide a URL to all of the video types", "vitrine"),
                "dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('local_video','local_video_popup'),
                )
			),
            array(
				"type" => "dropdown",
				"admin_label" => true,
				"class" => "",
				"heading" => esc_html__("Video Play Button Color", "vitrine"),
				"param_name" => "video_play_button_color",
				"description" => esc_html__("Select play button style.", "vitrine") , 
                "value" => array(
                    "Light" => "light",
                    "Dark" => "dark",
				),
                 "dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('local_video','local_video_popup','embeded_video_youtube_popup', 'embeded_video_vimeo_popup'),
                )
            ),
            array(
				"type" => "textfield",
				"class" => "",
				"heading" => esc_html__("Vimoe Video URL", "vitrine"),
				"param_name" => "video_vimeo_id",
				"value" => "",
				"admin_label" => false,
				"description" => "Enter vimeo video URL",
                "dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('embeded_video_vimeo', 'embeded_video_vimeo_popup'),
                )
            ),
            array(
				"type" => "textfield",
				"class" => "",
				"heading" => esc_html__("YouTube Video URL", "vitrine"),
				"param_name" => "video_youtube_id",
				"value" => "",
				"admin_label" => false,
				"description" => "Enter YouTube video URL",
                "dependency" => Array(
                    'element' => 'video_display_type', 
                    'value' => array('embeded_video_youtube', 'embeded_video_youtube_popup'),
                )
            ),
            array(
	            "type" => "dropdown",
	            "admin_label" => true,
	            "class" => "",
	            "heading" => esc_html__("Animation", "vitrine"),
	            "param_name" => "animation",
	            "description" => esc_html__("Select animation type", "vitrine") ,
		        "value" => $animations,
                "group" => esc_html__('Animation','vitrine')
            ),
            array(
	            "type" => "textfield",
	            "admin_label" => true,
	            "class" => "",
	            "heading" => esc_html__("Animation Delay", "vitrine"),
	            "param_name" => "animation_delay",
	            "value" => "",
                "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                "group" => esc_html__('Animation','vitrine')
            ),
			array(
			    "type" => "vc_multiselect",
			    "class" => "",
			    "heading" => esc_html__("Animation in Responsive", "vitrine"),
			    "param_name" => "responsive_animation",
			    "options" => array("disable" => "Disable animation"),
			    "value" => "disable",
			    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
			    "group" => esc_html__('Animation','vitrine')
			)
		)
	)
);

/*-----------------------------------------------------------------------------------*/
/* Image Box
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "ImageBox",
		"base" => "imagebox",
		"category" => 'By Epico',
		"icon" => "icon-wpb-imagebox",
        "weight" => 9,
		"params" => array(
				array(
					"type" => "attach_image",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Image URL", "vitrine"),
					"param_name" => "image_url",
					"value" => "",
					"description" => esc_html__("URL of the image", "vitrine")
				),
               array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Hover", "vitrine"),
					"param_name" => "image_hover",
				    "value" => array(
                       "Enable" => "enable",
		               "Disable" => "disable"
	                ),
					"description" => esc_html__("You can Enable Or Disable ImageBox hover", "vitrine") ,   
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Hover shadow", "vitrine"),
					"param_name" => "image_hover_shadow",
				    "value" => array(
                        "Disable" => "disable",
                        "Enable" => "enable",
	                ),
					"description" => esc_html__("You can Enable Or Disable ImageBox hover shadow", "vitrine") ,   
				),
                array(
                    "type" => "vc_imageselect",
                    "admin_label" => true,
                    "class" => "presets",
                    "heading" => esc_html__("Hover color presets", "vitrine"),
                    "param_name" => "image_hover_color_preset",
                    "description" => esc_html__("Select hover color.", "vitrine") , 
                     "value" => array(
                        "c0392b" => "c0392b",
                        "e74c3c" => "e74c3c",
                        "d35400" => "d35400",
                        "e67e22" => "e67e22",
                        "f39c12" => "f39c12",
                        "f1c40f" => "f1c40f",
                        "1abc9c" => "1abc9c",
                        "2ecc71" => "2ecc71",
                        "3498db" => "3498db",
                        "01558f" => "01558f",
                        "9b59b6" => "9b59b6",
                        "ecf0f1" => "ecf0f1",
                        "bdc3c7" => "bdc3c7",
                        "7f8c8d" => "7f8c8d",
                        "95a5a6" => "95a5a6",
                        "34495e" => "34495e",
                        "2e2e2e" => "2e2e2e",
                        "custom-color" => "custom"
                    ),
                    "dependency" => Array(
                        'element' => 'image_hover', 
                        'value' => 'enable'
                    )
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Hover Color", "vitrine"),
                    "param_name" => "image_hover_color_custom",
                    "value" => "",
                    "description" => esc_html__("Select custom hover color ", "vitrine") ,
                    "dependency" => Array(
                        'element' => 'image_hover_color_preset', 
                        'value' => 'custom'
                    )
                ),
                array(
					"type" => "textarea",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title", "vitrine"),
					"param_name" => "title",
					"value" => "",
					"description" => esc_html__("Enter title text", "vitrine") ,   
				),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title Font Size", "vitrine"), 
                    "param_name" => "image_title_size",
                    "description" => esc_html__("Select the font size of the title.", "vitrine") ,
                    "value" => $fontsize,
                ),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title color ", "vitrine"),
					"param_name" => "title_color",
					"value" => "",
					"description" => esc_html__("Select optional title color.", "vitrine") ,  
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Subtitle", "vitrine"),
					"param_name" => "subtitle",
					"value" => "",
					"description" => esc_html__("Enter Subtitle text", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Subtitle color ", "vitrine"),
					"param_name" => "subtitle_color",
					"value" => "",
					"description" => esc_html__("Select optional Subtitle color.", "vitrine") ,  
				),
                array(
					"type" => "textarea",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Text", "vitrine"),
					"param_name" => "vccontent",
					"value" => "",
					"description" => esc_html__("Enter your text content here", "vitrine") ,   
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content Font Size", "vitrine"),
					"param_name" => "content_fontsize",
					"description" => esc_html__("Select content font size.", "vitrine") , 
					 "value" => $contentfontsize,
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Text Color", "vitrine"),
					"param_name" => "image_text_color",
					"value" => "",
					"description" => esc_html__("Select optional text color.", "vitrine") ,  
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Text Align", "vitrine"), 
					"param_name" => "image_text_align",
					"description" => esc_html__("Select text align", "vitrine") ,
					"value" => array(
						"Left" => "left",
						"Right" => "right",
                        "Center" => "center",
					),
				),
                array(
	                "type" => "dropdown",
	                "admin_label" => true,
	                "class" => "",
	                "heading" => esc_html__("Font type", "vitrine"),
	                "param_name" => "font_type",
	                "description" => esc_html__("Select theme default/custom font", "vitrine") ,
		            "value" => array(
						"Default of theme" => "default",
						"Custom font" => 'custom'   
					),
                ),
                array(
                    'type' => 'google_fonts',
                    'param_name' => 'google_fonts',
                    'value' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                    'settings' => array(
                        'fields' => array(
                            'font_family_description' => esc_html__( 'Select font family.', 'vitrine' ),
                            'font_style_description' => esc_html__( 'Select font styling.', 'vitrine' ),
                        ),
                    ),
                    "dependency" => Array(
                        'element' => 'font_type', 
                        'value' => 'custom'
                    )
                ),
                array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Background Color", "vitrine"),
					"param_name" => "image_text_background_color",
					"value" => "",
					"description" => esc_html__("Select optional background color ", "vitrine") ,  
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("content border", "vitrine"),
					"param_name" => "imagebox_content_border",
				    "value" => array(
                       "Enable" => "enable",
		               "Disable" => "disable"
	                ),
					"description" => esc_html__("You can Enable Or Disable ImageBox content border", "vitrine"),
                ),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Border Color", "vitrine"),
					"param_name" => "image_text_border_color",
					"value" => "",
					"description" => esc_html__("Select optional border color ", "vitrine") ,  
                    "dependency" => Array(
                        'element' => 'imagebox_content_border', 
                        'value' => 'enable'
                    )
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Link", "vitrine"),
					"param_name" => "url",
					"value" => "",
					"description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
				),
                 array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Link Target", "vitrine"), 
					"param_name" => "target",
					"description" => esc_html__("Open link in the same page or a new browser page.", "vitrine") ,
					"value" => array(
						"Open in same window" => "_self",
						"Open in new window" => "_blank"   
					),
				),
                array(
	                "type" => "dropdown",
	                "admin_label" => true,
	                "class" => "",
	                "heading" => esc_html__("Animation", "vitrine"),
	                "param_name" => "image_animation",
	                "description" => esc_html__("Select animation type", "vitrine") ,
		            "value" => $animations,
                    "group" => esc_html__('Animation','vitrine')
                ),
                array(
	                "type" => "textfield",
	                "admin_label" => true,
	                "class" => "",
	                "heading" => esc_html__("Animation Delay", "vitrine"),
	                "param_name" => "image_animation_delay",
	                "value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
                ),
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				)
			)
		)
    );
/*-----------------------------------------------------------------------------------*/
/* Animated Text
/*-----------------------------------------------------------------------------------*/
vc_map( 
	array(
		"name" => "Animated Text",
		"base" => "animatedtext",
		"category" => 'By Epico',
		"icon" => "icon-wpb-animatedtitle",
        "holder" => "div",
        "weight" => 9,
		"params" => array(
                array(
					"type" => "textarea",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title", "vitrine"),
					"param_name" => "title",
					"value" => "",
					"description" => esc_html__("Enter title text", "vitrine") ,   
				),
                array(
	                "type" => "dropdown",
	                "admin_label" => true,
	                "class" => "",
	                "heading" => esc_html__("Styles", "vitrine"),
	                "param_name" => "animatedtext_style",
	                "description" => esc_html__("Select one of available styles.", "vitrine") ,
		            "value" => array(
						"Animated text with Image background" => "with_image",
						"Animated text" => 'text_only'   
					),
                ),
				array(
					"type" => "attach_image",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Image URL", "vitrine"),
					"param_name" => "image_url",
					"value" => "",
					"description" => esc_html__("URL of the image", "vitrine"),
                    "dependency" => Array(
                    'element' => 'animatedtext_style', 
                    'value_not_equal_to' =>'text_only',
                 )
				),
                array(
                    "type" => "colorpicker",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Front text color", "vitrine"),
                    "param_name" => "title_front_color",
                    "value" => "",
                    "description" => esc_html__("Select a color for your front text.", "vitrine") ,
                    "dependency" => Array(
                    'element' => "animatedtext_style", 
                    'value_not_equal_to' =>'text_only',
                 )
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Text color", "vitrine"),
                    "param_name" => "title_back_color",
                    "value" => "",
                    "description" => esc_html__("Select a color for your main text.", "vitrine") ,
                ),
                array(
	                "type" => "dropdown",
	                "admin_label" => true,
	                "class" => "",
	                "heading" => esc_html__("Font type", "vitrine"),
	                "param_name" => "font_type",
	                "description" => esc_html__("Select theme default/custom font", "vitrine") ,
		            "value" => array(
						"Default of theme" => "default",
						"Custom font" => 'custom'   
					),
                ),
                array(
                    'type' => 'google_fonts',
                    'param_name' => 'google_fonts',
                    'value' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                    'settings' => array(
                        'fields' => array(
                            'font_family_description' => esc_html__( 'Select font family.', 'vitrine' ),
                            'font_style_description' => esc_html__( 'Select font styling.', 'vitrine' ),
                        ),
                    ),
                    "dependency" => Array(
                        'element' => 'font_type', 
                        'value' => 'custom'
                    )
                ),
                array(
                    "type" => "textfield",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Font Size", "vitrine"), 
                    "param_name" => "animatedtext_font_size",
                    "description" => esc_html__("Enter the size of font (minimum possible value is equal to 1), for example 14.", "vitrine") ,
                    "value" => "30",
                ),
                array(
	                "type" => "dropdown",
	                "admin_label" => true,
	                "class" => "",
	                "heading" => esc_html__("Animation duration", "vitrine"),
	                "param_name" => "animatedtext_speed",
	                "description" => esc_html__("Select one of available options.", "vitrine") ,
		            "value" => array(
						"Faster" => "4",
						"Fast" => "8",
						"Medium" => "12",
						"Slow" => "16",
						"Slower" => "24",
					),
                ),
			)
		)
    );
/*-----------------------------------------------------------------------------------*/
/* banner 
/*-----------------------------------------------------------------------------------*/

vc_map( 
    array(
        "name" => "Banner",
        "base" => "banner",
        "category" => 'By Epico',
        "icon" => "icon-wpb-banner",
        "weight" => 9,
        "params" => array(
                array(
                    "type" => "attach_image",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Image URL", "vitrine"),
                    "param_name" => "image_url",
                    "value" => "",
                    "description" => esc_html__("URL of the image", "vitrine")
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => false,
                    "heading" => esc_html__("Style", "vitrine"), 
                    "param_name" => "style",
                    "description" => esc_html__("Select style of hover", "vitrine") ,
                    "value" => array("Large subtitle, small title" => "style1", "Large title, small subtitle"=>"style2"),
                ),  
                array(
	                "type" => "dropdown",
	                "admin_label" => true,
	                "class" => "",
	                "heading" => esc_html__("Font type", "vitrine"),
	                "param_name" => "font_type",
	                "description" => esc_html__("Select theme default/custom font", "vitrine") ,
		            "value" => array(
						"Default of theme" => "default",
						"Custom font" => 'custom'   
					),
                ),
                array(
                    'type' => 'google_fonts',
                    'param_name' => 'google_fonts',
                    'value' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                    'settings' => array(
                        'fields' => array(
                            'font_family_description' => esc_html__( 'Select font family.', 'vitrine' ),
                            'font_style_description' => esc_html__( 'Select font styling.', 'vitrine' ),
                        ),
                    ),
                    "dependency" => Array(
                        'element' => 'font_type', 
                        'value' => 'custom'
                    )
                ),				
                array(
                    "type" => "textarea",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Title", "vitrine"),
                    "param_name" => "title",
                    "value" => "",
                    "description" => esc_html__("Enter title text", "vitrine") ,   
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => false,
                    "heading" => esc_html__("Title Font Size", "vitrine"), 
                    "param_name" => "title_size",
                    "description" => esc_html__("Select the font size of the title.", "vitrine") ,
                    "value" => $fontsize,
                ),
               array(
                    "type" => "colorpicker",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Title color ", "vitrine"),
                    "param_name" => "title_color",
                    "value" => "",
                    "description" => esc_html__("Select optional title color.", "vitrine") ,  
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Line", "vitrine"), 
                    "param_name" => "line",
                    "options" => array("disable" => "Disable line"),
                    "description" => esc_html__("Disable line around the product box", "vitrine"),
                     "value" => "",
                ),     
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => false,
                    "heading" => esc_html__("Line's Color", "vitrine"),
                    "param_name" => "title_border_color",
                    "value" => "",
                    "description" => esc_html__("Select optional border color.", "vitrine") ,
                ),
                array(
                    "type" => "textfield",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Subtitle", "vitrine"),
                    "param_name" => "subtitle",
                    "value" => "",
                    "description" => esc_html__("Enter Subtitle text", "vitrine") ,   
                ),
               array(
                    "type" => "colorpicker",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Subtitle color ", "vitrine"),
                    "param_name" => "subtitle_color",
                    "value" => "",
                    "description" => esc_html__("Select optional Subtitle color.", "vitrine") ,  
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Hover", "vitrine"),
                    "param_name" => "hover",
                    "value" => array(
                       "Enable" => "enable",
                       "Disable" => "disable"
                    ),
                    "description" => esc_html__("You can Enable Or Disable hover", "vitrine") ,   
                ),
               array(
                    "type" => "vc_imageselect",
                    "admin_label" => false,
                    "class" => "presets",
                    "heading" => esc_html__("Hover color presets", "vitrine"),
                    "param_name" => "hover_color_preset",
                    "description" => esc_html__("Select Hover overlay color.", "vitrine") , 
                     "value" => array(
                        "c0392b" => "c0392b",
                        "e74c3c" => "e74c3c",
                        "d35400" => "d35400",
                        "e67e22" => "e67e22",
                        "f39c12" => "f39c12",
                        "f1c40f" => "f1c40f",
                        "1abc9c" => "1abc9c",
                        "2ecc71" => "2ecc71",
                        "3498db" => "3498db",
                        "01558f" => "01558f",
                        "9b59b6" => "9b59b6",
                        "ecf0f1" => "ecf0f1",
                        "bdc3c7" => "bdc3c7",
                        "7f8c8d" => "7f8c8d",
                        "95a5a6" => "95a5a6",
                        "34495e" => "34495e",
                        "2e2e2e" => "2e2e2e",
                        "custom-color" => "custom"
                    ),
                    "dependency" => Array(
                        'element' => 'hover', 
                        'value' => 'enable'
                    )
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("hove Color", "vitrine"),
                    "param_name" => "hover_color",
                    "value" => "",
                    "description" => esc_html__("Select custom hover color ", "vitrine") ,
                    "dependency" => Array(
                        'element' => "hover_color_preset", 
                        'value' => "custom"
                    ) 
                ),    
                array(
                    "type" => "vc_link",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Link", "vitrine"),
                    "param_name" => "url",
                    "value" => "",
                    "description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Link color ", "vitrine"),
                    "param_name" => "link_color",
                    "value" => "",
                    "description" => esc_html__("Select optional link color.", "vitrine") ,  
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Animation", "vitrine"),
                    "param_name" => "animation",
                    "description" => esc_html__("Select animation type", "vitrine") ,
                    "value" => $animations,
                    "group" => esc_html__('Animation','vitrine')
                ),
                array(
                    "type" => "textfield",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Animation Delay", "vitrine"),
                    "param_name" => "delay",
                    "value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
                ),
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				)
            )
        )
    );

/*-----------------------------------------------------------------------------------*/
/*  Custom image Box - Creative Image Box
/*-----------------------------------------------------------------------------------*/

vc_map( 
    array(
        "name" => "Creative Imagebox",
        "base" => "custom_imagebox",
        "category" => 'By Epico',
        "weight" => 9,
        "icon" => "icon-wpb-custom-imagebox",
        "params" => array(
                array(
                    "type" => "attach_image",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Image URL", "vitrine"),
                    "param_name" => "image_url",
                    "value" => "",
                    "description" => esc_html__("URL of the image", "vitrine")
                ),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Image Hover", "vitrine"),
					"param_name" => "image_hover",
				    "value" => array(
                       "Enable" => "enable",
		               "Disable" => "disable"
	                ),
					"description" => esc_html__("Enable Or disable imagebox hover", "vitrine") ,   
				),
                array(
                    "type" => "vc_imageselect",
                    "admin_label" => true,
                    "class" => "presets",
                    "heading" => esc_html__("Hover color presets", "vitrine"),
                    "param_name" => "image_hover_color_preset",
                    "description" => esc_html__("Select hover color.", "vitrine") , 
                     "value" => array(
                        "c0392b" => "c0392b",
                        "e74c3c" => "e74c3c",
                        "d35400" => "d35400",
                        "e67e22" => "e67e22",
                        "f39c12" => "f39c12",
                        "f1c40f" => "f1c40f",
                        "1abc9c" => "1abc9c",
                        "2ecc71" => "2ecc71",
                        "3498db" => "3498db",
                        "01558f" => "01558f",
                        "9b59b6" => "9b59b6",
                        "ecf0f1" => "ecf0f1",
                        "bdc3c7" => "bdc3c7",
                        "7f8c8d" => "7f8c8d",
                        "95a5a6" => "95a5a6",
                        "34495e" => "34495e",
                        "2e2e2e" => "2e2e2e",
                        "custom-color" => "custom"
                    ),
                    "dependency" => Array(
                        'element' => 'image_hover', 
                        'value' => 'enable'
                    )
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Hover Color", "vitrine"),
                    "param_name" => "image_hover_color_custom",
                    "value" => "",
                    "description" => esc_html__("Select custom hover color ", "vitrine") ,
                    "dependency" => Array(
                        'element' => 'image_hover_color_preset', 
                        'value' => 'custom'
                    )
                ),
                array(
                    "type" => "textarea",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title", "vitrine"),
                    "param_name" => "title",
                    "value" => "",
                    "description" => esc_html__("Enter title text", "vitrine") ,   
                ),
               array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title Color", "vitrine"),
                    "param_name" => "title_color",
                    "value" => "",
                    "description" => esc_html__("Select optional title color.", "vitrine") ,  
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title Font Size", "vitrine"),
                    "param_name" => "title_fontsize",
                    "description" => esc_html__("Select the font size of the title.", "vitrine") , 
                     "value" => $fontsize,
                ),
                 array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Box Position", "vitrine"), 
                    "param_name" => "box_position",
                    "description" => esc_html__("Select the positioning of the box.", "vitrine") ,
                    "value" => array(
                        "Left" => "left",
                        "Right" => "right",
                    ),
                ),
                 array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title border", "vitrine"), 
                    "param_name" => "title_border",
                    "description" => esc_html__("Select border type", "vitrine") ,
                    "value" => array(
                        "None" => "none",
                        "Left" => "left",
                    ),
                ),                 
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title Border Color", "vitrine"),
                    "param_name" => "title_border_color",
                    "value" => "#272727",
                    "description" => esc_html__("Select optional border color.", "vitrine") ,
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Subtitle", "vitrine"),
                    "param_name" => "subtitle",
                    "value" => "",
                    "description" => esc_html__("Enter subtitle text", "vitrine") ,   
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Subtitle Color", "vitrine"),
                    "param_name" => "subtitle_color",
                    "value" => "",
                    "description" => esc_html__("Select optional subtitle color.", "vitrine") ,  
                ),
                array(
                    "type" => "textarea",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Content", "vitrine"),
                    "param_name" => "text_content",
                    "value" => "",
                    "description" => esc_html__("Enter your text content here", "vitrine") ,   
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Content Font Size", "vitrine"),
                    "param_name" => "content_fontsize",
                    "description" => esc_html__("Select content font size.", "vitrine") , 
                     "value" => $contentfontsize,
                ),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Content Color", "vitrine"),
                    "param_name" => "text_content_color",
                    "value" => "",
                    "description" => esc_html__("Select optional content's color ", "vitrine") ,  
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Content Font Size", "vitrine"),
                    "param_name" => "content_fontsize",
                    "description" => esc_html__("Select content font size.", "vitrine") , 
                     "value" => $contentfontsize,
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Link", "vitrine"),
                    "param_name" => "url",
                    "value" => "",
                    "description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Link Target", "vitrine"), 
                    "param_name" => "target",
                    "description" => esc_html__("Open link in the same page or a new browser page.", "vitrine") ,
                    "value" => array(
                        "Open in same window" => "_self",
                        "Open in new window" => "_blank"   
                    ),
                ),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Background Color ", "vitrine"),
                    "param_name" => "bg_color",
                    "value" => "",
                    "description" => esc_html__("Select background color ", "vitrine") ,  
                ),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Border Color ", "vitrine"),
                    "param_name" => "border_color",
                    "value" => "",
                    "description" => esc_html__("Select border color ", "vitrine") ,  
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Border Width ", "vitrine"), 
                    "param_name" => "content_border_width",
                    "value" => array(
		                "2px" => "2px",
                        "3px" => "3px",
		                "4px" => "4px",
                    ),
                ),
            )
        )
);

/*-----------------------------------------------------------------------------------*/
/* text Box
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "TextBox",
		"base" => "textbox",
		"category" => 'By Epico',
        'weight' => 8,
		"icon" => "icon-wpb-textbox",
		"params" => array(
                array(
					"type" => "textarea",
					"class" => "",
                    "admin_label" => true,
					"heading" => esc_html__("Title", "vitrine"),
					"param_name" => "title",
					"value" => "",
					"description" => esc_html__("Enter title text", "vitrine") ,   
				),
                array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title Color", "vitrine"),
					"param_name" => "title_color",
					"value" => "",
					"description" => esc_html__("Select optional title color.", "vitrine") ,  
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title Font Size", "vitrine"),
					"param_name" => "title_fontsize",
					"description" => esc_html__("Select the font size of the title.", "vitrine") , 
					 "value" => $fontsize,
				),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Title Border", "vitrine"), 
                    "param_name" => "text_title_border",
                    "description" => esc_html__("Select title border", "vitrine") ,
                    "value" => array(
                        "None" => "none",
                        "Left Border" => "left_border",
                        "Right Border" => "right_border",
                        "Top Border" => "top_border",
                        "Bottom Border" => "bottom_border",
                    ),
                ),
                array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title border/underline color ", "vitrine"),
					"param_name" => "text_border_underline_color",
					"value" => "",
					"description" => esc_html__("Select an optional color for title border or underline", "vitrine") , 
                    "dependency" => Array(
                        'element' => "text_title_border", 
                        'value' => array('right_border','left_border','top_border','bottom_border')
                    )
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title Alignment", "vitrine"), 
					"param_name" => "text_under_align",
					"value" => array(
						"Left" => "left",
						"Right" => "right",
                        "Center" => "center",
					),
                    "dependency" => Array(
                        'element' => "text_title_border", 
                        'value' => array('top_border','bottom_border','none')
                    )
				),
                array(
					"type" => "textfield",
					"class" => "",
                    "admin_label" => true,
					"heading" => esc_html__("Subtitle", "vitrine"),
					"param_name" => "subtitle",
					"value" => "",
					"description" => esc_html__("Enter subtitle text", "vitrine") ,   
				),
                array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Subtitle Color", "vitrine"),
					"param_name" => "subtitle_color",
					"value" => "",
					"description" => esc_html__("Select optional subtitle color.", "vitrine") ,  
				),
                array(
					"type" => "textarea",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content", "vitrine"),
					"param_name" => "text_content",
					"value" => "",
					"description" => esc_html__("Enter your text content here", "vitrine") ,   
				),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Content Alignment", "vitrine"), 
                    "param_name" => "content_align",
                    "description" => esc_html__("Select content alignment", "vitrine") ,
                    "value" => array(
                        "Left" => "left",
                        "Right" => "right",
                        "Center" => "center",
                    ),
                ),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content Font Size", "vitrine"),
					"param_name" => "content_fontsize",
					"description" => esc_html__("Select content font size.", "vitrine") , 
					 "value" => $contentfontsize,
				),
                array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content Color", "vitrine"),
					"param_name" => "text_content_color",
					"value" => "",
					"description" => esc_html__("Select optional color for content.", "vitrine") ,  
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
	                "heading" => esc_html__("Font type", "vitrine"),
	                "param_name" => "font_type",
	                "description" => esc_html__("Select theme default/custom font", "vitrine") ,
		            "value" => array(
						"Default of theme" => "default",
						"Custom font" => 'custom'   
					),
                ),
                array(
                    'type' => 'google_fonts',
                    'param_name' => 'google_fonts',
                    'value' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                    'settings' => array(
                        'fields' => array(
                            'font_family_description' => esc_html__( 'Select font family.', 'vitrine' ),
                            'font_style_description' => esc_html__( 'Select font styling.', 'vitrine' ),
                        ),
                    ),
                    "dependency" => Array(
                        'element' => 'font_type', 
                        'value' => 'custom'
                    )
                ),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation", "vitrine"),
					"param_name" => "text_animation",
					"description" => esc_html__("Select animation type", "vitrine") ,
					 "value" => $animations,
                     "group" => esc_html__('Animation','vitrine'),
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation Delay", "vitrine"),
					"param_name" => "text_animation_delay",
					"value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Link", "vitrine"),
					"param_name" => "url",
					"value" => "",
					"description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
				),
                 array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Link Target", "vitrine"), 
					"param_name" => "target",
					"description" => esc_html__("Open link in the same page or a new browser page.", "vitrine") ,
					"value" => array(
						"Open in same window" => "_self",
						"Open in new window" => "_blank"   
					),
				),
			)
		)
);

/*-----------------------------------------------------------------------------------*/
/* Custom ( creative ) textBox 
/*-----------------------------------------------------------------------------------*/

vc_map( 
    array(
        "name" => "Textbox Creative",
        "base" => "custom_textbox",
        "category" => 'By Epico',
        'weight' => 8,
        "icon" => "icon-wpb-custom-textbox",
        "params" => array(
                array(
                    "type" => "textarea",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title", "vitrine"),
                    "param_name" => "title",
                    "value" => "",
                    "description" => esc_html__("Enter title text", "vitrine") ,   
                ),
               array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title Color", "vitrine"),
                    "param_name" => "title_color",
                    "value" => "",
                    "description" => esc_html__("Select optional title color.", "vitrine") ,  
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title Font Size", "vitrine"),
                    "param_name" => "title_fontsize",
                    "description" => esc_html__("Select the font size of the title.", "vitrine") , 
                     "value" => $fontsize,
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title Border", "vitrine"), 
                    "param_name" => "text_title_border",
                    "description" => esc_html__("Select title border.", "vitrine") ,
                    "value" => array(
                        "None" => "none",
                        "Left Border" => "left_border",
                        "Right Border" => "right_border",
                        "Top Border" => "top_border",
                        "Bottom Border" => "bottom_border",
                    ),
                ),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Tile's Border/Underline Color ", "vitrine"),
                    "param_name" => "text_border_underline_color",
                    "value" => "",
                    "description" => esc_html__("Select optional border or underline color ", "vitrine") , 
                    "dependency" => Array(
                        'element' => "text_title_border", 
                        'value' => array('right_border','left_border','top_border','bottom_border')
                    )
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title Alignment", "vitrine"), 
                    "param_name" => "text_under_align",
                    "description" => esc_html__("Select title's underline alignment", "vitrine") ,
                    "value" => array(
                        "Left" => "left",
                        "Right" => "right",
                        "Center" => "center",
                    ),
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Subtitle", "vitrine"),
                    "param_name" => "subtitle",
                    "value" => "",
                    "description" => esc_html__("Enter subtitle text", "vitrine") ,   
                ),
                array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Subtitle color ", "vitrine"),
					"param_name" => "subtitle_color",
					"value" => "",
					"description" => esc_html__("Select optional subtitle color.", "vitrine") ,  
				),
                array(
                    "type" => "textarea",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Content", "vitrine"),
                    "param_name" => "text_content",
                    "value" => "",
                    "description" => esc_html__("Enter your content here", "vitrine") ,   
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Content Alignment", "vitrine"), 
                    "param_name" => "text_align",
                    "description" => esc_html__("Select text alignment.", "vitrine") ,
                    "value" => array(
                        "Left" => "left",
                        "Right" => "right",
                        "Center" => "center",
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Content Font Size", "vitrine"),
                    "param_name" => "content_fontsize",
                    "description" => esc_html__("Select content font size.", "vitrine") , 
                     "value" => $contentfontsize,
                ),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Content Color", "vitrine"),
                    "param_name" => "text_content_color",
                    "value" => "",
                    "description" => esc_html__("Select optional content color.", "vitrine") ,  
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Link", "vitrine"),
                    "param_name" => "url",
                    "value" => "",
                    "description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Link Target", "vitrine"), 
                    "param_name" => "target",
                    "description" => esc_html__("Open link in the same page or a new browser page.", "vitrine") ,
                    "value" => array(
                        "Open in same window" => "_self",
                        "Open in new window" => "_blank"   
                    ),
                ),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Background Color ", "vitrine"),
                    "param_name" => "bg_color",
                    "value" => "",
                    "description" => esc_html__("Select background color ", "vitrine") ,  
                ),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Border Color ", "vitrine"),
                    "param_name" => "border_color",
                    "value" => "",
                    "description" => esc_html__("Select border color ", "vitrine") ,  
                ),
               array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Border Width ", "vitrine"), 
                    "param_name" => "content_border_width",
                    "value" => array(
		                "2px" => "2px",
                        "3px" => "3px",
		                "4px" => "4px",
                    ),
                ),
            ),

        )
);


/*-----------------------------------------------------------------------------------*/
/* Custom Heading - Title 
/*-----------------------------------------------------------------------------------*/

vc_map( 
    array(
        "name" => "Title",
        "base" => "custom_title",
        "category" => 'By Epico',
        'weight' => 8,
        "icon" => "icon-wpb-custom-title",
        "params" => array(
                array(
                    "type" => "textarea",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Title", "vitrine"),
                    "param_name" => "title",
                    "value" => "",
                    "description" => esc_html__("Enter title text", "vitrine") ,   
                ),
				array(
	                "type" => "dropdown",
	                "admin_label" => true,
	                "class" => "",
	                "heading" => esc_html__("Font type", "vitrine"),
	                "param_name" => "font_type",
	                "description" => esc_html__("Select theme default/custom font", "vitrine") ,
		            "value" => array(
						"Default of theme" => "default",
						"Custom font" => 'custom'   
					),
                ),
                array(
                    'type' => 'google_fonts',
                    'param_name' => 'google_fonts',
                    'value' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                    'settings' => array(
                        'fields' => array(
                            'font_family_description' => esc_html__( 'Select font family.', 'vitrine' ),
                            'font_style_description' => esc_html__( 'Select font styling.', 'vitrine' ),
                        ),
                    ),
                    "dependency" => Array(
                        'element' => 'font_type', 
                        'value' => 'custom'
                    )
                ),
               array(
                    "type" => "colorpicker",
                    "class" => "",
                    "heading" => esc_html__("Title color ", "vitrine"),
                    "param_name" => "title_color",
                    "value" => "",
                    "description" => esc_html__("Select optional title's color ", "vitrine") ,  
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Title Font Size", "vitrine"),
                    "param_name" => "title_fontsize",
                    "description" => esc_html__("Select the font size of the title.", "vitrine") , 
                     "value" => $Customfontsize,
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Letter Spacing", "vitrine"),
                    "param_name" => "letter_spacing",
                    "description" => esc_html__("Select a spacing amount for your title.", "vitrine") , 
                    "value" => array(
                        "0" => "0",
                        "1" => "1",
                        "2" => "2",
                        "3" => "3",
                        "4" => "4",
                    ),
                ),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title Background Style", "vitrine"), 
					"param_name" => "title_background_style",
					"description" => esc_html__("Select a style for your title's background.", "vitrine") ,
					"value" => array(
                       "Icon Background" => "iconbackground",
		               "Shape Background" => "shapebackground",
                       "text Background" => "textbackground",
					),
				),
                 array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Background Title", "vitrine"),
					"param_name" => "bg_title",
					"value" => "",
					"description" => esc_html__("Enter title's background text", "vitrine") ,
                    "dependency" => Array(
                        'element' => "title_background_style", 
                        'value' => "textbackground"
                    )
				),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "heading" => esc_html__("Background title color", "vitrine"),
                    "param_name" => "bg_title_color",
                    "value" => "",
                    "description" => esc_html__("Select optional background title's color", "vitrine") ,
                    "dependency" => Array(
                        'element' => "title_background_style", 
                        'value' => "textbackground"
                    )  
                ),
                array(
					"type" => "vc_icons",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon", "vitrine"),
					"param_name" => "icon",
					"value" => "",
					"description" => esc_html__("Select an icon to be located at title's background", "vitrine") ,
                    "dependency" => Array(
                        'element' => "title_background_style", 
                        'value' => "iconbackground"
                    )
				),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "heading" => esc_html__("Background icon color", "vitrine"),
                    "param_name" => "bg_icon_color",
                    "value" => "",
                    "description" => esc_html__("Select optional background icon's color", "vitrine") ,
                    "dependency" => Array(
                        'element' => "title_background_style", 
                        'value' => "iconbackground"
                    )  
                ),
                array(
                    "type" => "vc_imageselect",
                    "admin_label" => true,
                    "heading" => esc_html__("Shape", "vitrine"),
                    "class" => "shapes",
                    "param_name" => "style",
                    "description" => esc_html__("Select the shape that is going to be shown in title background.", "vitrine") , 
                    "value" => array(
                        "line" => "line",
                        "circle" => "circle",
                        "square" => "square",
                        "rotated_square" => "rotated_square",
                        "triangle" => "triangle",
                    ),
                    "dependency" => Array(
                        'element' => "title_background_style", 
                        'value' => "shapebackground"
                    )
                ),
                array(
                    "type" => "colorpicker",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_html__("Hover line color ", "vitrine"),
                    "param_name" => "hoverline_color",
                    "value" => "",
                    "description" => esc_html__("Select optional hover line color ", "vitrine") ,
                    "dependency" => Array(
                        'element' => "style", 
                        'value' => array("line")
                    )
                ),
                array(
                    "type" => "colorpicker",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_html__("Shape border color ", "vitrine"),
                    "param_name" => "shape_border_color",
                    "value" => "",
                    "description" => esc_html__("Select shape border color ", "vitrine") ,
                    "dependency" => Array(
                        'element' => "style", 
                        'value' => array("circle","square","rotated_square","triangle")
                    )
                ),
                array(
                    "type" => "colorpicker",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_html__("Shape fill color ", "vitrine"),
                    "param_name" => "shape_fill_color",
                    "value" => "",
                    "description" => esc_html__("Select shape fill color ", "vitrine") ,
                    "dependency" => Array(
                        'element' => "style", 
                        'value' => array("circle","square","rotated_square","triangle")
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_html__("Animation", "vitrine"),
                    "param_name" => "title_animation",
                    "description" => esc_html__("Select animation type", "vitrine") ,
                     "value" => $animations,
                     "group" => esc_html__('Animation','vitrine')
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_html__("Animation Delay", "vitrine"),
                    "param_name" => "title_animation_delay",
                    "value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
                ),
                array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				)
            )
        )
);


/*-----------------------------------------------------------------------------------*/
/* Icon Box top - no border
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "IconBox - Top",
		"base" => "iconbox_top_noborder",
		"category" => 'By Epico',
        'weight' => 8,
		"icon" => "icon-wpb-iconbox-noborder",
		"params" => array(
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title", "vitrine"),
					"param_name" => "title",
					"value" => "",
					"description" => esc_html__("Enter title text", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title Color ", "vitrine"),
					"param_name" => "title_color",
					"value" => "",
					"description" => esc_html__("Select optional title color.", "vitrine") ,  
				),
                array(
					"type" => "textarea",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content", "vitrine"),
					"param_name" => "content_text",
					"value" => "",
					"description" => esc_html__("Enter some content for your IconBox.", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content Color", "vitrine"),
					"param_name" => "content_color",
					"value" => "",
					"description" => esc_html__("Select optional content color", "vitrine") ,  
				),
                array(
					"type" => "vc_icons",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon", "vitrine"),
					"param_name" => "icon",
					"value" => "",
					"description" => esc_html__("Select an icon to be located at the top of the box", "vitrine") ,   
				),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Icon Alignment", "vitrine"), 
                    "param_name" => "alignment",
                    "description" => esc_html__("Choose one of available alignments.", "vitrine") ,
                    "value" => array(
                        "Right" => "right_alignment",
                        "Center" => "center_alignment",
                        "Left" => "left_alignment"   
                    ),
               ),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon Color", "vitrine"),
					"param_name" => "icon_color",
					"value" => "",
					"description" => esc_html__("Select optional icon color.", "vitrine") ,  
				),
                 array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation", "vitrine"),
					"param_name" => "icon_animation",
					"description" => esc_html__("Select an animation for Icon box.", "vitrine") , 
					 "value" => $animations,
                     "group" => esc_html__('Animation','vitrine')
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation Delay", "vitrine"),
					"param_name" => "icon_animation_delay",
					"value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				),
                array(
                    "type" => "vc_link",
                    "admin_label" => false,
                    "class" => "",
                    "heading" => esc_html__("Link", "vitrine"),
                    "param_name" => "url",
                    "value" => "",
                    "description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
                ),
			)
		)
);

/*-----------------------------------------------------------------------------------*/
/* Icon Box top rectangle
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "IconBox - Top Square ",
		"base" => "iconbox_rectangle",
        'weight' => 8,
		"category" => 'By Epico',
		"icon" => "icon-wpb-iconbox-rectangle",
		"params" => array(
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title", "vitrine"),
					"param_name" => "title",
					"value" => "",
					"description" => esc_html__("Enter title text", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title Color ", "vitrine"),
					"param_name" => "title_color",
					"value" => "",
					"description" => esc_html__("Select optional title color.", "vitrine") ,  
				),
                array(
					"type" => "textarea",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content", "vitrine"),
					"param_name" => "content_text",
					"value" => "",
					"description" => esc_html__("Enter some content for your IconBox", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content Color", "vitrine"),
					"param_name" => "content_color",
					"value" => "",
					"description" => esc_html__("Select optional content color", "vitrine") ,  
				),
                array(
					"type" => "vc_icons",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon", "vitrine"),
					"param_name" => "icon",
					"value" => "",
					"description" => esc_html__("Select an icon to be located at the top of the box.", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon Color", "vitrine"),
					"param_name" => "icon_color",
					"value" => "",
					"description" => esc_html__("Select optional icon color.", "vitrine") ,  
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon Background Style", "vitrine"), 
					"param_name" => "icon_background_fill",
					"description" => esc_html__("Select a style for your icon's background.", "vitrine") ,
					"value" => array(
                       "Fill Background" => "fillbackground",
		               "transparent Background" => "transparentbackground"
					),
				),
                array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon Border/Fill color", "vitrine"),
					"param_name" => "icon_border_color",
					"value" => "",
					"description" => esc_html__("Select optional icon border color", "vitrine") , 
				),
                 array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation", "vitrine"),
					"param_name" => "icon_animation",
					"description" => esc_html__("Select an animation for Icon box.", "vitrine") , 
					 "value" => $animations,
                     "group" => esc_html__('Animation','vitrine')
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation Delay", "vitrine"),
					"param_name" => "icon_animation_delay",
					"value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				),
                array(
                    "type" => "vc_link",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Link", "vitrine"),
                    "param_name" => "url",
                    "value" => "",
                    "description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
                ),
			)
		)
);

/*-----------------------------------------------------------------------------------*/
/* Icon Box top circle
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "IconBox - Top Circle ",
		"base" => "iconbox_circle",
		"category" => 'By Epico',
        'weight' => 8,
		"icon" => "icon-wpb-iconbox-circle",
		"params" => array(
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title", "vitrine"),
					"param_name" => "title",
					"value" => "",
					"description" => esc_html__("Enter title text", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title Color ", "vitrine"),
					"param_name" => "title_color",
					"value" => "",
					"description" => esc_html__("Select optional title color.", "vitrine") ,  
				),
                array(
					"type" => "textarea",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content", "vitrine"),
					"param_name" => "content_text",
					"value" => "",
					"description" => esc_html__("Enter some content for your Iconbox.", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content Color", "vitrine"),
					"param_name" => "content_color",
					"value" => "",
					"description" => esc_html__("Select optional content color", "vitrine") ,  
				),
                array(
					"type" => "vc_icons",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon", "vitrine"),
					"param_name" => "icon",
					"value" => "",
					"description" => esc_html__("Select an icon to be located at the top of the box.", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon Color", "vitrine"),
					"param_name" => "icon_color",
					"value" => "",
					"description" => esc_html__("Select optional icon color.", "vitrine") ,  
				),
                array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon Border Color", "vitrine"),
					"param_name" => "icon_border_color",
					"value" => "",
					"description" => esc_html__("Select optional icon border color.", "vitrine") , 
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon Background Style", "vitrine"), 
					"param_name" => "icon_background_fill",
					"description" => esc_html__("Select a style for your icon background.", "vitrine") ,
					"value" => array(
                       "Fill Background" => "fillbackground",
		               "transparent Background" => "transparentbackground"
					),
				),
                 array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation", "vitrine"),
					"param_name" => "icon_animation",
					"description" => esc_html__("Select an animation for Icon box.", "vitrine") , 
					 "value" => $animations,
                     "group" => esc_html__('Animation','vitrine')
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation Delay", "vitrine"),
					"param_name" => "icon_animation_delay",
					"value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				),
                array(
                    "type" => "vc_link",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Link", "vitrine"),
                    "param_name" => "url",
                    "value" => "",
                    "description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
                ),
			)
		)
);

/*-----------------------------------------------------------------------------------*/
/* Icon Box left 
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "IconBox - left ",
		"base" => "iconbox_left",
		"category" => 'By Epico',
        'weight' => 8,
		"icon" => "icon-wpb-iconbox-left",
		"params" => array(
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title", "vitrine"),
					"param_name" => "title",
					"value" => "",
					"description" => esc_html__("Enter title text", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Title Color ", "vitrine"),
					"param_name" => "title_color",
					"value" => "",
					"description" => esc_html__("Select optional title color.", "vitrine") ,  
				),
                array(
					"type" => "textarea",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content", "vitrine"),
					"param_name" => "content_text",
					"value" => "",
					"description" => esc_html__("Enter some content for your IconBox", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content Color", "vitrine"),
					"param_name" => "content_color",
					"value" => "",
					"description" => esc_html__("Select optional content color", "vitrine") ,  
				),
                array(
					"type" => "vc_icons",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon", "vitrine"),
					"param_name" => "icon",
					"value" => "",
					"description" => esc_html__("Select an icon to be located at the top of the box.", "vitrine") ,   
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Icon Color", "vitrine"),
					"param_name" => "icon_color",
					"value" => "",
					"description" => esc_html__("Select optional icon color.", "vitrine") ,  
				),
                 array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation", "vitrine"),
					"param_name" => "icon_animation",
					"description" => esc_html__("Select an animation for Icon box.", "vitrine") , 
					 "value" => $animations,
                     "group" => esc_html__('Animation','vitrine')
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation Delay", "vitrine"),
					"param_name" => "icon_animation_delay",
					"value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				),
               	array(
                    "type" => "vc_link",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Link", "vitrine"),
                    "param_name" => "url",
                    "value" => "",
                    "description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
                ),
			)
		)
);

/*-----------------------------------------------------------------------------------*/
/* Custom iconbox - creative iconbox 
/*-----------------------------------------------------------------------------------*/
vc_map( 
    array(
        "name" => "IconBox Creative",
        "base" => "iconbox_custom",
        "category" => 'By Epico',
        'weight' => 8,
        "icon" => "icon-wpb-iconbox-custom",
        "params" => array(
                array(
                    "type" => "textfield",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Title", "vitrine"),
                    "param_name" => "title",
                    "value" => "",
                    "description" => esc_html__("Enter title text", "vitrine") ,   
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Title Color", "vitrine"),
                    "param_name" => "title_color",
                    "value" => "",
                    "description" => esc_html__("Enter a Title color", "vitrine") , 
                ),
                array(
                    "type" => "vc_icons",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Icon", "vitrine"),
                    "param_name" => "icon",
                    "value" => "",
                    "description" => esc_html__("Select an icon to be located at the top of the box.", "vitrine") ,   
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Icon Color", "vitrine"),
                    "param_name" => "icon_color",
                    "value" => "",
                    "description" => esc_html__("Enter a icon color", "vitrine") , 
                ),
                array(
                    "type" => "textarea",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Content", "vitrine"),
                    "param_name" => "content_text",
                    "value" => "",
                    "description" => esc_html__("Enter some content for your IconBox", "vitrine") ,   
                ),
               array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Border Color", "vitrine"),
                    "param_name" => "border_color",
                    "value" => "",
                    "description" => esc_html__("Enter a border color", "vitrine") , 
                ),
               array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Background Color", "vitrine"),
                    "param_name" => "bg_color",
                    "value" => "",
                    "description" => esc_html__("Enter a background color", "vitrine") , 
                ),
               array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Background Hover Color", "vitrine"),
                    "param_name" => "bg_hover_color",
                    "value" => "",
                    "description" => esc_html__("Enter a background hover color", "vitrine") , 
                ),
				array(
					"type" => "attach_image",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Hover background image", "vitrine"),
					"param_name" => "image",
					"description" => esc_html__("Choose an image to be used as hover background (Optional)", "vitrine") , 
					"value" => "",
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Hover style", "vitrine"),
					"param_name" => "hover_style",
					"description" => esc_html__("Select an hover style", "vitrine") , 
	                "value" => array(
	                   "Style 1" => "style1",
		               "Style 2" => "style2"
					),
				),
                array(
                    "type" => "vc_link",
                    "class" => "",
                    "heading" => esc_html__("Link", "vitrine"),
                    "param_name" => "url",
                    "value" => "",
                    "description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
                ),
            )
        )
);

/*-----------------------------------------------------------------------------------*/
/* Social icon 
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "SocialIcon ",
		"base" => "socialIcon",
		"category" => 'By Epico',
        'weight' => 8,
		"icon" => "icon-wpb-social",

		"params" => array(
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Social Network Type", "vitrine"),
					"param_name" => "sociallink_type",
					"description" => esc_html__("Select social link type.", "vitrine") , 
					 "value" =>  $socialIcon,
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Social Network URL", "vitrine"),
					"param_name" => "sociallink_url",
					"value" => "",
					"description" => esc_html__("Copy and paste social network URL.", "vitrine") ,   
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Social Network Style", "vitrine"),
					"param_name" => "sociallink_style",
					"description" => esc_html__("Select social link style.", "vitrine") , 
                    "value" => array(
                       "Dark" => "dark",
		               "Light" => "light"
					),
					"dependency" => Array(
                    'element' => "sociallink_type", 
                    'value' => $socialIconDarkLight,
                   )
				),
				array(
					"type" => "attach_image",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Image URL", "vitrine"),
					"param_name" => "sociallink_image",
					"description" => esc_html__("Choose an image to be used as social icon's logo. ( best size : 20px * 25px ) ", "vitrine") , 
					"value" => "",
					"dependency" => Array(
                    'element' => "sociallink_type", 
                    'value' =>"custom"
                   )
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Social color", "vitrine"),
					"param_name" => "sociallink_color",
					"description" => esc_html__("Choose a color to be used as social icon's accent color.", "vitrine") , 
					"value" => "",
					"dependency" => Array(
                    'element' => "sociallink_type", 
                    'value' =>"custom"
                   )
				), 				
			)
		)
);

/*-----------------------------------------------------------------------------------*/
/* Social link 
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "SocialLink ",
		"base" => "socialLink",
		"category" => 'By Epico',
        'weight' => 8,
		"icon" => "icon-wpb-sociallink",
		"params" => array(
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Social Network Type", "vitrine"),
					"param_name" => "sociallink_type",
					"description" => esc_html__("Select social link type.", "vitrine") , 
					 "value" =>  $socialIcon,
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Socail Network URL", "vitrine"),
					"param_name" => "sociallink_url",
					"value" => "",
					"description" => esc_html__("Copy and paste social network URL.", "vitrine") ,   
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Social Network Style", "vitrine"),
					"param_name" => "sociallink_style",
					"description" => esc_html__("Select social link style.", "vitrine") , 
                    "value" => array(
                       "Dark" => "dark",
		               "Light" => "light"
					),
				),
               array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Social color", "vitrine"),
					"param_name" => "sociallink_color",
					"description" => esc_html__("Choose a color to be used as social icon's accent color.", "vitrine") , 
					"value" => "",
					"dependency" => Array(
                    'element' => "sociallink_type", 
                    'value' =>"custom"
                   )
				), 
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Social network name", "vitrine"),
					"param_name" => "sociallink_text",
					"description" => esc_html__("Enter a name for your social link", "vitrine") , 
					"value" => "",
					"dependency" => Array(
                        'element' => "sociallink_type", 
                        'value' =>"custom"
                    )
				),
			)
		)
);

/*-----------------------------------------------------------------------------------*/
/*  Count Down
/*-----------------------------------------------------------------------------------*/

vc_map( 
    array(
        "name" => "Count Down",
        "base" => "countdown",
        "category" => 'By Epico',
        'weight' => 8,
        "icon" => "icon-wpb-coundown",
        "params" => array(
            array(
                "type" => "vc_date",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("count to", "vitrine"),
                "param_name" => "end_date",
                "value" => "",          
                "description" => esc_html__("Enter the final date. Format : Month/Day/Year, Hour:Minute AM-PM. Notes that the entered date must be greater than current date!", "vitrine") ,   
            ),
             array(
                "type" => "colorpicker",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Color", "vitrine"),
                "param_name" => "color",
            	"description" => esc_html__("Choose the color of numbers", "vitrine") ,
				"value" => "",
            ),
             array(
                "type" => "colorpicker",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Label Color", "vitrine"),
                "param_name" => "label_color",
            	"description" => esc_html__("Choose the color of labels", "vitrine") ,
            	"value" => "",
            ),
            array(
                 "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("countdown Font Size", "vitrine"), 
                "param_name" => "fontsize",
                "description" => esc_html__("Select the font size of the countdown.", "vitrine") ,
                "value" => $contdownFontSize,
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Alignment", "vitrine"), 
                "param_name" => "alignment",
                "description" => esc_html__("Select alignment of the countdown.", "vitrine") ,
                "value" => array(
                    "Center" => "center",
                    "Left" => "left",
                    "Right" => "right"
                ),
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Animation", "vitrine"),
                "param_name" => "animation",
                "description" => esc_html__("Select entrance animation", "vitrine") , 
                 "value" => $animations,
                 "group" => esc_html__('Animation','vitrine')
            ),
            array(
				"type" => "textfield",
				"admin_label" => true,
				"class" => "",
				"heading" => esc_html__("Animation Delay", "vitrine"),
				"param_name" => "animation_delay",
				"value" => "",
                "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                "group" => esc_html__('Animation','vitrine')
			), 
			array(
			    "type" => "vc_multiselect",
			    "class" => "",
			    "heading" => esc_html__("Animation in Responsive", "vitrine"),
			    "param_name" => "responsive_animation",
			    "options" => array("disable" => "Disable animation"),
			    "value" => "disable",
			    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
			    "group" => esc_html__('Animation','vitrine')
			)
                
        )
    )
);


/*-----------------------------------------------------------------------------------*/
/* Counter Box
/*-----------------------------------------------------------------------------------*/

vc_map( 
    array(
        "name" => "Counter Box",
        "base" => "conterbox",
        "category" => 'By Epico',
        'weight' => 8,
        "icon" => "icon-wpb-counterbox",
        "params" => array(
                array(
                    "type" => "textfield",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Number", "vitrine"),
                    "param_name" => "counter_number",
                    "value" => "",
                    "description" => esc_html__("Enter the number that is going to be shown in counter", "vitrine") ,   
                ),
                 array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Counter's Number Color", "vitrine"),
                    "param_name" => "counter_number_color",
                    "value" => "",
                    "description" => esc_html__("Select optional counter's number color", "vitrine") ,  
                ),
                array(
                    "type" => "textfield",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Counter title", "vitrine"),
                    "param_name" => "counter_text",
                    "value" => "",
                    "description" => esc_html__("Enter counter title", "vitrine") ,   
                ),
                array(
                    "type" => "textfield",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Counter text", "vitrine"),
                    "param_name" => "counter_text2",
                    "value" => "",
                    "description" => esc_html__("Enter text title", "vitrine") ,   
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Title Color ", "vitrine"),
                    "param_name" => "counter_text_color",
                    "value" => "",
                    "description" => esc_html__("Select optional title color.", "vitrine") ,  
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Animation", "vitrine"),
                    "param_name" => "counter_animation",
                    "description" => esc_html__("Select counter's animation", "vitrine") , 
                     "value" => $animations,
                     "group" => esc_html__('Animation','vitrine')
                ),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation Delay", "vitrine"),
					"param_name" => "counter_animation_delay",
					"value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				), 
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				)
            )
        )
    );

/*-----------------------------------------------------------------------------------*/
/*  Piechart
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "Pie Chart",
		"base" => "piechart",
		"category" => 'By Epico',
        'weight' => 9,
		"icon" => "icon-wpb-piechart",

		"params" => array(
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Pie Chart Progress Percentage", "vitrine"),
					"param_name" => "piechart_percent",
					"value" => "",
					"description" => esc_html__("Enter the number that shows your progress in related skill here.", "vitrine") ,   
				),
               array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Pie Chart Percentage visibility", "vitrine"),
					"param_name" => "piechart_percent_display",
				    "value" => array(
                       "Enable" => "enable",
		               "Disable" => "disable"
	                ),
					"description" => esc_html__("You can enable Or disable progress bar percentage visibility.", "vitrine") ,   
				),
                array(
                    "type" => "vc_icons",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Icon", "vitrine"),
                    "param_name" => "piechart_icon",
                    "value" => "",
                    "description" => esc_html__("Select an icon to be located into the chart.", "vitrine") ,   
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Main Color", "vitrine"),
                    "param_name" => "main_color",
                    "value" => "#444",
                    "description" => esc_html__("Enter the main color of pie chart that includes its icon, percentage related data and dot color.", "vitrine") ,  
                ),
                array(
                    "type" => "vc_imageselect",
                    "admin_label" => true,
                    "class" => "presets",
                    "heading" => esc_html__("Pie Chart Line Color", "vitrine"),
                    "param_name" => "piechart_color_preset",
                    "description" => esc_html__("Select pie chart line color", "vitrine") , 
                        "value" => array(
                            "c0392b" => "c0392b",
                            "e74c3c" => "e74c3c",
                            "d35400" => "d35400",
                            "e67e22" => "e67e22",
                            "f39c12" => "f39c12",
                            "f1c40f" => "f1c40f",
                            "1abc9c" => "1abc9c",
                            "2ecc71" => "2ecc71",
                            "3498db" => "3498db",
                            "01558f" => "01558f",
                            "9b59b6" => "9b59b6",
                            "ecf0f1" => "ecf0f1",
                            "bdc3c7" => "bdc3c7",
                            "7f8c8d" => "7f8c8d",
                            "95a5a6" => "95a5a6",
                            "34495e" => "34495e",
                            "2e2e2e" => "2e2e2e",
                            "custom-color" => "custom"
                    ),
                ),
                array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Pie chart line custom Color", "vitrine"),
					"param_name" => "piechart_color",
					"value" => "",
                    "dependency" => array(
                        "element" => "piechart_color_preset",
                        'value' => 'custom',
                    ),
					"description" => esc_html__("Enter optional Pie Chart's line color", "vitrine") ,  
				),
               array(
                    "type" => "textfield",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Pie chart Title", "vitrine"),
                    "param_name" => "title",
                    "value" => "",
                    "description" => esc_html__("Enter pie chart title.", "vitrine") ,   
                ),
               array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Title Color", "vitrine"),
                    "param_name" => "title_color",
                    "value" => "",
                    "description" => esc_html__("Select optional title color. ", "vitrine") ,  
                ),
                array(
                    "type" => "textfield",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Pie Chart Subtitle", "vitrine"),
                    "param_name" => "subtitle",
                    "value" => "",
                    "description" => esc_html__("Enter pie chart subtitle.", "vitrine") ,   
                ),
               array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Subtitle Color", "vitrine"),
                    "param_name" => "subtitle_color",
                    "value" => "",
                    "description" => esc_html__("Select optional subtitle color.", "vitrine") ,  
                ),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation", "vitrine"),
					"param_name" => "piechart_animation",
					"description" => esc_html__("Select an animation for pie chart.", "vitrine") , 
					 "value" => $animations,
                     "group" => esc_html__('Animation','vitrine')
				),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Animation Delay", "vitrine"),
					"param_name" => "piechart_animation_delay",
					"value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				), 
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				)
			)
		)
);


/*-----------------------------------------------------------------------------------*/
/*  Team member
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "Team Member",
		"base" => "team_member",
		"category" => 'By Epico',
        'weight' => 9,
		"icon" => "icon-wpb-teammemmber",

		"params" => array(
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Name", "vitrine"),
					"param_name" => "name",
					"value" => "",
					"description" => esc_html__("Name of the team member", "vitrine") ,   
				),
               array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Job Title", "vitrine"),
					"param_name" => "job_title",
					"value" => "",
					"description" => esc_html__("Team member's job title", "vitrine") ,  
				),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Style", "vitrine"), 
					"param_name" => "style",
					"description" => esc_html__("Choose between dark and light styles", "vitrine") ,
					"value" => array(
						"Dark" => "dark",
						"Light" => "light"   
					),
			    ),
                array(
                    "type" => "vc_imageselect",
                    "admin_label" => true,
                    "class" => "presets",
                    "heading" => esc_html__("Color Presets", "vitrine"),
                    "param_name" => "team_color_preset",
                    "description" => esc_html__("Select team member's color", "vitrine") , 
                     "value" => array(
                            "c0392b" => "c0392b",
                            "e74c3c" => "e74c3c",
                            "d35400" => "d35400",
                            "e67e22" => "e67e22",
                            "f39c12" => "f39c12",
                            "f1c40f" => "f1c40f",
                            "1abc9c" => "1abc9c",
                            "2ecc71" => "2ecc71",
                            "3498db" => "3498db",
                            "01558f" => "01558f",
                            "9b59b6" => "9b59b6",
                            "ecf0f1" => "ecf0f1",
                            "bdc3c7" => "bdc3c7",
                            "7f8c8d" => "7f8c8d",
                            "95a5a6" => "95a5a6",
                            "34495e" => "34495e",
                            "2e2e2e" => "2e2e2e",
                            "custom-color" => "custom"
                         ),
                ),
               array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Custom team member Color", "vitrine"),
                    "param_name" => "team_color",
                    "value" => "",
                    "description" => esc_html__("Enter a team member color", "vitrine") , 
                    "dependency" => Array(
                        'element' => "team_color_preset", 
                        'value' => "custom"
                    ) 
                ),
               array(
					"type" => "attach_image",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Image", "vitrine"),
					"param_name" => "image",
					"value" => "",
					"description" => esc_html__("Optional URL of member's image", "vitrine")
				),
               array(
                    "type" => "attach_image",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Signature", "vitrine"),
                    "param_name" => "signature",
                    "value" => "",
                    "description" => esc_html__("Optional URL of the person's signature", "vitrine")
                ),
                array(
					"type" => "textarea",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Content", "vitrine"),
					"param_name" => "description",
					"value" => "",
					"description" => esc_html__("Small content text about the member", "vitrine") ,   
				),
                array(
                    "type" => "vc_link",
                    "holder" => "",
                    "class" => "",
                    "heading" => esc_html__("Link", "vitrine"),
                    "param_name" => "url",
                    "value" => "",
                    "description" => esc_html__("Optional URL to another web page", "vitrine") ,   
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Animation", "vitrine"),
                    "param_name" => "team_animation",
                    "description" => esc_html__("Select team member's animation", "vitrine") , 
                     "value" => $animations,
                     "group" => esc_html__('Animation','vitrine')
                ),
                array(
                    "type" => "textfield",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Animation Delay", "vitrine"),
                    "param_name" => "team_animation_delay",
                    "value" => "",
                    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
                ),
                array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				),
                array(
					"type" => "vc_icons",
					"class" => "",
					"heading" => esc_html__("Choose an icon for team member icon 1", "vitrine"),
					"param_name" => "team_icon1",
					"value" => "",
                    "group" => "social icons",
					"description" => esc_html__("Select an icon for team member icon 1(You can use it for social network icons)", "vitrine") ,
				),
               array(
					"type" => "textfield",
					"class" => "",
					"heading" => esc_html__("Icon 1 Link", "vitrine"),
					"param_name" => "team_icon_url1",
					"value" => "",
                    "group" => "social icons",
					"description" => esc_html__("Optional URL to another web page", "vitrine") ,   
				),
               array(
					"type" => "dropdown",
					"class" => "",
					"heading" => esc_html__("Icon 1 link's target", "vitrine"), 
					"param_name" => "team_icon_target1",
                    "group" => "social icons",
					"description" => esc_html__("Open the link in the same tab or a blank browser tab", "vitrine") ,
					"value" => array(
						"Open in same window" => "_self",
						"Open in new window" => "_blank"   
					),
			   ),
               array(
					"type" => "vc_icons",
					"class" => "",
					"heading" => esc_html__("Choose an icon for team member icon 2", "vitrine"),
					"param_name" => "team_icon2",
					"value" => "",
                    "group" => "social icons",
					"description" => esc_html__("Select an icon for team member icon 2(You can use it for social network icons)", "vitrine") ,
				),
               array(
					"type" => "textfield",
					"class" => "",
					"heading" => esc_html__("Icon 2 Link", "vitrine"),
					"param_name" => "team_icon_url2",
					"value" => "",
                    "group" => "social icons",
					"description" => esc_html__("Optional URL to another web page", "vitrine") ,   
				),
               array(
					"type" => "dropdown",
					"class" => "",
					"heading" => esc_html__("Icon 2 link's target", "vitrine"), 
					"param_name" => "team_icon_target2",
                    "group" => "social icons",
					"description" => esc_html__("Open the link in the same tab or a blank browser tab", "vitrine") ,
					"value" => array(
						"Open in same window" => "_self",
						"Open in new window" => "_blank"   
					),
				),
               array(
					"type" => "vc_icons",
					"class" => "",
					"heading" => esc_html__("Choose an icon for team member icon 3", "vitrine"),
					"param_name" => "team_icon3",
                    "group" => "social icons",
					"value" => "",
					"description" => esc_html__("Select an icon for team member icon 3(You can use it for social network icons)", "vitrine") ,
				),
               array(
					"type" => "textfield",
					"class" => "",
					"heading" => esc_html__("Icon 3 link", "vitrine"),
					"param_name" => "team_icon_url3",
                    "group" => "social icons",
					"value" => "",
					"description" => esc_html__("Optional URL to another web page", "vitrine") ,   
				),
               array(
					"type" => "dropdown",
					"class" => "",
					"heading" => esc_html__("Icon 3 link's target", "vitrine"), 
					"param_name" => "team_icon_target3",
                    "group" => "social icons",
					"description" => esc_html__("Open the link in the same tab or a blank browser tab", "vitrine") ,
					"value" => array(
						"Open in same window" => "_self",
						"Open in new window" => "_blank"   
					),
				),
              array(
					"type" => "vc_icons",
					"class" => "",
					"heading" => esc_html__("Choose an icon for team member icon 4" , "vitrine"),
					"param_name" => "team_icon4",
                    "group" => "social icons",
					"value" => "",
					"description" => esc_html__("Select an icon for team member icon 4(You can use it for social network icons)", "vitrine") ,
				),
               array(
					"type" => "textfield",
					"class" => "",
					"heading" => esc_html__("Icon 4 Link", "vitrine"),
					"param_name" => "team_icon_url4",
                    "group" => "social icons",
					"value" => "",
					"description" => esc_html__("Optional URL to another web page", "vitrine") ,   
				),
               array(
					"type" => "dropdown",
					"class" => "",
					"heading" => esc_html__("Icon 4 link's target", "vitrine"), 
					"param_name" => "team_icon_target4",
                    "group" => "social icons",
					"description" => esc_html__("Open the link in the same tab or a blank browser tab", "vitrine") ,
					"value" => array(
						"Open in same window" => "_self",
						"Open in new window" => "_blank"   
					),
				),
                array(
					"type" => "vc_icons",
					"class" => "",
					"heading" => esc_html__("Choose an icon for team member icon 5", "vitrine"),
					"param_name" => "team_icon5",
                    "group" => "social icons",
					"value" => "",
					"description" => esc_html__("Select an icon for team member icon 5(You can use it for social network icons)", "vitrine") ,
				),
               array(
					"type" => "textfield",
					"class" => "",
					"heading" => esc_html__("Icon 5 Link", "vitrine"),
					"param_name" => "team_icon_url5",
                    "group" => "social icons",
					"value" => "",
					"description" => esc_html__("Optional URL to another web page", "vitrine") ,   
				),
               array(
					"type" => "dropdown",
					"class" => "",
					"heading" => esc_html__("Icon 5 link's target", "vitrine"), 
					"param_name" => "team_icon_target5",
                    "group" => "social icons",
					"description" => esc_html__("Open the link in the same tab or a blank browser tab", "vitrine") ,
					"value" => array(
						"Open in same window" => "_self",
						"Open in new window" => "_blank"   
					),
				),  
			)
		)
);

/*-----------------------------------------------------------------------------------*/
/*  Button
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "Button",
		"base" => "button",
		"category" => 'By Epico',
        'weight' => 9,
		"icon" => "icon-wpb-button",
		"params" => array(
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("buttons Style", "vitrine"), 
                    "param_name" => "button_hover_style",
                    "value" => array(
                        "Text Animation" => "style1",
		                "Text & Background Animation" => "style2",
                        "Text & Border Animation" => "style3_border",
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Background Style", "vitrine"), 
                    "param_name" => "button_bg_style",
                    "description" => esc_html__("Choose one of available button background styles.", "vitrine") ,
                    "value" => array(
                        "Transparent" => "transparent",
                        "Fill" => "fill",
                    ),
                    "dependency" => Array(
                        'element' => "button_hover_style", 
                        'value' => array('style2')
                    )
                ),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Text", "vitrine"),
					"param_name" => "text",
					"value" => "",
					"description" => esc_html__("Button text", "vitrine") ,   
				),
                array(
                    "type" => "vc_link",
                    "holder" => "",
                    "class" => "",
                    "heading" => esc_html__("Link", "vitrine"),
                    "param_name" => "url",
                    "value" => "",
                    "description" => esc_html__("Optional URL to another web page", "vitrine") ,   
                ),
                array(
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Size", "vitrine"),
					"param_name" => "size",
					"description" => esc_html__("Choose one of three button sizes", "vitrine") ,
					"value" => array(
                        "Small" => "small",
						"Standard" => "standard",
                        "Large" => "large"   
					),
				),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Alignment", "vitrine"), 
                    "param_name" => "alignment",
                    "description" => esc_html__("Choose one of available button alignments.", "vitrine") ,
                    "value" => array(
                        "Left" => "left",
                        "Center" => "center",
                        "Right" => "right"
                    ),
                ),
				array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Position", "vitrine"), 
                    "param_name" => "position",
                    "description" => esc_html__("Choose one of available button styles", "vitrine") ,
                    "value" => array(
                        "In a row" => "row",
                        "In separate rows" => "separate",
                    ),
					"dependency" => Array(
                        'element' => "alignment", 
                        'value' => array('left','right')
                     )
                ),
                array(
					"type" => "colorpicker",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Text & Icon Color", "vitrine"),
					"param_name" => "button_text_color",
					"value" => "",
					"description" => esc_html__("Enter optional color for button's text and icon.", "vitrine") ,  
				),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Text & Icon On-hover Color", "vitrine"),
                    "param_name" => "button_text_hover_color",
                    "value" => "",
                    "description" => esc_html__("The color of button's text and icon on hover mode.", "vitrine") ,  
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Border Color", "vitrine"),
                    "param_name" => "button_border_color",
                    "value" => "",
                    "description" => esc_html__("Select an optional border color for your button.", "vitrine") ,  
                    "dependency" => Array(
                        'element' => "button_hover_style", 
                        'value' => array('style3_border')
                    )
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Border On-hover Color", "vitrine"),
                    "param_name" => "button_hover_color",
                    "value" => "",
                    "description" => esc_html__("Select an optional on-hover border color for your button.", "vitrine") ,  
                    "dependency" => Array(
                        'element' => "button_hover_style", 
                        'value' => array('style3_border')
                    )
                ),
                array (
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Border Color", "vitrine"),
                    "param_name" => "button_bg_color",
                    "value" => "",
                    "description" => esc_html__("Select border color for your button.", "vitrine") ,  
                    "dependency" => Array(
                        'element' => "button_hover_style", 
                        'value' => array('style1'),
                    )
                ),
                array (
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Border & Background Color", "vitrine"),
                    "param_name" => "button_bg_border_color",
                    "value" => "",
                    "description" => esc_html__("Select border and background color for your button.", "vitrine") ,  
                    "dependency" => Array(
                        'element' => "button_hover_style", 
                        'value' => array('style2'),
                    )
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Border Radius ", "vitrine"), 
                    "param_name" => "button_border_radius",
                    "value" => array(
                        "1px" => "1px",
                        "5px" => "5px",
                        "10px" => "10px",
                        "15px" => "15px",
                        "20px" => "20px",
                    ),
                    "dependency" => Array(
                        'element' => "button_hover_style", 
                        'value' => array('style1','style2')
                     )
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Border stroke ", "vitrine"), 
                    "param_name" => "button_border_width",
                    "value" => array(
                        "1px" => "1px",
						"2px" => "2px",
                        "3px" => "3px",
						"4px" => "4px",
                    ),
                    "dependency" => Array(
                        'element' => "button_hover_style", 
                        'value' => array('style1','style2')
                    )
                ),
                array(
                    "type" => "vc_icons",
                    "heading" => esc_html__("Select Icon", "vitrine"),
                    "param_name" => "button_icon",
                    'group'		=> esc_html__( "Icon",  "vitrine"),
                    "description" => esc_html__("Select an icon to be shown in buttons", "vitrine") ,   
                ),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Icon Position", "vitrine"), 
                    "param_name" => "button_icon_position",
                    "value" => array(
                        "Icon at left" => "left",
                        "Icon at right" => "right",
                    ),
                    "group" => esc_html__( "Icon",  "vitrine"),
                ),
                array(
					"type" => "textfield",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Tooltip text", "vitrine"),
					"param_name" => "title",
					"value" => "",
					"description" => esc_html__("Enter the text that you want to be shown on your button tooltip.", "vitrine") ,
                    "group" => esc_html__( "Tooltip",  "vitrine"), 
				),
			)
		)
);

/*-----------------------------------------------------------------------------------*/
/*  image Carousel
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "Image Carousel",
		"base" => "image_carousel",
        'weight' => 9,
		"category" => 'By Epico',
		"icon" => "icon-wpb-imagecarousel",

		"params" => array(
                array (
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Visible Items", "vitrine"), 
					"param_name" => "visible_items",
					"description" => esc_html__("Enter the maximum number of visible items that you want to be visible in the carousel.", "vitrine") ,
					"value" => array(
                        "1" => "1",
						"2" => "2",
                        "3" => "3",
						"4" => "4",
                        "5" => "5",
						"6" => "6",
                        "7" => "7",
                        "8" => "8",
					),
				),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => false,
                    "heading" => esc_html__("Gutter", "vitrine"),
                    "param_name" => "gutter",
                    "options" => array("no" => "No gutter"),
                    "description" => esc_html__("Remove gutter between items", "vitrine"),
                    "value" => "",
                ),
				array(
					"type" => "dropdown",
					"holder" => "",
					"class" => "",
					"heading" => esc_html__("Navigation Buttons Visibility", "vitrine"),
					"param_name" => "naxt_prev_btn",
					"description" => esc_html__("Enable or disable showing navigation buttons", "vitrine") ,
						"value" => array(
						   "Enable" => "show",
						   "Disable" => "hide"
						),
				),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Navigation Style", "vitrine"),
                    "param_name" => "nav_style",
                    "description" => esc_html__("Choose dark or light style", "vitrine") ,
                        "value" => array(
                            "Dark" => "dark",
                            "Light" => "light",
                        ),
                ),
                array (
                    "type" => "attach_images",
                    "class" => "",
                    "heading" => esc_html__("Images", "vitrine"),
                    "param_name" => "images",
                    "value" => "",
                    "description" => esc_html__("Select images from media library.", "vitrine")
                ),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Carousel Image Size', 'vitrine' ),
					'param_name' => 'image_size',
					"description" => esc_html__("Select image size", "vitrine"),
                    "value" => array( 
						"Full" => "full",
						"medium" => "medium",
						"thumbnail" => "thumbnail",
						"Custom" => "custom",
                    ),
				),
				array(
				    "type" => "textfield",
				    "class" => "custom_image_size",
				    "admin_label" => false,
				    "heading" => esc_html__("image size width", "vitrine"),
				    "param_name" => "image_size_width",
				    "value" => "",
				    "description" => esc_html__("Width", "vitrine") ,
	                "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                )
				),
				array(
				    "type" => "textfield",
				    "class" => "custom_image_size",
				    "admin_label" => false,
				    "heading" => esc_html__("image size height", "vitrine"),
				    "param_name" => "image_size_height",
				    "value" => "",
				    "description" => esc_html__("Height", "vitrine") ,
	                "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                )
				),
				array(
	                "type" => "vc_multiselect",
	                "class" => "custom_image_size",
	                "heading" => esc_html__("image size crop", "vitrine"),
	                "param_name" => "image_size_crop",
	                "options" => array("yes" => "Crop image"),
	                "value" => "",
	                 "dependency" => array(
	                   'element' => "image_size", 
	                    'value' => 'custom'
	                 
					)
	            ),
	           array(
	                "type" => "vc_imageselect",
	                "class" => "presets",
	                "admin_label" => false,
	                "heading" => esc_html__("Hover color", "vitrine"),
	                "param_name" => "hover_color",
	                "default" => "2e2e2e",
	                "description" => esc_html__("Select hover color.", "vitrine") , 
                    "value" => array(
                        "c0392b" => "c0392b",
                        "e74c3c" => "e74c3c",
                        "d35400" => "d35400",
                        "e67e22" => "e67e22",
                        "f39c12" => "f39c12",
                        "f1c40f" => "f1c40f",
                        "1abc9c" => "1abc9c",
                        "2ecc71" => "2ecc71",
                        "3498db" => "3498db",
                        "01558f" => "01558f",
                        "9b59b6" => "9b59b6",
                        "ecf0f1" => "ecf0f1",
                        "bdc3c7" => "bdc3c7",
                        "7f8c8d" => "7f8c8d",
                        "95a5a6" => "95a5a6",
                        "34495e" => "34495e",
                        "2e2e2e" => "2e2e2e",
                        "custom-color" => "custom"
                    ),
	            ),
	            array(
	                "type" => "colorpicker",
	                "admin_label" => false,
	                "class" => "",
	                "heading" => esc_html__("Custom hover Color", "vitrine"),
	                "param_name" => "custom_hover_color",
	                "value" => "",
	                "description" => esc_html__("Enter a custom hover color", "vitrine") , 
	                "dependency" => Array(
	                    'element' => "hover_color", 
	                    'value' => "custom"
	                ) 
	            ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => false,
                    "heading" => esc_html__("Hover Zoom effect", "vitrine"),
                    "param_name" => "zoom",
                    "options" => array("yes" => "Enable"),
                    "description" => esc_html__("Zoom effect on hover", "vitrine"),
                    "value" => "",
                ),
                array (
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__("Entrance animation", "vitrine"), 
                    "param_name" => "enterance_animation",
                    "description" => esc_html__("How you want your items to enter the page", "vitrine") ,
                    "value" => array(
                        esc_html__('FadeIn From Bottom', "vitrine") => 'fadeInFromBottom',
                        esc_html__('FadeIn From Top', "vitrine") => 'fadeInFromTop',
                        esc_html__('FadeIn From Right', "vitrine") => 'fadeInFromRight',
                        esc_html__('FadeIn From Left', "vitrine") => 'fadeInFromLeft',
                        esc_html__('Zoom-in', "vitrine")  => 'zoomIn',
                        esc_html__('No Animation', "vitrine")  => 'default',
                    ),
                    "group" => esc_html__('Animation','vitrine')       
                ),
                array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Autoplay", "vitrine"),
                    "param_name" => "is_autoplay",
                    "description" => esc_html__("Autoplay of carousel", "vitrine"),
                    "value" => array(
                        "On" => "on",
                        "Off" => "off",
                    ),
                    "group" => esc_html__('Animation','vitrine')  
                ),
        )
    )
);


/*-----------------------------------------------------------------------------------*/
/* Showcase
/*-----------------------------------------------------------------------------------*/

vc_map( 
    array(
        "name" => "Showcase",
        "base" => "showcase",
        "category" => 'By Epico',  
        'weight' => 9,   
        "icon" => "icon-wpb-showcase",
        "as_parent" => array('only' => 'showcase_item'),
        "js_view" => 'VcColumnView',
        "content_element" => true,
        "params" => array( 
            array(
                "type" => "textarea",
                "class" => "",
                "heading" => esc_html__("Title", "vitrine"),
                "param_name" => "title",
                "description" => "Type in a title for your showcase."
            ),
            array(
                "type" => "textfield",
                "holder" => "",
                "class" => "",
                "heading" => esc_html__("Subtitle", "vitrine"),
                "param_name" => "subtitle",
                "description" => "Type in a subtitle for your showcase."
            ),
            array(
                "type" => "dropdown",
                "holder" => "",
                "class" => "",
                "heading" => esc_html__("Next Section Button Visibility", "vitrine"),
                "param_name" => "nextsection",
                "description" => esc_html__("Enable or disable showing next section button", "vitrine") ,
                    "value" => array(
                       "Enable" => "show",
                       "Disable" => "hide"
                    ),
            ),
            array(
                "type" => "dropdown",
                "holder" => "",
                "class" => "",
                "heading" => esc_html__("Overlay", "vitrine"),
                "param_name" => "overlay",
                "description" => esc_html__("Enable or disable overlaying on the background.", "vitrine") ,
                    "value" => array(
                       "Enable" => "show",
                       "Disable" => "hide"
                    ),
            ),
            array(
                "type" => "dropdown",
                "holder" => "",
                "class" => "",
                "heading" => esc_html__("Style", "vitrine"),
                "param_name" => "style",
                "description" => esc_html__("Choose dark or light style", "vitrine") ,
                    "value" => array(
                        // We have to change this name :) 
                        "Light" => "dark",
                        "Dark" => "light",
                    ),
            ),
            array(
                "type" => "colorpicker",
                "class" => "",
                "heading" => esc_html__("Hover Color", "vitrine"),
                "param_name" => "hover_color",
                "value" => "",
                "description" => esc_html__("Enter optional color for On-Hover state", "vitrine") ,  
            ),
            array(
                "type" => "dropdown",
                "holder" => "",
                "class" => "",
                "heading" => esc_html__("Direction", "vitrine"),
                "param_name" => "direction",
                "description" => esc_html__("Select your showcase location (left or right).", "vitrine") ,
                    "value" => array(
                        "Left" => "left-align",
                        "Right" => "right-align",
                    ),
            ),
        )
    ) 
);

vc_map( 
    array(
        "name" => "Showcase item",
        "base" => "showcase_item",
        "category" => 'By Epico',
        'weight' => 9,
        "admin_enqueue_css" => array(get_template_directory_uri().'/lib/admin/css/vc-extend.css'),        
        "icon" => "icon-wpb-showcase-item",
        "content_element" => true,
        "as_child" => array('only' => 'showcase'),
        "params" => array(
            array(
                "type" => "textfield",
                "holder" => "",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Title", "vitrine"),
                "param_name" => "title",
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "holder" => "",
                "class" => "",
                "heading" => esc_html__("Subtitle", "vitrine"),
                "param_name" => "subtitle",
                "description" => ""
            ),
            array(
                "type" => "textarea",
                "holder" => "",
                "class" => "",
                "heading" => esc_html__("Content", "vitrine"),
                "param_name" => "text",
                "description" => ""
            ),
            array(
                "type" => "dropdown",
                "holder" => "",
                "class" => "",
                "heading" => esc_html__("Text Background", "vitrine"),
                "param_name" => "text_bg",
                "description" => esc_html__("Enable or disable showing background under the text", "vitrine") ,
                    "value" => array(
                        "Hide" => "hide",
                        "Show" => "show"
                    ),
            ),
            array(
                "type" => "attach_image",
                "class" => "",
                "heading" => esc_html__("Background Image", "vitrine"),
                "param_name" => "bg",
                "value" => "",
                "description" => esc_html__("Select an image as background", "vitrine")
            ),
            array(
                "type" => "attach_images",
                "class" => "",
                "heading" => esc_html__("Images", "vitrine"),
                "param_name" => "images",
                "value" => "",
                "description" => esc_html__("Select images from media library.", "vitrine")
            ),
            array(
                "type" => "vc_link",
                "admin_label" => false,
                "class" => "",
                "heading" => esc_html__("Link", "vitrine"),
                "param_name" => "outer_link",
                "value" => "",
                "description" => esc_html__("Optional URL to another web page.", "vitrine") ,   
            ),
        )
    ) 
);

/*-----------------------------------------------------------------------------------*/
/* Tabs, Tour, accordion
/*-----------------------------------------------------------------------------------*/

$tta_setting = array (
  "weight" => 9,
);
vc_map_update('vc_tta_tour', $tta_setting);
vc_map_update('vc_tta_tabs', $tta_setting);
vc_map_update('vc_tta_accordion', $tta_setting);

/*-----------------------------------------------------------------------------------*/
/*  Get MailPoet forms
/*-----------------------------------------------------------------------------------*/

if (class_exists('WYSIJA_NL_Widget')) { // Check if mailpoet be active then run below code 
    vc_map( 
        array(
            "name" => "Newsletter Subscription",
            "base" => "ep_newsletter",
            "category" => 'By Epico',
            "weight" => 9,
            "icon" => "icon-wpb-newsletter",
            "params" => array(
                array(
                    "type" => "dropdown",
				    "holder" => "",
                    "class" => "",
                    "heading" => esc_html__("MailPoet Form", "vitrine"),
                    "param_name" => "mail_poet_form",
                    "value" => array_merge(array(1 =>"Select a form ...") , epico_get_mail_poet_forms()),
                    "description" => esc_html__("Select a MailPoet form", "vitrine"),
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Button Style", "vitrine"),
                    "param_name" => "mail_poet_button_style",
                    "value" => array(
                        "Dark"  => "darkStyle",
                        "Light"  => "lightStyle"
                    ),
                    "description" => "dark and light styles set for button style",
                ),
            )
    ));
}


/*-----------------------------------------------------------------------------------*/
/* Gallery
/*-----------------------------------------------------------------------------------*/

vc_map( array(
        "name" => "Gallery",
		"base" => "ep_gallery",
		"icon" => "icon-wpb-gallery",
		"category" => 'By Epico',
        'weight' => 8,
		"params" => array(
             array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Grid Layout", "vitrine"),
                "param_name" => "portfolio_masonry",
                "value" => array(
					"Fixed Layout" => "perfectMasonry",
					"Masonary Layout" => "masonry"
                ),
				"description" => ""
            ),
			array(
                "type" => "vc_imageselect",
				"class" => "portfoliotype",
                "admin_label" => true,
                "heading" => esc_html__("Gallery Type", "vitrine"),
				"param_name" => "type",
                "value" => array(
                    "portfolio_space" => "portfolio_space",
                    "portfolio_no_space" => "portfolio_no_space",
                    "portfolio_text" => "portfolio_text",
				),
				"description" => "",
			),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Show title", "vitrine"), 
                "param_name" => "title_bar",
                "description" => esc_html__("Show or hide the title", "vitrine") ,
                "value" => array(
                    esc_html__('Do not show title', "vitrine")  => 'hide',
                     esc_html__('Show custom title', "vitrine") => 'show'
                ),              
            ),
            array(
				"type" => "textfield",
				"class" => "",
                "admin_label" => true,
				"heading" => esc_html__("Title", "vitrine"),
				"param_name" => "title_text",
                "value" => "",
				"description" => esc_html__("Enter a title to be shown at the beginning of gallery section", "vitrine") ,
                "dependency" => Array(
                    'element' => "title_bar", 
                    'value' => 'show'
                 )
			),
            array(
				"type" => "dropdown",
		        "admin_label" => true,
				"class" => "",
				"heading" => esc_html__("Filter Display", "vitrine"),
				"param_name" => "filter_display",
				"value" => array(
					"Show" => "show",
					"Hide" => "hide"
				),
				"description" => "",
			),
            array(
				"type" => "dropdown",
		        "admin_label" => true,
				"class" => "",
				"heading" => esc_html__("Filter Style", "vitrine"),
				"param_name" => "filter_style",
				"value" => array(
					"Standard" => "standard",
                    "Toggle" => "toggle"
				),
				"description" => "",
			),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Filter toggle beginning state", "vitrine"),
                "param_name" => "filter_toggle_state",
                "value" => array(
                    "Close"  => "close",
                    "Open"  => "open"
                ),
                "description" => "",
                "dependency" => Array(
                    'element' => "filter_style", 
                    'value' => 'toggle'
                 )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Gallery Categories", "vitrine"),
                "param_name" => "portfolio_filter",
                "value" => array(
                    "All"  => "all",
                    "Custom"  => "custom"
                ),
                "description" => "",
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Gallery custom Categories", "vitrine"),
                "param_name" => "filters",
                "options" => $gallery_cats,
                "description" => "Selected categories to be shown gallery section",
                "value" => "",
                 "dependency" => Array(
                    'element' => "portfolio_filter", 
                    'value' => 'custom'
                 )
            ),
            array(
                "type" => "vc_rangefield",
                "label" => "items",
                "admin_label" => true,
                "heading" => esc_html__("Gallery Post Per Section", "vitrine"),
                "param_name" => "gallery_posts_page",
                'min'   => '1',
                'max'   => '30',
                'step'   => '1',
                'value' => '12',
                "description" => "Choose the initial number of gallery items to be shown before clicking on load more button.",
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Style", "vitrine"),
                "param_name" => "filter_loadmore_style",
                "value" => array(
                    "Dark"  => "darkStyle",
                    "Light"  => "lightStyle"
                ),
                "description" => "dark and light styles set for filter bar , load more button , title and subtitle",
            ),
           array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Hover type", "vitrine"),
                "param_name" => "portfolio_hover",
                "value" => array(
					"Simple Gallery" => "simpleGallery",
                    "Border Style"  => "borderType",
                    "Simple"  => "simpleType"
                ),
                "description" => "",
                'group'=> esc_html__('On-hover','vitrine')
            ),
			array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Hover style", "vitrine"),
                "param_name" => "portfolio_hover_style",
                "value" => array(
                    "Light"  => "lightStyle",
                    "Dark"  => "darkStyle"
                ),
                "description" => "",
                'group'=> esc_html__('On-hover','vitrine')
            ),
             array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Display like Button On hover", "vitrine"),
                "param_name" => "portfolio_hover_like_button",
                "value" => array(
					"Show" => "show",
					"Hide" => "hide"
                ),
                "description" => "",
                'group'=> esc_html__('On-hover','vitrine')
            ),
			array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Pop-up style", "vitrine"),
                "param_name" => "gallery_pop_up",
                "value" => array(
				    "Dark"  => "darkPopUp",
                    "Light"  => "lightPopUp",
                    
                ),
                "description" => "",
            ),
			array(
                "type" => "dropdown",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Gallery Slide Animation", "vitrine"),
                "param_name" => "animation_style",
                "value" => array(
				    "Fade"  => "lg-fade",
				    "Slide"  => "lg-slide",
					"Zoom-In"  => "lg-zoom-in",
                    "Zoom-In Big"  => "lg-zoom-in-big",
					"Zoom-Out"  => "lg-zoom-out",
                    "Zoom-Out Big"  => "lightPopUp",
                    "Zoom-Out In"  => "lg-zoom-out-in",
                    "Zoom-In Out"  => "lg-zoom-in-out",
                    "Soft Zoom"  => "lg-soft-zoom",
                    "Scale-Up"  => "lg-scale-up",
                    "Circular Slide"  => "lg-slide-circular",
                    "Circular Vertical Slide"  => "lg-slide-circular-vertical",
                    "Vertical Slide"  => "lg-slide-vertical",
                    "Vertical Slide with growth"  => "lg-slide-vertical-growth",
                    "Skew"  => "lg-slide-skew-only",
                    "reverse Skew"  => "lg-slide-skew-only-rev",
                    "Skew"  => "lg-slide-skew-only",
                    "Horizontal Skew"  => "lg-slide-skew-only-y",
                    "Reverse Horizontal Skew"  => "lg-slide-skew-only-y-rev",
                    "Slide Skew"  => "lg-slide-skew",
                    "Slide reverse Skew"  => "lg-slide-skew-rev",
                    "Slide Skew Cross"  => "lg-slide-skew-cross",
                    "Reverse Slide Skew Cross"  => "lg-slide-skew-cross-rev",
                    "Vertical Slide Skew"  => "lg-slide-skew-ver",
                    "Reverse Vertical Slide Skew"  => "lg-slide-skew-ver-rev",
                    "Vertical Slide Skew Cross"  => "lg-slide-skew-ver-cross",
                    "Reverse Vertical Slide Skew Cross"  => "lg-slide-skew-ver-cross-rev",
                    "Lollipop"  => "lg-lollipop",
                    "Reverse Lollipop"  => "lg-lollipop-rev",
                    "Rotate"  => "lg-rotate",
                    "Asynchronous Rotate"  => "lg-rotate-rev",
                    "Tube"  => "lg-tube",
                ),
                "description" => "",
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Entrance animation", "vitrine"), 
                "param_name" => "enterance_animation",
                "description" => esc_html__("How you want your items to enter the page", "vitrine") ,
                "value" => array(
                    esc_html__('FadeIn From Bottom',"vitrine") => 'fadeInFromBottom',
                    esc_html__('FadeIn From Top', "vitrine") => 'fadeInFromTop',
                    esc_html__('FadeIn From Right', "vitrine") => 'fadeInFromRight',
                    esc_html__('FadeIn From Left', "vitrine")=> 'fadeInFromLeft',
                    esc_html__('Zoom-in', "vitrine") => 'zoomIn',
                    esc_html__('No Animation', "vitrine") => 'default',
                ),
                "group" => esc_html__('Animation','vitrine')              
            ),
            array(
			    "type" => "vc_multiselect",
			    "class" => "",
			    "heading" => esc_html__("Animation in Responsive", "vitrine"),
			    "param_name" => "responsive_animation",
			    "options" => array("disable" => "Disable animation"),
			    "value" => "disable",
			    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
			    "group" => esc_html__('Animation','vitrine')
			)
         )
    )
);


/*-----------------------------------------------------------------------------------*/
/* Carousel Galleryd
/*-----------------------------------------------------------------------------------*/

vc_map( 
	array(
		"name" => "gallery Carousel",
		"base" => "gallery_carousel",
        'weight' => 9,
		"category" => 'By Epico',
		"icon" => "icon-wpb-imagecarousel",
		"params" => array(
                array (
					"type" => "dropdown",
					"admin_label" => true,
					"class" => "",
					"heading" => esc_html__("Visible Items", "vitrine"), 
					"param_name" => "visible_items",
					"description" => esc_html__("Enter the maximum number of visible items that you want to be visible in the carousel.", "vitrine") ,
					"value" => array(
                        "1" => "1",
						"2" => "2",
                        "3" => "3",
						"4" => "4",
                        "5" => "5",
					),
				),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => false,
                    "heading" => esc_html__("Gutter", "vitrine"),
                    "param_name" => "gutter",
                    "options" => array("no" => "No gutter"),
                    "description" => esc_html__("Remove gutter between items", "vitrine"),
                    "value" => "",
                ),
				array(
					"type" => "dropdown",
					"holder" => "",
					"class" => "",
					"heading" => esc_html__("Navigation Buttons Visibility", "vitrine"),
					"param_name" => "naxt_prev_btn",
					"description" => esc_html__("Enable or disable showing navigation buttons", "vitrine") ,
						"value" => array(
						   "Enable" => "show",
						   "Disable" => "hide"
						),
				),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Navigation Style", "vitrine"),
                    "param_name" => "nav_style",
                    "description" => esc_html__("Choose dark or light style", "vitrine") ,
                        "value" => array(
                            "Dark" => "dark",
                            "Light" => "light",
                        ),
                ),
	           array(
	                "type" => "vc_imageselect",
	                "class" => "presets",
	                "admin_label" => false,
	                "heading" => esc_html__("Hover color", "vitrine"),
	                "param_name" => "hover_color",
	                "default" => "2e2e2e",
	                "description" => esc_html__("Select hover color.", "vitrine") , 
                    "value" => array(
                        "c0392b" => "c0392b",
                        "e74c3c" => "e74c3c",
                        "d35400" => "d35400",
                        "e67e22" => "e67e22",
                        "f39c12" => "f39c12",
                        "f1c40f" => "f1c40f",
                        "1abc9c" => "1abc9c",
                        "2ecc71" => "2ecc71",
                        "3498db" => "3498db",
                        "01558f" => "01558f",
                        "9b59b6" => "9b59b6",
                        "ecf0f1" => "ecf0f1",
                        "bdc3c7" => "bdc3c7",
                        "7f8c8d" => "7f8c8d",
                        "95a5a6" => "95a5a6",
                        "34495e" => "34495e",
                        "2e2e2e" => "2e2e2e",
                        "custom-color" => "custom"
                    ),
	            ),
	            array(
	                "type" => "colorpicker",
	                "admin_label" => false,
	                "class" => "",
	                "heading" => esc_html__("Custom hover Color", "vitrine"),
	                "param_name" => "custom_hover_color",
	                "value" => "",
	                "description" => esc_html__("Enter a custom hover color", "vitrine") , 
	                "dependency" => Array(
	                    'element' => "hover_color", 
	                    'value' => "custom"
	                ) 
	            ),
                array (
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__("Entrance animation", "vitrine"), 
                    "param_name" => "enterance_animation",
                    "description" => esc_html__("How you want your items to enter the page", "vitrine") ,
                    "value" => array(
                        esc_html__('FadeIn From Bottom', "vitrine") => 'fadeInFromBottom',
                        esc_html__('FadeIn From Top', "vitrine") => 'fadeInFromTop',
                        esc_html__('FadeIn From Right', "vitrine") => 'fadeInFromRight',
                        esc_html__('FadeIn From Left', "vitrine") => 'fadeInFromLeft',
                        esc_html__('Zoom-in', "vitrine")  => 'zoomIn',
                        esc_html__('No Animation', "vitrine")  => 'default',
                    ),
                    "group" => esc_html__('Animation','vitrine')       
                ),
                array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				),
                array(
                    "type" => "dropdown",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Autoplay", "vitrine"),
                    "param_name" => "is_autoplay",
                    "description" => esc_html__("Autoplay of carousel", "vitrine"),
                    "value" => array(
                        "On" => "on",
                        "Off" => "off",
                    ),
                    "group" => esc_html__('Animation','vitrine')  
                ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Gallery Categories", "vitrine"),
                "param_name" => "portfolio_filter",
                "value" => array(
                    "All"  => "all",
                    "Custom"  => "custom"
                ),
                "description" => "",
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Gallery custom Categories", "vitrine"),
                "param_name" => "filters",
                "options" => $gallery_cats,
                "description" => "Selected categories to be shown gallery section",
                "value" => "",
                 "dependency" => Array(
                    'element' => "portfolio_filter", 
                    'value' => 'custom'
                 )
            ),
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/* Instagram feed
/*-----------------------------------------------------------------------------------*/

vc_map( array(
        "name" => "Instagram feed",
        "base" => "ep_instagram",
        "icon" => "icon-wpb-instagram",
        'weight' => 8,
        "category" => 'By Epico',
        "params" => array(
            array(
                "type" => "textfield",
                "class" => "",
                "admin_label" => false,
                "heading" => esc_html__("Username", "vitrine"),
                "param_name" => "user",
                "value" => "",
                "description" => esc_html__("Display posts from a specific user", "vitrine"),
            ),


            array(
                "type" => "vc_rangefield",
                "label" => "items",
                "admin_label" => true,
                "heading" => esc_html__("Post count", "vitrine"),
                "param_name" => "posts_count",
                'min'   => '1',
                'max'   => '20',
                'step'   => '1',
                'value' => '10',
                "description" => "Choose the number of posts",
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Columns", "vitrine"),
                "param_name" => "column",
                "value" => array(
                    "1" => "1",
                    "2" => "2",
                    "3" => "3",
                    "4" => "4",
                    "5" => "5",
                    "6" => "6",
                ),
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Image Resolution", "vitrine"),
                "param_name" => "image_resolution",
                "value" => array(
                    "Thumbnail (150x150)" => "thumbnail",
                     "Medium" => "medium",
                    "Full size " => "large",
                ),
                "description" => "",
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => false,
                "heading" => esc_html__("Gutter", "vitrine"),
                "param_name" => "gutter",
                "options" => array("no" => "No gutter"),
                "description" => esc_html__("Remove gutter between items", "vitrine"),
                "value" => "",
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Carousel/Grid", "vitrine"),
                "param_name" => "carousel",
                "description" => esc_html__("Enable Carousel/Grid mode for Instagram feed", "vitrine"),
                "value" => array(
                    "Grid" => "disable",
                    "Carousel" => "enable",
                ),
            ),
			array(
				"type" => "dropdown",
				"holder" => "",
				"class" => "",
				"heading" => esc_html__("Navigation Buttons Visibility", "vitrine"),
				"param_name" => "naxt_prev_btn",
				"description" => esc_html__("Enable or disable showing navigation buttons", "vitrine") ,
				"value" => array(
				   "Enable" => "show",
				   "Disable" => "hide"
				),
                "dependency" => array(
                    'element' => "carousel", 
                    'value' => "enable"
                )
			),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Navigations Style", "vitrine"),
                "param_name" => "nav_style",
                "description" => esc_html__("Choose dark or light style of navigation", "vitrine") ,
                    "value" => array(
                        "Light" => "light",
                        "Dark" => "dark",
                    ),
                    "dependency" => array(
                    	'element' => "naxt_prev_btn", 
                    	'value' => "show"                   
                    )
            ),
            array(
                "type" => "vc_imageselect",
                "class" => "presets",
                "admin_label" => false,
                "heading" => esc_html__("Hover color", "vitrine"),
                "param_name" => "hover_color",
                "default" => "2e2e2e",
                "description" => esc_html__("Select hover color.", "vitrine") , 
                    "value" => array(
                        "c0392b" => "c0392b",
                        "e74c3c" => "e74c3c",
                        "d35400" => "d35400",
                        "e67e22" => "e67e22",
                        "f39c12" => "f39c12",
                        "f1c40f" => "f1c40f",
                        "1abc9c" => "1abc9c",
                        "2ecc71" => "2ecc71",
                        "3498db" => "3498db",
                        "01558f" => "01558f",
                        "9b59b6" => "9b59b6",
                        "ecf0f1" => "ecf0f1",
                        "bdc3c7" => "bdc3c7",
                        "7f8c8d" => "7f8c8d",
                        "95a5a6" => "95a5a6",
                        "34495e" => "34495e",
                        "2e2e2e" => "2e2e2e",
                        "custom-color" => "custom"
                        ),
            ),
            array(
                "type" => "colorpicker",
                "admin_label" => false,
                "class" => "",
                "heading" => esc_html__("Custom hover Color", "vitrine"),
                "param_name" => "custom_hover_color",
                "value" => "",
                "description" => esc_html__("Enter a custom hover color", "vitrine") , 
                "dependency" => Array(
                    'element' => "hover_color", 
                    'value' => "custom"
                ) 
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => false,
                "heading" => esc_html__("Like", "vitrine"),
                "param_name" => "like",
                "options" => array("enable" => "Show"),
                "description" => esc_html__('Show likes count', "vitrine"),
                "value" => "",
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => false,
                "heading" => esc_html__("Comment", "vitrine"),
                "param_name" => "comment",
                "options" => array("enable" => "Show"),
                "description" => esc_html__('Show comment count', "vitrine"),
                "value" => "",
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Entrance animation", "vitrine"), 
                "param_name" => "enterance_animation",
                "description" => esc_html__("How you want your items to enter the page", "vitrine") ,
                "value" => array(
                    esc_html__('FadeIn From Bottom',"vitrine") => 'fadeInFromBottom',
                    esc_html__('FadeIn From Top', "vitrine") => 'fadeInFromTop',
                    esc_html__('FadeIn From Right', "vitrine") => 'fadeInFromRight',
                    esc_html__('FadeIn From Left', "vitrine")=> 'fadeInFromLeft',
                    esc_html__('Zoom-in', "vitrine") => 'zoomIn',
                    esc_html__('No Animation', "vitrine") => 'default',
                ),   
                "group" => esc_html__('Animation','vitrine')           
            ),
            array(
			    "type" => "vc_multiselect",
			    "class" => "",
			    "heading" => esc_html__("Animation in Responsive", "vitrine"),
			    "param_name" => "responsive_animation",
			    "options" => array("disable" => "Disable animation"),
			    "value" => "disable",
			    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
			    "group" => esc_html__('Animation','vitrine')
			)
         )
    )
);

/*-----------------------------------------------------------------------------------*/
/* VC masonry grid
/*-----------------------------------------------------------------------------------*/
// remove filter
vc_remove_param( 'vc_masonry_grid', 'show_filter' );
vc_remove_param( 'vc_masonry_grid', 'filter_source' );
vc_remove_param( 'vc_masonry_grid', 'exclude_filter' );
vc_remove_param( 'vc_masonry_grid', 'filter_style' );
vc_remove_param( 'vc_masonry_grid', 'filter_align' );
vc_remove_param( 'vc_masonry_grid', 'filter_color' );
vc_remove_param( 'vc_masonry_grid', 'filter_size' );

// remove load more button style
vc_remove_param( 'vc_masonry_grid', 'button_color' );
vc_remove_param( 'vc_masonry_grid', 'button_size' );
vc_remove_param( 'vc_masonry_grid', 'button_style' );

// remove pagination options
vc_remove_param( 'vc_masonry_grid', 'arrows_design' );
vc_remove_param( 'vc_masonry_grid', 'arrows_color' );
vc_remove_param( 'vc_masonry_grid', 'arrows_position' );
vc_remove_param( 'vc_masonry_grid', 'paging_design' );
vc_remove_param( 'vc_masonry_grid', 'paging_color' );


/*-----------------------------------------------------------------------------------*/
/* Masonry blog- Card Blog
/*-----------------------------------------------------------------------------------*/

$posts_cats = array();
$cat_args = array(
    'orderby'       => 'term_id', 
    'order'         => 'ASC',
    'hide_empty'    => false,
);

$terms = get_terms('category', $cat_args);

foreach($terms as $taxonomy){
     $posts_cats[$taxonomy->slug] = $taxonomy->name;
}

vc_map(
    array(
        'base' => 'ep_masonry_blog',
        'name' => esc_html__( 'Blog', 'vitrine' ),
        "icon" => "icon-wpb-blog",
        "show_settings_on_create" => false,
        "category" => '',
        'weight' => 8,
        'params' => array(
            array(
                "type"             => "dropdown",
                "heading"          => esc_html__("Number of columns", 'vitrine'),
                "param_name"       => "blog_column",
                "value"            => array(
                    esc_html__("Three",'vitrine') => "3",
                    esc_html__("Four",'vitrine')   => "4",
                ),
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Blog Categories", "vitrine"),
                "param_name" => "blog_filter",
                "value" => array(
                    "All"  => "all",
                    "Custom"  => "custom"
                ),
                "description" => "",
            ),
            array(
                "type"=> 'vc_multiselect',
                "class" => "",
                'heading'=> esc_html__( 'Category', 'vitrine' ),
                'param_name'=> 'blog_category',
                "options"=> $posts_cats,
                'defaults'=> 'all',
                "admin_label" => true,
                "description" => "Selected categories to be shown in blog section.",
                "value" => "",
                "dependency" => Array(
                    'element' => "blog_filter", 
                    'value' => 'custom'
                 )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Blog Style", "vitrine"),
                "param_name" => "blog_style",
                "value" => array(
                    "Inline interaction"  => "inline_interaction",
                    "Pop-up interaction"  => "popup_interaction"
                ),
                "description" => "",
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Layout Mode", "vitrine"),
                "param_name" => "blog_layout_mode",
                "description" => esc_html__("Choose masonry or fitrow for blog layout", "vitrine") ,
                    "value" => array(
                        "Masonry" => "masonry",
                        "Fit Rows" => "fitRows",
                    ),
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Image size", "vitrine"),
                "param_name" => "blog_image_size",
                "description" => esc_html__("Select image size for blog", "vitrine"),
                    "value" => array(
                        "Large" => "large",
                        "Scaled" => "scaled",
                    ),
            ),
            array(
                "type"             => 'textfield',
                "heading"          => esc_html__("Number of posts", 'vitrine'),
                "param_name"       => "blog_post_number",
                "value"            => "16",
                'defaultSetting'   => array(
                    "min"    => "0",
                    "max"    => "30",
                    "prefix" => "",
                    "step"   => '1',
                ),
               "description" => "Number of posts per page, minimum valid number is 0 and maximum valid number of posts is 30.",
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Blog Author & Comments", "vitrine"),
                'group'=>esc_html__('Display','vitrine'),
                "param_name" => "blog_category_author",
                "value" => array(
                    "Visible"  => "yes",
                    "Invisible"  => "no"
                ),
                "description" => "",
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Category Visibility", "vitrine"),
                'group'=>esc_html__('Display','vitrine'),
                "param_name" => "blog_category_visibility",
                "value" => array(
                    "Visible"  => "yes",
                    "Invisible"  => "no"
                ),
                "description" => "",
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Entrance Animation", "vitrine"), 
                "param_name" => "enterance_animation",
                "description" => esc_html__("How you want your items to enter the page", "vitrine") ,
                "value" => array(
                    esc_html__('FadeIn From Bottom', "vitrine") => 'fadeInFromBottom',
                    esc_html__('FadeIn From Top', "vitrine") => 'fadeInFromTop',
                    esc_html__('FadeIn From Right', "vitrine")=> 'fadeInFromRight',
                    esc_html__('FadeIn From Left', "vitrine") => 'fadeInFromLeft',
                    esc_html__('Zoom-in', "vitrine")  => 'zoomIn',
                    esc_html__('No Animation', "vitrine")  => 'default',
                ),
                "group" => esc_html__('Animation','vitrine')          
            ),
            array(
			    "type" => "vc_multiselect",
			    "class" => "",
			    "heading" => esc_html__("Animation in Responsive", "vitrine"),
			    "param_name" => "responsive_animation",
			    "options" => array("disable" => "Disable animation"),
			    "value" => "disable",
			    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
			    "group" => esc_html__('Animation','vitrine')
			),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Text Color", 'vitrine'),
                "param_name" => "blog_foreground_color",
                "description" => esc_html__("Choose dark or light style of text", "vitrine") ,
                    "value" => array(
                        "Dark" => "dark",
                        "Light" => "light",
                    ),
                'group'=>esc_html__('Design','vitrine')
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Load more style", 'vitrine'),
                "param_name" => "load_more_style",
                "description" => esc_html__("Choose dark or light style for load more in blog.", "vitrine") ,
                    "value" => array(
                        "Dark" => "dark",
                        "Light" => "lightStyle",
                    ),
                'group'=>esc_html__('Design','vitrine')
            ),
             array(
                "type" => "colorpicker",
                "heading" => esc_html__("Background Color", 'vitrine'),
                "param_name" => "blog_background_color",
                "value" => '#f8f8f8',
                "admin_label" => false,
                "opacity" => false,
                'group'=>esc_html__('Design','vitrine')
            ),
            array(
                "type" => "colorpicker",
                "heading" => esc_html__("Quote Post Background Color", 'vitrine'),
                "param_name" => "quote_blog_background_color",
                "value" => '#073B87',
                "admin_label" => false,
                "opacity" => false,
                'group'=>esc_html__('Design','vitrine')
            ),
            array(
                "type" => "colorpicker",
                "heading" => esc_html__("Quote Post text Color", 'vitrine'),
                "param_name" => "quote_blog_text_color",
                "value" => '#fff',
                "admin_label" => false,
                "opacity" => false,
                'group'=>esc_html__('Design','vitrine')
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Video & sound icon style", 'vitrine'),
                "param_name" => "blog_multimedia_icon_style",
                "description" => esc_html__("Choose dark or light style for sound and video blog types.", "vitrine") ,
                    "value" => array(
                        "Light" => "light",
                        "Dark" => "dark",
                    ),
                'group'=>esc_html__('Design','vitrine')
            ),
        )
    )
);


/*-----------------------------------------------------------------------------------*/
/* Woocommerce elements
/*-----------------------------------------------------------------------------------*/

if(!function_exists('epico_vc_change_woocommerce_elements')) {

    function epico_vc_change_woocommerce_elements() {

    	$animations = array(
			"None" => "none",
			"Fade in" => "fade-in",
			"Fade in from top" => "fade-in-top",
			"Fade in from left" => "fade-in-left",
			"Fade in from right" => "fade-in-right",
			"Fade in from bottom" => "fade-in-bottom",
		    "Grow In" => "grow-in"
		);

        //*---------------------------*/
        /* Single Product shortcode
        //*---------------------------*/
        vc_map(array(
            'name' => esc_html__( 'Single Product', 'vitrine' ),
            'base' => 'product',
            'icon' => 'icon-wpb-single-product',
            'weight' => 0,
            'category' => esc_html__( 'WooCommerce', 'vitrine' ),
            'description' => esc_html__( 'Show a single product by ID or SKU', 'vitrine' ),
            'params' => array(
                array(
                    'type' => 'autocomplete',
                    'heading' => esc_html__( 'Select Identifier', 'vitrine' ),
                    'param_name' => 'id',
                    'description' => esc_html__( 'Input product ID or product SKU or product title to see suggestions', 'vitrine' ),
                ),
                array(
                    'type' => 'hidden',
                    // This will not show on render, but will be used when defining value for autocomplete
                    'param_name' => 'sku',
                ),
	            array(
	                "type" => "dropdown",
	                "admin_label" => true,
	                "class" => "",
	                "heading" => esc_html__("Style", "vitrine"),
	                "param_name" => "style",
	                "description" => esc_html__("Choose style of items", "vitrine") ,
	                    "value" => array(
	                        "Buttons on hover" => "style1",
	                        "Info on hover" => "style2",
	                        "Info on click" => "style3",
	                        "Instant shop" => "style4",
	                    ),
	            ),
	            array(
	                "type" => "vc_multiselect",
	                "class" => "",
	                "admin_label" => true,
	                "heading" => esc_html__("hover description", "vitrine"),
	                "param_name" => "hover_description",
	                "options" => array("disable" => "Disable hover description"),
	                "description" => esc_html__("Disable hover description / In 5 column is disabled by default ", "vitrine"),
	                "dependency" => array(
	                    'element' => "style", 
	                    'value' => array("style3")
	                )
	            ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__("Image Size", "vitrine"),
                    "param_name" => "image_size",
                    "description" => esc_html__("Select image size", "vitrine"),
                    "value" => array(
                        "Single Product Image" => "shop_single",
                        "Catalog Images" => "shop_catalog",
                        "Product Thumbnails" => "shop_thumbnail",
                        "Full" => "full",
                        "Custom" => "custom"
                    ),
                ),
				array(
				    "type" => "textfield",
				    "class" => "custom_image_size",
				    "admin_label" => false,
				    "heading" => esc_html__("Custom image size", "vitrine"),
				    "param_name" => "image_size_width",
				    "value" => "",
				    "description" => esc_html__("Width", "vitrine") ,
	                "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                )
				),
				array(
				    "type" => "textfield",
				    "class" => "custom_image_size",
				    "admin_label" => false,
				    "heading" => esc_html__("image size height", "vitrine"),
				    "param_name" => "image_size_height",
				    "value" => "",
				    "description" => esc_html__("Height", "vitrine") ,
	                "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                )
				),
	            array(
	                "type" => "vc_multiselect",
	                "class" => "custom_image_size",
	                "heading" => esc_html__("image size crop", "vitrine"),
	                "param_name" => "image_size_crop",
	                "options" => array("yes" => "Crop image"),
	                "value" => "",
	                 "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                 )
	            ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__("Hover Image", "vitrine"),
                    "param_name" => "hover_image",
                    "description" => esc_html__("Show or hide hover image if exist", "vitrine"),
                    "value" => array(
                        "Show" => "show",
                        "Hide" => "hide",
                    ),
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Border", "vitrine"),
                    "param_name" => "border",
                    "options" => array("disable" => "Disable border"),
                    "description" => esc_html__("Disable border around the product box", "vitrine"),
                    "value" => "",
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Quick View Button", "vitrine"),
                    "param_name" => "quickview",
                    "options" => array("disable" => "Disable quickview"),
                    "description" => "Disable quick view button",
                    "value" => "",
					"dependency" => array(
	                    'element' => "style", 
	                    'value' => array("style1","style1-center","style2","style4")
	                )
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Wishlist Button", "vitrine"),
                    "param_name" => "wishlist",
                    "options" => array("disable" => "Disable wishlist"),
                    "description" => "Disable wishlist button",
                    "value" => "",
					"dependency" => array(
	                    'element' => "style", 
	                    'value' => array("style1","style1-center","style2","style4")
	                )
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Compare Button", "vitrine"),
                    "param_name" => "compare",
                    "options" => array("disable" => "Disable compare"),
                    "description" => "Disable compare button",
					"dependency" => array(
	                    'element' => "style", 
	                    'value' => array("style1","style1-center","style2","style4")
	                )
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Badges", "vitrine"),
                    "param_name" => "badges",
                    "options" => array("disable" => "Disable badges"),
                    "description" => "Disable badges ( sales , out of stock ...)",
                    "value" => ""
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("hover price", "vitrine"),
                    "param_name" => "hover_price",
                    "options" => array("disable" => "Disable hover price"),
                    "description" => esc_html__("Disable hover price / In 5 column is disabled by default ", "vitrine"),
                    "value" => "",
					"dependency" => array(
	                    'element' => "style", 
	                    'value' => array("style2")
	                )
                ),
                array(
                    "type" => "vc_imageselect",
                    "class" => "presets",
                    "heading" => esc_html__("Hover color", "vitrine"),
                    "param_name" => "hover_color",
                    "description" => esc_html__("Select hover color.", "vitrine") , 
                        "value" => array(
                            "c0392b" => "c0392b",
                            "e74c3c" => "e74c3c",
                            "d35400" => "d35400",
                            "e67e22" => "e67e22",
                            "f39c12" => "f39c12",
                            "f1c40f" => "f1c40f",
                            "1abc9c" => "1abc9c",
                            "2ecc71" => "2ecc71",
                            "3498db" => "3498db",
                            "01558f" => "01558f",
                            "9b59b6" => "9b59b6",
                            "ecf0f1" => "ecf0f1",
                            "bdc3c7" => "bdc3c7",
                            "7f8c8d" => "7f8c8d",
                            "95a5a6" => "95a5a6",
                            "34495e" => "34495e",
                            "2e2e2e" => "2e2e2e",
                            "custom-color" => "custom"
                            ),
					"dependency" => array(
	                    'element' => "style", 
	                    'value' => array("style2")
	                )
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Custom hover Color", "vitrine"),
                    "param_name" => "custom_hover_color",
                    "value" => "",
                    "description" => esc_html__("Enter a custom hover color", "vitrine") , 
                    "dependency" => Array(
                        'element' => "hover_color", 
                        'value' => "custom"
                    ) 
                ),
				array(
				    "type" => "dropdown",
				    "admin_label" => false,
				    "class" => "",
				    "admin_label" => false,
				    "heading" => esc_html__("Animation", "vitrine"),
				    "param_name" => "animation",
				    "description" => esc_html__("Select animation type", "vitrine") ,
				    "value" => $animations,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "textfield",
				    "class" => "",
				    "admin_label" => false,
				    "heading" => esc_html__("Animation Delay", "vitrine"),
				    "param_name" => "delay",
				    "value" => "",
				    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				),
            )
        ));

        //*---------------------------*/
        /* Single Product 2 shortcode
        //*---------------------------*/
        vc_map(array(
            'name' => esc_html__( 'Single product - modern', 'vitrine' ),
            'base' => 'product_2',
            'weight' => 0,
            'icon' => 'icon-wpb-single-product2',
            'category' => esc_html__( 'WooCommerce', 'vitrine' ),
            'description' => esc_html__( 'Show a single product by ID or SKU', 'vitrine' ),
            'params' => array(
                array(
                    'type' => 'autocomplete',
                    'heading' => esc_html__( 'Select Identifier', 'vitrine' ),
                    'param_name' => 'id',
                    'description' => esc_html__( 'Input product ID or product SKU or product title to see suggestions', 'vitrine' ),
                ),
                array(
                    'type' => 'hidden',
                    // This will not show on render, but will be used when defining value for auto-complete
                    'param_name' => 'sku',
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Image", "vitrine"),
                    "param_name" => "image",
                    "options" => array("disable" => "Disable image"),
                    "description" => esc_html__("Disable image/slider","vitrine"),
                    "value" => "",
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__("Image size", "vitrine"),
                    "param_name" => "image_size",
                    "description" => esc_html__("Select image size", "vitrine"),
                    "value" => array(
                        "Single Product Image" => "shop_single",
                        "Catalog Images" => "shop_catalog",
                        "Product Thumbnails" => "shop_thumbnail",
                        "Full" => "full",
                        "Custom" => "custom"
                    ),
                ),
				array(
				    "type" => "textfield",
				    "class" => "custom_image_size",
				    "admin_label" => false,
				    "heading" => esc_html__("Custom image size", "vitrine"),
				    "param_name" => "image_size_width",
				    "value" => "",
				    "description" => esc_html__("Width", "vitrine") ,
	                "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                )
				),
				array(
				    "type" => "textfield",
				    "class" => "custom_image_size",
				    "admin_label" => false,
				    "heading" => esc_html__("image size height", "vitrine"),
				    "param_name" => "image_size_height",
				    "value" => "",
				    "description" => esc_html__("Height", "vitrine") ,
	                "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                )
				),
	            array(
	                "type" => "vc_multiselect",
	                "class" => "custom_image_size",
	                "heading" => esc_html__("image size crop", "vitrine"),
	                "param_name" => "image_size_crop",
	                "options" => array("yes" => "Crop image"),
	                "value" => "",
	                 "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                 )
	            ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Background Color", "vitrine"),
                    "param_name" => "bg_color",
                    "value" => "rgba(242,242,242,0.01)",
                    "description" => esc_html__("Enter a background color", "vitrine")
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Text Color", "vitrine"),
                    "param_name" => "font_color",
                    "value" => "",
                    "description" => esc_html__("Enter a text color", "vitrine")
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Border", "vitrine"),
                    "param_name" => "border",
                    "options" => array("disable" => "Disable border"),
                    "description" => esc_html__("Disable border around the product box", "vitrine"),
                    "value" => "",
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Rating", "vitrine"),
                    "param_name" => "rating",
                    "options" => array("disable" => "Disable rating"),
                    "description" => esc_html__("Disable rating", "vitrine"),
                    "value" => "",
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Quick View button", "vitrine"),
                    "param_name" => "quickview",
                    "options" => array("disable" => "Disable quickview"),
                    "description" => esc_html__("Disable quick view button","vitrine"),
                    "value" => "",
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("wishlist Button", "vitrine"),
                    "param_name" => "wishlist",
                    "options" => array("disable" => "Disable wishlist"),
                    "description" => "Disable wishlist button",
                    "value" => "",
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Compare Button", "vitrine"),
                    "param_name" => "compare",
                    "options" => array("disable" => "Disable compare"),
                    "description" => "Disable compare button",
                    "value" => "",
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Badges", "vitrine"),
                    "param_name" => "badges",
                    "options" => array("disable" => "Disable badges"),
                    "description" => esc_html__("Disable badges","vitrine"),
                    "value" => "",
                ),
				array(
				    "type" => "dropdown",
				    "admin_label" => false,
				    "class" => "",
				    "heading" => esc_html__("Animation", "vitrine"),
				    "param_name" => "animation",
				    "description" => esc_html__("Select animation type", "vitrine") ,
				    "value" => $animations,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "textfield",
				    "class" => "",
				    "admin_label" => false,
				    "heading" => esc_html__("Animation Delay", "vitrine"),
				    "param_name" => "delay",
				    "value" => "",
				    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "vc_multiselect",
				    "class" => "",
				    "heading" => esc_html__("Animation in Responsive", "vitrine"),
				    "param_name" => "responsive_animation",
				    "options" => array("disable" => "Disable animation"),
				    "value" => "disable",
				    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
				    "group" => esc_html__('Animation','vitrine')
				),
            )
        ));
        
        // Add autocomplete functionality to search in porducts shortcode
        if(class_exists('Vc_Vendor_Woocommerce')) {
            $vc_vendor_wc = new Vc_Vendor_Woocommerce();
            //Filters For autocomplete param:
            //For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
            add_filter( 'vc_autocomplete_product_2_id_callback', array(
                &$vc_vendor_wc,
                'productIdAutocompleteSuggester',
            ), 10, 1 ); // Get suggestion(find). Must return an array
            add_filter( 'vc_autocomplete_product_2_id_render', array(
                &$vc_vendor_wc,
                'productIdAutocompleteRender',
            ), 10, 1 ); // Render exact product. Must return an array (label,value)
            //For param: ID default value filter
            add_filter( 'vc_form_fields_render_field_product_2_id_param_value', array(
                &$vc_vendor_wc,
                'productIdDefaultValue',
            ), 10, 4 ); // Defines default value for param if not provided. Takes from other param value.
		 	
			add_filter( 'vc_autocomplete_featured_products_category_callback', array(
				&$vc_vendor_wc,
				'productCategoryCategoryAutocompleteSuggesterBySlug',
			), 10, 1 ); // Get suggestion(find). Must return an array
			add_filter( 'vc_autocomplete_featured_products_category_render', array(
				&$vc_vendor_wc,
				'productCategoryCategoryRenderBySlugExact',
			), 10, 1 );
			add_filter( 'vc_autocomplete_recent_products_category_callback', array(
				&$vc_vendor_wc,
				'productCategoryCategoryAutocompleteSuggesterBySlug',
			), 10, 1 ); // Get suggestion(find). Must return an array
			add_filter( 'vc_autocomplete_recent_products_category_render', array(
				&$vc_vendor_wc,
				'productCategoryCategoryRenderBySlugExact',
			), 10, 1 );
 			add_filter( 'vc_autocomplete_best_selling_products_category_callback', array(
				&$vc_vendor_wc,
				'productCategoryCategoryAutocompleteSuggesterBySlug',
			), 10, 1 ); // Get suggestion(find). Must return an array
			add_filter( 'vc_autocomplete_best_selling_products_category_render', array(
				&$vc_vendor_wc,
				'productCategoryCategoryRenderBySlugExact',
			), 10, 1 );

  			add_filter( 'vc_autocomplete_top_rated_products_category_callback', array(
				&$vc_vendor_wc,
				'productCategoryCategoryAutocompleteSuggesterBySlug',
			), 10, 1 ); // Get suggestion(find). Must return an array
			add_filter( 'vc_autocomplete_top_rated_products_category_render', array(
				&$vc_vendor_wc,
				'productCategoryCategoryRenderBySlugExact',
			), 10, 1 ); 
			add_filter( 'vc_autocomplete_featured_products_tags_callback','productTagsAutocompleteSuggesterBySlug', 10, 1 ); // Get tags suggestion(find) in featured products
			add_filter( 'vc_autocomplete_featured_products_tags_render','productTagsRenderBySlugExact',10, 1 );
 			add_filter( 'vc_autocomplete_best_selling_products_tags_callback','productTagsAutocompleteSuggesterBySlug',10, 1 ); // Get tags suggestion(find) in best selling products
			add_filter( 'vc_autocomplete_best_selling_products_tags_render','productTagsRenderBySlugExact', 10, 1 );
  			add_filter( 'vc_autocomplete_top_rated_products_tags_callback','productTagsAutocompleteSuggesterBySlug',10, 1 ); // Get tags suggestion(find) in top rated products
			add_filter( 'vc_autocomplete_top_rated_products_tags_render','productTagsRenderBySlugExact', 10, 1 ); 
			add_filter( 'vc_autocomplete_recent_products_tags_callback', 'productTagsAutocompleteSuggesterBySlug',10, 1 ); // Get tags suggestion(find) in recent products
			add_filter( 'vc_autocomplete_recent_products_tags_render','productTagsRenderBySlugExact', 10, 1 );
			
		
        }


        //*---------------------------*/
        /* woocommerce shortcodes with carousel capability
        //*---------------------------*/

        $wc_shortcode_category_setting = array(
			array(
                "type" => "dropdown",
                "class" => "",
				"category" => "",
                "heading" => esc_html__("product Categories", "vitrine"),
                "param_name" => "product_cats",
                "value" => array(
                    "All"  => "all",
                    "Custom"  => "custom"
				),
                "description" => esc_html__("Show Products from specific categories", "vitrine" ),
            ),
			array(
				"type" => "autocomplete",
				"heading" => esc_html__( 'Categories', 'vitrine' ),
				"param_name" => "category",
				"settings" => array(
					"multiple" => true,
					"sortable" => true,
					),
				"dependency" => array(
                    'element' => "product_cats", 
                    'value' => 'custom',
					),
				"save_always" => true,
				"description" => esc_html__( "List of product categories", "vitrine" ),
			),
			array(
				"type" => "autocomplete",
				"heading" => esc_html__( 'Product Tags', 'vitrine' ),
				"param_name" => "tags",
				"settings" => array(
					"multiple" => true,
					"sortable" => true,
				),
				"save_always" => true,
				"description" => esc_html__( "List of product tages", "vitrine" ),
			),
		);

		$wc_shortcode_setting = array(
            array(
                "type" => "dropdown",
                "holder" => "",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Columns", "vitrine"), 
                "param_name" => "columns",
                "description" => esc_html__("Select number of items", "vitrine") ,
                "value" => array(
                   "1" => "1",
                   "2" => "2",
                   "3" => "3",
                   "4" => "4",
                   "5" => "5",
                ),
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Image Size", "vitrine"),
                "param_name" => "image_size",
                "description" => esc_html__("Select image size", "vitrine"),
                "value" => array(
                    "Single Product Image" => "shop_single",
                    "Catalog Images" => "shop_catalog",
                    "Product Thumbnails" => "shop_thumbnail",
                    "Full" => "full",
                    "Custom" => "custom"
                ),
            ),
			array(
			    "type" => "textfield",
			    "class" => "custom_image_size",
			    "admin_label" => false,
			    "heading" => esc_html__("Custom image size", "vitrine"),
			    "param_name" => "image_size_width",
			    "value" => "",
			    "description" => esc_html__("Width", "vitrine") ,
                "dependency" => array(
                    'element' => "image_size", 
                    'value' => 'custom'
                )
			),
			array(
			    "type" => "textfield",
			    "class" => "custom_image_size",
			    "admin_label" => false,
			    "heading" => esc_html__("image size height", "vitrine"),
			    "param_name" => "image_size_height",
			    "value" => "",
			    "description" => esc_html__("Height", "vitrine") ,
                "dependency" => array(
                    'element' => "image_size", 
                    'value' => 'custom'
                )
			),
            array(
                "type" => "vc_multiselect",
                "class" => "custom_image_size",
                "heading" => esc_html__("image size crop", "vitrine"),
                "param_name" => "image_size_crop",
                "options" => array("yes" => "Crop image"),
                "value" => "",
                 "dependency" => array(
                    'element' => "image_size", 
                    'value' => 'custom'
                 )
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Hover Image", "vitrine"),
                "param_name" => "hover_image",
                "description" => esc_html__("Show or hide hover image if exist", "vitrine"),
                "value" => array(
                    "Show" => "show",
                    "Hide" => "hide",
                ),
            ),
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Entrance Animation", "vitrine"), 
                "param_name" => "enterance_animation",
                "description" => esc_html__("How you want your items to enter the page", "vitrine") ,
                "value" => array(
                    esc_html__('FadeIn', "vitrine") => 'fadeIn',
                    esc_html__('FadeIn From Bottom', "vitrine") => 'fadeInFromBottom',
                    esc_html__('FadeIn From Top', "vitrine") => 'fadeInFromTop',
                    esc_html__('FadeIn From Right', "vitrine") => 'fadeInFromRight',
                    esc_html__('FadeIn From Left', "vitrine") => 'fadeInFromLeft',
                    esc_html__('Zoom-in', "vitrine") => 'zoomIn',
                    esc_html__('No Animation', "vitrine")  => 'default',
                ),     
                "group" => esc_html__('Animation','vitrine')         
            ),
			array(
			    "type" => "vc_multiselect",
			    "class" => "",
			    "heading" => esc_html__("Animation in Responsive", "vitrine"),
			    "param_name" => "responsive_animation",
			    "options" => array("disable" => "Disable animation"),
			    "value" => "disable",
			    "description" => esc_html__("Disable animation in mobiles and tablet devices.", "vitrine") ,
			    "group" => esc_html__('Animation','vitrine')
			),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Carousel/Grid Mode", "vitrine"),
                "param_name" => "carousel",
                "description" => esc_html__("Enable Carousel/Grid mode for products", "vitrine"),
                    "value" => array(
                        "Carousel" => "enable",
                        "Grid" => "disable",
                    ),
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Autoplay", "vitrine"),
                "param_name" => "is_autoplay",
                "description" => esc_html__("Autoplay of carousel", "vitrine"),
                "value" => array(
                    "Off" => "off",
                    "On" => "on",
                ),
                "dependency" => array(
                    'element' => "carousel", 
                    'value' => "enable"
                )
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Navigations Style", "vitrine"),
                "param_name" => "nav_style",
                "description" => esc_html__("Choose dark or light style of navigation", "vitrine") ,
                    "value" => array(
                        "Light" => "light",
                        "Dark" => "dark",
                    ),
                    "dependency" => array(
                        'element' => "carousel", 
                        'value' => "enable"
                    )
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Style", "vitrine"),
                "param_name" => "style",
                "description" => esc_html__("Choose style of items", "vitrine") ,
                    "value" => array(
                        "Buttons on hover" => "style1",
                        "Buttons on hover - centered title" => "style1-center",
                        "Info on hover" => "style2",
                        "Info on click" => "style3",
                        "Instant shop" => "style4",
                    ),
            ),
            array(
                "type" => "dropdown",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Layout Mode", "vitrine"),
                "param_name" => "layout_mode",
                "description" => esc_html__("Choose dark or light style of navigation", "vitrine") ,
                    "value" => array(
                        "Masonry" => "masonry",
                        "Fit Rows" => "fitRows",
                    ),
                    "dependency" => array(
                        'element' => "carousel", 
                        'value' => "disable"
                    )
            ),
             array(
                "type" => "vc_imageselect",
                "class" => "presets",
                "heading" => esc_html__("Hover color", "vitrine"),
                "param_name" => "hover_color",
                "description" => esc_html__("Select hover color.", "vitrine") , 
                "value" => array(
                        "c0392b" => "c0392b",
                        "e74c3c" => "e74c3c",
                        "d35400" => "d35400",
                        "e67e22" => "e67e22",
                        "f39c12" => "f39c12",
                        "f1c40f" => "f1c40f",
                        "1abc9c" => "1abc9c",
                        "2ecc71" => "2ecc71",
                        "3498db" => "3498db",
                        "01558f" => "01558f",
                        "9b59b6" => "9b59b6",
                        "ecf0f1" => "ecf0f1",
                        "bdc3c7" => "bdc3c7",
                        "7f8c8d" => "7f8c8d",
                        "95a5a6" => "95a5a6",
                        "34495e" => "34495e",
                        "2e2e2e" => "2e2e2e",
                        "custom-color" => "custom"
                ),
                "dependency" => array(
                    'element' => "style", 
                    'value' => "style2"
                )
            ),
            array(
                "type" => "colorpicker",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Custom hover Color", "vitrine"),
                "param_name" => "custom_hover_color",
                "value" => "",
                "description" => esc_html__("Enter a custom hover color", "vitrine") , 
                "dependency" => Array(
                    'element' => "hover_color", 
                    'value' => "custom"
                ) 
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Gutter", "vitrine"),
                "param_name" => "gutter",
                "options" => array("no" => "No gutter"),
                "description" => esc_html__("Remove gutter between items", "vitrine"),
                "value" => "",
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Border", "vitrine"),
                "param_name" => "border",
                "options" => array("disable" => "Disable border"),
                "description" => esc_html__("Disable border around the product box", "vitrine"),
                "value" => "",
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Quick View Button", "vitrine"),
                "param_name" => "quickview",
                "options" => array("disable" => "Disable quickview"),
                "description" => "Disable quick view button",
                "value" => "",
                "dependency" => array(
                    'element' => "style", 
                    'value' => array("style1","style1-center","style2","style4")
                )
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("wishlist button", "vitrine"),
                "param_name" => "wishlist",
                "options" => array("disable" => "Disable wishlist"),
                "description" => esc_html__("Disable wishlist button","vitrine"),
                "value" => "",
                "dependency" => array(
                    'element' => "style", 
                    'value' => array("style1","style1-center","style2","style4")
                )
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Compare Button", "vitrine"),
                "param_name" => "compare",
                "options" => array("disable" => "Disable compare"),
                "description" => "Disable compare button",
                "value" => "",
	            "dependency" => array(
	                'element' => "style", 
	                'value' => array("style1","style1-center","style2")
	            )
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("Badges", "vitrine"),
                "param_name" => "badges",
                "options" => array("disable" => "Disable badges"),
                "description" => esc_html__("Disable badges ( sales , out of stock ...)","vitrine"),
                "value" => ""
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("hover price", "vitrine"),
                "param_name" => "hover_price",
                "options" => array("disable" => "Disable hover price"),
                "description" => esc_html__("Disable hover price", "vitrine"),
                "value" => "",
                "dependency" => array(
                    'element' => "style", 
                    'value' => array("style2")
                )
            ),
            array(
                "type" => "vc_multiselect",
                "class" => "",
                "admin_label" => true,
                "heading" => esc_html__("hover description", "vitrine"),
                "param_name" => "hover_description",
                "options" => array("disable" => "Disable hover description"),
                "description" => esc_html__("Disable hover description / In 5 column is disabled by default ", "vitrine"),
                "dependency" => array(
                    'element' => "style", 
                    'value' => array("style3")
                )
            ),
        );

        vc_add_params('recent_products',$wc_shortcode_category_setting);
        vc_add_params('best_selling_products',$wc_shortcode_category_setting);
        vc_add_params('top_rated_products',$wc_shortcode_category_setting);
        vc_add_params('featured_products',$wc_shortcode_category_setting);
        vc_add_params('products',$wc_shortcode_setting);
        vc_add_params('recent_products',$wc_shortcode_setting);
        vc_add_params('sale_products',$wc_shortcode_setting);
        vc_add_params('best_selling_products',$wc_shortcode_setting);
        vc_add_params('top_rated_products',$wc_shortcode_setting);
        vc_add_params('featured_products',$wc_shortcode_setting);
        vc_add_params('product_category',$wc_shortcode_setting);
        vc_add_params('product_attribute',$wc_shortcode_setting);



        //*---------------------------*/
        /* Product categories
        //*---------------------------*/
        $categories_options = array(
                array(
                    "type" => "dropdown",
                    "holder" => "",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Columns", "vitrine"), 
                    "param_name" => "columns",
                    "description" => esc_html__("Select number of columns", "vitrine") ,
                    "value" => array(
                       "1" => "1",
                       "2" => "2",
                       "3" => "3",
                       "4" => "4",
                       "5" => "5",
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__("Image size", "vitrine"),
                    "param_name" => "image_size",
                    "description" => esc_html__("Select image size", "vitrine"),
                    "value" => array(
                        "Single Product Image" => "shop_single",
                        "Catalog Images" => "shop_catalog",
                        "Product Thumbnails" => "shop_thumbnail",
                        "Full" => "full",
                        "Custom" => "custom"
                    ),
                ),
				array(
				    "type" => "textfield",
				    "class" => "custom_image_size",
				    "admin_label" => false,
				    "heading" => esc_html__("Custom image size", "vitrine"),
				    "param_name" => "image_size_width",
				    "value" => "",
				    "description" => esc_html__("Width", "vitrine") ,
	                "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                )
				),
				array(
				    "type" => "textfield",
				    "class" => "custom_image_size",
				    "admin_label" => false,
				    "heading" => esc_html__("image size height", "vitrine"),
				    "param_name" => "image_size_height",
				    "value" => "",
				    "description" => esc_html__("Height", "vitrine") ,
	                "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                )
				),
	            array(
	                "type" => "vc_multiselect",
	                "class" => "custom_image_size",
	                "heading" => esc_html__("image size crop", "vitrine"),
	                "param_name" => "image_size_crop",
	                "options" => array("yes" => "Crop image"),
	                "value" => "",
	                 "dependency" => array(
	                    'element' => "image_size", 
	                    'value' => 'custom'
	                 )
	            ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Border", "vitrine"),
                    "param_name" => "border",
                    "options" => array("disable" => "Disable border"),
                    "description" => esc_html__("Disable border around the product box", "vitrine"),
                    "value" => "",
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Hide empty categories", "vitrine"),
                    "param_name" => "hide_empty",
                    "options" => array("true" => "Hide empty"),
                    "description" => esc_html__("Hide categories that have no any products", "vitrine"),
                    "value" => "",
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Gutter", "vitrine"),
                    "param_name" => "gutter",
                    "options" => array("no" => "No gutter"),
                    "description" => esc_html__("Remove gutter between items", "vitrine"),
                    "value" => "",
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Text color", "vitrine"),
                    "param_name" => "style",
                    "value" => "",
                    "description" => esc_html__("Select category text color", "vitrine") , 
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Show Product count", "vitrine"),
                    "param_name" => "count",
                    "options" => array("enable" => "Show product count"),
                    "description" => "Show product count",
                    "value" => "",
                ),
                array(
                    "type" => "vc_multiselect",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => esc_html__("Show category description", "vitrine"),
                    "param_name" => "description",
                    "options" => array("enable" => "Show category description"),
                    "description" => "Show category description",
                    "value" => "enable",
                ),
                array(
                    "type" => "vc_imageselect",
                    "class" => "presets",
                    "heading" => esc_html__("Hover color", "vitrine"),
                    "param_name" => "hover_color",
                    "description" => esc_html__("Select hover color.", "vitrine") , 
                    "value" => array(
                            "c0392b" => "c0392b",
                            "e74c3c" => "e74c3c",
                            "d35400" => "d35400",
                            "e67e22" => "e67e22",
                            "f39c12" => "f39c12",
                            "f1c40f" => "f1c40f",
                            "1abc9c" => "1abc9c",
                            "2ecc71" => "2ecc71",
                            "3498db" => "3498db",
                            "01558f" => "01558f",
                            "9b59b6" => "9b59b6",
                            "ecf0f1" => "ecf0f1",
                            "bdc3c7" => "bdc3c7",
                            "7f8c8d" => "7f8c8d",
                            "95a5a6" => "95a5a6",
                            "34495e" => "34495e",
                            "2e2e2e" => "2e2e2e",
                            "custom-color" => "custom"
                    )
                ),
                array(
                    "type" => "colorpicker",
                    "admin_label" => true,
                    "class" => "",
                    "heading" => esc_html__("Custom hover Color", "vitrine"),
                    "param_name" => "custom_hover_color",
                    "value" => "",
                    "description" => esc_html__("Enter a custom hover color", "vitrine") , 
                    "dependency" => Array(
                        'element' => "hover_color", 
                        'value' => "custom"
                    ) 
                ),
                array(
                "type" => "colorpicker",
                "admin_label" => true,
                "class" => "",
                "heading" => esc_html__("Hover Text Color", "vitrine"),
                "param_name" => "hover_text_color",
                "value" => "",
                "description" => esc_html__("This is the font color when category is hovered.", "vitrine") , 
                ),
				array(
				    "type" => "dropdown",
				    "class" => "",
				    "heading" => esc_html__("Animation", "vitrine"),
				    "param_name" => "animation",
				    "description" => esc_html__("Select animation type", "vitrine") ,
				    "value" => $animations,
                    "group" => esc_html__('Animation','vitrine')
				),
				array(
				    "type" => "textfield",
				    "class" => "",
				    "heading" => esc_html__("Animation Delay", "vitrine"),
				    "param_name" => "delay",
				    "value" => "",
				    "description" => esc_html__("Enter animation delay (in milliseconds), for example if you want 3 seconds delay, you should enter 3000 .", "vitrine") ,
                    "group" => esc_html__('Animation','vitrine')
				)
            );

            vc_add_params('product_categories',$categories_options);
            
    }
}

add_action( 'vc_after_mapping', 'epico_vc_change_woocommerce_elements', 15 );


/*-----------------------------------------------------------------------------------*/
/* VC basic grid
/*-----------------------------------------------------------------------------------*/
// remove filter
vc_remove_param( 'vc_basic_grid', 'show_filter' );
vc_remove_param( 'vc_basic_grid', 'filter_source' );
vc_remove_param( 'vc_basic_grid', 'exclude_filter' );
vc_remove_param( 'vc_basic_grid', 'filter_style' );
vc_remove_param( 'vc_basic_grid', 'filter_align' );
vc_remove_param( 'vc_basic_grid', 'filter_color' );
vc_remove_param( 'vc_basic_grid', 'filter_size' );

// remove load more button style
vc_remove_param( 'vc_basic_grid', 'button_color' );
vc_remove_param( 'vc_basic_grid', 'button_size' );
vc_remove_param( 'vc_basic_grid', 'button_style' );

// remove pagination options
vc_remove_param( 'vc_basic_grid', 'arrows_design' );
vc_remove_param( 'vc_basic_grid', 'arrows_color' );
vc_remove_param( 'vc_basic_grid', 'arrows_position' );
vc_remove_param( 'vc_basic_grid', 'paging_design' );
vc_remove_param( 'vc_basic_grid', 'paging_color' );


/*-----------------------------------------------------------------------------------*/
/* Add container functionality to shortcodes
/*-----------------------------------------------------------------------------------*/

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    
    class WPBakeryShortCode_showcase extends WPBakeryShortCodesContainer {
    }

    class WPBakeryShortCode_testimonial extends WPBakeryShortCodesContainer {
    }
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_showcase_item extends WPBakeryShortCode {

        // Show Images in images Selector ( VC )
        
        public function singleParamHtmlHolder( $param, $value ) {
            $output = '';
            // Compatibility fixes
            $old_names = array( 'yellow_message', 'blue_message', 'green_message', 'button_green', 'button_grey', 'button_yellow', 'button_blue', 'button_red', 'button_orange' );
            $new_names = array( 'alert-block', 'alert-info', 'alert-success', 'btn-success', 'btn', 'btn-info', 'btn-primary', 'btn-danger', 'btn-warning' );
            $value = str_ireplace( $old_names, $new_names, $value );

            $param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
            $type = isset( $param['type'] ) ? $param['type'] : '';
            $class = isset( $param['class'] ) ? $param['class'] : '';
    
            if ( isset( $param['holder'] ) == false || $param['holder'] == 'hidden' || $param['holder'] == '') {
                $output .= '<input type="hidden" class="wpb_vc_param_value ' . esc_attr($param_name . ' ' . $type . ' ' . $class) . '" name="' . esc_attr($param_name) . '" value="' . esc_attr($value) . '" />';
                if ( ( $param['type'] ) == 'attach_images' ) {
                    $images_ids = empty( $value ) ? array() : explode( ',', trim( $value ) );
                    $output .= '<ul class="attachment-thumbnails thumb-attach-images' . ( empty( $images_ids ) ? ' image-exists' : '' ) . '" data-name="' . esc_attr($param_name) . '">';
                    foreach ( $images_ids as $image ) {
                        $img = wpb_getImageBySize( array( 'attach_id' => (int)$image, 'thumb_size' => 'thumbnail' ) );
                        $output .= ( $img ? '<li>' . $img['thumbnail'] . '</li>' : '<li><img width="32" height="32" test="' . esc_url($image) . '" src="' . esc_url(vc_asset_url( 'vc/blank.gif' )) . '" class="attachment-thumbnail" alt="" title="" /></li>' );
                    }
                    $output .= '</ul>';
                }
    
            } else {
                $output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . esc_attr($param_name . ' ' . $type . ' ' . $class) . '" name="' . esc_attr($param_name) . '">' . $value . '</' . $param['holder'] . '>';
            }
    
            if ( ! empty( $param['admin_label'] ) && $param['admin_label'] === true ) {
                $output .= '<h4 class="wpb_element_title">' . 'showcase item' . '</h4>';
				$output .= sprintf('<span class="vc_admin_label admin_label_' . $param['param_name'] . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>%s</label>: ' . $value . '</span>',esc_attr( $param['heading'] ));  
			}
    
            return $output;
        }
        protected function outputTitle($title) {
            return '';
        }
        protected function outputTitleTrue($title) {
            return  '<h4 class="wpb_element_title">'. $title.' '.$this->settings('logo').'</h4>';
        }
    }

   class WPBakeryShortCode_testimonial_item extends WPBakeryShortCode {

        // Show Images in images Selector ( VC )
        public function singleParamHtmlHolder( $param, $value ) {
	        $output = '';
	        // Compatibility fixes
	        $old_names = array( 'yellow_message', 'blue_message', 'green_message', 'button_green', 'button_grey', 'button_yellow', 'button_blue', 'button_red', 'button_orange' );
	        $new_names = array( 'alert-block', 'alert-info', 'alert-success', 'btn-success', 'btn', 'btn-info', 'btn-primary', 'btn-danger', 'btn-warning' );
	        $value = str_ireplace( $old_names, $new_names, $value );

	        $param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
	        $type = isset( $param['type'] ) ? $param['type'] : '';
	        $class = isset( $param['class'] ) ? $param['class'] : '';


	        if ( isset( $param['holder'] ) == false || $param['holder'] == 'hidden' ) {
	            $output .= '<input type="hidden" class="wpb_vc_param_value ' . esc_attr($param_name . ' ' . $type . ' ' . $class) . '" name="' . esc_attr($param_name) . '" value="' . esc_attr($value) . '" />';
	            if ( ( $param['type'] ) == 'attach_image' ) {
	                $element_icon = $this->settings('icon');
	                $img = wpb_getImageBySize( array( 'attach_id' => (int)preg_replace( '/[^\d]/', '', $value ), 'thumb_size' => 'thumbnail' ) );
	                $this->setSettings('logo', ( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . esc_url(vc_asset_url( 'vc/blank.gif' )) . '" class="attachment-thumbnail vc_element-icon"  data-name="' . esc_attr($param_name) . '" alt="" title="" style="display: none;" />' ) . '<span class="no_image_image vc_element-icon' . esc_attr(( !empty($element_icon) ? ' '.$element_icon : '' ) . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' )) . '" />');
	                $output .= $this->outputTitleTrue($this->settings['name']);
	                
	            }
            

	        } else {
	            $output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . esc_attr($param_name . ' ' . $type . ' ' . $class) . '" name="' . esc_attr($param_name) . '">' . $value . '</' . $param['holder'] . '>';
	        }


	        if ( ! empty( $param['admin_label'] ) && $param['admin_label'] === true ) {

				$output .= sprintf('<span class="vc_admin_label admin_label_' . esc_attr($param['param_name']) . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>%s</label>: ' . $value . '</span>',esc_attr( $param['heading'] ));

	        }

	        return $output;
    	}
	    protected function outputTitle($title) {
	        return '';
	    }
	    protected function outputTitleTrue($title) {
	        return  '<h4 class="wpb_element_title">'. $title.' '.$this->settings('logo').'</h4>';
	    }
    }
    
    
    class WPBakeryShortCode_image_carousel extends WPBakeryShortCode {

        public function singleParamHtmlHolder( $param, $value ) {
            $output = '';
            // Compatibility fixes
            $old_names = array( 'yellow_message', 'blue_message', 'green_message', 'button_green', 'button_grey', 'button_yellow', 'button_blue', 'button_red', 'button_orange' );
            $new_names = array( 'alert-block', 'alert-info', 'alert-success', 'btn-success', 'btn', 'btn-info', 'btn-primary', 'btn-danger', 'btn-warning' );
            $value = str_ireplace( $old_names, $new_names, $value );

            $param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
            $type = isset( $param['type'] ) ? $param['type'] : '';
            $class = isset( $param['class'] ) ? $param['class'] : '';
            
            if ( isset( $param['holder'] ) == false || $param['holder'] == 'hidden' || $param['holder'] == '') {
                $output .= '<input type="hidden" class="wpb_vc_param_value ' . esc_attr($param_name . ' ' . $type . ' ' . $class) . '" name="' . esc_attr($param_name) . '" value="' . esc_attr($value) . '" />';
                if ( ( $param['type'] ) == 'attach_images' ) {
                    $images_ids = empty( $value ) ? array() : explode( ',', trim( $value ) );
                    $output .= '<ul class="attachment-thumbnails thumb-attach-images' . ( !empty( $images_ids ) ? ' image-exists' : '' ) . '" data-name="' . esc_attr($param_name) . '">';
                    foreach ( $images_ids as $image ) {
                        $img = wpb_getImageBySize( array( 'attach_id' => (int)$image, 'thumb_size' => 'thumbnail' ) );
                        $output .= ( $img ? '<li>' . $img['thumbnail'] . '</li>' : '<li><img width="32" height="32" test="' . esc_url($image) . '" src="' . esc_url(vc_asset_url( 'vc/blank.gif' )) . '" class="attachment-thumbnail" alt="" title="" /></li>' );
                    }
                    $output .= '</ul>';
                }
                
            } else {
                $output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . esc_attr($param_name . ' ' . $type . ' ' . $class) . '" name="' . esc_attr($param_name) . '">' . $value . '</' . $param['holder'] . '>';
            }
            
            if ( ! empty( $param['admin_label'] ) && $param['admin_label'] === true ) {

				$output .= sprintf('<span class="vc_admin_label admin_label_' . $param['param_name'] . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>%s</label>: ' . $value . '</span>',esc_attr( $param['heading'] ));

			}
            
            return $output;
        }
    }
	
}

if( ! function_exists( 'productTagsAutocompleteSuggesterBySlug' ) ) {
	function productTagsAutocompleteSuggesterBySlug( $query, $slug = true ) {
		global $wpdb;
		$cat_id = (int) $query;
		$query = trim( $query );
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = 'product_tag' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )", $cat_id > 0 ? $cat_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

		$result = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data = array();
				$data['value'] = $slug ? $value['slug'] : $value['id'];
				$data['label'] = __( 'Id', 'vitrine' ) . ': ' . $value['id'] . ( ( strlen( $value['name'] ) > 0 ) ? ' - ' . __( 'Name', 'vitrine' ) . ': ' . $value['name'] : '' ) . ( ( strlen( $value['slug'] ) > 0 ) ? ' - ' . __( 'Slug', 'vitrine' ) . ': ' . $value['slug'] : '' );
				$result[] = $data;
			}
		}

		return $result;
	}
}
if( ! function_exists( 'productTagsRenderBySlugExact' ) ) {
	function productTagsRenderBySlugExact( $query ) {
		$query = $query['value'];
		$query = trim( $query );
		$term  = get_term_by( 'slug', $query, 'product_tag' );

		if ( is_wp_error( $term ) || ! $term ) {
			return false;
		}

		$data          = array();
		$data['value'] = $term->slug;
		$data['label'] = esc_html__( 'Id', 'vitrine' ) . ': ' . $term->term_id . ' - ' . esc_html__( 'Name', 'vitrine' ) . ': ' . $term->name;


		return $data;
	}
}