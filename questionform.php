<?php

  $email = filter_has_var(INPUT_POST, 'email') ? $_POST['email']: null;

  $sql = "SELECT iduser ,securityA, email, username FROM shutuser WHERE email = '$email'";

  $result = mysqli_query($conn, $sql);

  while ($row = mysqli_fetch_assoc($result)) {

      $securityA = $row['securityA'];

      $email = $row['email'];

      $username = $row['username'];

      $userid = $row['iduser'];

  }

  if (mysqli_num_rows($result)){


    echo "<form action='securityQuestion.php' method='post'>
          <section class=\"sign-in-register-title\">
            <h1>Security Question</h1>
          </section>
          <div>
              <section class='sign-in-register'>";


  $sql = "SELECT securityanswers.idsecurityanswers, securityanswers.answer, securityquestions.question
  FROM securityquestions 
  INNER JOIN securityanswers 
  ON securityquestions.idsecurityquestions = securityanswers.question WHERE idsecurityanswers = '$securityA'";

  $result = mysqli_query($conn, $sql);
                           
  while ($row = mysqli_fetch_assoc($result)) {

      echo "<p>".$row['question']."</p>";

      echo "<input type='hidden' name='answer' value='".$row['answer']."'>";

      }

      echo "</section>
         
        <section class='sign-in-register'>
          <input type='text' name='submitanswer' placeholder='Answer'>
          <input type='hidden' name='email' value='$email'>
          <input type='hidden' name='username' value='$username'>
          <input type='submit' value='Submit' name='questionSubmit'>
        </section>
      </div>
    </form>";
  } else {

    echo "<p>No account associated with that email </p>

          <a id='accountButtonLinkWide' href='forgotPassword.php'>BACK</a>";
  }


?>