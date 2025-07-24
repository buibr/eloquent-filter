<?php

namespace BI\EloquentFilter\Traits;

trait LimitableRequestFilter
{
    public $limitPrePage = 15;

    public function limit($limit)
    {
        $this->limitPrePage = (int)$limit;
        return $this->builder->limit($limit);
    }

    public function getLimit()
    {
        return $this->limitPrePage;
    }
}
