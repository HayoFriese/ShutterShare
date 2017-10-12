<?php
  include 'server/db_conn.php';
  require_once('functions.php');
  echo pageIni("Book Advert - Shuttershare");

  echo nav();

  $costTotal = filter_has_var(INPUT_POST,'cost') ? $_POST ['cost']:null;;
  $userid = $_SESSION['iduser'];

  $pickUpDate = filter_has_var(INPUT_POST, 'pickUpDate') ? $_POST ['pickUpDate']:null;
  $dropOffDate = filter_has_var(INPUT_POST,'dropOffDate') ? $_POST ['dropOffDate']:null;

  $advertid = filter_has_var(INPUT_POST,'advertid') ? $_POST ['advertid']:null;
  $ownerid = filter_has_var(INPUT_POST,'ownerid') ? $_POST ['ownerid']:null;

  $firstName = filter_has_var(INPUT_POST,'firstName') ? $_POST ['firstName']:null;
  $lastName = filter_has_var(INPUT_POST,'lastName') ? $_POST ['lastName']:null;
  $addressLine1 = filter_has_var(INPUT_POST,'addressLine1') ? $_POST ['addressLine1']:null;
  $addressLine2 = filter_has_var(INPUT_POST,'addressLine2') ? $_POST ['addressLine2']:null;
  $townCity = filter_has_var(INPUT_POST,'townCity') ? $_POST ['townCity']:null;
  $postcode = filter_has_var(INPUT_POST,'postcode') ? $_POST ['postcode']:null;

  $cardType = filter_has_var(INPUT_POST,'cardType') ? $_POST ['cardType']:null;
  $nameOnCard = filter_has_var(INPUT_POST,'NameOnCard') ? $_POST ['NameOnCard']:null;
  $cardNumber = filter_has_var(INPUT_POST,'cardNumber') ? $_POST ['cardNumber']:null;
  $expiryMonth = filter_has_var(INPUT_POST,'expiryMonth') ? $_POST ['expiryMonth']:null;
  $expiryYear = filter_has_var(INPUT_POST,'expiryYear') ? $_POST ['expiryYear']:null;
  $cardSecurityCode = filter_has_var(INPUT_POST,'cardSecurityCode') ? $_POST ['cardSecurityCode']:null;
  $paymentFactor = filter_has_var(INPUT_POST, 'paymentf') ? $_POST['paymentf']:null;

  $sqlPaymentDetails = "SELECT CCV FROM paymentdetails WHERE user = $userid";
  $rPaymentCCV = mysqli_query($conn, $sqlPaymentDetails) or die(mysqli_error($conn));
  $ccv = mysqli_fetch_assoc($rPaymentCCV)['CCV'];

  // Sanitise Input To Remove Tags
  $pickUpDate = filter_var($pickUpDate, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $dropOffDate = filter_var($dropOffDate, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $firstName = filter_var($firstName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $lastName = filter_var($lastName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $addressLine1 = filter_var($addressLine1, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $addressLine2 = filter_var($addressLine2, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $townCity = filter_var($townCity, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $postcode = filter_var($postcode, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

  $nameOnCard = filter_var($nameOnCard, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $cardNumber = filter_var($cardNumber, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $cardSecurityCode = filter_var($cardSecurityCode, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

  $pickUpDate = trim($pickUpDate);
  $dropOffDate = trim($dropOffDate);

  // Santising Special Character Input / Strip Tags / Trim
  $firstName = filter_var(strip_tags(trim($firstName)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
  $lastName = filter_var(strip_tags(trim($lastName)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
  $addressLine1 = filter_var(strip_tags(trim($addressLine1)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
  $addressLine2 = filter_var(strip_tags(trim($addressLine2)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
  $townCity = filter_var(strip_tags(trim($townCity)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
  $postcode = filter_var(strip_tags(trim($postcode)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);

  $date = date('Y-m-d');

  $fillerPickUp = strtr($pickUpDate, '/', '-');
  $fillerDropOff = strtr($dropOffDate, '/', '-');

  $newPickUpDate = date("Y-m-d", strtotime($fillerPickUp));
  $newDropOffDate = date("Y-m-d", strtotime($fillerDropOff));

  // Create error array

  $errors = array();


  if (empty($pickUpDate)) {
  $errors[] = "You have not entered a Pick Up Date";
  }
  if (empty($dropOffDate)) {
  $errors[] = "You have not entered a Drop Off Date";
  }
  if (empty($firstName)) {
  $errors[] = "You have not entered your First Name";
  }
  elseif (strlen($firstName) > 255) {
  $errors[] = "First Names have a maximum of 255 characters.";
  }
  if (empty($lastName)) {
  $errors[] = "You have not entered your Last Name";
  }
  elseif (strlen($lastName) > 255) {
  $errors[] = "Last Names have a maximum of 30 characters.";
  }
  if (empty($addressLine1)) {
  $errors[] = "You have not entered the first line of your Address";
  }
  elseif (strlen($addressLine1) > 255) {
  $errors[] = "The first line of your Address has a maximum of 255 characters.</p>\n";
  }
  if (!empty($addressLine2)) {
    if (strlen($addressLine2) > 255) {
      $errors[] = "The second line of your Address has a maximum of 255 characters.</p>\n";
    }
  }
  if (empty($townCity)) {
  $errors[] = "You have not entered your Town or City";
  }
  elseif (strlen($townCity) > 255) {
  $errors[] = "Your Town or City has a maximum of 255 characters.</p>\n";
  }
  if (empty($postcode)) {
  $errors[] = "You have not entered your Post Code";
  }
  elseif (strlen($postcode) > 9) {
  $errors[] = "Your Post Code has a maximum of 9 characters.</p>\n";
  }
  if (empty($cardType)) {
  $errors[] = "You have not entered your Card Type";
  }
  if (empty($nameOnCard)) {
  $errors[] = "You have not entered your name as displayed on your card";
  }
  elseif (strlen($nameOnCard) > 45) {
  $errors[] = "Your name on your card has a maximum of 45 characters.</p>\n";
  }
  if (empty($expiryMonth)) {
  $errors[] = "You have not entered your card's Expiry Month";
  }
  if (empty($expiryYear)) {
  $errors[] = "You have not entered your card's Expiry Year";
  }
  if($paymentFactor == 0){
    if (!empty($cardSecurityCode)) {
      if ($ccv != $cardSecurityCode) {
        $errors[] = "The Card CCV you have entered doesn't match our records";
      }
    } else{
      $errors[] = "You have not entered your card's CCV";
    }
  }


 // Check if array has errors
if (!empty($errors)){
  // If errors, echo message
  echo "<div id=\"site\">
    <div class=\"center\">
      <section class=\"breadcrumb\">
        <p><a href=\"Advert\">Advert</a> &gt; <span>Booking Request 3/3</span></p>
      </section>
      <h1>BOOKING REQUEST</h1>
      <div class=\"book-advert-back\">
          <div class=\"book-data\">
            <div>
              <ul><p>The following problem(s) have occured:</p><br/>\n";
  for ($a=0; $a < count($errors); $a++) {
    echo "<li>$errors[$a]</li>\n";
  }
        echo "</ul>\n<form method=\"post\" action=\"bookingRequest.php\" id=\"error\">
            <input type=\"hidden\" name=\"advertid\" value=\"$advertid\">
            <input type=\"hidden\" name=\"owner\" value=\"$ownerid\">
            <input type=\"hidden\" name=\"cost\" value=\"$costTotal\">
            <input type=\"hidden\" name=\"pickUpDate\" value=\"$pickUpDate\">
            <input type=\"hidden\" name=\"dropOffDate\" value=\"$dropOffDate\">
            <input type=\"submit\" id=\"accountButtonLinkWide\" value=\"BACK\">\n
            </form>\n
          </div>
        </div>
      </div>
      </div>
    </div>";
}

  // If no errors, run $sql and echo booking request confirmation
  else {
    $active = 0;

  $sqlAvailability = "INSERT INTO availability (start, end, advert)
  values(?, ?, ?)";

  $stmtAvailability = mysqli_prepare($conn, $sqlAvailability) or die(mysqli_error($conn));
  mysqli_stmt_bind_param($stmtAvailability, "sss", $newPickUpDate, $newDropOffDate, $advertid) or die(mysqli_error($conn));
  mysqli_stmt_execute($stmtAvailability) or die(mysqli_error($conn));

  mysqli_stmt_close($stmtAvailability) or die(mysqli_error($conn));

  $availabilityid = mysqli_insert_id($conn);

  $sqlBooking = "INSERT INTO bookings (bookingDate, costTotal, active, advert, availabilityid, userid, ownerid)
  values(?, ?, ?, ?, ?, ?, ?)";

  $stmtBooking = mysqli_prepare($conn, $sqlBooking) or die(mysqli_error($conn));
  mysqli_stmt_bind_param($stmtBooking, "sssssss", $date, $costTotal, $active, $advertid, $availabilityid, $userid, $ownerid) or die(mysqli_error($conn));
  mysqli_stmt_execute($stmtBooking) or die(mysqli_error($conn));

  mysqli_stmt_close($stmtBooking) or die(mysqli_error($conn));

  if($paymentFactor == 1) {

    $sqlPay = "INSERT INTO paymentdetails(cardtype, cardnum, name, expmonth, expyear, CCV, user)
    values(?, ?, ?, ?, ?, ?, ?)";

    $stmtPay= mysqli_prepare($conn, $sqlPay) or die(mysqli_error($conn));
    mysqli_stmt_bind_param($stmtPay, "sssssss", $cardType, $cardNumber, $nameOnCard, $expiryMonth, $expiryYear, $cardSecurityCode, $userid) or die(mysqli_error($conn));
    mysqli_stmt_execute($stmtPay) or die(mysqli_error($conn));

    mysqli_stmt_close($stmtPay) or die(mysqli_error($conn));

    $payid = mysqli_insert_id($conn);

    $sqlPay2 = "UPDATE shutuser SET paymentDet = ? WHERE iduser = ?";
    $stmtPay2= mysqli_prepare($conn, $sqlPay2) or die(mysqli_error($conn));
    mysqli_stmt_bind_param($stmtPay2, "ss", $payid, $userid) or die(mysqli_error($conn));
    mysqli_stmt_execute($stmtPay2) or die(mysqli_error($conn));

    mysqli_stmt_close($stmtPay2) or die(mysqli_error($conn));

  }

  $sqlAd = "SELECT advert.title, shutuser.username, shutmedia.src, shutmedia.alt FROM shutmedia 
  InNER JOIN advert ON shutmedia.advert = advert.idadvert 
  INNER JOIN shutuser ON shutmedia.user = shutuser.iduser WHERE shutmedia.advert = $advertid LIMIT 1";
  $rAd = mysqli_query($conn, $sqlAd) or die(mysqli_error($conn));
  while($rowAd = mysqli_fetch_assoc($rAd)){
    $advertName = $rowAd['title'];
    $advertCover = $rowAd['src'];
    $advertAlt = $rowAd['alt'];
    $ownerName = $rowAd['username'];

          $sqlUsername = "SELECT username FROM shutuser WHERE iduser = $userid";
          $rUsername = mysqli_query($conn, $sqlUsername) or die(mysqli_error($conn));

          $uname = mysqli_fetch_assoc($rUsername)['username'];
          $readMark = 0;
          $flag = 0;
          $sent = 1;
          $folder = 1;
          $fromid = 0;

          $messageSubject = $uname." requested to book '".$advertName."'!";
          $messageCont = "<div>".$uname." made a request to book ".$advertName." for rental between ".$pickUpDate." and ".$dropOffDate.".</div>
          <div><br></div>
          <div>To accept this offer or decline it, please head to Advert Requests in My Advert. Declining will notify the user that the advert was not approved by you. </div>
          <div><br></div>
          <div>Once you accept an advert, the payment will be transfered. The user will pay you for the rental of the camera based on the total cost of the advert request. Please give the banks a few days to complete the process.</div>
          <div><br></div>
          <div>By accepting this request, you agree to the Terms of Agreements provided by ShutterShare. You also accept that when a user cancels or wishes to amend the booking prior to ".$pickUpDate.", the starting date of the user's rental period, the user will be fully reimbursed for the money the user paid you. The reimbursement will come from your account.</div>
          <div><br></div>
          <div>Regards,</div>
          <div><br></div>
          <div>ShutterShare Team</div>
          <div><br></div>
          <div>-- This is an automated message sent by the system to notify the user of a booking request that has been placed --</div>";
          
          $sendDate = date("Y-m-d");
          $sendTime = date("H:i:s");

          $sqlMessage = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt = mysqli_prepare($conn, $sqlMessage) or die(mysqli_error($conn));
          mysqli_stmt_bind_param($stmt, "ssssssssss", $messageSubject, $messageCont, $sendDate, $sendTime, $readMark, $flag, $sent, $ownerid, $fromid, $folder) or die(mysqli_error($conn));
          mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
          mysqli_stmt_close($stmt) or die(mysqli_error($conn));
  }

  echo "<div id=\"site\">
    <div class=\"center\">
      <section class=\"breadcrumb\">
        <p><a href=\"Advert\">Advert</a> &gt; <span>Booking Request 3/3</span></p>
      </section>
      <h1>BOOKING REQUEST</h1>
      <div class=\"book-advert-back\" id=\"book-advert-back\">
          <p id=\"formInstructions\">Your Submitted Booking Request:</p>
          <div class=\"book-data\">
            <img src=\"$advertCover\" alt=\"$advertAlt\">
            <div>
                <ul>
                  <li><a href=\"\">$advertName</a></li>
                    <li>Rented From: <span>$ownerName</span></li>
                    <li>Pick Up: $pickUpDate</li>
                    <li>Drop Off: $dropOffDate</li>
                </ul>
            </div>
          </div>
          <div class=\"tick\">
                <img src=\"resources/img/tick.png\">
              <p>Your Booking Request has been submitted!<p>
            </div>
            <a id=\"accountButtonLinkWide\" href=\"viewAdvert.php?id=$advertid\">BACK</a>
      </div>
    </div>
  </div>";

  mysqli_close($conn);

  }

  echo pageClose();
?>
