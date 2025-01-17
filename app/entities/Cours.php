<?php

class Cour {
    private int $id;
    private string $titre;
    private string $description;
    private string $images;
    private string $contenu;
    private int $idCategorie; // Added field

    public function __construct(string $titre, string $description, string $contenu, int $idCategorie, string $images = "default.jpg") {
        $this->titre = $titre;
        $this->description = $description;
        $this->contenu = $contenu;
        $this->images = $images;
        $this->idCategorie = $idCategorie;
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

    public function getimages(): string {
        return $this->images;
    }

    public function setimage(string $images): void {
        $this->images = $images;
    }
    public function getIdCategorie(): int {
        return $this->idCategorie;
    }

    public function setIdCategorie(int $idCategorie): void {
        $this->idCategorie = $idCategorie;
    }
}
?>
