<?php 
  include_once ('funzioni.php'); 
  session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>piattaforma universitaria iscrizione esami</title>
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
        if(!isset($_SESSION['user']) || $_SESSION['tipo_utente'] <> 'studente'){
            session_unset();
            $utente = null;
            header("Location:../index.php");
        }
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Servizio iscrizione esami</h3>
          <?php
           if(isset($_GET['codice']) && isset($_GET['cdl']) && isset($_GET['data'])){
            $studente = preleva_dati_studente($_SESSION['user']);
            $esito = iscrizione_esame($_GET['codice'], $_GET['cdl'], $studente['matricola'], $_GET['data']);

            switch($esito){

                case null:?>
                    <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-danger rounded-1">e' gia' presente una iscrizione per questo esame</div>
                    </div>
                    <?php break;
                
                case "successo": ?>
                    <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-success rounded-1">iscrizione avvenuta con successo</div>
                    </div>
                    <?php break;
    
                default: ?>
                    <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-danger rounded-1"><?php echo($esito); ?></div>
                    </div>
                    <?php break;
                
                }
           }
           
           ?>
            </table>
        </div>
        <div class="d-flex mt-3 align-items-center justify-content-center">
                    <a href="iscrizione_esami.php" class="btn btn-danger" style="width: 250px" >Torna alla selezione</a>
        </div>
    </body>
</html>