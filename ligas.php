<?php
session_start();
require_once 'src/db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$itensPorPagina = 3;
$paginaAtual = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';

$offset = ($paginaAtual - 1) * $itensPorPagina;

$sqlCountBase = "SELECT COUNT(*) as total FROM ligatable";
$sqlBase = "SELECT idliga, nomeliga, criado_em FROM ligatable"; // pontosliga removido
$whereClauses = [];
$params = [];
$types = '';

if ($pesquisa !== '') {
    if (is_numeric($pesquisa)) {
        $whereClauses[] = "idliga = ?";
        $params[] = (int)$pesquisa;
        $types .= 'i';
    }
    $whereClauses[] = "nomeliga LIKE ?";
    $params[] = '%' . $pesquisa . '%';
    $types .= 's';
}

$filtroSQL = '';
if (!empty($whereClauses)) {
    $filtroSQL = " WHERE (" . implode(" OR ", $whereClauses) . ")";
}

$stmtCount = $conn->prepare($sqlCountBase . $filtroSQL);
if ($stmtCount === false) {
    $_SESSION['msg'] = "Erro interno ao preparar a busca por ligas (contagem).";
    header("Location: ligas.php");
    exit;
}
if (!empty($params)) {
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalRegistros = $resultCount ? (int)($resultCount->fetch_assoc()['total']) : 0;
$stmtCount->close();

$totalPaginas = ceil($totalRegistros / $itensPorPagina);

$sql = $sqlBase . $filtroSQL . " ORDER BY criado_em DESC LIMIT ? OFFSET ?";
$params[] = $itensPorPagina;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    $_SESSION['msg'] = "Erro interno ao preparar a busca por ligas.";
    header("Location: ligas.php");
    exit;
}
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Ligas</title>
  <link rel="stylesheet" href="CSS/ligas.css" />
</head>
<body>
<div class="background"></div>
<div class="retangulo">
  <?php if (isset($_SESSION['msg'])): ?>
    <p style="text-align:center; background: rgba(0,0,0,0.6); color:white; padding: 10px; border-radius: 5px;">
      <?php echo htmlspecialchars($_SESSION['msg']); unset($_SESSION['msg']); ?>
    </p>
  <?php endif; ?>
  <h1>Ligas</h1>
  <form method="GET" style="text-align: center; margin-bottom: 20px;">
    <input type="text" name="pesquisa" placeholder="Pesquisar por nome ou ID" value="<?php echo htmlspecialchars($pesquisa); ?>"/>
    <button type="submit">Buscar</button>
    <a href="ligas.php">Limpar</a>
  </form>
  <?php if ($totalRegistros > 0 && $result && $result->num_rows > 0): ?>
    <div class="tabela-container">
      <table class="tabela-clara">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Criada em</th>
            <th>Ação</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['idliga']); ?></td>
              <td><?php echo htmlspecialchars($row['nomeliga']); ?></td>
              <td><?php echo htmlspecialchars($row['criado_em']); ?></td>
              <td>
                <button type="button" onclick="abrirModal(<?php echo $row['idliga']; ?>)">Entrar na Liga</button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="paginacao" style="text-align: center; margin-top: 20px;">
      <?php if ($paginaAtual > 1): ?>
        <a href="?page=<?php echo $paginaAtual - 1; ?>&pesquisa=<?php echo urlencode($pesquisa); ?>">Anterior</a>
      <?php endif; ?>
      Página <?php echo $paginaAtual; ?> de <?php echo $totalPaginas; ?>
      <?php if ($paginaAtual < $totalPaginas): ?>
        <a href="?page=<?php echo $paginaAtual + 1; ?>&pesquisa=<?php echo urlencode($pesquisa); ?>">Próximo</a>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <p style="text-align: center;">Nenhuma liga encontrada.</p>
  <?php endif; ?>

  <div style="margin-top: 25px;">
    <button onclick="window.location.href='cadastroligass.php'">Nova Liga</button>
    <button onclick="window.location.href='TelaInicio.php'">Voltar</button>
  </div>
</div>

<!-- Modal para palavra-chave -->
<div id="modalPalavra" class="modal" style="display:none;">
  <div class="modal-content">
    <span id="fecharModal" class="close">&times;</span>
    <h2>Digite a palavra-chave da liga</h2>
    <form method="POST" action="entrarLiga.php">
      <input type="hidden" name="idliga" id="modalIdLiga">
      <input type="password" name="palavrachave" placeholder="Palavra-chave" required>
      <button type="submit">Entrar</button>
    </form>
  </div>
</div>

<script>
  const modal = document.getElementById('modalPalavra');
  const fecharBtn = document.getElementById('fecharModal');
  const modalIdLiga = document.getElementById('modalIdLiga');

  function abrirModal(idliga) {
    modal.style.display = 'flex';
    modalIdLiga.value = idliga;
  }

  fecharBtn.onclick = () => modal.style.display = 'none';

  window.onclick = function(e) {
    if (e.target == modal) modal.style.display = 'none';
  };
</script>

</body>
</html>

<?php $conn->close(); ?>
