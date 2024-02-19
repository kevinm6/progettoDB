<?php
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);

include_once 'pg_connection.php';


######################LOGIN ######################
function login($user, $pswd) {
  $db = open_pg_connection();

  if ($db) {
    $query = 'select nome_utente, profilo_utente from utente where nome_utente = $1 and password = $2';

    $res = pg_query_params($db, $query, array($user, md5($pswd)));

    $result = pg_fetch_assoc($res);

    pg_close($db);

    if ($result) 
    return $result;
  }
}

######################GET DATA ######################

function get_course_data($id) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select responsabile, tipologia, nome from corso_di_laurea where id = $1';
    $res = pg_query_params($conn, $query, array($id));

    $result = pg_fetch_assoc($res);
    pg_close($conn);

    if ($result) {
      return $result;
    }
  }
}

function get_teaching_data($id_code, $cdl) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select * from insegnamento where codice_univoco = $1 and corso_di_laurea = $2';
    $res = pg_query_params($conn, $query, array($id_code, $cdl));

    $result = pg_fetch_assoc($res);
    pg_close($conn);

    if ($result) {
      return $result;
    }
  }
}

function get_teaching($cdl) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select * from insegnamento where corso_di_laurea = $1';
    $res = pg_query_params($conn, $query, array($cdl));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_prerequisites($cdl) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select insegnamento, propedeutico_a from propedeuticita where corso_di_laurea1 = $1';
    $res = pg_query_params($conn, $query, array($cdl));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_subscribers($codice, $cdl, $data) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select s.nome, s.cognome, s.matricola from studente s join iscrizione_esami ie ON s.matricola = ie.studente where ie.insegnamento = $1 and ie.corso_di_laurea = $2 and ie.data = $3';
    $res = pg_query_params($conn, $query, array($codice, $cdl, $data));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_administration_data($user) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select id, nome, cognome from segreteria where utente = $1';
    $res = pg_query_params($conn, $query, array($user));

    $result = pg_fetch_assoc($res);

    pg_close($conn);

    if ($result) return $result;
  }
}

function get_user_data($user) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select nome_utente, tipo_utente from utente where nome_utente = $1';
    $res = pg_query_params($conn, $query, array($user));

    if ($res) 
    $result = pg_fetch_assoc($res);
    else 
    $result = array();

    pg_close($conn);

    return $result;
  }
}

function get_student_data($user) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select matricola, corso_di_laurea, nome, cognome, email from studente where Utente = $1';
    $res = pg_query_params($conn, $query, array($user));

    // prelevo il risultato sotto forma di array
    $result = pg_fetch_assoc($res);

    pg_close($conn);

    if ($result) {
      return $result;
    }
  }
}

function get_student_data_id($matricola) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select matricola, corso_di_laurea, nome, cognome, email from studente where matricola = $1';
    $res = pg_query_params($conn, $query, array($matricola));

    // prelevo il risultato sotto forma di array
    $result = pg_fetch_assoc($res);

    pg_close($conn);

    if ($result) {
      return $result;
    }
  }
}

function get_hist_student_data_from_id($matricola) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select matricola, corso_di_laurea, nome, cognome, email from storico_studente where matricola = $1';
    $res = pg_query_params($conn, $query, array($matricola));

    // prelevo il risultato sotto forma di array
    $result = pg_fetch_assoc($res);

    pg_close($conn);

    if ($result) {
      return $result;
    }
  }
}

