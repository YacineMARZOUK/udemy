<?php
class Tag {
    private int $id;
    private  string $nom;

      public function __construct( string $nom){
        $this->nom=$nom;
      }
      public function getId(): int {  
        return $this->id;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function getnom(): string {  
        return $this->nom;
    }
    public function setnom(string $nom): void {
        $this->nom = $nom;
    }


}
?>