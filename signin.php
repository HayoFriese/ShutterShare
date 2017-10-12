<?php

  include 'server/db_conn.php';

  require_once('functions.php');

  echo pageIni("Sign In - Shuttershare");

  echo nav();

  echo signInStartForm();

  echo pageClose();
?>