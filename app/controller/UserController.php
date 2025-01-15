<?php

interface UserController {
    public function save(): bool;
     public function verifyUser(User $user): array|bool;

}

?>