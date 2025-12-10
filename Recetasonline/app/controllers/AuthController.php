<?php
// app/controllers/AuthController.php
class AuthController {

    private $db;
    private $base;

    public function __construct() {
        $this->db = db();
        $this->base = BASE_URL;
    }

    public function loginView() {
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/auth/login.php';
        require __DIR__ . '/../views/partials/footer.php';
    }

    public function loginPost() {
        $email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
        $pwd = $_POST['password'] ?? '';

        if (!$email || !$pwd) {
            $_SESSION['flash']='Datos inválidos';
            header("Location: {$this->base}/login");
            exit;
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($pwd, $user['password'])) {
            $_SESSION['flash'] = 'Credenciales incorrectas';
            header("Location: {$this->base}/login");
            exit;
        }

        unset($user['password']);
        $_SESSION['user'] = $user;
        $_SESSION['flash'] = 'Bienvenido ' . htmlspecialchars($user['name']);
        header("Location: {$this->base}/recipes");
    }

    public function registerView() {
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/auth/register.php';
        require __DIR__ . '/../views/partials/footer.php';
    }

    public function registerPost() {
        $name = trim($_POST['name'] ?? '');
        $email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
        $pwd = $_POST['password'] ?? '';

        if (!$name || !$email || strlen($pwd) < 6) {
            $_SESSION['flash'] = 'Completa los campos correctamente (contraseña min 6)';
            header("Location: " . BASE_URL . "/register");
            exit;
        }

        // check exist
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['flash'] = 'Email ya registrado';
            header("Location: " . BASE_URL . "/register");
            exit;
        }

        $hash = password_hash($pwd, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (role_id,name,email,password,created_at) VALUES (?,?,?,?,?)");
        $stmt->execute([1,$name,$email,$hash,date('Y-m-d H:i:s')]);

        $_SESSION['flash'] = 'Cuenta creada. Inicia sesión';
        header("Location: " . BASE_URL . "/login");
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "/login");
    }
}
