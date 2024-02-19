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
        // ini_set("display_errors", "0");
        // ini_set("error_reporting", "0");
        // se non vi e' una sessione valida aperta (presumibilmente in seguito ad un logout)
        // torno alla pagina di login
        if(!isset($_SESSION['user']) || $_SESSION['tipo_utente'] <> 'studente'){
            session_unset();
            $utente = null;
            header("Location:../index.php");
        }
        ?>
        
        <?php
        include_once('navbar_stud.php');
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Servizio iscrizione esami</h3>
          <?php
            $studente = preleva_dati_studente($_SESSION['user']);
            $esami = preleva_esami_cdl($studente['corso_di_laurea']);
            ?>
            <h5 class="text">Seleziona l'esame al cui ci si vuole iscrivere</h5>
            </br>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Insegnamento</th>
                        <th>Data</th>
                        <th>Iscriviti</th>
                    </tr>
                </thead>
          
            <?php foreach ($esami as $e) {
                $insegnamento = preleva_nome_insegnamento($e['corso_di_laurea'], $e['insegnamento']);?>
                <tr>
                    <td><?php echo $insegnamento['nome']; ?></td>
                    <td><?php echo $e['data']; ?></td>
                    <th><a href=<?php echo("iscriviti_esame.php"."?codice=" . $e['insegnamento'] . "&cdl=" . $e['corso_di_laurea'] . "&data=" . $e['data']);?> class="link-primary">Iscriviti</a></th>
                </tr>
            <?php } ?>
            </table>
        </div>
    </body>
</html>


