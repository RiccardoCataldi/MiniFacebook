<?php

include_once '../common/session.php'; // Include la sessione
include_once '../common/connection.php'; // Include la connessione al database
include_once '../common/function.php'; // Include le funzioni

$loggedInUserEmail = $_SESSION['email'];

?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco Utenti</title>
    <?php include_once "../common/header.php" ?>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

</head>

<body class="d-flex flex-column h-100">
    <main role="main" class="flex-shrink-0">
        <div class="container">
            <h1 class="mt-4 mb-4">Elenco Utenti</h1>

            <?php
            // Verifica se si proviene dalla rimozione di un utente
            if (isset($_SESSION['utente_rimosso']) && $_SESSION['utente_rimosso'] == true) {
                echo '<div class="alert alert-success" role="alert">Utente rimosso con successo!</div>';
                // Resetta la variabile di stato per evitare che il messaggio venga mostrato nuovamente in futuro
                $_SESSION['utente_rimosso'] = false;
            }
            // Verifica se si proviene dal blocco di un utente
            elseif (isset($_SESSION['utente_bloccato']) && $_SESSION['utente_bloccato'] == true) {
                echo '<div class="alert alert-success" role="alert">Utente bloccato con successo!</div>';
                // Resetta la variabile di stato per evitare che il messaggio venga mostrato nuovamente in futuro
                $_SESSION['utente_bloccato'] = false;

            }
            // Verifica se si proviene dallo sblocco di un utente
            elseif (isset($_SESSION['utente_sbloccato']) && $_SESSION['utente_sbloccato'] == true) {
                echo '<div class="alert alert-success" role="alert">Utente sbloccato con successo!</div>';
                // Resetta la variabile di stato per evitare che il messaggio venga mostrato nuovamente in futuro
                $_SESSION['utente_sbloccato'] = false;

            } else { // viene visualizzato il contenuto vero e proprio della pagina, ossia l'elenco degli utenti
            
                ?>

                <ul class="list-group">
                    <?php
                    // Recupera l'array dei risultati dalla query
                    $risultati = $_SESSION['result'];
                    // Verifica se ci sono risultati
                    if ($risultati != null) {
                        foreach ($risultati as $utente) {
                            ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6 text-left" style="padding-bottom: 10px;">
                                        <strong>
                                            <?= $utente['nome'] . ' ' . $utente['cognome']; ?>
                                        </strong>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <span>Email:
                                            <?= htmlspecialchars($utente['email']); ?>
                                            <?php if (isAdmin($conn, $utente['email']))
                                                echo '(amministratore)' ?>
                                            </span>


                                        </div>
                                        <?php
                                            // Verifica se si proviene dalla rimozione di un utente: ergo non esiste più l'utente nel db.
                                            if (!existingUser($conn, $utente['email'])) {

                                                echo '<div class="alert alert-warning" role="alert">Attenzione! Questo utente è stato rimosso! Non puoi più vederne il profilo.</div>';

                                            } else { // altrimenti visualizza il contenuto normalmente ?>
                                        <!-- Aggiungi qui il link per visualizzare il profilo dell'utente -->
                                        <?php if ($loggedInUserEmail != $utente['email']) { ?>

                                            <a href="profilefriends.php?email=<?= urlencode($utente['email']); ?>"
                                                class="btn btn-outline-primary float-right mr-2">Visualizza profilo</a>

                                        <?php } else { ?>
                                            <!-- Se l'utente loggato è l'utente mostrato, aggiungi il link per visualizzare il proprio profilo metti la scritta visualizza profilo a destra -->
                                            <a href="profile.php" class="btn btn-outline-primary mr-2">Visualizza profilo</a>
                                        <?php } ?>

                                        <!-- Aggiungi qui il link per aggiungere l'utente come amico con controllo se utente loggato e utente mostrato non sono già amici o se l'amicizia è gia pending -->
                                        <?php if (!isFriend($loggedInUserEmail, $utente['email']) && !isPending($conn, $loggedInUserEmail, $utente['email']) && !isPending($conn, $utente['email'], $loggedInUserEmail) && $loggedInUserEmail != $utente['email']) { ?>
                                            <a href="../backend/addfriend.php?email=<?= urlencode($utente['email']); ?>"
                                                class="btn btn-success ">Aggiungi amico</a>
                                        <?php } ?>

                                        <!-- Funzionlità SOLO per amministratori -->
                                        <?php if (isAdmin($conn, $loggedInUserEmail) && !isAdmin($conn, $utente['email'])) { ?>

                                            <!-- aggiungi bottone per rimuovere dal database l'utente visualizzato -->
                                            <form method="post" action="../backend/remove_user_fromDB.php">
                                                <!-- Passa l'email dell'utente come parametro POST -->
                                                <input type="hidden" name="user_email" value="<?php echo $utente['email']; ?>">
                                                <!-- Aggiungi il bottone di conferma -->
                                                <button type="submit" class="btn btn-danger ml-2">Rimuovi dal database</button>
                                            </form>

                                            <!-- controlla se utente è bloccato o no -->
                                            <?php if (!isBlocked($conn, $utente['email'])) { ?>
                                                <!-- aggiungi bottone per bloccare l'utente visualizzato -->
                                                <form method="post" action="../backend/block_user.php">
                                                    <!-- Passa l'email dell'utente come parametro POST -->
                                                    <input type="hidden" name="emailToBlock" value="<?php echo $utente['email']; ?>">
                                                    <!-- Aggiungi il bottone di conferma -->
                                                    <button type="submit" class="btn btn-danger ml-2">Blocca</button>
                                                </form>
                                            <?php } else { ?>
                                                <!-- se utente già bloccato, aggiungi bottone per sbloccare l'utente visualizzato -->
                                                <form method="post" action="../backend/unlock_user.php">
                                                    <!-- Passa l'email dell'utente come parametro POST -->
                                                    <input type="hidden" name="emailToUnlock" value="<?php echo $utente['email']; ?>">
                                                    <!-- Aggiungi il bottone di conferma -->
                                                    <button type="submit" class="btn btn-success ml-2">Sblocca</button>
                                                </form>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </li>
                            <?php
                        }
                    } else {
                        // Nessun utente trovato
                        echo "<li class='list-group-item'>Nessun utente trovato.</li>";
                    }
                    ?>
                </ul>

            <?php } ?>
        </div>
    </main>
    <p>
    </p>

    <?php include_once "../common/footer.php" ?>


</body>

</html>