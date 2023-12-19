<?php
/*
Plugin Name: FDK DevOps WP Cache Scanner
Description: Scans the website for cache-related artifacts.
Version: 1.0
Author: Faisal Dad Khan
*/

// Hook to WordPress admin menu
add_action('admin_menu', 'cache_scanner_menu');

function cache_scanner_menu() {
    // Add a menu item under "Tools" for cache scanning
    add_submenu_page(
        'tools.php',
        'Cache Scanner',
        'Cache Scanner',
        'manage_options',
        'cache-scanner',
        'cache_scanner_page'
    );
}

function cache_scanner_page() {
    ?>
    <div class="wrap">
        <h1>Cache Scanner</h1>
        <p>Click the button below to scan the website for cache-related artifacts.</p>
        <form method="post" action="">
            <?php wp_nonce_field('cache_scanner_nonce', 'cache_scanner_nonce_field'); ?>
            <input type="submit" class="button button-primary" value="Scan for Cache" name="scan_cache">
        </form>
        <?php
        if (isset($_POST['scan_cache']) && check_admin_referer('cache_scanner_nonce', 'cache_scanner_nonce_field')) {
            // Perform the cache scan
            $cache_results = perform_cache_scan();
            ?>
            <h2>Scan Results</h2>
            <pre><?php print_r($cache_results); ?></pre>
            <?php
        }
        ?>
    </div>
    <?php
}

function perform_cache_scan() {
    // Define common cache-related directories and files
    $cache_artifacts = array(
        'wp-content/cache/',
        'wp-content/uploads/',
        'wp-content/advanced-cache.php',
        'wp-content/object-cache.php',
    );

    // Initialize scan results array
    $scan_results = array();

    // Check if each cache artifact exists
    foreach ($cache_artifacts as $artifact) {
        $scan_results[$artifact] = file_exists(ABSPATH . $artifact);
    }

    return $scan_results;
}
