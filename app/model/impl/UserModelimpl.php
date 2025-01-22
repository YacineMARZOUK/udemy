<?php
require_once 'C:\xampp\htdocs\udemy\app\model\UserModel.php';
require_once 'C:\xampp\htdocs\udemy\app\config\Database.php';

class UserModelimpl implements UserModel
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function save(User $user): bool {
        if ($user instanceof Student || $user instanceof Teacher) {
            $query = "INSERT INTO users (nom, email, password, role, status) 
                      VALUES (:name, :email, :password, :role, :status)";
            
            try {
                $stmt = $this->conn->prepare($query);
                
                return $stmt->execute([
                    ':name' => $user->getName(),
                    ':email' => $user->getEmail(),
                    ':password' => $user->getPassword(),
                    ':role' => $user->getRole(), // Get the string value from the enum
                    ':status' => $user->getStatus() // Now properly getting status from the user object
                ]);
                
            } catch (Exception $e) {
                throw new Exception("Error while saving user into database: " . $e->getMessage());
            }
        }
        throw new Exception("Invalid user type");
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
    public function verifyEmail(string $email) {
        $query = "SELECT * FROM users WHERE email = :email";
        try {
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':email', $email);
            $statement->execute();
            
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return null;
            }
            $role = Role::from($result['role']);
        $status = $result['status'] ?? 'active';
    
            // Create the appropriate user type based on role
            if ($role === Role::TEACHER) {
                $user = new Teacher(
                    $result['email'],
                    $result['password'],
                    $result['nom'],
                    $role,
                    $status
                );
            } else if ($role === Role::STUDENT) {
                $user = new Student(
                    $result['email'],
                    $result['password'],
                    $result['nom'],
                    $role,
                    $status
                );
            } else {
                $user = new User(
                    $result['email'],
                    $result['password'],
                    $result['nom'],
                    $role,
                    $status
                );
            }
    
            if (isset($result['id'])) {
                $user->setId($result['id']);
            }
            
            return $user;
    
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    public function verifyUser(User $user): array|bool
    {
        $email = $user->getEmail();
        $password = $user->getPassword();
        
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':email', $user);
        $statement->execute();
        
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
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


    public function getAllUsers(): array {
        $query = "SELECT * FROM users WHERE role != 'admin'";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $users = [];
            foreach ($results as $result) {
                if ($result['role'] === 'teacher') {
                    $user = new Teacher(
                        $result['email'],
                        $result['password'],
                        $result['nom'],
                        Role::from($result['role']),
                        $result['status']
                    );
                } else {
                    $user = new Student(
                        $result['email'],
                        $result['password'],
                        $result['nom'],
                        Role::from($result['role']),
                        $result['status']
                    );
                }
                $user->setId($result['id']);
                $users[] = $user;
            }
            return $users;
        } catch (Exception $e) {
            throw new Exception("Error fetching users: " . $e->getMessage());
        }
    }
    
    public function updateUserStatus(int $userId, string $status): bool {
        $query = "UPDATE users SET status = :status WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':status' => $status,
                ':id' => $userId
            ]);
        } catch (Exception $e) {
            throw new Exception("Error updating user status: " . $e->getMessage());
        }
    }



}

?>