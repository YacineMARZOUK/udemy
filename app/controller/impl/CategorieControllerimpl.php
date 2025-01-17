<?php
require_once 'C:\xampp\htdocs\udemy\app\entities\Categorie.php';
require_once 'C:\xampp\htdocs\udemy\app\model\impl\CategorieModelimpl.php';
 class CategorieControllerimpl {
    private CategorieModelimpl $categorieModel;
    public function __construct()
    {
        $this->categorieModel = new CategorieModelimpl();
    }

    public function addCategorie($categorie){
        try {
           return $this->categorieModel->addCategorie( $categorie);
           
            
     
        }
        catch (Exception $e) {
            return false ;

    }
    
 }

    public function deleteCategorie($id){
         try {
            return $this->categorieModel->deleteCategorie( $id);
       
         }
         catch (Exception $e) {
             return false ;
    }
}

public function updateCategorie(Categorie $categorie): bool
{
    try {
        return $this->courModel->updateCategorie($id);
    } catch (Exception $e) {
        return false;
    }
}




}