<?php
include_once '../common/session.php';
include_once '../common/connection.php';
include_once '../common/function.php';

// Recupera la nazione selezionata dalla richiesta GET
$nazione = $_GET['nazione'];

// Esegui la query per ottenere le province della nazione selezionata
$sql = "SELECT DISTINCT provincia FROM città WHERE nazione = '$nazione' ORDER BY provincia ASC";
$result = mysqli_query($conn, $sql);

// Genera le opzioni del menu a tendina
$options = '<option value="">Seleziona una provincia</option>';
while ($row = mysqli_fetch_assoc($result)) {
    $options .= '<option value="' . $row['provincia'] . '">' . $row['provincia'] . '</option>';
}

echo $options;
?>
