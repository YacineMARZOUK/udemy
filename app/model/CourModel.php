<?php

  interface CourModel
{
    public function addCour(Cour $cour): bool;

    public function deleteCour(int $id ): void;

    public function updateCour($course);

    public function searchCour(string $titre): array;

    public function countCour(): int;

    public function getAllCours(): array ;
    public function getCourseById($id);


}
?>