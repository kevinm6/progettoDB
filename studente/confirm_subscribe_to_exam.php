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
    <title>PGEU • Iscrizione Esami</title>
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
            }

          include_once 'navbar.php';
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Servizio iscrizione esami</h3>
          <?php
if (isset($_GET['codice']) && isset($_GET['cdl']) && isset($_GET['data'])) {
    $studente = get_student_data($_SESSION['nome_utente']);
    $result = exam_subscription($_GET['codice'], $_GET['cdl'], $studente['matricola'], $_GET['data']);

      $show_result = null;
    switch ($result) {
      case null:
      $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>è già presente una iscrizione per questo esame</div>";
      break;

      case 'ok':
      $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>iscrizione avvenuta con successo</div>";
      break;

      default:
        $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>{$result}</div>";
      break;
    }
      show_html_result($show_result);
}

?>
            </table>
        </div>
        <div class="d-flex mt-3 align-items-center justify-content-center">
                    <a href="exams_subscription.php" class="btn btn-outline-secondary" style="width: 250px">← Torna alla selezione</a>
        </div>
    </body>
  <script>
document.getElementById('nav_student_id').innerHTML = `
<u><b><?php echo ($_SESSION['nome_utente']) ?></b></u>
<img src='https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png' alt='profile_icn' width="30" height="30">
`;
  </script>
</html>
