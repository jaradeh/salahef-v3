<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'salahef_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '$rV#_!ThJ{7~3D9/^B4sqOo0E4 &B)9BT@=N(2 -((H#(+hkfw@I(AoERO8<LaYb');
define('SECURE_AUTH_KEY',  'uY32]U`yrkgl] !d$~mS>$JtmjpCGF#]PtXS}a09OY>wBO!Ze@8~-ce*F0 R^#1]');
define('LOGGED_IN_KEY',    '8_f[2=T61~}v}2wW./(+crA+f!`ZhrM1D_BW+|9]MNK&!)<*:`xK(ol~^/m%[AEX');
define('NONCE_KEY',        'Q&^(H7Z(WL]QOl3)yb,pJbEO?zS*98kos]C&(4?6jO?n^u05)cyN<WbCaF4,_d[D');
define('AUTH_SALT',        'GO~|;PEXbMCkF2T==V+Nic11}>wr0?$0sSswhU^bk/(tX_k)c Tq3enUtqfAHZ5M');
define('SECURE_AUTH_SALT', 'p<IO)jl+Gm)i45`S)o3M yEu4HcthsV07xbxLk |[#xO_C)a$o87W`gLaU:VH_bB');
define('LOGGED_IN_SALT',   'YVz)G1zN6/sHx@Pa`/]9m)?78>|lhn`?%=?0N<;kAh(WWiu6U}(#:rE{v>zxz#e(');
define('NONCE_SALT',       'w*1mWx?[7@10c`m{m&k#T{ogDm/)Xilq$pkH}(jV!(<Gb4X7WIE^Tq*O`2DAPK]~');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'sf_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
