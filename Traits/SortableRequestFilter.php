<?php

namespace BI\EloquentFilter\Traits;

use Illuminate\Support\Str as LrvStr;

trait SortableRequestFilter
{
    public function sort($sort)
    {
        $keys = array_keys($sort);

        foreach ($keys as $key) {

            $methodKey = 'sort_' . $key;

            if(method_exists($this, $methodKey)){
                $this->{$methodKey}($sort[$key]);
                continue;
            }

            if(method_exists($this, LrvStr::camel($methodKey))){
                $methodKey = LrvStr::camel($methodKey);
                $this->{$methodKey}($sort[$key]);
                continue;
            }

            $this->builder->orderBy($key, $sort[$key]);
        }

        return $this->builder;
    }
}
