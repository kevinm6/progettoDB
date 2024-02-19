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
    <title>servizio aggiornamento insegnamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</head>
    <body>
        <?php
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
          <h3 class="text-center">Servizio aggiornamento insegnamenti</h3>
        </br>
        <?php
        $corsi_di_laurea = preleva_corsi(); ?>

            <h3 class="text-center">Seleziona il corso di laurea dell'insegnamento da modificare</h3>
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
            $insegnamenti = preleva_insegnamenti($_GET['cdl']);
            ?>
            <h3 class="text-center">Seleziona l'insegnamento da aggiornare</h3>
            </br>
            <table class="table table-bordered table-hover">
              <thead class="thead-dark">
                  <tr>
                      <th>Codice</th>
                      <th>Responsabile</th>
                      <th>Nome</th>
                      <th>Descrizione</th>
                      <th>Anno</th>
                      <th>Seleziona</th>
                  </tr>
              </thead>
            
              <?php foreach ($insegnamenti as $i) {
                $r = preleva_dati_docente_per_id($i['responsabile'])?>
                <tr>
                    <td><?php echo $i['codice_univoco']; ?></td>
                    <td><?php echo "ID(" . $r['id'] . ") " . $r['nome'] . $r['cognome']; ?></td>
                    <td><?php echo $i['nome']; ?></td>
                    <td><?php echo $i['descrizione']; ?></td>
                    <td><?php echo $i['anno']; ?></td>
                    <th><a href=<?php echo("form_aggiornamento_insegnamento.php"."?codice=" . $i['codice_univoco'] . "&cdl=" . $i['corso_di_laurea']);?> class="link-primary">Aggiorna</a></th>
                </tr>
  
            
            <?php } ?>
  
            </table>
        <?php } ?>
      </div>
    </body>
</html>