<?php 
  ini_set ("display_errors", "On");
  ini_set("error_reporting", E_ALL);
  include_once ('funzioni.php'); 
  session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>servizio inserimento esame</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</head>
    <body>
        <?php
        ini_set("display_errors", "0");
        ini_set("error_reporting", "0");
        // se non vi e' una sessione valida aperta (presumibilmente in seguito ad un logout)
        // torno alla pagina di login
        if(!isset($_SESSION['user']) || $_SESSION['tipo_utente'] <> 'docente'){
            session_unset();
            $utente = null;
            header("Location:../index.php");
        } else {
            $utente = $_SESSION['user'];
        }

        if(isset($_GET['back'])){
            header("Location: inserisci_esame.php");
        }

        if (isset($_GET['codice']) && isset($_GET['cdl'])) {
            $_SESSION['codice'] = $_GET['codice'];
            $_SESSION['cdl'] = $_GET['cdl']; ?>
                <div class="container pt-1 pb-3 mt-5 border">
                <h3 class="text-center">Form inserimento esame</h3>
                </br>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">    
                    <div class="mb-3">
                        <label for="data" class="form-label">Seleziona la data dell'esame</label>
                        <input type="date" class="form-control" id="data" name="data">
                    </div>
                    <button type="submit" class="btn btn-primary">Invia</button>
                </form>
                </div>
            <?php
            }
            
            if(isset($_POST['data'])){

            $esito = inserisci_esame($_POST['data'], $_SESSION['codice'], $_SESSION['cdl']);

            switch($esito){
                case "successo": ?>
                    <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                        <div class="p-3 mb-3 text-bg-success rounded-1">inserimento riuscito</div>
                    </div>
                    <?php break;
                
                case null: ?>
                    <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                        <div class="p-3 mb-3 text-bg-danger rounded-1">errore nell'inserimento, 2 esami appartenenti allo stesso corso di laurea non possono avere lo stesso giorno</div>
                    </div>
                    <?php break;
                
                default : ?>
                    <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                        <div class="p-3 mb-3 text-bg-danger rounded-1"><?php echo($esito); ?></div>
                    </div>
                    <?php break;
                }

                unset($_SESSION['codice']);
                unset($_SESSION['cdl']);
    
            } ?>

        <div class="d-flex mt-3 align-items-center justify-content-center">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" >        
                <button type="submit" id="back" name="back" class="btn btn-danger">torna alla pagina di selezione</button>
            </form>
        </div>
        <?php 