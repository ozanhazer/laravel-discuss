<?php

namespace Alfatron\Discuss\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    public function getTable()
    {
        // This method is called multiple times for relations
        // thus we are making use of static vars.
        static $tableName;

        if (!$tableName) {
            $tableName = discuss_table(parent::getTable());
        }

        return $tableName;
    }

}
