<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'MyFirstDatabase');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>
