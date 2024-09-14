<?php

namespace BI\EloquentFilter;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{
    /** @var Request */
    protected $request;

    /** @var Builder */
    protected $builder;

    /**
     * QueryFilter constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \ReflectionException
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            if (!method_exists($this, $name)) {
                continue;
            }

            $param = new \ReflectionParameter([static::class, "$name"], 0);

            if (!$param->isOptional() && empty($value)) {
                continue;
            }

            call_user_func_array([$this, $name], array_filter([$value]));
        }

        return $this->builder;
    }

    /**
     * @return array
     */
    public function filters()
    {
        return $this->request->all();
    }
}
