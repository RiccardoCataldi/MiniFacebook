<?php
include_once '../common/session.php';
include_once '../common/connection.php';
include_once '../common/function.php';

// Esegui la query per ottenere tutte le nazioni
$sql = "SELECT DISTINCT nazione FROM città ORDER BY nazione ASC";
$result = mysqli_query($conn, $sql);

// Genera le opzioni del menu a tendina
$options = '<option value="">Seleziona una nazione</option>';
while ($row = mysqli_fetch_assoc($result)) {
    $options .= '<option value="' . $row['nazione'] . '">' . $row['nazione'] . '</option>';
}

echo $options;
?>
