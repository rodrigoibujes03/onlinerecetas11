<?php
// app/controllers/CommentController.php
class CommentController {
    private $db;
    private $base;
    public function __construct() {
        $this->db = db();
        $this->base = BASE_URL;
    }

    public function post() {
        // Endpoint para AJAX: recibe JSON { recipe_id, content }
        if (empty($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error'=>'no auth']);
            exit;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        if (!$payload) {
            http_response_code(400);
            echo json_encode(['error'=>'invalid payload']);
            exit;
        }

        $recipe_id = intval($payload['recipe_id'] ?? 0);
        $content = trim($payload['content'] ?? '');
        if (!$recipe_id || !$content) {
            http_response_code(400);
            echo json_encode(['error'=>'missing fields']);
            exit;
        }

        $stmt = $this->db->prepare("INSERT INTO comments (user_id,recipe_id,content,created_at) VALUES (?,?,?,?)");
        $stmt->execute([$_SESSION['user']['id'],$recipe_id,$content,date('Y-m-d H:i:s')]);

        echo json_encode(['ok'=>true]);
    }
}
