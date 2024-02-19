<?php 
  include_once ('funzioni.php'); 
  session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>piattaforma universitaria inserimento esami</title>
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
          <h3 class="text-center">Servizio inserimento esami</h3>
          <?php
            $res = preleva_dati_docente($_SESSION['user']);
            $insegnamenti = preleva_insegnamenti_per_responsabile($res['id']);
            ?>
            <h5 class="text">Seleziona l'insegnamento per il quale si vuole inserire un esame</h5>
            </br>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Codice</th>
                        <th>Corso di laurea</th>
                        <th>Nome</th>
                        <th>Descrizione</th>
                        <th>Anno</th>
                    </tr>
                </thead>
          
            <?php foreach ($insegnamenti as $i) { 
                $cdl = preleva_nome_corso($i['corso_di_laurea']);?>
                <tr>
                    <td><?php echo $i['codice_univoco']; ?></td>
                    <td><?php echo $cdl['nome']; ?></td>
                    <td><?php echo $i['nome']; ?></td>
                    <td><?php echo $i['descrizione']; ?></td>
                    <td><?php echo $i['anno']; ?></td>
                    <th><a href=<?php echo("form_inserimento_esame.php"."?codice=" . $i['codice_univoco'] . "&cdl=" . $i['corso_di_laurea']);?> class="link-primary">Inserisci</a></th>
                </tr>
            <?php } ?>
            </table>
        </div>
    </body>
</html>


