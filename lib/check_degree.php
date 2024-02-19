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
    <title>servizio rinuncia agli studi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</head>
    <body>
        <?php
        // se non vi e' una sessione valida aperta (presumibilmente in seguito ad un logout)
        // torno alla pagina di login
        if(!isset($_SESSION['user']) || $_SESSION['tipo_utente'] <> 'studente'){
            session_unset();
            $utente = null;
            header("Location:../index.php");
        } else {
            $utente = $_SESSION['user'];
        }
        ?>
        
        <?php
        include_once('navbar_stud.php');
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <?php if(isset($_GET['user']) && $_GET['user'] == $_SESSION['user']){
            // controllo che nella carriera valida ci sia una entrata per ogni 
            $studente = preleva_dati_studente($_SESSION['user']);
            
            $carriera = preleva_carriera_valida($studente['matricola']);
            $esami_superati = array();
            foreach ($carriera as $c){
                $esami_superati[] = $c['insegnamento'];
            }

            $insegnamenti = preleva_insegnamenti($studente['corso_di_laurea']);

            $valido = true;

            foreach ($insegnamenti as $i){
                if(!in_array($i['codice_univoco'], $esami_superati))
                $valido = false;
            }


            if(!$valido){
                ?>
                 <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-danger rounded-1">lo studente non ha superato tutti gli esami, laurea non valida</div>
                </div>
                <div class="d-flex mt-3 align-items-center justify-content-center">
                    <a href="../studente.php" class="btn btn-danger" style="width: 250px" >Torna alla home</a>
                </div>
                <?php
            } else {

            
            $esito = rimuovi_utente($_GET['user']);

            if(!$esito){ ?>
                <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-danger rounded-1">qualcosa e' andato storto</div>
                </div>
            <?php }
            if($esito){
                session_unset();
                header("Location: ../index.php");
            }
          } 
          }?>

      </div>
        
      
        
    </body>
</html>