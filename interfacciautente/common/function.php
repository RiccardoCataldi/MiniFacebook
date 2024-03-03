<?php

include_once("connection.php");

// controlla se un utente è presente nel database
function existingUser($conn, $userEmail)
{
    // Query SQL
    $sql = "SELECT * FROM utenti WHERE email = '$userEmail'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Ci sono risultati, quindi c'è un utente corrispondente
        return true;
    } else {
        // Non ci sono risultati, quindi non c'è un utente corrispondente
        return false;
    }
}

// controlla se c’è una richiesta di amicizia in sospeso fra due utenti.
function isPending($conn, $utente1, $utente2)
{
    // Query SQL per verificare se c'è una richiesta di amicizia in sospeso fra due utenti (utente1 è il richiedente, utente2 il ricevente)
    $sql = "SELECT * FROM AmicoDi WHERE Richiedente = '$utente1' AND Ricevente = '$utente2' AND dataAccettazione IS NULL";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Ci sono risultati, quindi c'è una richiesta di amicizia in sospeso
        return true;
    } else {
        // Non ci sono risultati, quindi non c'è una richiesta di amicizia in sospeso
        return false;
    }
}


// restituisce le richieste di amicizia ricevute, ma non ancora accettate.
function pendingRequest($conn, $email)
{
    $sql = "SELECT * FROM AmicoDi WHERE Ricevente = '$email' AND dataAccettazione IS NULL";

    $result = mysqli_query($conn, $sql);

    $pending = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $pending[] = $row;
        }
    }

    return $pending;
}


// restituisce gli amici di un utente.
function getFriends($conn, $email)
{
    $sql = "SELECT * FROM Utenti WHERE email IN (
        SELECT Ricevente FROM AmicoDi WHERE Richiedente = '$email' AND dataAccettazione IS NOT NULL
        UNION
        SELECT Richiedente FROM AmicoDi WHERE Ricevente = '$email' AND dataAccettazione IS NOT NULL
    )";

    $result = mysqli_query($conn, $sql);

    $friends = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $friends[] = $row;
        }
    }

    return $friends;
}


// controlla se due utenti sono amici.
function isFriend($loggedUserEmail, $otherUserEmail)
{
    global $conn;

    // Ottieni la lista degli amici dell'utente loggato
    $friends = getFriends($conn, $loggedUserEmail);

    // Verifica se l'utente di destinazione è presente nella lista degli amici
    foreach ($friends as $friend) {
        if ($friend['email'] === $otherUserEmail) { //=== è un operatore di uguaglianza che controlla anche il tipo
            return true; // Gli utenti sono amici
        }
    }

    // L'utente di destinazione non è presente nella lista degli amici
    return false;
}

// restituisce gli utenti cercati per email, nome o cognome (caso senza filtri selezionati).
function cercaUtente($stringaRicerca)
{
    global $conn;

    // Preparazione della stringa di ricerca per la query SQL
    $stringaRicerca = '%' . $conn->real_escape_string($stringaRicerca) . '%';

    // Query SQL per cercare l'utente
    $sql = "SELECT email, nome, cognome FROM Utenti WHERE 
            nome LIKE '$stringaRicerca' OR
            cognome LIKE '$stringaRicerca' OR
            email LIKE '$stringaRicerca'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Restituisci i risultati della query come un array di utenti
        $utenti = array();
        while ($row = $result->fetch_assoc()) {
            $utenti[] = $row;
        }
        return $utenti;
    } else {
        // Nessun utente trovato
        return null;
    }
}


// restituisce i dati selezionati dal filtro leggendoli dagli utenti memorizzati nel db. Funzione per aggiornare dinamicamente l'input di ricerca nell'header.
function getDropdownData($conn, $filter) {
    $data = array();

    // logica per ottenere i dati dal database in base al filtro
    switch ($filter) {
        case "citta":
            $query = "SELECT nome, provincia, nazione FROM città";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $cityData = $row['nome'] . "," . $row['provincia'] . "," . $row['nazione'];
                $data[] = $cityData;
            }
            break;
        case "eta":
            $data = array("meno di 20", "20-30", "30-40", "40-50", "50+");          
            break;
        case "hobby":
            $query = "SELECT tipo FROM hobby";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row['tipo'];
            }
            break;
        case "sesso":
            $data = array("M", "F");
            break;
        default:
            return $data;
    }

    return $data;
}

