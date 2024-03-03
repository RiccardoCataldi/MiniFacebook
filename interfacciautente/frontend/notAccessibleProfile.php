<?php

include_once '../common/session.php'; // Include la sessione
include_once '../common/connection.php'; // Include la connessione al database
include_once '../common/function.php';

// Verifica se l'utente è loggato
if (!isset($_SESSION['email'])) {
    // Redirect alla pagina di login
    header('Location: ../index.php');
}

// Verifica se si proviene dalla rimozione di un utente
if (isset($_SESSION['utente_rimosso']) && $_SESSION['utente_rimosso'] == true) {
    header('Location: elenco_utenti.php');
}

$friendEmail = $_GET['email'];

$isFriend = isFriend($_SESSION['email'], $friendEmail);
if ($isFriend) {
    // Redirect al profilo dell'amico
    header('Location: profilefriends.php?email=' . urlencode($friendEmail));
} elseif ($_SESSION['email'] == $friendEmail) {
    // Redirect alla pagina profilo dell'utente loggato 
    header('Location: profile.php');
}


$user = getAttributes($conn, $friendEmail); // Ottieni gli attributi dell'utente

?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Non amici</title>
    <?php include_once "../common/header.php" ?>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

</head>

<body class="d-flex flex-column h-100">
    <main role="main" class="flex-shrink-0">
        <div class="container mt-5">
            <?php
            // Verifica se si proviene dalla rimozione di un utente: ergo non esiste più l'utente nel db.
            if (!existingUser($conn, $friendEmail)) {
                
                echo '<div class="alert alert-warning" role="alert">Attenzione! Questo utente è stato rimosso! Non puoi più vederne il profilo.</div>';
                
            } else { // visualizza il contenuto della pagina
            ?>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-body text-center">

                            <!-- profile picture -->
                            <img src="<?php echo $user['profilePicturePath'] ?? 'https://via.placeholder.com/150'; ?>"
                                class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                            <h4 class="card-title">
                                <?php echo $user['nome'] . ' ' . $user['cognome']; ?>
                            </h4>

                            <!-- Aggiunta della logica per visualizzare il pulsante corretto -->
                            <?php if (isPending($conn, $_SESSION['email'], $friendEmail)): ?>
                                <!-- Se l'utente ha già fatto richiesta di amicizia, mostra il pulsante "Richiesta già inviata" -->
                                <button class="btn btn-outline-primary" disabled>Richiesta già inviata</button>
                                <!-- Aggiungi un pulsante per annullare la richiesta di amicizia -->
                                <a href="../backend/cancelrequest.php?email=<?php echo $user['email']; ?>"
                                    class="btn btn-danger">Annulla richiesta</a>
                            <?php elseif (isPending($conn, $friendEmail, $_SESSION['email'])): ?>
                                <!-- Se l'utente ha già ricevuto richiesta di amicizia, mostra il pulsante "Vuole fare amicizia con te" -->
                                <button class="btn btn-outline-primary" disabled>Vuole fare amicizia con te</button>
                                <!-- Aggiungi un pulsante per accettare la richiesta di amicizia -->
                                <a href="../backend/accept_request.php?email=<?php echo $user['email']; ?>"
                                    class="btn btn-success">Accetta</a>
                                <!-- Aggiungi un pulsante per rifiutare la richiesta di amicizia -->
                                <a href="../backend/cancelrequest.php?email=<?php echo $user['email']; ?>"
                                    class="btn btn-danger">Rifiuta</a>

                            <?php else: ?>
                                <!-- Se l'utente non è in sospeso, mostra il pulsante "Aggiungi Amico" -->
                                <a href="../backend/addfriend.php?email=<?php echo $user['email']; ?>"
                                    class="btn btn-success">Aggiungi Amico</a>
                            <?php endif; ?>
                        </div>
                        <div class="card-header">
                            <h2>Informazioni Profilo</h2>
                            <p>Email:
                                <?php echo $user['email']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </main>

</body>


<?php include "../common/footer.php"; ?>




</html>