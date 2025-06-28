<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$port = 3306;
$dbname = "web1db";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>