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

    public function updateCour($course) {
        try {
            $sql = "UPDATE cours SET titre = ?, description = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $course->getTitre(),
                $course->getDescription(),
                $course->getId()
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error updating course: " . $e->getMessage());
        }
    }
    
    public function getCourseById($id) {
        try {
            $sql = "SELECT * FROM cours WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "is created";
            
            if (!$result) {
                return null;
            }
            
            $course = new Cour(
                $result['titre'],
                $result['description'] ?? '',
                $result['images'] ?? '',
                $result['contenu'] ?? ''
            );
            $course->setId($result['id']);
            return $course;
        } catch (PDOException $e) {
            throw new Exception("Error fetching course: " . $e->getMessage());
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