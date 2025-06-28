<?php
function verifica_Campo($campo){
  $campo = trim($campo);
  $campo = stripslashes($campo);
  $campo = htmlspecialchars($campo);
  return $campo;
}

function verifica_Nulo($campo, &$existencia) {
  if (empty($campo)) {
    $existencia = false;
    return "Este campo é obrigatório.";
  }
  return "";
}
?>