// restituisce gli utenti cercati per sesso.
function cercaPerSesso($sesso)
{
    global $conn;

    // Query SQL per cercare gli utenti per sesso
    $sql = "SELECT email, nome, cognome FROM Utenti WHERE sesso = '$sesso'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Restituisci i risultati della query come un array di utenti
        $utenti = array();
        while ($row = $result->fetch_assoc()) {
            $utenti[] = $row;
        }
        return $utenti;
    } else {
        // Nessun utente trovato
        return null;
    }
}

// restituisce gli utenti cercati per hobby.
function cercaPerHobby($hobby)
{
    global $conn;

    // Query SQL per cercare gli utenti per hobby
    $sql = "SELECT u.email, u.nome, u.cognome FROM Utenti u join Praticano p on (u.email=p.email) WHERE tipo = '$hobby'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Restituisci i risultati della query come un array di utenti
        $utenti = array();
        while ($row = $result->fetch_assoc()) {
            $utenti[] = $row;
        }
        return $utenti;
    } else {
        // Nessun utente trovato
        return null;
    }
}

// restituisce gli utenti cercati per città di residenza.
function cercaPerCitta($nome, $provincia, $nazione) {
    
    global $conn;

    // Query SQL per cercare gli utenti per hobby
    $sql = "SELECT email, nome, cognome FROM Utenti WHERE cittàResidenza = '$nome' and provinciaResidenza = '$provincia' and nazioneResidenza = '$nazione'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Restituisci i risultati della query come un array di utenti
        $utenti = array();
        while ($row = $result->fetch_assoc()) {
            $utenti[] = $row;
        }
        return $utenti;
    } else {
        // Nessun utente trovato
        return null;
    }
}

// restituisce gli utenti cercati per età.
function cercaPerEta($stringa_intervallo_di_età) {

    global $conn;

    $query = "";

    // possibili query sql in base alla fascia di età
    if ($stringa_intervallo_di_età == "meno di 20") { // abbiamo stabilito 4 casi: "meno di 20", "20-30", "30-40", "40-50", "50+"
        $query = "SELECT * FROM utenti WHERE TIMESTAMPDIFF(YEAR, dataNascita, CURDATE()) < 20";
    } else if ($stringa_intervallo_di_età == "20-30") {
        $query = "SELECT * FROM utenti WHERE TIMESTAMPDIFF(YEAR, dataNascita, CURDATE()) BETWEEN 20 AND 30";
    } else if ($stringa_intervallo_di_età == "30-40") {
        $query = "SELECT * FROM utenti WHERE TIMESTAMPDIFF(YEAR, dataNascita, CURDATE()) BETWEEN 30 AND 40";
    } else if ($stringa_intervallo_di_età == "40-50") {
        $query = "SELECT * FROM utenti WHERE TIMESTAMPDIFF(YEAR, dataNascita, CURDATE()) BETWEEN 40 AND 50";
    } else if ($stringa_intervallo_di_età == "50+") {
        $query = "SELECT * FROM utenti WHERE TIMESTAMPDIFF(YEAR, dataNascita, CURDATE()) >= 50";
    } else {
        return null;
    }
   
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Restituisci i risultati della query come un array di utenti
        $utenti = array();
        while ($row = $result->fetch_assoc()) {
            $utenti[] = $row;
        }
        return $utenti;
    } else {
        // Nessun utente trovato
        return null;
    }
}

// rimuove l’amicizia fra due utenti.
function removeFriend($conn, $loggedUserEmail, $friendEmail)
{
    // Query SQL per rimuovere l'amicizia
    $sql = "DELETE FROM AmicoDi WHERE (Richiedente = '$loggedUserEmail' AND Ricevente = '$friendEmail') OR (Richiedente = '$friendEmail' AND Ricevente = '$loggedUserEmail')";

    // Esecuzione della query
    $result = $conn->query($sql);

    // Verifica se la query è stata eseguita con successo
    if ($result) {
        // Amicizia rimossa con successo
        return true;
    } else {
        // Errore nella rimozione dell'amicizia
        return false;
    }
}


