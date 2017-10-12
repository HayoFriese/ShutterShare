<?php

include 'db_conn.php';
ini_set("session.save_path", "/home/unn_w13020720/sessionData/sessionAdmin");
session_start();

if (isset($_POST['issueReport'])) {
    $type = filter_has_var(INPUT_POST, 'type') ? $_POST['type'] : null;
    $category = filter_has_var(INPUT_POST, 'category') ? $_POST['category'] : null;
    $message = filter_has_var(INPUT_POST, 'message') ? $_POST['message'] : null;
    $message = trim($message);
    $message = filter_var($message, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $reporter = filter_has_var(INPUT_POST, 'reporter') ? $_POST['reporter'] : null;
    $user = filter_has_var(INPUT_POST, 'user') ? $_POST['user'] : null;
    $idadvert = filter_has_var(INPUT_POST, 'idadvert') ? $_POST['idadvert'] : null;
    $date = date("Y-m-d H:i:s");
    $sqlUpdateUser = "UPDATE shutuser SET flagged=1 WHERE iduser=$user";
    $resultUpdateUser = mysqli_query($conn, $sqlUpdateUser) or die(mysqli_error($conn));

    if ($type == "Advert") {
        $sqlUpdateAdvert = "UPDATE advert SET flagged=1 WHERE idadvert=$idadvert";
        $resultUpdateAdvert = mysqli_query($conn, $sqlUpdateAdvert) or die(mysqli_error($conn));
    }

    $sqlIssueReport = "INSERT INTO reports (type, category, message, reporter, user, advert, date) VALUES (?,?,?,?,?,?,?)";
    $stmtIssueReport = mysqli_prepare($conn, $sqlIssueReport) or die(mysqli_error($conn));
    mysqli_stmt_bind_param($stmtIssueReport, "sssssss", $type, $category, $message, $reporter, $user, $idadvert, $date) or die(mysqli_error($conn));
    mysqli_stmt_execute($stmtIssueReport) or die(mysqli_error($conn));
    mysqli_stmt_close($stmtIssueReport);
    $issueReport = mysqli_insert_id($conn);
    header("location: ../viewAdvert.php?id=$idadvert");
}

if (isset($_POST['issueReportReview'])) {
    $type = filter_has_var(INPUT_POST, 'type') ? $_POST['type'] : null;
    $category = filter_has_var(INPUT_POST, 'category') ? $_POST['category'] : null;
    $message = filter_has_var(INPUT_POST, 'message') ? $_POST['message'] : null;
    $message = trim($message);
    $message = filter_var($message, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $reporter = filter_has_var(INPUT_POST, 'reporter') ? $_POST['reporter'] : null;
    $user = filter_has_var(INPUT_POST, 'user') ? $_POST['user'] : null;
    $idadvert = filter_has_var(INPUT_POST, 'idadvert') ? $_POST['idadvert'] : null;
    $idreview = filter_has_var(INPUT_POST, 'idreview') ? $_POST['idreview'] : null;
    $date = date("Y-m-d H:i:s");
    $sqlIssueReportReview = "INSERT INTO reports (type, category, message, reporter, user, advert, review, date) VALUES (?,?,?,?,?,?,?,?)";
    $stmtIssueReportReview = mysqli_prepare($conn, $sqlIssueReportReview) or die(mysqli_error($conn));
    mysqli_stmt_bind_param($stmtIssueReportReview, "ssssssds", $type, $category, $message, $reporter, $user, $idadvert, $idreview, $date) or die(mysqli_error($conn));
    mysqli_stmt_execute($stmtIssueReportReview) or die(mysqli_error($conn));
    mysqli_stmt_close($stmtIssueReportReview);
    $issueReportReview = mysqli_insert_id($conn);
    header("location: ../viewAdvert.php?id=$idadvert");
}

$changekey = filter_has_var(INPUT_POST, 'changekey') ? $_POST['changekey'] : null;
$changekey = trim($changekey);
$changekey = filter_var($changekey, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$sessionChangeKey = $_SESSION['changekey'];

$iduser = filter_has_var(INPUT_POST, 'iduser') ? $_POST['iduser'] : null;
$name = filter_has_var(INPUT_POST, 'name') ? $_POST['name'] : null;

$credentials = false;

if ($changekey === $sessionChangeKey) {
    $credentials = true;
}

if (isset($_POST['createAdmin'])) {

    if ($credentials == true) {

        $firstname = filter_has_var(INPUT_POST, 'firstname') ? $_POST['firstname'] : null;
        $firstname = trim($firstname);
        $firstname = filter_var($firstname, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        $lastname = filter_has_var(INPUT_POST, 'lastname') ? $_POST['lastname'] : null;
        $lastname = trim($lastname);
        $lastname = filter_var($lastname, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        $password = filter_has_var(INPUT_POST, 'password') ? $_POST['password'] : null;
        $password = trim($password);
        $password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        $passwordConfirm = filter_has_var(INPUT_POST, 'passwordConfirm') ? $_POST['passwordConfirm'] : null;
        $passwordConfirm = trim($passwordConfirm);
        $passwordConfirm = filter_var($passwordConfirm, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        $secondpass = filter_has_var(INPUT_POST, 'secondpass') ? $_POST['secondpass'] : null;
        $secondpass = trim($secondpass);
        $secondpass = filter_var($secondpass, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        $secondpassConfirm = filter_has_var(INPUT_POST, 'secondpassConfirm') ? $_POST['secondpassConfirm'] : null;
        $secondpassConfirm = trim($secondpassConfirm);
        $secondpassConfirm = filter_var($secondpassConfirm, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        $password = password_hash($password, PASSWORD_DEFAULT);
        $secondpass = password_hash($secondpass, PASSWORD_DEFAULT);

        $master = 0;
        $newUserkey = mt_rand(10000000, 99999999);
        $newChangekey = mt_rand(1000, 9999);

        $email = "$firstname.$lastname@shuttershare.com";
        $created = date("Y-m-d H:i:s");

        $sqlCreateAdmin = "INSERT INTO admin (userkey, password, secondpass, changekey, master, firstname, lastname, email, created) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmtCreateAdmin = mysqli_prepare($conn, $sqlCreateAdmin) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtCreateAdmin, "sssssssss", $newUserkey, $password, $secondpass, $newChangekey, $master, $firstname, $lastname, $email, $created) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtCreateAdmin) or die(mysqli_error($conn));
        mysqli_stmt_close($stmtCreateAdmin);
        $createAdmin = mysqli_insert_id($conn);

    }

    header("location: ../admins.php");

} else if (isset($_POST['deleteAdmin'])) {

    if ($credentials == true) {

        $admin = filter_has_var(INPUT_POST, 'admin') ? $_POST['admin'] : null;

        $sqlDeleteAdmin = "DELETE FROM admin WHERE idadmin=?";
        $stmtDeleteAdmin = mysqli_prepare($conn, $sqlDeleteAdmin) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtDeleteAdmin, "s", $admin) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtDeleteAdmin) or die(mysqli_error($conn));
        mysqli_stmt_close($stmtDeleteAdmin);
        $deleteAdmin = mysqli_insert_id($conn);

    }

    header("location: ../admins.php");

} else if (isset($_POST['suspendUser'])) {

    if ($credentials == true) {

        $suspended = 1;
        $suspendReason = filter_has_var(INPUT_POST, 'suspendReason') ? $_POST['suspendReason'] : null;
        $suspendDuration = filter_has_var(INPUT_POST, 'suspendDuration') ? $_POST['suspendDuration'] : null;
        $suspendDate = date("Y-m-d H:i:s");
        $suspendEnd = "";
        $adminName = $_SESSION['name'];

        switch($suspendDuration)
        {
            case '1 day': $suspendEnd = date('Y-m-d H:i:s', strtotime("+1 day")) ; break;
            case '3 days': $suspendEnd = date('Y-m-d H:i:s', strtotime("+3 days")) ; break;
            case '1 week': $suspendEnd = date('Y-m-d H:i:s', strtotime("+1 week")) ; break;
            case '2 weeks': $suspendEnd = date('Y-m-d H:i:s', strtotime("+2 weeks")) ; break;
            case '1 month': $suspendEnd = date('Y-m-d H:i:s', strtotime("+1 month")) ; break;
            case '3 months': $suspendEnd = date('Y-m-d H:i:s', strtotime("+3 months")) ; break;
        }

        $sqlSuspendUser = "UPDATE shutuser SET suspension=?, suspensions=suspensions+? WHERE iduser=?";

        $stmtSuspendUser = mysqli_prepare($conn, $sqlSuspendUser) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtSuspendUser, "iss", $suspended, $suspended, $iduser) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtSuspendUser) or die(mysqli_error($conn));
        mysqli_stmt_close($stmtSuspendUser);
        $suspendUser = mysqli_insert_id($conn);

        $sqlSuspendUser2 = "INSERT INTO suspensions (suspendReason, suspendDuration, suspendDate, suspendEnd, suspendAdmin, suspendUser, suspendActive) VALUES (?,?,?,?,?,?,?)";
        $stmtSuspendUser2 = mysqli_prepare($conn, $sqlSuspendUser2) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtSuspendUser2, "ssssssi", $suspendReason, $suspendDuration, $suspendDate, $suspendEnd, $adminName, $iduser, $suspended) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtSuspendUser2) or die(mysqli_error($conn));
        mysqli_stmt_close($stmtSuspendUser2);
        $suspendUser2 = mysqli_insert_id($conn);

    }

    header("location: ../users.php?filter=iduser&iduser=$iduser&name=$name&suspension=$suspended");

} else if (isset($_POST['unsuspendUser'])) {

    if ($credentials == true) {

        $suspended = 0;

        $sqlUnsuspendUser = "UPDATE shutuser u, suspensions s SET u.suspension=?, s.suspendActive=? WHERE u.iduser=? AND s.suspendUser=?";

        $stmtUnsuspendUser = mysqli_prepare($conn, $sqlUnsuspendUser) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtUnsuspendUser, "iiii", $suspended, $suspended, $iduser, $iduser) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtUnsuspendUser) or die(mysqli_error($conn));
        mysqli_stmt_close($stmtUnsuspendUser);
        $unsuspendUser = mysqli_insert_id($conn);

    }

    header("location: ../users.php?filter=iduser&iduser=$iduser&name=$name&suspension=$suspended");

} else if (isset($_POST['deactivateUser'])) {

    if ($credentials == true) {

        $suspended = filter_has_var(INPUT_POST, 'suspended') ? $_POST['suspended'] : null;

        $active = 0;

        if ($suspended == 1) {
            $sqlDeleteUser = "UPDATE shutuser u, suspensions s SET u.active=?, u.suspension=?, s.suspendActive=? WHERE u.iduser=? AND s.suspendUser=?";
            $stmtDeleteUser = mysqli_prepare($conn, $sqlDeleteUser) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmtDeleteUser, "iiiii", $active, $active, $active, $iduser, $iduser) or die(mysqli_error($conn));
        } else {
            $sqlDeleteUser = "UPDATE shutuser SET active=? WHERE iduser=?";
            $stmtDeleteUser = mysqli_prepare($conn, $sqlDeleteUser) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmtDeleteUser, "ss", $active, $iduser) or die(mysqli_error($conn));
        }

        mysqli_stmt_execute($stmtDeleteUser) or die(mysqli_error($conn));
        mysqli_stmt_close($stmtDeleteUser);
        $deleteUser = mysqli_insert_id($conn);

    }
    header("location: ../users.php?filter=iduser&iduser=$iduser&name=$name");

} else if (isset($_POST['reactivateUser'])) {

    if ($credentials == true) {

        $active = 1;

        $sqlReactivateUser = "UPDATE shutuser SET active=? WHERE iduser=?";

        $stmtReactivateUser = mysqli_prepare($conn, $sqlReactivateUser) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtReactivateUser, "ss", $active, $iduser) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtReactivateUser) or die(mysqli_error($conn));
        mysqli_stmt_close($stmtReactivateUser);
        $reactivateUser = mysqli_insert_id($conn);

    }
    header("location: ../users.php?filter=iduser&iduser=$iduser&name=$name");

} else if (isset($_POST['deleteAdvert'])) {

    $idadvert = filter_has_var(INPUT_POST, 'idadvert') ? $_POST['idadvert'] : null;

    if ($credentials == true) {

        $active = 0;

        $sqlDeleteAdvert = "UPDATE advert SET active=? WHERE idadvert=?";
        $stmtDeleteAdvert = mysqli_prepare($conn, $sqlDeleteAdvert) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtDeleteAdvert, "ss", $active, $idadvert) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtDeleteAdvert) or die(mysqli_error($conn));
        mysqli_stmt_close($stmtDeleteAdvert);
        $deleteAdvert = mysqli_insert_id($conn);

        header("location: ../adverts.php?filter=idadvert&idadvert=$idadvert");

    } else {
        header("location: ../adverts.php?filter=idadvert&idadvert=$idadvert&error");
    }


} else if (isset($_POST['restoreAdvert'])) {

    $idadvert = filter_has_var(INPUT_POST, 'idadvert') ? $_POST['idadvert'] : null;

    if ($credentials == true) {

        $active = 1;

        $sqlDeleteAdvert = "UPDATE advert SET active=? WHERE idadvert=?";
        $stmtDeleteAdvert = mysqli_prepare($conn, $sqlDeleteAdvert) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtDeleteAdvert, "ss", $active, $idadvert) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtDeleteAdvert) or die(mysqli_error($conn));
        mysqli_stmt_close($stmtDeleteAdvert);
        $restoreAdvert = mysqli_insert_id($conn);

    }

    header("location: ../adverts.php?filter=idadvert&idadvert=$idadvert");

} else if (isset($_POST['resolveReportDismiss'])) {

    $idreports = filter_has_var(INPUT_POST, 'idreports') ? $_POST['idreports'] : null;

    if ($credentials == true) {

        $fixed = 1;
        $dateFixed = date("Y-m-d H:i:s");
        $adminName = $_SESSION['name'];

        // update the report

        $sqlResolveReport = "UPDATE reports SET fixed=?, dateFixed=?, fixedAdmin=? WHERE idreports=?";
        $stmtResolveReport = mysqli_prepare($conn, $sqlResolveReport) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtResolveReport, "ssss", $fixed, $dateFixed, $adminName, $idreports) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtResolveReport) or die(mysqli_error($conn));

        // check if there's any more reports against the user

        $sqlGetUserReports = "SELECT * FROM reports WHERE user=$iduser AND fixed=0";
        $resultGetUserReports = mysqli_query($conn, $sqlGetUserReports) or die(mysqli_error($conn));

        if (mysqli_num_rows($resultGetUserReports) === 0) {
            $sqlUpdateUser = "UPDATE shutuser SET flagged=0 WHERE iduser=$iduser";
            $resultUpdateUser = mysqli_query($conn, $sqlUpdateUser) or die(mysqli_error($conn));
        }

        // check if there's any more reports against the advert

        $sqlGetAdvert = "SELECT type, advert FROM reports WHERE idreports=$idreports";
        $resultGetAdvert = mysqli_query($conn, $sqlGetAdvert) or die(mysqli_error($conn));

        while ($row = mysqli_fetch_assoc($resultGetAdvert)) {

            $type = $row['type'];
            $idadvert = $row['advert'];

            if ($type == "Advert") {

                $sqlGetAdvertReports = "SELECT * FROM reports WHERE advert=$idadvert AND fixed=0";
                $resultGetAdvertReports = mysqli_query($conn, $sqlGetAdvertReports) or die(mysqli_error($conn));

                if (mysqli_num_rows($resultGetAdvertReports) === 0) {
                    $sqlUpdateAdvert = "UPDATE advert SET flagged=0 WHERE idadvert=$idadvert";
                    $resultUpdateAdvert = mysqli_query($conn, $sqlUpdateAdvert) or die(mysqli_error($conn));
                }

            }

        }

        mysqli_stmt_close($stmtResolveReport);
        $resolveReport = mysqli_insert_id($conn);

    }

    header("location: ../reports.php?filter=idreports&idreports=$idreports");

} else if (isset($_POST['resolveReportDeleteAdvert'])) {

    $idreports = filter_has_var(INPUT_POST, 'idreports') ? $_POST['idreports'] : null;
    $idadvert = filter_has_var(INPUT_POST, 'idadvert') ? $_POST['idadvert'] : null;

    if ($credentials == true) {

        $fixed = 1;
        $dateFixed = date("Y-m-d H:i:s");
        $adminName = $_SESSION['name'];

        // update the report

        $sqlResolveReport = "UPDATE reports SET fixed=?, dateFixed=?, fixedAdmin=? WHERE idreports=?";
        $stmtResolveReport = mysqli_prepare($conn, $sqlResolveReport) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtResolveReport, "ssss", $fixed, $dateFixed, $adminName, $idreports) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtResolveReport) or die(mysqli_error($conn));

        // update the advert

        $sqlUpdateAdvert = "UPDATE advert SET active=0 WHERE idadvert=$idadvert";
        $resultUpdateAdvert = mysqli_query($conn, $sqlUpdateAdvert) or die(mysqli_error($conn));

        // check if there's any more reports against the user

        $sqlGetUserReports = "SELECT * FROM reports WHERE user=$iduser AND fixed=0";
        $resultGetUserReports = mysqli_query($conn, $sqlGetUserReports) or die(mysqli_error($conn));

        if (mysqli_num_rows($resultGetUserReports) === 0) {
            $sqlUpdateUser = "UPDATE shutuser SET flagged=0 WHERE iduser=$iduser";
            $resultUpdateUser = mysqli_query($conn, $sqlUpdateUser) or die(mysqli_error($conn));
        }

        // check if there's any more reports against the advert

        $sqlGetAdvert = "SELECT type, advert FROM reports WHERE idreports=$idreports";
        $resultGetAdvert = mysqli_query($conn, $sqlGetAdvert) or die(mysqli_error($conn));

        while ($row = mysqli_fetch_assoc($resultGetAdvert)) {

            $type = $row['type'];
            $idadvert = $row['advert'];

            if ($type == "Advert") {

                $sqlGetAdvertReports = "SELECT * FROM reports WHERE advert=$idadvert AND fixed=0";
                $resultGetAdvertReports = mysqli_query($conn, $sqlGetAdvertReports) or die(mysqli_error($conn));

                if (mysqli_num_rows($resultGetAdvertReports) === 0) {
                    $sqlUpdateAdvert = "UPDATE advert SET flagged=0 WHERE idadvert=$idadvert";
                    $resultUpdateAdvert = mysqli_query($conn, $sqlUpdateAdvert) or die(mysqli_error($conn));
                }

            }

        }

        mysqli_stmt_close($stmtResolveReport);
        $resolveReport = mysqli_insert_id($conn);

    }

    header("location: ../reports.php?filter=idreports&idreports=$idreports");

} else if (isset($_POST['resolveReportDeleteReview'])) {

    $idreports = filter_has_var(INPUT_POST, 'idreports') ? $_POST['idreports'] : null;
    $idreview = filter_has_var(INPUT_POST, 'idreview') ? $_POST['idreview'] : null;

    if ($credentials == true) {

        $fixed = 1;
        $dateFixed = date("Y-m-d H:i:s");
        $adminName = $_SESSION['name'];

        // update the report

        $sqlResolveReport = "UPDATE reports SET fixed=?, dateFixed=?, fixedAdmin=? WHERE idreports=?";
        $stmtResolveReport = mysqli_prepare($conn, $sqlResolveReport) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtResolveReport, "ssss", $fixed, $dateFixed, $adminName, $idreports) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtResolveReport) or die(mysqli_error($conn));

        // update the review

        $sqlUpdateAdvert = "UPDATE reviews SET active=0 WHERE idreviews=$idreview";
        $resultUpdateAdvert = mysqli_query($conn, $sqlUpdateAdvert) or die(mysqli_error($conn));

        // check if there's any more reports against the user

        $sqlGetUserReports = "SELECT * FROM reports WHERE user=$iduser AND fixed=0";
        $resultGetUserReports = mysqli_query($conn, $sqlGetUserReports) or die(mysqli_error($conn));

        if (mysqli_num_rows($resultGetUserReports) === 0) {
            $sqlUpdateUser = "UPDATE shutuser SET flagged=0 WHERE iduser=$iduser";
            $resultUpdateUser = mysqli_query($conn, $sqlUpdateUser) or die(mysqli_error($conn));
        }

        // check if there's any more reports against the advert

        $sqlGetAdvert = "SELECT type, advert FROM reports WHERE idreports=$idreports";
        $resultGetAdvert = mysqli_query($conn, $sqlGetAdvert) or die(mysqli_error($conn));

        while ($row = mysqli_fetch_assoc($resultGetAdvert)) {

            $type = $row['type'];
            $idadvert = $row['advert'];

            if ($type == "Advert") {

                $sqlGetAdvertReports = "SELECT * FROM reports WHERE advert=$idadvert AND fixed=0";
                $resultGetAdvertReports = mysqli_query($conn, $sqlGetAdvertReports) or die(mysqli_error($conn));

                if (mysqli_num_rows($resultGetAdvertReports) === 0) {
                    $sqlUpdateAdvert = "UPDATE advert SET flagged=0 WHERE idadvert=$idadvert";
                    $resultUpdateAdvert = mysqli_query($conn, $sqlUpdateAdvert) or die(mysqli_error($conn));
                }

            }

        }

        mysqli_stmt_close($stmtResolveReport);
        $resolveReport = mysqli_insert_id($conn);

    }

    header("location: ../reports.php?filter=idreports&idreports=$idreports");

} else if (isset($_POST['resolveReportSuspendUser'])) {

    $idreports = filter_has_var(INPUT_POST, 'idreports') ? $_POST['idreports'] : null;
    $iduser = filter_has_var(INPUT_POST, 'iduser') ? $_POST['iduser'] : null;
    $type = filter_has_var(INPUT_POST, 'type') ? $_POST['type'] : null;

    if ($credentials == true) {

        $fixed = 1;
        $inactive = 0;
        $dateFixed = date("Y-m-d H:i:s");
        $adminName = $_SESSION['name'];

        $suspendReason = filter_has_var(INPUT_POST, 'suspendReason') ? $_POST['suspendReason'] : null;
        $suspendDuration = filter_has_var(INPUT_POST, 'suspendDuration') ? $_POST['suspendDuration'] : null;
        $suspendEnd = "";

        switch($suspendDuration)
        {
            case '1 day': $suspendEnd = date('Y-m-d H:i:s', strtotime("+1 day")) ; break;
            case '3 days': $suspendEnd = date('Y-m-d H:i:s', strtotime("+3 days")) ; break;
            case '1 week': $suspendEnd = date('Y-m-d H:i:s', strtotime("+1 week")) ; break;
            case '2 weeks': $suspendEnd = date('Y-m-d H:i:s', strtotime("+2 weeks")) ; break;
            case '1 month': $suspendEnd = date('Y-m-d H:i:s', strtotime("+1 month")) ; break;
            case '3 months': $suspendEnd = date('Y-m-d H:i:s', strtotime("+3 months")) ; break;
        }

        // update report, suspend user, delete advert/review

        if ($type == "User") {
            $sqlresolveReportSuspendUser = "UPDATE reports r, shutuser u SET r.fixed=?, r.dateFixed=?, r.fixedAdmin=?, u.suspensions=suspensions+?, u.suspension=? WHERE r.idreports=? AND u.iduser=?";
            $stmtResolveReportSuspendUser = mysqli_prepare($conn, $sqlresolveReportSuspendUser) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmtResolveReportSuspendUser, "sssssss", $fixed, $dateFixed, $adminName, $fixed, $fixed, $idreports, $iduser) or die(mysqli_error($conn));
        } else if ($type == "Advert") {
            $idadvert = filter_has_var(INPUT_POST, 'idadvert') ? $_POST['idadvert'] : null;
            $sqlresolveReportSuspendUser = "UPDATE reports r, shutuser u, advert a SET r.fixed=?, r.dateFixed=?, r.fixedAdmin=?, u.suspensions=suspensions+?, u.suspension=?, a.active=? WHERE r.idreports=? AND u.iduser=? AND a.user=?";
            $stmtResolveReportSuspendUser = mysqli_prepare($conn, $sqlresolveReportSuspendUser) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmtResolveReportSuspendUser, "sssssssss", $fixed, $dateFixed, $adminName, $fixed, $fixed, $inactive, $idreports, $iduser, $iduser) or die(mysqli_error($conn));
        } else if ($type == "Review") {
            $idreview = filter_has_var(INPUT_POST, 'idreview') ? $_POST['idreview'] : null;
            $sqlresolveReportSuspendUser = "UPDATE reports r, shutuser u, reviews v SET r.fixed=?, r.dateFixed=?, r.fixedAdmin=?, u.suspensions=suspensions+?, u.suspension=?, v.active=? WHERE r.idreports=? AND u.iduser=? AND v.idreviews=?";
            $stmtResolveReportSuspendUser = mysqli_prepare($conn, $sqlresolveReportSuspendUser) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmtResolveReportSuspendUser, "sssssssss", $fixed, $dateFixed, $adminName, $fixed, $fixed, $inactive, $idreports, $iduser, $idreview) or die(mysqli_error($conn));
        }

        mysqli_stmt_execute($stmtResolveReportSuspendUser) or die(mysqli_error($conn));

        // insert suspension into suspensions table

        $sqlUpdateSuspensions = "INSERT INTO suspensions (suspendUser, suspendDate, suspendEnd, suspendReason, suspendDuration, suspendAdmin) VALUES (?,?,?,?,?,?)";
        $stmtUpdateSuspensions = mysqli_prepare($conn, $sqlUpdateSuspensions) or die(mysqli_error($conn));
        mysqli_stmt_bind_param($stmtUpdateSuspensions, "ssssss", $iduser, $suspendDate, $suspendEnd, $suspendReason, $suspendDuration, $adminName) or die(mysqli_error($conn));
        mysqli_stmt_execute($stmtUpdateSuspensions) or die(mysqli_error($conn));

        // check if there's any more reports against the user

        $sqlGetUserReports = "SELECT * FROM reports WHERE user=$iduser AND fixed=0";
        $resultGetUserReports = mysqli_query($conn, $sqlGetUserReports) or die(mysqli_error($conn));

        if (mysqli_num_rows($resultGetUserReports) === 0) {
            $sqlUpdateUser = "UPDATE shutuser SET flagged=0 WHERE iduser=$iduser";
            $resultUpdateUser = mysqli_query($conn, $sqlUpdateUser) or die(mysqli_error($conn));
        }

        // check if there's any more reports against the advert

        $sqlGetAdvert = "SELECT type, advert FROM reports WHERE idreports=$idreports";
        $resultGetAdvert = mysqli_query($conn, $sqlGetAdvert) or die(mysqli_error($conn));

        while ($row = mysqli_fetch_assoc($resultGetAdvert)) {

            $type = $row['type'];
            $idadvert = $row['advert'];

            if ($type == "Advert") {

                $sqlGetAdvertReports = "SELECT * FROM reports WHERE advert=$idadvert AND fixed=0";
                $resultGetAdvertReports = mysqli_query($conn, $sqlGetAdvertReports) or die(mysqli_error($conn));

                if (mysqli_num_rows($resultGetAdvertReports) === 0) {
                    $sqlUpdateAdvert = "UPDATE advert SET flagged=0 WHERE idadvert=$idadvert";
                    $resultUpdateAdvert = mysqli_query($conn, $sqlUpdateAdvert) or die(mysqli_error($conn));
                }

            }

        }

        mysqli_stmt_close($stmtResolveReportSuspendUser);
        $resolveReportSuspendUser = mysqli_insert_id($conn);

    }

    header("location: ../reports.php?filter=idreports&idreports=$idreports");

} else if (isset($_POST['resolveReportDeleteUser'])) {

    $idreports = filter_has_var(INPUT_POST, 'idreports') ? $_POST['idreports'] : null;
    $iduser= filter_has_var(INPUT_POST, 'iduser') ? $_POST['iduser'] : null;
    $type = filter_has_var(INPUT_POST, 'type') ? $_POST['type'] : null;

    if ($credentials == true) {

        $fixed = 1;
        $active = 0;
        $dateFixed = date("Y-m-d H:i:s");
        $adminName = $_SESSION['name'];

        // update report, delete user, delete advert/review

        if ($type == "User") {
            $sqlresolveReportDeleteUser = "UPDATE reports r, shutuser u SET r.fixed=?, r.dateFixed=?, r.fixedAdmin=?, u.active=? WHERE r.idreports=? AND u.iduser=?";
            $stmtresolveReportDeleteUser = mysqli_prepare($conn, $sqlresolveReportDeleteUser) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmtresolveReportDeleteUser, "ssssss", $fixed, $dateFixed, $adminName, $active, $idreports, $iduser) or die(mysqli_error($conn));
        } else if ($type == "Advert") {
            $idadvert = filter_has_var(INPUT_POST, 'idadvert') ? $_POST['idadvert'] : null;
            $sqlresolveReportDeleteUser = "UPDATE reports r, shutuser u, advert a SET r.fixed=?, r.dateFixed=?, r.fixedAdmin=?, u.active=?, a.active=? WHERE r.idreports=? AND u.iduser=? AND a.user=?";
            $stmtresolveReportDeleteUser = mysqli_prepare($conn, $sqlresolveReportDeleteUser) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmtresolveReportDeleteUser, "ssssssss", $fixed, $dateFixed, $adminName, $active, $active, $idreports, $iduser, $iduser) or die(mysqli_error($conn));
        } else if ($type == "Review") {
            $idreview = filter_has_var(INPUT_POST, 'idreview') ? $_POST['idreview'] : null;
            $sqlresolveReportDeleteUser = "UPDATE reports r, shutuser u, reviews v SET r.fixed=?, r.dateFixed=?, r.fixedAdmin=?, u.active=?, v.active=? WHERE r.idreports=? AND u.iduser=? AND v.idreviews=?";
            $stmtresolveReportDeleteUser = mysqli_prepare($conn, $sqlresolveReportDeleteUser) or die(mysqli_error($conn));
            mysqli_stmt_bind_param($stmtresolveReportDeleteUser, "ssssssss", $fixed, $dateFixed, $adminName, $active, $active, $idreports, $iduser, $idreview) or die(mysqli_error($conn));
        }

        mysqli_stmt_execute($stmtresolveReportDeleteUser) or die(mysqli_error($conn));

        // check if there's any more reports against the user

        $sqlGetUserReports = "SELECT * FROM reports WHERE user=$iduser AND fixed=0";
        $resultGetUserReports = mysqli_query($conn, $sqlGetUserReports) or die(mysqli_error($conn));

        if (mysqli_num_rows($resultGetUserReports) === 0) {
            $sqlUpdateUser = "UPDATE shutuser SET flagged=0 WHERE iduser=$iduser";
            $resultUpdateUser = mysqli_query($conn, $sqlUpdateUser) or die(mysqli_error($conn));
        }

        // check if there's any more reports against the advert

        $sqlGetAdvert = "SELECT type, advert FROM reports WHERE idreports=$idreports";
        $resultGetAdvert = mysqli_query($conn, $sqlGetAdvert) or die(mysqli_error($conn));

        while ($row = mysqli_fetch_assoc($resultGetAdvert)) {

            $type = $row['type'];
            $idadvert = $row['advert'];

            if ($type == "Advert") {

                $sqlGetAdvertReports = "SELECT * FROM reports WHERE advert=$idadvert AND fixed=0";
                $resultGetAdvertReports = mysqli_query($conn, $sqlGetAdvertReports) or die(mysqli_error($conn));

                if (mysqli_num_rows($resultGetAdvertReports) === 0) {
                    $sqlUpdateAdvert = "UPDATE advert SET flagged=0 WHERE idadvert=$idadvert";
                    $resultUpdateAdvert = mysqli_query($conn, $sqlUpdateAdvert) or die(mysqli_error($conn));
                }

            }

        }

        mysqli_stmt_close($stmtresolveReportDeleteUser);
        $resolveReportDeleteUser = mysqli_insert_id($conn);

    }

    header("location: ../reports.php?filter=idreports&idreports=$idreports");

}