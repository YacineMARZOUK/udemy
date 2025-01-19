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
                    Role::from($_POST["role"]) // Utilisation de la méthode from()
                );
            } else if ($person->getRole()=== Role::TEACHER) {
                $person = new Teacher(
                    $_POST["email"],
                    $_POST["password"],
                    $_POST["name"],
                    Role::from($_POST["role"])
                );
            }
            
            return $this->userModel->save($person);
        } catch(Exception $e) {
            error_log("Erreur lors de la sauvegarde de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function verifyUser(User $person) {
        var_dump($person);
        $email = $person->getEmail();
        try {
           $obj = $this->userModel->verifyEmail( $email);
            
            
            return ($obj);
        } catch(Exception $e) {
            error_log("Erreur lors de la vérification de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
}