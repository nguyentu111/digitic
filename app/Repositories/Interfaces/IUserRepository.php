<?php
namespace App\Repositories\Interfaces;
use App\Repositories\Interfaces\IBaseRepository;

interface IUserRepository extends IBaseRepository{
    public function createUser();
}
