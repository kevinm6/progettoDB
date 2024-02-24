<?php
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);

include_once 'util.php';
session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PGEU: cambio password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>
    <?php
        if (isset($_POST['reset'])) {
            unset($password_changed);
            unset($_POST['reset']);
        }

        if (!isset($_SESSION['nome_utente'])) {
            session_unset();
            $user = null;
            header('Location: ../index.php');
            exit();
        } else {
            $user = $_SESSION['nome_utente'];
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['old-pswd'], $_POST['pswd'])) {
                $password_changed = change_password($user, md5($_POST['old-pswd']), $_POST['pswd']);

            if (!$password_changed)
              show_html_result("<div class='p-3 mb-3 text-bg-danger rounded-1'>La password attuale inserita non è corretta.</div>");
            }
        }
       if (!isset($password_changed) || !$password_changed) { ?>
    <div class="container mt-5 border" style="max-width: 60%;">
      <div class="text-center" style="margin-top: 4px;">
        <img class="center" src="https://cdn.pixabay.com/photo/2014/04/03/00/32/padlock-308589_1280.png" width="40px" height="40px">
      </div>
      <br>
      <h3 class="text-center">Servizio di modifica della password</h3>
      <h5 class="text-center text-secondary"><?php echo (ucfirst("{$_SESSION['profilo_utente']}")); ?></h5>
      <h5 class="text-center" style="font-weight: bold;"><?php echo "{$_SESSION['nome_utente']}"; ?></h5>
      <br>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

        <div class="mb-3">
          <label for="old-pswd">Password attuale</label><br>
          <input type="password" class="form-control" id="old-pswd" placeholder="inserici la password attuale" name="old-pswd">
        </div>

        <div class="mb-3">
          <label for="new-pswd">Nuova password</label>
          <input type="password" class="form-control" placeholder="inserisci la nuova password" id="new-pswd" name="pswd">
        </div>

        <div class="row mb-3">
          <div class="d-flex mt-3 align-items-center justify-content-center">
            <button
              type="submit" id="btn_change_pswd" class="btn btn-outline-danger"
              onclick="return confirm('Confermi modifica password?')"
              style="width: 20em" value="Verify" name="verify"
             disabled>Conferma password</button>
          </div>
        </div>
      </form>
      <?php
      ?>
    </div>
    <?php }
if (isset($password_changed) && $password_changed == 'ok') { ?>
    <div class="container mt-5 border border-dark-subtle border-2 bg-success-subtle">
      <h2 class="text-center">Password modificata con successo</h2>
      <br>
      <div class="row mb-3 justify-content-center">
        <div class="col-sm-2">
          <?php
    $profile = $_SESSION['profilo_utente'];
    $go_homepage_user = <<<EOD
        <a href="{$profile}.php" class="btn btn-primary" style="width: 150px">continua</a>
        EOD;
    echo $go_homepage_user;
    ?>
        </div>
        </div>
    </div>

    <?php
} else if (isset($password_changed) && $password_changed == 'error') {
    ?>
    <div class="container mt-5 border border-dark-subtle border-2 bg-danger-subtle">
      <h2 class="text-center">Errore nella modifica della password</h2>
      <br>
      <div class="row mb-3 justify-content-center">
        <div class="col-sm-3 ">
          <?php
    $profile = $_SESSION['profilo_utente'];
    $go_homepage_user = <<<EOD
        <div class="d-flex mt-3 align-items-center justify-content-center">
        <a href="../{$profile}/{$profile}.php" class="btn btn-outline-secondary" style="width: 250px">← Torna alla pagina utente</a>\
        </div>
        EOD;
    echo $go_homepage_user;
    ?>
      </div>
        <div class="col-sm-2">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <button type="submit" class="btn btn-primary" style="width: 250px" name="reset">riprova</button>
          </form>
        </div>
      </div>
    </div>
    <?php
}

if (!isset($password_changed) || !$password_changed) {
    $profile = $_SESSION['profilo_utente'];
    $go_homepage_user = <<<EOD
        <div class="d-flex mt-3 align-items-center justify-content-center">
        <a href="../{$profile}/{$profile}.php" class="btn btn-outline-secondary" style="width: 250px">← Torna alla home {$profile}</a>
        </div>
        EOD;
    echo $go_homepage_user;
}
?>
  </body>
  <script>
  const old_pswd_in = document.getElementById('old-pswd');
  const new_pswd_in = document.getElementById('new-pswd');
  const bnt_change_pswd = document.getElementById('btn_change_pswd');

  let pswds_in = false;
  new_pswd_in.addEventListener('input', ev => {
    if (old_pswd_in.value != "" && new_pswd_in.value != "") {
      console.log(ev);
      bnt_change_pswd.removeAttribute('disabled');
    } else {
      bnt_change_pswd.disabled = true;
    }
  });

</script>
</html>

