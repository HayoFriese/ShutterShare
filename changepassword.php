<?php
  include 'server/db_conn.php';

  require_once('functions.php');

  if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){

    echo pageIni("Access Denied");
    
    echo error();


  } else {

    echo pageIni("Change Password | My Account - Shuttershare");
  
    echo nav2();

    echo nav3(" class=\"active\"", "", "", ""); 
  
    echo "<div class='back-end'>";
  
    echo "<div class='back-end-center'>";
  
    echo breadcrumb("myaccount.php", "My Account", "Change Password");
  
    if ( isset( $_POST['changePassword'] ) ) {
  
      $username = $_SESSION['username'];
  
      $passWD = filter_has_var(INPUT_POST, 'password') ? $_POST['password']: null;
  
      $passWD = filter_var($passWD, FILTER_SANITIZE_STRING);
  
     $passWD = trim($passWD);


        $sql = "SELECT password FROM shutuser WHERE username = ?";
          // prepare the sql statement
          $stmt = mysqli_prepare($conn, $sql); 
          // Bind the $username entered by the user to the prepared statement. Data type is string
          mysqli_stmt_bind_param($stmt, "s", $username);
          // execute the query
          mysqli_stmt_execute($stmt); 
          // Store the password hash 
          mysqli_stmt_bind_result($stmt, $passWDHash);
          //Check if a record was returned by the query. 
          //check if the username entered matched the one in login form
          if (mysqli_stmt_fetch($stmt)) {
            //if password was correct
            //show logged in status and username
            if (password_verify($passWD, $passWDHash)) {
  
                  mysqli_stmt_close($stmt);
  
                  $passNew = filter_has_var(INPUT_POST, 'passwordNew') ? $_POST['passwordNew']: null;
  
                  $passNew = filter_var($passNew, FILTER_SANITIZE_STRING);
            
                  $passNew = trim($passNew);
            
            
                  $passNewC = filter_has_var(INPUT_POST, 'passwordNewC') ? $_POST['passwordNewC']: null;
            
                  $passNewC = filter_var($passNewC, FILTER_SANITIZE_STRING);
            
                  $passNewC = trim($passNewC);


                if (empty($passNew)) {

                  $errors[]="You have not entered a new password";
                }
                
                //check that the password entered is over 5 charectors
                elseif(strlen($passNew) < 5) {

                  $errors[]="Passwords must include at least 5 characters";
                } 


                if (preg_match('/[A-Z]+[a-z]+[0-9]+/', $passNew))
                {
                            

                } else {

                    $errors[]="Passwords must contain one uppercase letter, lowercase letter and number";

                }

                //loop through and display errors array along with login form 
                if (!empty($errors)) {

                  echo changePassword();
              
                  for ($a=0; $a < count($errors); $a++) {

                      echo "<p style='color:red'>$errors[$a]</p>";
                  } 

                }else {

                     if ($passNewC == $passNew) {
  
                      $passWDHash = password_hash( $passNew, PASSWORD_DEFAULT);
  

               $query = "UPDATE shutuser SET password='$passWDHash' WHERE username = '$username'";
            $result = mysqli_query($conn, $query) or die("Error in query: ". mysqli_error($conn)); 
  
            echo successLog("Password has successfully been changed!", "myaccount.php");
        
        } else {
  
          echo changePassword();
  
          echo "<p>Passwords do not match</p>";
  
        }
                  }

  
                 
  
      } else {

        echo changePassword();
      
        echo "<p style='color:red'> Old Password incorrect</p>";
      }
  
    }






  
    } else {
  
   echo changePassword();
  
    }
  
    echo "</form>
    </div>
    </div>
    </div>";
  }
  echo pageClose();
?>