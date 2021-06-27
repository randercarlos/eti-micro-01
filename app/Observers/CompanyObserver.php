<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Support\Str;

class CompanyObserver
{
    /**
     * Handle the Category "created" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function creating(Company $company)
    {
        $company->url = Str::slug($company->name);
    }

    /**
     * Handle the Category "updated" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function updating(Company $company)
    {
        $company->url = Str::slug($company->name);
    }
}
