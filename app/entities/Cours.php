<?php
   
   class Cour {
    private int $id;
    private string $titre;
    private string $description;
    private string $images;
    private string $contenu;
    
     public function __construct($titre,$description,$contenu){
         $this->titre=$titre;
        $this->description=$description;
         $this->contenu=$contenu;
     }
     public function getId(): int {  
        return $this->id;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }
     public function gettitre(): string {
         return $this->titre;
     }
     public function settitre( string $titre): void  {
         $this->titre=$titre;
    }
    public  function getdescription(): string {
        return $this->description;
    }
    public  function setdescription( string $description): void {
        $this->titre=$description;
   }
   public  function getcontenu(): string {
    return $this->contenu;
   }
   public  function setcontenu( string $contenu): void {
    $this->contenu=$contenu;
   }
   public  function getimages(): string {
    return $this->images;
   }
   public  function setimage( string $images): void {
    $this->images=$images;
   }


   }


















?>