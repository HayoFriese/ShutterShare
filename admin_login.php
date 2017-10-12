<?php
include 'server/db_conn.php';
require_once('functions_admin.php');
echo pageIni("Sign In | Admin - Shuttershare");

if (isset($_SESSION['error'])) {
  $error = "<p class='error'>Invalid credentials.</p>";
  echo adminSignInForm($error);
} else {
  $error = null;
  echo adminSignInForm($error);
}

echo pageClose();