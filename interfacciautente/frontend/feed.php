<?php

include_once '../common/session.php'; // session start
include_once '../common/connection.php'; // Include the database connection
include_once '../common/function.php'; // Include funzioni varie
include_once '../common/header.php'; // Include header 

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page
    header('Location: ../index.php');
}

// --- Get the user's friends ---
$email = $_SESSION['email'];

// Ottieni gli amici che hanno accettato la richiesta
$friends = getFriends($conn, $email);

// Messaggi Utente e Amici
$ufm = usrFriendMsg($conn, $email);


//elenco città
$cities = getCities($conn);

// true se utente è bloccato, false altrimenti
$blockStatus = isBlocked($conn, $email);
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Il tuo feed</title>


    <script src="../js/bootstrap.bundle.min.js"></script>
    <!-- JQuery -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script type="text/javascript" src="../js/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- funzioni js -->
    <script src="../js/function.js"></script>

</head>

<body class="d-flex flex-column h-100">
    <main role="main" class="flex-shrink-0">
        <div class="container">
            <div class="mt-5">
                <div>
                    <!-- controlla se utente è bloccato, se sì avvisa -->
                    <?php if ($blockStatus == true) { ?>
                        <div class="alert alert-danger" role="alert">Sei stato bloccato! Non potrai
                            più postare e commentare finchè non ti sbloccano.</div>
                    <?php } ?>
                </div>

                <div class="border border-left border-right px-0 mb-5">

                    <!-- solo se utente non è bloccato, può postare -->
                    <?php if (!$blockStatus == true) { ?>
                        <div class="card shadow-0">
                            <!-- Pubblica qualcosa -->
                            <div class="card-body border-bottom pb-2 flex-column">
                                <div class="d-flex flex-column align-items-center">
                                    <?php $profilePicturePath = profilePicturePath($conn, $email); ?>
                                    <?php if ($profilePicturePath): ?>
                                        <img src="<?php echo $profilePicturePath; ?>" alt="Profilo"
                                            class="img-fluid rounded-circle mb-2 mt-2 img-profile"
                                            style="width: 70px; height: 70px;">
                                    <?php else: ?>
                                        <img src="../img/profile/default.jpg" alt="Profilo"
                                            class="img-fluid rounded-circle mb-2 mt-2 img-profile"
                                            style="width: 70px; height: 70px; border: 2px solid #000;">
                                    <?php endif; ?>

                                    <div class="d-flex flex-column ml-sm-3 w-100">
                                        <div class="w-100 mt-3">
                                            <style>
                                                #form143::placeholder {
                                                    font-size: 30px;
                                                }
                                            </style>
                                            <input type="text" id="form143"
                                                class="form-control form-status border-1 py-1 px-0 mb-2 mb-sm-0"
                                                placeholder="Pubblica qualcosa ..." />
                                        </div>

                                        <div class="d-flex flex-column flex-sm-row mt-4 mb-4">
                                            <label for="imageInput" type="button"
                                                class="btn btn-outline-primary mb-0">Scegli immagine</label>
                                            <input type="file" accept="image/*" class="form-control-file" id="imageInput"
                                                name="imageInput" style="display: none;">

                                            <!-- scegli città -->
                                            <select class="form-select form-select-sm mb-2 mb-sm-0 ml-sm-2" id="città">
                                                <option selected>Scegli città</option>
                                                <?php foreach ($cities as $city): ?>
                                                    <option
                                                        value="<?php echo $city['nome'] . ',' . $city['provincia'] . ',' . $city['nazione']; ?>">
                                                        <?php echo $city['nome'] . ',' . $city['provincia'] . ',' . $city['nazione']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                            <button type="button" class="btn btn-primary btn-rounded ml-sm-2"
                                                onclick="publishMessage()">Pubblica</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php } ?>
                    <div>
                        <!-- Post -->
                        <?php foreach ($ufm as $msg): ?>
                            <div class="d-flex p-3 border-bottom" style="overflow: auto;overflow-wrap: anywhere;">
                                <?php $profilePicturePath = profilePicturePath($conn, $msg['email']); ?>
                                <?php if ($profilePicturePath): ?>
                                    <img src="<?php echo $profilePicturePath; ?>" alt="Profilo"
                                        class="img-fluid rounded-circle mb-3 img-profile" style="width: 50px; height: 50px;">
                                <?php else: ?>
                                    <img src="../img/profile/default.jpg" alt="Profilo"
                                        class="img-fluid rounded-circle mb-3 img-profile"
                                        style="width: 50px; height: 50px; border: 2px solid #000;">
                                <?php endif; ?>
                                <div class="d-flex ps-3 ml-2">
                                    <div>
                                        <a href=profilefriends.php?email=<?php echo $msg['email']; ?>>
                                            <h6 class="text-body">
                                                <?php echo $msg['email']; ?>
                                        </a>
                                        <span class="small text-muted font-weight-normal">
                                            <?php echo $msg['dataPubblicazione']; ?>
                                            <span><i class="fas fa-angle-down float-end"></i></span>
                                        </span>
                                        <span class="small text-muted font-weight-normal">
                                            <?php if (trim($msg['nomeCittà']) != 'null'): ?>
                                                <?php echo $msg['nomeCittà']; ?>
                                            <?php endif; ?>
                                        </span>
                                        <span class="small text-muted float-right mb-0 pe-xl-5">
                                            <!-- delete button -->
                                            <?php if ($msg['email'] == $email): ?>

                                                <button type="button" class="btn btn-rounded ml-2"
                                                    onclick="delete_post('<?php echo $msg['email']; ?>','<?php echo $msg['dataPubblicazione']; ?>')">
                                                    <i class="bi bi-trash"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-trash"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                            <path
                                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                        </svg></i>
                                                </button>


                                            <?php endif; ?>


                                        </span>

                                        </h6>


                                        <p style="line-height: 1.2;">
                                            <?php if ($msg['tipo'] == 'foto'): ?>
                                                <!-- aggiungi descrizione -->
                                            <div>
                                                <?php echo '<span style="font-size: 20px;">' . $msg['descrizione'] . '</span><br>'; ?>
                                            </div>
                                            <img src="<?php echo $msg['posizioneFileSystem']; ?>" alt="Avatar"
                                                class="img-fluid rounded-left" style="width: 500px; height: 500px;">
                                        <?php else: ?>
                                            <?php echo '<span style="font-size: 20px;">' . $msg['testo'] . '</span><br>'; ?>

                                        <?php endif; ?>
                                        </p>

                                        <!-- Mostra Commenti Post -->
                                        <?php
                                        $comments = getComments($conn, $msg['email'], $msg['dataPubblicazione']);
                                        foreach ($comments as $comment): ?>
                                            <div class="card border mb-3 shadow-0 mt-5 mx-auto" style="max-width: 440px;">
                                                <div class="row g-0">
                                                    <div class="col-md-3">
                                                        <?php $profilePicturePath = profilePicturePath($conn, $comment['emailComment']); ?>
                                                        <?php if ($profilePicturePath): ?>
                                                            <img src="<?php echo $profilePicturePath; ?>" alt="Profilo"
                                                                class="img-fluid rounded-circle mb-3 img-profile ml-2 mt-2"
                                                                style="width: 50px; height: 50px;">
                                                        <?php else: ?>
                                                            <img src="../img/profile/default.jpg" alt="Profilo"
                                                                class="img-fluid rounded-circle mb-3 img-profile ml-2 mt-2"
                                                                style="width: 50px; height: 50px; border: 2px solid #000;">
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="card-body">
                                                            <p class="card-text" style="line-height: 1;">
                                                                <a href=profilefriends.php?email=<?php echo $comment['emailComment']; ?>>
                                                                    <h6 class="text-body">
                                                                        <?php echo $comment['emailComment']; ?>
                                                                </a>
                                                            </p>
                                                            <p class="card-text small mb-0" style="line-height: 1.2;">
                                                                <?php
                                                                // Check if the comment contains the pattern #email TIMESTAMP
                                                                if (preg_match('/#(\S+)\s(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2})/', $comment['testo'], $matches)) {
                                                                    $emailPost = $matches[1];
                                                                    $timestamp = $matches[2];
                                                                    $emailDichiCommenta = $comment['emailComment'];
                                                                    $emaildelCommentoPrincipale = $comment['emailPost'];
                                                                    $dataCommento = $comment['dataCommento'];
                                                                    $dataCommentoPrincipale = $comment['dataPubblicazione'];
                                                                    $mailCitata = $emailPost;
                                                                    $dataCitata = $timestamp;
                                                                    insertRiferiti($conn, $emailDichiCommenta, $emaildelCommentoPrincipale, $dataCommentoPrincipale, $dataCommento, $mailCitata, $dataCitata);
                                                                    // Split the comment based on the pattern and include the anchor tag
                                                                    $commentParts = preg_split('/#(\S+)\s(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2})/', $comment['testo'], -1, PREG_SPLIT_DELIM_CAPTURE);
                                                                    if (!empty($comment['indiceGradimento'])) {
                                                                        echo 'Indice gradimento: ' . $comment['indiceGradimento'] . '<br>';
                                                                    }
                                                                    // Output each part of the comment
                                                                    foreach ($commentParts as $index => $part) {
                                                                        if ($index % 3 === 0) {
                                                                            // Output the non-matching part of the comment
                                                                            echo '<span style="font-size: 16px;">' . $part . '</span><br>';
                                                                        } elseif ($index % 3 === 1) {
                                                                            // Include the anchor tag
                                                                            $post = getPost($conn, $emailPost, $timestamp);
                                                                            //print_r($post);
                                                                            ?>
                                                                            <?php if ($post['tipo'] == 'foto'): ?>
                                                                                <!-- aggiungi descrizione -->
                                                                                <a href="#/"
                                                                                    onclick="handleAnchorClickFoto('<?php echo $emailPost; ?>', '<?php echo $timestamp; ?>',' <?php echo $post['descrizione']; ?>','<?php echo $post['posizioneFileSystem']; ?>')">
                                                                                    <?php echo '#' . $emailPost; ?>
                                                                                </a>
                                                                            <?php else: ?>
                                                                                <a href="#/"
                                                                                    onclick="handleAnchorClickTesto('<?php echo $emailPost; ?>', '<?php echo $timestamp; ?>',' <?php echo $post['testo']; ?>')">
                                                                                    <?php echo '#' . $emailPost; ?>
                                                                                </a>
                                                                            <?php endif; ?>

                                                                            <?php
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<span style="font-size: 16px;">' . $comment['testo'] . '</span><br>';


                                                                    if (!empty($comment['indiceGradimento'])) {
                                                                        echo 'Indice gradimento: ' . $comment['indiceGradimento'] . '<br>';
                                                                    }
                                                                    echo $comment['dataCommento'];
                                                                }



                                                                ?>
                                                            </p>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <?php
                                                    ?>
                                                    <?php if ($comment['emailComment'] == $email): ?>

                                                        <button type="button" class="btn btn-rounded ml-2 mb-2"
                                                            onclick="delete_comment('<?php echo $msg['email']; ?>','<?php echo $msg['dataPubblicazione']; ?>','<?php echo $comment['dataCommento']; ?>')">
                                                            <i class="bi bi-trash"><svg xmlns="http://www.w3.org/2000/svg"
                                                                    width="16" height="16" fill="currentColor" class="bi bi-trash"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                                    <path
                                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                                </svg></i>
                                                        </button>



                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>




                                        <div class="d-flex align-items-center w-100 ps-3 mt-2">
                                            <!-- solo se utente non è bloccato, può commentare -->
                                            <?php if (!$blockStatus == true) { ?>

                                                <div class="w-100" style="overflow-wrap: normal;">
                                                    <!-- crea id univoco per ogni commento -->
                                                    <?php
                                                    $commentId = base64_encode($msg['email'] . $msg['dataPubblicazione']);
                                                    $commentId = str_replace(['+', '/', '='], ['-', '_', ''], $commentId);
                                                    ?>
                                                    <input type="text" id=<?php echo $commentId; ?>
                                                        class="form-control form-status border-0 py-1 px-0 ml-2"
                                                        placeholder="Lascia un commento"
                                                        onkeyup="whichButton(event, '<?php echo $commentId; ?>')">
                                                </div>
                                                <div>
                                                    <!-- solo se utente non è bloccato, può mettere like -->

                                                    <?php if (!$blockStatus == true) { ?>
                                                        <!-- rating from -3 to 3 hmtl-->
                                                        <?php $valutazioneId = "valutazione_" . $commentId; ?>
                                                        <?php if (!hasRated($conn, $email, $msg['email'], $msg['dataPubblicazione'])): ?>
                                                            <select class="form-select form-select-sm mt-2 ml-4"
                                                                id="<?php echo $valutazioneId; ?>">
                                                                <option selected>Scegli valutazione</option>
                                                                <?php for ($i = -3; $i <= 3; $i++): ?>
                                                                    <option value="<?php echo $i; ?>">
                                                                        <?php echo $i; ?>
                                                                    </option>
                                                                <?php endfor; ?>
                                                            </select>
                                                        <?php endif; ?>
                                                    <?php } ?>
                                                </div>
                                                <form id="commentForm" enctype="multipart/form-data"
                                                    style=overflow-wrap:normal;>


                                                    <button type="button" class="btn btn-outline-primary btn-rounded  ml-2"
                                                        onclick="publishComment('<?php echo $msg['email']; ?>','<?php echo $msg['dataPubblicazione']; ?>','<?php echo $commentId; ?>','<?php echo $valutazioneId; ?>')">
                                                        Pubblica
                                                    </button>


                                                </form>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </main>
    <?php include_once '../common/footer.php'; ?>

    <!-- Post Box -->
    <div id="dialog">

    </div>

</body>
<script>
    // funzione che quando un utente scrive il carattere speciale “#” in un commento per riferirsi a un altro post, mostra dinamicamente una tendina con i post selezionabili.
    function whichButton(event, commentId) {
        var element = $("#" + commentId);
        var msg = <?php echo json_encode($ufm); ?>;

        function split(val) {
            return val.split(/\s+/);
        }

        function extractLast(term) {
            return split(term.trim()).pop();
        }

        $(element)
            .bind("keydown", function (event) {
                // Aggiungi eventuali logiche aggiuntive per il keydown
            })
            .autocomplete({
                minLength: 1,
                source: function (request, response) {
                    var lastword = extractLast(request.term);
                    var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(lastword), "i");

                    if (lastword[0] == '#') {
                        // Filter and flatten nested JSON data
                        var flattenedResults = msg.reduce(function (acc, item) {
                            var mailWithHash = '#' + item.email;

                            if (matcher.test(item.email) || matcher.test(mailWithHash)) {
                                acc.push(item);
                            }

                            return acc;
                        }, []);

                        response(flattenedResults);
                    }
                },
                focus: function (event, ui) {
                    // Impedisci il focus su un elemento dell'elenco per evitare l'aggiunta del valore in input
                    event.preventDefault();
                },
                select: function (event, ui) {
                    var terms = split(this.value);
                    terms.pop();

                    // Aggiungi sia l'email con l'hastag che la dataPubblicazione
                    terms.push('#' + ui.item.email + ' ' + ui.item.dataPubblicazione);

                    terms.push("");
                    this.value = terms.join(" ");
                    return false;
                }
            })
            .autocomplete("instance")._renderItem = function (ul, item) {
                var listItem = $("<li>");

                var content = '<div>' +
                    'email: ' + item.email + '<br>' +
                    'dataPubblicazione: ' + item.dataPubblicazione + '<br>';

                // Aggiungi attributi aggiuntivi in base al tipo di file
                if (item.tipo === 'testo') {
                    content += 'testo: ' + item.testo + '<br>';
                } else if (item.tipo === 'foto') {
                    content += '<img src="' + item.posizioneFileSystem + '" width="50" height="50"><br>';
                    content += 'descrizione: ' + item.descrizione + '<br>';
                }

                content += '</div>';

                listItem.append(content);
                return listItem.appendTo(ul);
            };
    }

</script>

</html>