<?php
require_once __DIR__ . '/../config.php';
class User {
    private $id;
    private $name;
    private $lastname;
    private $email;
    private $password;
    private $cin;
    private $gender;
    private $location;
    private $age;
    private $role;

    public function __construct(
        $id,
        $name,
        $lastname,
        $email,
        $password,
        $cin,
        $gender,
        $location,
        $age,
        $role,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->cin = $cin;
        $this->gender = $gender;
        $this->location = $location;
        $this->age = $age;
        $this->role = $role;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function getLastname(): ?string {
        return $this->lastname;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function getCin(): ?int {
        return $this->cin;
    }

    public function getGender(): ?string {
        return $this->gender;
    }

        public function getAge(): ?int {
        return $this->age;
    }

    public function getLocation(): ?string {
        return $this->location;
    }

    public function getRole(): ?string {
        return $this->role;
    }
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setName(?string $name): void {
        $this->name = $name; 
    }
    public function setLastname(?string $lastname): void { 
        $this->lastname = $lastname; 
    }
    public function setEmail(?string $email): void {
        $this->email = $email;
    }
    public function setPassword(?string $password): void {
        $this->password = $password;
    }
    public function setCin(?int $cin): void {
        $this->cin = $cin;
    }
    public function setGender(?string $gender): void {
        $this->gender = $gender;
    }
    public function setAge(?int $age): void {
        $this->age = $age;
    }
    public function setLocation(?string $location): void {
        $this->location = $location;
    }
    public function setRole(?string $role): void {
        $this->role = $role;
    }
}
?>