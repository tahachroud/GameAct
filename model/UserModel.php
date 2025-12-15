<?php
require_once __DIR__ . '/../config.php';

class UserModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $sql = "SELECT id, name, lastname, email, cin, gender, location, age, role
                FROM users";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT id, name, lastname, email, cin, gender, location, age, role
                FROM users
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    // Return an existing user id to use as default when no session user is available
    public function getDefaultUserId(): ?int
    {
        // Prefer an admin if present
        $sql = "SELECT id FROM users WHERE role = 'admin' LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && isset($row['id'])) return (int)$row['id'];

        // Fallback to first user in table
        $sql = "SELECT id FROM users ORDER BY id ASC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'] ?? null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO users (name, lastname, email, password, cin, gender, location, age, role)
                VALUES (:name, :lastname, :email, :password, :cin, :gender, :location, :age, :role)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'] ?? null,
            ':lastname' => $data['lastname'] ?? null,
            ':email' => $data['email'] ?? null,
            ':password' => $data['password'] ?? null,
            ':cin' => $data['cin'] ?? null,
            ':gender' => $data['gender'] ?? null,
            ':location' => $data['location'] ?? null,
            ':age' => $data['age'] ?? null,
            ':role' => $data['role'] ?? null
        ]);
    }

    public function update(array $data): bool
    {
        $sql = "UPDATE users
                SET name = :name,
                    lastname = :lastname,
                    email = :email,
                    password = :password,
                    cin = :cin,
                    gender = :gender,
                    location = :location,
                    age = :age,
                    role = :role
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $data['id'] ?? null,
            ':name' => $data['name'] ?? null,
            ':lastname' => $data['lastname'] ?? null,
            ':email' => $data['email'] ?? null,
            ':password' => $data['password'] ?? null,
            ':cin' => $data['cin'] ?? null,
            ':gender' => $data['gender'] ?? null,
            ':location' => $data['location'] ?? null,
            ':age' => $data['age'] ?? null,
            ':role' => $data['role'] ?? null
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