// restituisce gli attributi specifici di un certo utente.
function getAttributes($conn, $email)
{
    // Query SQL per ottenere gli attributi dell'utente
    $sql = "SELECT * FROM Utenti WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "Errore nel recupero delle informazioni dell'utente.";
    }

    return $user;
}


// restituisce gli hobby di un utente.
function getHobbies($conn, $email)
{
    // Query SQL per ottenere gli hobby di un utente
    $sql = "SELECT tipo FROM Praticano WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $hobby = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $hobby[] = $row;
        }
    } else {
        echo "Errore nel recupero delle informazioni dell'utente.";
    }

    return $hobby;
}


// restituisce gli hobby selezionabili, memorizzati nel database.
function hobby($conn)
{
    // Query SQL per ottenere gli hobby
    $sql = "SELECT tipo FROM Hobby";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $hobby = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $hobby[] = $row;
        }
    } else {
        echo "Errore nel recupero delle informazioni dell'utente.";
    }

    return $hobby;
}

//seleziona tutti i messaggi di un utente e dei suoi amici.
function usrFriendMsg($conn, $email)
{
    $sql = "SELECT * FROM messaggi WHERE email IN (
        SELECT Ricevente FROM AmicoDi WHERE Richiedente = '$email' AND dataAccettazione IS NOT NULL
        UNION
        SELECT Richiedente FROM AmicoDi WHERE Ricevente = '$email' AND dataAccettazione IS NOT NULL
        UNION
        SELECT '$email'
    ) ORDER BY dataPubblicazione DESC";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $msg = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $msg[] = $row;
        }
    } else {
        echo "Errore nel recupero delle informazioni dell'utente.";
    }

    return $msg;
}

