<?php
require_once 'C:\xampp\htdocs\udemy\app\controller\impl\UserControllerimpl.php';
require_once 'C:\xampp\htdocs\udemy\app\controller\impl\Courcontrollerimpl.php';
require_once 'C:\xampp\htdocs\udemy\app\controller\impl\CategorieControllerimpl.php';
require_once 'C:\xampp\htdocs\udemy\app\controller\impl\TagControllerimpl.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\User.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\Student.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\Teacher.php';
require_once 'C:\xampp\htdocs\udemy\app\entities\Tags.php';
require_once 'C:\xampp\htdocs\udemy\app\enums\Role.php';

session_start();

$userController = new UserControllerimpl();
$Courcontroller = new Courcontrollerimpl();
$Categoriecontroller = new CategorieControllerimpl();
$TagController = new TagControllerimpl();

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
    
        try {
            if ($role === "student") {
                $role = Role::from($role);
                $status = 'active'; // Students are active by default
                $person = new Student($email, $hashed_password, $name, $role, $status);
            } elseif ($role === "teacher") {
                $role = Role::from($role);
                $status = 'pending'; // Teachers need approval
                $person = new Teacher($email, $hashed_password, $name, $role, $status);
            } else {
                echo "Type d'utilisateur invalide.";
                exit();
            }
    
            $result = $userController->save($person);
            
            if ($result) {
                $_SESSION['success_message'] = "Registration successful!";
                if ($role === Role::TEACHER) {
                    $_SESSION['info_message'] = "Your account is pending approval.";
                }
                header("Location: ../../user/login.php");
            } else {
                echo "Error during registration.";
            }
        } catch (Exception $e) {
            echo "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }

    if (isset($_POST['login'])) {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Email and password are required.";
            header("Location: ../../user/login.php");
            exit();
        }
    
        try {
            // Create a basic user object for verification
            $person = new User($email, $password, '', Role::STUDENT, 'active');
            
            // Get user data including status
            $userData = $userController->verifyUser($person);
            
            if (!$userData) {
                $_SESSION['error'] = "Invalid email or password.";
                header("Location: ../../user/login.php");
                exit();
            }
    
            $status = $userData->getStatus();
            
            switch($status) {
                case 'block':
                    $_SESSION['error'] = "Your account has been blocked. Please contact support.";
                    header("Location: ../../user/login.php");
                    exit();
                
                case 'pending':
                    $_SESSION['error'] = "Your account is pending approval. Please wait for administrator confirmation.";
                    header("Location: ../../user/login.php");
                    exit();
                
                case 'active':
                    // Set session data
                    $_SESSION["user"] = $userData;
                    
                    // Redirect based on role
                    switch($userData->getRole()) {
                        case Role::STUDENT:
                            header('location: ../../views/coursStudent.php');
                            break;
                        case Role::TEACHER:
                            header('location: ../../views/cours.php');
                            break;
                        case Role::ADMIN:
                            header('location: ../../views/admin/addCategorie.php');
                            break;
                        default:
                            $_SESSION['error'] = "Invalid role assigned.";
                            header("Location: ../../user/login.php");
                    }
                    exit();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "An error occurred during login. Please try again.";
            error_log("Login error: " . $e->getMessage());
            header("Location: ../../user/login.php");
            exit();
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
        echo "ana";
        $searchTerm = $_POST["search"] ?? "";
        
        if (!empty($searchTerm)) {
            $searchResults = $Courcontroller->searchCour($searchTerm);
            print_r($searchResults);
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



    if (isset($_POST['enrollCourse'])) {
    // Debug the incoming data
    error_log('POST data: ' . print_r($_POST, true));
    
    $courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : null;
    error_log('Course ID: ' . $courseId);
    
    if (empty($courseId) || $courseId <= 0) {
        $_SESSION['error_message'] = "Invalid course selection";
        header("Location: ../../views/cours.php");
        exit();
    }
    
    $Courcontroller = new Courcontrollerimpl();
    $result = $Courcontroller->getCourseById($courseId);
    
    if ($result) {
        // Log successful course retrieval
        error_log('Course found: ' . print_r($result, true));
        header("Location: ../../views/detailsCours.php?id=" . $courseId);
    } else {
        $_SESSION['error_message'] = "Course not found";
        header("Location: ../../views/cours.php");
    }
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
        header("Location: ../../views/admin/addCategorie.php");
        } catch (Exception $e) {
        echo "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}

//*******************************************************************add categorie********************************************** */

if(isset($_POST["addTag"])){
    $tagName = $_POST["tagName"] ?? "";

    if(empty($tagName)){
        echo "le champs de tag est requis.";
        exit();
    }
    $Tag = new Tag($tagName);
    try {
        $TagController->addTag($Tag);
        header("Location: ../../views/admin/addCategorie.php");


    } catch (Exception $e) {
        echo "Erreur lors de l'enregistrement de Tag : " . $e->getMessage();    }
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
