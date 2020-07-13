<?php

function discuss_table($tableName)
{
    return ($tablePrefix = config('discuss.table_prefix')) ?
        $tablePrefix . '_' . $tableName :
        $tableName;
}

function discuss_theme($path)
{
    return url('vendor/discuss/' .
        trim(config('discuss.theme'), '/') . '/' .
        trim($path, '/'));
}
