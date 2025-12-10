<?php
// app/controllers/RecipeController.php
class RecipeController {
    private $db;
    private $base;
    private $uploads_dir;
    private $uploads_url;

    public function __construct() {
        $this->db = db();
        $this->base = BASE_URL;
        $this->uploads_dir = UPLOADS_DIR;
        $this->uploads_url = UPLOADS_URL;
        if (!is_dir($this->uploads_dir)) mkdir($this->uploads_dir,0755,true);
    }

    private function ensureAuth() {
        if (empty($_SESSION['user'])) {
            $_SESSION['flash'] = 'Debes iniciar sesión';
            header("Location: {$this->base}/login");
            exit;
        }
    }

    public function list() {
        $q = $_GET['q'] ?? null;
        $category = $_GET['category'] ?? null;

        $sql = "SELECT r.*, u.name as author, ri.filename as image
                FROM recipes r
                LEFT JOIN users u ON u.id = r.user_id
                LEFT JOIN recipe_images ri ON ri.recipe_id = r.id AND ri.is_main = 1
                WHERE r.visibility = 'public'";

        $params = [];
        if ($q) {
            $sql .= " AND (r.title LIKE ? OR r.description LIKE ? OR r.ingredients LIKE ?)";
            $params = array_merge($params, ["%$q%","%$q%","%$q%"]);
        }
        if ($category) {
            $sql .= " AND r.category_id = ?";
            $params[] = $category;
        }
        $sql .= " ORDER BY r.id DESC LIMIT 200";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $recipes = $stmt->fetchAll();

        $cats = $this->db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/recipes/list.php';
        require __DIR__ . '/../views/partials/footer.php';
    }

    public function show() {
        $id = intval($_GET['id'] ?? 0);
        if (!$id) { header("Location: {$this->base}/recipes"); exit; }

        $stmt = $this->db->prepare("SELECT r.*, u.name as author FROM recipes r LEFT JOIN users u ON u.id = r.user_id WHERE r.id = ?");
        $stmt->execute([$id]);
        $recipe = $stmt->fetch();
        if (!$recipe) { header("Location: {$this->base}/recipes"); exit; }

        $images = $this->db->prepare("SELECT * FROM recipe_images WHERE recipe_id = ? ORDER BY is_main DESC, id ASC");
        $images->execute([$id]);
        $images = $images->fetchAll();

        $comments = $this->db->prepare("SELECT c.*, u.name as author FROM comments c LEFT JOIN users u ON u.id = c.user_id WHERE c.recipe_id = ? ORDER BY c.created_at DESC");
        $comments->execute([$id]);
        $comments = $comments->fetchAll();

        $isFav = false;
        if (!empty($_SESSION['user'])) {
            $s = $this->db->prepare("SELECT id FROM favorites WHERE user_id = ? AND recipe_id = ?");
            $s->execute([$_SESSION['user']['id'],$id]);
            $isFav = (bool)$s->fetch();
        }

        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/recipes/show.php';
        require __DIR__ . '/../views/partials/footer.php';
    }

    public function createView() {
        $this->ensureAuth();
        $cats = $this->db->query("SELECT * FROM categories ORDER BY name")->fetchAll();
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/recipes/create.php';
        require __DIR__ . '/../views/partials/footer.php';
    }

