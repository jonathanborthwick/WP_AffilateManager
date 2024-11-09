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
define( 'DB_NAME', 'spinnis0_wp276' );

/** Database username */
define( 'DB_USER', 'spinnis0_wp276' );

/** Database password */
define( 'DB_PASSWORD', 'h0C]6dS!p3' );

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
define( 'AUTH_KEY',         'rnbyqds8smdzh9xir8jpv1wklgf5ylprae1ylmbqbngwlssaqwx4lhhecp89tbdn' );
define( 'SECURE_AUTH_KEY',  'garxfburbkd61zkdujdthoq9jumabgcb1hijpakqgotva4gbfot7opri3zbowjvo' );
define( 'LOGGED_IN_KEY',    '48siityncoi7wouginu8qkseqttw7og9fgivaidvjeclrtjsu2d47w3sqb2dlslu' );
define( 'NONCE_KEY',        'x3ehtb0jily5fq7eonqq7ykqvtwujzebqvexrzg8vylmgwkizkl2l9vw4ohixgaa' );
define( 'AUTH_SALT',        'wuulav7ev0uiozhwpnmlkclci3eic8mex3j3phgvnoxi8th3t0iyq2ruzltsehrk' );
define( 'SECURE_AUTH_SALT', 'lugagras3oljdbpzqo3znqirb2az5jwjbsj3ip0rqqo0ojupdbckoyqdlxdwrlij' );
define( 'LOGGED_IN_SALT',   '3xj0dpvstl1maswir7frlsub7ftkxalge23lqjdfk9u13konrtb23aznuktifzdy' );
define( 'NONCE_SALT',       'vupxeq8ozm6jgdrj3vyk4qo7mgtmbxl5fpjr6xbu0rcen4fypl2p3yr4rzfdmgjk' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpzw_';

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
define( 'WP_DEBUG', true );
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
