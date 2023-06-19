<?php
namespace App\Repositories\Implements;
use App\Repositories\Implements\BaseRepository;
use App\Repositories\Interfaces\ITagRepository;
use App\Models\Bank;
class TagRepository extends BaseRepository implements ITagRepository{
    protected $modelTag;

    public function __construct($modelTag){
        parent::__construct($modelTag);
        $this->modelTag = $modelTag;
    }
    public function updateById(array $data, $id){
        $tag = $this->getById($id);
        if(!$tag) throw new ModelNotFoundException('Tag not found for id='.$id.'.');
        $tag = parent::updateById($data, $id);
        return $tag;
    }
}
