<nav class="navbar navbar-expand-md bg-body-secondary" style="margin-top: 4px; margin-left: 10px; margin-right: 10px; background-color: green; border-radius: 20px;">
  <div class="container-fluid">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link" href="studente.php">Home</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle"  role="button" data-bs-toggle="dropdown" aria-expanded="false">Esami</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="exams_subscription.php">Iscrizione Esami</a></li>
          <li><a class="dropdown-item" href="check_subscription.php">Controlla Iscrizioni</a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle"  role="button" data-bs-toggle="dropdown" aria-expanded="false">Carriera</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="career_completed.php">Carriera Completa</a></li>
          <li><a class="dropdown-item" href="career_ok.php">Carriera Valida</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="course_information.php">Informazioni Corsi</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="degree.php">Laurea</a>
      </li>
    </ul>
    <ul class="dropdown navbar-nav ms-auto">
      <a id="nav_student_id" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="link-secondary dropdown-item" href="../lib/change_password.php">Cambia password</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="link-danger dropdown-item" href="giveup_studies.php">Rinuncia agli Studi</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a
          class="link-danger dropdown-item"
          onclick="return confirm('Confermi Logout?')"
          href="studente.php?log=del">Logout</a></li>
      </ul>
    </ul>
  </div>
</nav>
<script>
document.getElementById('nav_student_id').innerHTML = `
<u><b><?php echo ($_SESSION['nome_utente']) ?></b></u>
<img src='https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png' alt='profile_icn' width="30" height="30">
`;
</script>

