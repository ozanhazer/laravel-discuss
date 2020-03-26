<?php

namespace Alfatron\Discuss\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    public function getTable()
    {
        $tableName = parent::getTable();

        return ($tablePrefix = config('discuss.table_prefix')) ?
            $tablePrefix . '_' . $tableName :
            $tableName;
    }

}
