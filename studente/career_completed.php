<?php
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);

include_once '../lib/util.php';
session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PGEU: carriera studente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</head>
    <body>
        <?php
            if (!isset($_SESSION['nome_utente']) || $_SESSION['profilo_utente'] != 'studente') {
                session_unset();
                $user = null;
                header('Location: ../index.php');
            } else {
                $user = $_SESSION['nome_utente'];
            }

            include_once 'navbar.php';
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Carriera</h3>
          <br>
        <?php
            $student = get_student_data($user);
            $career = get_career_completed($student['matricola']);
        ?>
            <h3 class="text-center" style="font-weight: bold;"><?php echo ("{$student['nome']} {$student['cognome']}"); ?></h3>
          <br>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                  <th style="text-align: center; font-weight: bold;">Insegnamento</th>
                  <th style="text-align: center; font-weight: bold;">Data</th>
                  <th style="text-align: center; font-weight: bold;">Esito</th>
                  <th style="text-align: center; font-weight: bold;">Voto</th>
                </tr>
            </thead>
          
            <?php foreach ($career as $c) {
                $i = get_teaching_data($c['insegnamento'], $c['cdl']); ?>
              <tr>
                  <td style="text-align: center;"><?php echo $i['nome']; ?></td>
                  <td style="text-align: center;"><?php
                $date = new DateTime($c['data']);
                $fmt_date = $date->format('d M Y');
                echo $fmt_date;
            ?></td>
                  <td style="text-align: center;"><?php echo $c['esito']; ?></td>
                  <td style="text-align: center;"><?php echo $c['voto']; ?></td>
              </tr>
            <?php } ?>
            </table>
      </div>
    </body>
</html>
