<?php

namespace Alfatron\Discuss\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    public function getTable()
    {
        $tableName = parent::getTable();

        $prefix = config('discuss.table_prefix');
        if ($prefix && strpos($tableName, $prefix) === false) {
            $tableName = discuss_table($tableName);
        }

        return $tableName;
    }

}
