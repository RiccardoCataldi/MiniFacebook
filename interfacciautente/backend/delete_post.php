<?php

include_once '../common/session.php'; // Include il file di sessione
include_once '../common/connection.php'; // Include la connessione al database
include_once '../common/function.php';

// Verifica se l'utente è loggato
if (!isset($_SESSION['email'])) {
    // Redirect alla pagina di login
    header('Location: ../index.php');
}

$email = $_POST['email'];
$dataPubblicazione = $_POST['dataPubblicazione'];

if (deleteMsg($conn, $email, $dataPubblicazione)) {

    // calcola e aggiorna la valutazione media dell'utente che aveva postato, e quindi ricevuto i commenti con possibile valutazione
    $_SESSION["emailUserDaCalcolare"] = $email;
    include 'valutazioneMedia.php'; // esegui algoritmo calcolo rispettabilità

    echo "success";
} else {
    echo "failure";
}

