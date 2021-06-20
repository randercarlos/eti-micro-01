<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class CategoryService extends AbstractService
{
    protected $model;

    public function __construct() {
        $this->model = new Category();
    }

    public function findUrl($url) {
        return $this->model->where('url', $url)->firstOrFail();
    }

//    public function delete(Category $category): Model {
//        if (!$category->delete()) {
//            throw new \Exception("Fail on delete category with id {$category->id}" );
//        }
//
//        return $category;
//    }
}
