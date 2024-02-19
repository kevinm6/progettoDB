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
    <title>servizio aggiornamento utenti</title>
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
          <h3 class="text-center">Servizio aggiornamento utenti</h3>
          </br>
   
          <h3 class="text-center">Seleziona il tipo di utente</h3>
          </br>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <div class="mb-3">
                <label for="tipo_selezione" class="form-label">Seleziona tipo utente</label>
                <select class="form-select" id="tipo_selezione" name="tipo_selezione">
                    <option value="studente">studente</option>
                    <option value="docente">docente</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Seleziona</button>
          </form>

        <?php 

        if(isset($_GET['tipo_selezione']) && $_GET['tipo_selezione'] == 'studente'){

          $studenti = preleva_studenti();
          ?>
          <h3 class="text-center">Seleziona lo studente da aggiornare</h3>
          </br>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Username</th>
                    <th>Matricola</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Seleziona</th>
                </tr>
            </thead>
          
            <?php foreach ($studenti as $s) { ?>
              <tr>
                  <td><?php echo $s['username']; ?></td>
                  <td><?php echo $s['matricola']; ?></td>
                  <td><?php echo $s['nome']; ?></td>
                  <td><?php echo $s['cognome']; ?></td>
                  <th><a href=<?php echo("form_aggiornamento.php"."?user=" . $s['username'] )?> class="link-primary">Aggiorna</a></th>
              </tr>

          
          <?php } ?>

          </table>
        <?php 
        
        }

        if(isset($_GET['tipo_selezione']) && $_GET['tipo_selezione'] == 'docente'){

          $docenti = preleva_docenti();
          ?>
          <h3 class="text-center">Selezione il docente da aggiornare</h3>
          </br>
          <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Username</th>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Seleziona</th>
                </tr>
            </thead>
          
            <?php foreach ($docenti as $d) { ?>
              <tr>
                  <td><?php echo $d['username']; ?></td>
                  <td><?php echo $d['id']; ?></td>
                  <td><?php echo $d['nome']; ?></td>
                  <td><?php echo $d['cognome']; ?></td>
                  <th><a href=<?php echo("form_aggiornamento.php"."?user=" . $d['username'] )?> class="link-primary">Aggiorna</a></th>
              </tr>

          
          <?php } ?>

          </table>
        <?php } ?>

        
      </div>
    </body>
</html>