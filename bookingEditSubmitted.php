<?php
  include 'server/db_conn.php';
  require_once('functions.php');

  if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
    echo pageIni("Access Denied");
      echo error();
  } else {
  echo pageIni("Edit Booking | Bookings - Shuttershare");

  echo nav2();
  echo nav3("", " class=\"active\"", "", "");
?>
    <div class="back-end">
      <div class="back-end-center">
<?php
  echo breadcrumb("bookings.php", "My Bookings", "Edit Bookings");

?>
        <div class="book-advert-back" id="book-advert-back">
          <p id="formInstructions">The details of the changes you wish to make to your booking:</p>

          <?php

          if(isset($_POST['availabilityid'])){
            $pickUpDate = $_POST['pickUpDate'];
            $dropOffDate = $_POST['dropOffDate'];
            $idavai = $_POST['availabilityid'];

            $fillerPickUp = strtr($pickUpDate, '/', '-');
            $fillerDropOff = strtr($dropOffDate, '/', '-');

            $newPickUpDate = date("Y-m-d", strtotime($fillerPickUp));
            $newDropOffDate = date("Y-m-d", strtotime($fillerDropOff));

            $costoriginal = $_POST['costoriginal'];
            $costnew = $_POST['costnew'];
            $active = 0;
            $bookingid = $_POST['bookingid'];

            $sql = "UPDATE availability SET start = ?, end = ? WHERE idavailability = ?";
            $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, "ssd", $newPickUpDate, $newDropOffDate, $idavai) or die(mysqli_error($conn));
            mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
            mysqli_stmt_close($stmt) or die(mysqli_error($conn));

            $sql2 = "UPDATE bookings SET costTotal = ?, active = ? WHERE idbookings = ?";

            $stmt2 = mysqli_prepare($conn, $sql2) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmt2, "sdd", $costnew, $active, $bookingid) or die(mysqli_error($conn));
            mysqli_stmt_execute($stmt2) or die(mysqli_error($conn));
            mysqli_stmt_close($stmt2) or die(mysqli_error($conn));

            $sqlPayment = "SELECT shutuser.username, advert.title, bookings.userid, bookings.ownerid FROM bookings 
            INNER JOIN shutuser ON bookings.userid = shutuser.iduser 
            INNER JOIN advert ON bookings.advert = advert.idadvert
            WHERE idbookings = $bookingid";
            $rPay = mysqli_query($conn, $sqlPayment) or die(mysqli_error($conn));
            $rowPay = mysqli_fetch_assoc($rPay);

            $userid = $rowPay['userid'];
            $ownerid = $rowPay['ownerid'];
            $userName = $rowPay['username'];
            $advertName = $rowPay['title'];

            $readMark = 0;
            $flag = 0;
            $sent = 1;
            $folder = 1;
            $sendDate = date("Y-m-d");
            $sendTime = date("H:i:s");

            $messageSubject = $userName." amended their booking of '".$advertName."'!";
            $messageCont = "<div>A user has amended their booking of ".$advertName.". It has been sent to \"Advert Requests\" inside \"My Advert\" for reapproval.</div>
            <div><br></div>
            <div>So, what happens next?</div>
            <div><br></div>
            <div>Since the user and you had both agreed on a price and rental period, we need to set a reconfirmation to make sure both parties agree to the new rental period and therefore a new price. To do this, we have reset your booking. This means the user will be reimbursed for the amount of the previous booking from your account. When you approve the new booking request, you will be paid the new amount. Please give your bank a few days to complete the process.</div>
            <div><br></div>
            <div>The booking that was previously agreed upon has been effectively disabled and cancelled.</div>
            <div><br></div>
            <div>We apologize for any inconveniences caused.</div>
            <div><br></div>
            <div>ShutterShare Team</div>
            <div><br></div>
            <div>-- This is an automated message sent by the system to notify the user of a change in circumstances regarding a previously agreed upon booking --</div>";

            $sqlMessage = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt5 = mysqli_prepare($conn, $sqlMessage) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmt5, "ssssssssss", $messageSubject, $messageCont, $sendDate, $sendTime, $readMark, $flag, $sent, $ownerid, $userid, $folder) or die(mysqli_error($conn));
            mysqli_stmt_execute($stmt5) or die(mysqli_error($conn));
            mysqli_stmt_close($stmt5) or die(mysqli_error($conn));

            $sql3 = "UPDATE paymentdetails SET wallet = wallet - ? WHERE user = ?";

              $stmt3 = mysqli_prepare($conn, $sql3) or die(mysqli_error($conn));
              mysqli_stmt_bind_param($stmt3, "dd", $costoriginal, $ownerid) or die(mysqli_error($conn));
              mysqli_stmt_execute($stmt3) or die(mysqli_error($conn));
              mysqli_stmt_close($stmt3) or die(mysqli_error($conn));

            $sql4 = "UPDATE paymentdetails SET wallet = wallet + ? WHERE user = ?";

                $stmt4 = mysqli_prepare($conn, $sql4) or die(mysqli_error($conn));
                mysqli_stmt_bind_param($stmt4, "dd", $costoriginal, $userid) or die(mysqli_error($conn));
                mysqli_stmt_execute($stmt4) or die(mysqli_error($conn));
                mysqli_stmt_close($stmt4) or die(mysqli_error($conn));


            $sqlResult = "SELECT * FROM availability WHERE idavailability = $idavai";
            $resultR = mysqli_query($conn, $sqlResult) or die(mysqli_error($conn));
            while($row = mysqli_fetch_assoc($resultR)){

              $upickupdate = $row['start'];
              $udropoffdate = $row['end'];

              echo "<div class=\"book-data\">
                <img src=\"resources/img/d3200.jpg\">
                <div>
                    <ul>
                      <li><a href=\"\">Nikon D3200 SLR</a></li>
                        <li>Rented From: <span>nikon17</span></li>
                        <li>Pick Up: $upickupdate</li>
                        <li>Drop Off: $udropoffdate</li>
                    </ul>
                </div>
              </div>
              <div class=\"tick\">
                  <img src=\"resources/img/tick.png\">
                  <p>Your request has been sent for reapproval.</p>
              </div>
              <a id=\"accountButtonLinkWide\" href=\"bookings.php\">BACK</a>";
            }
          }
          ?>
        </div>
      </div>
    </div>
<?php
  }
  echo pageClose();
?>
