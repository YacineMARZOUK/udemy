<?php

require_once __DIR__ . '/../enums/Role.php';

class User {
    protected int $id;
    protected string $name;
    protected string $email;
    protected string $password;
    protected $role;
    protected string $status;

    public function __construct(
        string $email, 
        string $password, 
        string $name, 
        $role, 
        string $status = 'active'
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->role = $role;
        $this->status = $status;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRole() {
        return $this->role;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setRole($role): void {
        $this->role = $role;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }
}
?>
