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
    public function with($populateValue)
    {
        return $this->model->with($populateValue);
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
        return $this->model->find($id, $columns);
    }
    public function findOrFail($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }
    public function findMany($ids, $columns = ['*'])
    {
        return $this->model->findMany($ids, $columns);
    }

    public function getById($id)
    {
        $object = $this->model->find($id);
        return $object;
    }

    public function destroy($value)
    {
        return $this->destroy($value);
    }

    public function delete($id)
    {
        $object = $this->model->find($id);
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
    public function where($val){
        return $this->model->where($val);
    }

    public function whereColumn($column_name, $value){
        return $this->model->where($column_name, $value)->first();
    }

    public function whereOrderBy($column_name, $value, $order_by){
        return $this->model->where($column_name, $value)->orderBy('created_at', $order_by)->get();
    }
    public function restoreAll(){
        return $this->model->withTrashed()->restore();
    }
    public function whereBelongsTo($value){
        return $this->model->whereBelongsTo($value);
    }
}
