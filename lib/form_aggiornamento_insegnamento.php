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
    <title>servizio aggiornamento insegnamenti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</head>
    <body>
        <?php
        // ini_set("display_errors", "0");
        // ini_set("error_reporting", "0");
        // se non vi e' una sessione valida aperta (presumibilmente in seguito ad un logout)
        // torno alla pagina di login
        if(!isset($_SESSION['user']) || $_SESSION['tipo_utente'] <> 'segreteria'){
            session_unset();
            $utente = null;
            header("Location:../index.php");
        } else {
            $utente = $_SESSION['user'];
        }

        if(isset($_GET['back'])){
            header("Location: aggiorna_insegnamento.php");
        }

        if (isset($_GET['codice']) && isset($_GET['cdl'])) {
            // prelevo i dati dell'insegnamento, del responsabile e i possibili nuovi responsabili
            $dati = preleva_dati_insegnamento($_GET['codice'], $_GET['cdl'] );

            $_SESSION['codice'] = $_GET['codice']; 
            $_SESSION['cdl'] = $_GET['cdl'];
            $old_docente = preleva_dati_docente_per_id($dati['responsabile']);
            $docenti = preleva_possibili_responsabili_insegnamento();

            // controllo che il vecchio responsabile sia nella lista dei possibili responsabili (in caso non vogliamo cambiarlo e lo metto in cima) 
            $presente = false;
            foreach ($docenti as $d){
                if($old_docente['id'] == $d['id']){
                    $presente = true;
                }
            }

            if($presente == false){
                $docenti = array_merge(array($old_docente), $docenti);
            } ?>


                <div class="container pt-1 pb-3 mt-5 border">
                <h3 class="text-center">Form aggiornamento insegnamento</h3>
                </br>
                <h5 class="text">Modifica i campi che si desidera modificare per l'insegnamento <?php echo($dati['nome']); ?></h5>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    
                    <div class="mb-3 pt-3">
                        <label for="responsabile" class="form-label">Seleziona Responsabile </label>
                        <select class="form-select" id="responsabile" name="responsabile">
                        <?php foreach ($docenti as $d) { ?>
                            <option value="<?php echo $d['id'];?>"> <?php echo $d['nome'] . ' ' . $d['cognome']; ?> </option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" value="<?php echo $dati['nome']?>"class="form-control" id="nome" name="nome">
                    </div>
                    <div class="mb-3">
                        <label for="descrizione" class="form-label">Descrizione</label>
                        <input type="text" value="<?php echo $dati['descrizione']?>"class="form-control" id="descrizione" name="descrizione">
                    </div>
                    <div class="mb-3">
                        <label for="anno" class="form-label">Anno</label>
                        <input type="text" value="<?php echo $dati['anno']?>"class="form-control" id="anno" name="anno">
                    </div>
                    <button type="submit" class="btn btn-primary">Invia</button>
                </form>
                </div>
            <?php
            }
            
            if(isset($_POST['responsabile']) && isset($_POST['descrizione']) && isset($_POST['anno']) && isset($_POST['nome'])){

            $esito = aggiorna_insegnamento($_SESSION['codice'], $_SESSION['cdl'], $_POST['responsabile'], $_POST['descrizione'], $_POST['nome'], $_POST['anno']);
            
            switch($esito){
                case "successo": ?>
                    <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                        <div class="p-3 mb-3 text-bg-success rounded-1">aggiornamento riuscito</div>
                    </div>
                    <?php break;
                
                case null: ?>
                    <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                        <div class="p-3 mb-3 text-bg-danger rounded-1">errore nell'aggiornamento</div>
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