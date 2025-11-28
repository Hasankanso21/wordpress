<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1:3307' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'k3x3qt#.$-R&g&Vm?{%tk2pQ*7?XMJoS5GUV[IqC=up*4 ,+,=P#MArmG@hL]bW`' );
define( 'SECURE_AUTH_KEY',  'UGF,X~e|4;Q^T{p$so|d0$!tX4?X]#t&[hm-k,|:@aGR^?1)iO:2mzrXB05w2J05' );
define( 'LOGGED_IN_KEY',    'xH9NWq]3oVJY?8RS44W4(1.}N]5C-!|i{_nJVLEZ_q%T;dR<Bk$iLuqKx{tJDYL8' );
define( 'NONCE_KEY',        ']z@X>]@{Mw2l*;t^N)?CKZLjrl/Xtpr{uh 4GE>!M&1=KJFSHAL?7vbeuGy#_iD{' );
define( 'AUTH_SALT',        '4g~D?GDE~i-OBr<A`0P-b6~uWh4ixD|F~n_gLF?,kz=]j0gw##n`Q$L>^2[/8Wfz' );
define( 'SECURE_AUTH_SALT', '_Gx=${|t$:S@7CZSi:Xt*Be U&t~l)<^?JT!YR&)xe[l$awTKY+r^^Ld`QX;qh~z' );
define( 'LOGGED_IN_SALT',   '{$3RDTEftDO?|^|=O& Os4G 4=VErJ`0uV7NYN1Dc|;wo>z[bjKzl}y#Q90VQECX' );
define( 'NONCE_SALT',       'G`7=G*mn=a7[5zWu32cr!J*.@WXsQ$zTf&!v(`2%vFs{y4v9?nV<n3+:Lg>/#x%0' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
