<?php

/** @var $theme WP_Theme */
$theme = wp_get_theme();

$version = $theme->Version;
if(is_child_theme())
{
	$parent_theme = wp_get_theme(get_template());
	$version = $parent_theme->Version;
}
define('THEME_NAME',    $theme->Name);
define('THEME_NAME_SEO', strtolower(str_replace(" ", "_", THEME_NAME)));
define('THEME_AUTHOR', $theme->Author);
define('THEME_VERSION',$version);
define('OPTIONS_KEY', "theme_". THEME_NAME_SEO ."_options");

/**************************************************
Theme Defaults
**************************************************/

define('DEFAULT_FOOTER_WIDGETS', 3);
