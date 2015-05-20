<?php

/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'partyDBrz4ou');

/** MySQL database username */
define('DB_USER', 'partyDBrz4ou');

/** MySQL database password */
define('DB_PASSWORD', 'l49wRFOH6b');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'iET+<2bmyALu*{EQb,Xju3EQy,Xfq3Emy.+#2al;9Lt~]Sal:9ht-DOZt*]Te#2');
define('SECURE_AUTH_KEY',  'sJY!}Ugr4FNEQb.{6fq2EMu*{Tfq2ALu+<7IUy,Ufn7Iny,MUf.7fnyPWi]5Dl');
define('LOGGED_IN_KEY',    '@Rco}8Gov![fnv7EQu^<QXfrAIny^IQbj{3AUcn}7Fnv^>QYg>7fny^IQc^{3<2');
define('NONCE_KEY',        'rIUoz!JUg,0Bgr@BNU@>0Yjr3FM.Aiu*IT+{6eq2EP+<Paq,Xjq3EMu^<Qbj{6E');
define('AUTH_SALT',        '6q2DLt+#PWipGOZ~[5Zks4COs@[NZg[0CKsz|9HOw_]SZl[5Dls~GOVh[1Chs-08J');
define('SECURE_AUTH_SALT', 'pCS-|:Vhs4Gow!5HS-#1Wht5Hp-#OWh[5Dlw_Yg}8Foz|NUg>4Bkv!JQc,07Rd|:8');
define('LOGGED_IN_SALT',   '0k0BNv^JYk}7gr$Tfq2Emy<Pb.;Aiu*LX*]6Qb,Aju^IT$<6bq2EPy._;alx9KW');
define('NONCE_SALT',       '^BnyPe<2Em+DPa.2Ait6HT+]EQb.{6fmy6IPy.Tem;AIq+.-#:Whp1CKs-|OVh[1');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);define('FS_METHOD', 'direct');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
