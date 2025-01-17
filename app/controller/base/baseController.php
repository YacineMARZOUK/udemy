<?php
require_once 'C:xampp\htdocs\udemy\app\controller\impl\UserControllerimpl.php';
require_once 'C:\xampp\htdocs\udemy\app\controller\impl\Courcontrollerimpl.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\User.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\Student.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\Teacher.php';
require_once 'C:\xampp\htdocs\udemy\app\enums\Role.php';

session_start();

$userController = new UserControllerimpl();
$Courcontroller = new Courcontrollerimpl();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    ///////////  UserProcess   //////////

    if (isset($_POST["register"])) {
        $role = $_POST["role"] ?? "";
        $name = $_POST["name"] ?? "";
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if (empty($role) || empty($name) || empty($email) || empty($password)) {
            echo "Tous les champs sont requis.";
            exit();
        }

        if ($role === "student") {
            $role = Role::from($role); 
            $person = new Student($email, $hashed_password, $name, $role);
        } elseif ($role === "teacher") {
            $role = Role::from($role); 
            $person = new Teacher($email, $hashed_password, $name, $role);
        } else {
            echo "Type d'utilisateur invalide.";
            exit();
        }

        $person->setName($name);
        $person->setEmail($email);
        $person->setPassword($hashed_password);

        try {
            $userController->save($person);
            echo "Utilisateur enregistré avec succès.";
        } catch (Exception $e) {
            echo "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }

    if (isset($_POST['login'])) {
        // Assurez-vous que les champs email et mot de passe sont fournis
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            echo "Email et mot de passe sont requis.";
            exit();
        }

        // Créez l'objet $person en utilisant les données du formulaire
        $person = new User($email, $password, $name = '', Role::STUDENT); // Vous pouvez ajuster selon votre rôle
        try {
            // Appeler verifyUser pour vérifier si l'utilisateur existe
            $userData = $userController->verifyUser($person);

            if ($userData) {
                $_SESSION["user"] = $userData;
                require_once("../../../index.php");
                exit();
            } else {
                // Email ou mot de passe incorrect
                echo "Email ou mot de passe incorrect.";
            }
        } catch (Exception $e) {
            echo "Erreur lors de la vérification : " . $e->getMessage();
        }
    }

    //*****************************************************add cour****************************************************************
    if (isset($_POST["addCour"])) {
        $titre = $_POST["titre"] ?? "";
        $description = $_POST["description"] ?? "";
        $images = $_POST["images"] ?? "";
        $contenu = $_POST["contenu"] ?? "";

        if (empty($titre) || empty($description) || empty($images) || empty($contenu)) {
            echo "Tous les champs sont requis.";
            exit();
        }

        $cour = new Cour($titre, $description, $images, $contenu);

        try {
            $Courcontroller->addCour($cour);
            echo "Le cours a été ajouté avec succès.";
        } catch (Exception $e) {
            echo "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }

    //*****************************************************search cours****************************************************************
    if (isset($_POST["search"])) {
        $searchTerm = $_POST["search"] ?? "";

        if (!empty($searchTerm)) {
            $searchResults = $Courcontroller->searchCour($searchTerm);

            $_SESSION['searchResults'] = $searchResults;

            header("Location: ../../views/coursStudent.php");
            exit();
        } else {
            header("Location: ../../views/coursStudent.php");
            exit();
        }
    }

    //*****************************************************delet cours****************************************************************
    if (isset($_POST['deletCour'])) {
        $course_id = $_POST['deletCour'];
        $Courcontroller->deleteCour($course_id);
        header("Location: ../../views/cours.php");
    }

      //*****************************************************update cours****************************************************************
      if (isset($_POST['updateCourseSubmit'])) {
        $courseId = isset($_POST['courseId']) ? (int)$_POST['courseId'] : 0;
        $titre = $_POST['titre'] ?? '';
        $description = $_POST['description'] ?? ''; // Add other fields as needed
        
        if ($courseId <= 0 || empty($titre)) {
            header("Location: ../../views/cours.php?error=invalid_input");
            exit();
        }
        
        try {
            $updatedCourse = new Cour($titre, $description, '', ''); // Adjust constructor parameters as needed
            $updatedCourse->setId($courseId);
            
            $result = $Courcontroller->updateCour($updatedCourse);
            if ($result) {
                header("Location: ../../views/cours.php?success=updated");
            } else {
                header("Location: ../../views/cours.php?error=update_failed");
            }
            exit();
        } catch (Exception $e) {
            error_log("Error updating course: " . $e->getMessage());
            header("Location: ../../views/cours.php?error=system_error");
            exit();
        }
    }



    if (isset($_POST["deleteStudent"])) {
      
    }
}


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["getUsers"])) {
        // Supposé $categcontroller et sa méthode getCatgeories()
        $categcontroller = new CategoryController();
        $allcateg = $categcontroller->getCatgeories();

        // session set $allcateg
        $_SESSION['allCategories'] = $allcateg;

        // Redirection après la récupération des catégories
        header("Location: view/addcours");
    }
}
?>
