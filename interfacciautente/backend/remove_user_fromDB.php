<?php 
include_once "../common/connection.php";
include_once "../common/function.php";
include_once "../common/session.php";

$adminEmail = $_SESSION['email'];
$userToRemove = $_POST['user_email'];

$result = removeFromDB($conn, $userToRemove);

if ($result) { // se non ci sono errori
    
    $_SESSION["utente_rimosso"] = true; // aggiorna sessione

    $referer = $_SERVER['HTTP_REFERER'];
    header("Location: $referer");

}
?>