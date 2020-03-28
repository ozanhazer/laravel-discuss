<?php

function discuss_table($tableName)
{
    return ($tablePrefix = config('discuss.table_prefix')) ?
        $tablePrefix . '_' . $tableName :
        $tableName;
}
