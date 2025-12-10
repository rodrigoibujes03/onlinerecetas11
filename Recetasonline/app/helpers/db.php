<?php
// app/helpers/db.php
require_once __DIR__ . '/../../config/config.php';

function db() {
    global $conn;
    return $conn;
}
