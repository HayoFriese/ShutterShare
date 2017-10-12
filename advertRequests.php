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
      <?php
        echo breadcrumb("bookings.php", "My Bookings", "BOOKING REQUESTS");
      ?>
      <form id="bookingElements" action="" method="post">
            <div class="book-advert-back">
              <p id="formInstructions">Request Placed: 09/09/17 <span>Total: £50</span><p>
              <div class="book-data">
                  <img src="resources/img/d3200.jpg">
                  <div>
                    <ul>
                      <li>Nikon D3200 SLR</li>
                      <li>Owner: <span>nikon17</span></li>
                        <li><a id="accountButtonLink" href="">ACCEPT REQUEST</a></li>
                        <li><a id="accountButtonLink" href="">DECLINE REQUEST</a></li>
                    </ul>
                  </div>
              </div>
            </div>
        </form>
      </div>
<?php
  }

  echo javascript("booking.js");

  echo pageClose();
?>