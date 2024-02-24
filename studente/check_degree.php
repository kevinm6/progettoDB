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
    <title>servizio rinuncia agli studi</title>
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
        ?>

        <div class="container pt-1 pb-3 mt-5 border" style="max-width: 60%;">
          <?php
if (isset($_GET['nome_utente']) && $_GET['nome_utente'] == $_SESSION['nome_utente']) {
    $studente = get_student_data($_SESSION['nome_utente']);

    $carriera = get_career_ok($studente['matricola']);
    $esami_superati = array();
    foreach ($carriera as $c) {
        $esami_superati[] = $c['insegnamento'];
    }

    $teaching = get_teaching($studente['cdl']);

    $valido = true;

    foreach ($teaching as $i) {
        if (!in_array($i['codice_univoco'], $esami_superati))
            $valido = false;
    }

    if (!$valido) {
        ?>
                 <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-danger rounded-2">Lo studente non ha superato tutti gli esami, laurea non valida</div>
                </div>
                <div class="d-flex mt-3 align-items-center justify-content-center">
                    <a href="studente.php" class="btn btn-outline-secondary" style="width: 250px">← Torna alla home studente</a>
                </div>
                <?php
    } else {
        $result = remove_user($_GET['nome_utente']);

        if (!$result) {
            ?>
                <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-danger rounded-1">Errore nell'operazione richiesta</div>
                </div>
            <?php }
        if ($result) {
            session_unset();
            header('Location: ../index.php');
        }
    }
} ?>

      </div>
    </body>
</html>
