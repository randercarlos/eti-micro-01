<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractService
{
    protected $model;

    public function loadAll($recordsPerPage = false) {
        $class = get_class($this->model);
        $obj = new $class;

        return $recordsPerPage ? $obj->paginate($recordsPerPage) : $obj->get();
    }

    public function loadByName(string $name = null, bool $like = false): ?Collection {
        $query = $this->model->orderBy('name');
        if (!empty($name)) {
            if ($like) {
                $query->where('name', 'like', '%' . $name . '%');
            } else {
                $query->whereName($name);
            }
        }

        return $query->get();
    }

    public function loadNameAndIdOnly(): ?Collection {
        if (in_array('name', $this->model->getFillable())) {
            return $this->model->orderBy('name')->pluck('name', 'id');
        }

        return null;
    }

    public function find(int $id): Model {
        $class = get_class($this->model);

        if (!$record = $this->model->find($id)) {
            throw new ModelNotFoundException("{$class} with id $id was not found!");
        }

        return $record;
    }

    public function save(array $data, Model $model = null): Model {

        $class = get_class($this->model);

        if (!is_null($model) && !empty($model->id)) {

            if (!$model->update($data)) {
                throw new \Exception("Fail on update {$class} with values: " . implode(', ', $data));
            }

        } else {
            if (!$model = $class::create($data)) {
                throw new \Exception("Fail on create {$class} with values: " . implode(', ', $data));
            }

        }

        return $model;
    }

    public function delete($model): Model {
        $class = get_class($this->model);
        if (is_integer($model)) {
            $model = $this->find($model);
        }

        if (!($model instanceof Model)) {
            throw new \Exception("Only models can be deleted.");
        }

        if (!$model->delete()) {
            throw new \Exception("Fail on delete {$class} with id {$model->id}");
        }

        return $model;
    }
}
