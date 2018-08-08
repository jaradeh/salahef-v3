<?php

define('EPICO_THEME_SLUG', 'Ep');
define('EPICOMEDIA_THEME_SLUG', 'vitrine');
define('VITRINE_THEME_SLUG', 'vitrine');

/**************************************************
	FOLDERS
**************************************************/

define('EPICO_THEME_DIR',         get_parent_theme_file_path());
define('EPICO_THEME_LIB',         EPICO_THEME_DIR . '/lib');
define('EPICO_THEME_PLUGINS',     EPICO_THEME_DIR . '/plugins');
define('EPICO_THEME_CSS',         EPICO_THEME_DIR . '/assets/css');

/**************************************************
    FOLDER URI
**************************************************/

define('EPICO_THEME_URI',              get_parent_theme_file_uri());
define('EPICO_THEME_LIB_URI',         EPICO_THEME_URI . '/lib');
define('EPICO_THEME_ASSETS_URI',      EPICO_THEME_URI     . '/assets');
define('EPICO_THEME_IMAGES_URI',      EPICO_THEME_ASSETS_URI . '/img');

/**************************************************
    Text Domain
**************************************************/

load_theme_textdomain( 'vitrine' , EPICO_THEME_DIR . '/languages' );

/**************************************************
    Content Width
**************************************************/

if ( !isset( $content_width ) ) $content_width = 1170;

/**************************************************
    LIBRARIES
**************************************************/

require_once(EPICO_THEME_LIB . '/framework.php');


