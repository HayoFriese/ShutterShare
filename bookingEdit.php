<?php
  include 'server/db_conn.php';
  require_once('functions.php');

  if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
    echo pageIni("Access Denied");
      echo error();
  } else {
  echo pageIni("Edit Booking | Bookings - Shuttershare");

  echo nav2();
  echo nav3("", " class=\"\"", "", "");

  $userid_loggedin = $_SESSION['iduser'];
  $advertid = $_GET['advert'];
  $bookingid = isset($_GET['bookingid']) ? $_GET['bookingid']: null;

  $arrayName = [];

  $sqlUn = "SELECT availability.start, availability.end FROM bookings
  INNER JOIN availability ON bookings.availabilityid = availability.idavailability
  WHERE availability.advert = $advertid AND bookings.userid != $userid_loggedin";

  $rUn = mysqli_query($conn, $sqlUn) or die(mysqli_error($conn));

  while($row = mysqli_fetch_assoc($rUn)) {
    $start = $row['start'];
    $end = $row['end'];

    array_push($arrayName, $start, $end);
  }

  $sqlCost = "SELECT bookings.costTotal, advert.cost FROM bookings
  INNER JOIN advert ON bookings.advert = advert.idadvert
  WHERE advert = $advertid AND idbookings = $bookingid";
  $rCost = mysqli_query($conn, $sqlCost) or die(mysqli_error($conn));
  
  while($roCost = mysqli_fetch_assoc($rCost)){
    $costinitial = $roCost['cost'];
    $costTotal = $roCost['costTotal'];
  }
?>
    <div class="back-end">
      <div class="back-end-center">
<?php
  echo breadcrumb("bookings.php", "My Bookings", "Edit Bookings");
?>
            <div class="form-background">
              <form id="bookingElements" action="bookingEditSubmitted.php" method="post">
                <?php

                      $sql = "SELECT availability.idavailability, availability.start,
                        availability.end
                        FROM bookings
                        INNER JOIN availability
                        ON bookings.availabilityid = availability.idavailability
                        WHERE userid = ? AND idbookings = ?";

                        $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
                        mysqli_stmt_bind_param($stmt, "dd", $userid_loggedin, $bookingid) or die(mysqli_error($conn));
                        mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
                        mysqli_stmt_bind_result($stmt, $idavai, $startDate, $endDate) or die(mysqli_error($conn));

                      while (mysqli_stmt_fetch($stmt)) {
                        $newPickUpDate = date("d-m-Y", strtotime($startDate));
                        $newDropOffDate = date("d-m-Y", strtotime($endDate));

                        $fillerPickUp = strtr($newPickUpDate, '-', '/');
                        $fillerDropOff = strtr($newDropOffDate, '-', '/');


                        echo "<label for=\"pickUpDate\">Pick Up Date:</label>
                          <input type=\"text\" id=\"pickUpDate\" name=\"pickUpDate\" value=\"$fillerPickUp\" />
                          <input type=\"hidden\" name=\"availabilityid\" value=\"$idavai\" />
                          <input type=\"hidden\" id=\"hideCost\" name=\"costinitial\" value=\"$costinitial\" />
                          <input type=\"hidden\" name=\"costoriginal\" value=\"$costTotal\" />
                          <input type=\"hidden\" name=\"bookingid\" value=\"$bookingid\" />
                          <input type=\"hidden\" name=\"costnew\" id=\"costNew\" value=\"$costTotal\" />

                          <label for=\"dropOffDate\">Drop Off Date:</label>
                          <input type=\"text\" id=\"dropOffDate\" class=\"dropOffDate2\" name=\"dropOffDate\" value=\"$fillerDropOff\" />

                          <label for=\"pickUpDate\">New Cost:</label>
                          <input type=\"text\" id=\"showCost\" value=\"&pound;$costTotal\" readonly />

                          <input type=\"submit\" value=\"SAVE CHANGES\">";
                      }

                mysqli_stmt_close($stmt) or die(mysqli_error($conn));

              ?>
            </form>

            </div>
          </div>
      </div>
      <script type="text/javascript">
        <?php
          $js_array = json_encode($arrayName);
          echo "var dr = ".$js_array.";\n";
        ?>
      </script>
<?php
  }
  echo javaAdvert('advert.js');
  echo pageClose();
?>
