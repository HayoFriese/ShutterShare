<?php
  include 'server/db_conn.php';
  require_once('functions.php');
  echo pageIni("My Adverts - Shuttershare");

  echo nav2();
  echo nav3("", "", " class=\"active\"", "");
?>
    <div class="back-end">
      <?php echo breadcrumb("myAdvert.php", "My Adverts", "Decline Request");

          $active = 3;
          $bookingid = $_GET['bookingid'];

          $sql = "UPDATE bookings SET active = ? WHERE idbookings = ?";

          $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
          mysqli_stmt_bind_param($stmt, "sd", $active, $bookingid) or die(mysqli_error($conn));
          mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
          mysqli_stmt_close($stmt) or die(mysqli_error($conn));

          $sqlPayment = "SELECT advert.title, bookings.userid, bookings.ownerid FROM bookings 
          INNER JOIN advert ON bookings.advert = advert.idadvert 
          WHERE idbookings = $bookingid";
          $rPay = mysqli_query($conn, $sqlPayment) or die(mysqli_error($conn));
          $rowPay = mysqli_fetch_assoc($rPay);

          $advertName = $rowPay['title'];
          $userid = $rowPay['userid'];
          $ownerid = $rowPay['ownerid'];
          
          $readMark = 0;
          $flag = 0;
          $sent = 1;
          $folder = 1;

          $messageSubject = "Your booking of '".$advertName."' has been declined!";
          $messageCont = "<div>Your booking of ".$advertName." has been declined by the advert owner.</div>
          <div><br></div>
          <div>So, what happens next?</div>
          <div><br></div>
          <div>The owner declined the request you submitted. This does not necessarily mean that an advert isn't available anymore. Perhaps there might be something wrong with the dates you selected. You can get in touch by responding to this message.</div>
          <div><br></div>
          <div>If you want to try a different range of dates, you can return to the advert by searching '".$advertName."' on the home page.</div>
          <div><br></div>
          <div>We apologize for the inconvenience.</div>
          <div><br></div>
          <div>ShutterShare Team</div>
          <div><br></div>
          <div>-- This is an automated message sent by the system to notify the user that their booking has been declined by the advert owner --</div>";
          
          $sendDate = date("Y-m-d");
          $sendTime = date("H:i:s");

          $sqlMessage = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt2 = mysqli_prepare($conn, $sqlMessage) or die(mysqli_error($conn));
          mysqli_stmt_bind_param($stmt2, "ssssssssss", $messageSubject, $messageCont, $sendDate, $sendTime, $readMark, $flag, $sent, $userid, $ownerid, $folder) or die(mysqli_error($conn));
          mysqli_stmt_execute($stmt2) or die(mysqli_error($conn));
          mysqli_stmt_close($stmt2) or die(mysqli_error($conn));

                  echo "<form id=\"bookingElements\" action=\"\" method=\"post\">
                          <div class=\"book-advert-back\" id=\"book-advert-back\">
                          <div class=\"tick\">
                              <img src=\"resources/img/tick.png\">
                              <p>The advert request has been declined.</p>
                          </div>
                          <a id=\"accountButtonLinkWide\" href=\"myAdvert.php\">BACK</a>
                        </div>";
        ?>
      </div>
      </div>
<?php

  echo pageClose();
?>
