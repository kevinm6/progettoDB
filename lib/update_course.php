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
    <title>servizio aggiornamento corso</title>
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
          <h3 class="text-center">Servizio aggiornamento corsi di laurea</h3>
        </br>
        <?php
        $corsi_di_laurea = preleva_corsi(); ?>

            <h3 class="text-center">Selezione il corso di laurea</h3>
            </br>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Id</th>
                        <th>Responsabile</th>
                        <th>Tipologia</th>
                        <th>Nome</th>
                    </tr>
                </thead>
          
            <?php foreach ($corsi_di_laurea as $cdl) { 
                $docente = preleva_dati_docente_per_id($cdl['responsabile']); ?>
                <tr>
                    <td><?php echo $cdl['id']; ?></td>
                    <td><?php echo $docente['nome'] . " " . $docente['cognome']; ?></td>
                    <td><?php echo $cdl['tipologia']; ?></td>
                    <td><?php echo $cdl['nome']; ?></td>
                    <th><a href=<?php echo("form_aggiornamento_cdl.php"."?id=" . $cdl['id'] )?> class="link-primary">Aggiorna</a></th>
                </tr>
            <?php } ?>
            </table>
      </div>
    </body>
</html>