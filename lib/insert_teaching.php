<?php 
  include_once ('funzioni.php'); 
  session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>piattaforma universitaria inserimento insegnamento</title>
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
        }
        ?>
        
        <?php
        include_once('navbar_seg.php');
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Servizio inserimento insegnamento</h3>
          <?php
            $responsabili = preleva_possibili_responsabili_insegnamento();
            $corsi_di_laurea = preleva_corsi(); ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-3 pt-3">
                    <label for="cdl" class="form-label">Seleziona corso di laurea</label>
                    <select class="form-select" id="cdl" name="cdl">
                      <?php foreach ($corsi_di_laurea as $cdl) { ?>
                        <option value="<?php echo $cdl['id'];?>"> <?php echo $cdl['nome'] . ' (' . $cdl['tipologia'] . ')'; ?> </option>
                      <?php } ?>
                    </select>
                </div>
                <div class="mb-3 pt-3">
                    <label for="responsabile" class="form-label">Seleziona responsabile</label>
                    <select class="form-select" id="responsabile" name="responsabile">
                      <?php foreach ($responsabili as $r) { ?>
                        <option value="<?php echo $r['id'];?>"> <?php echo $r['nome'] . " " . $r['cognome']; ?> </option>
                      <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="descrizione" class="form-label">Descrizione</label>
                    <input type="text" class="form-control" id="descrizione" name="descrizione">
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome">
                </div>
                <div class="mb-3">
                    <label for="anno" class="form-label">Anno</label>
                    <input type="text" class="form-control" id="anno" name="anno">
                </div>
                <button type="submit" class="btn btn-primary">Invia</button>
                    
                    
              </form>
        </div>

        <?php
        if(isset($_POST['cdl']) && isset($_POST['responsabile']) && isset($_POST['descrizione']) && isset($_POST['nome']) && isset($_POST['anno'])){
              
            $esito = inserisci_insegnamento($_POST['cdl'], $_POST['responsabile'], $_POST['nome'], $_POST['descrizione'], $_POST['anno']);

            switch($esito){
              case "successo": ?>
                  <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                      <div class="p-3 mb-3 text-bg-success rounded-1">inserimento riuscito</div>
                  </div>
                  <?php break;
              
              case null: ?>
                  <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                      <div class="p-3 mb-3 text-bg-danger rounded-1">errore nell'inserimento</div>
                  </div>
                  <?php break;
              
              default : ?>
                  <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                      <div class="p-3 mb-3 text-bg-danger rounded-1"><?php echo($esito); ?></div>
                  </div>
                  <?php break;
              }
        }
          ?>
    </body>
</html>


