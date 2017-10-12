<?php
  include 'server/db_conn.php';
  require_once('functions.php');
  echo pageIni("My Adverts - Shuttershare");

  echo nav2();
  echo nav3("", "", " class=\"active\"", "");
?>
    <div class="back-end">
          <h1>BOOKING REQUESTS</h1>
          <?php
          $active = 1;
          $bookingid = $_GET['bookingid'];

          $sqlPayment = "SELECT bookings.costTotal, advert.title, bookings.userid, bookings.ownerid FROM bookings 
          INNER JOIN advert ON bookings.advert = advert.idadvert 
          WHERE idbookings = $bookingid";
          $rPay = mysqli_query($conn, $sqlPayment) or die(mysqli_error($conn));
          $rowPay = mysqli_fetch_assoc($rPay);

          $advertName = $rowPay['title'];
          $costTotal = $rowPay['costTotal'];
          $userid = $rowPay['userid'];
          $ownerid = $rowPay['ownerid'];
          
          $readMark = 0;
          $flag = 0;
          $sent = 1;
          $folder = 1;

          $messageSubject = "Your booking of '".$advertName."' has been approved!";
          $messageCont = "<div>Your booking of ".$advertName." has been approved by the advert owner! To view the booking details you can go to the My Bookings tab.</div>
          <div><br></div>
          <div>So, what happens next?</div>
          <div><br></div>
          <div>You and the camera owner are now responsible for arranging how you wish to meet. You can start this process by replying to this automated message to get in touch. Please refer to the Terms of Agreements for proper code of conduct during this procedure. All payments have also been successfully completed. Please give a few days for your bank to complete the process.</div><div><br></div><div>You can still amend your booking! If you wish to change the dates, you can make this change in the My Bookings tab. Please note that you can only do this before the start of your booking. During this process you will be fully reimbursed for your previous payment made, and the booking will be sent for reapproval by the camera owner.</div>
          <div><br></div>
          <div>You can also cancel your booking before your booking begins. To do this, head over to the My Bookings tab. You will be fully reimbursed, give or take a few days for your bank to complete the process.</div>
          <div><br></div>
          <div>If you attempt to cancel or amend your booking after your booking date starts, nothing will happen. Should you not have received your camera, you can get in touch with an administrator by reporting the user's advert.
          <br>
          <br>
          Enjoy your camera!</div>
          <div><br></div>
          <div>ShutterShare Team</div>
          <div><br></div>
          <div>-- This is an automated message sent by the system to notify the user of confirmation of their booking and provide a mechanism to get in touch with the owner --</div>";
          
          $sendDate = date("Y-m-d");
          $sendTime = date("H:i:s");

          $sqlMessage = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt = mysqli_prepare($conn, $sqlMessage) or die(mysqli_error($conn));
          mysqli_stmt_bind_param($stmt, "ssssssssss", $messageSubject, $messageCont, $sendDate, $sendTime, $readMark, $flag, $sent, $userid, $ownerid, $folder) or die(mysqli_error($conn));
          mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
          mysqli_stmt_close($stmt) or die(mysqli_error($conn));

          
          $sql1 = "UPDATE paymentdetails SET wallet = wallet - ? WHERE user = ?";

            $stmt1 = mysqli_prepare($conn, $sql1) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmt1, "dd", $costTotal, $userid) or die(mysqli_error($conn));
            mysqli_stmt_execute($stmt1) or die(mysqli_error($conn));
            mysqli_stmt_close($stmt1) or die(mysqli_error($conn));

          $sql2 = "UPDATE paymentdetails SET wallet = wallet + ? WHERE user = ?";

              $stmt2 = mysqli_prepare($conn, $sql2) or die(mysqli_error($conn));
              mysqli_stmt_bind_param($stmt2, "dd", $costTotal, $ownerid) or die(mysqli_error($conn));
              mysqli_stmt_execute($stmt2) or die(mysqli_error($conn));
              mysqli_stmt_close($stmt2) or die(mysqli_error($conn));

          $sql = "UPDATE bookings SET active = ? WHERE idbookings = ?";

                  $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
                  mysqli_stmt_bind_param($stmt, "sd", $active, $bookingid) or die(mysqli_error($conn));
                  mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
                  mysqli_stmt_close($stmt) or die(mysqli_error($conn));

                  echo "<form id=\"bookingElements\" action=\"\" method=\"post\">
                          <div class=\"book-advert-back\" id=\"book-advert-back\">
                          <div class=\"tick\">
                              <img src=\"resources/img/tick.png\">
                              <p>The advert request has been accepted.</p>
                          </div>
                          <a id=\"accountButtonLinkWide\" href=\"myAdvert.php\">BACK</a>
                        </div>";

        ?>
      </div>
      </div>
<?php
  echo pageClose();
?>
