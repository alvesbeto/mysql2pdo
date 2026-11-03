<?php

/**
 * @package alvesbeto/mysql2pdo
 *
 * mysql_* → mysql2pdo shim. The guard means the shim is already loaded in this request (not the removed mysql ext).
 * Loaded via Composer autoload (files) or manual require after mysql2pdo.class.php.
 */
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\Driver\ShimResult;

if (!function_exists('mysql_affected_rows')) {

    if (!defined('MYSQL_BOTH')) {
        define('MYSQL_BOTH', 3);
    }
    if (!defined('MYSQL_NUM')) {
        define('MYSQL_NUM', 2);
    }
    if (!defined('MYSQL_ASSOC')) {
        define('MYSQL_ASSOC', 1);
    }
    if (!defined('MYSQL_CLIENT_COMPRESS')) {
        define('MYSQL_CLIENT_COMPRESS', 32);
    }
    if (!defined('MYSQL_CLIENT_SSL')) {
        define('MYSQL_CLIENT_SSL', 2048);
    }
    if (!defined('MYSQL_CLIENT_INTERACTIVE')) {
        define('MYSQL_CLIENT_INTERACTIVE', 1024);
    }
    if (!defined('MYSQL_CLIENT_IGNORE_SPACE')) {
        define('MYSQL_CLIENT_IGNORE_SPACE', 256);
    }

    function mysql_affected_rows($link = null)
    {

        return mysql2pdo::mysql_affected_rows($link);
    }

    function mysql_client_encoding($link = null)
    {

        return mysql2pdo::mysql_client_encoding($link);
    }

    function mysql_close($link = null)
    {
        if ($link === null) {
            $link = mysql2pdo::$currObj;
        }

        if (!$link || !($link instanceof ShimLink) || $link->connect_errno) {
            return true;
        }

        return mysql2pdo::mysql_close($link);
    }

    function mysql_connect($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
        return mysql2pdo::mysql_connect($host, $username, $passwd, $new_link, $client_flags);
    }

    function mysql_create_db($database_name, $link = null)
    {

        return mysql2pdo::mysql_create_db($database_name, $link);
    }

    function mysql_data_seek($result, $offset)
    {
        if (!($result instanceof ShimResult)) {
            trigger_error('mysql_data_seek(): supplied resource is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        return mysql2pdo::mysql_data_seek($result, $offset);
    }

    function mysql_db_name($result, $row, $field = null)
    {

        return mysql2pdo::mysql_db_name($result, $row, $field);
    }

    function mysql_db_query($database, $query, $link = null)
    {

        return mysql2pdo::mysql_db_query($database, $query, $link);
    }

    function mysql_drop_db($database, $link = null)
    {

        return mysql2pdo::mysql_drop_db($database, $link);
    }

    function mysql_errno($link = null)
    {

        return mysql2pdo::mysql_errno($link);
    }

    function mysql_error($link = null)
    {

        return mysql2pdo::mysql_error($link);
    }

    function mysql_multi_query_script($link, $script)
    {
        return mysql2pdo::mysql_multi_query_script($link, $script);
    }

    function mysql_escape_string($escapestr)
    {
        trigger_error('mysql_escape_string() is deprecated and insecure. Use mysql_real_escape_string() or prepared statements instead.', E_USER_DEPRECATED);
        return mysql2pdo::mysql_escape_string($escapestr);
    }

    function mysql_fetch_array($result, $resulttype = MYSQL_BOTH)
    {
        if (!($result instanceof ShimResult)) {
            trigger_error('mysql_fetch_array(): supplied resource is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        return mysql2pdo::mysql_fetch_array($result, $resulttype);
    }

    function mysql_fetch_assoc($result)
    {
        if (!($result instanceof ShimResult)) {
            trigger_error('mysql_fetch_assoc(): supplied resource is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        return mysql2pdo::mysql_fetch_assoc($result);
    }

    function mysql_fetch_field($result, $field_offset = null)
    {

        return mysql2pdo::mysql_fetch_field($result, $field_offset);
    }

    function mysql_fetch_lengths($result)
    {

        return mysql2pdo::mysql_fetch_lengths($result);
    }

    function mysql_fetch_object($result, $class_name = 'stdClass', $params = [])
    {
        return mysql2pdo::mysql_fetch_object($result, $class_name, $params);
    }

    function mysql_fetch_row($result)
    {
        if (!($result instanceof ShimResult)) {
            trigger_error('mysql_fetch_row(): supplied resource is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        return mysql2pdo::mysql_fetch_row($result);
    }

    function mysql_field_flags($result, $field_offset)
    {

        return mysql2pdo::mysql_field_flags($result, $field_offset);
    }

    function mysql_field_len($result, $field_offset)
    {

        return mysql2pdo::mysql_field_len($result, $field_offset);
    }

    function mysql_field_name($result, $field_offset)
    {

        return mysql2pdo::mysql_field_name($result, $field_offset);
    }

    function mysql_field_seek($result, $fieldnr)
    {

        return mysql2pdo::mysql_field_seek($result, $fieldnr);
    }

    function mysql_field_table($result, $field_offset)
    {

        return mysql2pdo::mysql_field_table($result, $field_offset);
    }

    function mysql_field_type($result, $field_offset)
    {

        return mysql2pdo::mysql_field_type($result, $field_offset);
    }

    function mysql_free_result($result)
    {
        if (!$result || !($result instanceof ShimResult)) {
            return true;
        }

        return mysql2pdo::mysql_free_result($result);
    }

    function mysql_get_client_info()
    {

        return mysql2pdo::mysql_get_client_info();
    }

    function mysql_get_host_info($link = null)
    {

        return mysql2pdo::mysql_get_host_info($link);
    }

    function mysql_get_proto_info($link = null)
    {

        return mysql2pdo::mysql_get_proto_info($link);
    }

    function mysql_get_server_info($link = null)
    {

        return mysql2pdo::mysql_get_server_info($link);
    }

    function mysql_info($link = null)
    {

        return mysql2pdo::mysql_info($link);
    }

    function mysql_insert_id($link = null)
    {

        return mysql2pdo::mysql_insert_id($link);
    }

    function mysql_list_dbs($link = null)
    {
        return mysql2pdo::mysql_list_dbs($link);
    }

    function mysql_list_fields($database_name, $table_name, $link = null)
    {

        return mysql2pdo::mysql_list_fields($database_name, $table_name, $link);
    }

    function mysql_list_processes($link = null)
    {

        return mysql2pdo::mysql_list_processes($link);
    }

    function mysql_list_tables($database, $link)
    {

        return mysql2pdo::mysql_list_tables($database, $link);
    }

    function mysql_num_fields($result)
    {
        if (!($result instanceof ShimResult)) {
            trigger_error('mysql_num_fields(): supplied resource is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        return mysql2pdo::mysql_num_fields($result);
    }

    function mysql_num_rows($result)
    {
        if (!($result instanceof ShimResult)) {
            trigger_error('mysql_num_rows(): supplied resource is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        return mysql2pdo::mysql_num_rows($result);
    }

    function mysql_pconnect($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
        trigger_error('mysql_pconnect() is deprecated. Use PDO with persistent connections instead.', E_USER_DEPRECATED);
        return mysql2pdo::mysql_pconnect($host, $username, $passwd, $new_link, $client_flags);
    }

    function mysql_ping($link = null)
    {

        return mysql2pdo::mysql_ping($link);
    }

    function mysql_query($query, $link = null)
    {
        if (empty($query) || !is_string($query)) {
            trigger_error('mysql_query(): Query cannot be empty', E_USER_WARNING);
            return false;
        }

        if ($link === null) {
            $link = mysql2pdo::$currObj;
        }

        if (!$link || !($link instanceof ShimLink) || $link->connect_errno) {
            $errorMsg = $link && ($link instanceof ShimLink) ? $link->connect_error : 'No MySQL connection';
            error_log('MySQL Connection Error: ' . $errorMsg);
            trigger_error('mysql_query(): No MySQL connection', E_USER_WARNING);
            return false;
        }

        return mysql2pdo::mysql_query($query, $link);
    }

    function mysql_real_escape_string($escapestr, $link = null)
    {
        return mysql2pdo::mysql_real_escape_string($escapestr, $link);
    }

    function mysql_result($result, $row, $field = null)
    {
        if (!($result instanceof ShimResult)) {
            trigger_error('mysql_result(): supplied resource is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        return mysql2pdo::mysql_result($result, $row, $field);
    }

    function mysql_select_db($dbname, $link = null)
    {
        return mysql2pdo::mysql_select_db($dbname, $link);
    }

    function mysql_set_charset($charset, $link = null)
    {

        return mysql2pdo::mysql_set_charset($charset, $link);
    }

    function mysql_stat($link = null)
    {

        return mysql2pdo::mysql_stat($link);
    }

    function mysql_tablename($result, $row, $field = null)
    {

        return mysql2pdo::mysql_tablename($result, $row, $field);
    }

    function mysql_thread_id($link = null)
    {

        return mysql2pdo::mysql_thread_id($link);
    }

    function mysql_unbuffered_query($query, $link = null)
    {

        return mysql2pdo::mysql_unbuffered_query($query, $link);
    }
}
