<?php
/**
 * Uninstall handler for DoRegister plugin
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

// Drop custom tables (if they exist)
$tables = array(
    $wpdb->prefix . 'doregister_temp',
    $wpdb->prefix . 'doregister_tokens',
    $wpdb->prefix . 'doregister_event_log',
);

foreach ( $tables as $table ) {
    $wpdb->query( "DROP TABLE IF EXISTS {$table}" );
}

// Remove plugin options
delete_option( 'doregister_version' );
delete_option( 'doregister_db_version' );
