<?php 
  ini_set ("display_errors", "On");
  ini_set("error_reporting", E_ALL);
  include_once ('lib/utils.php'); 
  session_start();
?>  

<!-- form della pagina di login  -->

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PGEU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
    <body>
        <div class="container-fluid pb-3" style="background-color: lightgray;text-align: center;">
            <div class="row">
                <div class="col-sm-9">
                 <h1>Piattaforma Gestione Esami Universitari</h1>
                </div>
            </div>
        </div>

        <div class="container mt-5 border">
        <h3 class="text-center">Autenticazione</h3>
            </br>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="user-usr" placeholder="username" name="user">
                    <label for="user-usr">Nome Utente</label>
            
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" placeholder="inserisci la password" id="user-psw" name="pswd">
                    <label for="user-psw">Password</label>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary" style="width: 150px" value="Verify" name="verify">Accedi</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
<?php

// controllo del login
    $autenticato=null;

    if(isset($_POST) && isset($_POST['user']) && isset($_POST['pswd'])){
        $autenticato = login($_POST['user'], $_POST['pswd']);
    
        // se il login fallisce mostro messaggio
        if(is_null($autenticato)){
            ?>
            <div class="d-flex align-items-center justify-content-center" style="height: 150px;">
                <div class="p-3 mb-3 text-bg-danger rounded-1">Errore: username o password errati</div>
            </div>
            <?php

        // se il login ha successo mi salvo l'username e carico la pagina relativa al tipo utente
        } else {
            $_SESSION['nome_utente']=$autenticato['nome_utente'];
            $_SESSION['profilo_utente']=$autenticato['profilo_utente'];
            
            switch($autenticato['profilo_utente']){
                case 'segreteria':
                    header("Location: profile/segreteria.php");
                    break;
                case 'docente':
                    header("Location: profile/docente.php");
                    break;
                case 'studente':
                    header("Location: profile/studente.php");
                    break;
            }
        }
    }
?>
