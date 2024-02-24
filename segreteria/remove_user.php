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
    <title>servizio rimozione utenti</title>
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

        include_once 'navbar.php';
    ?>

    <div class="container pt-1 pb-3 mt-5 border">
    <br>
        <h3 class="text-center">Rimozione Utente</h3>
      <br>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <div class="mb-3">
          <label for="tipo_rimozione" class="form-label">Seleziona la modalita' di rimozione</label>
          <select class="form-select" id="tipo_rimozione" name="tipo_rimozione">
            <option value="nome_utente">rimuovi per nome utente</option>
            <option value="profilo_utente">rimuovi per selezione</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Seleziona</button>
      </form>
      <?php

if (isset($_GET['tipo_rimozione']) && $_GET['tipo_rimozione'] == 'profilo_utente') {
    ?>

      <h3 class="text-center">Selezione il tipo di utente</h3>
      <br>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <div class="mb-3">
          <label for="tipo_rimozione" class="form-label">Seleziona profilo utente</label>
          <select class="form-select" id="tipo_rimozione" name="tipo_rimozione">
            <option value="studente">Studente</option>
            <option value="docente">Docente</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Seleziona</button>
      </form>

      <?php
}

if (isset($_GET['tipo_rimozione']) && $_GET['tipo_rimozione'] == 'nome_utente') {
    ?>

      <h3 class="text-center">Rimozione per nome utente</h3>
      <br>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="mb-3">
          <label for="nome_utente" class="form-label">Username</label>
          <input type="text" class="form-control" id="nome_utente" name="nome_utente">
        </div>
        <button type="submit" class="btn btn-primary">Conferma</button>

      </form>

      <?php
}

if (isset($_GET['tipo_rimozione']) && $_GET['tipo_rimozione'] == 'studente') {
?>

      <?php
    $studenti = get_students();
    ?>
      <h3 class="text-center text-secondary">seleziona lo studente da rimuovere</h3>
      <br>
      <table class="table table-bordered table-hover">
        <thead class="thead-light">
          <tr>
            <th style="text-align: center; font-weight: bold;">Username</th>
            <th style="text-align: center; font-weight: bold;">Matricola</th>
            <th style="text-align: center; font-weight: bold;">Nome</th>
            <th style="text-align: center; font-weight: bold;">Cognome</th>
          </tr>
        </thead>

        <?php foreach ($studenti as $s) { ?>
        <tr>
          <td style="text-align: center;"><?php echo $s['nome_utente']; ?></td>
          <td style="text-align: center;"><?php echo $s['matricola']; ?></td>
          <td style="text-align: center;"><?php echo $s['nome']; ?></td>
          <td style="text-align: center;"><?php echo $s['cognome']; ?></td>
          <th style="text-align: center;">
            <a
              href=<?php echo ("{$_SERVER['PHP_SELF']}?nome_utente={$s['nome_utente']}"); ?>
              onclick="return confirm('Confermi rimozione dello studente selezionato?')"
              class="link-danger">Rimuovi</a></th>
        </tr>


        <?php } ?>

      </table>
      <?php
}

if (isset($_GET['tipo_rimozione']) && $_GET['tipo_rimozione'] == 'docente') {
?>

      <?php
    $docenti = get_teachers();
    ?>
      <h3 class="text-center text-secondary">selezione il docente da rimuovere</h3>
      <br>
      <table class="table table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th style="text-align: center; font-weight: bold;">Username</th>
            <th style="text-align: center; font-weight: bold;">ID</th>
            <th style="text-align: center; font-weight: bold;">Nome</th>
            <th style="text-align: center; font-weight: bold;">Cognome</th>
          </tr>
        </thead>

        <?php foreach ($docenti as $d) { ?>
        <tr>
          <td style="text-align: center;"><?php echo $d['nome_utente']; ?></td>
          <td style="text-align: center;"><?php echo $d['id']; ?></td>
          <td style="text-align: center;"><?php echo $d['nome']; ?></td>
          <td style="text-align: center;"><?php echo $d['cognome']; ?></td>
          <th style="text-align: center;">
            <a
              href=<?php echo ("{$_SERVER['PHP_SELF']}?nome_utente={$d['nome_utente']}") ?>
              onclick="return confirm('Confermi rimozione del docente selezionato?')"
              class="link-danger">rimuovi</a></th>
        </tr>


        <?php } ?>

      </table>

      <?php
}

if (isset($_GET['nome_utente'])) {
    $result = remove_user($_GET['nome_utente']);

    switch ($result) {
        case null:
            $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nella rimozione </div>";
            show_html_result($show_result);
            break;

        case 'ok':
            $show_result = "<div class='d-flex align-items-center justify-content-center' style='height: 150px;'>";
            show_html_result($show_result);
            break;

        default:
            $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>{$result}</div>";
            show_html_result($show_result);
            break;
    }
}

if (isset($_POST) && isset($_POST['nome_utente'])) {
    $result = remove_user($_POST['nome_utente']);

    $show_result = null;
    switch ($result) {
        case null:
            $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nella rimozione</div>";
            break;

        case 'ok':
            $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>rimozione riuscita</div>";
            break;

        default:
            $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>{$result}</div>";
            break;
    }
    show_html_result($show_result);
}
?>
    </div>
  </body>
</html>
