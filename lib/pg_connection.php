<?php

function open_pg_connection() {
  $root = realpath($_SERVER["DOCUMENT_ROOT"]);
  $env = parse_ini_file("$root/.env");
  $host = $env['HOST'];
  $dbuser = $env['DB_USER'];
  $dbname = $env['DB_NAME'];
  $dbpasswd = $env['DB_PASSWD'];

  $conn_string = "host=$host dbname=$dbname user=$dbuser password=$dbpasswd";
  $conn = pg_connect("$conn_string");
  return $conn;
}
?>
