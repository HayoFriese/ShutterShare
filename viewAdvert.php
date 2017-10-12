<?php
  include 'server/db_conn.php';
  require_once('functions.php');

  //error_reporting(E_ALL);
  //ini_set('display_errors', 1);

  echo pageIni("Shuttershare");
  echo nav();

  $id = $_GET['id'];
  if(isset($_SESSION['iduser'])){
    $userid = $_SESSION['iduser'];
  }

  $sqlView = "UPDATE advert SET views = views + 1 WHERE idadvert = '$id'";
  $sview = mysqli_query($conn, $sqlView) or die(mysqli_error($conn));

  $sqlUn = "SELECT * FROM availability WHERE advert = $id";
  $rUn = mysqli_query($conn, $sqlUn) or die(mysqli_error($conn));

  $arrayName = array();

  while($row = mysqli_fetch_assoc($rUn)) {
    $start=$row['start'];
    $end=$row['end'];

    array_push($arrayName, $start, $end);
  }

  $sqlMed = "SELECT src, alt FROM shutmedia WHERE advert = '$id'";
  $rMed = mysqli_query($conn, $sqlMed) or die(mysqli_error($conn));

  $sql = "SELECT title, keywords, adDesc, cost, advert.active, advert.user,  shutuser.username,  billingaddress.addline1, 
     billingaddress.addline2,  billingaddress.city,  billingaddress.zipcode, 
     billingaddress.region,  billingaddress.country
    FROM  advert
    INNER JOIN  shutuser ON  advert.user =  shutuser.iduser 
    INNER JOIN  billingaddress ON  shutuser.billingAd =  billingaddress.idbillingaddress 
    WHERE idadvert = '$id'";

  $sqlrev = "SELECT idreviews, title, datePost, body, rating, helpful, username FROM  reviews 
              INNER JOIN shutuser ON reviews.user = shutuser.iduser WHERE advert = '$id' AND reviews.active = 1 LIMIT 10";
  $rrev = mysqli_query($conn, $sqlrev) or die(mysqli_error($conn));

  $sqlrev2 = "SELECT rating FROM  reviews WHERE advert = '$id' AND active = 1";
  $rrev2 = mysqli_query($conn, $sqlrev2) or die(mysqli_error($conn));

  $num = mysqli_num_rows($rrev2);

  $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
  
  //Put results into an array
  $advert = mysqli_fetch_array( $result );
  $owner = $advert['user'];
  $title = $advert['title'];
  $keywords = $advert['keywords'];
  $adDesc = $advert['adDesc'];
  $cost  = $advert['cost'];
  $activeTest = $advert['active'];
  $user = $advert['username'];
  $adline1 = $advert['addline1'];
  $adline2 = $advert['addline2'];
  $city = $advert['city'];
  $zip = $advert['zipcode'];
  $country = $advert['country'];

  $adDesc = trim($adDesc);

  if($activeTest != 1){
    header("Location: index.php");
  }

  if(isset($_SESSION['iduser'])){
    echo messageAdvert($id, $userid, $owner, $user, $title);
  } else {
    echo pleaseSignIn();
  }
