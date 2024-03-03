<?php
include_once '../common/session.php';
include_once '../common/connection.php';
include_once '../common/function.php';

// Recupera la provincia selezionata dalla richiesta GET
$provincia = $_GET['provincia'];

// Esegui la query per ottenere le città della provincia selezionata
$sql = "SELECT nome FROM città WHERE provincia = '$provincia' ORDER BY nome ASC";
$result = mysqli_query($conn, $sql);

// Genera le opzioni del menu a tendina
$options = '<option value="">Seleziona una città</option>';
while ($row = mysqli_fetch_assoc($result)) {
    $options .= '<option value="' . $row['nome'] . '">' . $row['nome'] . '</option>';
}

echo $options;
?>
