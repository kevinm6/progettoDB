<?php 
  ini_set ("display_errors", "On");
  ini_set("error_reporting", E_ALL);
  include_once '../lib/util.php'; 
  session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PGEU • Laurea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</head>
    <body>
        <?php
        if(!isset($_SESSION['nome_utente']) || $_SESSION['profilo_utente'] != 'studente'){
            session_unset();
            $utente = null;
            header("Location: ../index.php");
        } else {
            $utente = $_SESSION['nome_utente'];
        }

        include_once 'navbar.php';
        ?>

        <div class="container pt-1 pb-3 mt-5 border" style="max-width: 65%;">
          <h3 class="text-center">Laurea</h3>
          <br>
          <h5 class="text-center text-danger">ATTENZIONE</h5>
          <h5 class="text-center text-secondary text-opacity-75">Continuando si controllerà che l'utente abbia superato positivamente tutti gli esami.<br/>In caso di successo l'utente verrà rimosso<br/>e non sarà più possibile effettuare il login.<br/>L'azione è irreversibile</h5>
          <div class="d-flex mt-3 align-items-center justify-content-center">
        <a href=<?php echo("check_degree.php?nome_utente={$_SESSION['nome_utente']}"); ?> class="btn btn-outline-danger btn-opacity-75" style="width: 250px"
          onclick="return confirm('Confermi verifica della laurea?')"
        >Visualizza</a>
          </div>
      </div>
    </body>
  <script>
document.getElementById('nav_student_id').innerHTML = `
<u><b><?php echo ($_SESSION['nome_utente']) ?></b></u>
<img src='https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png' alt='profile_icn' width="30" height="30">
`;
  </script>
</html>
