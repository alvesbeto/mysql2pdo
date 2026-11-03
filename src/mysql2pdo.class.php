<?php

/**
 * @package alvesbeto/mysql2pdo
 * @see     https://github.com/alvesbeto/mysql2pdo
 *
 * Legacy global facade; implementations live in Mysql2pdo\Shim\*.
 */

spl_autoload_register(static function (string $class): void {
    $prefix = 'Mysql2pdo\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/Mysql2pdo/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

if (!class_exists('mysql2pdo', false)) {

class mysql2pdo
{
    public static $currObj;

    public static function mysql_affected_rows($link = null)
    {
        return \Mysql2pdo\Shim\MysqlAffectedRows::invoke($link);
    }

    public static function mysql_client_encoding($link = null)
    {
        return \Mysql2pdo\Shim\MysqlClientEncoding::invoke($link);
    }

    public static function mysql_close($link = null)
    {
        return \Mysql2pdo\Shim\MysqlClose::invoke($link);
    }

    public static function mysql_connect($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
        return \Mysql2pdo\Shim\MysqlConnect::invoke($host, $username, $passwd, $new_link, $client_flags);
    }

    public static function mysql_create_db($database_name, $link = null)
    {
        return \Mysql2pdo\Shim\MysqlCreateDb::invoke($database_name, $link);
    }

    public static function mysql_data_seek($result, $offset)
    {
        return \Mysql2pdo\Shim\MysqlDataSeek::invoke($result, $offset);
    }

    public static function mysql_db_name($result, $row, $field = null)
    {
        return \Mysql2pdo\Shim\MysqlDbName::invoke($result, $row, $field);
    }

    public static function mysql_db_query($database, $query, $link = null)
    {
        return \Mysql2pdo\Shim\MysqlDbQuery::invoke($database, $query, $link);
    }

    public static function mysql_drop_db($database, $link = null)
    {
        return \Mysql2pdo\Shim\MysqlDropDb::invoke($database, $link);
    }

    public static function mysql_multi_query_script($link, $script)
    {
        return \Mysql2pdo\Shim\MysqlMultiQueryScript::invoke($link, $script);
    }

    public static function mysql_errno($link = null)
    {
        return \Mysql2pdo\Shim\MysqlErrno::invoke($link);
    }

    public static function mysql_error($link = null)
    {
        return \Mysql2pdo\Shim\MysqlError::invoke($link);
    }

    public static function mysql_escape_string($escapestr)
    {
        return \Mysql2pdo\Shim\MysqlEscapeString::invoke($escapestr);
    }

    public static function mysql_fetch_array($result, $resulttype = MYSQL_BOTH)
    {
        return \Mysql2pdo\Shim\MysqlFetchArray::invoke($result, $resulttype);
    }

    public static function mysql_fetch_assoc($result)
    {
        return \Mysql2pdo\Shim\MysqlFetchAssoc::invoke($result);
    }

    public static function mysql_fetch_field($result, $field_offset = null)
    {
        return \Mysql2pdo\Shim\MysqlFetchField::invoke($result, $field_offset);
    }

    public static function mysql_fetch_lengths($result)
    {
        return \Mysql2pdo\Shim\MysqlFetchLengths::invoke($result);
    }

    public static function mysql_fetch_object($result, $class_name = 'stdClass', $params = [])
    {
        return \Mysql2pdo\Shim\MysqlFetchObject::invoke($result, $class_name, $params);
    }

    public static function mysql_fetch_row($result)
    {
        return \Mysql2pdo\Shim\MysqlFetchRow::invoke($result);
    }

    public static function mysql_field_flags($result, $field_offset)
    {
        return \Mysql2pdo\Shim\MysqlFieldFlags::invoke($result, $field_offset);
    }

    public static function mysql_field_len($result, $field_offset)
    {
        return \Mysql2pdo\Shim\MysqlFieldLen::invoke($result, $field_offset);
    }

    public static function mysql_field_name($result, $field_offset)
    {
        return \Mysql2pdo\Shim\MysqlFieldName::invoke($result, $field_offset);
    }

    public static function mysql_field_seek($result, $fieldnr)
    {
        return \Mysql2pdo\Shim\MysqlFieldSeek::invoke($result, $fieldnr);
    }

    public static function mysql_field_table($result, $field_offset)
    {
        return \Mysql2pdo\Shim\MysqlFieldTable::invoke($result, $field_offset);
    }

    public static function mysql_field_type($result, $field_offset)
    {
        return \Mysql2pdo\Shim\MysqlFieldType::invoke($result, $field_offset);
    }

    public static function mysql_free_result($result)
    {
        return \Mysql2pdo\Shim\MysqlFreeResult::invoke($result);
    }

    public static function mysql_get_client_info()
    {
        return \Mysql2pdo\Shim\MysqlGetClientInfo::invoke();
    }

    public static function mysql_get_host_info($link = null)
    {
        return \Mysql2pdo\Shim\MysqlGetHostInfo::invoke($link);
    }

    public static function mysql_get_proto_info($link = null)
    {
        return \Mysql2pdo\Shim\MysqlGetProtoInfo::invoke($link);
    }

    public static function mysql_get_server_info($link = null)
    {
        return \Mysql2pdo\Shim\MysqlGetServerInfo::invoke($link);
    }

    public static function mysql_info($link = null)
    {
        return \Mysql2pdo\Shim\MysqlInfo::invoke($link);
    }

    public static function mysql_insert_id($link = null)
    {
        return \Mysql2pdo\Shim\MysqlInsertId::invoke($link);
    }

    public static function mysql_list_dbs($link = null)
    {
        return \Mysql2pdo\Shim\MysqlListDbs::invoke($link);
    }

    public static function mysql_list_fields($database_name, $table_name, $link = null)
    {
        return \Mysql2pdo\Shim\MysqlListFields::invoke($database_name, $table_name, $link);
    }

    public static function mysql_list_processes($link = null)
    {
        return \Mysql2pdo\Shim\MysqlListProcesses::invoke($link);
    }

    public static function mysql_list_tables($database, $link = null)
    {
        return \Mysql2pdo\Shim\MysqlListTables::invoke($database, $link);
    }

    public static function mysql_num_fields($result)
    {
        return \Mysql2pdo\Shim\MysqlNumFields::invoke($result);
    }

    public static function mysql_num_rows($result)
    {
        return \Mysql2pdo\Shim\MysqlNumRows::invoke($result);
    }

    public static function mysql_pconnect($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
        return \Mysql2pdo\Shim\MysqlPconnect::invoke($host, $username, $passwd, $new_link, $client_flags);
    }

    public static function mysql_ping($link = null)
    {
        return \Mysql2pdo\Shim\MysqlPing::invoke($link);
    }

    public static function mysql_query($query, $link = null)
    {
        return \Mysql2pdo\Shim\MysqlQuery::invoke($query, $link);
    }

    public static function mysql_real_escape_string($escapestr, $link = null)
    {
        return \Mysql2pdo\Shim\MysqlRealEscapeString::invoke($escapestr, $link);
    }

    public static function mysql_result($result, $row, $field = null)
    {
        return \Mysql2pdo\Shim\MysqlResult::invoke($result, $row, $field);
    }

    public static function mysql_select_db($dbname, $link = null)
    {
        return \Mysql2pdo\Shim\MysqlSelectDb::invoke($dbname, $link);
    }

    public static function mysql_set_charset($charset, $link = null)
    {
        return \Mysql2pdo\Shim\MysqlSetCharset::invoke($charset, $link);
    }

    public static function mysql_stat($link = null)
    {
        return \Mysql2pdo\Shim\MysqlStat::invoke($link);
    }

    public static function mysql_tablename($result, $row, $field = null)
    {
        return \Mysql2pdo\Shim\MysqlTablename::invoke($result, $row, $field);
    }

    public static function mysql_thread_id($link = null)
    {
        return \Mysql2pdo\Shim\MysqlThreadId::invoke($link);
    }

    public static function mysql_unbuffered_query($query, $link = null)
    {
        return \Mysql2pdo\Shim\MysqlUnbufferedQuery::invoke($query, $link);
    }

    public static function resolveLink($link = null)
    {
        return \Mysql2pdo\ResolveLink::invoke($link);
    }
}

}