<?php
  include 'server/db_conn.php';
  require_once('functions.php');
  if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
    echo pageIni("Access Denied");
      echo error();
  } else {
    echo pageIni("Leave Review | Bookings - Shuttershare");

    echo nav2();
    echo nav3("", " class=\"active\"", "", "");

    $reviewSubject = filter_has_var(INPUT_POST, 'review-subject') ? $_POST ['review-subject']:null;
    $rating = filter_has_var(INPUT_POST, 'star') ? $_POST ['star']:null;
    $reviewComments = filter_has_var(INPUT_POST, 'comments') ? $_POST ['comments']:null;
    $adid = isset ($_POST ['advertid']) ? $_POST ['advertid']:null;
    $userid = isset ($_POST ['iduser']) ? $_POST ['iduser']:null;

    // Sanitise Input To Remove Tags
    $reviewSubject = filter_var($reviewSubject, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $reviewComments = filter_var($reviewComments, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    // Santising Special Character Input / Strip Tags / Trim
    $reviewSubject = filter_var(strip_tags(trim($reviewSubject)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
    $reviewComments = filter_var(strip_tags(trim($reviewComments)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);

    $date = date('Y-m-d');

    $errors = array();

    if (empty($reviewSubject)) {
    $errors[] = "You have not entered a Review Title";
    }
    elseif (strlen($reviewSubject) > 255) {
    $errors[] = "Review Titles have a maximum of 255 characters.";
    }
    if (empty($rating)) {
    $errors[] = "You have not selected a Camera Owner Rating";
    }
    if (empty($reviewComments)) {
    $errors[] = "You have not entered any Review Comment";
    }
    elseif (strlen($reviewComments) > 4294967295) {
    $errors[] = "Comments about your experience have a maximum of 4,294,967,295 characters.";
    }

    // Check if array has errors
   if (!empty($errors)) {
     // If errors, echo message
     echo "<div class=\"back-end\">
           <div class=\"back-end-center\">";

           echo breadcrumb("bookings.php", "My Bookings", "Leave a Review");

              echo "<div class=\"book-advert-back\">
                 <p>The following problem(s) have occured:</p>\n";
    for ($a=0; $a < count($errors); $a++) {
      echo "<p>$errors[$a]</p>\n";
    }
           echo "<a id=\"accountButtonLinkWide\" href=\"bookings.php\">BACK</a>
           </div>
         </div>
     </div>";
   }

   else {

    $helpful = 0;

    $sqlReview = "INSERT INTO reviews (title, datePost, body, rating, helpful, advert, user)
    VALUES(?, ?, ?, ?, ?, ?, ?)";

    $stmtReview = mysqli_prepare($conn, $sqlReview) or die(mysqli_error($conn));

    mysqli_stmt_bind_param($stmtReview, "sssssss", $reviewSubject, $date, $reviewComments, $rating, $helpful, $adid, $userid) or die(mysqli_error($conn));

    mysqli_stmt_execute($stmtReview) or die(mysqli_error($conn));

    echo "<div class=\"back-end\">
          <div class=\"back-end-center\">";

    echo breadcrumb("bookings.php", "My Bookings", "Leave a Review");

            echo "<div class=\"book-advert-back\">
                  <div class=\"tick\">
                    <img src=\"resources/img/tick.png\">
                    <p>Thanks for your Review!<p>
                  </div>
                <a id=\"accountButtonLinkWide\" href=\"bookings.php\">BACK</a>
              </div>
            </div>
        </div>";

      mysqli_close($conn);

    }
  }

    echo pageClose();

?>