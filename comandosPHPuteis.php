<?php
// aumentar limite de tempo de execução em segundos

set_time_limit(200000);

// retirar conteúdo de debtro de qualquer tag HTML
$apenasConteudo = strip_tags($textocheiodehtml);


//banco de dados direto

$bdantigo = new mysqli("localhost", "root", "", "bdantigo");
$bdnovo = new mysqli("localhost", "root", "", "wordpress");

if ($bdnovo->connect_error || $bdantigo->connect_error) {
    echo "Not connected, error: " . $bdantigo->connect_error;
 }
 else {
    echo "Connected.";
 }

$sql = "select * from prestacao";
$conteudoBD = $bdantigo->query($sql, MYSQLI_USE_RESULT);

while($conteudo = $conteudoBD->fetch_array()){
}

?>