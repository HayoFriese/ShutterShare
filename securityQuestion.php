<?php

  include 'server/db_conn.php';

  require_once('functions.php');

  echo pageIni("Sign In - Shuttershare");

  echo nav();

  echo "<div class='account-side'>

        <div class='center'>";

// STEP 1 ENTER EMAIL

 if (isset( $_POST['forgotPassword'] ) ) {

  $email = filter_has_var(INPUT_POST, 'email') ? $_POST['email']: null;
  
  $sql = "SELECT iduser ,securityA, email, username FROM shutuser WHERE email = '$email'";

  $result = mysqli_query($conn, $sql);

  $numrows = mysqli_num_rows($result);

  if ($numrows < 1) {

       echo "<p class='white'>No Account Found for: $email</p>";

      echo email();

} else {

  require_once('questionform.php');

}

// STEP 2 SECURITY QUESTION

} elseif (isset( $_POST['questionSubmit'] ) ) {

    $username = filter_has_var(INPUT_POST, 'username') ? $_POST['username']: null;

    $answer = filter_has_var(INPUT_POST, 'answer') ? $_POST['answer']: null;

    $submitanswer = filter_has_var(INPUT_POST, 'submitanswer') ? $_POST['submitanswer']: null;


    if ($answer == $submitanswer ) {

      require_once("rPassword.php");

    } else {

      require_once('questionform.php');

      echo "<p style='color:red'>Incorrect Answer</p>";

    }

// STEP 3 RESET PASSWORD

  } elseif (isset( $_POST['resetPassword'] ) ) {

    $username = filter_has_var(INPUT_POST, 'username') ? $_POST['username']: null;

    $username = trim($username);

    $passNew = filter_has_var(INPUT_POST, 'passwordNew') ? $_POST['passwordNew']: null;
    
    $passNew = filter_var($passNew, FILTER_SANITIZE_STRING);
 
    $passNew = trim($passNew);
  
  
    $passNewC = filter_has_var(INPUT_POST, 'passwordNewC') ? $_POST['passwordNewC']: null;
  
    $passNewC = filter_var($passNewC, FILTER_SANITIZE_STRING);
  
    $passNewC = trim($passNewC);


    if (empty($passNew)) {

      $errors[]="You have not entered a password";
    }
    //check that the password entered is over 5 charectors
    elseif(strlen($passNew) < 5) {

      $errors[]="Passwords must include at least 5 characters";
    } 


    if (preg_match('/[A-Z]+[a-z]+[0-9]+/', $passNew)){
                

    } else {

        $errors[]="Password Must contain:<br>One uppercase letter <br> One lowercase letter <br> One number";

    }

    //loop through and display errors array along with login form 
    if (!empty($errors)) {

      require_once("rPassword.php");
  
      for ($a=0; $a < count($errors); $a++) {
          echo "<p style='color:red'>$errors[$a]</p>";
      } 



    } else {


        $sql = "SELECT username, password FROM shutuser WHERE username = ?";

        // prepare the sql statement
        $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn)); 
        // Bind the $username entered by the user to the prepared statement. Data type is string
        mysqli_stmt_bind_param($stmt, "s", $username) or die(mysqli_error($conn));
        // execute the query
        mysqli_stmt_execute($stmt) or die(mysqli_error($conn)); 
        // Store the password hash 
        mysqli_stmt_bind_result($stmt, $username, $passWDHash) or die(mysqli_error($conn));
        //Check if a record was returned by the query. 
        //check if the username entered matched the one in login form
        if (mysqli_stmt_fetch($stmt)) {
          //if password was correct
          //show logged in status and username
          mysqli_stmt_close($stmt);
          if (password_verify($passNew, $passWDHash)) {
            require_once("rPassword.php");
              
            echo "<p style='color:red'>Cannot use old password</p>";
                     
          } else {

            if ($passNewC === $passNew) {


              $passWDHash = password_hash( $passNew, PASSWORD_DEFAULT);
        
              $query = "UPDATE shutuser SET password ='$passWDHash', attempts = 0 WHERE username = '$username'";
              $result = mysqli_query($conn, $query) or die("Error in query: ". mysqli_error($conn)); 
        
              echo successLog("<p>Password has successfully been changed for $username </p>", "signin.php");
              mysqli_close($conn);
                                    
            } else {        
              require_once("rPassword.php");
                
              echo "<p style='color:red'>Passwords do not match</p>";
            }
          }
        }
      }
    } else {
      echo email();
    }

    echo "</div>
      </div>"; 

  echo pageClose();
?>