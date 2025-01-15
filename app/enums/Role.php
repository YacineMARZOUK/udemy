<?php
// /enums/Role.php

class Role {
    // Définition des constantes de rôle
    public const STUDENT = "student";
    public const TEACHER = "teacher";
    public const ADMIN = "admin";
    
    private string $value;
    
    public function __construct(string $role) {
        $this->setValue($role);
    }
    
    public static function from(string $value): Role {
        return new self($value);
    }
    
    public function setValue(string $role): void {
        if (!in_array($role, [self::STUDENT, self::TEACHER, self::ADMIN])) {
            throw new InvalidArgumentException("Role invalide: " . $role);
        }
        $this->value = $role;
    }
    
    public function getValue(): string {
        return $this->value;
    }
    
    public function equals(Role $other): bool {
        return $this->value === $other->getValue();
    }
    
    public function __toString(): string {
        return $this->value;
    }
}