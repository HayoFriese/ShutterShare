<?php
  include 'server/db_conn.php';
  require_once('functions.php');

  //error_reporting(E_ALL);
  //ini_set('display_errors', 1);

  echo pageIni("Shuttershare");

  echo nav();

  $sql = "SELECT idadvert, title, keywords, cost FROM advert
  INNER JOIN shutuser ON advert.user = shutuser.iduser 
  WHERE advert.flagged = 0 
  AND advert.active = 1 
  AND shutuser.flagged = 0 
  AND shutuser.suspension = 0 
  AND shutuser.active = 1 
  ORDER BY date DESC LIMIT 5";
  $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  $sql2 = "SELECT idadvert, title, keywords, cost FROM advert 
  INNER JOIN shutuser ON advert.user = shutuser.iduser 
  WHERE advert.flagged = 0 
  AND advert.active = 1 
  AND shutuser.flagged = 0 
  AND shutuser.suspension = 0 
  AND shutuser.active = 1 
  ORDER BY views DESC LIMIT 5";
  $result2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));  
?>	
    <div id="site">
      <header class="header-home">
        <h1>Shutter<span>Share</span></h1>
        <h2>RENT A CAMERA, SAVE MONEY</h2>
      </header>

      <form id="homeSearch" action="searchResults.php" method="post">
        <input type="text" name="searchTerms" placeholder="Search for adverts by location, user, or keywords...">

        <input type="submit" id="searchSubmit" value="SEARCH">
      </form>
        <?php
          if(mysqli_num_rows($result) > 0){
            echo "<section class=\"cameraRentingOffers\">
              <h2>LATEST CAMERA RENTING OFFERS</h2>";

            while ($row = mysqli_fetch_assoc($result)) {
              $id = $row['idadvert'];
              $title = $row['title'];
              $keywords = $row['keywords'];
              $cost = $row['cost'];
              $cost = number_format($cost, 2, '.', '');

              $sqlmedia1 = "SELECT src, alt FROM shutmedia WHERE advert = $id LIMIT 1";
              $rmedia1 = mysqli_query($conn, $sqlmedia1) or die(mysqli_query($conn));
              $img = mysqli_fetch_assoc($rmedia1)['src'];
              $alt = mysqli_fetch_assoc($rmedia1)['alt'];

              echo "<article>";
              echo "<a href=\"viewAdvert.php?id=$id\" class=\"offer-background\">";
              echo "<p id=\"offerPrice\">£ $cost<p>/day</p></p>";
              echo "<img src=\"$img\" alt=\"$alt\">";
              echo "<p id=\"offerTitle\">$title</p>";
              echo "</a>";
              echo "</article>";
            }
            mysqli_free_result($result);
            echo "</section>";
          }

          if(mysqli_num_rows($result2) > 0){
            echo "<section class=\"cameraRentingOffers\">
              <h2>MOST VIEWED</h2>";

            while ($row = mysqli_fetch_assoc($result2)) {
              $id = $row['idadvert'];
              $title = $row['title'];
              $keywords = $row['keywords'];
              $cost = $row['cost'];
              $cost = number_format($cost, 2, '.', '');

              $sqlmedia1 = "SELECT src, alt FROM shutmedia WHERE advert = $id LIMIT 1";
              $rmedia1 = mysqli_query($conn, $sqlmedia1) or die(mysqli_query($conn));
              $img = mysqli_fetch_assoc($rmedia1)['src'];
              $alt = mysqli_fetch_assoc($rmedia1)['alt'];

              echo "<article>";
              echo "<a href=\"viewAdvert.php?id=$id\" class=\"offer-background\">";
              echo "<p id=\"offerPrice\">£ $cost<p>/day</p></p>";
              echo "<img src=\"$img\" alt=\"$alt\">";
              echo "<p id=\"offerTitle\">$title</p>";
              echo "</a>";
              echo "</article>";
            }
            mysqli_free_result($result2);
            echo "</section>";
          }
        ?>
<?php
  echo footer();
?>
    </div>
    <script type="text/javascript">
      var dr = [];
    </script>
<?php
  echo javaAdvert('advert.js');
  echo pageClose();
?>