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
    <title>PGEU: Inserimento Esame</title>
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
            include_once 'navbar.php';
        }

        if (isset($_GET['back'])) {
            header('Location: insert_exam.php');
        }

        if (isset($_GET['codice']) && isset($_GET['cdl'])) {
            $_SESSION['codice'] = $_GET['codice'];
            $_SESSION['cdl'] = $_GET['cdl'];

          $exam_name = get_teaching_name($_SESSION['cdl'], $_SESSION['codice']);
    ?>
    <div class="container pt-1 pb-3 mt-5 border">
      <h3 class="text-center text-secondary">Inserimento Esame</h3>
      <br>
      <h3 class="text-center" style="font-weight: bold;"><?php echo $exam_name; ?></h3>
      <br>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">    
        <div class="mb-3">
          <label for="data" class="form-label text-secondary">seleziona la data dell'esame</label>
          <input type="date" class="form-control" id="data" name="data">
        </div>
        <button type="submit" class="btn btn-primary"
          onclick="return confirm('Confermare inserimento esame di <?php echo($exam_name) ;?>?')"
        >Conferma</button>
      </form>
    </div>
    <?php
        }

        if (isset($_POST['data'])) {
            $result = insert_exam($_POST['data'], $_SESSION['codice'], $_SESSION['cdl']);

            $show_result = null;
            switch ($result) {
                case 'ok':
                    $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>inserimento riuscito</div>";
                    break;

                case null:
                    $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nell'inserimento, 2 esami appartenenti allo stesso corso di laurea non possono avere lo stesso giorno</div>";
                    break;

                default:
                    $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>{$result}</div>";
                    break;
            }

            show_html_result($show_result);

            unset($_SESSION['codice']);
            unset($_SESSION['cdl']);
        }
    ?>

    <div class="d-flex mt-3 align-items-center justify-content-center">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" >        
        <button type="submit" id="back" name="back" class="btn btn-outline-secondary">← torna alla pagina di selezione</button>
      </form>
    </div>
  </body>
  </html>
