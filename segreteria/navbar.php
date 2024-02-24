<nav class="navbar navbar-expand-md bg-body-secondary" style="margin-top: 4px; margin-left: 10px; margin-right: 10px; background-color: green; border-radius: 20px;">
    <div class="container-fluid">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="segreteria.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">gestione utenti</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="insert_user.php">inserisci utente</a></li>
            <li><a class="dropdown-item" href="remove_user.php">rimuovi utente</a></li>
            <li><a class="dropdown-item" href="update_user.php">aggiorna utente</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">gestione corsi di laurea</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="insert_course.php">inserisci corso</a></li>
            <li><a class="dropdown-item" href="update_course.php">aggiorna corso</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">gestione insegnamenti</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="insert_teaching.php">inserisci insegnamento</a></li>
            <li><a class="dropdown-item" href="update_teaching.php">aggiorna insegnamento</a></li>
            <li><a class="dropdown-item" href="insert_prerequisites.php">inserisci propedeuticita</a></li>
            <li><a class="dropdown-item" href="remove_prerequisites.php">rimuovi propedeuticita</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">carriere studenti</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="career_completed_admin.php">carriere complete studenti</a></li>
            <li><a class="dropdown-item" href="career_ok_admin.php">carriere valide studenti</a></li>
          </ul>
        </li>
      </ul>
      <ul class="dropdown navbar-nav ms-auto">
        <a id="nav_adminoffice_id" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="link-secondary link-opacity-25 link-opacity-100-hover dropdown-item" href="../lib/change_password.php">cambia password</a></li>
          <li><a
            class="link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover dropdown-item"
            onclick="return confirm('Confermi Logout?')"
            href="segreteria.php?log=del">Logout</a></li>
        </ul>
      </ul>
    </div>
  </nav>
  <script>
document.getElementById('nav_adminoffice_id').innerHTML = `
<u><b><?php echo ($_SESSION['nome_utente']) ?></b></u>
<img src='https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png' alt='profile_icn' width="30" height="30">
`;
</script>

