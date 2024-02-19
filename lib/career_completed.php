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
    <title>servizio carriera completa studenti</title>
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
          <h3 class="text-center">Servizio carriera completa</h3>
          </br>
        <?php
            
            $studente = preleva_dati_studente($utente);
            $carriera = preleva_carriera_completa($studente['matricola']);
        
        ?>
            <h3 class="text-center">carriera completa dello studente: <?php echo( $studente['nome'] . " " . $studente['cognome']); ?></h3>
          </br>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Insegnamento</th>
                    <th>Data</th>
                    <th>Voto</th>
                    <th>Esito</th>

                </tr>
            </thead>
          
            <?php foreach ($carriera as $c) { 
                 $i = preleva_dati_insegnamento($c['insegnamento'], $c['corso_di_laurea']); ?>
              <tr>
                  <td><?php echo $i['nome']; ?></td>
                  <td><?php echo $c['data']; ?></td>
                  <td><?php echo $c['voto']; ?></td>
                  <td><?php echo $c['esito']; ?></td>

              </tr>
            <?php } ?>
            </table>
      </div>
    </body>
</html>