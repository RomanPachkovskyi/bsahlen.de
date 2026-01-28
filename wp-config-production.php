<?php
/**
 * WordPress Config: PRODUCTION TEMPLATE
 *
 * INSTRUCTIONS:
 * 1. Copy this file to hosting: /httpdocs/wp/wp-config.php
 * 2. Update DB credentials from Plesk
 * 3. Verify salts (or generate new ones for extra security)
 * 4. Update WP_HOME and WP_SITEURL if needed
 */

// ** Database settings - Get from Plesk **
define('DB_NAME',     'YOUR_DB_NAME');        // From Plesk → Databases
define('DB_USER',     'YOUR_DB_USER');        // From Plesk → Databases
define('DB_PASSWORD', 'YOUR_DB_PASSWORD');    // From Plesk → Databases
define('DB_HOST',     'localhost');           // Usually localhost on Plesk
define('DB_CHARSET',  'utf8mb4');             // utf8mb4 for full Unicode support
define('DB_COLLATE',  '');

/**
 * Authentication unique keys and salts.
 *
 * SECURITY: For production, consider generating NEW salts:
 * https://api.wordpress.org/secret-key/1.1/salt/
 *
 * Current salts are copied from local for convenience.
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
 * Debugging (DISABLED for production)
 */
define('WP_DEBUG',         false);
define('WP_DEBUG_LOG',     false);
define('WP_DEBUG_DISPLAY', false);
define('SAVEQUERIES',      false);

/**
 * Production URL
 *
 * After SOP v2.0 migration, WordPress will be in /wp/ subdirectory.
 * Router in root will handle all requests.
 *
 * Option A: WordPress in subdirectory (recommended for SOP v2.0)
 * define('WP_HOME',    'https://bsahlen.de');
 * define('WP_SITEURL', 'https://bsahlen.de/wp');
 *
 * Option B: WordPress in root (if not using subdirectory)
 * define('WP_HOME',    'https://bsahlen.de');
 * define('WP_SITEURL', 'https://bsahlen.de');
 *
 * CURRENT: Choose based on final structure
 */
define('WP_HOME',    'https://bsahlen.de');
define('WP_SITEURL', 'https://bsahlen.de/wp');  // Update after confirming structure

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
