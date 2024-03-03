<?php 
include_once "../common/connection.php";
include_once "../common/function.php";
include_once "../common/session.php";

$adminEmail = $_SESSION['email'];
$userToUnlock = $_POST['emailToUnlock'];

$result = unlockUser($conn, $userToUnlock);

if ($result) { // se non ci sono errori
    
    $_SESSION["utente_sbloccato"] = true; // aggiorna sessione

    $referer = $_SERVER['HTTP_REFERER'];
    header("Location: $referer");

}
?>