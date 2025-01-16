<?php

require_once 'C:\xampp\htdocs\udemy\app\model\CourModel.php';
require_once 'C:\xampp\htdocs\udemy\app\config\Database.php';

class CourModelimpl implements CourModel
{

    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function addCour(Cour $cour): bool
    {

        $query = "INSERT INTO Cours (titre  , description , contenu , image ) values (:titre , :description, :contenu , :image )";
        try {
            $stmt = $this->conn->prepare($query);

            return $stmt->execute(
                [
                    ':titre' => $cour->gettitre(),
                    ':description' => $cour->getdescription(),
                    ':contenu' => $cour->getcontenu(),
                    ':image' => $cour->getimages() ?? "hhhhhhhhh.jpg"

                ]
            );

        } catch (Exception $e) {
            throw new Exception("error while saving user into database");
        }
    }

    public function updateCour(Cour $cour): void
{
    $query = "UPDATE Cours SET titre = :courtitre, description = :courdescription, contenu = :courContent , image = :image ";

    try {
        $stmt = $this->conn->prepare($query);
        $stmt->execute(
            [
                ':courtitre' => $cour->gettitre(),
                ':courdescription' => $cour->getdescription(),
                ':courContent' => $cour->getcontenu(),
                ':image' => $cour->getimages() ?? "hhhhhhhhh.jpg"
            ]
        ); // Assurez-vous que cette ligne est fermée correctement
    } catch (Exception $e) {
        throw new Exception("error while saving user into database");
    }
}


    public function deleteCour(int $id): void
    {
        $query = "DELETE FROM Cours WHERE id = :courId";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':courId', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function searchCour(string $searchTerm): array
    {
        $query = "SELECT * FROM cours 
                  WHERE titre LIKE :searchTerm 
                  OR description LIKE :searchTerm 
                  OR contenu LIKE :searchTerm";
    
        try {
            $stmt = $this->conn->prepare($query);
            $search = "%{$searchTerm}%";
            $stmt->bindValue(':searchTerm', $search, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            // Log the error or return an empty array to avoid breaking the code
            error_log("Error searching courses: " . $e->getMessage());
            return []; // Return an empty array instead of false
        }
    }


    public function countCour(): int
    {
        $query = "SELECT COUNT(*) AS CourCount FROM Cour";
        $statement = $this->conn->query($query);
        $result = $statement->fetch(PDO::FETCH_OBJ);
        return (int) $result->CourCount;
    }

    public function getAllCours(): array {
        $query = "select * from Cours";
        $statement = $this->conn->query($query);
      $result = $statement->fetchAll(PDO::FETCH_OBJ);
        return  $result;
    }

    }




?>