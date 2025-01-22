<?php
interface UserModel
{
    public function save(User $user): bool;

    public function fetchUsers(): array;

    public function verifyEmail(string $email) ;

    public function verifyUser(User $user);

    public function countUser(): int;

    public function getAllUsers(): array;

    public function updateUserStatus(int $userId, string $status): bool;
}
?>