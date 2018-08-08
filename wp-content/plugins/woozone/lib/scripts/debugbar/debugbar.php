<?php
/*
* Define class WooZoneDebugBar
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
	  
if (class_exists('WooZoneDebugBar') != true) { class WooZoneDebugBar {

	const VERSION = '1.0';
	public $the_plugin = null;

	private $module_folder = '';
	private $module = '';

	static protected $_instance;

	private $plugin_icon_url = '';

	public $did_action_footer = false;

	public $bar_row = array(); // our debugbar rows
	public $bar_menu = array(); // our debugbar box menu
	public $bar_menua = array(); // adminbar menu


	/*
	 * Required __construct() function that initalizes the AA-Team Framework
	 */
	public function __construct( $parent ) {
		//global $WooZone;
		//$this->the_plugin = $WooZone;
		$this->the_plugin = $parent;

		$this->plugin_icon_url = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'icon_16.png';

		// can we have a debug bar?
		if ( ! $this->can_bar_exist() ) {
			return true;
		}
		//return true; //DEBUG

		add_action( 'admin_bar_menu', array( $this, 'action_admin_bar_menu' ), 999 );

		add_filter( 'user_has_cap', array( $this, 'filter_user_has_cap' ), 10, 3 );
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'wp_footer', array( $this, 'do_action_footer' ) );
		add_action( 'admin_footer', array( $this, 'do_action_footer' ) );
		add_action( 'login_footer', array( $this, 'do_action_footer' ) );
		add_action( 'embed_footer', array( $this, 'do_action_footer' ) );

		add_action( 'shutdown', array( $this, 'show_bar' ), 99 );
	}

	/**
	 * Singleton pattern
	 *
	 * @return WooZoneBitly Singleton instance
	 */
	static public function getInstance( $parent ) {
		if (!self::$_instance) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	}



	//================================================
	//== WP HOOKS Callbacks

	public function do_action_footer() {
		$this->did_action_footer = true;
	}

	public function action_admin_bar_menu( $wp_admin_bar ) {

		if ( ! $this->user_can_view() ) {
			return false;
		}

		// at least one box row beside dashboard exists?
		if ( empty($this->bar_menua) ) {
			return false;
		}

		$title = __( 'WZone Debug Bar', 'woozone' );

		$wp_admin_bar->add_menu( array(
			'id'    => 'woozone-debugbar',
			'title' => esc_html( $title ),
			'href'  => '#woozone-debugbar-dashboard',
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'woozone-debugbar',
			'id'     => 'woozone-debugbar-placeholder',
			'title'  => esc_html( $title ),
			'href'   => '#woozone-debugbar-dashboard',
		) );
	}

	public function init() {

		if ( ! $this->user_can_view() ) {
			return false;
		}

		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', 1 );
		}

		// negative priority (alternative to use 'plugins_loaded' hook) : this will be run first
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), -1000 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ), -1000 );
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_assets' ), -1000 );
		add_action( 'enqueue_embed_scripts', array( $this, 'enqueue_assets' ), -1000 );
		add_action( 'send_headers', 'nocache_headers' );
	}

	public function enqueue_assets() {
		global $wp_locale, $wp_version;

		wp_enqueue_style(
			'woozone-debugbar',
			WooZone_asset_path( 'js', $this->the_plugin->cfg['paths']['scripts_dir_url'] . '/debugbar/assets/debugbar.css', true ),
			array( 'dashicons' ),
			WooZone_asset_version( 'css' )
		);

		wp_enqueue_script(
			'woozone-debugbar-screenfull',
			WooZone_asset_path( 'js', $this->the_plugin->cfg['paths']['freamwork_dir_url'] . 'js/screenfull/screenfull.min.js', true ),
			array(),
			WooZone_asset_version( 'js' ),
			false
		);
		wp_enqueue_script(
			'woozone-debugbar',
			WooZone_asset_path( 'js', $this->the_plugin->cfg['paths']['scripts_dir_url'] . '/debugbar/assets/debugbar.js', true ),
			array( 'jquery-core', 'woozone-debugbar-screenfull' ),
			WooZone_asset_version( 'js' ),
			false
		);

		wp_localize_script(
			'woozone-debugbar',
			'woozone_debugbar_vars',
			array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	public function show_bar() {

		// do we have permissions to show box?
		if ( ! $this->can_show_bar() ) {
			return false;
		}

		//global $wp_admin_bar;
		//$this->action_admin_bar_menu( $wp_admin_bar );

		// at least one box row beside dashboard exists?
		if ( empty($this->bar_row) ) {
			return false;
		}

		$this->build_bar_start();

		// dashboard
		$html = array();
		$html[] = '<div id="woozone-debugbar-dashboard" class="woozone-debugbar-row">';
		$html[] = 	__('This is WZone Debug Bar - Dashboard', 'woozone');
		$html[] = '</div>';
		$html = implode( PHP_EOL, $html );
		echo $html;

		// bar rows
		foreach ( $this->bar_row as $row_id => $row_html ) {
			echo $row_html;
		}

		$this->build_bar_end();
	}



	//================================================
	//== UTILS

	// can we have a debug bar?
	public function can_bar_exist() {

		if ( ! $this->the_plugin->debug_bar_activate ) {
			return false;
		}

		if ( version_compare( PHP_VERSION, '5.3.6', '<=' ) ) {
			return false;
		}

		if ( defined('WOOZONE_DEBUGBAR_DISABLED') && WOOZONE_DEBUGBAR_DISABLED ) {
			return false;
		}

		// don't load it when CLI
		if ( 'cli' === php_sapi_name() ) {
			return false;
		}

		// dont' load it when cron events
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return false;
		}

		return true;
	}

	// filter user's capabilities
	// grant the 'view_woozone_debug_bar' capability to the user if he can manage options
	public function filter_user_has_cap( $user_caps, $required_caps, $args ) {

		if ( 'view_woozone_debug_bar' !== $args[0] ) {
			return $user_caps;
		}
		if ( ! is_multisite() && user_can( $args[1], 'manage_options' ) ) {
			$user_caps['view_woozone_debug_bar'] = true;
		}
		return $user_caps;
	}

	public function user_can_view() {

		if ( ! did_action( 'plugins_loaded' ) ) {
			return false;
		}
		if ( current_user_can( 'view_woozone_debug_bar' ) ) {
			return true;
		}
		return false;
	}

	public function can_show_bar() {

		// do not show if fatal error
		$e = error_get_last();
		if ( ! empty( $e ) && ( $e['type'] & ( E_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR ) ) ) {
			return false;
		}

		if ( ! $this->user_can_view() ) {
			return false;
		}

		if ( ! $this->did_action_footer ) {
			return false;
		}

		// async request and not customizer preview
		if ( $this->the_plugin->u->is_async() && ( ! function_exists( 'is_customize_preview' ) || ! is_customize_preview() ) ) {
			return false;
		}

		// minimum required actions must be fired
		if ( is_admin() ) {
			if ( ! did_action( 'admin_init' ) ) {
				return false;
			}
		}
		else {
			if ( ! ( did_action( 'wp' ) || did_action( 'login_init' ) ) ) {
				return false;
			}
		}

		return true;
	}



	//================================================
	//== ADDERS

	public function add2bar_menua( $id, $title='', $pms=array() ) {
		return $this->add2bar_generic( $id, $title, array_replace_recursive( $pms, array(
			'what' => 'bar_menua',
			'href' => '',
		)) );
	}

	public function add2bar_menu( $id, $title='', $pms=array() ) {
		return $this->add2bar_generic( $id, $title, array_replace_recursive( $pms, array(
			'what' => 'bar_menu',
			'href' => '',
		)) );
	}

	public function add2bar_row( $id, $html='', $pms=array() ) {
		return $this->add2bar_generic( $id, $html, array_replace_recursive( $pms, array(
			'what' => 'bar_row',
		)) );
	}

	public function add2bar_generic( $id, $html='', $pms=array() ) {
		$pms = array_replace_recursive( array(
			'what' 	=> '',
			'href' 	=> '', // for menu & menu_adminbar
		), $pms );
		extract( $pms );

		if ( empty($html) ) {
			return false;
		}

		if ( empty($href) ) {
			$href = "#$id";
			//$href = $html;
			//$href = strtolower( $href );
			//$href = sanitize_title( $href );
			//$href = "#$href";
		}

		switch ( $what ) {

			case 'bar_row':

				$html_ = array();
				$html_[] = '<div id="' . $id . '" class="woozone-debugbar-row">';
				$html_[] = $html;
				$html_[] = '</div>';
				$html_ = implode( PHP_EOL, $html_ );

				$this->$what["$id"] = $html_;
				break;

			case 'bar_menu':
			case 'bar_menua':

				$this->$what["$id"] = array(
					'id' 	=> $id,
					'title' => $html,
					'href' 	=> $href,
				);
				break;
		}
		return true;
	}



	//================================================
	//== BUILD BAR

	public function build_bar_start() {

		$class = array();
		//$class[] = 'woozone-debugbar-no-js';

		if ( did_action( 'wp_head' ) ) {
			$class[] = sprintf( 'woozone-debugbar-theme-%s', get_template() );
			$class[] = sprintf( 'woozone-debugbar-theme-%s', get_stylesheet() );
		}

		if ( ! is_admin_bar_showing() ) {
			$class[] = 'woozone-debugbar-force-show';
		}
		//$class[] = 'woozone-debugbar-force-show'; //DEBUG

		//:: box settings
		$bar_settings = array(
			'menu'        => $this->get_adminbar_menu(),
		);
		$bar_settings = json_encode( $bar_settings );
		$bar_settings = htmlentities( $bar_settings );
		//$bar_settings = htmlspecialchars( $bar_settings, ENT_QUOTES, 'UTF-8' );

		echo '<div class="woozone-debugbar-settings" style="display: none;">' . $bar_settings . '</div>';

		//:: start main box
		echo '<div id="woozone-debugbar" class="' . implode( ' ', array_map( 'esc_attr', $class ) ) . '">';

		echo 	'<div id="woozone-debugbar-title">';

		echo 		'<img src="' . $this->plugin_icon_url . '" alt="">';
		echo 		'<h1>' . esc_html__( 'WZone Debug Bar', 'woozone' ) . '</h1>';

		echo 		'<div class="woozone-debugbar-title-heading">';
		echo 			'<select>';

		printf(
							'<option value="%1$s">%2$s</option>',
							'#woozone-debugbar-dashboard',
							esc_html__( 'Dashboard', 'woozone' )
		);

		foreach ( $this->bar_menu as $menu ) {
			printf(
							'<option value="%1$s">%2$s</option>',
							esc_attr( $menu['href'] ),
							esc_html( $menu['title'] )
			);
		}

		echo 			'</select>';
		echo 		'</div>'; // end title-heading

		echo 		'<div class="woozone-debugbar-title-buttons">';
		echo 			'<button class="woozone-debugbar-button-fullscreen"><span class="dashicons dashicons-editor-contract" aria-hidden="true" title="' . esc_html__( 'Full Screen', 'woozone' ) . '"></span></button>';
		echo 			'<button class="woozone-debugbar-button-pin"><span class="dashicons dashicons-paperclip" aria-hidden="true" title="' . esc_html__( 'Pin Box Open', 'woozone' ) . '"></span></button>';
		echo 			'<button class="woozone-debugbar-button-close"><span class="dashicons dashicons-no" aria-hidden="true" title="' . esc_html__( 'Close Box', 'woozone' ) . '"></span></button>';
		echo 		'</div>'; // end title-buttons

		echo 	'</div>'; // end title

		echo 	'<div id="woozone-debugbar-wrapper">';
		echo 		'<div id="woozone-debugbar-menu">';
		echo 			'<ul>';

		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			'#woozone-debugbar-dashboard',
			esc_html__( 'Dashboard', 'woozone' )
		);

		foreach ( $this->bar_menu as $menu ) {
			printf(
				'<li><a href="%1$s">%2$s</a></li>',
				esc_attr( $menu['href'] ),
				esc_html( $menu['title'] )
			);
		}

		echo 			'</ul>';
		echo 		'</div>'; // end menu

		echo 		'<div id="woozone-debugbar-rows">';
	}

	public function build_bar_end() {

		echo 		'</div>'; // end rows
		echo 	'</div>'; // end wrapper
		echo '</div>'; // end main box
	}

	public function get_adminbar_menu() {

		$class = '';

		$title = esc_html__( 'WZone Debug Bar', 'woozone' );

		$admin_bar_menu = array(
			'top' => array(
				'title'     => sprintf(
					'<span class="ab-icon"><img src="%s" alt=""></span><span class="ab-label">%s</span>',
					$this->plugin_icon_url,
					$title
				),
				'classname' => $class,
			),
			'sub' => array(),
		);

		foreach ( $this->bar_menua as $menu ) {
			$admin_bar_menu['sub'][ $menu['id'] ] = $menu;
		}
		return $admin_bar_menu;
	}

} }
 
// Initialize the WooZoneDebugBar class
//$WooZoneDebugBar = WooZoneDebugBar::getInstance();