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
    <title>servizio carriere valide segreteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</head>
    <body>
        <?php
            if (!isset($_SESSION['nome_utente']) || $_SESSION['profilo_utente'] != 'segreteria') {
                session_unset();
                $utente = null;
                header('Location: ../index.php');
            } else {
                $utente = $_SESSION['nome_utente'];
            }

            include_once 'navbar.php';
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Carriere valide degli studenti</h3>
          <br>
        <?php
            if (!isset($_GET['matricola'])) {
                // prelevo studenti sia da carriera che da storico
                $studenti = get_all_students();
        ?>
          <br>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th style="text-align: center;">Matricola</th>
                    <th style="text-align: center;">Nome</th>
                    <th style="text-align: center;">Cognome</th>
                    <th style="text-align: center;">E-mail</th>
                    <th style="text-align: center;">Seleziona</th>
                </tr>
            </thead>
          
            <?php foreach ($studenti as $s) { ?>
              <tr>
                  <td style="text-align: center;"><?php echo $s['matricola']; ?></td>
                  <td style="text-align: center;"><?php echo $s['nome']; ?></td>
                  <td style="text-align: center;"><?php echo $s['cognome']; ?></td>
                  <td style="text-align: center;"><?php echo $s['email']; ?></td>
          <th style="text-align: center;"><a
            href=<?php echo ("{$_SERVER['PHP_SELF']}?matricola={$s['matricola']}") ?>
            class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Apri</a></th>
              </tr>

          <?php } ?>

          </table>
        <?php
            }
            if (isset($_GET['matricola'])) {
                // controlla in quale tabella e' lo studente (storico o normale)
                $tipo_studente = check_student($_GET['matricola']);
                if ($tipo_studente == 'rimosso') {
                    $carriera = get_career_ok_removed_student($_GET['matricola']);
                    $studente = get_hist_student_data_from_id($_GET['matricola']);
                } else if ($tipo_studente == 'attivo') {
                    $carriera = get_career_ok($_GET['matricola']);
                    $studente = get_student_data_id($_GET['matricola']);
                }

                    ?>
            <h3 class="text-center"><?php echo ("{$studente['nome']} {$studente['cognome']}"); ?></h3>
          <br>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th style="text-align: center;">Insegnamento</th>
                    <th style="text-align: center;">Data</th>
                    <th style="text-align: center;">Voto</th>
                </tr>
            </thead>
          
            <?php foreach ($carriera as $c) {
                    $i = get_teaching_data($c['insegnamento'], $c['cdl']); ?>
              <tr>
                  <td style="text-align: center;"><?php echo $i['nome']; ?></td>
                  <td style="text-align: center;"><?php echo $c['data']; ?></td>
                  <td style="text-align: center;"><?php echo $c['voto']; ?></td>
              </tr>
            <?php } ?>
            </table>

        <?php } ?>
      </div>
    </body>
</html>
