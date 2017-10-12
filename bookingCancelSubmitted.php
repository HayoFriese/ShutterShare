<?php

if(isset($_GET['bookingid'])){

  include 'server/db_conn.php';
  require_once('functions.php');

  if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
    echo pageIni("Access Denied");
      echo error();
  } else {
  echo pageIni("Cancel Booking | Bookings - Shuttershare");

  echo nav2();
  echo nav3("", " class=\"active\"", "", "");

    echo "<div class=\"back-end\">
      <div class=\"back-end-center\">";

  echo breadcrumb("bookings.php", "My Bookings", "Cancel Bookings");

        echo "<div class=\"book-advert-back\" id=\"book-advert-back\">";


            $bookingid = $_GET['bookingid'];
            $iduser = $_SESSION['iduser'];

            //Reimburse
            $sqlReimburse = "SELECT bookings.costTotal, shutuser.username, shutuser.paymentDet, advert.title, bookings.userid, bookings.ownerid FROM bookings 
            INNER JOIN shutuser ON bookings.userid = shutuser.iduser 
            INNER JOIN advert ON bookings.advert = advert.idadvert 
            INNER JOIN paymentdetails ON shutuser.paymentDet = paymentdetails.idpaymentdetails 
            WHERE idbookings = $bookingid";

            $rReimburse = mysqli_query($conn, $sqlReimburse) or die(mysqli_error($conn));
            while($rowRe = mysqli_fetch_assoc($rReimburse)){
              $costTotal = $rowRe['costTotal'];
              $username = $rowRe['username'];
              $payID = $rowRe['paymentDet'];
              $advertName = $rowRe['title'];
              $iduser = $rowRe['userid'];
              $idowner = $rowRe['ownerid'];

              $systemId = 0;

              $readMark = 0;
              $flag = 0;
              $sent = 1;
              $folder = 1;
  
              $messageSubject = $username." has cancelled their booking of '".$advertName."'";
              $messageCont = "<div>".$username." has cancelled their booking of '".$advertName."', and is no longer requesting to rent out the advert.</div>
              <div><br></div>
              <div>So, what happens next?</div>
              <div><br></div>
              <div>Unfortunately, since the user is no longer willing to rent out the camera for the desired days they initially wanted, we are obliged to reimburse the user. As you received money from our system upon approving their booking request, we will be reimbursing them from your account. Please refer to the Terms of Agreements for more information.</div>
              <div><br></div>
              <div>Also, please give your bank a few days to complete this process.</div>
              <div><br></div>
              <div>We apologize for the inconvenience.</div>
              <div><br></div>
              <div>ShutterShare Team</div>
              <div><br></div>
              <div>-- This is an automated message sent by the system to notify the advert owner of the cancellation of an approved booking --</div>";
          
              $sendDate = date("Y-m-d");
              $sendTime = date("H:i:s");

              $sqlMessage = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_prepare($conn, $sqlMessage) or die(mysqli_error($conn));
              mysqli_stmt_bind_param($stmt, "ssssssssss", $messageSubject, $messageCont, $sendDate, $sendTime, $readMark, $flag, $sent, $idowner, $systemId, $folder) or die(mysqli_error($conn));
              mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
              mysqli_stmt_close($stmt) or die(mysqli_error($conn));

            }


            $sqlAddMon = "UPDATE paymentdetails set wallet = wallet + ? WHERE idpaymentdetails = ? AND user = ?";
            $stmt = mysqli_prepare($conn, $sqlAddMon) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, "ddd", $costTotal, $payID, $iduser) or die(mysqli_error($conn));
            mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
            mysqli_stmt_close($stmt) or die(mysqli_error($conn));


            $sqlRemoveMon = "UPDATE paymentdetails set wallet = wallet - ? WHERE idpaymentdetails = ? AND user = ?";
            $stmt = mysqli_prepare($conn, $sqlRemoveMon) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, "ddd", $costTotal, $payID, $idowner) or die(mysqli_error($conn));
            mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
            mysqli_stmt_close($stmt) or die(mysqli_error($conn));

            $sql = "UPDATE bookings SET active = 4 WHERE idbookings = ?";
            $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, "d", $bookingid) or die(mysqli_error($conn));
            mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
            mysqli_stmt_close($stmt) or die(mysqli_error($conn));

            echo "<div class=\"tick\">
              <img src=\"resources/img/tick.png\">
              <p>Your booking has been cancelled.</p>
            </div>
            <a id=\"accountButtonLinkWide\" href=\"bookings.php\">BACK</a>";
          }

        echo"</div>
      </div>
    </div>";
    echo pageClose();
  }
else {
  header("location: bookings.php");
}
?>