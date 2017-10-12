<?php
  include 'server/db_conn.php';
  require_once('functions.php');

  $usernameloggedin = $_SESSION['username'];
  $userid_loggedin = $_SESSION['iduser'];
  $id = $_POST['advertid'];
  $owner = $_POST['owner'];
  $pud = $_POST['pickUpDate'];
  $dod = $_POST['dropOffDate'];
  $cost = $_POST['cost'];

  $sqlUn = "SELECT * FROM availability WHERE advert = $id";
  $rUn = mysqli_query($conn, $sqlUn) or die(mysqli_error($conn));

  $arrayName = array();

  while($row = mysqli_fetch_assoc($rUn)) {
    $start=$row['start'];
    $end=$row['end'];

    array_push($arrayName, $start, $end);
  }

  echo pageIni("Book Advert - Shuttershare");

  echo nav();
?>
    <div id="site">
      <div class="center">
        <section class="breadcrumb">
          <p><a href="viewAdvert.php">Advert</a> &gt; <span id="span-tag">Booking Request 2/3</span></p>
        </section>
        <h1>BOOKING REQUEST</h1>
        <div class="form-background">
            <form id="bookingElements" action="bookingrequestComplete.php" method="post">
              <p id="formInstructions">Please Verify Your Billing Information</p>

              <div id="booking-step-1">
                <label for="pickUpDate">From</label>
                  <?php
                  echo "<input type=\"text\" id=\"pickUpDate\" name=\"pickUpDate\" value= \"$pud\"/>";
                  echo "<input type=\"hidden\" name=\"advertid\" value=\"$id\" />";
                  echo "<input type=\"hidden\" name=\"ownerid\" value=\"$owner\" />";?>

                <label for="dropOffDate">Until</label>
                  <?php echo "<input type=\"text\" id=\"dropOffDate\" name=\"dropOffDate\" value=\"$dod\"/>";?>

                <input type="button" id="booking-next-2" value="NEXT STEP">
              </div>

              <?php

              $sql = "SELECT shutuser.email, shutuser.fname, shutuser.surname, shutuser.billingAd,
              billingaddress.addline1 , billingaddress.addline2, billingaddress.city,
              billingaddress.zipcode, billingaddress.region, billingaddress.country
              FROM billingaddress
              INNER JOIN shutuser
              ON billingaddress.idbillingaddress = shutuser.billingAd
              WHERE iduser = '$userid_loggedin'";

              $result = mysqli_query($conn, $sql);

              while ($row = mysqli_fetch_assoc($result)) {
                $fname = $row['fname'];
                $surname = $row['surname'];
                $addline1 = $row['addline1'];
                $addline2 = $row['addline2'];
                $city = $row['city'];
                $zipcode = $row['zipcode'];

                echo "<div id=\"booking-step-2\">
                      <input type=\"text\" name=\"firstName\" value=\"$fname\" placeholder=\"First Name\">
                      <input type=\"text\" name=\"lastName\" value=\"$surname\" placeholder=\"Last Name\">
                      <input type=\"text\" name=\"addressLine1\" value=\"$addline1\" placeholder=\"Address Line 1\">
                      <input type=\"text\" name=\"addressLine2\" value=\"$addline2\" placeholder=\"Address Line 2\">
                      <input type=\"text\" name=\"townCity\" value=\"$city\" placeholder=\"Town/ City\">
                      <input type=\"text\" name=\"postcode\" value=\"$zipcode\" placeholder=\"Postcode\">
                      <input type=\"button\" id=\"booking-previous-1\" value=\"BACK\">
                      <input type=\"button\" id=\"booking-next-3\" value=\"NEXT STEP\">
                    </div>";
              }


              $sqlPay = "SELECT cardtype.cardtype, paymentdetails.cardnum, paymentdetails.name,
              paymentdetails.expmonth, paymentdetails.expyear, paymentdetails.CCV, paymentdetails.wallet
              FROM shutuser 
              INNER JOIN paymentdetails ON paymentdetails.idpaymentdetails = shutuser.paymentDet
              INNER JOIN cardtype ON paymentdetails.cardtype = cardtype.idcardtype 
              WHERE iduser = $userid_loggedin";

              $resultPay = mysqli_query($conn, $sqlPay) or die(mysqli_error($conn));

                if(mysqli_num_rows($resultPay) > 0) {
                  $paymentfactor = 0;

                  while ($row = mysqli_fetch_assoc($resultPay)) {
                    $cardtype = $row['cardtype'];
                    $cardnum = $row['cardnum'];
                    $name = $row['name'];
                    $expmonth = $row['expmonth'];
                    $expyear = $row['expyear'];
                    $ccv = $row['CCV'];

                    echo "<div id=\"booking-step-3\">
                      <select id=\"cardType\" name=\"cardType\">
                        <option value=\"$cardtype\">$cardtype</option>
                        <option value=\"Visa\">Visa</option>
                        <option value=\"Master Card\">Master Card</option>
                      </select>
                      <input type=\"text\" name=\"NameOnCard\" value=\"$name\" placeholder=\"Name on Card\">
                      <input type=\"text\" name=\"cardNumber\" pattern=\"[0-9]{13,16}\" value=\"$cardnum\" placeholder=\"Card Number\">
                      <select id=\"expiryMonth\" name=\"expiryMonth\">
                        <option value=\"$expmonth\">$expmonth</option>
                        <option value=\"01\">01</option>
                        <option value=\"02\">02</option>
                        <option value=\"03\">03</option>
                        <option value=\"04\">04</option>
                        <option value=\"05\">05</option>
                        <option value=\"06\">06</option>
                        <option value=\"07\">07</option>
                        <option value=\"08\">08</option>
                        <option value=\"09\">09</option>
                        <option value=\"10\">10</option>
                        <option value=\"11\">11</option>
                        <option value=\"12\">12</option>
                      </select>
                      <select id=\"expiryYear\" name=\"expiryYear\">
                        <option value=\"$expyear\">$expyear</option>
                        <option value=\"2016\">2016</option>
                        <option value=\"2017\">2017</option>
                        <option value=\"2018\">2018</option>
                        <option value=\"2019\">2019</option>
                        <option value=\"2020\">2020</option>
                        <option value=\"2021\">2021</option>
                        <option value=\"2022\">2022</option>
                        <option value=\"2023\">2023</option>
                        <option value=\"2024\">2024</option>
                      </select>
                      <input type=\"text\" name=\"cardSecurityCode\" value=\"\" placeholder=\"Card Security Code\">
                      <input type=\"hidden\" name=\"paymentf\" value=\"$paymentfactor\">
                      <input type=\"hidden\" name=\"cost\" value=\"$cost\">
                      <input type=\"button\" id=\"booking-previous-2\" value=\"BACK\">
                      <input type=\"submit\" value=\"SUBMIT\">
                    </div>";
                  }
                }
                else {
                  $paymentfactor = 1;

                  echo "<div id=\"booking-step-3\">
                   <select id=\"cardType\" name=\"cardType\">
                     <option value=\"\">Card Type</option>
                     <option value=\"Visa\">Visa</option>
                     <option value=\"Master Card\">Master Card</option>
                   </select>
                   <input type=\"text\" name=\"NameOnCard\" placeholder=\"Name on Card\">
                   <input type=\"text\" name=\"cardNumber\" placeholder=\"Card Number\">
                   <select id=\"expiryMonth\" name=\"expiryMonth\">
                     <option value=\"\">Expiry Month</option>
                     <option value=\"01\">01</option>
                     <option value=\"02\">02</option>
                     <option value=\"03\">03</option>
                     <option value=\"04\">04</option>
                     <option value=\"05\">05</option>
                     <option value=\"06\">06</option>
                     <option value=\"07\">07</option>
                     <option value=\"08\">08</option>
                     <option value=\"09\">09</option>
                     <option value=\"10\">10</option>
                     <option value=\"11\">11</option>
                     <option value=\"12\">12</option>
                   </select>
                   <select id=\"expiryYear\" name=\"expiryYear\">
                     <option value=\"\">Expiry Year</option>
                     <option value=\"2017\">2017</option>
                     <option value=\"2018\">2018</option>
                     <option value=\"2019\">2019</option>
                     <option value=\"2020\">2020</option>
                     <option value=\"2021\">2021</option>
                     <option value=\"2022\">2022</option>
                     <option value=\"2023\">2023</option>
                     <option value=\"2024\">2024</option>
                   </select>
                   <input type=\"text\" name=\"cardSecurityCode\" value=\"\" placeholder=\"Card Security Code\">
                   <input type=\"button\" id=\"booking-previous-2\" value=\"BACK\">
                   <input type=\"hidden\" name=\"paymentf\" value=\"$paymentfactor\">
                   <input type=\"hidden\" name=\"cost\" value=\"$cost\">
                   <input type=\"submit\" value=\"SUBMIT\">
                  </div>";
    
                }

            mysqli_close($conn);

            ?>

          </form>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      <?php
        $js_array = json_encode($arrayName);
        echo "var dr = ".$js_array.";\n";
        //if you dont need a dates list, then (var dr = [];)
      ?>
    </script>
<?php
  echo javascript("booking.js");
  echo javaAdvert('advert.js');
  echo pageClose();
?>
