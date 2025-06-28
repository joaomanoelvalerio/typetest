<?php
$servername = "localhost";
$username = "root";
$password = "";
$port = 3306;
$dbname = "web1db";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    if (strpos($conn->connect_error, 'Unknown database') !== false) {
        $conn = new mysqli($servername, $username, $password, "", $port);
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }
        if (!$conn->query("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
            die("Erro ao criar banco: " . $conn->error);
        }
        $conn->select_db($dbname);
    } else {
        die("Conexão falhou: " . $conn->connect_error);
    }
}

$tables = [
    "ligatable" => "CREATE TABLE IF NOT EXISTS ligatable (
        idliga INT AUTO_INCREMENT PRIMARY KEY,
        nomeliga VARCHAR(100) NOT NULL,
        pontosliga INT DEFAULT 0,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "cadastrotable" => "CREATE TABLE IF NOT EXISTS cadastrotable (
        idusuario INT AUTO_INCREMENT PRIMARY KEY,
        nomeusuario VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        idliga INT DEFAULT NULL,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (idliga) REFERENCES ligatable(idliga)
            ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "historicotable" => "CREATE TABLE IF NOT EXISTS historicotable (
        idhistorico INT AUTO_INCREMENT PRIMARY KEY,
        idusuario INT NOT NULL,
        idpartida INT NOT NULL,
        tempo_jogo TIME NOT NULL,
        pontuacaoPartida INT DEFAULT 0,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (idusuario) REFERENCES cadastrotable(idusuario)
            ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

foreach ($tables as $sql) {
    $conn->query($sql);
}

$colCheck = $conn->query("SHOW COLUMNS FROM ligatable LIKE 'palavrachave'");
if ($colCheck && $colCheck->num_rows === 0) {
    $alter = "ALTER TABLE ligatable ADD palavrachave VARCHAR(30) NOT NULL DEFAULT ''";
    if (!$conn->query($alter)) {
        die("Erro ao adicionar coluna palavrachave: " . $conn->error);
    }
}

$conn->close();
?>
