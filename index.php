<!-- form della pagina di login  -->
<?php
  $root = $_SERVER['DOCUMENT_ROOT'];
  include_once "$root/lib/util.php"; 
  session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PGEU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>
    <div class="container-fluid pb-3" style="background-color: lightgray;text-align: center;">
      <div class="row">
        <div class="d-flex align-items-center justify-content-center">
          <h1>Piattaforma Gestione Esami Universitari</h1>
        </div>
      </div>
    </div>

    <div class="container mt-5 border">
      <h3 class="text-center">Autenticazione</h3>
      </br>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="user-usr" placeholder="username" name="user">
          <label for="user-usr">Nome Utente</label>

        </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" placeholder="inserisci la password" id="user-psw" name="pswd">
          <label for="user-psw">Password</label>
        </div>

        <div class="form-floating mb-3">
          <select class="form-select" aria-label="seleziona-profilo" id="user-profile" name="profile">
            <option selected>Seleziona profilo</option>
            <option value="segreteria">Segreteria</option>
            <option value="docente">Docente</option>
            <option value="studente">Studente</option>
          </select>
        </div>

        <div class="d-flex align-items-center justify-content-center">
            <button type="submit" class="btn btn-primary" style="width: 200px; align-self: center;" value="Verify" name="verify">Accedi</button>
        </div>
      </form>
    </div>
    <?php

    if (count($_SESSION) == 0) {
      error_log('Session empty..');
      $auth = null;
    } else {
    // if session is running or the user enter the full path (but was logged in)
    // go to relative homepage
      header("Location: lib/{$_SESSION['profilo_utente']}.php");
      return;
    }


    // check if POST method -> user clicked "Login" button
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST['user'], $_POST['pswd'])) {
        $auth = login($_POST['user'], $_POST['pswd']);

        // show error on unsuccessful login
        if (is_null($auth)) {
        ?>
          <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
            <div class="p-3 mb-3 text-bg-danger rounded-1">Errore: nome_utente o password errati</div>
          </div>
        <?php
          return;
        } else {
          error_log("✅ Successful login, redirecting...\n");

          // save user data on successful login
          $_SESSION['nome_utente'] = $auth['nome_utente'];
          $_SESSION['profilo_utente'] = $_POST['profile'];

          if ($_POST['profile'] == null) {
            echo <<<EOD
            <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
              <div class="p-3 mb-3 text-bg-danger rounded-1">Errore profilo utente selezionato. Riprova.</div>
            </div>
            EOD;
            return;
          }

          // load profile-relative user homepage
          header("Location: lib/{$_SESSION['profilo_utente']}.php");
        }
      }
    }
    ?>
  </body>
</html>


