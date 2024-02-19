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
    <title>servizio inserimento propedeuticita</title>
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
        if(!isset($_SESSION['user']) || $_SESSION['tipo_utente'] <> 'segreteria'){
            session_unset();
            $utente = null;
            header("Location:../index.php");
        } else {
            $utente = $_SESSION['user'];
        }
        ?>
        
        <?php
        include_once('navbar_seg.php');
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Servizio inserimento propedeuticita</h3>
        </br>
        <?php
        $corsi_di_laurea = preleva_corsi(); ?>

            <h3 class="text-center">Seleziona il corso di laurea</h3>
            </br>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                <div class="mb-3 pt-3">
                    <label for="cdl" class="form-label">Seleziona corso di laurea</label>
                    <select class="form-select" id="cdl" name="cdl">
                      <?php foreach ($corsi_di_laurea as $cdl) { ?>
                        <option value="<?php echo $cdl['id'];?>"> <?php echo $cdl['nome'] . ' (' . $cdl['tipologia'] . ')'; ?> </option>
                      <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Invia</button>
            </form>
        
        <?php if(isset($_GET['cdl'])){
            $_SESSION['cdl'] = $_GET['cdl'];
            $insegnamenti = preleva_insegnamenti($_GET['cdl']);
            ?>
            </br>
            <h3 class="text-center">Seleziona gli insegnamenti coinvolti</h3>
            </br>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-3 pt-3">
                    
                    <label for="propedeutico_a" class="form-label">Seleziona insegnamento per il quale si vuole aggiungere una propedeuticita</label>
                    <select class="form-select" id="propedeutico_a" name="propedeutico_a">
                      <?php foreach ($insegnamenti as $i) { ?>
                        <option value="<?php echo $i['codice_univoco'];?>"> <?php echo $i['nome']; ?> </option>
                      <?php } ?>
                    </select>
                </div>

                <div class="mb-3 pt-3">
                    <label for="insegnamento" class="form-label">Seleziona insegnamento propedeutico</label>
                    <select class="form-select" id="insegnamento" name="insegnamento">
                      <?php foreach ($insegnamenti as $i) { ?>
                        <option value="<?php echo $i['codice_univoco'];?>"> <?php echo $i['nome']; ?> </option>
                      <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Invia</button>
            </form>

        <?php }

        if(isset($_POST['propedeutico_a']) && isset($_POST['insegnamento'])){
            
            $esito = inserisci_propedeuticita($_POST['insegnamento'], $_POST['propedeutico_a'], $_SESSION['cdl']);
          
          
            switch($esito){

                case null:?>
                <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-danger rounded-1">errore nell'inserimento della propedeuticita'</div>
                </div>
                <?php break;
                
                case "successo": ?>
                <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-success rounded-1">inserimento riuscito</div>
                </div>
                <?php break;

                default: ?>
                <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-danger rounded-1"><?php echo($esito); ?></div>
                </div>
                <?php break;
            }

            unset($_SESSION['cdl']);
        }
        ?>
      </div>
    </body>
</html>