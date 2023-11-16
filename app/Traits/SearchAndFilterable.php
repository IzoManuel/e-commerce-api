<?php
namespace App\Traits;

use App\Services\SearchAndFilterService;

trait SearchAndFilterable
{
    public function scopeSearchAndFilter($query, $queryData, $filters)
    {
        return SearchAndFilterService::apply($query, $queryData, $filters);
    }
}