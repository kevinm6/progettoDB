<?php
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);
include_once '../lib/util.php';
session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>servizio rimozione propedeuticita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</head>
    <body>
        <?php
            if (!isset($_SESSION['nome_utente']) || $_SESSION['profilo_utente'] != 'segreteria') {
                session_unset();
                $utente = null;
                header('Location: ../index.php');
            } else {
                $utente = $_SESSION['nome_utente'];
            }

            include_once 'navbar.php';

            if (isset($_GET['insegnamento']) && isset($_GET['cdl']) && isset($_GET['propedeutico_a'])) {
                $result = remove_prerequisites($_GET['insegnamento'], $_GET['cdl'], $_GET['propedeutico_a']);
            }
        ?>

        <div class="container pt-1 pb-3 mt-5 border">
          <h3 class="text-center">Servizio rimozione propedeuticita</h3>
          
            <?php
                $corsi_di_laurea = get_courses();
            ?>

            <h3 class="text-center">Seleziona il corso di laurea</h3>
            <br>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                <div class="mb-3 pt-3">
                    <label for="cdl" class="form-label">Seleziona corso di laurea</label>
                    <select class="form-select" id="cdl" name="cdl">
                        <?php foreach ($corsi_di_laurea as $cdl) { ?>
                        <option value="<?php echo $cdl['id']; ?>"> <?php echo $cdl['nome'] . ' (' . $cdl['tipologia'] . ')'; ?> </option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Invia</button>
            </form>
      
            <?php
                if (isset($_GET['cdl'])) {
                    $_SESSION['cdl'] = $_GET['cdl'];
                    $propedeuticita = get_prerequisites_alt($_GET['cdl']);
            ?>
            <h3 class="text-center">Seleziona la propedeuticita da rimuovere</h3>
            <br>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="text-align: center;">insegnamento</th>
                        <th style="text-align: center;">propedeutico a</th>
                        <th style="text-align: center;">Seleziona</th>
                    </tr>
                </thead>
            
                <?php foreach ($propedeuticita as $p) {
                        $t_main = get_teaching_name($_GET['cdl'], $p['insegnamento']);
                        $t_dep = get_teaching_name($_GET['cdl'], $p['propedeutico_a']); ?>
                <tr>
                    <td style="text-align: center;"><?php echo $t_main; ?></td>
                    <td style="text-align: center;"><?php echo $t_dep; ?></td>
          <th style="text-align: center;"><a href=<?php echo ("{$_SERVER['PHP_SELF']}?insegnamento={$p['insegnamento']}&cdl={$p['cdl_main']}&propedeutico_a={$p['propedeutico_a']}") ?> 
            class="link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" onclick="return confirm('Confermi rimozione propedeuticità?')">
            Rimuovi</a>

          </th>
                </tr>

            <?php } ?>

            </table>
            <?php
                }

                $show_result = null;
                if (isset($result)) {
                    if (!$result) {
                        $show_result = "<div class='p-3 mb-3 text-bg-danger rounded-1'>errore nella rimozione</div>";
                    } else {
                        $show_result = "<div class='p-3 mb-3 text-bg-success rounded-1'>rimozione riuscita</div>";
                    }
                    show_html_result($show_result);
                }
                                ?>
        </div>
    </body>
</html>
