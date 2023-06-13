<?php

namespace App\Repositories\Implements;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Interfaces\IBaseRepository;

class BaseRepository implements IBaseRepository{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function paginate($itemOnPage)
    {
        return $this->model->paginate($itemOnPage);
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function findMany($ids, $columns = ['*'])
    {
        return $this->model->findMany($ids, $columns);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function destroy($value)
    {
        return $this->destroy($value);
    }

    public function delete($id)
    {
        $object = $this->model->findOrFail($id);
        return $object->delete();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update(array $condition, array $data)
    {
        return $this->model->where($condition)->update($data);
    }

    public function updateById(array $data, $id)
    {
        $model = $this->find($id);

        $model->update($data);

        return $model;
    }

    public function updateOrCreate(array $attributes, $value = [])
    {
        return $this->model->updateOrCreate($attributes, $value);
    }

    public function whereColumn($column_name, $value){
        return $this->model->where($column_name, $value)->first();
    }

    public function whereOrderBy($column_name, $value, $order_by){
        return $this->model->where($column_name, $value)->orderBy('created_at', $order_by)->get();
    }
}
