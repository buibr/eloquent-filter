<?php

namespace BI\EloquentFilter\Traits;

trait SortableRequestFilter
{
    public function sort($sort)
    {
        $keys = array_keys($sort);

        foreach ($keys as $key) {
            $this->builder->orderBy($key, $sort[$key]);
        }
    }
}
