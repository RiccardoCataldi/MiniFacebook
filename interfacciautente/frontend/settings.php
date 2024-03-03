<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <title>Modifica dati</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- ajax caricamento automatico città -->
    <script src="../js/ajaxCittà.js"></script>
    <?php include_once "../common/header.php" ?>
</head>

<body class="d-flex flex-column h-100">
    <main role="main" class="flex-shrink-0">
        <div class="container">
            <h1 style="margin-top: 20px;">Modifica attributi</h1>
            <form method="post" action="../backend/modify.php" id="registrationForm">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter password">
                </div>
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter name" minlength="2">
                </div>
                <div class="form-group">
                    <label for="surname">Cognome</label>
                    <input type="text" class="form-control" name="surname" placeholder="Enter surname" minlength="2">
                </div>
                <div class="form-group">
                    <label>Sesso</label>
                    <select class="form-control" name="gender">
                        <option value=""></option>
                        <option value="M">M</option>
                        <option value="F">F</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nazione di nascita</label>
                    <select class="form-control" name="birthCountry" id="selectNazioneNascita">
                        <!-- Opzioni delle nazioni saranno caricate dinamicamente tramite AJAX -->
                    </select>
                </div>

                <div class="form-group">
                    <label>Provincia di nascita</label>
                    <select class="form-control" name="birthProvince" id="selectProvinciaNascita" disabled>
                        <!-- Opzioni delle province saranno caricate dinamicamente tramite AJAX -->
                    </select>
                </div>

                <div class="form-group">
                    <label>Città di nascita</label>
                    <select class="form-control" name="birthCity" id="selectCittaNascita" disabled>
                        <!-- Opzioni delle città saranno caricate dinamicamente tramite AJAX -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="birthdate">Data di nascita</label>
                    <input type="date" class="form-control" id="birthdate" name="birthDate"
                        max="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label>Hobby</label>
                    <div class="custom-checkbox overflow-auto" style="max-height: 150px;">
                        <?php $hobbies = hobby($conn); // hobby restituisce un array degli hobby ?>
                        <?php foreach ($hobbies as $hobby): ?>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="hobby[]"
                                    id="hobby_<?php echo $hobby['tipo']; ?>" value="<?php echo $hobby['tipo']; ?>">
                                <label class="custom-control-label" for="hobby_<?php echo $hobby['tipo']; ?>">
                                    <?php echo $hobby['tipo']; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nazione di residenza</label>
                    <select class="form-control" name="residenceCountry" id="selectNazione">
                        <!-- Opzioni delle nazioni saranno caricate dinamicamente tramite AJAX -->
                    </select>
                </div>

                <div class="form-group">
                    <label>Provincia di residenza</label>
                    <select class="form-control" name="residenceProvince" id="selectProvincia" disabled>
                        <!-- Opzioni delle province saranno caricate dinamicamente tramite AJAX -->
                    </select>
                </div>

                <div class="form-group">
                    <label>Città di residenza</label>
                    <select class="form-control" name="residenceCity" id="selectCitta" disabled>
                        <!-- Opzioni delle città saranno caricate dinamicamente tramite AJAX -->
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-bottom: 20px;">Salva</button>
            </form>

            <!-- eventuali errori di incompletezza sulle città -->
            <div id="residenceWarning" class="alert alert-danger" style="display: none;">
                Si prega di fornire tutte le informazioni sulla residenza.
            </div>
            <div id="birthplaceWarning" class="alert alert-danger" style="display: none;">
                Si prega di fornire tutte le informazioni sul luogo di nascita.
            </div>

        </div>
    </main>
    <?php include_once "../common/footer.php" ?>
</body>

</html>