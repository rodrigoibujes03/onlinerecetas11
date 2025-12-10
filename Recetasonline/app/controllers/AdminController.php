<?php
// app/controllers/AdminController.php
class AdminController {
    private $db;
    private $base;
    public function __construct() {
        $this->db = db();
        $this->base = BASE_URL;
    }

    private function ensureAdmin() {
        if (empty($_SESSION['user']) || $_SESSION['user']['role_id'] != 2) {
            $_SESSION['flash']='Acceso denegado';
            header("Location: {$this->base}/recipes");
            exit;
        }
    }

    public function users() {
        $this->ensureAdmin();
        $users = $this->db->query("SELECT u.*, r.name as role_name FROM users u LEFT JOIN roles r ON r.id = u.role_id ORDER BY u.id DESC")->fetchAll();
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/admin/users.php';
        require __DIR__ . '/../views/partials/footer.php';
    }

    public function deleteUser() {
        $this->ensureAdmin();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { header("Location: {$this->base}/admin_users"); exit; }
        $this->db->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
        $_SESSION['flash']='Usuario eliminado';
        header("Location: {$this->base}/admin_users");
    }
}
