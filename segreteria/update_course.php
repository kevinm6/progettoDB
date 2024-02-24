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
    <title>PGEU: aggiornamento corso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</head>
    <body>
        <?php
        if(!isset($_SESSION['nome_utente']) || $_SESSION['profilo_utente'] != 'segreteria'){
            session_unset();
            $utente = null;
            header("Location: ../index.php");
        } else {
            $utente = $_SESSION['nome_utente'];
        }

        include_once 'navbar.php';
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Servizio aggiornamento corsi di laurea</h3>
        <br>
        <?php
        $corsi_di_laurea = get_courses(); ?>

            <h3 class="text-center">Selezione il corso di laurea</h3>
            <br>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="text-align: center;">Id</th>
                        <th style="text-align: center;">Responsabile</th>
                        <th style="text-align: center;">Tipologia</th>
                        <th style="text-align: center;">Nome</th>
                    </tr>
                </thead>
          
            <?php foreach ($corsi_di_laurea as $cdl) { 
                $docente = get_teacher_data_from_id($cdl['responsabile']); ?>
                <tr>
                    <td style="text-align: center;"><?php echo $cdl['id']; ?></td>
                    <td style="text-align: center;"><?php echo $docente['nome'] . " " . $docente['cognome']; ?></td>
                    <td style="text-align: center;"><?php echo $cdl['tipologia']; ?></td>
                    <td style="text-align: center;"><?php echo $cdl['nome']; ?></td>
                    <th style="text-align: center;"><a href=<?php echo("form_update_cdl.php"."?id=" . $cdl['id'] )?> class="link-primary">Aggiorna</a></th>
                </tr>
            <?php } ?>
            </table>
      </div>
    </body>
</html>
