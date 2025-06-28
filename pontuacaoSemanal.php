<?php
session_start();
require_once 'src/db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$idusuario = $_SESSION['idusuario'];

$pesquisa = isset($_GET['pesquisa']) ? trim($conn->real_escape_string($_GET['pesquisa'])) : '';
$filtroSQL = "";

if ($pesquisa !== '') {
    if (is_numeric($pesquisa)) {
        $filtroSQL = "AND (YEAR(criado_em) = $pesquisa OR pontuacaoPartida = $pesquisa)";
    } else {
        $filtroSQL = "";
    }
}

$itensPorPagina = 3;
$paginaAtual = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($paginaAtual - 1) * $itensPorPagina;

$sqlCount = "
    SELECT COUNT(DISTINCT YEAR(criado_em), WEEK(criado_em, 1)) AS total
    FROM historicotable
    WHERE idusuario = ? $filtroSQL
";
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->bind_param("i", $idusuario);
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalRegistros = $resultCount->fetch_assoc()['total'] ?? 0;
$totalPaginas = ceil($totalRegistros / $itensPorPagina);
$stmtCount->close();

$sql = "
    SELECT 
        YEAR(criado_em) AS ano,
        WEEK(criado_em, 1) AS semana_iso,
        SUM(pontuacaoPartida) AS total_pontos
    FROM historicotable
    WHERE idusuario = ? $filtroSQL
    GROUP BY ano, semana_iso
    ORDER BY ano, semana_iso
    LIMIT $itensPorPagina OFFSET $offset
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idusuario);
$stmt->execute();
$result = $stmt->get_result();
$semanas_relativas = [];
$contador = $offset + 1;

while ($row = $result->fetch_assoc()) {
    $chave = $row['ano'] . '-' . $row['semana_iso'];
    $semanas_relativas[$chave] = [
        'semana_usuario' => $contador,
        'total_pontos' => $row['total_pontos'],
        'ano' => $row['ano']
    ];
    $contador++;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Pontuação Semanal</title>
    <link rel="stylesheet" href="CSS/pontuacaoSemanal.css">
</head>
<body>
<div class="background"></div>
<div class="retangulo">
    <h1>Pontuação Semanal</h1>
    <form method="GET">
        <input type="text" name="pesquisa" id="barraPesquisa" placeholder="Filtrar" value="<?php echo htmlspecialchars($pesquisa); ?>"/>
        <button type="submit" id="botaoBuscar">Buscar</button>
        <a href="pontuacaoSemanal.php">Limpar</a>
    </form>

    <?php if (!empty($semanas_relativas)): ?>
        <div class="tabela-container">
            <table>
                <thead>
                    <tr>
                        <th>Semana Jogada</th>
                        <th>Ano</th>
                        <th>Total de Pontos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($semanas_relativas as $info): ?>
                        <tr>
                            <td><?php echo $info['semana_usuario']; ?></td>
                            <td><?php echo $info['ano']; ?></td>
                            <td><?php echo $info['total_pontos']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="paginacao">
            <?php if ($paginaAtual > 1): ?>
                <a href="?page=<?php echo $paginaAtual - 1; ?>&pesquisa=<?php echo urlencode($pesquisa); ?>" class="botaoPagina">Anterior</a>
            <?php endif; ?>
            Página <?php echo $paginaAtual; ?> de <?php echo $totalPaginas; ?>
            <?php if ($paginaAtual < $totalPaginas): ?>
                <a href="?page=<?php echo $paginaAtual + 1; ?>&pesquisa=<?php echo urlencode($pesquisa); ?>" class="botaoPagina">Próximo</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p class="mensagem-vazia">Você ainda não tem pontuação registrada.</p>
    <?php endif; ?>
    <div class="Botoes">
        <button id="BotaoVoltar" onclick="window.location.href='TelaInicio.php'">Voltar</button>
    </div>
</div>
</body>
</html>

<?php $conn->close(); ?>
