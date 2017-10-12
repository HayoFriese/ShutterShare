<?php
  include 'server/db_conn.php';
  require_once('functions.php');
  echo pageIni("Search Results - Shuttershare");

  echo nav();
?>
    <div id="site">
      <form id="homeSearch" action="searchResults.php" method="post">
        <input type="text" name="searchTerms" placeholder="Search for adverts by location, user, or keywords...">

        <input type="submit" id="searchSubmit" value="SEARCH">
      </form>

    <section class="cameraRentingOffers">
          <?php
            $keywordSearch = isset($_POST['searchTerms']) ? $_POST['searchTerms']:null;

            // Sanitise Input To Remove Tags
            $keywordSearch = filter_var($keywordSearch, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);


            // Santising Special Character Input / Strip Tags / Trim
            $keywordSearch = filter_var(strip_tags(trim($keywordSearch)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);

            $sql = "SELECT DISTINCT idadvert, title, keywords, cost 
                    FROM  advert
                    INNER JOIN shutuser ON advert.user = shutuser.iduser
                    INNER JOIN billingaddress ON shutuser.billingAd = billingaddress.idbillingaddress
                    WHERE advert.flagged = 0 
                    AND advert.active = 1 
                    AND shutuser.suspension = 0 
                    AND shutuser.active = 1
                    AND (keywords LIKE '%$keywordSearch%' 
                      OR title LIKE '%$keywordSearch%' 
                      OR adDesc LIKE '%$keywordSearch%' 
                      OR shutuser.username LIKE '%$keywordSearch%'
                      OR billingaddress.city LIKE '%$keywordSearch%' 
                      OR billingaddress.region LIKE '%$keywordSearch%'
                      OR billingaddress.country LIKE '%$keywordSearch%')";

            $result = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));

          $num = mysqli_num_rows($result);

          echo "<h2>$num RESULTS FOUND</h2>";

            while ($row = mysqli_fetch_assoc($result)) {
                $title = $row['title'];
                $keywords = $row['keywords'];
                $id = $row['idadvert'];
                $cost = $row['cost'];

                echo "<article>";
                  echo "<a href=\"viewAdvert.php?id=$id\" class=\"offer-background\">";
                    echo "<p id=\"offerPrice\">£$cost <p>/day</p></p>";

                    $sqlmed = "SELECT src FROM shutmedia WHERE advert='$id' LIMIT 1";
                    $resultmed = mysqli_query($conn, $sqlmed) or die(mysqli_error($conn));

                    While ($media = mysqli_fetch_array( $resultmed )) {
                      $src = $media['src'];

                      echo "<img src=\"$src\">";
                    }
                    mysqli_free_result($resultmed);


                    echo "<p id=\"offerTitle\">$title</p>";
                  echo "</a>";
                echo "</article>";
            }
        ?>
      </section>
      <script type="text/javascript">
        var dr = [];
      </script>
<?php
  echo javaAdvert('advert.js');
  echo footer();
  echo pageClose();
?>
