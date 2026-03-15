<?php
/**
 * IDE Compatibility Stubs
 * This file provides dummy definitions for classes and functions that are
 * flagged as "unknown" by the IDE's static analysis.
 */

// Jetpack Stubs
if ( ! class_exists( 'Jetpack' ) ) {
    class Jetpack {
        public static function is_active() { return false; }
        public static function load_xml_rpc_client() {}
    }
}
if ( ! class_exists( 'Jetpack_IXR_ClientMulticall' ) ) {
    class Jetpack_IXR_ClientMulticall {
        public function addCall() {}
        public function query() {}
        public function isError() { return false; }
        public function getResponse() { return array(); }
    }
}

// WP_CLI Stubs
if ( ! class_exists( 'WP_CLI' ) ) {
    class WP_CLI {
        public static function error( $msg ) {}
        public static function success( $msg ) {}
        public static function log( $msg ) {}
        public static function line( $msg ) {}
        public static function warning( $msg ) {}
        public static function add_command( $name, $class ) {}
    }
}
if ( ! class_exists( 'WP_CLI_Command' ) ) {
    class WP_CLI_Command {}
}

// AMP Stubs
if ( ! function_exists( 'amp_is_request' ) ) {
    function amp_is_request() { return false; }
}

// LiteSpeed Stubs
if ( ! function_exists( 'litespeed_finish_request' ) ) {
    function litespeed_finish_request() {}
}

// Internal WordPress Stubs (for IDEs that miss them)
if ( ! function_exists( '_get_cron_lock' ) ) {
    function _get_cron_lock() { return false; }
}
if ( ! function_exists( 'wp_cache_postload' ) ) {
    function wp_cache_postload() {}
}

// XML-RPC Extension Stubs (Standard PHP extension)
if ( ! function_exists( 'xmlrpc_get_type' ) ) {
    function xmlrpc_get_type( $value ) { return ''; }
}
if ( ! function_exists( 'xmlrpc_is_fault' ) ) {
    function xmlrpc_is_fault( $arg ) { return false; }
}
if ( ! function_exists( 'xmlrpc_encode_request' ) ) {
    function xmlrpc_encode_request( $method, $params ) { return ''; }
}
if ( ! function_exists( 'xmlrpc_decode' ) ) {
    function xmlrpc_decode( $xml ) { return array(); }
}

// Akismet class stubs (to satisfy IDE logic)
if ( ! class_exists( 'Akismet' ) ) {
    class Akismet {
        public static function get_api_key() { return ''; }
        public static function is_test_mode() { return false; }
        public static function http_post( $request, $path, $ip ) { return array(); }
        public static function _get_microtime() { return 0; }
        public static function delete_old_comments() {}
        public static function delete_old_comments_meta() {}
        public static function check_db_comment( $id, $recheck_reason ) { return ''; }
        public static function recheck_comment( $id, $recheck_reason ) { return ''; }
        public static function comment_is_spam( $approved ) { return false; }
        public static function comment_needs_moderation( $approved ) { return false; }
    }
}

if ( ! class_exists( 'Akismet_Admin' ) ) {
    class Akismet_Admin {
        public static function text_add_link_callback( $m ) { return $m; }
        public static function text_add_link_class( $text ) { return $text; }
    }
}

// Define common constants the IDE assumes are missing
if ( ! defined( 'WP_CACHE' ) ) define( 'WP_CACHE', false );
if ( ! defined( 'SHORTINIT' ) ) define( 'SHORTINIT', false );
if ( ! defined( 'MULTISITE' ) ) define( 'MULTISITE', false );
if ( ! defined( 'WP_DEBUG' ) ) define( 'WP_DEBUG', false );
if ( ! defined( 'XMLRPC_REQUEST' ) ) define( 'XMLRPC_REQUEST', true );
