<?php 
  include_once ('funzioni.php'); 
  session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>piattaforma universitaria inserimento esiti</title>
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
        }
        ?>
        
        <?php
        include_once('navbar_doc.php');
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
        <h3 class="text-center">Servizio inserimento esiti</h3>
        <?php
        if(!isset($_GET['codice']) && !isset($_GET['cdl']) && !isset($_GET['data'])){
            $res = preleva_dati_docente($_SESSION['user']);
            $esami = preleva_esami($res['id']);
          ?>
          <h3 class="text-center">Seleziona l'esame per il quale si vuole inserire l'esito</h3>
          </br>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Data</th>
                    <th>Insegnamento</th>
                    <th>Corso di laurea</th>
                    <th>Seleziona</th>
                </tr>
            </thead>
          
            <?php foreach ($esami as $e) { 
                $cdl = preleva_nome_corso($e['corso_di_laurea']);?>
              <tr>
                  <td><?php echo $e['data']; ?></td>
                  <td><?php echo $e['nome']; ?></td>
                  <td><?php echo $cdl['nome']; ?></td>
                  <th><a href=<?php echo($_SERVER['PHP_SELF']."?codice=" . $e['insegnamento'] . "&cdl=" . $e['corso_di_laurea'] . "&data=" . $e['data'])?> class="link-primary">Seleziona</a></th>
              </tr>

            <?php } 
        } ?>
            </table>
 

        <?php if(isset($_GET['codice']) && isset($_GET['cdl']) && isset($_GET['data'])){
            
            $_SESSION['codice'] = $_GET['codice'];
            $_SESSION['cdl'] = $_GET['cdl'];
            $_SESSION['data'] =$_GET['data'];

            // prelevare gli iscritti a quell' esame
            $iscritti = preleva_iscritti($_GET['codice'], $_GET['cdl'], $_GET['data']);

            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-3 pt-3">
                    <label for="studente" class="form-label">Seleziona lo studente</label>
                    <select class="form-select" id="studente" name="studente">
                      <?php foreach ($iscritti as $i) { ?>
                        <option value="<?php echo $i['matricola'];?>"> <?php echo $i['nome'] . " " . $i['cognome'] . " matricola: " . $i['matricola']; ?> </option>
                      <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="esito" class="form-label">Voto</label>
                    <input type="text" class="form-control" id="esito" name="esito">
                </div>
                <button type="submit" class="btn btn-primary">Invia</button>
              </form>
              <?php
            }
            if(isset($_POST['studente']) && isset($_POST['esito'])){

                $esito = inserisci_voto($_SESSION['codice'], $_SESSION['cdl'], $_SESSION['data'], $_POST['studente'], $_POST['esito']);

                switch($esito){
                    case "successo": ?>
                        <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                            <div class="p-3 mb-3 text-bg-success rounded-1">inserimento riuscito</div>
                        </div>
                        <?php break;
                    
                    case null: ?>
                        <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                            <div class="p-3 mb-3 text-bg-danger rounded-1">errore nell'inserimento, controlla di aver inserito un voto valido</div>
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
                    unset($_SESSION['data']);
                } ?>
        </div>
    </body>
</html>


