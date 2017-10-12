<?php
    echo "<form action='securityQuestion.php' method='post'>
      <div>
        <section class=\"sign-in-register-title\">
            <h1>Forgot Password</h1>
          </section>
        
        <section class='sign-in-register'>
          <input type='hidden' name='username' value='$username'>
          <input type='password' name='passwordNew' placeholder='New Password' required>
          <input type='password' name='passwordNewC' placeholder='Confirm New Password' required>
        </section>
        <section class='sign-in-register'>
          <input type='submit' value='Reset Password' name='resetPassword'>
        </section>
        <section class='register'>
      </div>
    </form>";
?>