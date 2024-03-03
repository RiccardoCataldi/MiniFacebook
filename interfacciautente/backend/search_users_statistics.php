<?php 
include_once("../common/connection.php");
include_once("../common/function.php");
include_once("../common/session.php");

$loggedInUserEmail = $_SESSION['email'];
$ricerca = $_POST['ricerca'];

$result = cercaUtente($ricerca);


if ($result) { // se non ci sono errori
    
    $_SESSION['lista_utenti'] = $result;; // aggiorna sessione

    header("Location: ../frontend/statistics.php"); // reindirizza

}
