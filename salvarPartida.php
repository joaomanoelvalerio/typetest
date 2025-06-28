<?php
session_start();
header('Content-Type: application/json');
require_once 'src/db.php';

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
    exit;
}

$idusuario = (int)$_SESSION['idusuario'];

$idliga = isset($_POST['idliga']) ? (int)$_POST['idliga'] : 0;
$pontuacao = isset($_POST['pontuacao']) ? (int)$_POST['pontuacao'] : null;

if ($idliga <= 0 || $pontuacao === null) {
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos.']);
    exit;
}

$sqlInserir = "INSERT INTO partidas (idusuario, idliga, pontuacao, data_partida) VALUES (?, ?, ?, NOW())";
$stmtInserir = $conn->prepare($sqlInserir);

if (!$stmtInserir) {
    echo json_encode(['status' => 'error', 'message' => 'Erro na preparação da query: ' . $conn->error]);
    exit;
}

$stmtInserir->bind_param("iii", $idusuario, $idliga, $pontuacao);

if ($stmtInserir->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Pontuação salva com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar pontuação: ' . $stmtInserir->error]);
}

$stmtInserir->close();
$conn->close();
