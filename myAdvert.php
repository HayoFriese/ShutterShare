<?php
  include 'server/db_conn.php';
  require_once('functions.php');
  echo pageIni("My Adverts - Shuttershare");

  echo nav2();
  echo nav3("", "", " class=\"active\"", "");

    $uid = $_SESSION['iduser'];
?>
    <div class="back-end">
    <h1>my advert</h1>
        <div class="adscroll">
          <section id="myAdvertElements">
        <?php
          $sql = "SELECT idadvert, title, keywords, cost, user FROM  advert WHERE user = '$uid' AND active = 1 ORDER BY date";
          $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));


          while ($row = mysqli_fetch_assoc($result)) {
            $title = $row['title'];
            $keywords = $row['keywords'];
            $id=$row['idadvert'];
            $cost = $row['cost'];
            $cost = number_format($cost, 2, '.', '');

            echo "<article>";
              echo "<p id=\"advertPrice\">£$cost <p>/day</p></p>";


              $sqlmed = "SELECT src FROM shutmedia WHERE advert='$id' LIMIT 1";
              $resultmed = mysqli_query($conn, $sqlmed) or die(mysqli_error($conn));

              While ($media = mysqli_fetch_array( $resultmed )) {
                  $src = $media['src'];

                  echo "<div id=\"imageContainer\">";
                    echo "<img src=\"$src\">";
                  echo "</div>";
              }
              mysqli_free_result($resultmed);


              echo "<p id=\"advertTitle\"><a href=\"viewAdvert.php?id=$id\">$title</a></p>";
              echo "<a id=\"accountButtonMyAdvertLink\" href=\"adStats.php?id=$id\">View Stats</a>";
              echo "<a id=\"accountButtonMyAdvertLink\" href=\"editAdvert.php?id=$id\">EDIT ADVERT</a>";
              echo "<a id=\"accountButtonMyAdvertLink\" href=\"server/deleteAdvertCode.php?id=$id\">DELETE ADVERT</a>";
            echo "</article>";
          }

        ?>
          </section>
            </div>
          <?php

            $activeCheck = 0;

            $username = $_SESSION['username'];
            $sql2 = "SELECT  bookings.idbookings,  bookings.bookingDate,  bookings.costTotal, bookings.advert, advert.title, shutuser.username
                  FROM  bookings
                  INNER JOIN  advert ON  bookings.advert = advert.idadvert
                  INNER JOIN shutuser ON bookings.userid = shutuser.iduser
                  WHERE  bookings.active = $activeCheck AND ownerid = $uid
                  ORDER BY  bookings.bookingDate";
            
            $r2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
            if(mysqli_num_rows($r2) > 0){
              echo "<h1>Advert Requests</h1>";
              echo "<section class=\"book-list\">
                <div>";

                while($row2 = mysqli_fetch_assoc($r2)){
                  $bookid = $row2['idbookings'];
                  $bookDate = $row2['bookingDate'];
                  $advertid = $row2['advert'];
                  $cost = $row2['costTotal'];
                  $cost = number_format($cost, 2, '.', '');
                  $title = $row2['title'];
                  $pls = $row2['username'];

                  $sqlCov = "SELECT src, alt FROM shutmedia WHERE advert = $advertid LIMIT 1";
                  $rcov = mysqli_query($conn, $sqlCov) or die(mysqli_error($conn));
                  $cov = mysqli_fetch_assoc($rcov)['src'];
                  $alt = mysqli_fetch_assoc($rcov)['alt'];

                  $owname = $row['username'];

                  $sqlmedia1 = "SELECT src, alt FROM shutmedia WHERE advert = $advertid LIMIT 1";
                  $rmedia1 = mysqli_query($conn, $sqlmedia1) or die(mysqli_query($conn));
                  $img = mysqli_fetch_assoc($rmedia1)['src'];
                  $alt = mysqli_fetch_assoc($rmedia1)['alt'];

                  echo "<div class=\"book-advert-back\">
                          <p id=\"formInstructions\">Request Placed: $bookDate <span>Total: &pound;$cost</span><p>
                          <div class=\"book-data\">
                            <img src=\"$cov\" alt=\"$alt\">
                            <div>
                              <ul>
                                <li>$title</li>
                                <li>From: <span>$pls</span></li>
                                  <li><a id=\"accountButtonLink\" href=\"acceptRequest.php?bookingid=$bookid\">ACCEPT REQUEST</a></li>
                                  <li><a id=\"accountButtonLink\" href=\"declineRequest.php?bookingid=$bookid\">DECLINE REQUEST</a></li>
                              </ul>
                            </div>
                          </div>
                        </div>";
                }
                echo "</div>
              </section>";
              }
          ?>
        <?php
          $sql = "SELECT idadvert, title, keywords, cost, user FROM  advert WHERE user = '$uid' AND active = 0 ORDER BY date";
          $result3 = mysqli_query($conn, $sql) or die(mysqli_error($conn));

          if(mysqli_num_rows($result3) > 0){
            echo "<h1>Past Adverts</h1>
              <div class=\"adscroll\">
                <section id=\"myAdvertElements\">";
                while ($row = mysqli_fetch_assoc($result3)) {
                  $title = $row['title'];
                  $keywords = $row['keywords'];
                  $id=$row['idadvert'];
                  $cost = $row['cost'];
                  $cost = number_format($cost, 2, '.', '');

                  echo "<article>";
                    echo "<p id=\"advertPrice\">£$cost <p>/day</p></p>";


                    $sqlmed = "SELECT src FROM shutmedia WHERE advert='$id' LIMIT 1";
                    $resultmed = mysqli_query($conn, $sqlmed) or die(mysqli_error($conn));

                    while ($media = mysqli_fetch_array( $resultmed )) {
                      $src = $media['src'];

                      echo "<div id=\"imageContainer\">";
                      echo "<img src=\"$src\">";
                      echo "</div>";
                    }
                    mysqli_free_result($resultmed);


                    echo "<p id=\"advertTitle\"><a href=\"viewAdvert.php?id=$id\">$title</a></p>";
                    echo "<a id=\"accountButtonMyAdvertLink\" href=\"adStats.php?id=$id\">View Stats</a>";
                    echo "<a id=\"accountButtonMyAdvertLink\" href=\"editAdvert.php?id=$id\">EDIT ADVERT</a>";
                    echo "<a id=\"accountButtonMyAdvertLink\" href=\"server/reuploadAdvert.php?id=$id\">REUPLOAD ADVERT</a>";
                  echo "</article>";
                }
              echo "</section>
              </div>";
          }
        ?>
          
      </div>
    </div>
<?php
  echo pageClose();
?>