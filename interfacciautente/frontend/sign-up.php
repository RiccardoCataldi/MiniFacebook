<?php
include_once '../common/session.php';
include "../common/connection.php";
include "../common/function.php";

?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="UTF-8">
  <title>Sign-Up</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <!-- Website icon -->
  <link rel="icon" href="../img/logo_originale.ico" type="image/x-icon">
  <!-- ajax caricamento automatico città -->
  <script src="../js/ajaxCittà.js"></script>
</head>

<body class="d-flex flex-column h-100">
  <main role="main" class="flex-shrink-0">

    <div class="container">

      <h1 style="margin-bottom:2rem; margin-top:2rem;">Sign-Up</h1>

      <?php if (isset($_SESSION['email_gia_esistente']) && $_SESSION['email_gia_esistente']) {
        echo "<div class='alert alert-danger'>
                Email già registrata! Si prega di effettuare il login o cambiare email.
              </div>";
        $_SESSION['email_gia_esistente'] = false; // riaggiorna parametro
      } else { ?>

        <form method="post" action="../backend/register.php" id="registrationForm">

          <div class="form-group">
            <label>Email*</label>
            <input type="email" class="form-control" name="email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
              title="(aaaaaaaa@bbbbbb.dominio)" required>
          </div>
          <div class="form-group">
            <label>Password*</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="firstName" minlength="2">
          </div>
          <div class="form-group">
            <label>Cognome</label>
            <input type="text" class="form-control" name="lastName" minlength="2">
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
            <label>Data di nascita</label>
            <?php
            $minBirthDate = date('Y-m-d', strtotime('-16 years')); // minimo 16 anni per registrarsi
            ?>
            <input type="date" class="form-control" name="birthDate" max="<?php echo $minBirthDate; ?>">
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



          <div class="form-group py-3">
            <p class="text-dark">* campi obbligatori</p>
          </div>

          <div class="form-submit">
            <button type="submit" class="btn btn-primary " style="margin-bottom: 20px;">Sign-Up</button>
          </div>

        </form>
      <?php } ?>
      <!-- eventuali errori di incompletezza sulle città -->
      <div id="residenceWarning" class="alert alert-danger" style="display: none;">
        Si prega di fornire tutte le informazioni sulla residenza.
      </div>
      <div id="birthplaceWarning" class="alert alert-danger" style="display: none;">
        Si prega di fornire tutte le informazioni sul luogo di nascita.
      </div>

    </div>
  </main>

  <?php include "../common/footer.php"; ?>

</body>

</html>