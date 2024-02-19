<?php

$menu=<<<EOD
<div class="container mt-5">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
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
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" style="color: red;"  href="segreteria.php?log=del">Logout</a>
        </li>
      </ul>
    </div>
  </nav>
</div> 
EOD;

echo $menu;
?>;
