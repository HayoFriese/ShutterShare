<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_conn.php';
ini_set("session.save_path", "/home/unn_w13020720/sessionData");
session_start();


$title = filter_has_var(INPUT_POST, 'title') ? $_POST['title']: null;
$keywords = filter_has_var(INPUT_POST, 'keywords') ? $_POST['keywords'] :null;
$adDesc = filter_has_var(INPUT_POST, 'adDesc')  ? $_POST['adDesc'] :null;
$cost = filter_has_var(INPUT_POST, 'cost') ? $_POST['cost'] :null;

// Sanitise Input To Remove Tags
$title = filter_var($title, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$keywords = filter_var($keywords, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$adDesc = filter_var($adDesc, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$cost = filter_var($cost, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

// Santising Special Character Input / Strip Tags / Trim
$title = filter_var(strip_tags(trim($title)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
$keywords = filter_var(strip_tags(trim($keywords)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
$adDesc = filter_var(strip_tags(trim($adDesc)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
$cost = filter_var(strip_tags(trim($cost)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);

$id = $_POST['idedit'];
$uid = $_SESSION['iduser'];



$sql2 = "SELECT bookings.idbookings, availability.start, availability.end, advert.title, advert.cost, bookings.userid, bookings.ownerid FROM bookings 
INNER JOIN availability ON bookings.availabilityid = availability.idavailability 
INNER JOIN advert ON bookings.advert = advert.idadvert
WHERE bookings.advert = $id AND (bookings.active = 0 OR bookings.active = 1)";
$r2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
while($row2 = mysqli_fetch_assoc($r2)){
    $r_cost = $row2['cost'];
    $r_start = $row2['start'];
    $r_end = $row2['end'];
    $r_id = $row2['idbookings'];

    $r_userid = $row2['userid'];
    $r_ownerid = $row2['ownerid'];
    $r_title = $row2['title'];

    if($cost != $r_cost){
        $from = 0;
        $readMark = 0;
        $flag = 0;
        $sent = 1;
        $folder = 1;

        $messageSubject = "'".$r_title."' has changed in price.";
        $messageCont = "<div>'".$r_title."' has changed in price.</div>
          <div><br></div>
          <div>So, what does this mean?</div>
          <div><br></div>
          <div>It doesn't mean much for you if you are satisfied with your booking. Your booking still costs the same, and if you're still waiting for approval, there is no price change for you.</div>
          <div><br></div>
          <div>Should you wish to amend your booking, however, the price of your booking will change depending on the new price per day set by the owner.</div>
          <div><br></div>
          <div>Please proceed with the following information.</div>
          <br>
          <br>
          <div>Enjoy your camera!</div>
          <div><br></div>
          <div>ShutterShare Team</div>
          <div><br></div>
          <div>-- This is an automated message sent by the system to notify the user of a change in price of the advert they have booked --</div>";
          
        $sendDate = date("Y-m-d");
        $sendTime = date("H:i:s");

        $sqlNewMessage = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sqlNewMessage) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, "ssssssssss", $messageSubject, $messageCont, $sendDate, $sendTime, $readMark, $flag, $sent, $r_userid, $from, $folder) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
        mysqli_stmt_close($stmt) or die(mysqli_error($conn));


    }
}


$sql = "UPDATE advert SET title='$title', keywords='$keywords', adDesc='$adDesc', cost='$cost' WHERE idadvert = '$id'";
mysqli_query($conn, $sql) or die(mysqli_error($conn));

if(isset($_FILES['imageUpload'])){
    foreach($_FILES['imageUpload']['name'] as $f => $name){
        $movedir = ("../resources/img/adverts/".$id."/");
        $dir = ("resources/img/adverts/".$id."/");

        $allowedExts = array("gif", "GIF", "jpeg", "JPEG", "jpg", "JPG", "png", "PNG");
        $temp = explode(".", $name);
        $extension = end($temp);

        if ((($_FILES['imageUpload']['type'][$f] == "image/gif") || ($_FILES['imageUpload']['type'][$f] == "image/GIF")
                || ($_FILES['imageUpload']['type'][$f] == "image/jpeg") || ($_FILES['imageUpload']['type'][$f] == "image/JPEG")
                || ($_FILES['imageUpload']['type'][$f] == "image/jpg") || ($_FILES['imageUpload']['type'][$f] == "image/JPG")
                || ($_FILES['imageUpload']['type'][$f] == "image/png") || ($_FILES['imageUpload']['type'][$f] == "image/PNG"))
            && ($_FILES['imageUpload']['size'][$f] < 1073741824)
            && in_array($extension, $allowedExts)){
                echo "img type gucci ";
            if ($_FILES['imageUpload']['error'][$f] > 0){
                echo "Return Code: " .$_FILES['imageUpload']['error'][$f];
            } else{
                if(file_exists($movedir)){
                    echo "Directory exists";
                } else{
                    mkdir($movedir, 0777, true);
                }
                if(file_exists($movedir.$name)){
                    echo "File already exists";
                    $pathname = $dir.$name;
                } else{
                    $names = $_FILES['imageUpload']['tmp_name'][$f];
                    if (move_uploaded_file($names, "$movedir/$name")){
                        $pathname = ($dir.$name);

                        $sql="INSERT INTO shutmedia(src, alt, user, advert) VALUES (?, ?, ?, ?)";

                        $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));

                        mysqli_stmt_bind_param($stmt, 'ssdd', $pathname, $name, $uid, $id) or die(mysqli_error($conn));

                        mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

                        mysqli_stmt_close($stmt);

                        echo "files have been uploaded etc";
                    }
                }
            }
        }
    }
}

mysqli_close($conn);

header('Location: ../myAdvert.php') ;
exit;

?>
