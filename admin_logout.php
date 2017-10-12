<?php
  include 'server/db_conn.php';
  require_once('functions_admin.php');
  session_unset();
  session_destroy();
  header("location: admin_login.php");
?>  
