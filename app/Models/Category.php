<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'url', 'description'];
    protected $with = ['companies'];

    // Article model
    public function getRouteKeyName()
    {
        return 'url';
    }

    public function companies() {
        return $this->hasMany(Company::class);
    }
}
