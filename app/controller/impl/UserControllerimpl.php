<?php
require_once '../../entities/User.php';
require_once '../../entities/Student.php';
require_once '../../entities/Teacher.php';
require_once '../../enums/Role.php';
require_once '../../model/UserModel.php';
require_once '../../model/impl/UserModelimpl.php';

class UserControllerimpl
{

    private UserModelimpl $userModel;
    public function __construct()
    {
        $this->userModel = new UserModelimpl();
    }

    
    public function save(Student|Teacher $person): bool
    {
        try{
            if ($person->getRole()=='student'){
                $person= new Student($_POST["email"], $_POST["password"], $_POST["name"], Role::from($_POST["role"])) ;
    
            }
            else if ($person->getRole()=='teacher'){
                $person= new Teacher($_POST["email"], $_POST["password"], $_POST["name"], Role::from($_POST["role"])) ;
            }
            
               return  $this->userModel->save($person);
        }
        catch(Exception $e){   
            return false;

    }
    }
    public function verifyUser(User $person): array|bool
    {
        try{
            $person= new User($_POST["email"], $_POST["password"],'',Role::STUDENT) ;
               return  $this->userModel->verifyUser($person);
        }
        catch(Exception $e){   
            return false;

    }
       
    }
         
}


?>