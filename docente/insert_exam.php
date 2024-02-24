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
    <title>PGEU: Inserimento Esami</title>
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
            }

            include_once 'navbar.php';
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Servizio inserimento esami</h3>
          <?php
$res = get_teacher_data($_SESSION['nome_utente']);
$teaching = get_teaching_from_responsible($res['id']);
?>
            <h5 class="text-center text-secondary">seleziona l'insegnamento per il quale si vuole inserire un esame</h5>
            <br>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                      <th style="text-align: center; font-weight: bold;">Codice</th>
                      <th style="text-align: center; font-weight: bold;">Corso di laurea</th>
                      <th style="text-align: center; font-weight: bold;">Nome</th>
                      <th style="text-align: center; font-weight: bold;">Descrizione</th>
                      <th style="text-align: center; font-weight: bold;">Anno</th>
                    </tr>
                </thead>
          
            <?php foreach ($teaching as $i) {
                $cdl = get_course_name($i['cdl']); ?>
                <tr>
                    <td style="text-align: center;"><?php echo $i['codice_univoco']; ?></td>
                    <td style="text-align: center;"><?php echo $cdl; ?></td>
                    <td style="text-align: center;"><?php echo $i['nome']; ?></td>
                    <td style="text-align: center;"><?php echo $i['descrizione']; ?></td>
                    <td style="text-align: center;"><?php echo $i['anno']; ?></td>
          <th style="text-align: center;"><a
            href=<?php echo ("insert_exam_form.php?codice={$i['codice_univoco']}&cdl={$i['cdl']}"); ?> class="link-primary"
          >Inserisci</a></th>
                </tr>
            <?php } ?>
            </table>
        </div>
    </body>
</html>
