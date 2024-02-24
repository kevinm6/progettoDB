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
    <title>PGEU • Info Corsi</title>
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

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Informazioni Corso di Laurea</h3>
          <br>
        <?php
            if (!isset($_GET['cdl'])) {
                $courses = get_courses();
        ?>
            <h3 class="text-center text-secondary">Seleziona un corso di laurea per vedere i suoi corsi</h3>
          <br>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th style="text-align: center; font-weight: bold;">Nome</th>
                    <th style="text-align: center; font-weight: bold;">Tipologia</th>
                    <th style="text-align: center; font-weight: bold;">Responsabile</th>
                </tr>
            </thead>
          
            <?php foreach ($courses as $c) {
                    $res = get_teacher_data_from_id($c['responsabile']); ?>
              <tr>
                  <td style="text-align: center;"><?php echo $c['nome']; ?></td>
                  <td style="text-align: center;"><?php echo $c['tipologia']; ?></td>
                  <td style="text-align: center;"><?php echo $res['nome'] . ' ' . $res['cognome']; ?></td>
          <th style="text-align: center;"><a href=<?php echo ("{$_SERVER['PHP_SELF']}?cdl={$c['id']}") ?>
            class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Apri</a></th>
              </tr>
            <?php } ?>
            </table>

            <?php } else if (isset($_GET['cdl'])) {
      $teaching = get_teaching($_GET['cdl']);
      $course_name = get_course_name($_GET['cdl']);
      ?>

                <h4 class="text-center text-secondary">Insegnamenti presenti per il corso di laurea</h4>
                <h3 class="text-center" style="font-weight: bold;"><?php echo($course_name); ?></h3>
                <br>
                <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="text-align: center; font-weight: bold;">Nome</th>
                        <th style="text-align: center; font-weight: bold;">Responsabile</th>
                        <th style="text-align: center; font-weight: bold;">Descrizione</th>
                        <th style="text-align: center; font-weight: bold;">Anno</th>
                    </tr>
                </thead>
                
                <?php foreach ($teaching as $i) {
                    $res = get_teacher_data_from_id($i['responsabile']); ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $i['nome']; ?></td>
                        <td style="text-align: center;"><?php echo "{$res['nome']} {$res['cognome']}"; ?></td>
                        <td style="text-align: center;"><?php echo $i['descrizione']; ?></td>
                        <td style="text-align: center;"><?php echo $i['anno']; ?></td>
                    </tr>
                <?php } ?>
                </table>
                <br>
                <br>
                
      <?php
                $result = null;

                $prerequisites = get_prerequisites($_GET['cdl']);
                if (!$prerequisites) {
                    $result = <<<EOD
                        <h5 class="text-center text-secondary">Nessuna propedeuticità trovata per il corso selezionato</h5><br>
                        <div class="d-flex mt-3 align-items-center justify-content-center">
                            <a href="course_information.php" class="btn btn-outline-secondary" style="width: 250px">← Torna alla selezione</a>
                        </div>
                        EOD;
                } else {
                    $result = <<<EOD
                        <h5 class="text-center text-secondary">Propedeuticità per il corso selezionato</h5>
                        <br>
                        <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                          <tr>
                            <th style="text-align: center; font-weight: bold;">insegnamento</th>
                            <th style="text-align: center; font-weight: bold;">propedeutico a</th>
                          </tr>
                        </thead>
                        EOD;

                    foreach ($prerequisites as $p) {
                        $t1 = get_teaching_name($_GET['cdl'], $p['insegnamento']);
                        $t2 = get_teaching_name($_GET['cdl'], $p['propedeutico_a']);

                        $result .= <<<EOD
                            <tr>
                              <td style="text-align: center;">{$t1}</td>
                              <td style="text-align: center;">{$t2}</td>    
                            </tr>
                            EOD;
                    }
                    $result .= <<<EOD
                        </table>
                        <br>
                        <div class="d-flex mt-3 align-items-center justify-content-center">
                          <a href="course_information.php" class="btn btn-outline-secondary" style="width: 250px">← Torna alla selezione</a>
                        </div>
                        EOD;
                }
                echo ($result);
            ?>
                

      <?php } ?>
      </div>
    </body>
  <script>
document.getElementById('nav_student_id').innerHTML = `
<u><b><?php echo ($_SESSION['nome_utente']) ?></b></u>
<img src='https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png' alt='profile_icn' width="30" height="30">
`;
  </script>
</html>
