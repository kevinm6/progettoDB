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
    <title>PGEU • Esiti Esami</title>
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
        <h3 class="text-center">Esiti Esami</h3>
        <?php
            if (!isset($_GET['codice']) && !isset($_GET['cdl']) && !isset($_GET['data'])) {
                $res = get_teacher_data($_SESSION['nome_utente']);
                $esami = get_exams($res['id']);
        ?>
          <br>
          <h5 class="text-center text-secondary">Seleziona l'esame per il quale si vuole inserire l'esito</h6>
          <br>
          <table class="table table-bordered table-hover">
        <thead class="thead-light">
          <tr>
            <th style="text-align: center; font-weight: bold;">Data</th>
            <th style="text-align: center; font-weight: bold;">Insegnamento</th>
            <th style="text-align: center; font-weight: bold;">Corso di laurea</th>
          </tr>
        </thead>
          
            <?php foreach ($esami as $e) {
                    $cdl_name = get_course_name($e['cdl']); ?>
              <tr>
                <td style="text-align: center;"><?php
                    $date = new DateTime($e['data']);
                    $fmt_date = $date->format('d M Y');
                    echo $fmt_date;;
            ?></td>
                  <td style="text-align: center;"><?php echo $e['nome']; ?></td>
                  <td style="text-align: center;"><?php echo $cdl_name; ?></td>
          <th style="text-align: center;"><a
            href=<?php echo ("{$_SERVER['PHP_SELF']}?codice={$e['insegnamento']}&cdl={$e['cdl']}&data={$e['data']}"); ?>
            class="link-primary">Inserisci Voto</a></th>
              </tr>

            <?php }
            } ?>
            </table>
 

        <?php
            if (isset($_GET['codice']) && isset($_GET['cdl']) && isset($_GET['data'])) {
                $_SESSION['codice'] = $_GET['codice'];
                $_SESSION['cdl'] = $_GET['cdl'];
                $_SESSION['data'] = $_GET['data'];

                $subscribers = get_subscribers($_GET['codice'], $_GET['cdl'], $_GET['data']);
        if (!$subscribers) {
        $show_result = <<<EOD
        <br>
        <div class="container-sm" style="max-width: 600px; border-radius: 20px; background-color: #dcdcdc">
        <h4 class="text-center text-secondary" style="font-weight: bold;">Nessuno studente trovato per l'esame selezionato</h4>
        </div>
        <br>
        <div class="d-flex mt-3 align-items-center justify-content-center">
          <a href="insert_mark.php" class="btn btn-outline-secondary" style="width: 250px">← Torna alla selezione esame</a>
        </div>
        EOD;
        echo ($show_result);
        return;
        }
        ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-3 pt-3">
                    <label for="studente" class="form-label">Seleziona lo studente</label>
                    <select class="form-select" id="studente" name="studente">
                      <?php foreach ($subscribers as $i) { ?>
                        <option value="<?php echo $i['matricola']; ?>"> <?php echo "{$i['nome']} {$i['cognome']} (matricola {$i['matricola']})"; ?> </option>
                      <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="result" class="form-label">Voto</label>
                    <input type="text" class="form-control" id="result" name="result">
                </div>
          <button type="submit"
            class="btn btn-primary"
            onclick="return confirm('Confermi voto per lo studente selezionato?')"
          >Conferma Voto</button>
              </form>
              <?php
            }
            if (isset($_POST['studente']) && isset($_POST['result'])) {
                $result = insert_mark($_SESSION['codice'], $_SESSION['cdl'], $_SESSION['data'], $_POST['studente'], $_POST['result']);

                $show_result = null;
                switch ($result) {
                    case 'ok':
                        $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>inserimento riuscito</div>";
                        break;

                    case null:
                        $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nell'inserimento, controlla di aver inserito un voto valido</div>";
                        break;

                    default:
                        $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'><?php {$result}; ?></div>";
                        break;
                }
                show_html_result($show_result);

                unset($_SESSION['codice']);
                unset($_SESSION['cdl']);
                unset($_SESSION['data']);
            }
        ?>
        </div>
    </body>
</html>


