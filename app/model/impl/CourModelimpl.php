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

    public function addCour(Cour $cour): bool {
        $query = "INSERT INTO Cours (titre, description, contenu, image, idCategorie) 
                 VALUES (:titre, :description, :contenu, :image, :idCategorie)";
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':titre' => $cour->gettitre(),
                ':description' => $cour->getdescription(),
                ':contenu' => $cour->getcontenu(),
                ':image' => $cour->getimages() ?? "default.jpg",
                ':idCategorie' => $cour->getIdCategorie()
            ]);
        } catch (Exception $e) {
            throw new Exception("Error while saving course into database: " . $e->getMessage());
        }
    }

    public function updateCour($course) {
        try {
            $sql = "UPDATE cours 
                    SET titre = :titre, 
                        description = :description,
                        contenu = :contenu,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";
                    
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':titre' => $course->getTitre(),
                ':description' => $course->getDescription(),
                ':contenu' => $course->getContenu(),
                ':id' => $course->getId()
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Error updating course: " . $e->getMessage());
        }
    }
    
    public function getCourseById($id) {
        try {
            $sql = "SELECT * FROM cours WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return null;
            }

            $course = new Cour(
                $result['titre'],
                $result['description'] ?? '',
                $result['contenu'] ?? '',
                $result['idcategorie'],
                $result['image'] ?? ''
                
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

    public function searchCour(string $searchTerm): array {
        $query = "SELECT c.*, cat.titre as category_name 
                 FROM cours c 
                 LEFT JOIN categories cat ON c.idCategorie = cat.id
                 WHERE c.titre LIKE :searchTerm 
                 OR c.description LIKE :searchTerm 
                 OR c.contenu LIKE :searchTerm";

        try {
            $stmt = $this->conn->prepare($query);
            $search = "%{$searchTerm}%";
            $stmt->bindValue(':searchTerm', $search, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error searching courses: " . $e->getMessage());
            return [];
        }
    }


    public function countCour(): int
    {
        $query = "SELECT COUNT(*) AS CourCount FROM Cours";
        $statement = $this->conn->query($query);
        $result = $statement->fetch(PDO::FETCH_OBJ);
        return (int) $result->CourCount;
    }

    public function getAllCours(): array {
        $query = "SELECT c.*, cat.titre as category_name 
                 FROM Cours c 
                 LEFT JOIN categories cat ON c.idCategorie = cat.id";
        $statement = $this->conn->query($query);
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCoursesByCategory(int $categoryId): array {
        $query = "SELECT * FROM Cours WHERE idCategorie = :categoryId";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error fetching courses by category: " . $e->getMessage());
            return [];
        }
    }
    
    public function enrollUserInCourse($userId, $courseId) {
        try {
            $connection = Database::getInstance()->getConnection();
            
            // Vérifier si l'utilisateur est déjà inscrit
            $checkQuery = "SELECT id FROM enrolled WHERE iduser = ? AND idcour = ?";
            $checkStmt = $connection->prepare($checkQuery);
            $checkStmt->execute([$userId, $courseId]);
            
            if ($checkStmt->rowCount() > 0) {
                return ["success" => false, "message" => "You are already enrolled in this course"];
            }
            
            // Procéder à l'inscription
            $query = "INSERT INTO enrolled (iduser, idcour, created_at) VALUES (?, ?, NOW())";
            $stmt = $connection->prepare($query);
            $result = $stmt->execute([$userId, $courseId]);
            
            if ($result) {
                return ["success" => true, "message" => "Successfully enrolled in the course"];
            } else {
                return ["success" => false, "message" => "Failed to enroll in the course"];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

}

?>