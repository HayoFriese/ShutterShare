<?php

    include 'db_conn.php';
    ini_set("session.save_path", "/home/unn_w13020720/sessionData");
    session_start();

    $ad_name = isset($_POST['ad_name']) ? $_POST['ad_name']:null;
    $ad_keywords = isset($_POST['ad_keywords']) ? $_POST['ad_keywords']:null;
    $ad_desc = isset($_POST['ad_desc']) ? $_POST['ad_desc']:null;
    $ad_price = isset($_POST['ad_price']) ? $_POST['ad_price']:null;
    $ad_date = date("Y-m-d");
    $user = isset($_POST['ad_user']) ? $_POST['ad_user']: null;

    //Trim Values
    $ad_name = trim($ad_name);
    $ad_keywords = trim($ad_keywords);
    $ad_desc = trim($ad_desc);
    $ad_price = trim($ad_price);

    // Sanitise Input To Remove Tags
    $ad_name = filter_var($ad_name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $ad_keywords = filter_var($ad_keywords, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $ad_desc = filter_var($ad_desc, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $ad_price = filter_var($ad_price, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    // Santising Special Character Input / Strip Tags / Trim
    $ad_name = filter_var(strip_tags(trim($ad_name)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
    $ad_keywords = filter_var(strip_tags(trim($ad_keywords)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
    $ad_desc = filter_var(strip_tags(trim($ad_desc)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);
    $ad_price = filter_var(strip_tags(trim($ad_price)), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_URL);

    $sql= "INSERT INTO  advert (title, adDesc, cost, date, keywords, user)VALUES(?, ?, ?, ?, ?, ?)";

    $advertstmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));

    mysqli_stmt_bind_param($advertstmt, 'ssssss', $ad_name, $ad_desc, $ad_price, $ad_date, $ad_keywords, $user) or die(mysqli_error($conn));

    mysqli_stmt_execute($advertstmt) or die(mysqli_error($conn));

    mysqli_stmt_close($advertstmt);

    $advertid = mysqli_insert_id($conn);

    if(isset($_FILES['imageUpload'])){
        foreach($_FILES['imageUpload']['name'] as $f => $name){
            $movedir = ("../resources/img/adverts/".$advertid."/");
            $dir = ("resources/img/adverts/".$advertid."/");

            $allowedExts = array("gif", "GIF", "jpeg", "JPEG", "jpg", "JPG", "png", "PNG");
            $temp = explode(".", $name);
            $extension = end($temp);

            if ((($_FILES['imageUpload']['type'][$f] == "image/gif") || ($_FILES['imageUpload']['type'][$f] == "image/GIF")
                    || ($_FILES['imageUpload']['type'][$f] == "image/jpeg") || ($_FILES['imageUpload']['type'][$f] == "image/JPEG")
                    || ($_FILES['imageUpload']['type'][$f] == "image/jpg") || ($_FILES['imageUpload']['type'][$f] == "image/JPG")
                    || ($_FILES['imageUpload']['type'][$f] == "image/png") || ($_FILES['imageUpload']['type'][$f] == "image/PNG"))
                && ($_FILES['imageUpload']['size'][$f] < 1073741824)
                && in_array($extension, $allowedExts)){
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

                            mysqli_stmt_bind_param($stmt, 'ssdd', $pathname, $name, $user, $advertid) or die(mysqli_error($conn));

                            mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

                            mysqli_stmt_close($stmt);

                            echo "files have been uploaded etc";
                        }
                    }
                }
            }
        }
    }

header('Location: ../myAdvert.php?id='. $user) ;
    exit;
