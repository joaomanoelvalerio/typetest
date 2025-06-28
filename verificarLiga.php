<?php
session_start();
require_once 'src/db.php';

if (!isset($_GET['id'])) {
    echo "Liga inválida.";
    exit;
}

$idliga = (int)$_GET['id'];
$sql = "SELECT nomeliga FROM ligatable WHERE idliga = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idliga);
$stmt->execute();
$result = $stmt->get_result();
$liga = $result->fetch_assoc();

if (!$liga) {
    echo "Liga não encontrada.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Entrar na Liga - <?php echo htmlspecialchars($liga['nomeliga']); ?></title>
    <link rel="stylesheet" href="CSS/ligas.css" />
</head>
<body>

<div class="background"></div>
<div class="retangulo">
    <h1>Entrar na Liga: <?php echo htmlspecialchars($liga['nomeliga']); ?></h1>
    <form method="POST" action="entrarLiga.php">
        <input type="hidden" name="idliga" value="<?php echo $idliga; ?>">
        <label for="palavra_chave">Palavra-chave:</label>
        <input type="password" id="palavra_chave" name="palavra_chave" required>
        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>
