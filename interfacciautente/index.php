<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="UTF-8">
  <title>Login</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Website icon -->
  <link rel="icon" href="img/logo_originale.ico" type="image/x-icon">
  <!-- funzioni js -->
  <script src="js/function.js"></script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>

</head>

<!-- Modal -->
<div class="modal fade" id="erroreCredenziali" tabindex="-1" aria-labelledby="erroreCredenzialiLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="erroreCredenzialiLabel">Errore di login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Credenziali errate, riprova!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<body class="d-flex flex-column h-100">
  <main role="main" class="flex-shrink-0">
    <div class="container">




      <!-- Login form -->

      <div class="container">
        <div class="row justify-content-center mt-5">
          <div class="col-md-6 mt-5">

            <div class="card">
              <div class="card-header">
                <h4>Login</h4>
              </div>
              <div class="card-body">
                <form action="backend/loginphp.php" name=login method="post" id="loginForm"
                  onsubmit="return submitLoginForm()">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input type="email" required class="form-control" id="username" name="email"
                      placeholder="Enter username">
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" required class="form-control" id="password" name="password"
                      placeholder="Enter password">

                  </div>
                  <button type="submit" class="btn btn-primary">Login</button>
                </form>
              </div>
              <hr>
              </hr>
              <div class="card-body">
                <!-- not registered? redirect to sign-up page -->
                <p>Not registered? <a href="frontend/sign-up.php">Sign-up</a></p>
              </div>
            </div>
          </div>


          <!-- logo -->
          <div class="col-md-4 mt-4">
            <div class="justify-content-right mt-4 ml-5">
              <img src="img/logo/logo.png" class="img-fluid" style="max-width:50vh; max-height=50vh">
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>


  <?php include "common/footer.php"; ?>

</body>

</html>