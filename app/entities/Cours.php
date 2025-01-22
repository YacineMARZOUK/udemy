<?php

class Cour {
    private int $id;
    private string $titre;
    private string $description;
    private string $video; // Changed from images
    private string $contenu;
    private int $idCategorie;
    private int $idTeacher;
    

    public function __construct(
        string $titre, 
        string $description, 
        string $contenu, 
        int $idCategorie, 
        int $idTeacher, // New parameter
        string $video = "default.mp4"
    ) {
        $this->titre = $titre;
        $this->description = $description;
        $this->contenu = $contenu;
        $this->video = $video;
        $this->idCategorie = $idCategorie;
        $this->idTeacher = $idTeacher;
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

    public function settitre(string $titre): void {
        $this->titre = $titre;
    }

    public function getdescription(): string {
        return $this->description;
    }

    public function setdescription(string $description): void {
        $this->description = $description;
    }

    public function getcontenu(): string {
        return $this->contenu;
    }

    public function setcontenu(string $contenu): void {
        $this->contenu = $contenu;
    }

    
    public function getIdCategorie(): int {
        return $this->idCategorie;
    }

    public function setIdCategorie(int $idCategorie): void {
        $this->idCategorie = $idCategorie;
    }
    public function getVideo(): string {
        return $this->video;
    }

    public function setVideo(string $video): void {
        $this->video = $video;
    }

    public function getIdTeacher(): int {
        return $this->idTeacher;
    }

    public function setIdTeacher(int $idTeacher): void {
        $this->idTeacher = $idTeacher;
    }
}
?>
