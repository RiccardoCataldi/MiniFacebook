<?php
include_once '../common/session.php'; // session start
include_once '../common/connection.php'; // Include the database connection
include_once '../common/function.php'; // Include funzioni varie

// Verifica se l'utente è loggato
if (!isset($_SESSION['email'])) {
    // Redirect alla pagina di login
    header('Location: ../index.php');
}

$friendEmail = $_GET['email'];
$listaPost = postList($conn, $friendEmail); // ottieni lista di post dell'utente visualizzato

// Controllo se l'utente loggato è amico dell'utente che sta visualizzando
$isFriend = isFriend($_SESSION['email'], $friendEmail);
if (!$isFriend && $_SESSION['email'] != $friendEmail) {
    // Redirect alla pagina di notAccessibleProfile.php se utente loggato non è ancora amico dell'utente visualizzato
    header('Location: notAccessibleProfile.php?email=' . urlencode($friendEmail));

} elseif ($_SESSION['email'] == $friendEmail) {
    // Redirect alla pagina di profilo personale se utente loggato vuole vedere il proprio profilo
    header('Location: profile.php');
}

$email = $_SESSION['email'];

$user = getAttributes($conn, $friendEmail); // ottieni gli attributi dell'utente visualizzato
$hobbiesPraticati = getHobbies($conn, $friendEmail); // ottieni eventuali hobby dell'utente visualizzato
$friends = getFriends($conn, $friendEmail); // ottieni lista di amici dell'utente visualizzato

?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <title>Il profilo del tuo amico</title>
    <?php include_once '../common/header.php'; // Include header ?>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>

