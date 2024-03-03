<?php
include_once '../common/session.php';
include_once("../common/connection.php");
include_once("../common/function.php");

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    // Ottieni i dati dal database in base al filtro selezionato
    $dropdownData = getDropdownData($conn, $filter);

    // restituisci le opzioni come JSON
    echo json_encode($dropdownData);

}
?>
