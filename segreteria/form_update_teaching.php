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
  <title>PGEU: aggiornamento insegnamenti</title>
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
    header('Location: update_teaching.php');
}

if (isset($_GET['codice']) && isset($_GET['cdl'])) {
    // prelevo i dati dell'insegnamento, del responsabile e i possibili nuovi responsabili
    $dati = get_teaching_data($_GET['codice'], $_GET['cdl']);

    $_SESSION['codice'] = $_GET['codice'];
    $_SESSION['cdl'] = $_GET['cdl'];
    $old_docente = get_teacher_data_from_id($dati['responsabile']);
    $docenti = get_teachers_candidates_teaching();

    // controllo che il vecchio responsabile sia nella lista dei possibili responsabili (in caso non vogliamo cambiarlo e lo metto in cima)
    $presente = false;
    foreach ($docenti as $d) {
        if ($old_docente['id'] == $d['id']) {
            $presente = true;
        }
    }

    if ($presente == false) {
        $docenti = array_merge(array($old_docente), $docenti);
    }
?>

<div class="container pt-1 pb-3 mt-5 border">
  <h3 class="text-center">Form aggiornamento insegnamento</h3>
  <br>
  <h5 class="text">Modifica i campi per l'insegnamento <b><?php echo ($dati['nome']); ?></b></h5>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

    <div class="mb-3 pt-3">
      <label for="responsabile" class="form-label">Seleziona Responsabile </label>
      <select class="form-select" id="responsabile" name="responsabile">
        <?php foreach ($docenti as $d) { ?>
        <option value="<?php echo $d['id']; ?>"> <?php echo $d['nome'] . ' ' . $d['cognome']; ?> </option>
        <?php } ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="nome" class="form-label">Nome</label>
      <input type="text" value="<?php echo $dati['nome'] ?>"class="form-control" id="nome" name="nome">
    </div>
    <div class="mb-3">
      <label for="descrizione" class="form-label">Descrizione</label>
      <input type="text" value="<?php echo $dati['descrizione'] ?>"class="form-control" id="descrizione" name="descrizione">
    </div>
    <div class="mb-3">
      <label for="anno" class="form-label">Anno</label>
      <input type="text" value="<?php echo $dati['anno'] ?>"class="form-control" id="anno" name="anno">
    </div>
    <button type="submit" class="btn btn-primary">Conferma</button>
  </form>
</div>
<?php
}

if (isset($_POST['responsabile']) && isset($_POST['descrizione']) && isset($_POST['anno']) && isset($_POST['nome'])) {
    $result = update_teaching($_SESSION['codice'], $_SESSION['cdl'], $_POST['responsabile'], $_POST['nome'], $_POST['descrizione'], $_POST['anno']);

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

    unset($_SESSION['codice']);
    unset($_SESSION['cdl']);
}
?>
<div class="d-flex mt-3 align-items-center justify-content-center">
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" >        
    <button type="submit" id="back" name="back" class="btn btn-outline-secondary">torna alla pagina di selezione</button>
  </form>
</div>
<?php
