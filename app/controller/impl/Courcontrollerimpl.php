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

 public function countCour(){
        try {
           return $this->courModel->countCour();
           
            
     
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
public function updateCour($course): bool
{
    try {
        return $this->courModel->updateCour($course);
    } catch (Exception $e) {
        return false;
    }
}

public function getCourseById(int $id): ?object
{
    try {
        return $this->courModel->getCourseById($id);
    } catch (Exception $e) {
        return null;
    }
}
public function enrollUserInCourse($userId, $courseId)
{
    try {
        return $this->courModel->enrollUserInCourse($userId, $courseId);
    } catch (Exception $e) {
        return null;
    }
}



 }


?>