<body class="d-flex flex-column h-100">
    <main role="main" class="flex-shrink-0">
        <div class="container mt-5 mb-5">
        <?php
        // Verifica se si proviene dallo sblocco di un utente
        if (isset($_SESSION['utente_sbloccato']) && $_SESSION['utente_sbloccato'] == true) {
            echo '<div class="alert alert-success" role="alert">Utente sbloccato con successo!</div>';
            // Resetta la variabile di stato per evitare che il messaggio venga mostrato nuovamente in futuro
            $_SESSION['utente_sbloccato'] = false;
        } 
        // Verifica se si proviene dal blocco di un utente
        elseif (isset($_SESSION['utente_bloccato']) && $_SESSION['utente_bloccato'] == true) {
            echo '<div class="alert alert-success" role="alert">Utente bloccato con successo!</div>';
            // Resetta la variabile di stato per evitare che il messaggio venga mostrato nuovamente in futuro
            $_SESSION['utente_bloccato'] = false;

        }
        ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <img src="<?php echo $user['profilePicturePath'] ?? 'https://via.placeholder.com/150'; ?>"
                                class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                            <h4 class="card-title">
                                <?php echo $user['nome'] . ' ' . $user['cognome']; ?>
                            </h4>
                            <p>Email:
                                <?php echo $user['email']; ?>
                            </p>
                            <?php
                            if (isAdmin($conn, $email) == true) {
                                echo 'Rispettabilità: ' . $user['valutazioneMedia'];
                            } ?>
                            <div>
                                <!-- button per rimuovere amicizia -->
                                <a href="../backend/remove_friend.php?email=<?= urlencode($friendEmail); ?>"
                                    class="btn btn-danger mt-3"
                                    onclick="return confirm('Sei sicuro di voler rimuovere questo amico?');">Rimuovi
                                    amico</a>

                                <!-- Funzionlità SOLO per amministratori -->
                                <?php if (isAdmin($conn, $email) && !isAdmin($conn, $friendEmail)) { ?>

                                    <!-- aggiungi bottone per rimuovere dal database l'utente visualizzato -->
                                    <form method="post" action="../backend/remove_user_fromDB.php">
                                        <!-- Passa l'email dell'utente come parametro POST -->
                                        <input type="hidden" name="user_email" value="<?php echo $friendEmail; ?>">
                                        <!-- Aggiungi il bottone di conferma -->
                                        <button type="submit" class="btn btn-danger mt-2">Rimuovi dal database</button>
                                    </form>

                                    <!-- controlla se utente è bloccato o no -->
                                    <?php if (!isBlocked($conn, $friendEmail)) { ?>
                                        <!-- aggiungi bottone per bloccare l'utente visualizzato -->
                                        <form method="post" action="../backend/block_user.php">
                                            <!-- Passa l'email dell'utente come parametro POST -->
                                            <input type="hidden" name="emailToBlock" value="<?php echo $friendEmail; ?>">
                                            <!-- Aggiungi il bottone di conferma -->
                                            <button type="submit" class="btn btn-danger mt-2">Blocca</button>
                                        </form>
                                    <?php } else { ?>
                                        <!-- se utente già bloccato, aggiungi bottone per sbloccare l'utente visualizzato -->
                                        <form method="post" action="../backend/unlock_user.php">
                                            <!-- Passa l'email dell'utente come parametro POST -->
                                            <input type="hidden" name="emailToUnlock" value="<?php echo $friendEmail; ?>">
                                            <!-- Aggiungi il bottone di conferma -->
                                            <button type="submit" class="btn btn-success mt-2">Sblocca</button>
                                        </form>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">About Me</h4>
                            <ul class="navbar-nav">
                                <li class="list-item">
                                    Mi chiamo:
                                    <?php echo $user['nome'] . ' ' . $user['cognome']; ?>
                                </li>
                                <li class="list-item">
                                    Sesso:
                                    <?php echo $user['sesso']; ?>
                                </li>
                                <li class="list-item">
                                    Nato/a a:
                                    <?php echo $user['cittàNascita']; ?>
                                </li>
                                <li class="list-item">
                                    Nazione di nascita:
                                    <?php echo $user['nazioneNascita']; ?>
                                </li>
                                <li class="list-item">
                                    Provincia di nascita:
                                    <?php echo $user['provinciaNascita']; ?>
                                </li>
                                <li class="list-item">
                                    Data di nascita:
                                    <?php echo $user['dataNascita']; ?>
                                </li>
                                <li class="list-item">
                                    Hobby:
                                    <?php foreach ($hobbiesPraticati as $hobby): ?>
                                        -
                                        <?php echo $hobby['tipo']; ?>
                                    <?php endforeach; ?>
                                </li>
                                <li class="list-item">
                                    Residenza:
                                    <?php echo $user['cittàResidenza']; ?>
                                </li>
                                <li class="list-item">
                                    Nazione di residenza:
                                    <?php echo $user['nazioneResidenza']; ?>
                                </li>
                                <li class="list-item">
                                    Provincia di residenza:
                                    <?php echo $user['provinciaResidenza']; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <!-- card body right align with other cards -->
                        <div class="card-body">
                            <h4 class="card-title">Posts</h4>
                            <div class="row">
                                <?php foreach ($listaPost as $post): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <?php if ($post['tipo'] === 'foto'): ?>
                                                    <!-- Display photo -->
                                                    <img src="<?php echo $post['posizioneFileSystem']; ?>" class="img-fluid"
                                                        alt="Post Photo"style="width: 100%; height: auto;">
                                                <?php else: ?>
                                                    <!-- Display text -->
                                                    <p>
                                                        <?php echo $post['testo']; ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h4 class="card-title">Amici</h4>
                            <?php if (!empty($friends)): ?>
                                <?php foreach ($friends as $friend): ?>
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <?php echo $friend['email']; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                            // Verifica se l'utente corrente è un amico
                                            $isFriend = isFriend($_SESSION['email'], $friend['email']);

                                            if (!$isFriend && $_SESSION['email'] != $friend['email'] && !isPending($conn, $_SESSION['email'], $friend['email']) && !isPending($conn, $friend['email'], $_SESSION['email'])): ?>
                                                <!-- se non sono già amici utente loggato non può accedere al profilo, ma può richiederne l'amicizia -->
                                                <a href="notAccessibleProfile.php?email=<?php echo $friend['email']; ?>"
                                                    class="btn btn-outline-primary">Visualizza profilo</a>
                                                <a href="../backend/addfriend.php?email=<?php echo $friend['email']; ?>"
                                                    class="btn btn-success">Aggiungi amico</a>
                                            <?php elseif ($_SESSION['email'] == $friend['email']): ?>
                                                <!-- se l'amico è l'utente loggato, può vedere il suo profilo personale -->
                                                <a href="profile.php" class="btn btn-outline-primary">Visualizza profilo</a>
                                            <?php elseif (isPending($conn, $_SESSION['email'], $friend['email'])): ?>
                                                <!-- se l'utente loggato ha già mandato richiesta di amicizia, può annullarla -->
                                                <a href="notAccessibleProfile.php?email=<?php echo $friend['email']; ?>"
                                                    class="btn btn-outline-primary">Visualizza profilo</a>
                                                <a href="../backend/cancelrequest.php?email=<?php echo $friend['email']; ?>"
                                                    class="btn btn-danger">Annulla richiesta</a>
                                            <?php elseif (isPending($conn, $friend['email'], $_SESSION['email'])): ?>
                                                <!-- se l'utente loggato ha ricevuto richiesta di amicizia, può accettarla o rifiutarla -->
                                                <a href="notAccessibleProfile.php?email=<?php echo $friend['email']; ?>"
                                                    class="btn btn-outline-primary">Visualizza profilo</a>
                                                <div class="btn-group">
                                                    <!-- Aggiungi un pulsante per accettare la richiesta di amicizia -->
                                                    <a href="../backend/accept_request.php?email=<?php echo $friend['email']; ?>"
                                                        class="btn btn-success">Accetta</a>
                                                    <!-- Aggiungi un pulsante per rifiutare la richiesta di amicizia -->
                                                    <a href="../backend/cancelrequest.php?email=<?php echo $friend['email']; ?>"
                                                        class="btn btn-danger">Rifiuta</a>
                                                </div>
                                            <?php else: ?>
                                                <!-- eventuali altri casi li gestisce profilefriends.php -->
                                                <a href="profilefriends.php?email=<?php echo $friend['email']; ?>"
                                                    class="btn btn-outline-primary">Visualizza profilo</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Ancora nessuna amicizia</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        </div>
    </main>
    <?php include_once "../common/footer.php"; ?>
</body>

</html>