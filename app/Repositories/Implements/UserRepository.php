<?php
namespace App\Repositories\Implements;
use App\Repositories\Implements\BaseRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Models\User;
class UserRepository extends BaseRepository implements IUserRepository{
    protected $userModel;

    public function __construct($userModel){
        parent::__construct($userModel);
        $this->userModel = $userModel;
    }

    public function createUser(){

    }
  
}
