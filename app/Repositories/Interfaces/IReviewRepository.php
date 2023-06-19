<?php
namespace App\Repositories\Interfaces;
use App\Repositories\Interfaces\IBaseRepository;
use App\Models\Bank;

interface IReviewRepository extends IBaseRepository{
    public function createReview($data);
    public function deleteReview($id);
    public function getUserReviews($user_id,array $data);
}
