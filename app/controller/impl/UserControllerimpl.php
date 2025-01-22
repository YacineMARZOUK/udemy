<?php
require_once 'C:\xampp\htdocs\udemy\app\entities\User.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\Student.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\Teacher.php';
require_once 'C:\xampp\htdocs\udemy\app\enums\Role.php';
require_once 'C:\xampp\htdocs\udemy\app\model\UserModel.php';
require_once 'C:\xampp\htdocs\udemy\app\model\impl\UserModelimpl.php';

class UserControllerimpl {
    private UserModelimpl $userModel;

    public function __construct() {
        $this->userModel = new UserModelimpl();
    }
    
    public function save(Student|Teacher $person): bool {
        try {
            if ($person->getRole() === Role::STUDENT) {
                $person = new Student(
                    $_POST["email"],
                    $_POST["password"],
                    $_POST["name"],
                    Role::from($_POST["role"], // Utilisation de la méthode from()
                    $_POST["status"])
                );
            } else if ($person->getRole()=== Role::TEACHER) {
                $person = new Teacher(
                    $_POST["email"],
                    $_POST["password"],
                    $_POST["name"],
                    Role::from($_POST["role"], // Utilisation de la méthode from()
                    $_POST["status"])
                );
            }
            
            return $this->userModel->save($person);
        } catch(Exception $e) {
            error_log("Erreur lors de la sauvegarde de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function verifyUser(User $person) {
        try {
            $user = $this->userModel->verifyEmail($person->getEmail());
            
            if (!$user) {
                return null;
            }
    
            // Verify password
            if (!password_verify($person->getPassword(), $user->getPassword())) {
                return null;
            }
    
            return $user;
    
        } catch(Exception $e) {
            error_log("User verification error: " . $e->getMessage());
            throw $e;
        }
    }

    public function getAllUsers(): array {
        return $this->userModel->getAllUsers();
    }
    
    public function updateUserStatus(int $userId, string $status): bool {
        return $this->userModel->updateUserStatus($userId, $status);
    }
}