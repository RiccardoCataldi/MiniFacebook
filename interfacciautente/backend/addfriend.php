<?php 

include_once '../common/session.php'; // include sessione
include_once '../common/connection.php'; // Include la connessione al database

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page
    header('Location: ../index.php');
}

$utenteLoggato = $_SESSION['email'];

$utenteDaAggiungere = $_GET['email'];


$sql = "INSERT INTO amicodi (Richiedente, Ricevente,dataRichiesta) VALUES ('$utenteLoggato', '$utenteDaAggiungere',CURRENT_TIMESTAMP)";

$result = mysqli_query($conn, $sql);

if ($result) { // se non ci sono errori
    $referer = $_SERVER['HTTP_REFERER'];
    header("Location: $referer");

}
 ?>