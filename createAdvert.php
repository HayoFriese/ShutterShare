<?php
include 'server/db_conn.php';
require_once('functions.php');

$userId = $_SESSION['iduser'];

$sql = "SELECT paymentDet FROM shutuser WHERE iduser = '$userId'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$payment = mysqli_fetch_array( $result );
$owner = $payment['paymentDet'];

    if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
        echo pageIni("Access Denied");
        echo error();
    } elseif ($owner == 0) {
        echo pageIni("Access Denied");
        echo nav2();
        echo nav3("", "", " class=\"active\"", "");

        echo "<div class=\"back-end\">";

        echo breadcrumb("myAdvert.php", "My Adverts", "Create Advert");

        echo"<div class=\"book-advert-back\">";
          echo"<div class=\"book-data\">";
            echo"<div>";
        echo"<p>The following problem occurred:</p><br/>";
              echo"<p>You can't create an advert until you enter 
                    </br>payment details in the 'My Account' page.
                    </br> This allows users to send you payments
                    </br> upon booking.</p><br/>";

        echo "<form action='myaccount.php'>";
        echo "<button>Enter payment details</button>";
        echo "</form>";
          echo"</div>";
        echo"</div>";
      echo"</div>";


    }else{
        echo pageIni("Create Advert | My Adverts - Shuttershare");

        echo nav2();
        echo nav3("", "", " class=\"active\"", "");


        ?>
        <div class="back-end">
<?php
  echo breadcrumb("myAdvert.php", "My Adverts", "Create Advert");
?>
      <form id="advert-back-form" action="server/createAdvertCode.php" method="post" enctype="multipart/form-data">
          <?php echo "<input type=\"hidden\" name=\"ad_user\" value=\"$userId\">"; ?>
          <div class="half-form">
              <input type="text" name="ad_name" placeholder="Title">
              <input type="text" name="ad_keywords" placeholder="Keywords">
              <textarea name="ad_desc" placeholder="Write your advert description here" rows="8"></textarea>

              <input type="submit" value="SUBMIT">




          </div>
          <div class="half-form">
              <span>&pound;<input type="number" name="ad_price" placeholder="Price" step="0.01" min="0"></span>
              <input type="file" id="image-upload" name="imageUpload[]" required value="UPLOAD IMAGES" multiple>
              <div id="imagePreview">
              </div>
          </div>
      </form>
    </div>
<?php
    echo javascript('advert.js');
  }
    echo pageClose();
?>