<?php

namespace App\Models\Traits;

trait HasTable
{
    public static function getTableName(): string
    {
        /** @var Model $model */
        $model = new static();
        return $model->getTable();
    }
}
