<?php

include_once '../common/session.php';
include_once '../common/connection.php';
include_once '../common/function.php';

$emailComment = $_SESSION['email'];
$emailPost = $_POST['emailPost'];
$dataPubblicazione = $_POST['dataPubblicazione'];
$dataCommento = $_POST['dataCommento'];

if (deleteComment($conn, $emailComment, $emailPost, $dataPubblicazione, $dataCommento)) {
    
    // calcola e aggiorna la valutazione media dell'utente che aveva ricevuto il commento
    $_SESSION["emailUserDaCalcolare"] = $emailPost;
    include 'valutazioneMedia.php'; // esegui algoritmo calcolo rispettabilità

    // rimozione avvenuta con successo
    echo "success";
} else {
    echo "failure";
}