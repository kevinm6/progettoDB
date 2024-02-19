<?php 
ini_set ("display_errors", "On");
ini_set("error_reporting", E_ALL);

$root = $_SERVER['DOCUMENT_ROOT'];
include_once "$root/lib/util.php"; 

session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>piattaforma universitaria segreteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

  </head>
  <body>

    <?php
    include_once 'navbar_seg.php';

    // se l'utente fa logout, inizializza la sessione
    if ($_SERVER["REQUEST_METHOD"] == 'GET') {
      if (isset($_GET['log']) && $_GET['log'] == 'del') {
        unset($_SESSION['user']);
        session_unset();
        $user = null;
      }
    }

    // se non vi e' una sessione valida aperta (presumibilmente in seguito ad un logout)
    // torno alla pagina di login
    if (!isset($_SESSION['nome_utente']) || $_SESSION['profilo_utente'] != 'segreteria') {
      session_unset();
      $user = null;
      header("Location: ../index.php");
    } else {
      $user = $_SESSION['nome_utente'];
    }

    // se utente e' loggato, prepara link per logout
    if (isset($user)) {
    ?>

    <div class="container mt-5">
      <div class="alert alert-primary" role="alert">
        <?php
          $dati = get_administration_data($user);
          echo("Benvenuto $user");
        ?>
        <br> 
      </div>
    </div>


    <br>
    <div class="container pt-3">
      <h3 class="text" style="text-align:center">I tuoi dati</h3><br>
      <div class="container border">
        <div class="row">        
          <div class="col p-2">
              <b>Nome:</b> <?php echo($dati["nome"])?>
            <br>
              <b>Cognome:</b> <?php echo($dati["cognome"])?>
            <br>
              <b>ID:</b> <?php echo($dati["id"])?>
          </div>
          <div class="col">
            <i>Servizi utente</i><br>
            <a href ="<?php echo("change_password.php"); ?>" class="link-primary"> cambia password</a>
          </div>
        </div>
      </div>
    </div>
    <?php
    } 
    ?>
  </body>
</html>
