<?php
session_start();
require_once 'src/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

$idusuario = (int)$_SESSION['idusuario'];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['pontuacao'], $data['tempo'])) {
    echo json_encode(['erro' => 'Dados incompletos']);
    exit;
}

$pontuacao = (int)$data['pontuacao'];
$tempo = $data['tempo'];

// Busca todas as ligas do usuário
$sqlLigas = "SELECT idliga FROM usuario_liga WHERE idusuario = ?";
$stmtLigas = $conn->prepare($sqlLigas);
$stmtLigas->bind_param("i", $idusuario);
$stmtLigas->execute();
$resultLigas = $stmtLigas->get_result();

if ($resultLigas->num_rows === 0) {
    echo json_encode(['erro' => 'Usuário não está inscrito em nenhuma liga']);
    $stmtLigas->close();
    $conn->close();
    exit;
}

$stmtInsert = $conn->prepare("INSERT INTO partidas (idusuario, idliga, pontuacao) VALUES (?, ?, ?)");
$erroInsercao = false;

while ($liga = $resultLigas->fetch_assoc()) {
    $idliga = (int)$liga['idliga'];
    $stmtInsert->bind_param("iii", $idusuario, $idliga, $pontuacao);
    if (!$stmtInsert->execute()) {
        $erroInsercao = true;
        break;
    }
}

$stmtInsert->close();
$stmtLigas->close();
$conn->close();

if ($erroInsercao) {
    echo json_encode(['erro' => 'Erro ao salvar pontuação em alguma liga']);
} else {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Pontuação salva em todas as ligas!']);
}
