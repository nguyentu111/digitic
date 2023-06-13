<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IBaseRepository {
    public function paginate($itemOnPage);

    public function getAll();

    public function all($columns = ['*']);

    public function find($id, $columns = ['*']);

    public function findMany($ids,  $columns = ['*']);

    public function getById($id);

    public function destroy($value);

    public function delete($id);

    public function create($data);

    public function update(array $condition, array $data);

    public function updateById(array $data, $id);

    public function updateOrCreate(array $attributes, $value = []);

    public function whereColumn($column_name, $value);

    public function whereOrderBy($column_name, $value, $order_by);
}