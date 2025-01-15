<?php
require_once 'C:/Users/youco/Desktop/iLearN/app/model/UserModel.php';
require_once 'C:/Users/youco/Desktop/iLearN/app/config/Database.php';

class UserModelimpl implements UserModel
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function save(User $user): bool
    {

        $query = "INSERT INTO users (nom , email, password , role) values (:name , :email, :password , :role)";

        try {
            $stmt = $this->conn->prepare($query);

            return $stmt->execute(
                [
                    ':name' => $user->getName(),
                    ':email' => $user->getEmail(),
                    ':password' => $user->getPassword(),
                    ':role' => $user->getRole()->name 
                ]
            );
            
        } catch (Exception $e) {
            throw new Exception("error while saving user into database");
        }
    }


    public function fetchUsers(): array
    {

        $query = "select * from users";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll();

            $users = [];

            foreach ($results as $userResult) {
                $user = new User($userResult["email"], $userResult["password"], $userResult["name"], Role::from($userResult["role"]));

                $users[] = $user;
                // array_push($users, $user);
            }
            return $users;

        } catch (Exception $e) {
            throw new Exception("Error fetching users: " . $e->getMessage());
        }
    }
    public function verifyEmail(string $email): bool
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC) === false;
    }
    public function verifyUser(User $user): array|bool
    {
        $email = $user->getEmail();
        $password = $user->getPassword();
        
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();
        
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        

        if ($result && password_verify($password, $result['password'])) {
            return $result;
        }
        return false;
    }
    public function countUser(): int
    {
        $query = "SELECT COUNT(*) AS usersCount FROM users WHERE role = 'student'";
        $statement = $this->conn->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_OBJ);
        return (int)$result->usersCount;
    }



}

?>