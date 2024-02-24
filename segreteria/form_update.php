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
  <title>PGEU: aggiornamento utente</title>
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
    header('Location: update_user.php');
}

if (isset($_GET['user'])) {
    $dati = get_user_data($_GET['user']);
    $_SESSION['user_old'] = $_GET['user'];

    if ($dati['profilo_utente'] == 'studente') {
        $dati2 = get_student_data($_GET['user']);
?>
<div class="container pt-1 pb-3 mt-5 border">
  <h3 class="text-center">Form aggiornamento studente</h3>
  <br>
  <p class="text-center"">Modifica i campi per lo studente: <b><?php echo ($_GET['user']) ?></b></p>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

    <div class="mb-3">
      <label for="nome_utente" class="form-label"><b>Username</b></label>
      <input type="text" value="<?php echo $dati['nome_utente'] ?>" class="form-control" id="nome_utente" name="nome_utente">
    </div>
    <div class="mb-3">
      <label for="cdl" class="form-label"><b>Corso di Laurea</b></label>
      <input type="text" value="<?php echo $dati2['cdl'] ?>" class="form-control" id="cdl" name="cdl">
    </div>
    <div class="mb-3">
      <label for="nome" class="form-label"><b>Nome</b></label>
      <input type="text" value="<?php echo $dati2['nome'] ?>"class="form-control" id="nome" name="nome">
    </div>
    <div class="mb-3">
      <label for="cognome" class="form-label"><b>Cognome</b></label>
      <input type="text" value="<?php echo $dati2['cognome'] ?>"class="form-control" id="cognome" name="cognome">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label"><b>e-mail</b></label>
      <input type="text" value="<?php echo $dati2['email'] ?>" class="form-control" id="email" name="email">
    </div>
    <button type="submit" class="btn btn-primary">Conferma Modifiche</button>
  </form>
</div>
<?php
    }

    if ($dati['profilo_utente'] == 'docente') {
        $dati2 = get_teacher_data($_GET['user']);
?>
<div class="container pt-1 pb-3 mt-5 border">
  <h3 class="text-center">Form aggiornamento docente</h3>
  <br>
  <p class="text-center">Modifica i campi per il docente: <b><?php echo ($_GET['user']) ?></b></p>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="mb-3">
      <label for="nome_utente" class="form-label"><b>Username</b></label>
      <input type="text" value="<?php echo $dati['nome_utente'] ?>" class="form-control" id="nome_utente" name="nome_utente">
    </div>
    <div class="mb-3">
      <label for="nome" class="form-label"><b>Nome</b></label>
      <input type="text" value="<?php echo $dati2['nome'] ?> "class="form-control" id="nome" name="nome">
    </div>
    <div class="mb-3">
      <label for="cognome" class="form-label"><b>cognome</b></label>
      <input type="text" value="<?php echo $dati2['cognome'] ?>" class="form-control" id="cognome" name="cognome">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label"><b>e-mail</b></label>
      <input type="text" value="<?php echo $dati2['email'] ?>" class="form-control" id="email" name="email">
    </div>

    <button type="submit" class="btn btn-primary">Conferma Modifiche</button>
  </form>
</div>
<?php
    }
}

if (!isset($_POST['cdl']) && isset($_POST['nome_utente']) && isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['email'])) {
    $result = update_teacher($_SESSION['user_old'], $_POST['nome_utente'], $_POST['nome'], $_POST['cognome'], $_POST['email']);

    switch ($result) {
        case null:
            $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nell'aggiornamento</div>";
            show_html_result($show_result);
            break;

        case 'ok':
            $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>aggiornamento riuscito</div>";
            show_html_result($show_result);
            break;

        default:
            $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>{$result}</div>";
            show_html_result($show_result);
            break;
    }

    unset($_SESSION['user_old']);

    //   se cdl e' inserito si tratta di uno studente
} else if (isset($_POST['cdl']) && isset($_POST['nome_utente']) && isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['email'])) {
    $result = update_student($_SESSION['user_old'], $_POST['nome_utente'], $_POST['cdl'], $_POST['nome'], $_POST['cognome'], $_POST['email']);

    $show_result = null;
    switch ($result) {
        case null:
            $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nell'aggiornamento</div>";
            break;

        case 'ok':
            $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>aggiornamento riuscito</div>";
            break;

        default:
            $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>{$result}</div>";
            break;
    }
    show_html_result($show_result);

    unset($_SESSION['user_old']);
}
?>

<div class="d-flex mt-3 align-items-center justify-content-center">
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" >        
    <button type="submit" id="back" name="back" class="btn btn-outline-secondary">torna alla pagina di selezione</button>
  </form>
</div>

<?php
