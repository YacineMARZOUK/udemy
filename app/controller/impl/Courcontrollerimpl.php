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
    public function addCour(Cour $cour){
        try {
           return $this->courModel->addCour( $cour);
           
            
     
        }
        catch (Exception $e) {
            return false ;

    }
    
 }
 public function searchCour($searchTerm){
        try {
           return $this->courModel->searchCour( $searchTerm);
           
            
     
        }
        catch (Exception $e) {
            return false ;

    }



    }
 public function deleteCour($id){
        try {
           return $this->courModel->deleteCour( $id);
           
            
     
        }
        catch (Exception $e) {
            return false ;

    }



    }



 }


?>