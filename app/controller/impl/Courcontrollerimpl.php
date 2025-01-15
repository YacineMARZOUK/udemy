<?php
require_once 'C:\xampp\htdocs\udemy\app\entities\Cours.php';
require_once 'C:\xampp\htdocs\udemy\app\model\impl\CourModelimpl.php';
 class Courcontrollerimpl {
    private CourModelimpl $courModel;
    public function __construct()
    {
        $this->courModel = new CourModelimpl();
    }

    public function fetchCours(): array|bool {
        try {
            $result=$this->courModel->getAllCours();
            return $result;
            
     
        }
        catch (Exception $e) {
            return false ;

    }


    }




 }





?>