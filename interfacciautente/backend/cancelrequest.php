<?php

include_once '../common/session.php'; // Include il file di sessione
include_once '../common/connection.php'; // Include la connessione al database
include_once '../common/function.php';

// Verifica se l'utente è loggato
if (!isset($_SESSION['email'])) {
    // Redirect alla pagina di login
    header('Location: ../index.php');
}

$friendEmail = $_GET['email'];
$loggedInUserEmail = $_SESSION['email'];


// Controlla se c'è una richiesta di amicizia in sospeso tra l'utente loggato e l'utente richiedente l'amicizia
if (isPending($conn, $loggedInUserEmail, $friendEmail) || isPending($conn, $friendEmail, $loggedInUserEmail)) {
    // Annulla la richiesta di amicizia
    $cancelSql = "DELETE FROM AmicoDi WHERE
                    (Richiedente = '$loggedInUserEmail' AND Ricevente = '$friendEmail' AND dataAccettazione IS NULL) OR
                    (Richiedente = '$friendEmail' AND Ricevente = '$loggedInUserEmail' AND dataAccettazione IS NULL)";

    if (mysqli_query($conn, $cancelSql)) {
        $referer = $_SERVER['HTTP_REFERER']; // Redirect alla pagina precedente
        header("Location: $referer");
    } else {
        echo "Errore durante l'annullamento della richiesta di amicizia: " . mysqli_error($conn);
    }
} else {
    echo "Nessuna richiesta di amicizia in sospeso da annullare.";
}

// Chiudi la connessione al database
mysqli_close($conn);
?>