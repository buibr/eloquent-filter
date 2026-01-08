<?php

namespace BI\EloquentFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str as LrvStr;

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

            if (is_array($value) && empty($value)) {
                continue;
            }

            // or:1234
            if (is_string($value) && strpos(':', $value) !== false) {
                $value = explode(':', $value);
                $arguments = array_reverse($value);
            } elseif (is_array($value) && !$this->is_assoc($value) && 'sort' !== $name) {
                $key = $value['operator'] ?? 'and';
                $value = $value['value'] ?? '';
                $arguments = [$value, $key];
            } else {
                $arguments = [$value];
            }

            call_user_func_array([$this, $name], array_filter($arguments));
        }

        return $this->builder;
    }

    function is_assoc($array)
    {
        if (function_exists('array_is_list')) {
            return !array_is_list($array);
        }

        return array_values($array) !== $array;
    }

    /**
     * @return array
     */
    public function filters()
    {
        return $this->request->all();
    }

    /**
     * sort by parameters
     *
     * example:
     * ```
     * public function sort_name($direction){
     *     $this->builder->orderBy('name', $direction);
     * }
     * ```
     *
     * @param $sort
     * @return Builder
     */
    public function sort($sort)
    {
        $keys = array_keys($sort);

        foreach ($keys as $key) {
            $methodKey = 'sort_' . $key;

            if (method_exists($this, $methodKey)) {
                $this->{'sort_' . $key}($sort[$key]);
                continue;
            }

            if (method_exists($this, LrvStr::camel($methodKey))) {
                $methodKey = LrvStr::camel($methodKey);
                $this->{$methodKey}($sort[$key]);
                continue;
            }

            $this->builder->orderBy($key, $sort[$key]);
        }

        return $this->builder;
    }
}
