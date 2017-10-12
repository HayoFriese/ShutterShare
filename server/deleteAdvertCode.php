<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_conn.php';
ini_set("session.save_path", "/home/unn_w13020720/sessionData");
session_start();


$id = $_GET['id'];
$one = 0;
$four = 4;
$today = date('Y-m-d');

    $sql= "UPDATE advert SET active = ? WHERE idadvert = ? ";
    $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "dd", $one, $id) or die(mysqli_error($conn));
    mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    mysqli_stmt_close($stmt);

    $sql2= "SELECT * FROM bookings WHERE advert = $id AND active != 4";
    $r = mysqli_query($conn, $sql2) or die(mysqli_error($conn));

    while($row = mysqli_fetch_assoc($r)){
        $idbookings = $row['idbookings'];
        $bookinguser = $row['userid'];
        $costTotal = $row['costTotal'];
        $bookingowner = $row['ownerid'];

        $sql3= "UPDATE bookings INNER JOIN availability ON bookings.availabilityid = availability.idavailability SET active = ? 
                WHERE bookings.advert = ? AND availability.start > ?";
        $stmt3 = mysqli_prepare($conn, $sql3) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmt3, "dds", $four, $id, $today) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmt3) or die(mysqli_error($conn));
        mysqli_stmt_close($stmt3);

        $sql4 = "UPDATE paymentdetails INNER JOIN bookings ON paymentdetails.user = bookings.userid 
        INNER JOIN availability ON bookings.advert = availability.advert SET wallet = wallet + ?
        WHERE paymentdetails.user = ? AND availability.start > ?";
        $stmt4 = mysqli_prepare($conn, $sql4) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmt4, "dds", $costTotal, $bookinguser, $today) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmt4) or die(mysqli_error($conn));
        mysqli_stmt_close($stmt4);

        $sql5 = "UPDATE paymentdetails INNER JOIN bookings ON paymentdetails.user = bookings.userid 
        INNER JOIN availability ON bookings.advert = availability.advert SET wallet = wallet - ?
        WHERE paymentdetails.user = ? AND availability.start > ?";
        $stmt5 = mysqli_prepare($conn, $sql5) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmt5, "dds", $costTotal, $bookingowner, $today) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmt5) or die(mysqli_error($conn));
        mysqli_stmt_close($stmt5);
    }



header('Location: ../myAdvert.php');
exit;