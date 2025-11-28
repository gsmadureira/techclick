<?php
// config.php
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('BASE', 'techclick'); // <--- Atualize aqui para 'techclick'

$conn = new mysqli(HOST, USER, PASS, BASE);
// ... resto do código
?>