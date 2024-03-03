<?php

include_once '../common/session.php'; // Include la sessione
include_once '../common/connection.php'; // Include la connessione al database
include_once '../common/function.php'; // Include le funzioni

$loggedInUserEmail = $_SESSION['email'];

?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <title>Statistiche</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <?php include_once "../common/header.php" ?>
</head>

<body class="d-flex flex-column h-100">
    <main role="main" class="flex-shrink-0">
        <div class="container">

            <div class="row mt-5">
                <div class="col-md-6">
                <h3>Post ultima settimana</h3>

                <ul class="list-group">
                    <?php
                    // Recupera l'array dei risultati dalla query
                    $risultati = $_SESSION['lista_utenti'];
                    // Verifica se ci sono risultati
                    if ($risultati != null) {
                        foreach ($risultati as $utente) {
                            ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <!-- statistiche messaggi pubblicati dall'utente nell'ultima settimana -->
                                    <div class="col-md-10 text-left">
                                        <div>Email:
                                            <h6>
                                                <?= htmlspecialchars($utente['email']); ?>
                                            </h6>

                                            <?php if (isAdmin($conn, $utente['email']))
                                                echo '(amministratore)' ?>
                                            </div>
                                            <div>
                                            <?php $minMsgNum_and_day = getMinMessagesAndDayLastWeek($conn, $utente['email']) ?>
                                            Num minimo messaggi:
                                            <?php echo $minMsgNum_and_day['minMessaggi'] . ' (' . $minMsgNum_and_day['giornoMinMessaggi'] . ')' ?>
                                        </div>

                                        <div>
                                            <?php $maxMsgNum_and_day = getMaxMessagesAndDayLastWeek($conn, $utente['email']) ?>
                                            Num massimo messaggi:
                                            <?php echo $maxMsgNum_and_day['maxMessaggi'] . ' (' . $maxMsgNum_and_day['giornoMaxMessaggi'] . ')' ?>
                                        </div>
                                        <div>
                                            <?php $avgMsgNum = getAverageMessagesLastWeek($conn, $utente['email']) ?>
                                            Num medio messaggi:
                                            <?php echo $avgMsgNum ?>
                                        </div>

                                    </div>
                                    <div class="col-md-2">
                                        <!-- Aggiungi qui il link per visualizzare il profilo dell'utente -->
                                        <?php if ($loggedInUserEmail != $utente['email']) { ?>

                                            <a href="profilefriends.php?email=<?= urlencode($utente['email']); ?>"
                                                class="btn btn-outline-primary float-right mr-2">Profilo</a>

                                        <?php } else { ?>
                                            <!-- Se l'utente loggato è l'utente mostrato, aggiungi il link per visualizzare il proprio profilo metti la scritta visualizza profilo a destra -->
                                            <a href="profile.php" class="btn btn-outline-primary float-right mr-2">Profilo</a>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                        <?php

                    } else {
                        // Nessun utente trovato
                        echo "<li class='list-group-item'>Nessun utente trovato.</li>";
                    }
                    ?>
                </ul>
                </div>



                <div class="col-md-6">
                <h3>Classifica commenti positivi</h3>
                <ul class="list-group">
                    <?php
                    $top5PositiveComments = getTopUsersWithPositiveComments($conn);

                    if ($top5PositiveComments) {
                        $counter = 1;
                        foreach ($top5PositiveComments as $user) { ?>
                            <li class="list-group-item">

                                <div class="row">
                                    <!-- Statistiche top 5 utenti con commenti positivi -->
                                    <div class="col-md-10 text-left">
                                        <div>Email:
                                            <h6>
                                                <?php echo $counter . '. ' . $user['email']; ?>
                                            </h6>
                                        </div>
                                        <div>
                                             Commenti positivi:
                                            <?php echo $user['positive_comments_count']; ?>
                                        </div>

                                    </div>
                                    <div class="col-md-2">
                                        <!-- Aggiungi qui il link per visualizzare il profilo dell'utente -->
                                        <?php if ($loggedInUserEmail != $user['email']) { ?>
                                            <a href="profilefriends.php?email=<?= urlencode($user['email']); ?>"
                                                class="btn btn-outline-primary float-right mr-2">Profilo</a>
                                        <?php } else { ?>
                                            <!-- Se l'utente loggato è l'utente mostrato, aggiungi il link per visualizzare il proprio profilo metti la scritta visualizza profilo a destra -->
                                            <a href="profile.php" class="btn btn-outline-primary float-right mr-2">Profilo</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </li>
                        <?php $counter++;
                        } ?>
                    <?php } else {
                        // Nessun utente trovato
                        echo "<li class='list-group-item'>'Nessun utente con commenti positivi.'</li>";
                    }
                    ?>
                </ul>
                </div>
            </div>

        </div>
    </main>
    <p>
    </p>
    <?php include_once "../common/footer.php" ?>

</body>