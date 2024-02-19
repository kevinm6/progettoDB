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
    <title>servizio info iscrizioni esami</title>
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
        

        if(isset($_GET['data']) && isset($_GET['studente'])){

        $esito = rimuovi_iscrizione($_GET['data'], $_GET['studente']);

        } ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Servizio controlla iscrizioni esami</h3>
          </br>
        <?php
            $studente = preleva_dati_studente($_SESSION['user']);
            $iscrizioni = preleva_iscrizioni($studente['matricola']);
        ?>
            <h3 class="text-center">iscrizioni confermate agli esami</h3>
          </br>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Nome</th>
                    <th>Data</th>
                    <th class="text-center">Rimuovi iscrizione</th>
                </tr>
            </thead>
          
            <?php foreach ($iscrizioni as $i) { 
                $insegnamento = preleva_nome_insegnamento($i['corso_di_laurea'], $i['insegnamento']); ?>
              <tr>
                  <td><?php echo $insegnamento['nome']; ?></td>
                  <td><?php echo $i['data']; ?></td>
                  <th class="text-center"><a href=<?php echo($_SERVER['PHP_SELF']."?data=" . $i['data'] . "&studente=" . $i['studente'])?> class="link-primary">Rimuovi</a></th>
              </tr>
            <?php } ?>
            </table>

            <?php
            if(isset($esito)){  
              if(!$esito){
                ?>
                <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-danger rounded-1">errore nella rimozione</div>
                </div>
                <?php
              }else{?>
                <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                    <div class="p-3 mb-3 text-bg-success rounded-1">rimozione riuscita</div>
                </div>
            <?php
              } 
              }?>
      </div>
      
        
    </body>
</html>