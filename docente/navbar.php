<nav class="navbar navbar-expand-md bg-body-secondary" style="margin-top: 4px; margin-left: 10px; margin-right: 10px; background-color: green; border-radius: 20px;">
  <div class="container-fluid">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link" href="docente.php">Home</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Gestione Esami</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="insert_exam.php">Inserisci Esame</a></li>
          <li><a class="dropdown-item" href="remove_exam.php">Rimuovi Esame</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="insert_mark.php">Esiti Esami</a>
      </li>
    </ul>
    <ul class="dropdown navbar-nav ms-auto">
      <a id="nav_teacher_id" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="link-secondary dropdown-item" href="../lib/change_password.php">Cambia password</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a
          class="link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover dropdown-item"
          href="docente.php?log=del"
          onclick="return confirm('Confermi Logout?')"
        >Logout</a></li>
      </ul>
    </ul>
  </div>
</nav>
<script>
document.getElementById('nav_teacher_id').innerHTML = `
<u><b><?php echo($_SESSION['nome_utente']) ?></b></u>
<img src='https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png' alt='profile_icn' width="30" height="30">
`;
</script>

