<?php

echo "<div class=\"back-end\">
      <section class=\"acc-man\">
        <h1>Personal Details</h1>
        <div id=\"no-edit-pers\">";

          $sql = "SELECT username, email, fname, surname, mobile, tel FROM shutuser WHERE iduser = '$userid_loggedin' ";

                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {

                      $mobile = $row['mobile'];
                      $tel = $row['tel'];

                      echo "<p>".$row['username']."</p>".
                        "<p>".$row['fname']."</p>".
                        "<p>".$row['surname']."</p>".
                        "<p>".$row['email']."</p>";

                        if ((empty($mobile) || is_null($mobile)))  {

                          echo "<p style='color:red'> No mobile number added </p>";

                        } else {

                          echo "<p>".$mobile."</p>";

                        }

                        if ((empty($tel) || is_null($tel)))  {

                          echo "<p style='color:red'> No telephone number added</p>";

                        } else {

                          echo "<p>".$tel."</p>";

                        }


                      echo "<button id='edit-pers'>Edit</button>
                    </div>";


                    echo "<div id='pers-edit'>
                      <form action='./myaccount.php' method='post'>".
                      "<input type='text' name='username' value='".$row['username']."' placeholder='Username' required >".
                        "<input type='text' name='fname' value='".$row['fname']."' placeholder='First Name' required>".
                        "<input type='text' name='surname' value='".$row['surname']."' placeholder='Surname' required>".
                        "<input type='text' name='email' value='".$row['email']."' placeholder='Email' required>".
                        "<input type='text' name='mobileNumber' value='".$row['mobile']."' placeholder='Mobile Number'>".
                        "<input type='text' name='phoneNumber' value='".$row['tel']."' placeholder='Phone Number'>".
                          "<input type='submit' value='Save Changes' name='personalDetails'>
                      </form>
                    </div>";
              }

        echo "</section>
      <section class=\"acc-man\">
        <h1>Billing Address</h1>
        <div id=\"no-edit-ad\">";

          //Hayo Stuff
            $sql = "SELECT shutuser.username , shutuser.billingAd, 
            billingaddress.addline1 , billingaddress.addline2, billingaddress.city, billingaddress.zipcode, billingaddress.region, billingaddress.country 
            FROM billingaddress 
            INNER JOIN shutuser
            ON billingaddress.idbillingaddress = shutuser.billingAd 
            WHERE iduser = '$userid_loggedin'";

            $result = mysqli_query($conn, $sql);
      

            while ($row = mysqli_fetch_assoc($result)) {

            $addline1 = $row['addline1'];
            $addline2 = $row['addline2'];
            $city = $row['city'];
            $zipcode = $row['zipcode'];
            $region = $row['region'];
            $country = $row['country'];
            $billingAd = $row['billingAd'];


                if ((empty($addline1) || is_null($addline1)) && (empty($addline2) || is_null($addline2)) && (empty($city) || is_null($city)) && (empty($zipcode) || is_null($zipcode)) && (empty($region) || is_null($region)) && (empty($country) || is_null($country)) )  {

                  echo "<p style='color:red'> No address details added</p>";


                } else {

                   echo "<p>".$addline1."</p>".
                        "<p>".$addline2."</p>".
                        "<p>".$city."</p>".
                        "<p>".$zipcode."</p>".
                        "<p>".$region."</p>".
                        "<p>".$country."</p>";

                }

                echo "<button id='edit-ad'>Edit</button>
                    </div>
                    <div id='ad-edit'>
                      <form action='./myaccount.php' method='post'>".
                        "<input type='text' name='addline1' value='".$addline1."' placeholder='Address Line 1'> ".
                        "<input type='text' name='addline2' value='".$addline2."' placeholder='Address Line 2'> ".
                        "<input type='text' name='city' value='".$city."' placeholder='City'>".
                        "<input type='text' name='zipcode' value='".$zipcode."' placeholder='Postcode E.g. AB12 1BY' pattern='[A-Za-z]{1,2}[0-9Rr][0-9A-Za-z]? [0-9][ABD-HJLNP-UW-Zabd-hjlnp-uw-z]{2}'>".
                        "<input type='text' name='region' value='".$region."' placeholder='County'> ".
                        "<input type='text' name='country' value='".$country."' placeholder='Country'>".
                        "<input type='hidden' name='billingAd' value='".$billingAd."'>".
                        "<input type='submit' value='Save Changes' name='addressDetails'>
                      </form>
                    </div>";

                 
              }

            echo "</section>
      <section class=\"acc-man\">
        <h1>Payment Details</h1>
        <div id=\"no-edit-pay\">";


          //Hayo Stuff
            $sql = "SELECT cardtype.cardtype, paymentdetails.cardnum, paymentdetails.name, paymentdetails.expmonth, paymentdetails.expyear, paymentdetails.CCV
            FROM paymentdetails 
            INNER JOIN cardtype ON paymentdetails.cardtype = cardtype.idcardtype 
            WHERE user = '$userid_loggedin'";

            $result = mysqli_query($conn, $sql);

            $numrows = mysqli_num_rows($result);

            if ($numrows == 0){

                 echo "<p style='color:red'> No Details added yet</p>
                    <button id='edit-pay'>Edit</button>
                    </div>
                    <div id='pay-edit'>
                      <form action='myaccount.php' method='post'>
                        <select name='cardtype'>
                          <option value='1'>Visa Debit</option>
                          <option value='2'>Visa</option>
                          <option value='3'>Mastercard</option>
                        </select>
                        <input type='text' name='cardnum'  placeholder='Card Num' pattern='[0-9]{13,16}'>
                        <input type='text' name='name' placeholder='Card Holders Name'>
                        <input type='number' name='expmonth' placeholder='mm' max='12' min='01'>
                        <input type='number' name='expyear' placeholder='yyyy' min='2017'>
                        <input type='text' name='ccv' placeholder='CCV'>
                        <input type='submit' value='Save Changes' name='paymentDetails'>
                      </form>
                    </div>";

            } else {

              while ($row = mysqli_fetch_assoc($result)) {
                    
                    echo "<p>".$row['cardtype']."</p>".
                      "<p>".$row['cardnum']."</p>".
                      "<p>".$row['name']."</p>".
                      "<p>".$row['expmonth']." / ".$row['expyear']."</p>".
                      "<p>".$row['CCV']."</p>".
  
                    "<button id='edit-pay'>Edit</button>
                    </div>
                    <div id='pay-edit'>
                      <form action='myaccount.php' method='post'>";
                        
                        $sql3 ="SELECT * from cardtype";
                        $r3 = mysqli_query($conn, $sql3) or die(mysqli_error($conn));
                        
                        echo "<select name='cardtype'>";
                        echo "<option value=''>-- Select a Card Type --</option>";
                        
                        while($row3 = mysqli_fetch_assoc($r3)){
                          $cardid = $row3['idcardtype'];
                          $cardlistname = $row3['cardtype'];

                          if($cardlistname == $row['cardtype']){
                            echo "<option value='$cardid' selected>$cardlistname</option>";
                          } else {
                            echo "<option value='$cardid'>$cardlistname</option>";
                          } 
                        }

                        echo "</select>".
                        "<input type='text' name='cardnum' value='".$row['cardnum']."' placeholder='Card Num' pattern='[0-9]{13,16}'>".
                        "<input type='text' name='name' value='".$row['name']."' placeholder='Card Holders Name'>".
                        "<input type='number' name='expmonth' value='".$row['expmonth']."' placeholder='mm' min='1' max='12'>".
                        "<input type='number' name='expyear' value='".$row['expyear']."' placeholder='yyyy' min='2017'>".
                        "<input type='text' name='ccv' value='".$row['CCV']."' placeholder='CCV'>".
                        "<input type='submit' value='Save Changes' name='paymentDetails'>
                      </form>
                    </div>";
                  }
            }

            




        echo "</section>
      <section class=\"acc-man\">
        <h1>Password</h1>
        <div id=\"no-edit\">
          <form action=\"./changepassword.php\">
                      <input type=\"submit\" value=\"Change Password\">
                  </form>
        </div>
      </section>
    </div>";

    ?>