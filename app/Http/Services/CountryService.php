<?php
namespace App\Http\Services;

use Illuminate\Database\Eloquent\Builder;

class CountryService
{
    protected $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function applyFilters(array $filters)
    {
        foreach ($filters as $key => $value) {
            if (method_exists($this, $method = 'filterBy' . ucfirst($key))) {
                $this->$method($value);
            }
        }

        return $this->builder;
    }


    protected function filterByName($value)
    {
        $this->builder->where('name', 'like', "$value%");
    }

    protected function filterByDescription($value)
    {
        $this->builder->where('email', 'like', "$value%");
    }

}
