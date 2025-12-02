<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/user.php';

class userController {

    private $db;

    public function __construct(){
        $this->db = config::getConnexion();
    }

    public function listUsers() {
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser(User $u){
        $sql = "INSERT INTO users (name, lastname, email, password, cin, gender, location, age, role)
                VALUES(:name, :lastname, :email, :password, :cin, :gender, :location, :age, :role)";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
            ":name"     => $u->getName(),
            ":lastname" => $u->getLastname(),
            ":email"    => $u->getEmail(),
            ":password" => $u->getPassword(),
            ":cin"      => $u->getCin(),
            ":gender"   => $u->getGender(),
            ":location" => $u->getLocation(),
            ":age"      => $u->getAge(),
            ":role"     => $u->getRole()
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }
    
    public function updateUser(User $u) {
        $sql = "UPDATE users SET name=:name, lastname=:lastname, email=:email, password=:password,
                cin=:cin, gender=:gender, location=:location, age=:age, role=:role WHERE id=:id";
        
        $db = config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $query->execute([
            ":id"       => $u->getId(),
            ":name"     => $u->getName(),
            ":lastname" => $u->getLastname(),
            ":email"    => $u->getEmail(),
            ":password" => $u->getPassword(),
            ":cin"      => $u->getCin(),
            ":gender"   => $u->getGender(),
            ":location" => $u->getLocation(),
            ":age"      => $u->getAge(),
            ":role"     => $u->getRole()
        ]);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);

        try {
            $query->execute([":id" => $id]);
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $db = config::getConnexion();
        $query = $db->prepare($sql);

        try {
            $query->execute([":email" => $email]);
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getUserByPassword($password) {
        $sql = "SELECT * FROM users WHERE password = :password";
        $db = config::getConnexion();
        $query = $db->prepare($sql);

        try {
            $query->execute([":password" => $password]);
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getUserByCin($cin) {
        $sql = "SELECT * FROM users WHERE cin = :cin";
        $db = config::getConnexion();
        $query = $db->prepare($sql);

        try {
            $query->execute([":cin" => $cin]);
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}

?>