//inserisce il messaggio di un utente nel database.
function publishMsg($conn, $email, $msg, $separateCityData)
{

    if ($separateCityData!=array('null', 'null', 'null')) { // se c'è una città associata
        $sql = "INSERT INTO messaggi (email, dataPubblicazione, testo, nomeCittà, provincia, nazione) VALUES ('$email', CURRENT_TIMESTAMP, '$msg', '$separateCityData[0]', '$separateCityData[1]', '$separateCityData[2]')";
    } else { // se non c'è una città associata al post
        $sql = "INSERT INTO messaggi (email, dataPubblicazione, testo) VALUES ('$email', CURRENT_TIMESTAMP, '$msg')";
    }
    $result = mysqli_query($conn, $sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

//cancella il messaggio di un utente dal database.
function deleteMsg($conn, $email, $dataPubblicazione)
{
    $sql = "DELETE FROM messaggi WHERE email = '$email' AND dataPubblicazione = '$dataPubblicazione'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}
//insersce il commento ad un messaggio di un utente nel database con controllo sul numero di commenti inseriti.
function commentPost($conn, $emailComment, $emailPost, $dataPubblicazione, $comment,$valutazione)
{
    // Query per ottenere il conteggio attuale di commenti fatti da quell'utente per il post specifico
    $countQuery = "SELECT COUNT(*) as commentCount FROM commenti WHERE emailComment = '$emailComment' AND emailPost = '$emailPost' AND dataPubblicazione = '$dataPubblicazione'";

    $countResult = mysqli_query($conn, $countQuery);

    if ($countResult) {
        $row = mysqli_fetch_assoc($countResult);
        $commentCount = $row['commentCount'] + 1; // Incrementa il conteggio di uno
        //se il conteggio è maggiore di 5, non permettere di commentare fornisci un messaggio di errore
        if ($commentCount > 5) {
            return false;
        }

    } else {
        return false; // la query non ha successo
    }

    if ($valutazione === null) {
        // Inserimento del nuovo commento insieme al conteggio aggiornato
        $sql = "INSERT INTO commenti (emailComment, emailPost, dataPubblicazione, dataCommento, testo, progressivo) 
            VALUES ('$emailComment', '$emailPost', '$dataPubblicazione', CURRENT_TIMESTAMP, '$comment', $commentCount)";
    } else{
        // Inserimento del nuovo commento insieme al conteggio aggiornato
        $sql = "INSERT INTO commenti (emailComment, emailPost, dataPubblicazione, dataCommento, testo, progressivo, indiceGradimento) 
            VALUES ('$emailComment', '$emailPost', '$dataPubblicazione', CURRENT_TIMESTAMP, '$comment', $commentCount, $valutazione)";
    }
    

    $result = mysqli_query($conn, $sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}
//resituisce i commenti ad un messaggio di un utente.
function getComments($conn, $emailPost, $dataPubblicazione)
{
    $sql = "SELECT * FROM commenti WHERE emailPost = '$emailPost' AND dataPubblicazione = '$dataPubblicazione' ORDER BY dataCommento DESC";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $comments = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $comments[] = $row;
        }
    } else {
        echo "Errore nel recupero delle informazioni dell'utente.";
    }

    return $comments;
}
//cancella il commento ad un messaggio di un utente dal database.
function deleteComment($conn, $emailComment, $emailPost, $dataPubblicazione, $dataCommento)
{
    $sql = "DELETE FROM commenti WHERE emailComment = '$emailComment' AND emailPost = '$emailPost' AND dataPubblicazione = '$dataPubblicazione' AND dataCommento = '$dataCommento'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}


// controlla se un utente è amministratore o no
function isAdmin($conn, $email)
{
    $sql = "SELECT * FROM Utenti WHERE email='$email' and amministratore IS NOT NULL";
    $result = $conn->query($sql);

    // se c'è il risultato vuol dire che utente è amministratore
    if ($result->num_rows == 1) {
        return true;
    }

    // se è null non è amministratore
    return false;

}


// rimuove un utente dal database (funzionalità per amministratori)
function removeFromDB($conn, $userToRemove)
{
    $sql = "DELETE FROM Utenti WHERE email='$userToRemove'";
    $result = $conn->query($sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}


// blocca un utente (funzionalità per amministratori)
function blockUser($conn, $userToBlock, $adminEmail)
{
    // Aggiorna l'attributo 'amministratoreBlocca'
    $sql = "UPDATE Utenti SET amministratoreBlocca = '$adminEmail' WHERE email = '$userToBlock'";
    $result = $conn->query($sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}


// verifica se un utente è bloccato (funzionalità per amministratori)
function isBlocked($conn, $userEmail)
{
    $sql = "SELECT * FROM Utenti WHERE email='$userEmail' and amministratoreBlocca IS NOT NULL";
    $result = $conn->query($sql);

    // se c'è il risultato vuol dire che utente è bloccato
    if ($result->num_rows == 1) {
        return true;
    }

    // se è null non è bloccato
    return false;
}


// restituisce gli utenti bloccati
function getBlockedUsers($conn)
{
    $sql = "SELECT * FROM Utenti WHERE amministratoreBlocca IS NOT NULL";
    $result = mysqli_query($conn, $sql);

    $blockedUser = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $blockedUser[] = $row;
        }
    }

    return $blockedUser;
}
// sblocca un utente (funzionalità per amministratori)
function unlockUser($conn, $userToUnlock)
{
    // Aggiorna l'attributo 'amministratoreBlocca'
    $sql = "UPDATE Utenti SET amministratoreBlocca = null WHERE email = '$userToUnlock'";
    $result = $conn->query($sql);

    if ($result) {
        // rimetti al valore di default la valutazioneMedia dell'utente, così può ripartire da 0
        $sql = "UPDATE Utenti SET valutazioneMedia = DEFAULT WHERE email = '$userToUnlock'";
        $result = $conn->query($sql);
        return true;
    } else {
        return false;
    }
}


// restituisce il giorno dell'ultima settimana con il minimo numero di messaggi pubblicati dall'utente e il numero di messaggi stesso
function getMinMessagesAndDayLastWeek($conn, $user_email)
{
    $query = "
        SELECT MIN(numeroMessaggi) AS minMessaggi, giornoMinMessaggi
        FROM (
            SELECT COUNT(*) AS numeroMessaggi, DATE(dataPubblicazione) AS giornoMinMessaggi
            FROM Messaggi
            WHERE email = '$user_email' AND dataPubblicazione >= CURDATE() - INTERVAL 1 WEEK
            GROUP BY giornoMinMessaggi
        ) AS messaggiSettimana;
    ";

    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $minMessaggi = $row['minMessaggi'] ?? 0;
        $giornoMinMessaggi = $row['giornoMinMessaggi'] ?? 'nessun messaggio';

        return [
            'minMessaggi' => $minMessaggi,
            'giornoMinMessaggi' => $giornoMinMessaggi,
        ];
    } else {
        return ["Errore nel recupero delle informazioni dell'utente."];
    }
}


// restituisce il giorno dell'ultima settimana con il massimo numero di messaggi pubblicati dall'utente e il numero di messaggi stesso
function getMaxMessagesAndDayLastWeek($conn, $user_email)
{
    $query = "
        SELECT COUNT(*) AS numeroMessaggi, DATE(dataPubblicazione) AS giornoMaxMessaggi
        FROM Messaggi
        WHERE email = '$user_email' AND dataPubblicazione >= CURDATE() - INTERVAL 1 WEEK
        GROUP BY giornoMaxMessaggi
        ORDER BY numeroMessaggi DESC
        LIMIT 1;
    ";

    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $maxMessaggi = $row['numeroMessaggi'] ?? 0;
        $giornoMaxMessaggi = $row['giornoMaxMessaggi'] ?? 'nessun messaggio';

        return [
            'maxMessaggi' => $maxMessaggi,
            'giornoMaxMessaggi' => $giornoMaxMessaggi,
        ];
    } else {
        return ["Errore nel recupero delle informazioni dell'utente."];
    }
}


// restituisce il numero medio di messaggi pubblicati dall'utente nell'ultima settimana
function getAverageMessagesLastWeek($conn, $user_email)
{
    $query = "
        SELECT ROUND(COUNT(*) / 7 , 2) AS mediaMessaggi
        FROM Messaggi
        WHERE email = '$user_email' AND dataPubblicazione >= CURDATE() - INTERVAL 1 WEEK;
    ";

    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $mediaMessaggi = $row['mediaMessaggi'] ?? 0;

        return $mediaMessaggi;
    } else {
        return 'Errore nel recupero della media'; // se si verificano errori durante la query
    }
}


