<?php

class Categories {
    private $id;
    private $titre;

    public function __construct($titre) {
        $this->titre = $titre;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }
}
?>