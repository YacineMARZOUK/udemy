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
        $query = "INSERT INTO cours (titre, description, contenu, video, idCategorie, idTeacher, created_at, updated_at) 
                 VALUES (:titre, :description, :contenu, :video, :idCategorie, :idTeacher, NOW(), NOW())";
        try {
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                ':titre' => $cour->gettitre(),
                ':description' => $cour->getdescription(),
                ':contenu' => $cour->getcontenu(),
                ':video' => $cour->getVideo(),
                ':idCategorie' => $cour->getIdCategorie(),
                ':idTeacher' => $cour->getIdTeacher()
            ]);
            
            if (!$result) {
                error_log("Database insert failed: " . print_r($stmt->errorInfo(), true));
            }
            return $result;
        } catch (Exception $e) {
            error_log("Error adding course: " . $e->getMessage());
            throw new Exception("Error while saving course: " . $e->getMessage());
        }
    }
    
    public function updateCour($course) {
        try {
            $sql = "UPDATE cours 
                    SET titre = :titre, 
                        description = :description,
                        contenu = :contenu,
                        idTeacher = :idTeacher,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";
                    
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':titre' => $course->getTitre(),
                ':description' => $course->getDescription(),
                ':contenu' => $course->getContenu(),
                ':idTeacher' => $course->getIdTeacher(),
                ':id' => $course->getId()
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Error updating course: " . $e->getMessage());
        }
    }
    public function getCourseById($id) {
        try {
            $sql = "SELECT c.*, cat.titre as category_name 
                    FROM cours c 
                    LEFT JOIN categories cat ON c.idCategorie = cat.id 
                    WHERE c.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$result) {
                return null;
            }
    
            // Create course object with proper type casting
            $course = new Cour(
                $result['titre'],
                $result['description'] ?? '',
                $result['contenu'] ?? '',
                (int)$result['idcategorie'], // Cast to integer
                (int)$result['idteacher'],   // Cast to integer
                $result['video'] ?? 'default.mp4'
            );
            $course->setId((int)$result['id']);
            return $course;
        } catch (PDOException $e) {
            error_log("Error fetching course: " . $e->getMessage());
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

    public function searchCour($searchTerm) {
        $searchW = "%" . $searchTerm . "%";  // Add wildcards to the search term
    
        $query = "SELECT c.*, cat.titre as category_name 
                  FROM cours c 
                  LEFT JOIN categories cat ON c.idCategorie = cat.id
                  WHERE c.titre LIKE :searchTerm 
                  OR c.description LIKE :searchTerms 
                  OR c.contenu LIKE :searchTerma";
        
        try {
            $stmt = $this->conn->prepare($query);
    
            // Bind the same parameter for each occurrence of :searchTerm
            $stmt->bindParam(':searchTerm', $searchW, PDO::PARAM_STR);
            $stmt->bindParam(':searchTerms', $searchW, PDO::PARAM_STR);
            $stmt->bindParam(':searchTerma', $searchW, PDO::PARAM_STR);
    
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error searching courses: " . $e->getMessage());
            error_log("Query: " . $query);
            return "Error searching courses: " . $e->getMessage();
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
                 FROM cours c 
                 LEFT JOIN categories cat ON c.idCategorie = cat.id";
        $statement = $this->conn->query($query);
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllMyCours($getID): array {
        $query = "SELECT * FROM cours INNER JOIN enrolled ON cours.id = enrolled.idcour WHERE cours.idteacher = :getID";
        $statement = $this->conn->prepare($query);  // Use prepare instead of query
        $statement->bindParam(":getID", $getID, PDO::PARAM_INT);  // Bind the parameter
        $statement->execute();  // Execute the query
        return $statement->fetchAll(PDO::FETCH_OBJ);  // Fetch the results
    }
    public function getAllCoursbyTeacher($idTeacher): array {
        $query = "SELECT c.*, cat.titre as category_name 
                 FROM cours c 
                 LEFT JOIN categories cat ON c.idCategorie = cat.id  where c.idteacher = :idt " ;
        $statement = $this->conn->prepare($query);
        $statement->bindParam(":idt",$idTeacher);
        $statement->execute();
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
            // First check if user is already enrolled
            $checkQuery = "SELECT id FROM enrolled WHERE iduser = :userId AND idcour = :courseId";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([
                ':userId' => $userId,
                ':courseId' => $courseId
            ]);
            
            if ($checkStmt->rowCount() > 0) {
                return [
                    "success" => false, 
                    "message" => "You are already enrolled in this course"
                ];
            }
            
            // Proceed with enrollment
            $query = "INSERT INTO enrolled (iduser, idcour, created_at) VALUES (:userId, :courseId, NOW())";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                ':userId' => $userId,
                ':courseId' => $courseId
            ]);
            
            if ($result) {
                return [
                    "success" => true, 
                    "message" => "Successfully enrolled in the course"
                ];
            } else {
                return [
                    "success" => false, 
                    "message" => "Failed to enroll in the course"
                ];
            }
        } catch (PDOException $e) {
            error_log("Database error during enrollment: " . $e->getMessage());
            return [
                "success" => false, 
                "message" => "Database error during enrollment"
            ];
        }
    }

}

?>