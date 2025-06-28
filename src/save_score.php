<?php
session_start();
require_once 'db.php'; 

header('Content-Type: text/plain');

if (!isset($_SESSION['idusuario'])) {
    echo "Erro: Usuário não logado.";
    exit;
}

if (!isset($_POST['score'])) {
    echo "Erro: Pontuação não recebida.";
    exit;
}

$idUsuario = $_SESSION['idusuario'];
$pontuacao = intval($_POST['score']);

$stmt = $conn->prepare("INSERT INTO historicotable (idusuario, pontuacaoPartida, dataPartida) VALUES (?, ?, NOW())");

if ($stmt === false) {
    echo "Erro ao preparar a declaração: " . $conn->error;
    exit;
}

$stmt->bind_param("ii", $idUsuario, $pontuacao);

if ($stmt->execute()) {
    echo "Pontuação salva com sucesso!";
} else {
    echo "Erro ao salvar a pontuação: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>