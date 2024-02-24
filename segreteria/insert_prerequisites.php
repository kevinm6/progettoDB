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
    <title>PGEU: inserimento propedeuticità</title>
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
      <h3 class="text-center">Servizio inserimento propedeuticità</h3>
      <br>
      <?php
$cdls = get_courses();
?>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <div class="mb-3 pt-3">
          <label for="cdl" class="form-label"><b>Seleziona corso di laurea</b></label>
          <select class="form-select" id="cdl" name="cdl">
            <?php foreach ($cdls as $cdl) { ?>
            <option value="<?php echo $cdl['id']; ?>"> <?php echo $cdl['nome'] . ' (' . $cdl['tipologia'] . ')'; ?> </option>
            <?php } ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Conferma</button>
      </form>

      <?php
if (isset($_GET['cdl'])) {
    $_SESSION['cdl'] = $_GET['cdl'];
    $teachings = get_teaching($_GET['cdl']);
    ?>
      <br>
      <h3 class="text-center">Seleziona gli insegnamenti</h3>
      <br>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="mb-3 pt-3">

          <label for="propedeutico_a" class="form-label"><b>Seleziona insegnamento per il quale si vuole aggiungere una propedeuticità</b></label>
          <select class="form-select" id="propedeutico_a" name="propedeutico_a">
            <?php foreach ($teachings as $t) { ?>
            <option value="<?php echo $t['codice_univoco']; ?>"> <?php echo $t['nome']; ?> </option>
            <?php } ?>
          </select>
        </div>

        <div class="mb-3 pt-3">
          <label for="insegnamento" class="form-label"><b>Seleziona insegnamento propedeutico</b></label>
          <select class="form-select" id="insegnamento" name="insegnamento">
            <?php foreach ($teachings as $t) { ?>
            <option value="<?php echo $t['codice_univoco']; ?>"> <?php echo $t['nome']; ?> </option>
            <?php } ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Conferma</button>
      </form>

      <?php
}

if (isset($_POST['propedeutico_a']) && isset($_POST['insegnamento'])) {
    $result = insert_prerequisites($_POST['insegnamento'], $_POST['propedeutico_a'], $_SESSION['cdl']);

    $show_result = null;
    switch ($result) {
        case null:
            $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nell'inserimento della propedeuticita'</div>";
            break;

        case 'ok':
            $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>inserimento riuscito</div>";
            break;

        default:
            $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>{$result}</div>";
            break;
    }
    show_html_result($show_result);

    unset($_SESSION['cdl']);
}
?>
    </div>
  </body>
</html>
