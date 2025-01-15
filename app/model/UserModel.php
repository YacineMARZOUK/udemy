<?php
interface UserModel
{
    public function save(User $user): bool;

    public function fetchUsers(): array;

    public function verifyEmail(string $email): bool ;

    public function verifyUser(User $user): array|bool;

    public function countUser(): int;
}
?>