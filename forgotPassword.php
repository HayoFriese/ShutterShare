<?php

  include 'server/db_conn.php';

  require_once('functions.php');

  echo pageIni("Sign In - Shuttershare");

  echo nav();

  echo "<div class='account-side'>
         <div class='center'>";

 if (isset( $_POST['forgotPassword'] ) ) {


  $email = filter_has_var(INPUT_POST, 'email') ? $_POST['email']: null;
  
  $sql = "SELECT iduser ,securityA, email, username FROM shutuser WHERE email = '$email'";


  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result)){

    echo "No Account Found";

    echo email();


} else {

  header("location: securityQuestion.php");

}

} else {

  echo email();


}


echo "</div>
      </div>"; 

echo pageClose();

?>