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
    <title>PGEU • Verifica Iscrizioni Esami</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

  </head>
  <body>
    <?php
        if (!isset($_SESSION['nome_utente']) || $_SESSION['profilo_utente'] != 'studente') {
            session_unset();
            $utente = null;
            header('Location: ../index.php');
        } else {
            $utente = $_SESSION['nome_utente'];
        }
        include_once 'navbar.php';

        if (isset($_GET['data']) && isset($_GET['studente'])) {
            $result = remove_subscription($_GET['data'], $_GET['studente']);
        }

        $student = get_student_data($_SESSION['nome_utente']);
        $subscriptions = get_subscriptions($student['matricola']);
        if (!$subscriptions) {
            $show_result = <<<EOD
                <br>
                <div class="container-sm" style="max-width: 600px; border-radius: 20px; background-color: lightgrey">
                <h4 class="text-center text-secondary" style="font-weight: bold;">Nessuna iscrizione ad esami trovata</h4>
                </div>
                <br>
                <div class="d-flex mt-3 align-items-center justify-content-center">
                <a href="studente.php" class="btn btn-outline-secondary" style="width: 250px">← Torna alla home studente</a>
                </div>
                EOD;
            echo ($show_result);
            return;
        }
    ?>

    <div class="container pt-1 pb-3 mt-5 border">
      <br>
      <h3 class="text-center">Iscrizioni confermate agli esami</h3>
      <br>
      <table class="table table-bordered table-hover">
        <thead class="thead-light">
          <tr>
            <th style="text-align: center; font-weight: bold;">Nome</th>
            <th style="text-align: center; font-weight: bold;">Data</th>
          </tr>
        </thead>

        <?php foreach ($subscriptions as $i) {
            $teaching = get_teaching_name($i['cdl'], $i['insegnamento']); ?>

        <tr>
          <td style="text-align: center;"><?php echo $teaching; ?></td>
          <td style="text-align: center;"><?php
            $date = new DateTime($i['data']);
            $fmt_date = $date->format('d M Y');
            echo $fmt_date;;
        ?></td>
          <th
            class="text-center">
            <a href=<?php echo "{$_SERVER['PHP_SELF']}?data={$i['data']}&studente={$i['studente']}"; ?>
              onclick="return confirm('Confermi rimozione iscrizione?')"
              class="link-danger">Rimuovi iscrizione</a></th>
        </tr>
        <?php } ?>
      </table>

      <?php
$show_result = null;
if (isset($result)) {
    if (!$result) {
        $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nella rimozione</div>";
    } else {
        $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>rimozione riuscita</div>";
    }
    $html_result = <<<EOD
        <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
        {$show_result}
        </div>
        EOD;
    show_html_result($html_result);
}
?>
    </div>


  </body>
  <script>
document.getElementById('nav_student_id').innerHTML = `
<u><b><?php echo ($_SESSION['nome_utente']) ?></b></u>
<img src='https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png' alt='profile_icn' width="30" height="30">
`;
  </script>
</html>
