<?php 
include_once("../common/connection.php");
include_once("../common/function.php");
include_once("../common/session.php");

$loggedInUserEmail = $_SESSION['email'];
$ricerca = $_POST['ricerca'];
$filtro = $_POST['filtro']; // Aggiunto per ottenere il valore del filtro

if ($filtro == "" || $filtro == "tutti" || $ricerca == "") {
    // Se il filtro è vuoto o è "tutti", utilizza la funzione cercaUtente
    $result = cercaUtente($ricerca);
} else {
    // Altrimenti, gestisci i casi specifici per ogni filtro
    switch ($filtro) {
        case "citta":
            // Implementa la logica per la ricerca per città
            $separateCityData = explode(",", $ricerca); // ricava città, provincia, nazione dalla stringa separando dalle virgole
            $result = cercaPerCitta($separateCityData[0], $separateCityData[1], $separateCityData[2]);
            break;
        case "eta":
            // Implementa la logica per la ricerca per età
            $result = cercaPerEta($ricerca);
            break;
        case "hobby":
            // ricerca per hobby
            $result = cercaPerHobby($ricerca);
            break;
        case "sesso":
            // ricerca per sesso
            $result = cercaPerSesso($ricerca);
            break;
        default:
            // Se il filtro non corrisponde a nessun caso, utilizza la funzione cercaUtente di default
            $result = cercaUtente($ricerca);
            break;
    }
}

$_SESSION['result'] = $result;

// Reindirizza a una nuova pagina passando l'array di risultati come parametro della sessione
header("Location: ../frontend/elenco_utenti.php");
