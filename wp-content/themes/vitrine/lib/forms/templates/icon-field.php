<?php
$name     = $vars['key'];
$settings = $vars['settings'];
$class    = epico_array_value('class', $settings);//Optional value
$flags    = epico_array_value('flags', $settings);//Optional value
$label       = epico_array_value('label', $settings);//Optional value

//remove -{id} from end of name of filed[just used in admin menu page] becouse in that page we make names unique by adding -{id}
$generalKey = rtrim($name, "-". get_the_ID());

$value       = ( ( esc_attr( $this->epico_GetValue($generalKey) ) == "") && (epico_array_value('value', $settings) != "") ) ?  epico_array_value('value', $settings) : esc_attr( $this->epico_GetValue($generalKey) );

$icons = array('home','home2','home3','home4','home5','home6','bathtub','toothbrush','bed','couch','chair','city','apartment','pencil','pencil2','pen','pencil3','eraser','pencil4','pencil5','feather','feather2','feather3','pen2','pen-add','pen-remove','vector','pen3','blog','brush','brush2','spray','paint-roller','stamp','tape','desk-tape','texture','eye-dropper','palette','color-sampler','bucket','gradient','gradient2','magic-wand','magnet','pencil-ruler','pencil-ruler2','compass','aim','gun','bottle','drop','drop-crossed','drop2','snow','snow2','fire','lighter','knife','dagger','tissue','toilet-paper','poop','umbrella','umbrella2','rain','tornado','wind','fan','contrast','sun-small','sun','sun2','moon','cloud','cloud-upload','cloud-download','cloud-rain','cloud-hailstones','cloud-snow','cloud-windy','sun-wind','cloud-fog','cloud-sun','cloud-lightning','cloud-sync','cloud-lock','cloud-gear','cloud-alert','cloud-check','cloud-cross','cloud-crossed','cloud-database','database','database-add','database-remove','database-lock','database-refresh','database-check','database-history','database-upload','database-download','server','shield','shield-check','shield-alert','shield-cross','lock','rotation-lock','unlock','key','key-hole','toggle-off','toggle-on','cog','cog2','wrench','screwdriver','hammer-wrench','hammer','saw','axe','axe2','shovel','pickaxe','factory','factory2','recycle','trash','trash2','trash3','broom','game','gamepad','joystick','dice','spades','diamonds','clubs','hearts','heart','star','star-half','star-empty','flag','flag2','flag3','mailbox-full','mailbox-empty','at-sign','envelope','envelope-open','paperclip','paper-plane','reply','reply-all','inbox','inbox2','outbox','box','archive','archive2','drawers','drawers2','drawers3','eye','eye-crossed','eye-plus','eye-minus','binoculars','binoculars2','hdd','hdd-down','hdd-up','floppy-disk','disc','tape2','printer','shredder','file-empty','file-add','file-check','file-lock','files','copy','compare','folder','folder-search','folder-plus','folder-minus','folder-download','folder-upload','folder-star','folder-heart','folder-user','folder-shared','folder-music','folder-picture','folder-film','scissors','paste','clipboard-empty','clipboard-pencil','clipboard-text','clipboard-check','clipboard-down','clipboard-left','clipboard-alert','clipboard-user','register','enter','exit','papers','news','reading','typewriter','document','document2','graduation-hat','license','license2','medal-empty','medal-first','medal-second','medal-third','podium','trophy','trophy2','music-note','music-note2','music-note3','playlist','playlist-add','guitar','trumpet','album','shuffle','repeat-one','repeat','headphones','headset','loudspeaker','equalizer','theater','3d-glasses','ticket','presentation','play','film-play','clapboard-play','media','film','film2','surveillance','surveillance2','camera','camera-crossed','camera-play','time-lapse','record','camera2','camera-flip','panorama','time-lapse2','shutter','shutter2','face-detection','flare','convex','concave','picture','picture2','picture3','pictures','book','audio-book','book2','bookmark','bookmark2','label','library','library2','contacts','profile','portrait','portrait2','user','user-plus','user-minus','user-lock','users','users2','users-plus','users-minus','group-work','woman','man','baby','baby2','baby3','baby-bottle','walk','hand-waving','jump','run','woman2','man2','man-woman','height','weight','scale','button','bow-tie','tie','socks','shoe','shoes','hat','pants','shorts','flip-flops','shirt','hanger','laundry','store','haircut','store-24','barcode','barcode2','barcode3','cashier','bag','bag2','cart','cart-empty','cart-full','cart-plus','cart-plus2','cart-add','cart-remove','cart-exchange','tag','tags','receipt','wallet','credit-card','cash-dollar','cash-euro','cash-pound','cash-yen','bag-dollar','bag-euro','bag-pound','bag-yen','coin-dollar','coin-euro','coin-pound','coin-yen','calculator','calculator2','abacus','vault','telephone','phone-lock','phone-wave','phone-pause','phone-outgoing','phone-incoming','phone-in-out','phone-error','phone-sip','phone-plus','phone-minus','voicemail','dial','telephone2','pushpin','pushpin2','map-marker','map-marker-user','map-marker-down','map-marker-check','map-marker-crossed','radar','compass2','map','map2','location','road-sign','calendar-empty','calendar-check','calendar-cross','calendar-31','calendar-full','calendar-insert','calendar-text','calendar-user','mouse','mouse-left','mouse-right','mouse-both','keyboard','keyboard-up','keyboard-down','delete','spell-check','escape','enter2','screen','aspect-ratio','signal','signal-lock','signal-80','signal-60','signal-40','signal-20','signal-0','signal-blocked','sim','flash-memory','usb-drive','phone','smartphone','smartphone-notification','smartphone-vibration','smartphone-embed','smartphone-waves','tablet','tablet2','laptop','laptop-phone','desktop','launch','new-tab','window','cable','cable2','tv','radio','remote-control','power-switch','power','power-crossed','flash-auto','lamp','flashlight','lampshade','cord','outlet','battery-power','battery-empty','battery-alert','battery-error','battery-low1','battery-low2','battery-low3','battery-mid1','battery-mid2','battery-mid3','battery-full','battery-charging','battery-charging2','battery-charging3','battery-charging4','battery-charging5','battery-charging6','battery-charging7','chip','chip-x64','chip-x86','bubble','bubbles','bubble-dots','bubble-alert','bubble-question','bubble-text','bubble-pencil','bubble-picture','bubble-video','bubble-user','bubble-quote','bubble-heart','bubble-emoticon','bubble-attachment','phone-bubble','quote-open','quote-close','dna','heart-pulse','pulse','syringe','pills','first-aid','lifebuoy','bandage','bandages','thermometer','microscope','brain','beaker','skull','bone','construction','construction-cone','pie-chart','pie-chart2','graph','chart-growth','chart-bars','chart-settings','cake','gift','balloon','rank','rank2','rank3','crown','lotus','diamond','diamond2','diamond3','diamond4','linearicons','teacup','teapot','glass','bottle2','glass-cocktail','glass2','dinner','dinner2','chef','scale2','egg','egg2','eggs','platter','steak','hamburger','hotdog','pizza','sausage','chicken','fish','carrot','cheese','bread','ice-cream','ice-cream2','candy','lollipop','coffee-bean','coffee-cup','cherry','grapes','citrus','apple','leaf','landscape','pine-tree','tree','cactus','paw','footprint','speed-slow','speed-medium','speed-fast','rocket','hammer2','balance','briefcase','luggage-weight','dolly','plane','plane-crossed','helicopter','traffic-lights','siren','road','engine','oil-pressure','coolant-temperature','car-battery','gas','gallon','transmission','car','car-wash','car-wash2','bus','bus2','car2','parking','car-lock','taxi','car-siren','car-wash3','car-wash4','ambulance','truck','trailer','scale-truck','train','ship','ship2','anchor','boat','bicycle','bicycle2','dumbbell','bench-press','swim','football','baseball-bat','baseball','tennis','tennis2','ping-pong','hockey','8ball','bowling','bowling-pins','golf','golf2','archery','slingshot','soccer','basketball','cube','3d-rotate','puzzle','glasses','glasses2','accessibility','wheelchair','wall','fence','wall2','icons','resize-handle','icons2','select','select2','site-map','earth','earth-lock','network','network-lock','planet','happy','smile','grin','tongue','sad','wink','dream','shocked','shocked2','tongue2','neutral','happy-grin','cool','mad','grin-evil','evil','wow','annoyed','wondering','confused','zipped','grumpy','mustache','tombstone-hipster','tombstone','ghost','ghost-hipster','halloween','christmas','easter-egg','mustache2','mustache-glasses','pipe','alarm','alarm-add','alarm-snooze','alarm-ringing','bullhorn','hearing','volume-high','volume-medium','volume-low','volume','mute','lan','lan2','wifi','wifi-lock','wifi-blocked','wifi-mid','wifi-low','wifi-low2','wifi-alert','wifi-alert-mid','wifi-alert-low','wifi-alert-low2','stream','stream-check','stream-error','stream-alert','communication','communication-crossed','broadcast','antenna','satellite','satellite2','mic','mic-mute','mic2','spotlights','hourglass','loading','loading2','loading3','refresh','refresh2','undo','redo','jump2','undo2','redo2','sync','repeat-one2','sync-crossed','sync2','repeat-one3','sync-crossed2','return','return2','refund','history','history2','self-timer','clock','clock2','clock3','watch','alarm2','alarm-add2','alarm-remove','alarm-check','alarm-error','timer','timer-crossed','timer2','timer-crossed2','download','upload','download2','upload2','enter-up','enter-down','enter-left','enter-right','exit-up','exit-down','exit-left','exit-right','enter-up2','enter-down2','enter-vertical','enter-left2','enter-right2','enter-horizontal','exit-up2','exit-down2','exit-left2','exit-right2','cli','bug','code','file-code','file-image','file-zip','file-audio','file-video','file-preview','file-charts','file-stats','file-spreadsheet','link','unlink','link2','unlink2','thumbs-up','thumbs-down','thumbs-up2','thumbs-down2','thumbs-up3','thumbs-down3','share','share2','share3','magnifier','file-search','find-replace','zoom-in','zoom-out','loupe','loupe-zoom-in','loupe-zoom-out','cross','menu','list','list2','list3','menu2','list4','menu3','exclamation','question','check','cross2','plus','minus','percent','chevron-up','chevron-down','chevron-left','chevron-right','chevrons-expand-vertical','chevrons-expand-horizontal','chevrons-contract-vertical','chevrons-contract-horizontal','arrow-up','arrow-down','arrow-left','arrow-right','arrow-up-right','arrows-merge','arrows-split','arrow-divert','arrow-return','expand','contract','expand2','contract2','move','tab','arrow-wave','expand3','expand4','contract3','notification','warning','notification-circle','question-circle','menu-circle','checkmark-circle','cross-circle','plus-circle','circle-minus','percent-circle','arrow-up-circle','arrow-down-circle','arrow-left-circle','arrow-right-circle','chevron-up-circle','chevron-down-circle','chevron-left-circle','chevron-right-circle','backward-circle','first-circle','previous-circle','stop-circle','play-circle','pause-circle','next-circle','last-circle','forward-circle','eject-circle','crop','frame-expand','frame-contract','focus','transform','grid','grid-crossed','layers','layers-crossed','toggle','rulers','ruler','funnel','flip-horizontal','flip-vertical','flip-horizontal2','flip-vertical2','angle','angle2','subtract','combine','intersect','exclude','align-center-vertical','align-right','align-bottom','align-left','align-center-horizontal','align-top','square','plus-square','minus-square','percent-square','arrow-up-square','arrow-down-square','arrow-left-square','arrow-right-square','chevron-up-square','chevron-down-square','chevron-left-square','chevron-right-square','check-square','cross-square','menu-square','prohibited','circle','radio-button','ligature','text-format','text-format-remove','text-size','bold','italic','underline','strikethrough','highlight','text-align-left','text-align-center','text-align-right','text-align-justify','line-spacing','indent-increase','indent-decrease','text-wrap','pilcrow','direction-ltr','direction-rtl','page-break','page-break2','sort-alpha-asc','sort-alpha-desc','sort-numeric-asc','sort-numeric-desc','sort-amount-asc','sort-amount-desc','sort-time-asc','sort-time-desc','sigma','pencil-line','hand','pointer-up','pointer-right','pointer-down','pointer-left','finger-tap','fingers-tap','reminder','fingers-crossed','fingers-victory','gesture-zoom','gesture-pinch','fingers-scroll-horizontal','fingers-scroll-vertical','fingers-scroll-left','fingers-scroll-right','hand2','pointer-up2','pointer-right2','pointer-down2','pointer-left2','finger-tap2','fingers-tap2','reminder2','gesture-zoom2','gesture-pinch2','fingers-scroll-horizontal2','fingers-scroll-vertical2','fingers-scroll-left2','fingers-scroll-right2','fingers-scroll-vertical3','border-style','border-all','border-outer','border-inner','border-top','border-horizontal','border-bottom','border-left','border-vertical','border-right','border-none','ellipsis',
'asterisk','plus2','question2','minus2','glass3','music','search','envelope-o','heart2','star2','star-o','user2','film3','th-large','th','th-list','check2','close','remove','times','search-plus','search-minus','power-off','signal2','cog3','gear','trash-o','home7','file-o','clock-o','road2','download3','arrow-circle-o-down','arrow-circle-o-up','inbox3','play-circle-o','repeat2','rotate-right','refresh3','list-alt','lock2','flag4','headphones2','volume-off','volume-down','volume-up','qrcode','barcode4','tag2','tags2','book3','bookmark3','print','camera3','font','bold2','italic2','text-height','text-width','align-left2','align-center','align-right2','align-justify','list5','dedent','outdent','indent','video-camera','image','photo','picture-o','pencil6','map-marker2','adjust','tint','edit','pencil-square-o','share-square-o','check-square-o','arrows','step-backward','fast-backward','backward','play2','pause','stop','forward','fast-forward','step-forward','eject','chevron-left2','chevron-right2','plus-circle2','minus-circle','times-circle','check-circle','question-circle2','info-circle','crosshairs','times-circle-o','check-circle-o','arrow-left2','arrow-right2','arrow-up2','arrow-down2','mail-forward','share4','expand5','compress','exclamation-circle','gift2','leaf2','fire2','eye2','eye-slash','exclamation-triangle','warning2','plane2','calendar','random','comment','magnet2','chevron-up2','chevron-down2','retweet','shopping-cart','folder2','folder-open','arrows-v','arrows-h','bar-chart','bar-chart-o','twitter-square','facebook-square','camera-retro','key2','cogs','gears','comments','thumbs-o-up','thumbs-o-down','star-half2','heart-o','sign-out','linkedin-square','thumb-tack','external-link','sign-in','trophy3','github-square','upload3','lemon-o','phone2','square-o','bookmark-o','phone-square','twitter','facebook','facebook-f','github','unlock2','credit-card2','feed','rss','hdd-o','bullhorn2','bell-o','certificate','hand-o-right','hand-o-left','hand-o-up','hand-o-down','arrow-circle-left','arrow-circle-right','arrow-circle-up','arrow-circle-down','globe','wrench2','tasks','filter','briefcase2','arrows-alt','group','users3','chain','link3','cloud2','flask','cut','scissors2','copy2','files-o','paperclip2','floppy-o','save','square2','bars','navicon','reorder','list-ul','list-ol','strikethrough2','underline2','table','magic','truck2','pinterest','pinterest-square','google-plus-square','google-plus','money','caret-down','caret-up','caret-left','caret-right','columns','sort','unsorted','sort-desc','sort-down','sort-asc','sort-up','envelope2','linkedin','rotate-left','undo3','gavel','legal','dashboard','tachometer','comment-o','comments-o','bolt','flash','sitemap','umbrella3','clipboard','paste2','lightbulb-o','exchange','cloud-download2','cloud-upload2','user-md','stethoscope','suitcase','bell','coffee','cutlery','file-text-o','building-o','hospital-o','ambulance2','medkit','fighter-jet','beer','h-square','plus-square2','angle-double-left','angle-double-right','angle-double-up','angle-double-down','angle-left','angle-right','angle-up','angle-down','desktop2','laptop2','tablet3','mobile','mobile-phone','circle-o','quote-left','quote-right','spinner','circle2','mail-reply','reply2','github-alt','folder-o','folder-open-o','smile-o','frown-o','meh-o','gamepad2','keyboard-o','flag-o','flag-checkered','terminal','code2','mail-reply-all','reply-all2','star-half-empty','star-half-full','star-half-o','location-arrow','crop2','code-fork','chain-broken','unlink3','info','exclamation2','superscript','subscript','eraser2','puzzle-piece','microphone','microphone-slash','shield2','calendar-o','fire-extinguisher','rocket2','maxcdn','chevron-circle-left','chevron-circle-right','chevron-circle-up','chevron-circle-down','html5','css3','anchor2','unlock-alt','bullseye','ellipsis-h','ellipsis-v','rss-square','play-circle2','ticket2','minus-square2','minus-square-o','level-up','level-down','check-square2','pencil-square','external-link-square','share-square','compass3','caret-square-o-down','toggle-down','caret-square-o-up','toggle-up','caret-square-o-right','toggle-right','eur','euro','gbp','dollar','usd','inr','rupee','cny','jpy','rmb','yen','rouble','rub','ruble','krw','won','bitcoin','btc','file','file-text','sort-alpha-asc2','sort-alpha-desc2','sort-amount-asc2','sort-amount-desc2','sort-numeric-asc2','sort-numeric-desc2','thumbs-up4','thumbs-down4','youtube-square','youtube','xing','xing-square','youtube-play','dropbox','stack-overflow','instagram','flickr','adn','bitbucket','bitbucket-square','tumblr','tumblr-square','long-arrow-down','long-arrow-up','long-arrow-left','long-arrow-right','apple2','windows','android','linux','dribbble','skype','foursquare','trello','female','male','gittip','gratipay','sun-o','moon-o','archive3','bug2','vk','weibo','renren','pagelines','stack-exchange','arrow-circle-o-right','arrow-circle-o-left','caret-square-o-left','toggle-left','dot-circle-o','wheelchair2','vimeo-square','try','turkish-lira','plus-square-o','space-shuttle','slack','envelope-square','wordpress','openid','bank','institution','university','graduation-cap','mortar-board','yahoo','google','reddit','reddit-square','stumbleupon-circle','stumbleupon','delicious','digg','pied-piper-pp','pied-piper-alt','drupal','joomla','language','fax','building','child','paw2','spoon','cube2','cubes','behance','behance-square','steam','steam-square','recycle2','automobile','car3','cab','taxi2','tree2','spotify','deviantart','soundcloud','database2','file-pdf-o','file-word-o','file-excel-o','file-powerpoint-o','file-image-o','file-photo-o','file-picture-o','file-archive-o','file-zip-o','file-audio-o','file-sound-o','file-movie-o','file-video-o','file-code-o','vine','codepen','jsfiddle','life-bouy','life-buoy','life-ring','life-saver','support','circle-o-notch','ra','rebel','resistance','empire','ge','git-square','git','hacker-news','y-combinator-square','yc-square','tencent-weibo','qq','wechat','weixin','paper-plane2','send','paper-plane-o','send-o','history3','circle-thin','header','paragraph','sliders','share-alt','share-alt-square','bomb','futbol-o','soccer-ball-o','tty','binoculars3','plug','slideshare','twitch','yelp','newspaper-o','wifi2','calculator3',' paypal','google-wallet','cc-visa','cc-mastercard','cc-discover','cc-amex','cc-paypal','cc-stripe','bell-slash','bell-slash-o','trash4','copyright','at','eyedropper','paint-brush','birthday-cake','area-chart','pie-chart3','line-chart','lastfm','lastfm-square','toggle-off2','toggle-on2','bicycle3','bus3','ioxhost','angellist','cc','ils','shekel','sheqel','meanpath','buysellads','connectdevelop','dashcube','forumbee','leanpub','sellsy','shirtsinbulk','simplybuilt','skyatlas','cart-plus3','cart-arrow-down','diamond5','ship3','user-secret','motorcycle','street-view','heartbeat','venus','mars','mercury','intersex','transgender','transgender-alt','venus-double','mars-double','venus-mars','mars-stroke','mars-stroke-v','mars-stroke-h','neuter','genderless','facebook-official','pinterest-p','whatsapp','server2','user-plus2','user-times','bed2','hotel','viacoin','train2','subway','medium','y-combinator','yc','optin-monster','opencart','expeditedssl','battery','battery-4','battery-full2','battery-3','battery-three-quarters','battery-2','battery-half','battery-1','battery-quarter','battery-0','battery-empty2','mouse-pointer','i-cursor','object-group','object-ungroup','sticky-note','sticky-note-o','cc-jcb','cc-diners-club','clone','balance-scale','hourglass-o','hourglass-1','hourglass-start','hourglass-2','hourglass-half','hourglass-3','hourglass-end','hourglass2','hand-grab-o','hand-rock-o','hand-paper-o','hand-stop-o','hand-scissors-o','hand-lizard-o','hand-spock-o','hand-pointer-o','hand-peace-o','trademark','registered','creative-commons','gg','gg-circle','tripadvisor','odnoklassniki','odnoklassniki-square','get-pocket','wikipedia-w','safari','chrome','firefox','opera','internet-explorer','television','tv2','contao','500px','amazon','calendar-plus-o','calendar-minus-o','calendar-times-o','calendar-check-o','industry','map-pin','map-signs','map-o','map3','commenting','commenting-o','houzz','vimeo','black-tie','fonticons','reddit-alien','edge','credit-card-alt','codiepie','modx','fort-awesome','usb','product-hunt','mixcloud','scribd','pause-circle2','pause-circle-o','stop-circle2','stop-circle-o','shopping-bag','shopping-basket','hashtag','bluetooth','bluetooth-b','percent2','gitlab','wpbeginner','wpforms','envira','universal-access','wheelchair-alt','question-circle-o','blind','audio-description','volume-control-phone','braille','assistive-listening-systems','american-sign-language-interpreting','asl-interpreting','deaf','deafness','hard-of-hearing','glide','glide-g','sign-language','signing','low-vision','viadeo','viadeo-square','snapchat','snapchat-ghost','snapchat-square','pied-piper','first-order','yoast','themeisle','google-plus-circle','google-plus-official','fa','font-awesome','handshake-o','envelope-open2','envelope-open-o','linode','address-book','address-book-o','address-card','vcard','address-card-o','vcard-o','user-circle','user-circle-o','user-o','id-badge','drivers-license','id-card','drivers-license-o','id-card-o','quora','free-code-camp','telegram','thermometer2','thermometer-4','thermometer-full','thermometer-3','thermometer-three-quarters','thermometer-2','thermometer-half','thermometer-1','thermometer-quarter','thermometer-0','thermometer-empty','shower','bath','bathtub2','s15','podcast','window-maximize','window-minimize','window-restore','times-rectangle','window-close','times-rectangle-o','window-close-o','bandcamp','grav','etsy','imdb','ravelry','eercast','microchip','snowflake-o','superpowers','wpexplorer','meetup','search2','quotes-right','quotes-left','blogger','brand','social');

$vars['settings']['label-class'] = 'ep-no-float';
?>
<div class="ep-icon-field field">
	<?php if($label != ''){ ?>
	    <label for="field-<?php echo esc_attr($name); ?>"><?php echo esc_attr($label); ?></label>
	<?php } ?>
	<div class="ep-icon-container <?php echo esc_attr($class); ?>">

		<div class="ep-icons">
			<span class="close"></span>
	    <?php foreach($icons as $icon){ ?>
	        <span class="ep-icon icon-<?php echo esc_attr($icon) ?>" data-name="<?php echo esc_attr($icon) ?>"></span>
	    <?php } ?>
	    </div>
	    <span class="selected-icon icon<?php if($value != '' ) { echo '-' . esc_attr($value); } ?>" data-name="<?php echo esc_attr($value) ?>" title="<?php esc_attr_e('select an icon','vitrine'); ?>"></span>
	   	<span class="select-icon-text <?php if($value != '' ) { echo 'show'; } ?>"><?php esc_html_e('Select an icon','vitrine') ?></span>
	    <input class="icon-filed" type="hidden" name="<?php echo esc_attr($name); ?>" data-flags="<?php echo esc_attr($flags); ?>" value="<?php echo esc_attr($value); ?>" />
	</div>
</div>