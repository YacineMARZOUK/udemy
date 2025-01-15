<?php
require_once __DIR__ . '/User.php';

  class Teacher extends User {
      
     


       public function __construct(string $email, string $password, string $name , Role $role ) {
          parent::__construct( $email,  $password,  $name  , $role );
             

       }

  
//   public function setbio( string $bio) : void {
//     $this->bio=$bio;
//  }

   
//    public function getbio(): string {
//       return $this->bio;
//    }

}

?>