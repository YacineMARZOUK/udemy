<?php

require_once 'C:\Users\youco\Desktop\iLearN\app\model\CourModel.php';
require_once 'C:\Users\youco\Desktop\iLearN\app\config\Database.php';

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
            );

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

    public function searchCour(string $titre): array
    {
        $query = "
            SELECT DISTINCT Cours.*, user.*, categorie.* 
            FROM wiki 
            JOIN user ON wiki.idUser = user.userId 
            JOIN categorie ON wiki.idCategorie = categorie.categorieId 
            WHERE wiki.wikiTitre LIKE CONCAT('%', ?, '%') 
            OR categorie.categorieName LIKE CONCAT('%', ?, '%')
        ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$titre, $titre]);
            $results = $stmt->fetchAll();

            $users = [];

            foreach ($results as $userResult) {
                $user = new User($userResult["email"], $userResult["password"], $userResult["name"]);

                $users[] = $user;
                // array_push($users, $user);
            }
            return $users;

        } catch (Exception $e) {
            throw new Exception("Error fetching users: " . $e->getMessage());
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