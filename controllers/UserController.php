<?php
require_once __DIR__ . '/../models/User.php';

class UserController {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // LIST
    public function index()
    {
        $user = new User($this->db);
        $users = $user->getAll();

        ob_start();
        include __DIR__ . '/../views/users/list.php';
        $content = ob_get_clean();

        include __DIR__ . '/../views/layout.php';
    }

    // CREATE FORM
    public function createForm()
    {
        ob_start();
        include __DIR__ . '/../views/users/create.php';
        $content = ob_get_clean();

        include __DIR__ . '/../views/layout.php';
    }

    // STORE (FIXED NAME)
    public function create()
    {
        $user = new User($this->db);

        $avatar = null;
        if (!empty($_FILES['avatar']['name'])) {
            $folder = __DIR__ . '/../public/uploads/users/';
            if (!is_dir($folder)) mkdir($folder, 0777, true);

            $avatar = time() . "_" . $_FILES['avatar']['name'];
            move_uploaded_file($_FILES['avatar']['tmp_name'], $folder . $avatar);
        }

        $user->create([
            'username' => $_POST['username'],
            'level'    => $_POST['level'],
            'xp'       => $_POST['xp'],
            'badges'   => $_POST['badges'],
            'avatar'   => $avatar
        ]);

        header("Location: index-community.php?action=users");
        exit();
    }

    // EDIT FORM
    public function editForm()
    {
        $user = new User($this->db);
        $data = $user->find($_GET['id']);

        ob_start();
        include __DIR__ . '/../views/users/edit.php';
        $content = ob_get_clean();

        include __DIR__ . '/../views/layout.php';
    }

    // UPDATE
    public function update()
    {
        $user = new User($this->db);
        $old = $user->find($_POST['id']);

        $avatar = $old['avatar'];

        if (!empty($_FILES['avatar']['name'])) {
            $folder = __DIR__ . '/../public/uploads/users/';
            $avatar = time() . "_" . $_FILES['avatar']['name'];
            move_uploaded_file($_FILES['avatar']['tmp_name'], $folder . $avatar);
        }

        $user->update([
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

    // DELETE
    public function delete()
    {
        $user = new User($this->db);
        $user->delete($_GET['id']);

        header("Location: index-community.php?action=users");
        exit();
    }
}
