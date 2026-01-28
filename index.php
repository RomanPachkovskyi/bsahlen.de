<?php
/**
 * Router: Maintenance ↔ Live WordPress
 *
 * MODE = 'maintenance' → Public sees /maintenance, admin sees WP
 * MODE = 'live'        → Public sees WP
 *
 * IMPORTANT: This file IS under Git control.
 * To switch modes: change MODE value, commit, push → Plesk deploys automatically.
 */

define('MODE', 'live'); // 'maintenance' | 'live'

$docRoot    = __DIR__;
$wpPath     = $docRoot . '/wp';
$wpIndex    = $wpPath . '/index.php';
$maintIndex = $docRoot . '/maintenance/index.html';

/**
 * Check if user has WordPress admin cookie
 */
function is_wp_admin_logged_in(): bool {
    foreach ($_COOKIE as $name => $value) {
        if (strpos($name, 'wordpress_logged_in_') === 0) {
            return true;
        }
    }
    return false;
}

/**
 * Serve WordPress
 */
function serve_wordpress(string $wpIndex): void {
    if (!is_file($wpIndex)) {
        http_response_code(500);
        die('WordPress not installed. Missing: ' . $wpIndex);
    }

    // Change to WP directory for correct paths
    chdir(dirname($wpIndex));
    require $wpIndex;
    exit;
}

/**
 * Serve maintenance page
 */
function serve_maintenance(string $maintIndex): void {
    if (!is_file($maintIndex)) {
        http_response_code(503);
        header('Retry-After: 3600');
        die('Site under maintenance.');
    }

    // HTTP 200 for SEO (Landing mode, not "site down")
    http_response_code(200);
    header('Content-Type: text/html; charset=utf-8');
    readfile($maintIndex);
    exit;
}

// =============================================================================
// ROUTING LOGIC
// =============================================================================

// Request to /wp/* always goes to WordPress (for admin access)
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
if (preg_match('#^/wp(/|$)#', $requestUri)) {
    serve_wordpress($wpIndex);
}

// MODE: live → everyone sees WordPress
if (MODE === 'live') {
    serve_wordpress($wpIndex);
}

// MODE: maintenance
// Admin (logged in) → WordPress
if (is_wp_admin_logged_in()) {
    serve_wordpress($wpIndex);
}

// Public → maintenance page
serve_maintenance($maintIndex);
