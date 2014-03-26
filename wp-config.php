<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'daryltulay_vrc_w');

/** MySQL database username */
define('DB_USER', 'daryltulay_vrc_w');

/** MySQL database password */
define('DB_PASSWORD', 'ljArioIP');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY', 'lmUuw1BkfgqZGL3gO8lXBZUGdCkFS0k1HMfpG87EZtKcXqJw0smGj9BAaB7KbuzO');
define('SECURE_AUTH_KEY', '2fLiNFFMFMAuweLjTIzmCSwfUps6SBFd3aWJaMGKJGjNdt3cr5mqaHUkgvIqaEXq');
define('LOGGED_IN_KEY', '9Ppzb5j0YDQYbBPDf2zxNqSMJRzSTPxvYc1BgCq0M64K5QWIxNDrznmjAyuE9rzB');
define('NONCE_KEY', 'vydGLGFJtkGP47SyPiR00RcEnkqSaGnJCIgY569Wxfr7T8NhkI7Dk0yZXMBwJNGR');
define('AUTH_SALT', 'cSeNC5kZn0FORzRCQYouiydkuDiOwCnGm5aXpwKuiAlm92uUmmGgvvZGUFOFHghy');
define('SECURE_AUTH_SALT', 'FTJVieZQtHIfqfOcIhXYi39b1aJQyTNACHPdJ4cfuuRTsxolvF5kMkjuAt2GqHly');
define('LOGGED_IN_SALT', '0CzyU2A9BEUcwlZHttWy2DSIaqDAKD8Qiu3eSUzbTyWJT4MG1Rz2oXzQcelzI2jO');
define('NONCE_SALT', 'CqznUdbDCqOeFdFHbR8r4qwabRNoK0vURe4EA3xNRNtNihG6DYyS4fH8sDKj6NKW');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
