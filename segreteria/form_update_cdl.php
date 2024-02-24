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
    <title>servizio aggiornamento corso di laurea</title>
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

        if (isset($_GET['back'])) {
            header('Location: update_course.php');
        }
      include_once 'navbar.php';

        if (isset($_GET['id'])) {
            $dati = get_course_data($_GET['id']);
            // salvo l'id del corso in una variabile session
            $_SESSION['id'] = $_GET['id'];

            // prelevo i dati del vecchio responsabile e lo aggiungo alla lista dei responsabili elegibili, ponendolo in cima in modo che sia la prima scelta
            $old_docente = get_teacher_data_from_id($dati['responsabile']);
            $docenti = array_merge(array($old_docente), get_teachers_candidates())
    ?>

    <div class="container pt-1 pb-3 mt-5 border">
      <h3 class="text-center">Form aggiornamento corsi di laurea</h3>
      </br>
      <h5 class="text-center text-secondary">Modifica i campi che si desidera modificare per il corso <?php echo ($dati['nome'] . ' ' . $dati['tipologia']); ?></h5>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

        <div class="mb-3 pt-3">
          <label for="responsabile" class="form-label">Seleziona Responsabile </label>
          <select class="form-select" id="responsabile" name="responsabile">
            <?php foreach ($docenti as $d) { ?>
            <option value="<?php echo $d['id']; ?>"> <?php echo $d['nome'] . ' ' . $d['cognome']; ?> </option>
            <?php } ?>
          </select>
        </div>
        <?php if ($dati['tipologia'] == 'triennale') { ?>
        <div class="mb-3">
          <label for="tipologia" class="form-label">Tipologia</label>
          <select class="form-select" id="tipologia" name="tipologia">
            <option value="triennale" selected>triennale</option>
            <option value="magistrale">magistrale</option>
          </select>
        </div>
        <?php } else if ($dati['tipologia'] == 'magistrale') { ?>
        <div class="mb-3">
          <label for="tipologia" class="form-label">Tipologia</label>
          <select class="form-select" id="tipologia" name="tipologia">
            <option value="triennale">triennale</option>
            <option value="magistrale" selected>magistrale</option>
          </select>
        </div>
        <?php } ?>
        <div class="mb-3">
          <label for="nome" class="form-label">Nome</label>
          <input type="text" value="<?php echo $dati['nome'] ?>"class="form-control" id="nome" name="nome">
        </div>
        <button type="submit" class="btn btn-primary">Conferma</button>
      </form>
    </div>
    <?php
        }

        if (isset($_POST['responsabile']) && isset($_POST['tipologia']) && isset($_POST['nome'])) {
            $result = update_cdl($_SESSION['id'], $_POST['responsabile'], $_POST['tipologia'], $_POST['nome']);

            $show_result = null;
            switch ($result) {
                case 'ok':
                    $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>aggiornamento riuscito</div>";
                    break;
                case null:
                    $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nell'aggiornamento</div>";
                    break;

                default:
                    $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>{$result}</div>";
                    break;
            }
            show_html_result($show_result);
            unset($_SESSION['id']);
        }
    ?>
    <div class="d-flex mt-3 align-items-center justify-content-center">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" >        
        <button type="submit" id="back" name="back" class="btn btn-outline-secondary">← torna alla pagina di selezione</button>
      </form>
    </div>
  </body>
</html>
