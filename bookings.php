<?php
  include 'server/db_conn.php';
  require_once('functions.php');
  if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
    echo pageIni("Access Denied");
      echo error();
  } else {
  echo pageIni("My Bookings - Shuttershare");

  echo nav2();
  echo nav3("", " class=\"active\"", "", "");
?>
    <div class="back-end">
      <h1>Active Bookings</h1>
      <section class="book-list">
        <div>

          <?php

          $userid_loggedin = $_SESSION['iduser'];

          $sqlActive = "SELECT idbookings, bookingDate, costTotal, bookings.advert, advert.title, shutuser.username FROM bookings 
          INNER JOIN advert ON bookings.advert = advert.idadvert 
          INNER JOIN shutuser ON bookings.ownerid = shutuser.iduser
          WHERE userid = $userid_loggedin AND bookings.active = 1";
          $resultActive = mysqli_query($conn, $sqlActive) or die(mysqli_error($conn));

          while ($row = mysqli_fetch_assoc($resultActive)) {
            $bookingid = $row['idbookings'];
            $bookingDate = $row['bookingDate'];
            $costTotal = $row['costTotal'];
            $advertid = $row['advert'];
            $adtit = $row['title'];
            $owname = $row['username'];

            $sqlmedia1 = "SELECT src, alt FROM shutmedia WHERE advert = $advertid LIMIT 1";
            $rmedia1 = mysqli_query($conn, $sqlmedia1) or die(mysqli_query($conn));
            $img = mysqli_fetch_assoc($rmedia1)['src'];
            $alt = mysqli_fetch_assoc($rmedia1)['alt'];

            echo "<div class=\"book-advert-back\">
              <p id=\"formInstructions\">Booking Placed: $bookingDate <span>Total: £$costTotal</span></p>
              <div class=\"book-data\">
                <img src=\"$img\" alt=\"$alt\">
                <div>
                  <ul>
                    <li>$adtit</li>
                    <li>Owner: <span>$owname</span></li>
                    <li><a id=\"accountButtonLink\" href=\"bookingEdit.php?bookingid=$bookingid&advert=$advertid\">EDIT BOOKING</a></li>
                    <li><a id=\"accountButtonLink\" class=\"cancel-booking\" data-id=\"$bookingid\" href=\"bookingCancelSubmitted.php?bookingid=$bookingid\">CANCEL BOOKING</a></li>
                  </ul>
                </div>
              </div>
            </div>";
          }

          ?>

        </div>
      </section>

      <h1>Past Bookings</h1>
      <section class="book-list">
        <div>

          <?php

          $sqlPast = "SELECT idbookings, bookingDate, costTotal, bookings.advert, advert.title, shutuser.username FROM bookings 
          INNER JOIN advert ON bookings.advert = advert.idadvert 
          INNER JOIN shutuser ON bookings.ownerid = shutuser.iduser
          WHERE userid = $userid_loggedin AND bookings.active = 2";

          $resultPast = mysqli_query($conn, $sqlPast) or die(mysqli_error($conn));

          while ($row = mysqli_fetch_assoc($resultPast)) {
            $bookingid = $row['idbookings'];
            $bookingDate = $row['bookingDate'];
            $costTotal = $row['costTotal'];
            $idad = $row['advert'];
            $advertTitle = $row['title'];
            $owner = $row['username'];

            $sqlmedia = "SELECT src, alt FROM shutmedia WHERE advert = $idad LIMIT 1";
            $rmedia = mysqli_query($conn, $sqlmedia) or die(mysqli_query($conn));
            $img = mysqli_fetch_assoc($rmedia)['src'];
            $alt = mysqli_fetch_assoc($rmedia)['alt'];

            echo "<div class='book-advert-back'>".
              "<p id='formInstructions'>$advertTitle <span>Total: £$costTotal</span></p>".
              "<div class='book-data'>".
                "<img src='$img' alt='$alt'>".
                "<div>".
                  "<ul>".
                    "<li>Placed: $bookingDate</li>".
                    "<li>Rented From: <span>$owner</span></li>".
                    "<li><a id='accountButtonLink' href='leaveReview.php?advert=$idad'>LEAVE A REVIEW</a></li>".
                  "</ul>".
                "</div>".
              "</div>".
            "</div>";
          }

          mysqli_close($conn);

          ?>

        </div>
      </section>
    </div>
<?php
  }

  echo javascript("booking.js");

  echo pageClose();
?>
