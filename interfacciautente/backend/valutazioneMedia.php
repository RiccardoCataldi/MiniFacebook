<?php 
include_once "../common/connection.php";
include_once "../common/function.php";
include_once "../common/session.php";

$userToCalculate = $_SESSION["emailUserDaCalcolare"]; // recupera l'email dell'utente di cui si vuole calcolare la rispettabilità

// recupera la media degli indici di gradimento sui post dell'utente (compresa fra -3 e 3)
$valutazioneTo3 = getAvgUserGradimento($conn, $userToCalculate); 

if (($valutazioneTo3 <= -1) && (!isAdmin($conn, $userToCalculate)) && (!isBlocked($conn, $userToCalculate))) { // se media minore di -1: blocca utente
    
    // recupera in modo arbitrario la email di 1 utente amministratore
    $sql = "SELECT email FROM Utenti WHERE amministratore='amministratore' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $adminEmail = $row['email'];

    // aggiorna attributo 'amministratoreBlocca' dell'utente nel db (se utente già bloccato non fa niente)
    $sql = "UPDATE Utenti SET amministratoreBlocca = '$adminEmail' WHERE email = '$userToCalculate' and amministratoreBlocca is null";
    $result = $conn->query($sql);

} 

// mappa nel nuovo intervallo (1,10) e aggiorna valutazioneMedia dell'utente

// intervallo valutazioneTo3 [-3,3] => lunghezza intervallo 6; lunghezza intervallo finale 9;
$output = 9 * ($valutazioneTo3 + 3) / 6 ; // ($valutazioneTo3 + 3) : 6 = $output : 9

// converti il valore in un intervallo fra 1 e 10 (ora è fra 0 e 9)
$valutazioneMedia1To10 = $output + 1 ;

// aggiorna attributo 'valutazioneMedia' dell'utente nel db
$sql = "UPDATE Utenti SET valutazioneMedia = '$valutazioneMedia1To10' WHERE email = '$userToCalculate'";
$result = $conn->query($sql);
