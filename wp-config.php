<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'agroinvestpub' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'tDuH,sj$%_JI1;!Ev|Bh (dvrVQHwB~@<z0=f@E]aFWk]b.gNlhbcZQ5C1C7v<SN' );
define( 'SECURE_AUTH_KEY',  'AF)@ot<M#i#N ?|.w:ROV!t_$%1BCuh1o5<l`i]:esH,tbFKe|RCKa`pp|HY3&jQ' );
define( 'LOGGED_IN_KEY',    'G>bfm<XAC9c.izbZ/Iu&DYoG,2D?YzK)O6t*TDUoO/*`/FcN!P 7YF^HQ<ykPOZ{' );
define( 'NONCE_KEY',        '%wSrv%9>sUNp,KpnwI7P9=p->Rt~S2G2fhMi~XBF)E&`Xiv;|**=lLxKrN%<ADGy' );
define( 'AUTH_SALT',        '@NrtYN~gkj9Bit4.0];|}WlC!-J+4`~Xht@2$U:OW+!m6G>O2Mt:($.r}L3Os$iE' );
define( 'SECURE_AUTH_SALT', 'cQp]Ej z8-_A[ ocR/9we(nf[/rp4s<{oJWjg,j<Wc-PD`G((!:rlJ/0biU{9slp' );
define( 'LOGGED_IN_SALT',   'X;!C+j.NBNRn-q# ^cRh^2B.q5Djhs:sk^%p5HovL)JGD,qj^6_|WNqPvYA?L#FJ' );
define( 'NONCE_SALT',       '_7kutfO*>zXP@On%czcatH!=LLMPLqja#De{-R~R_y$I+|qSCpBl*zUg~~gA8RKJ' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
