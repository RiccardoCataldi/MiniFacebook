<?php

include_once '../common/session.php';
include_once '../common/connection.php';
include_once '../common/function.php';

$emailComment = $_SESSION['email'];
$emailPost = $_POST['emailPost'];
$dataPubblicazione = $_POST['dataPubblicazione'];
$testo = $_POST['comment'];
$valutazione = $_POST['valutazione'];

if ($valutazione == ""){
    $valutazione = NULL;
}

if (commentPost($conn,$emailComment, $emailPost, $dataPubblicazione, $testo, $valutazione)){

    // calcola e aggiorna la valutazione media dell'utente che riceve il commento
    $_SESSION["emailUserDaCalcolare"] = $emailPost;
    include 'valutazioneMedia.php'; // esegui algoritmo calcolo rispettabilità
    
    echo trim("success");
} else {
    echo "error";
}



