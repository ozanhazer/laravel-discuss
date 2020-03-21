<?php

namespace Alfatron\Discussions\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    public function getTable()
    {
        $tableName = parent::getTable();
        
        return ($tablePrefix = config('discussions.table_prefix')) ?
            $tablePrefix . '_' . $tableName :
            $tableName;
    }

}
