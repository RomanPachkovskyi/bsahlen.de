<?php
/**
 * WordPress Config: LOCAL (Docker)
 * This file is tracked in Git and used for local development.
 */

// ** Database settings - Docker **
define('DB_NAME',     'bsahlen');
define('DB_USER',     'wp');
define('DB_PASSWORD', 'wp');
define('DB_HOST',     'db');  // Docker service name
define('DB_CHARSET',  'utf8');
define('DB_COLLATE',  '');

/**
 * Authentication unique keys and salts.
 * Using the same keys as production for local consistency.
 * (In real production, these should be different!)
 */
define('AUTH_KEY',         'U0n:&PV3Q5j/o(/b!5;-5/[iyrc6!UHNU6fygB_S8i:5&2MmS+Fm_P1l7I11c)gF');
define('SECURE_AUTH_KEY',  'u:1*]!Rjv-a9Y9O]6p-o8CtvT0CGhd8rFy8s@jIdIO](5[_1*V+NmoZS6RFDyVRS');
define('LOGGED_IN_KEY',    'B~G4RW;x6[e+loI8XS0yW@(*(:iB~687m+R0(3__57PA#R#81kA23]w*XO_05/8R');
define('NONCE_KEY',        '&;1pFD5x374KdB_J3s9Te*2/(E8K]&z9_Zjb9(@OW(#/p%&06i54ISR!d*6u44e7');
define('AUTH_SALT',        'NQD9W/z34(sq++sX1+L5w6%-4u7BpV2S95gi-d/01*dG!@h1h);p0!744SD78JJz');
define('SECURE_AUTH_SALT', 'MJRG[!tqE(y@Jo21Z~29~yL*20g8G-]rQB!(BB_bv;1:Ot5j5G0q]eb&StJ)2k88');
define('LOGGED_IN_SALT',   'eZ&%f[Aq3uN0dM3#!x4dA3ey2@%7eG;4XE90&x/2bN%-uO&u56uYi@k3lx~0LW9y');
define('NONCE_SALT',       'Z0w7~5y3(#C8IS7SW2Y|O-Qaa)pa(Nvp57zn!S-7DSIx2qS25hJ0_z1o8)41]913');

/**
 * Database table prefix
 */
$table_prefix = 'XutfWi7d_';

/**
 * WordPress configuration
 */
define('WP_ALLOW_MULTISITE', true);
define('WP_AUTO_UPDATE_CORE', false);
define('WP_MEMORY_LIMIT', '1024M');
define('WP_MAX_MEMORY_LIMIT', '1024M');

/**
 * Debugging (enabled for local development)
 */
define('WP_DEBUG',         true);
define('WP_DEBUG_LOG',     true);
define('WP_DEBUG_DISPLAY', false);
define('SAVEQUERIES',      false);

// PHP error logging
@ini_set('log_errors', 'On');
@ini_set('error_log', dirname(__FILE__) . '/php-error.log');

/**
 * Local URL
 * NOTE: After migration to /wp/ subdirectory, these may need updating
 */
define('WP_HOME',    'http://localhost:8080');
define('WP_SITEURL', 'http://localhost:8080');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
