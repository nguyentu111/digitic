<?php 
namespace App\Exceptions\CustomException;
use Exception;
class UnprocessableContent extends BaseException{
    private $title='unprocessable_content';
    private $statusCode = 422;
    public function __construct($message){
        parent::__construct($message, $this->title,$this->statusCode);
    }

}
?>