// restituisce i 5 utenti con maggior numero di commenti positivi sui loro post
function getTopUsersWithPositiveComments($conn) {
    $query = "
        SELECT
            c.emailPost AS email,
            COUNT(*) AS positive_comments_count
        FROM
            Commenti c
        WHERE
            c.indiceGradimento > 0
        GROUP BY
            c.emailPost
        ORDER BY
            positive_comments_count DESC
        LIMIT 5
    ";

    $result = $conn->query($query);

    if ($result) {
        if ($result->num_rows > 0) {
            $topUsers = $result->fetch_all(MYSQLI_ASSOC);

            return $topUsers;
        } else {
            return false;
        }
    } else {
        return ["Errore nel recupero delle informazioni dell'utente: " . $conn->error];
    }
}


// restituisce la media degli indici di gradimento ricevuti da un dato utente sui propri post.
function getAvgUserGradimento($conn, $userToCalculate) {

    $sql = "SELECT avg(indiceGradimento) AS avgGradimento FROM Commenti WHERE emailPost='$userToCalculate' AND indiceGradimento IS NOT NULL";
    $result = mysqli_query($conn, $sql);

    if ($result === false) {
        // Errore nella query
        return false;
    }

    $row = mysqli_fetch_assoc($result);

    // Controlla se il risultato è NULL
    if ($row['avgGradimento'] === NULL) {
        return false;
    }

    // Restituisci la media degli indici di gradimento
    return $row['avgGradimento'];
}

