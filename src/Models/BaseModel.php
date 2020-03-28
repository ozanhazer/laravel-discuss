<?php

namespace Alfatron\Discuss\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    public function getTable()
    {
        return discuss_table(parent::getTable());
    }

}
