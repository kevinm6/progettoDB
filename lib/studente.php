<?php 
  ini_set ("display_errors", "On");
  ini_set("error_reporting", E_ALL);
  include_once '../util.php'; 
  session_start();

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>piattaforma universitaria studente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

  </head>
    <body>

        <?php

         // se l'utente fa logout, inizializza la sessione
        if (isset($_GET) && isset($_GET['log']) && $_GET['log'] == 'del') {
            // unset($_SESSION['user']);
            session_unset();
            $utente = null;
        }
        
        // se non vi e' una sessione valida aperta (presumibilmente in seguito ad un logout)
        // torno alla pagina di login
        if (!isset($_SESSION['user']) || $_SESSION['profilo_utente'] != 'studente') {
            session_unset();
            $utente = null;
            header("Location: ../../index.php");
        } else {
            $utente = $_SESSION['user'];
        }

        // se utente e' loggato, prepara link per logout
        if (isset($utente)) {
          $logout_link = $_SERVER['PHP_SELF']."?log=del";
        ?>

        <div class="container mt-5">
          <div class="alert alert-primary" role="alert">
            <?php
                $dati = get_dati_studente($utente);
                $corso = get_dati_corso($dati['corso_di_laurea']);
            ?>  
                <!-- stampa messaggio di benvenuto, e link di logout -->
                <?php echo("Benvenuto $utente"); ?> <br> 
                <a href ="<?php echo($logout_link); ?>" class="alert-link"> Logout </a>
          </div>
        </div>
        
        <!-- corpo con i dati -->
        <div class="container pt-3">
            <h3 class="text">- I tuoi dati</h3>
            <div class="container border">
              <div class="row">        
                <div class="col p-2">
                    
                        Nome: <?php echo($dati["nome"])?>
                        <br>
                        Cognome: <?php echo($dati["cognome"])?>
                        <br>
                        ID: <?php echo($dati["matricola"])?>
                        <br>
                        Corso di laurea: <?php echo($corso["nome"])?>
                    
                </div>
                <div class="col">
                    Servizi utente: <br>
                    <a href ="<?php echo("../change_password.php"); ?>" class="link-primary"> cambia password</a>
                </div>
              </div>
            </div>
        </div>
        <?php
        } 
        ?>
        <div class="container mt-5">
          <h3> - operazioni</h3>
          <nav class="navbar navbar-expand-lg bg-body-tertiary">
              <div class="container-fluid">
                  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <li class="nav-item">
                      <a class="nav-link" href="studente.php">Home</a>
                      </li>
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle"  role="button" data-bs-toggle="dropdown" aria-expanded="false">carriere</a>
                          <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="lib/carriera_completa.php">carriera completa</a></li>
                              <li><a class="dropdown-item" href="lib/carriera_valida.php">carriera valida</a></li>
                          </ul>
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle"  role="button" data-bs-toggle="dropdown" aria-expanded="false">esami</a>
                          <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="lib/iscrizione_esami.php">iscrizione_esami</a></li>
                              <li><a class="dropdown-item" href="lib/controlla_iscrizioni.php">controlla iscrizioni</a></li>
                          </ul>
                      </li>
                      <li class="nav-item">
                      <a class="nav-link" href="lib/informazioni_corsi.php">informazioni corsi</a>
                      </li>
                      <li class="nav-item">
                      <a class="nav-link" href="lib/rinuncia_agli_studi.php">rinuncia agli studi</a>
                      </li>
                      <li class="nav-item">
                      <a class="nav-link" href="lib/laurea.php">laurea</a>
                      </li>
                      
                  </ul>
              </div>
          </nav>
      </div> 
    </body>
</html>
