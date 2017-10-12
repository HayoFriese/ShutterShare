<?php

include 'db_conn.php';
require_once('../functions_admin.php');
ini_set("session.save_path", "../../sessionData/sessionAdmin");
session_start();


if (isset($_POST['signIn'])) {
    $adminKey = filter_has_var(INPUT_POST, 'adminKey') ? $_POST['adminKey'] : null;
    $firstPassword = filter_has_var(INPUT_POST, 'firstPassword') ? $_POST['firstPassword'] : null;
    $secondPassword = filter_has_var(INPUT_POST, 'secondPassword') ? $_POST['secondPassword'] : null;

    $adminKey = trim($adminKey);
    $firstPassword = trim($firstPassword);
    $secondPassword = trim($secondPassword);

    $adminKey = filter_var($adminKey, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $firstPassword = filter_var($firstPassword, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $secondPassword = filter_var($secondPassword, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    $sql = "SELECT idadmin, password, secondpass, changekey, master, firstname, lastname FROM admin WHERE userkey = ?";

    $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "s", $adminKey) or die(mysqli_error($conn));
    mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    mysqli_stmt_bind_result($stmt, $aid, $firstPasswordHash, $secondPasswordHash, $changekey, $master, $fname, $lname) or die(mysqli_error($conn));

    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($firstPassword, $firstPasswordHash) && password_verify($secondPassword, $secondPasswordHash)){

            $_SESSION['logged-in'] = true;
            $_SESSION['adminKey'] = $aid;
            $_SESSION['name'] = $fname." ".$lname;
            $_SESSION['changekey'] = $changekey;

            if ($master == 1) {
                $_SESSION['master'] = 1;
            } else {
                $_SESSION['master'] = 0;
            }

            header("location: ../adverts.php");
        } else {
            $_SESSION['error'] = true;
            header("location: ../admin_login.php");
        }
    } else {
        $_SESSION['error'] = true;
        header("location: ../admin_login.php");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}