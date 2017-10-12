<?php
include 'server/db_conn.php';
require_once('functions.php');

$userId = $_SESSION['iduser'];

if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
    echo pageIni("Access Denied");
    echo error();
}else{
    echo pageIni("Advert Stats | My Adverts - Shuttershare");

    echo nav2();
    echo nav3("", "", " class=\"active\"", "");

    $id = $_GET['id'];

    //Advert details

    $sqlCount = "SELECT title, views FROM advert WHERE idadvert = '$id'";
    $countResult = mysqli_query($conn, $sqlCount) or die(mysqli_error($conn));

    $count = mysqli_fetch_array( $countResult );
    $viewCount = $count['views'];
    $adName = $count['title'];

    //Booking details

    $sqlBook = "SELECT * FROM bookings WHERE advert='$id'";
    $BookingCount = mysqli_query($conn, $sqlBook) or die(mysqli_error($conn));

    $BookingNum = mysqli_num_rows($BookingCount);

    //Revenue

    $totalIncome = "SELECT SUM(costTotal) AS costTotal FROM bookings WHERE advert='$id' AND active != 4";
    $revenue = mysqli_query($conn, $totalIncome) or die(mysqli_error($conn));
    $costTotal = 0;
    while($income = mysqli_fetch_assoc($revenue)){
        $costTotal += round(intval($income['costTotal']), 2);
    }
    $costTotal = round(intval($costTotal), 2);


    //rating
    $sqlrev2 = "SELECT rating, helpful FROM  reviews WHERE advert = '$id'";
    $rrev2 = mysqli_query($conn, $sqlrev2) or die(mysqli_error($conn));

    $num = mysqli_num_rows($rrev2);
    $sum_rate = 0;
    while($row3 = mysqli_fetch_assoc($rrev2)){
        $sum_rate += intval($row3['rating']);
    }

    $helpRev = mysqli_fetch_array($rrev2);
    $helpTotal = $helpRev['helpful'];

    //helpful
    $sqlrev3 = "SELECT * FROM  reviews WHERE advert = '$id' AND helpful > 0 ";
    $rrev3 = mysqli_query($conn, $sqlrev3) or die(mysqli_error($conn));

    $helpNum = mysqli_num_rows($rrev3);

    //total days booked

    $sql = "SELECT start, end FROM availability WHERE advert = '$id' ";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    $totalDays = 0;

    while ($dates = mysqli_fetch_array($result)){
        $start = $dates['start'];
        $end = $dates['end'];

        $datetime1 = new DateTime($end);
        $datetime2 = new DateTime($start);
        $interval = $datetime1->diff($datetime2);

        $totalDays = $totalDays + $interval->format('%a');


    }

    ?>
    <div class="back-end">
        <?php
        echo breadcrumb("myAdvert.php", "My Adverts", "$adName");
        ?>
        <section class="acc-man">
            <h1>Advert Statistics</h1>

            <div id="no-edit-pers">
                <section>

                <ul>
                    <li><span>View count</span><p><?php echo $viewCount; ?></p></li>
                    <li><span>Total bookings</span><p><?php echo $BookingNum; ?></p></li>
                    <li><span>Total days booked</span><p><?php echo $totalDays; ?></p></li>
                    <li><span>Total revenue</span><?php echo "&pound;", $costTotal; ?></li>
                    <li><span>Total reviews</span><p><?php echo $num; ?></p></li>
                    <li><span>Total helpful reviews</span><p><?php echo $helpNum; ?></p></li>
                    <li><span>Average rating</span><p><div class="rating">

                            <?php
                            if($sum_rate != 0){
                                $avgrate = round($sum_rate/$num);
                            }

                            if ($sum_rate == 0){
                                echo "<span class='noRevs'>No reviews yet</span>";
                            }

                            for($i = 1; $i <=5; $i++){
                                if ($sum_rate == 0){
                                    echo "<span></span>";
                                }elseif($i <= $avgrate){
                                    echo "<span id=\"plus\">&#9733;</span>";
                                } else {
                                    echo "<span id=\'minus\'>&#9733;</span>";
                                }
                            }
                            ?>
                        </div></p></li>
                </ul>
        </section>
    </div>
    <?php
    echo javascript('advert.js');
}
echo pageClose();
?>