// ritorna tutte le città memorizzate nel database in ordine alfabetico
function getCities($conn)
{
    $sql = "SELECT * FROM città ORDER BY nome ASC";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $cities = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $cities[] = $row;
        }
    } else {
        echo "Errore nel recupero delle informazioni dell'utente.";
    }

    return $cities;
}
// ritorna uno specifico messaggio di un utente
function getPost($conn, $email, $dataPubblicazione)
{
    $sql = "SELECT * FROM messaggi WHERE email = '$email' AND dataPubblicazione = '$dataPubblicazione'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $post = mysqli_fetch_assoc($result);
    } else {
        echo "Errore nel recupero delle informazioni dell'utente.";
    }

    return $post;
}
// inserisce il riferimento ad un messaggio come commento ad un altro messaggio
function insertRiferiti($conn, $emailComment, $emailPost1, $dataPubblicazionePost1, $dataCommento, $emailPost2, $dataPubblicazionePost2)
{
    //	emailComment	emailPost1	dataPubblicazionePost1	dataCommento	emailPost2	dataPubblicazionePost2	
    $sql = "INSERT IGNORE INTO riferitia (emailComment, emailPost1, dataPubblicazionePost1, dataCommento, emailPost2, dataPubblicazionePost2) VALUES ('$emailComment','$emailPost1','$dataPubblicazionePost1','$dataCommento','$emailPost2','$dataPubblicazionePost2')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}
// ritorna il path della foto profilo di un utente
function profilePicturePath($conn, $email)
{
    $sql = "SELECT profilePicturePath FROM Utenti WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $profilePicturePath = $row['profilePicturePath'] ?? null;

        return $profilePicturePath;
    } else {
        return null;
    }
}
// restituisce un booleano che indica se l'utente ha già votato il post o meno
function hasRated($conn, $emailComment, $emailPost, $dataPubblicazione)
{
    //check if the user has already rated the post
    $sql = "SELECT * FROM commenti WHERE emailComment = '$emailComment' AND emailPost = '$emailPost' AND dataPubblicazione = '$dataPubblicazione' AND indiceGradimento IS NOT NULL";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Ci sono risultati, quindi l'utente ha già votato
        return true;
    } else {
        // Non ci sono risultati, quindi l'utente non ha ancora votato
        return false;
    }
}
//restituisce il rating di un commento fatto da un utente ad un post
function getRating($conn, $emailComment, $emailPost, $dataPubblicazione)
{
    $sql = "SELECT indiceGradimento FROM commenti WHERE emailComment = '$emailComment' AND emailPost = '$emailPost' AND dataPubblicazione = '$dataPubblicazione'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Verifica se il risultato è valido e se contiene la chiave 'indiceGradimento'
        if ($row && isset($row['indiceGradimento'])) {
            return $row['indiceGradimento'];
        } else {
            // Se il risultato non è valido o non contiene la chiave 'indiceGradimento', restituisci un valore di default o gestisci l'errore come preferisci
            return null;
        }
    } else {
        echo "Errore nel recupero delle informazioni dell'utente.";
    }
}

//inserice il commento ad un post con il rating con check sul numero di commenti inseriti
function ratingPost($conn, $emailComment, $emailPost, $dataPubblicazione, $rating)
{
    $countQuery = "SELECT COUNT(*) as commentCount FROM commenti WHERE emailComment = '$emailComment' AND emailPost = '$emailPost'";

    $countResult = mysqli_query($conn, $countQuery);

    if ($countResult) {
        $row = mysqli_fetch_assoc($countResult);
        $commentCount = $row['commentCount'] + 1; // Incrementa il conteggio di uno
        //se il conteggio è maggiore di 5, non permettere di commentare fornisci un messaggio di errore
        if ($commentCount > 5) {
            return false;
        }

    } else {
        return false; // Gestisci l'errore se la query non ha successo
    }

    // Inserimento del nuovo commento insieme al conteggio aggiornato
    $sql = "INSERT INTO commenti (emailComment, emailPost, dataPubblicazione, dataCommento, testo, progressivo, indiceGradimento) 
            VALUES ('$emailComment', '$emailPost', '$dataPubblicazione', CURRENT_TIMESTAMP, ' ', $commentCount, $rating)";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        return true;
    } else {
        return false;
    }

}
// ritorna tutti i messaggi di un utente
function postList($conn, $email)
{
    $sql = "SELECT * FROM messaggi WHERE email = '$email' ORDER BY dataPubblicazione DESC";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $post = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $post[] = $row;
        }
    } else {
        echo "Errore nel recupero delle informazioni dell'utente.";
    }

    return $post;
}
?>