function get_teachers_candidates_teaching() {
  $conn = open_pg_connection();

  if ($conn) {
    $query = "select d.id, d.nome, d.cognome, count(i.codice_univoco)
    from docente d
    left join insegnamento i on d.id = i.responsabile
    group by d.id
    having count(i.codice_univoco) < 3 or count(i.codice_univoco) is null";
    $res = pg_query_params($conn, $query, array());

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_teachers_candidates() {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select id, nome, cognome from docente where id NOT IN( select d.id from docente d join corso_di_laurea c on d.id = c.responsabile)';
    $res = pg_query_params($conn, $query, array());

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_subscriptions($matricola) {
  $conn = open_pg_connection();
  if ($conn) {
    $query = 'select * from iscrizione_esami where studente = $1';
    $res = pg_query_params($conn, $query, array($matricola));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_prerequisites_alt($cdl) {
  $conn = open_pg_connection();
  if ($conn) {
    $query = 'select * from propedeuticita where corso_di_laurea1 = $1';
    $res = pg_query_params($conn, $query, array($cdl));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_teaching_name($cdl, $codice) {
  $conn = open_pg_connection();
  $query = 'select nome from insegnamento where corso_di_laurea = $1 and codice_univoco = $2';
  $res = pg_query_params($conn, $query, array($cdl, $codice));

  // prelevo il risultato sotto forma di array
  $result = pg_fetch_assoc($res);

  pg_close($conn);

  if ($result) {
    return $result;
  }
}

function get_nome_corso($id) {
  $conn = open_pg_connection();

  $query = 'select nome from corso_di_laurea where id = $1';
  $res = pg_query_params($conn, $query, array($id));

  // prelevo il risultato sotto forma di array
  $result = pg_fetch_assoc($res);

  pg_close($conn);

  if ($result) {
    return $result;
  }
}

function get_insegnamenti_per_responsabile($id) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select * from insegnamento where responsabile = $1';
    $res = pg_query_params($conn, $query, array($id));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_tutti_studenti() {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select matricola, nome, cognome, email from studente union select matricola, nome, cognome, email from storico_studente order by matricola';
    $res = pg_query_params($conn, $query, array());

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_carriera_completa($matricola) {
  $conn = open_pg_connection();

  if ($conn) {
    $query =
    'select c.insegnamento, c.corso_di_laurea, c.data, c.voto, c.esito
    from carriera c 
    where c.studente = $1
    order by c.insegnamento, c.data';
    $res = pg_query_params($conn, $query, array($matricola));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_carriera_valida($matricola) {
  $conn = open_pg_connection();

  if ($conn) {
    $query =
    'select c.insegnamento, c.corso_di_laurea, c.data, c.voto, c.esito
    from carriera_valida c 
    where c.studente = $1';
    $res = pg_query_params($conn, $query, array($matricola));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_carriera_valida_studente_rimosso($matricola) {
  $conn = open_pg_connection();

  if ($conn) {
    $query =
    'select c.insegnamento, c.corso_di_laurea, c.data, c.voto 
    from carriera_valida_studenti_rimossi c 
    where c.studente = $1';
    $res = pg_query_params($conn, $query, array($matricola));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_carriera_completa_studente_rimosso($matricola) {
  $conn = open_pg_connection();

  if ($conn) {
    $query =
    'select c.insegnamento, c.corso_di_laurea, c.data, c.voto, c.esito
    from storico_carriera c 
    where c.studente = $1
    order by c.data';
    $res = pg_query_params($conn, $query, array($matricola));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_teacher_data($user) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select id, nome, cognome, email from docente where utente = $1';
    $res = pg_query_params($conn, $query, array($user));

    // prelevo il risultato sotto forma di array
    $result = pg_fetch_assoc($res);

    pg_close($conn);

    if ($result) {
      return $result;
    }
  }
}

function get_exams_for_cdl($cdl) {
  $conn = open_pg_connection();

  if ($conn) {
    $query =
    'select * from esame where corso_di_laurea = $1 order by insegnamento, data';
    $res = pg_query_params($conn, $query, array($cdl));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_exams($id) {
  $conn = open_pg_connection();

  if ($conn) {
    $query =
    'select e.*, i.nome
    from esame e join insegnamento i on e.corso_di_laurea = i.corso_di_laurea and e.insegnamento = i.codice_univoco join docente d on d.id = i.responsabile 
    where i.responsabile = $1';
    $res = pg_query_params($conn, $query, array($id));

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }

    pg_close($conn);
    return $result;
  }
}

function get_teacher_data_for_id($id) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select id, nome, cognome from docente where id = $1';
    $res = pg_query_params($conn, $query, array($id));

    // prelevo il risultato sotto forma di array
    $result = pg_fetch_assoc($res);

    pg_close($conn);

    if ($result) {
      return $result;
    }
  }
}

function get_courses() {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select id, responsabile, nome, tipologia from corso_di_laurea order by nome';
    $res = pg_query_params($conn, $query, array());

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }
    pg_close($conn);
    return $result;
  }
}

function get_students() {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select u.nome_utente, s.matricola, s.nome, s.cognome from utente u join studente s on u.nome_utente = s.utente order by s.matricola';
    $res = pg_query_params($conn, $query, array());

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }
    pg_close($conn);
    return $result;
  }
}

function get_teachers() {
  $conn = open_pg_connection();
  if ($conn) {
    $query = 'select u.nome_utente, d.id, d.nome, d.cognome from utente u join docente d on u.nome_utente = d.utente order by d.id';
    $res = pg_query_params($conn, $query, array());

    if ($res) {
      $result = pg_fetch_all($res);
    } else {
      $result = array();
    }
    pg_close($conn);
    return $result;
  }
}


function check_student($matricola) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'select 1 from studente where matricola = $1';
    $res = pg_query_params($conn, $query, array($matricola));
    $result = pg_fetch_assoc($res);

    if ($result) {
      return 'attivo';
    }

    $query = 'select matricola from storico_studente where matricola = $1';
    $res = pg_query_params($conn, $query, array($matricola));
    $result = pg_fetch_assoc($res);

    if ($result) {
      return 'rimosso';
    }
  }
}

######################UPDATE DATA ######################

function update_teacher($userold, $usernew, $nome, $cognome, $email) {
  $conn = open_pg_connection();


  if ($conn) {
    // Inizia la transazione
    pg_query($conn, 'begin');
    $query = 'update utente set nome_utente = $1 where nome_utente = $2';
    $res = pg_query_params($conn, $query, array($usernew, $userold));

    if (!$res) {
      $msg = pg_last_error($conn);
      pg_query($conn, 'rollback');
      pg_close($conn);
      return $msg;
    }

    $query_docente = 'update docente set nome = $2, cognome = $3, email = $4 where utente = $1';
    $res_docente = pg_query_params($conn, $query_docente, array($usernew, $nome, $cognome, $email));

    if (!$res_docente) {
      $msg = pg_last_error($conn);
      pg_query($conn, 'rollback');
      pg_close($conn);
      return $msg;
    }

    pg_query($conn, 'commit');
    pg_close($conn);
    return 'ok';
  }
}

function update_student($userold, $usernew, $cdl, $nome, $cognome, $email) {
  $conn = open_pg_connection();


  if ($conn) {
    // Inizia la transazione
    pg_query($conn, 'begin');
    $query = 'update utente set nome_utente = $1 where nome_utente = $2';
    $res = pg_query_params($conn, $query, array($usernew, $userold));

    if (!$res) {
      $msg = pg_last_error($conn);
      pg_query($conn, 'rollback');
      pg_close($conn);
      return $msg;
    }

    $query_studente = 'update studente set corso_di_laurea = $5, nome = $2, cognome = $3, email = $4 where utente = $1';
    $res_studente = pg_query_params($conn, $query_studente, array($usernew, $nome, $cognome, $email, $cdl));

    if (!$res_studente) {
      $msg = pg_last_error($conn);
      pg_query($conn, 'rollback');
      pg_close($conn);
      return $msg;
    }

    pg_query($conn, 'commit');
    pg_close($conn);
    return 'ok';
  }
}

function update_cdl($id, $responsabile, $tipologia, $nome) {
  $conn = open_pg_connection();


  if ($conn) {
    $query = 'update corso_di_laurea set responsabile = $2, tipologia = $3, nome = $4 where id = $1';
    $res = pg_query_params($conn, $query, array($id, $responsabile, $tipologia, $nome));

    if (!$res) {
      $msg = pg_last_error($conn);
      pg_close($conn);
      return $msg;
    }

    pg_close($conn);
    return 'ok';
  }
}

function update_teaching($codice, $cdl, $responsabile, $nome, $descrizione, $anno) {
  $conn = open_pg_connection();


  if ($conn) {
    $query = 'update insegnamento set responsabile = $3, nome = $4, descrizione = $5, anno = $6 where codice_univoco = $1 and corso_di_laurea = $2';
    $res = pg_query_params($conn, $query, array($codice, $cdl, $responsabile, $nome, $descrizione, $anno));

    if (!$res) {
      $msg = pg_last_error($conn);
      // $errore = substr($msg, 0, strpos($msg, 'CONTEXT'));
      pg_close($conn);
      return $msg;
    }

    pg_close($conn);
    return 'ok';
  }
}

function change_password($user, $old_psw, $new_psw) {
  $conn = open_pg_connection();

  if ($conn) {
    // fai le varie query, allora qua ce molto importante capire come fetcahre gli errori per stampare in caso di errore il messaggio corretto\

    $query = 'select password from utente where nome_utente = $1';
    $res = pg_query_params($conn, $query, array($user));
    $result = pg_fetch_result($res, 0, 0);

    if ($result != $old_psw) {
      pg_close($conn);
      // throw new Exception("la password vecchia non e' corretta");
      return false;
    } else {
      $query = 'update utente set password = $1 where nome_utente = $2';
      $res = pg_query_params($conn, $query, array(md5($new_psw), $user));
      if (pg_affected_rows($res) == 1) {
        pg_close($conn);
        return 'ok';
      } else {
        pg_last_error($conn);
        pg_close($conn);
        return 'error';
      }
    }
  }
}

######################REMOVE DATA ######################

function remove_user($nome_utente) {
  $conn = open_pg_connection();


  if ($conn) {
    $query = 'delete from utente where nome_utente = $1';
    $res = pg_query_params($conn, $query, array($nome_utente));

    if (!$res) {
      $msg = pg_last_error($conn);
      pg_close($conn);
      return $msg;
    }

    if (pg_affected_rows($res) == 1) {
      pg_close($conn);
      return 'ok';
    } else {
      pg_close($conn);
      return false;
    }
  }
}

function remove_prerequisites($insegnamento, $cdl, $propedeutico_a) {
  $conn = open_pg_connection();


  if ($conn) {
    $query = 'delete from propedeuticita where insegnamento = $1 and corso_di_laurea1 = $2 and propedeutico_a = $3';
    $res = pg_query_params($conn, $query, array($insegnamento, $cdl, $propedeutico_a));

    if (!$res) {
      pg_close($conn);
      return false;
    }

    if (pg_affected_rows($res) == 1) {
      pg_close($conn);
      return true;
    } else {
      pg_close($conn);
      return false;
    }
  }
}

function remove_exam($codice, $cdl, $data) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'delete from esame where insegnamento = $1 and corso_di_laurea = $2 and data = $3';
    $res = pg_query_params($conn, $query, array($codice, $cdl, $data));

    if (!$res) {
      pg_close($conn);
      return false;
    }

    if (pg_affected_rows($res) == 1) {
      pg_close($conn);
      return true;
    } else {
      pg_close($conn);
      return false;
    }
  }
}

function remove_subscription($data, $studente) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'delete from iscrizione_esami where data = $1 and studente = $2';
    $res = pg_query_params($conn, $query, array($data, $studente));

    if (!$res) {
      pg_close($conn);
      return false;
    }

    if (pg_affected_rows($res) == 1) {
      pg_close($conn);
      return true;
    } else {
      pg_close($conn);
      return false;
    }
  }
}


######################INSERT DATA ######################

function insert_teacher($nome_utente, $password, $tipo_utente, $nome, $cognome, $email) {
  $conn = open_pg_connection();

  if ($conn) {
    // Inizia la transazione
    pg_query($conn, 'begin');

    // Esegui la prima query per inserire l'utente
    $query_utente = 'insert into utente VALUES($1, $2, $3)';
    $res = pg_query_params($conn, $query_utente, array($nome_utente, $password, $tipo_utente));

    if (!$res) {
      $msg = pg_last_error($conn);
      pg_query($conn, 'rollback');
      pg_close($conn);
      return $msg;
    }

    // Esegui la seconda query per inserire il docente
    $query_docente = 'insert into docente(utente, nome, cognome, email) VALUES($1, $2, $3, $4)';
    $res_docente = pg_query_params($conn, $query_docente, array($nome_utente, $nome, $cognome, $email));

    if (!$res_docente) {
      $msg = pg_last_error($conn);
      pg_query($conn, 'rollback');
      pg_close($conn);
      return $msg;
    }

    pg_query($conn, 'commit');
    pg_close($conn);
    return 'ok';
  }
}

function insert_exam($data, $codice, $cdl) {
  $conn = open_pg_connection();
  $query = 'insert into esame(data, insegnamento, corso_di_laurea) values ($1, $2, $3)';
  $res = pg_query_params($conn, $query, array($data, $codice, $cdl));

  if (!$res) {
    $msg = pg_last_error($conn);
    // $errore = substr($msg, 0, strpos($msg, 'CONTEXT'));
    pg_close($conn);
    return $msg;
  }

  pg_close($conn);
  return 'ok';
}

function insert_prerequisites($insegnamento, $propedeutico_a, $cdl) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'insert into propedeuticita(corso_di_laurea1, insegnamento, corso_di_laurea2, propedeutico_a) values ($1, $2, $3, $4)';
    $res = pg_query_params($conn, $query, array($cdl, $insegnamento, $cdl, $propedeutico_a));

    if (!$res) {
      $msg = pg_last_error($conn);
      // $errore = substr($msg, 0, strpos($msg, 'CONTEXT'));
      pg_close($conn);
      return $msg;
    }

    pg_close($conn);
    return 'ok';
  }
}

function insert_mark($codice, $cdl, $data, $studente, $voto) {
  $conn = open_pg_connection();
  $query = 'insert into carriera(data, insegnamento, corso_di_laurea, studente, voto) values ($1, $2, $3, $4, $5)';
  $res = pg_query_params($conn, $query, array($data, $codice, $cdl, $studente, $voto));

  if (!$res) {
    $msg = pg_last_error($conn);
    // $errore = substr($msg, 0, strpos($msg, 'CONTEXT'));
    pg_close($conn);
    return $msg;
  }

  pg_close($conn);
  return 'ok';
}

function insert_cdl($responsabile, $tipologia, $nome) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'insert into corso_di_laurea(responsabile, tipologia, nome) values( $1, $2, $3);';
    $res = pg_query_params($conn, $query, array($responsabile, $tipologia, $nome));

    if (!$res) {
      $msg = pg_last_error($conn);
      pg_close($conn);
      return $msg;
    }

    pg_close($conn);
    return 'ok';
  }
}

