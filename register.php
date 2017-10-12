<?php
  include 'server/db_conn.php';
  require_once('functions.php');
  echo pageIni("Register - Shuttershare");

  echo nav();
?>
    <div class="account-side">
        <div class="center">
            <form action="login.php" id="register-new-user" method="post">
                <section class="sign-in-register-title">
                        <h1>Shutter<span id="share">Share</span></h1>
                        <p id="error-tag"></p>
                </section>
                <div id="register-part1">
                    <section class="sign-in-register">
                        <input type="text" name="username" placeholder="Username*" required>
                        <input type="text" name="email" placeholder="Email Address*" required>
                        <input type="password" name="password" placeholder="Password*" required>
                        <input type="password" name="confirmPassword" placeholder="Confirm Password*" required>
                    </section>
                    <section class="sign-in-register">
                        <input type="button" id="step-2" value="Register for Free">
                    </section>
                    <section class="register">
                        <p>Already have an account? <a href="signin.php" >Sign In</a></p>
                    </section>
                </div>
                <div id="register-part2">
                    <section class="sign-in-register">
                        <input type="text" name="fname" placeholder="First Name*" required>
                        <input type="text" name="surname" placeholder="Last Name*" required>
                        <input type="text" name="mobileNumber" placeholder="Mobile Number">
                        <input type="text" name="phoneNumber" placeholder="Phone Number">
                    </section>
                    <section class="sign-in-register">
                        <input type="button" id="step-to-1" value="Previous">
                        <input type="button" id="step-3" value="Next">
                    </section>
                </div>
                <div id="register-part3">
                    <section class="sign-in-register">
                        <input type="text" name="addline1" placeholder="Address Line 1*">
                        <input type="text" name="addline2" placeholder="Address Line 2">
                        <input type="text" name="city" placeholder="Town / City*">
                        <input type="text" name="zipcode" placeholder="Postcode*">
                        <input type="text" name="region" placeholder="County*">
                        <input type="text" name="country" placeholder="Country*">
                    </section>
                    <section class="sign-in-register">
                        <input type="button" id="step-to-2" value="Previous">
                        <input type="button" id="step-4" value="next">
                    </section>
                </div>
                 <div id="register-part4">
                    <section class="sign-in-register">
                    <?php
                            $sql = "SELECT idsecurityquestions, question 
                                   FROM securityquestions";
                            $result = mysqli_query($conn, $sql);
                           
                            echo "<select name='question'>";      
                                echo "<option value=\"\">-- Select a Security Question --</option>";
                                while ($row = mysqli_fetch_assoc($result)) {
                                   echo "<option value='".$row['idsecurityquestions']."'>".$row['question']."</option>";
                                }
                            echo "</select>";
                    ?>
                    
                    <input type="text" name="answer" placeholder="Answer">
                    </section>
                    <section class="sign-in-register">
                        <input type="button" id="step-to-3" value="Previous">
                        <input type="submit" value="Create Account" name="register">
                    </section>
                </div>
            </form>
<?php
    echo javascript("register.js");
    echo pageClose();
?>