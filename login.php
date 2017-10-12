<?php 
    include 'server/db_conn.php';

    require_once('functions.php');

    echo pageIni("Shuttershare");


    if (isset( $_POST['signIn'] ) ) {
 
        //check if variable exists and stores it

        //if variable doesnt set the value as null
        $username = filter_has_var(INPUT_POST, 'username') ? $_POST['username']: null;

        $passWD = filter_has_var(INPUT_POST, 'password') ? $_POST['password']: null;

        /*<!--==== Validation ======= -->*/

        //trim username and password to remove white space

        $username = trim($username);

        $passWD = trim($passWD);

        $errors = array();


        $sql = "SELECT attempts from shutuser WHERE username = '$username'";

        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

        $count = mysqli_fetch_array($result, MYSQLI_NUM);


        if($count[0] > 3){

           $errors[]="You have used all your login attempts, please click on the forgot password link";
        
        } 
        /*<!--==== User name validation and sanitization ======= -->*/

        if (empty($username)) {

            $errors[]="You have not entered a username";

          }
         /*<!--==== Password validation and sanitization ======= -->*/

        $passWD = filter_var($passWD, FILTER_SANITIZE_STRING);

        //check that password has been entered
        if (empty($passWD)) {

            $errors[]="You have not entered a password";
          }
        //check that the password entered is over 5 charectors
        elseif(strlen($passWD) < 5) {

            $errors[]="Passwords must include at least 5 characters";
          } 
          //loop through and display errors array along with login form 

        if (!empty($errors)) {
           
            echo nav();

            echo signInForm($errors);

            echo "</div></div>";   
        }

        else { 
            // if all checks pass start to proccess data entered
            // make db connection
            // Query the users database table to get the password hash for the username entered by the user in the logon form 

            $sql = "SELECT iduser, password, suspension, active FROM shutuser WHERE username = ?";

            // prepare the sql statement
            $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn)); 
            // Bind the $username entered by the user to the prepared statement. Data type is string
            mysqli_stmt_bind_param($stmt, "s", $username) or die(mysqli_error($conn));
            // execute the query
            mysqli_stmt_execute($stmt) or die(mysqli_error($conn)); 
            // Store the password hash 
            mysqli_stmt_bind_result($stmt, $uid, $passWDHash, $suspend, $activeCheck) or die(mysqli_error($conn));

            //Check if a record was returned by the query. 
            //check if the username entered matched the one in login form
            if (mysqli_stmt_fetch($stmt)) {
                //if password was correct
                //show logged in status and username

                mysqli_stmt_close($stmt);
                
                if (password_verify($passWD, $passWDHash)) {

                    if($suspend == 1){

                        echo nav();

                        $err = ["This account has been suspended"];
            
                        echo signInForm($err);                

                    } elseif($activeCheck != 1){

                        echo nav();
                        
                        $err = ["This account has been deactivated. To appeal this, please email admin@shuttershare.com."];

                        echo signInForm($err);
                    
                    } else{

                    $query = "UPDATE shutuser SET attempts = 0 WHERE username = '$username'";

                    $result = mysqli_query($conn, $query) or die("Error in query: ". mysqli_error($conn)); 

                    $_SESSION['logged-in'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['iduser'] = $uid;

                    header("location: index.php");
                    }
                }
                //password incorrect
                //display error message and login form

                else {

                    $sql = "UPDATE shutuser SET attempts = attempts + 1 WHERE username = '$username'";

                    if (mysqli_query($conn, $sql)) {

                        echo nav();

                        $err = ["Password incorrect"];
            
                        echo signInForm($err); 

                        echo "</div></div>"; 

                    } else{
                        
                        echo "Error updating record: " . mysqli_error($conn); 
                    
                    }

                }

            }
            //if username was incorrect
            //display error message and login form 
            else {
                //close statement
                mysqli_stmt_close($stmt);

                echo nav();

                $err = ["Username doesn't exist"];

                echo signInForm($err);

                echo "</div></div>";
            }

    
        }

    } elseif ( isset( $_POST['register'] ) ) {    

        //Filter_Has_Var validation
            $fname = filter_has_var(INPUT_POST, 'fname') ? $_POST['fname'] : null;

            $surname = filter_has_var(INPUT_POST, 'surname') ? $_POST['surname'] : null;

            $username = filter_has_var(INPUT_POST, 'username') ? $_POST['username']: null;
    
            $email = filter_has_var(INPUT_POST, 'email') ? $_POST['email'] : null;
    
            $passWD = filter_has_var(INPUT_POST, 'password') ? $_POST['password']: null;

            $passCon = filter_has_var(INPUT_POST, 'confirmPassword') ? $_POST['confirmPassword']: null;

            $mobileNumber = filter_has_var(INPUT_POST, 'mobileNumber') ? $_POST['mobileNumber']: null;

            $phoneNumber = filter_has_var(INPUT_POST, 'phoneNumber') ? $_POST['phoneNumber']: null;


            $addline1 = filter_has_var(INPUT_POST, 'addline1') ? $_POST['addline1']: null;

            $addline2 = filter_has_var(INPUT_POST, 'addline2') ? $_POST['addline2']: null;

            $city = filter_has_var(INPUT_POST, 'city') ? $_POST['city']: null;

            $zipcode = filter_has_var(INPUT_POST, 'zipcode') ? $_POST['zipcode']: null;

            $region = filter_has_var(INPUT_POST, 'region') ? $_POST['region']: null;

            $country = filter_has_var(INPUT_POST, 'country') ? $_POST['country']: null;


            $question = filter_has_var(INPUT_POST, 'question') ? $_POST['question']: null;

            $answer = filter_has_var(INPUT_POST, 'answer') ? $_POST['answer']: null;

        //Trim Values
            $fname = trim($fname); 
    
            $surname = trim($surname);
    
            $username = trim($username);
    
            $email = trim($email); 
    
            $passWD = trim($passWD);

            $passCon = trim($passCon);
    
    
            $addline1 = trim($addline1); 
    
            $addline2 = trim($addline2);
    
            $city = trim($city);
    
            $zipcode = trim($zipcode); 
    
            $region = trim($region);
    
            $country = trim($country); 
    
    
            $question = trim($question);
    
            $answer = trim($answer); 


        //VALIDATION

        $errors = array();

            if (empty($passWD)) {

              $errors[]="You have not entered a password";
            }
            //check that the password entered is over 5 charectors
            elseif(strlen($passWD) < 5) {

              $errors[]="Passwords must include at least 5 characters";
            } 
            

        if (empty($email)) {

            $errors[]="You have not entered a email address";

        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        

        } else {

             $errors[]="Email Address is invalid";
        }



         if ($passWD === $passCon) {

            if (preg_match('/[A-Z]+[a-z]+[0-9]+/', $passWD))

            {
                

            } else {

                $errors[]="Password Must contain:<br>One uppercase letter <br> One lowercase letter <br> One number";

            }


        } else {

            $errors[]="Passwords do not match";

        }

  
        $sql = "SELECT * FROM shutuser WHERE email = '$email'";

        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

        $numrows = mysqli_num_rows($result);

        if ($numrows >= 1){

            $errors[]="Email already in use";

        }

        $sql = "SELECT * FROM shutuser WHERE username = '$username'";

        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

        $numrows = mysqli_num_rows($result);

        if ($numrows >= 1){

               $errors[]="Username already in use";

        }


        if (!empty($errors)) {
           
            echo nav();
            
            echo registerForm($errors);

            echo "</div></div>";  

            echo javascript("register.js"); 

        } else { 


        //Address SQL

            $addresssql = "INSERT INTO billingaddress (addline1, addline2, city, zipcode, region, country)
                VALUES ( ?,?,?,?,?,?)";

            $addressstmt = mysqli_prepare($conn, $addresssql) or die(mysqli_error($conn));
    
            mysqli_stmt_bind_param($addressstmt, "ssssss", $addline1, $addline2, $city, $zipcode, $region, $country) or die(mysqli_error($conn));
    
            mysqli_stmt_execute($addressstmt) or die(mysqli_error($conn));
    
            mysqli_stmt_close($addressstmt);

            $billingAD = mysqli_insert_id($conn);


        //Questions SQL
            $questionsql = "INSERT INTO securityanswers (answer, question) VALUES ( ?,?)";
    
            $questionstmt = mysqli_prepare($conn, $questionsql) or die(mysqli_error($conn));
    
            mysqli_stmt_bind_param($questionstmt, "ss", $answer, $question) or die(mysqli_error($conn));
    
            mysqli_stmt_execute($questionstmt) or die(mysqli_error($conn));
    
            mysqli_stmt_close($questionstmt);



            $questionID = mysqli_insert_id($conn);

        //User SQL
            $passWDHash = password_hash( $passWD, PASSWORD_DEFAULT);

            $created = date("Y-m-d H:i:s");

            $sql = "INSERT INTO shutuser (username, password, email, fname, surname, mobile, tel, billingAD, securityA, created)
                VALUES ( ?,?,?,?,?,?,?,?,?,?)";

            $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
    
            mysqli_stmt_bind_param($stmt, "ssssssssss", $username, $passWDHash, $email, $fname, $surname, $mobileNumber, $phoneNumber,  $billingAD, $questionID, $created) or die(mysqli_error($conn));
    
            mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    
            mysqli_stmt_close($stmt);


            $user_ID = mysqli_insert_id($conn);

        //Log in
            $_SESSION['logged-in'] = true;

            $_SESSION['username'] = $username;

            $_SESSION['iduser'] = $user_ID;


            $readMark = 0;
            $flag = 0;
            $sent = 1;
            $folder = 1;
            $systemid = 0;

            $messageSubject = "Welcome to ShutterShare, ".$username."!";
            $messageCont = "<div>On behalf of the entire community, we would like to welcome you to ShutterShare, ".$username."!</div>
            <div><br></div>
            <div>So, who are we?</div>
            <div><br></div>
            <div>ShutterShare is a camera renting service, where you can be both the customer and the retailer! Our services allow anyone to put up a camera. To do this, all you have to do is click on the button in the top-right corner, and fill out all the details. 
            To purchase a camera, you can have a look at all the available adverts. Once finding one to your liking, you can simply select the dates you wish to book it out, and press Rent.</div>
            <div><br></div>
            <div>In order to provide you with both transparency and security, the booking process is a two-fold manner. Upon clicking rent, the owner of the advert first has to agree to the terms you have set. However, the dates you selected will no longer be up for the taking, so if your advert get's declined, it does not mean the dates were no longer available. 
            Once you have sent a booking request, the owner will approve or decline it. Once approved, you will have paid for the camera for the desired time-window. However, you can still cancel the window, with 100% refund, give or take a few days for the bank to process the request.</div>
            <div><br></div>
            <div>If you wish to amend your booking, you can also do so. You will be reimbursed, and your new dates will be reserved once more. The costs will change depending on the advert's current cost per day, so keep that in mind. Once you submitted your new dates, the request will be sent back to the advert owner for reapproval.</div>
            <div><br></div>
            <div>Oh yeah, before we forget, this here is your inbox. We won't get in touch with you unless something important comes along, so don't worry about spam. However, you can message any user you wish using this inbox service. You will receive all messages sent through the system here. The yellow icon on the top-right of your username in the navigation will let you know if you have received a new message!</div>
            <div><br></div>
            <div>We sincerely hope you enjoy the use of our platform. For any questions, refer to the FAQ. If someone is bothering you, or you see content you don't like, don't hesitate to report it. A member of staff will have a look at it for you!</div>
            <div><br></div>
            <div>Once again, Welcome!</div>
            <div><br></div>
            <div>Regards, </div>
            <div><br></div>
            <div>ShutterShare Team</div>
            <div><br></div>
            <div>-- This is an automated message sent by the system welcoming the new registrant and introducing the system --</div>
            <div>-- System Notifications are no-reply messages. No one will respond to any replies to this user -- </div>";
            
            $sendDate = date("Y-m-d");
            $sendTime = date("H:i:s");

            $sqlMessage = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtMess = mysqli_prepare($conn, $sqlMessage) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmtMess, "ssssssssss", $messageSubject, $messageCont, $sendDate, $sendTime, $readMark, $flag, $sent, $user_ID, $systemid, $folder) or die(mysqli_error($conn));
            mysqli_stmt_execute($stmtMess) or die(mysqli_error($conn));
            mysqli_stmt_close($stmtMess) or die(mysqli_error($conn));


            header("location: index.php");

            echo nav();

            echo homeContent() ;
                         
            echo footer();

        }

    echo pageClose();

    mysqli_close($conn);

}

?>