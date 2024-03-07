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
    <div class="container-fluid pb-3" style="background-color: lightgray; box-sizing: border-box; padding-top: 10px; text-anchor: middle; border-radius: 20px;">
      <div class="row">
        <div class="d-flex align-items-center justify-content-center">
          <h1><b>P</b>iattaforma <b>G</b>estione <b>E</b>sami <b>U</b>niversitari</h1>
        </div>
      </div>
    </div>

    <div class="container mt-5 border" style="max-width: 65%;">
      <br>
      <h3 class="text-center">Autenticazione</h3>
      <br>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

        <div class="mb-3">
          <label for="user-usr" class="text-secondary">Nome Utente</label><br>
          <input type="text" class="form-control" id="user-usr" placeholder="username" name="user">
        </div>
        <div class="mb-3">
          <label for="user-psw" class="text-secondary">Password</label><br>
          <input type="password" class="form-control" placeholder="inserisci la password" id="user-psw" name="pswd">
        </div>

        <div class="mb-3">
          <select class="form-select" aria-label="seleziona-profilo" id="user-profile" name="profile">
            <option selected>Seleziona profilo</option>
            <option value="segreteria">Segreteria</option>
            <option value="docente">Docente</option>
            <option value="studente">Studente</option>
          </select>
        </div>

        <div class="d-flex align-items-center justify-content-center">
            <button type="submit" class="btn btn-outline-primary" style="width: 200px; align-self: center;" value="Verify" name="verify">Accedi</button>
        </div>
        <br>
      </form>
    </div>
    <?php

    if (count($_SESSION) == 0) {
      error_log('Session empty..');
      $auth = null;
    } else {
    // if session is running or the user enter the full path (but was logged in)
    // go to relative homepage
      header("Location: {$_SESSION['profilo_utente']}/{$_SESSION['profilo_utente']}.php");
      return;
    }


    // check if POST method -> user clicked "Login" button
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST['user'], $_POST['pswd'])) {
        $auth = login($_POST['user'], $_POST['pswd']);

        // show error on unsuccessful login
        if (!$auth) {
          show_html_result("<div class='p-3 mb-3 text-bg-danger rounded-1'>Errore: nome utente o password errati</div>");
          exit();
        } else {

          // FIX: security bug: user try to access with different profile and not save profile until successful login
          if ($_POST['profile'] == "Seleziona profilo") {
            show_html_result("<div class='p-3 mb-3 text-bg-danger rounded-1'>Errore: profilo utente non selezionato. Riprova.</div>");
            session_unset();
            exit();
          } elseif ($auth['profilo_utente'] != $_POST['profile']) {
            show_html_result("<div class='p-3 mb-3 text-bg-warning rounded-1'>Utente presente nel database ma non corrispondente al profilo selezionato.\nSelezionare il profilo corretto e riprovare.</div>");
            session_unset();
            exit();
          }

          // save user data on successful login
          $_SESSION['nome_utente'] = $auth['nome_utente'];
          $_SESSION['profilo_utente'] = $auth['profilo_utente'];

          error_log("✅ Successful login, redirecting...\n");
          // load profile-relative user homepage
          header("Location: {$auth['profilo_utente']}/{$auth['profilo_utente']}.php");
        }
      }
    }
    ?>
  </body>
</html>