function insert_teaching($cdl, $responsabile, $nome, $descrizione, $anno) {
  $conn = open_pg_connection();


  if ($conn) {
    $query = 'insert into insegnamento(corso_di_laurea, responsabile, nome, descrizione, anno) values( $1, $2, $3, $4, $5)';
    $res = pg_query_params($conn, $query, array($cdl, $responsabile, $nome, $descrizione, $anno));

    if (!$res) {
      $msg = pg_last_error($conn);
      // $errore = substr($msg, 0, strpos($msg, 'CONTEXT'));
      pg_close($conn);
      return $msg;
    }

    pg_close($conn);
    return 'ok';
  }
}

function insert_student($nome_utente, $password, $tipo_utente, $cdl, $nome, $cognome, $email) {
  $conn = open_pg_connection();

  if ($conn) {
    pg_query($conn, 'begin');

    $query_utente = 'insert into utente values($1, $2, $3)';
    $res = pg_query_params($conn, $query_utente, array($nome_utente, $password, $tipo_utente));

    if (!$res) {
      $msg = pg_last_error($conn);
      pg_query($conn, 'rollback');
      pg_close($conn);
      return $msg;
    }

    $query_student = 'insert into studente(corso_di_laurea, utente, nome, cognome, email) values($1, $2, $3, $4, $5)';
    $res_studente = pg_query_params($conn, $query_student, array($cdl, $nome_utente, $nome, $cognome, $email));

    if (!$res_studente) {
      $msg = pg_last_error($conn);
      // if error on transaction -> rollback
      pg_query($conn, 'rollback');
      pg_close($conn);
      return $msg;
    }

    pg_query($conn, 'commit');
    pg_close($conn);
    return 'ok';
  }
}

function exam_subscription($codice, $cdl, $matricola, $data) {
  $conn = open_pg_connection();

  if ($conn) {
    $query = 'insert into iscrizione_esami(data, insegnamento, corso_di_laurea, studente) values( $1, $2, $3, $4)';
    $res = pg_query_params($conn, $query, array($data, $codice, $cdl, $matricola));

    if (!$res) {
      $msg = pg_last_error($conn);
      pg_close($conn);
      return $msg;
    }

    pg_close($conn);
    return 'ok';
  }
}
