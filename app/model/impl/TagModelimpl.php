<?php
require_once 'C:\xampp\htdocs\udemy\app\model\TagModel.php';
require_once 'C:\xampp\htdocs\udemy\app\config\Database.php';

class TagModelimpl implements TagModel{
    
    private PDO $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function addTag(Tag $tag): bool{
        $query = "INSERT into tags (nom) values (:nom)";
        try {
            $stmt=$this->conn->prepare($query);
            return $stmt->execute([
                ':nom'=>$tag->getNom()
            ]);
        } catch (Exception $e) {
            throw new Exception("Error while adding Tag to the database: " . $e->getMessage());
        }
    }

    public function getAllTags(): array
    {
        $query = "SELECT * FROM tags";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        

       
    }




}









?>