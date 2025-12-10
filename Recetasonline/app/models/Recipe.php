<?php
class Recipe {
    public static function allPublic() {
        global $pdo;
        return $pdo->query("SELECT * FROM recipes WHERE visibility='public' ORDER BY id DESC")->fetchAll();
    }
}
