<?php

interface TagModel
{
    public function addTag(Tag $tag): bool;

    public function getAllTags(): array;
}

?>
