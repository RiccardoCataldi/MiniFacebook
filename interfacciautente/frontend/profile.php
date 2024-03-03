<?php
include '../common/session.php'; // Start the session
include '../common/connection.php';
include '../common/function.php';

// Verifica se l'utente è loggato
if (!isset($_SESSION['email'])) {
    // Redirect alla pagina di login
    header('Location: ../index.php');
}

$email = $_SESSION['email'];
$user = getAttributes($conn, $email); // ottieni gli attributi dell'utente
$hobbiesPraticati = getHobbies($conn, $email); // ottieni eventuali hobby dell'utente
$listaPost = postList($conn, $email); // ottieni lista di post
$friends = getFriends($conn, $email); // ottieni lista di amici
$blockedUsers = getBlockedUsers($conn); // ottieni lista utenti bloccati

?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <title>Il tuo profilo</title>
    <?php include_once "../common/header.php" ?>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Js -->
    <script src="../js/function.js"></script>
</head>

<body>
    <div class="container mt-5 mb-5">
        <?php
        // Verifica se si proviene dallo sblocco di un utente
        if (isset($_SESSION['utente_sbloccato']) && $_SESSION['utente_sbloccato'] == true) {
            echo '<div class="alert alert-success" role="alert">Utente sbloccato con successo!</div>';
            // Resetta la variabile di stato per evitare che il messaggio venga mostrato nuovamente in futuro
            $_SESSION['utente_sbloccato'] = false;
        }
        ?>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form id="profilePictureForm" action="../backend/upload_profile_picture.php" method="post"
                            enctype="multipart/form-data">
                            <img src="<?php echo $user['profilePicturePath'] ?? 'https://via.placeholder.com/150'; ?>"
                                class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                            <div class="form-group">
                                <label for="profilePicture" type="button"
                                    class="btn btn-outline-primary btn-sm mb-0">Scegli immagine</label>
                                <input type="file" accept="image/*" class="form-control-file" id="profilePicture"
                                    name="profilePicture" style="display: none;">
                            </div>
                            <button type="button" class="btn btn-primary mt-0" onclick="checkAndSubmit()">Salva</button>
                        </form>
                        <h4 class="card-title">
                            <?php echo '<p class="mt-3"> '. $user['nome'].' '. $user['cognome'].'</p>'; ?>
                        </h4>
                        <p>Email:
                            <?php echo $user['email']; ?>
                        </p>
                        <?php
                        if (isAdmin($conn, $email) == true) {
                            
                            echo '<p> Rispettabilità: ' . $user['valutazioneMedia']. '</p>';
                        } ?>
                        <p>
                            <?php
                            if (isAdmin($conn, $user['email']) == true) {
                                echo 'Sei un amministratore';
                            } ?>
                        </p>
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
                                <?php foreach ($hobbiesPraticati as $key => $hobby): ?>
                                    <?php if ($key === 0): ?>
                                        <?php echo $hobby['tipo']; ?>
                                    <?php else: ?>
                                        -
                                        <?php echo $hobby['tipo']; ?>
                                    <?php endif; ?>
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
                        <div class="text-right">
                            <a href="settings.php" class="btn btn-outline-secondary">
                                <i class="fas fa-cog"></i> &#9881
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
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
                                                    alt="Post Photo" style="width:100%;height:auto;">
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
                <div class=" card mt-3">
                    <div class="card-body">
                        <h4 class="card-title">Amici</h4>
                        <?php if (!empty($friends)): ?>
                            <?php foreach ($friends as $friend): ?>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <?php echo $friend['email']; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="profilefriends.php?email=<?php echo $friend['email']; ?>"
                                            class="btn btn-outline-primary"> Visualizza profilo </a>
                                        <!-- button per rimuovere amicizia -->
                                        <a href="../backend/remove_friend.php?email=<?php echo $friend['email']; ?>"
                                            class="btn btn-danger">Rimuovi amico</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Ancora nessuna amicizia</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (isAdmin($conn, $email)) { ?>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h4 class="card-title">Utenti Bloccati</h4>
                            <?php if (!empty($blockedUsers)): ?>
                                <?php foreach ($blockedUsers as $blockedUser): ?>
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <?php echo $blockedUser['email']; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row ml-1">
                                                <a href="profilefriends.php?email=<?php echo $blockedUser['email']; ?>"
                                                    class="btn btn-outline-primary"> Visualizza profilo </a>
                                                <!-- se utente già bloccato, aggiungi bottone per sbloccare l'utente visualizzato -->
                                                <form method="post" action="../backend/unlock_user.php" class="form-inline">
                                                    <!-- Passa l'email dell'utente come parametro POST -->
                                                    <input type="hidden" name="emailToUnlock"
                                                        value="<?php echo $blockedUser['email']; ?>">
                                                    <!-- Aggiungi il bottone di conferma -->
                                                    <button type="submit" class="btn btn-success ml-1">Sblocca</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Ancora nessun bloccato.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php include_once "../common/footer.php"; ?>
</body>


</html>