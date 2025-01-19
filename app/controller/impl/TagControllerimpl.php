<?php
require_once 'C:\xampp\htdocs\udemy\app\entities\Tags.php';
require_once 'C:\xampp\htdocs\udemy\app\model\impl\TagModelimpl.php';

class TagControllerimpl{

    private TagModelimpl $TagModel;
    public function __construct(){
        $this->TagModel = new TagModelimpl();
    }

    public function addTag($tag){
        try{
            return $this->TagModel->addTag($tag);
        }
        catch (Exception $e) {
            return false ;

    }

    }
}




?>