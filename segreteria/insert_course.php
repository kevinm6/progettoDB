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
    <title>piattaforma universitaria inserimento corso di laurea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

  </head>
  <body>
    <?php
        // se non vi e' una sessione valida aperta (presumibilmente in seguito ad un logout)
        // torno alla pagina di login
        if (!isset($_SESSION['nome_utente']) || $_SESSION['profilo_utente'] != 'segreteria') {
            session_unset();
            $utente = null;
            header('Location: ../index.php');
        }

        include_once 'navbar.php';
    ?>

    <div class="container pt-1 pb-3 mt-5 border">
      <h3 class="text-center">Servizio inserimento corso di laurea</h3>
      <?php

// prelevo solo i responsabili che non hanno ancora un cdl assegnato
$responsabili = get_teachers_candidates();
?>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="mb-3 pt-3">
          <label for="responsabile" class="form-label">Seleziona responsabile</label>
          <select class="form-select" id="responsabile" name="responsabile">
            <?php foreach ($responsabili as $r) { ?>
            <option value="<?php echo $r['id']; ?>"> <?php echo $r['nome'] . ' ' . $r['cognome']; ?> </option>
            <?php } ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="tipologia" class="form-label">Tipologia</label>
          <select class="form-select" id="tipologia" name="tipologia">
            <option value="triennale">triennale</option>
            <option value="magistrale">magistrale</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="nome" class="form-label">Nome</label>
          <input type="text" class="form-control" id="nome" name="nome">
        </div>
        <button type="submit" class="btn btn-primary">Invia</button>

      </form>
    </div>

    <?php
        if (isset($_POST) && isset($_POST['responsabile']) && isset($_POST['tipologia']) && isset($_POST['nome'])) {
            $result = insert_cdl($_POST['responsabile'], $_POST['tipologia'], $_POST['nome']);

            $show_result = null;
            switch ($result) {
                case null:
                    $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nell'inserimento del corso di laurea</div>";
                    break;

                case 'ok':
                    $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>inserimento riuscito</div>";
                    break;

                default:
                    $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>{$result}</div>";
                    break;
            }
          show_html_result($show_result);
        }
    ?>
  </body>
</html>


