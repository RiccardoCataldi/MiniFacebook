<?php 
include_once "../common/connection.php";
include_once "../common/function.php";
include_once "../common/session.php";

$adminEmail = $_SESSION['email'];
$userToBlock = $_POST['emailToBlock'];

$result = blockUser($conn, $userToBlock, $adminEmail);

if ($result) { // se non ci sono errori
    
    $_SESSION["utente_bloccato"] = true; // aggiorna sessione

    $referer = $_SERVER['HTTP_REFERER'];
    header("Location: $referer");

}
?>