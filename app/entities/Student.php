<?php
require_once __DIR__ . '/User.php';

class Student extends User {
   public function __construct(
       string $email, 
       string $password, 
       string $name, 
       $role, 
       string $status = 'active'
   ) {
       parent::__construct($email, $password, $name, $role, $status);
   }
}

?>
