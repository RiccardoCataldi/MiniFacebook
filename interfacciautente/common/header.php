<!DOCTYPE html>

<?php include_once 'function.php';

//controlla se la sessione è attiva
if (!isset($_SESSION['email'])) {
    include_once 'session.php';
}
$pendingRequest = pendingRequest($conn, $_SESSION['email']);
$pendingRequestCount = count($pendingRequest);

?>
<html>

<head>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Website icon -->
    <link rel="icon" href="../img/logo_originale.ico" type="image/x-icon">
    <!-- ajax caricamento dinamico filtri per ricerca utenti -->
    <script src="../js/ajaxFiltriRicerca.js"></script>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light py-3" style="background-color: #ebeffa">

            <!-- Bottone del menu per schermi di dimensioni ridotte -->
            <button class="navbar-toggler mx-auto" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Elementi della barra di navigazione -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="navbar-brand" href="../frontend/feed.php">Feed</a> <!-- link al feed (home) -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../frontend/profile.php">Profilo</a>
                    </li>
                    <?php if (isAdmin($conn, $_SESSION['email'])) { // le statistiche può vederle solo un amministratore ?>
                        <li class="nav-item">
                            <form class="form-inline mx-auto mt-2 mt-md-0 ml-4" action="../backend/search.php"
                                method="post">
                                <input class="form-control mr-2" type="hidden" name="ricerca">
                                <a class="nav-link" href="../backend/search_users_statistics.php"
                                    type="submit">Statistiche</a>
                            </form>

                        </li>
                    <?php } ?>
                </ul>

                <!-- Form di ricerca con filtro a comparsa -->
                <form class="form-inline mx-auto mt-2 mt-md-0 ml-4" action="../backend/search.php" method="post">
                    <input class="form-control mr-2" type="search" placeholder="Cerca utenti" name="ricerca"
                        id="inputSearch">
                    <div id="paramContainer"></div> <!-- Contenitore per la tendina dinamica -->
                    <select class="form-control mr-2" name="filtro" id="filtro">
                        <option value="">Tutti</option>
                        <option value="citta">Città di residenza</option>
                        <option value="eta">Età</option>
                        <option value="hobby">Hobby</option>
                        <option value="sesso">Sesso</option>
                    </select>

                    <button class="btn btn-outline-primary mt-2 mt-md-0" type="submit">Search</button>
                </form>

                <ul class="navbar-nav mt-2 mt-md-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown">
                            Richieste di amicizia <span class="badge badge-danger">
                                <?php echo $pendingRequestCount; ?>
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <?php if (count($pendingRequest) == 0): ?>
                                <h1 class="dropdown-header">Nessuna richiesta</h1>
                            <?php else: ?>
                                <?php foreach ($pendingRequest as $friend): ?>

                                    <a class="dropdown-item"
                                        href="../frontend/notAccessibleProfile.php?email=<?php echo $friend['Richiedente']; ?>">
                                        <?php echo $friend['Richiedente']; ?>
                                        <div class="btn-group float-right mt-3 mt-md-0 mb-1 mb-md-0 mr-4 d-none d-md-block">
                                            <!-- bottoni visibili solo da schermi medium o pià larghi (se lo schermo è
                                            collassato, non sarà visibile) -->
                                            <a href="../backend/accept_request.php?email=<?php echo $friend['Richiedente']; ?>"
                                                class="btn btn-success btn-sm ">Accetta</a>
                                            <a href="../backend/cancelrequest.php?email=<?php echo $friend['Richiedente']; ?>"
                                                class="btn btn-danger btn-sm">Rifiuta</a>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                    </li>
                </ul>

                <!-- Logout Button -->
                <form class="form-inline mt-2 mt-md-0 ml-md-4" method="post" action="../backend/logout.php">
                    <button type="submit" name="logout" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>

        </nav>
    </header>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>