?>
<div id="site">
  <div class="view-advert-cont">
    <section class="breadcrumb-advert">
      <p>
        <a href="searchResults.php">SEARCH RESULTS</a> &gt;
      <p class="truncate"><?php
        echo $title;
        ?></p>
      </p>
    </section>
    <section class="slideshow">
      <article class="cover" id="cover-img"></article>
      <article class="pics">
        <ul>
        <?php
        $i = 0;
        while($row = mysqli_fetch_assoc($rMed)){
          $src = $row['src'];
          $alt = $row['alt'];
          if($i == 0){
            echo "<li><img class=\"active\" src=\"$src\" alt=\"$alt\"/></li>";
          } else {
            echo "<li><img src=\"$src\" alt=\"$alt\"/></li>";
          }
          $i++;
        }
        ?>
        </ul>
      </article>
    </section>
    <section class="side-box">
      <article>
        <h1><?php
          echo $title;
          ?></h1>
        <div class="side-box-rating">
          <?php
            $sum_rate = 0;
            while($row3 = mysqli_fetch_assoc($rrev2)){
              $sum_rate+= intval($row3['rating']);
            }
            if($sum_rate != 0){
              $avgrate= round($sum_rate/$num);
            }
            if ($sum_rate == 0){
              echo "<span class='noRevs'>No reviews</span>";
            }

            for($i = 1; $i <=5; $i++){
              if ($sum_rate == 0){
                echo "<span></span>";
              }elseif($i <= $avgrate){
                echo "<span id=\"plus\">&#9733;</span>";
              } else {
                echo "<span>&#9733;</span>";
              }
            }


          ?>
        </div>
        <p>
          <?php
          echo $adDesc;
          ?>
        </p>
        <form id="book-step-1" action="bookingRequest.php" method="post">
          <div>
            <h1>From</h1>
            <input type="name" id="pickUpDate" name="pickUpDate" />
            <?php echo "<input type=\"hidden\" name=\"advertid\" value=\"$id\" />"; ?>
            <?php echo "<input type=\"hidden\" name=\"owner\" value=\"$owner\" />"; ?>

            <h1><br/>Until</h1>
            <input type="name" id="dropOffDate" name="dropOffDate" />

          </div>
          <div>
            <input type="text" id="showCost" value='&pound;<?php echo $cost;?>/day' readonly>
            <?php echo "<input type=\"hidden\" id=\"hideCost\" name=\"cost\" value=\"$cost\" />"; ?>
            <?php
            if(isset($_SESSION['iduser'])){
              echo "<input type=\"submit\" value=\"Rent\" name=\"rent\">";
            } else {
              echo "<a href='signin.php' id='rent-sign-in'>Rent</a>";
            }
            ?>
            
          </div>
        </form>
      </article>
      <article>
        <a href="#">Share</a>
        <a id="message-advert-popup"href="#">Message</a>
      </article>
      <div><a href="#" id="toggle-report-advert">&#9872; Report This Advert</a></div>
    </section>
    <section class="sub-box">
      <h1>Description</h1>
      <?php
      echo $adDesc;
      ?>
    </section>
    <section class="sub-box">
      <h1>Owner Information</h1>
      <article class="ad-data">
        <div><p>Username:</p></div>
        <div><?php
          echo $user;
          ?></div>
      </article>
      <article class="ad-data">
        <div><p>Advert Location: </p></div>
        <div>
          <?php
          echo "<p>$adline1</p>";
          if(isset($adline2)){
            echo "<p>$adline2</p>";
          }
          echo "<p>$city</p>";
          echo "<p>$zip</p>";
          echo "<p>$country</p>";
          ?>
        </div>
      </article>
      <a id="message-user-popup" href="#">Message User</a>
    </section>
    <section class="sub-box">
      <h1>Availability</h1>
      <a href="" id="link">+ View Calendar</a>
      <div id="calendar" style="display:none;">
      <div class="week-picker"></div>
       <br />
       <br />
       <span id="startDate"></span> <span id="endDate"></span>
      </div>
    </section>
    <section class="sub-box">
      <h1>Reviews</h1>
      <?php
      $rev_count = mysqli_num_rows($rrev);
      if($rev_count > 0){
        while ($row1 = mysqli_fetch_assoc( $rrev )) {
          $rev_id = $row1['idreviews'];
          $rev_title = $row1['title'];
          $revdate = $row1['datePost'];
          $rev_body = $row1['body'];
          $rev_rating = $row1['rating'];
          $rev_helpful = $row1['helpful'];
          $rev_username = $row1['username'];
  
          $rev_date = strtotime($revdate);
          $rev_date = date("j F, Y", $rev_date);
  
            echo "<article class=\"review-container\">";
              echo "<h2>$rev_title</h2>";
              echo "<div class=\"review-rating\">";
                for($i = 1; $i <=5; $i++){
                  if($i <= $rev_rating){
                    echo "<span id=\"plus\">&#9733;</span>";
                  } else {
                    echo "<span>&#9733;</span>";
                  }
                }
                echo "<p> - $rev_helpful users found this helpful.</p>";
                echo "<input type='hidden' value='$rev_id'>";
              echo "</div>";
              echo "<div class=\"review-content\">";
                echo "<p>$rev_body</p>";
              echo "</div>";
              echo "<div class=\"review-by\">";
                echo "<p>By <span>$rev_username</span> on <span>$rev_date</span>.</p>";
                echo "</br>";
              echo "</div>";
  
              echo "<div class=\"review-help\">";
                echo "<a href=\"#\" data-revid=\"$rev_id\" class=\"toggle-report-review\">&#9872; Report</a>";
                echo "<a href=\"server/helpplus.php?id=$rev_id&ad=$id\">&#9745; Helpful</a>";
              echo "</div>";
            echo "</article>";
          } 
        } else {
          echo "<article class=\"review-container\">";
            echo "<p>There are no reviews for this advert.</p>";
          echo "</article>";
        }
      ?>
    </section>
  </div>
</div>
<?php
  if(isset($_SESSION['iduser'])){
    echo reportAdvert($id, $owner, $userid);
    echo reportReview($id, $owner, $userid);
  } else {
    echo signInPlease();
  }
?>
<script type="text/javascript">
  <?php
    $js_array = json_encode($arrayName);
    echo "var dr = ".$js_array.";\n";
  ?>
</script>
<?php
echo javaAdvert('advert.js');
echo pageClose();
?>