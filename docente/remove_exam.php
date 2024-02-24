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
    <title>servizio rimozione esami</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
  </head>
  <body>
    <?php
        if (!isset($_SESSION['nome_utente']) || $_SESSION['profilo_utente'] != 'docente') {
            session_unset();
            $utente = null;
            header('Location: ../index.php');
        } else {
            $utente = $_SESSION['nome_utente'];
        }

        include_once 'navbar.php';

        if (isset($_GET['codice']) && isset($_GET['cdl']) && isset($_GET['data'])) {
            $result = remove_exam($_GET['codice'], $_GET['cdl'], $_GET['data']);
        }
    ?>

    <div class="container pt-1 pb-3 mt-5 border">
      <h3 class="text-center">Servizio rimozione esami</h3>

      <?php
$res = get_teacher_data($_SESSION['nome_utente']);
$exams = get_exams($res['id']);
?>
      <h4 class="text-center text-secondary">Seleziona l'esame da rimuovere</h4>
      <br>
      <table class="table table-bordered table-hover">
        <thead class="thead-light">
          <tr>
            <th style="text-align: center;">Data</th>
            <th style="text-align: center;">Insegnamento</th>
            <th style="text-align: center;">Corso di laurea</th>
          </tr>
        </thead>

        <?php foreach ($exams as $e) {
            $cdl = get_course_name($e['cdl']); ?>
        <tr>
          <td style="text-align: center;"><?php
            $date = new DateTime($e['data']); 
            $fmt_date = $date->format('d M Y');
            echo $fmt_date;
            ; ?></td>
          <td style="text-align: center;"><?php echo $e['nome']; ?></td>
          <td style="text-align: center;"><?php echo $cdl; ?></td>
          <th style="text-align: center;"><a
            href=<?php echo ("{$_SERVER['PHP_SELF']}?codice={$e['insegnamento']}&cdl={$e['cdl']}&data={$e['data']}") ?>
            onclick="return confirm('Confermi rimozione esame?')"
            class="link-danger"
          >Rimuovi</a></th>
        </tr>
        <?php } ?>

      </table>
      <?php
if (isset($result)) {
    $show_result = null;
    if (!$result) {
        $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>Errore nella rimozione</div>";
    } else {
        $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>Esame rimosso</div>";
    }
    show_html_result($show_result);
}
?>
    </div>
  </body>
</html>
