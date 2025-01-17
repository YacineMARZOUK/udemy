<?php

require_once 'C:\xampp\htdocs\udemy\app\model\CategorieModel.php';
require_once 'C:\xampp\htdocs\udemy\app\config\Database.php';

class CategorieModelImpl implements CategorieModel
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function addCategorie($categorie): bool {
        $query = "INSERT INTO categories (titre) VALUES (:titre)";
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':titre' => $categorie->getTitre()
            ]);
        } catch (Exception $e) {
            throw new Exception("Error while adding category to the database: " . $e->getMessage());
        }
    }

    public function updateCategorie(Categorie $categorie): bool
    {
        try {
            $sql = "UPDATE categories SET nom = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $categorie->getTitre(),
                $categorie->getId()
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error updating category: " . $e->getMessage());
        }
    }

    public function deleteCategorie(int $id): void
    {
        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function countCategorie(): int
    {
        $query = "SELECT COUNT(*) AS categoryCount FROM categories";
        $statement = $this->conn->query($query);
        $result = $statement->fetch(PDO::FETCH_OBJ);
        return (int) $result->categoryCount;
    }

}
?>
