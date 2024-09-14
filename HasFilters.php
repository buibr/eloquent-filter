<?php

namespace BI\EloquentFilter;

trait HasFilters
{
    /**
     * @param                                 $query
     * @param \BI\EloquentFilter\QueryFilter $filter
     * @return mixed
     */
    public function scopeFilter($query, QueryFilter $filter)
    {
        return $filter->apply($query);
    }
}
