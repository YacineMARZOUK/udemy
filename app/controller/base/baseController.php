<?php
require_once 'C:\xampp\htdocs\udemy\app\controller\impl\UserControllerimpl.php';
require_once 'C:\xampp\htdocs\udemy\app\controller\impl\Courcontrollerimpl.php';
require_once 'C:\xampp\htdocs\udemy\app\controller\impl\CategorieControllerimpl.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\User.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\Student.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\Teacher.php';
require_once 'C:\xampp\htdocs\udemy\app\enums\Role.php';

session_start();

$userController = new UserControllerimpl();
$Courcontroller = new Courcontrollerimpl();
$Categoriecontroller = new CategorieControllerimpl();

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
            header("Location: ../../user/login.php");
        } catch (Exception $e) {
            echo "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }

    if (isset($_POST['login'])) {
        // Assurez-vous que les champs email et mot de passe sont fournis
       echo 'ggg';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            echo $email ;
            echo "Email et mot de passe sont requis.";
            exit();
        }

        // Créez l'objet $person en utilisant les données du formulaire
       
       echo "<pre>";
       echo "</pre>";// Vous pouvez ajuster selon votre rôle
       try {
           // Appeler verifyUser pour vérifier si l'utilisateur existe
           
           $person = new User($email, $password, $name = '',Role::STUDENT); 
           echo $person->getEmail();
           var_dump($person);
            $userData = $userController->verifyUser( $person);
            echo "<br>".$userData->getRole();
            $person->setRole( $userData->getRole());
            if ($userData) {
                if($userData->getRole()==Role::STUDENT){
                    $_SESSION["user"] = $userData;
                
                    header('location:../../views/coursStudent.php');
                    exit();
                }elseif($userData->getRole()==Role::TEACHER){
                    $_SESSION["user"] = $userData;
                
                    header('location:../../views/cours.php');
                    exit();
                }else{
                    $_SESSION["user"] = $userData;
                
                    header('location:../../views/admin/addCategorie.php');
                    exit();
                }
                
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
    $idCategorie = $_POST["idCategorie"] ?? 0;
    
    if (empty($titre) || empty($description) || empty($contenu) || empty($idCategorie)) {
        echo "Tous les champs sont requis.";
        exit();
    }

    $cour = new Cour($titre, $description, $contenu, (int)$idCategorie, $images);

    try {
        $Courcontroller->addCour($cour);
        header("Location: ../../views/addcours.php");
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
        $description = $_POST['description'] ?? '';
        $contenu = $_POST['contenu'] ?? '';
        
        if ($courseId <= 0 || empty($titre) || empty($description)) {
            $_SESSION['error'] = "All fields are required";
            header("Location: ../../views/update_course.php?id=" . $courseId);
            exit();
        }
        
        try {
            $updatedCourse = new Cour($titre, $description, $contenu, 0, '');
            $updatedCourse->setId($courseId);
            
            $result = $Courcontroller->updateCour($updatedCourse);
            
            if ($result) {
                $_SESSION['success'] = "Course updated successfully";
                header("Location: ../../views/cours.php");
            } else {
                $_SESSION['error'] = "Failed to update course";
                header("Location: ../../views/update_course.php?id=" . $courseId);
            }
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "System error: " . $e->getMessage();
            header("Location: ../../views/update_course.php?id=" . $courseId);
            exit();
        }
    }



    if (isset($_POST["deleteStudent"])) {
      
    }


    if (isset($_POST['enrollCourse'])) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = "Please login to enroll in courses";
            header("Location: ../../user/login.php");
            exit();
        }
        
        $courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
        $userId = $_SESSION['user']->getId();
        
        if ($courseId <= 0) {
            $_SESSION['error_message'] = "Invalid course selection";
            header("Location: ../../views/cours.php");
            exit();
        }
        
        // Fix: Use correct controller variable name
        $Courcontroller = new Courcontrollerimpl();  // Make sure this matches your controller name
        $result = $Courcontroller->enrollUserInCourse($userId, $courseId);
        
        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }
        
        header("Location: ../../views/cours.php");
        exit();
    }
}

//*******************************************************************admin section********************************************** */


//*******************************************************************add categorie********************************************** */

if (isset($_POST["addCategory"])) {
    $titre = $_POST["titre"] ?? "";
    

    if (empty($titre)) {
        echo "le champs DE titre est requis.";
        exit();
    }

    $Categorie = new categories($titre);

    try {
        
        $Categoriecontroller->addCategorie($Categorie);
        echo "Le cours a été ajouté avec succès.";
    } catch (Exception $e) {
        echo "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
//************************************************************************   GET     ********************************************************* */
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
