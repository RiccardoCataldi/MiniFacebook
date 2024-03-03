<?php

include_once '../common/session.php'; // Start the session
include_once '../common/connection.php'; // Include la connessione al database
include_once '../common/function.php';

// Verifica se l'utente è loggato
if (!isset($_SESSION['email'])) {
    // Redirect alla pagina di login
    header('Location: ../index.php');
}

$richiedente = $_GET['email'];
$user = $_SESSION['email'];

// Controlla se c'è una richiesta di amicizia in sospeso tra l'utente loggato e l'utente richiedente l'amicizia 

if (isPending($conn, $user, $richiedente) || isPending($conn, $richiedente, $user)) {
    $acceptSql = "UPDATE AmicoDi SET dataAccettazione = CURRENT_TIMESTAMP WHERE Richiedente = '$richiedente' AND Ricevente = '$user' AND dataAccettazione IS NULL";
    if (mysqli_query($conn, $acceptSql)) {
        $referer = $_SERVER['HTTP_REFERER']; 
        header("Location: $referer");
    } else {
        echo "Errore durante l'accettazione della richiesta di amicizia: " . mysqli_error($conn);
    }
} 

?>