<?php
class User {
    public static function find($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id,name,email,role_id,avatar FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
