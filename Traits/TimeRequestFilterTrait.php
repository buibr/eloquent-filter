<?php

namespace BI\EloquentFilter\Traits;

use BI\EloquentFilter\Exceptions\FilterableColumnException;

trait TimeRequestFilterTrait
{

    private string $filterDateColumn = 'created_at';

    /**
     * @override
     */
    public function getFilterDateColumn():string
    {
        $filter = trim($this->filterDateColumn);

        if(!$this->hasAttribute($filter)){
            throw new FilterableColumnException('The column '.$filter.' does not exist in the model '.$this->builder->getModel()::class);
        }

        if(!$this->isFillable($filter)){
            throw new FilterableColumnException('The column '.$filter.' is not fillable in the model '.$this->builder->getModel()::class);
        }

        return $filter;
    }

    /**
     * Incoming from request to change the column to filter by date
     */
    public function dateColumn(string $column): void
    {
        $this->filterDateColumn = $column;
    }

    public function timeFrom(string $timeFrom): void
    {
        $date = now()->parse($timeFrom);
        $this->builder->where($this->getFilterDateColumn(), '>=', $date);
    }

    public function timeTo(string $timeTo): void
    {
        $date = now()->parse($timeTo);
        $this->builder->where($this->getFilterDateColumn(), '<=', $date);
    }

    public function dateFrom(string $dateFrom): void
    {
        $date = now()->parse($dateFrom)->startOfDay();
        $this->builder->where($this->getFilterDateColumn(), '>=', $date);
    }

    public function dateTo(string $dateTo): void
    {
        $date = now()->parse($dateTo)->endOfDay();
        $this->builder->where($this->getFilterDateColumn(), '<=', $date);
    }

    public function today($today = true): void
    {
        $this->builder->whereToday($this->getFilterDateColumn(), true);
    }

    public function yesterday($yesterday = true): void
    {
        $date = now()->subDay();
        $this->builder->where($this->getFilterDateColumn(), $date->day)
            ->whereMonth($this->getFilterDateColumn(), $date->month)
            ->whereYear($this->getFilterDateColumn(), $date->year);
    }

    public function thisWeek($thisWeek = true): void
    {
        $this->builder->where($this->getFilterDateColumn(), '>=', now()->startOfWeek()->toDateTimeString());
    }

    public function lastWeek($lastWeek = true): void
    {
        $date = now()->subWeek()->startOfWeek()->startOfDay();
        $end = $date->copy()->endOfWeek();
        $this->builder->whereBetween($this->getFilterDateColumn(), [$date, $end]);
    }

    public function thisMonth($thisMonth = true): void
    {
        $this->builder->whereMonth($this->getFilterDateColumn(), '>=', now()->startOfMonth());
    }

    public function lastMonth($lastMonth): void
    {
        $date = now()->subMonth()->startOfMonth();
        $end = $date->copy()->endOfMonth();
        $this->builder->whereBetween($this->getFilterDateColumn(), [$date, $end]);
    }

    public function monthOfYear($monthOfYear): void
    {
        $start = now()->setMonth((int)$monthOfYear)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $this->builder->whereBetween($this->getFilterDateColumn(), [$start, $end]);
    }

    public function firstQuarter(): void
    {
        $start = now()->setMonth(1)->startOfMonth();
        $end = now()->setMonth(3)->endOfMonth();
        $this->builder->whereBetween($this->getFilterDateColumn(), [$start, $end]);
    }
    public function secondQuarter(): void
    {
        $start = now()->setMonth(4)->startOfMonth();
        $end = now()->setMonth(6)->endOfMonth();
        $this->builder->whereBetween($this->getFilterDateColumn(), [$start, $end]);
    }
    public function thirdQuarter(): void
    {
        $start = now()->setMonth(7)->startOfMonth();
        $end = now()->setMonth(9)->endOfMonth();
        $this->builder->whereBetween($this->getFilterDateColumn(), [$start, $end]);
    }
    public function fourthQuarter(): void
    {
        $start = now()->setMonth(10)->startOfMonth();
        $end = now()->setMonth(12)->endOfMonth();
        $this->builder->whereBetween($this->getFilterDateColumn(), [$start, $end]);
    }

    public function thisYear(): void
    {
        $this->builder->whereYear($this->getFilterDateColumn(), '>=', now()->startOfYear());
    }

    public function lastYear($lastYear = true): void
    {
        $date = now()->subYear()->startOfYear();
        $end = $date->copy()->endOfYear();
        $this->builder->whereBetween($this->getFilterDateColumn(), [$date, $end]);
    }

    public function year($year): void
    {
        $start = now()->setYear((int)$year)->startOfYear();
        $end = $start->copy()->endOfYear();
        $this->builder->whereBetween($this->getFilterDateColumn(), [$start, $end]);
    }
}
