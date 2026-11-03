<?php

/**
 * @package alvesbeto/mysql2pdo
 *
 * mysql_* → mysql2pdo shim. The guard means the shim is already loaded in this request (not the removed mysql ext).
 * Loaded via Composer autoload (files) or manual require after mysql2pdo.class.php.
 */
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\Driver\ShimResult;
use Mysql2pdo\ResolveLink;

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

        return mysql2pdo::mysql_escape_string($escapestr);
    }

    function mysql_fetch_array($result, $resulttype = MYSQL_BOTH)
    {
        return mysql2pdo::mysql_fetch_array($result, $resulttype);
    }

    function mysql_fetch_assoc($result)
    {
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
        return mysql2pdo::mysql_num_fields($result);
    }

    function mysql_num_rows($result)
    {
        return mysql2pdo::mysql_num_rows($result);
    }

    function mysql_pconnect($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
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

        $resolved = ResolveLink::ensureConnectedForQuery($link ?? mysql2pdo::$currObj ?? null);
        if (!$resolved instanceof ShimLink) {
            error_log('MySQL Connection Error: No MySQL connection');
            trigger_error('mysql_query(): No MySQL connection', E_USER_WARNING);

            return false;
        }

        return mysql2pdo::mysql_query($query, $resolved);
    }

    function mysql_real_escape_string($escapestr, $link = null)
    {
        return mysql2pdo::mysql_real_escape_string($escapestr, $link);
    }

    function mysql_result($result, $row, $field = null)
    {
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

    function is_resource_custom($var): bool
    {
        return \Mysql2pdo\Driver\PdoExecutor::isLink($var)
            || \Mysql2pdo\Driver\PdoExecutor::isResult($var);
    }

    function get_resource_type_custom($var): string
    {
        if (\Mysql2pdo\Driver\PdoExecutor::isLink($var)) {
            return 'mysql link';
        }
        if (\Mysql2pdo\Driver\PdoExecutor::isResult($var)) {
            return 'mysql result';
        }

        return 'unknown';
    }
}
