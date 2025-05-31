<?php
function verifica_Campo($campo){
  $campo = trim($campo);
  $campo = stripslashes($campo);
  $campo = htmlspecialchars($campo);
  return $campo;
}
?>

<?php
function verifica_Nulo($campo) {
  if (empty($_POST[$campo])) {
    return "O campo $campo é obrigatório.";
  }
  return "";
}
?>