    public function save() {
        $this->ensureAuth();

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $ingredients = trim($_POST['ingredients'] ?? '');
        $steps = trim($_POST['steps'] ?? '');
        $category_id = $_POST['category_id'] ?: null;
        $user_id = $_SESSION['user']['id'];

        if (!$title) { $_SESSION['flash']='Título requerido'; header("Location: {$this->base}/recipe_create"); exit; }

        $slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $title)) . '-' . substr(md5(time()),0,6);
        $stmt = $this->db->prepare("INSERT INTO recipes (user_id,category_id,title,slug,description,ingredients,steps,created_at,visibility) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$user_id,$category_id,$title,$slug,$description,$ingredients,$steps,date('Y-m-d H:i:s'),'public']);
        $recipe_id = $this->db->lastInsertId();

        $this->handleImages($recipe_id);

        header("Location: {$this->base}/recipe?id={$recipe_id}");
    }

    private function handleImages($recipe_id) {
        if (empty($_FILES['images'])) return;
        $files = $_FILES['images'];
        for ($i=0;$i<count($files['name']);$i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            $tmp = $files['tmp_name'][$i];
            $size = $files['size'][$i];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo,$tmp);
            finfo_close($finfo);
            $allowed = ['image/jpeg','image/png','image/webp'];
            if (!in_array($mime,$allowed) || $size > 5*1024*1024) continue;
            $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $fname = bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($tmp, $this->uploads_dir . '/' . $fname);
            $is_main = ($i===0) ? 1 : 0;
            $stmt = $this->db->prepare("INSERT INTO recipe_images (recipe_id,filename,is_main,created_at) VALUES (?,?,?,?)");
            $stmt->execute([$recipe_id,$fname,$is_main,date('Y-m-d H:i:s')]);
        }
    }

    public function editView() {
        $this->ensureAuth();
        $id = intval($_GET['id'] ?? 0);
        if (!$id) { header("Location: {$this->base}/recipes"); exit; }
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE id = ?");
        $stmt->execute([$id]);
        $recipe = $stmt->fetch();
        if (!$recipe) { header("Location: {$this->base}/recipes"); exit; }
        if ($recipe['user_id'] != $_SESSION['user']['id'] && $_SESSION['user']['role_id'] != 2) {
            $_SESSION['flash']='No tiene permiso';
            header("Location: {$this->base}/recipes"); exit;
        }
        $cats = $this->db->query("SELECT * FROM categories ORDER BY name")->fetchAll();
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/recipes/edit.php';
        require __DIR__ . '/../views/partials/footer.php';
    }

    public function update() {
        $this->ensureAuth();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { header("Location: {$this->base}/recipes"); exit; }
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE id = ?");
        $stmt->execute([$id]);
        $recipe = $stmt->fetch();
        if (!$recipe) { header("Location: {$this->base}/recipes"); exit; }
        if ($recipe['user_id'] != $_SESSION['user']['id'] && $_SESSION['user']['role_id'] != 2) {
            $_SESSION['flash']='No tiene permiso';
            header("Location: {$this->base}/recipes"); exit;
        }
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $ingredients = trim($_POST['ingredients'] ?? '');
        $steps = trim($_POST['steps'] ?? '');
        $category_id = $_POST['category_id'] ?: null;
        $stmt = $this->db->prepare("UPDATE recipes SET title=?, description=?, ingredients=?, steps=?, category_id=?, updated_at=? WHERE id=?");
        $stmt->execute([$title,$description,$ingredients,$steps,$category_id,date('Y-m-d H:i:s'),$id]);
        $this->handleImages($id);
        header("Location: {$this->base}/recipe?id={$id}");
    }

    public function delete() {
        $this->ensureAuth();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { header("Location: {$this->base}/recipes"); exit; }
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE id = ?");
        $stmt->execute([$id]);
        $recipe = $stmt->fetch();
        if (!$recipe) { header("Location: {$this->base}/recipes"); exit; }
        if ($recipe['user_id'] != $_SESSION['user']['id'] && $_SESSION['user']['role_id'] != 2) {
            $_SESSION['flash']='No tiene permiso';
            header("Location: {$this->base}/recipes"); exit;
        }
        // delete images from disk
        $imgs = $this->db->prepare("SELECT * FROM recipe_images WHERE recipe_id = ?");
        $imgs->execute([$id]);
        foreach($imgs->fetchAll() as $im) {
            $f = $this->uploads_dir . '/' . $im['filename'];
            if (file_exists($f)) @unlink($f);
        }
        $this->db->prepare("DELETE FROM recipe_images WHERE recipe_id = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM favorites WHERE recipe_id = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM comments WHERE recipe_id = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM recipes WHERE id = ?")->execute([$id]);

        $_SESSION['flash']='Receta eliminada';
        header("Location: {$this->base}/recipes");
    }

    public function myRecipes() {
        $this->ensureAuth();
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$_SESSION['user']['id']]);
        $recipes = $stmt->fetchAll();
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/recipes/myrecipes.php';
        require __DIR__ . '/../views/partials/footer.php';
    }

    public function favoritesView() {
        $this->ensureAuth();
        $stmt = $this->db->prepare("SELECT r.*, u.name as author, ri.filename as image FROM favorites f JOIN recipes r ON r.id = f.recipe_id LEFT JOIN users u ON u.id = r.user_id LEFT JOIN recipe_images ri ON ri.recipe_id = r.id AND ri.is_main = 1 WHERE f.user_id = ? ORDER BY f.created_at DESC");
        $stmt->execute([$_SESSION['user']['id']]);
        $recipes = $stmt->fetchAll();
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/recipes/favorites.php';
        require __DIR__ . '/../views/partials/footer.php';
    }

    public function toggleFavorite() {
        $this->ensureAuth();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { header("Location: {$this->base}/recipes"); exit; }
        $s = $this->db->prepare("SELECT id FROM favorites WHERE user_id = ? AND recipe_id = ?");
        $s->execute([$_SESSION['user']['id'],$id]);
        $row = $s->fetch();
        if ($row) {
            $this->db->prepare("DELETE FROM favorites WHERE id = ?")->execute([$row['id']]);
            $_SESSION['flash']='Eliminado de favoritos';
        } else {
            $this->db->prepare("INSERT INTO favorites (user_id,recipe_id,created_at) VALUES (?,?,?)")->execute([$_SESSION['user']['id'],$id,date('Y-m-d H:i:s')]);
            $_SESSION['flash']='Agregado a favoritos';
        }
        header("Location: {$this->base}/recipe?id={$id}");
    }
}
