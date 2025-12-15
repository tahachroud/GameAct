<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/UserModel.php';

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

    // --- Controller-style methods for dashboard UI (compat with previous userzcontroller)
    public function index()
    {
        $userModel = new UserModel($this->db);
        $users = $userModel->getAll();

        ob_start();
        include __DIR__ . '/../view/users/list.php';
        $content = ob_get_clean();
        include __DIR__ . '/../view/layout.php';
    }

    public function createForm()
    {
        ob_start();
        include __DIR__ . '/../view/users/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../view/layout.php';
    }

    public function create()
    {
        $userModel = new UserModel($this->db);

        $avatar = null;
        if (!empty($_FILES['avatar']['name'])) {
            $folder = __DIR__ . '/../public/uploads/users/';
            if (!is_dir($folder)) mkdir($folder, 0777, true);

            $avatar = time() . "_" . $_FILES['avatar']['name'];
            move_uploaded_file($_FILES['avatar']['tmp_name'], $folder . $avatar);
        }

        $userModel->create([
            'username' => $_POST['username'] ?? null,
            'level'    => $_POST['level'] ?? null,
            'xp'       => $_POST['xp'] ?? null,
            'badges'   => $_POST['badges'] ?? null,
            'avatar'   => $avatar
        ]);

        header("Location: index-community.php?action=users");
        exit();
    }

    public function editForm()
    {
        $userModel = new UserModel($this->db);
        $data = $userModel->find($_GET['id']);

        ob_start();
        include __DIR__ . '/../view/users/edit.php';
        $content = ob_get_clean();
        include __DIR__ . '/../view/layout.php';
    }

    public function update()
    {
        $userModel = new UserModel($this->db);
        $old = $userModel->find($_POST['id']);

        $avatar = $old['avatar'] ?? null;

        if (!empty($_FILES['avatar']['name'])) {
            $folder = __DIR__ . '/../public/uploads/users/';
            $avatar = time() . "_" . $_FILES['avatar']['name'];
            move_uploaded_file($_FILES['avatar']['tmp_name'], $folder . $avatar);
        }

        $userModel->update([
            'id'       => $_POST['id'],
            'username' => $_POST['username'],
            'level'    => $_POST['level'],
            'xp'       => $_POST['xp'],
            'badges'   => $_POST['badges'],
            'avatar'   => $avatar
        ]);

        header("Location: index-community.php?action=users");
        exit();
    }

    public function delete()
    {
        $userModel = new UserModel($this->db);
        $userModel->delete($_GET['id']);

        header("Location: index-community.php?action=users");
        exit();
    }

    // Return a DB-backed default user id (delegates to UserModel)
    public function getDefaultUserId(): ?int
    {
        $userModel = new UserModel($this->db);
        return $userModel->getDefaultUserId();
    }
}

?>