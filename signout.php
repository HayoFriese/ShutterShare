<?php
  //Initiate the session, save path outside directory folder in a safe location that can't be accessed easily.
  // ini_set("session.save_path", "../sessionData");
  ini_set("session.save_path", "/home/unn_w13020720/sessionData");

  //Start session, tracking activity and storing it in the session directory.
  session_start(); 

  //Gathers all existing session data.

  $_SESSION['logged-in'] = false;

  $_SESSION = array();    

  //Destroys session.
  session_destroy(); 

  //returns to previous url that the user was on.
  header("location: signin.php");

?>