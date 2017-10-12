<?php
  include 'server/db_conn.php';
  require_once('functions.php');
  echo pageIni("Leave Review | Bookings - Shuttershare");

  echo nav2();
  echo nav3("", " class=\"active\"", "", "");
  $adid = isset($_GET['advert']) ? $_GET['advert']:null;
  $userid_loggedin = $_SESSION['iduser'];
?>
    <div class="back-end">
      <div class="back-end-center">
<?php
  echo breadcrumb("bookings.php", "My Bookings", "Leave a Review");
?>
          <div class="form-background">
            <form id="bookingElements" action="reviewSubmitted.php" method="post">
              <div>
                <p>Review Title:</p>
                <input type="text" name="review-subject" id="review-subject">
                <?php echo "<input type=\"hidden\" name=\"advertid\" value=\"$adid\" />"; ?>
              </div>
              <div>
                <p>Camera Owner Rating:</p>
                <div class="rating">
                  <input class="star star-5" id="star-5" type="radio" name="star" value="5"/>
                  <label class="star star-5" for="star-5"></label>
                  <input class="star star-4" id="star-4" type="radio" name="star" value="4"/>
                  <label class="star star-4" for="star-4"></label>
                  <input class="star star-3" id="star-3" type="radio" name="star" value="3"/>
                  <label class="star star-3" for="star-3"></label>
                  <input class="star star-2" id="star-2" type="radio" name="star" value="2"/>
                  <label class="star star-2" for="star-2"></label>
                  <input class="star star-1" id="star-1" type="radio" name="star" value="1"/>
                  <label class="star star-1" for="star-1"></label>
                </div>
              </div>
              <div>
                <p id="comments">Comments:</p>
                <textarea name="comments" placeholder="Comments About Your Experience..." cols="60" rows="8"></textarea>
              </div>
              <input type="submit" id="submit-review" value="SUBMIT REVIEW">
              <?php echo "<input type=\"hidden\" name=\"iduser\" value=\"$userid_loggedin\" />"; ?>
            </form>
          </div>
        </div>
      </div>
<?php
  echo pageClose();
?>