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
    <title>servizio info corsi</title>
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
          <h3 class="text-center">Servizio info corsi</h3>
          </br>
        <?php
        if(!isset($_GET['cdl'])){
            $corsi = preleva_corsi();
        ?>
            <h3 class="text-center">Seleziona un corso di laurea per vedere i suoi corsi</h3>
          </br>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Nome</th>
                    <th>Tipologia</th>
                    <th>Responsabile</th>
                    <th>Seleziona</th>

                </tr>
            </thead>
          
            <?php foreach ($corsi as $c) { 
                $res = preleva_dati_docente_per_id($c['responsabile']); ?>
              <tr>
                  <td><?php echo $c['nome']; ?></td>
                  <td><?php echo $c['tipologia']; ?></td>
                  <td><?php echo $res['nome'] . " " . $res['cognome']; ?></td>
                  <th><a href=<?php echo($_SERVER['PHP_SELF']."?cdl=" . $c['id'] )?> class="link-primary">Seleziona</a></th>

              </tr>
            <?php } ?>
            </table>

            <?php } else if(isset($_GET['cdl'])){
                $insegnamenti = preleva_insegnamenti($_GET['cdl']); ?>

                <h3 class="text-center">Insegnamenti presenti per il corso di laurea scelto:</h3>
                </br>
                <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Nome</th>
                        <th>Responsabile</th>
                        <th>Descrizione</th>
                        <th>Anno</th>
                    </tr>
                </thead>
                
                <?php foreach ($insegnamenti as $i) { 
                    $res = preleva_dati_docente_per_id($i['responsabile']); ?>
                    <tr>
                        <td><?php echo $i['nome']; ?></td>
                        <td><?php echo $res['nome'] . " " . $res['cognome']; ?></td>
                        <td><?php echo $i['descrizione']; ?></td>
                        <td><?php echo $i['anno'];?></td>
    
                    </tr>
                <?php } ?>
                </table>
                </br>
                </br>
                
                <h3 class="text-center">propedeuticita' presenti per il corso di laurea scelto:</h3>
                </br>
                <?php $propedeuticita = preleva_propedeuticita($_GET['cdl']); ?>
                <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>insegnamento</th>
                        <th>propedeutico a</th>
                    </tr>
                </thead>
                
                <?php foreach ($propedeuticita as $p) { 
                    $i1 = preleva_nome_insegnamento($_GET['cdl'], $p['insegnamento']); 
                    $i2 = preleva_nome_insegnamento($_GET['cdl'], $p['propedeutico_a']); ?>
                    <tr>
                        <td><?php echo $i1['nome']; ?></td>
                        <td><?php echo $i2['nome']; ?></td>    
                    </tr>
                <?php } ?>
                </table>

                <div class="d-flex mt-3 align-items-center justify-content-center">
                    <a href="informazioni_corsi.php" class="btn btn-danger" style="width: 250px" >Torna alla selezione</a>
                </div>
            <?php } ?>

      </div>
      
        
    </body>
</html>