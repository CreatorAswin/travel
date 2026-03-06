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
define( 'DB_NAME', 'travel' );

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
define( 'AUTH_KEY',         'S/lk-tAu?iO>_ynt4{|xJnvcf $@y9}#YlhZxVC[BjrO}gqM|II-`7Ie+`6{wx?}' );
define( 'SECURE_AUTH_KEY',  'a6r|fnYPr h1vs3/BMeVzB-z^GXhs.wedk[Y)1}2LZzd!!l7.;28u3r@3Ed*t6rJ' );
define( 'LOGGED_IN_KEY',    'DzK_ kPbHL`O.1}iPU@+Q`IAATq|%uCx{bM1@L^GGs X)pE@V5?4wpX1el2*Bu`~' );
define( 'NONCE_KEY',        '3(y,im3}fFxSvXD*yleo7YMQeHUl;9ZGCR5gFbYh>MKe<p)f,KGE.=0zDKVOJTXR' );
define( 'AUTH_SALT',        'E7<?`ml_Ss]x_Due?!R%%al!wqXKY%=IKQ&|3699$~HX&sdhtML98=a;(r:m hwy' );
define( 'SECURE_AUTH_SALT', 'sSyXXJ *A-S7.r]<ND>kFBh4K%W2_aW4TJUSzge` n$D?q[`8ryonyjBK,U+[m&9' );
define( 'LOGGED_IN_SALT',   'Fdw/Ub/gGjfv<_]-o/A#feOs_Pfd[,oWsG6-l;7i} H?[rd(Sh)JeH<+(A@mg#S@' );
define( 'NONCE_SALT',       'E3a/C^6,*Q9Y_mQoJ_DN]K>zs>%D3&(.I{G$@%]l-C{L~6v!aQ1XO`mh(![O-S:J' );

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
