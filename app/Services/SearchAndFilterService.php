<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class SearchAndFilterService
{
    
    public static function apply(Builder $query, $queryData, $filters)
    {
        if (!empty($queryData['search'])) {
            $query->where(function ($innerQuery) use ($queryData) {
                foreach ($queryData['searchableColumns'] as $column) {
                    $innerQuery->orWhere($column, 'LIKE', '%' . $queryData['search'] . '%');
                }
            });
        }

        foreach ($filters as $filter => $value) {
            if (method_exists(__CLASS__, $filter)) {
                call_user_func([__CLASS__, $filter], $query, $value);
            }
        }

        return $query;
    }

    public static function priceFilter(Builder $query, $value)
    {
        return $query->where('unit_price', '<=', $value);
    }
}