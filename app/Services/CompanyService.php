<?php

namespace App\Services;

use App\Models\Company;

class CompanyService extends AbstractService
{
    protected $model;

    public function __construct() {
        $this->model = new Company();
    }

    public function getCompanies($filter = null) {
        $companies = $this->model->with('category')
            ->where(function($query) use ($filter) {
                if (!empty($filter)) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                    $query->orWhere('email', $filter);
                    $query->orWhere('phone', $filter);
                }
        })->paginate();

        return $companies;
    }
}
