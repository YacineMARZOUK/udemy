<?php

interface CategorieModel
{
    public function addCategorie( $categorie): bool;

    public function deleteCategorie(int $id): void;

    public function updateCategorie(Categorie $categorie): bool;

    public function countCategorie(): int;
    
    public function getAllCategories(): array;

}
?>