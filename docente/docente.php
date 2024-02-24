<?php 
ini_set ("display_errors", "On");
ini_set("error_reporting", E_ALL);

include_once '../lib/util.php'; 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PGEU: Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
  </head>
  <body>

    <?php
    if (isset($_GET) && isset($_GET['log']) && $_GET['log'] == 'del') {
      session_unset();
      $user=null;
    }

    if (!isset($_SESSION['nome_utente']) || $_SESSION['profilo_utente'] != 'docente') {
      session_unset();
      $user = null;
      header("Location: ../index.php");
      } else {
      $user = $_SESSION['nome_utente'];
    }

    if (isset($user)) {
      include_once 'navbar.php';
    ?>

    <div class="container mt-5" style="max-width: 60%;">
      <div class="alert alert-primary text-center" role="alert">
        <?php
        $dati = get_teacher_data($user);
        ?>  
        <?php echo("Benvenuto <b>$user</b>"); ?> <br> 
      </div>
    </div>

    <h3 class="text-center">I tuoi dati</h3>
    <br>
    <div class="container text-center">
      <div class="row">
        <div class="col-4 p-2 border-0">
          <img width="300px" style="border-radius: 30px;" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png">
        </div>
        <div class="col p-2 border text-center">
          <div class="flex-row text-secondary">Nome</div>
          <div class="flex-row" style="font-weight: bold;">
              <?php echo($dati["nome"])?>
          </div>
            <br>
          <div class="flex-row text-secondary">Cognome</div>
          <div class="flex-row" style="font-weight: bold;">
              <?php echo($dati["cognome"])?>
          </div>
          <br>
          <div class="flex-row text-secondary">ID</div>
          <div class="flex-row" style="font-weight: bold;">
              <?php echo($dati["id"])?>
          </div>
            <br>
          <div class="flex-row text-secondary">E-Mail</div>
          <div class="flex-row" style="font-weight: bold;">
              <?php echo($dati["email"])?>
          </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    } 
    ?>
  </body>
</html>
