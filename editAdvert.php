<?php
include 'server/db_conn.php';
require_once('functions.php');

$userId = $_SESSION['iduser'];

$sql1 = "SELECT paymentDet FROM shutuser WHERE iduser = '$userId'";
$result1 = mysqli_query($conn, $sql1) or die(mysqli_error($conn));

$payment = mysqli_fetch_array( $result1 );
$owner = $payment['paymentDet'];

$idedit = $_GET['id'];
$userId = $_SESSION['iduser'];

$sqlMed = "SELECT idmedia, src, alt FROM shutmedia WHERE advert = '$idedit'";
$rMed = mysqli_query($conn, $sqlMed) or die(mysqli_error($conn));

$sql = "SELECT idadvert, title, keywords, adDesc, cost FROM advert WHERE idadvert = $idedit AND user = $userId ";

$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

//Put results into an array

$advert = mysqli_fetch_array($result);
$idedit = $advert['idadvert'];
$title = $advert['title'];
$keywords = $advert['keywords'];
$adDesc = $advert['adDesc'];
$cost = $advert['cost'];

if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
    echo pageIni("Access Denied");
    echo error();
}elseif ($idedit == 0){
    header('Location: myAdvert.php');
} elseif ($owner == 0) {
    echo pageIni("Access Denied");
    echo nav2();
    echo nav3("", "", " class=\"active\"", "");

    echo "<div class=\"back-end\">";

    echo breadcrumb("myAdvert.php", "My Adverts", "Edit Advert");

    echo"<div class=\"book-advert-back\">";
    echo"<div class=\"book-data\">";
    echo"<div>";
    echo"<p>The following problem occurred:</p><br/>";
    echo"<p>You can't edit an advert until you enter 
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
    echo pageIni("edit Advert | My Adverts - Shuttershare");

    echo nav2();
    echo nav3("", "", " class=\"active\"", "");

      ?>
      <div class="back-end">
          <?php
          echo breadcrumb("myAdvert.php", "My Adverts", "Edit Advert");
          ?>
          <form id="advert-back-form" action="server/editAdvertCode.php" method="POST" enctype="multipart/form-data">

              <div class="half-form">
                  <input type="hidden" name="idedit" value="<?php echo $idedit; ?>">
                  <input type="hidden" name="ad_user" value="<?php echo $userId; ?>">
                  <input type="text" name="title" value="<?php echo $title; ?>">
                  <input type="text" name="keywords" value="<?php echo $keywords; ?>">
                  <textarea name="adDesc" rows="8"><?php echo $adDesc; ?></textarea>
                  <input type="submit" value="SUBMIT">
              </div>
              <div class="half-form">
                  <input type="text" name="cost" value="<?php echo $cost; ?>">
                  <input type="file" id="image-upload" name="imageUpload[]" value="UPLOAD IMAGES" multiple>
                  <div id="imagePreview">
                  </div>
                  <div id="imagePreviewCurrent">
                      <?php
                      while($row = mysqli_fetch_assoc($rMed)) {
                          $src = $row['src'];
                          $alt = $row['alt'];
                          $mediaId = $row['idmedia'];
                          echo "<div class='prevBlock'>";
                          echo "<img class=\"imageThumb\" src=\"$src\" alt=\"$alt\"/>";
                          echo "<p>$alt</p> <form action='server/imagedelete.php' method=\"post\" enctype=\"multipart/form-data\" style='float: left'>
                                <input type=\"hidden\" name=\"ad_media\" value=\"$mediaId\">
                                <input type=\"hidden\" name=\"ad_id\" value=\"$idedit\">
                                <button>Delete Image</button></form>";
                          echo "</div>";
                      }
                      ?>
                  </div>
              </div>
          </form>
      </div>

      <?php
  }
    echo javascript('advert.js');
    echo pageClose();
?>