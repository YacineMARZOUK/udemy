<?php
require_once __DIR__ . '/User.php';
  class Student extends User {

      // private string  $datebirdth;


       public function __construct(string $email, string $password, string $name  , Role $role ) {
          parent::__construct( $email,  $password,  $name  ,  $role );
            

       }


//   public function setdatebirdth( string $datebirdth) : void{
//     $this->datebirdth=$datebirdth;
//  }

   
//    public function getdatebirdth(): string {
//       return $this->datebirdth;
//    }
}

?>