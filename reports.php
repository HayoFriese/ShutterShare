<?php
include 'server/db_conn.php';
require_once('functions_admin.php');
require_once('adminCheckSession.php');
echo pageIni("Reports | Admin - Shuttershare");
echo nav4();
echo nav6("", " class=\"active\"", "", "");

$filter = isset($_GET['filter'])?$_GET['filter']:null;
$idreports = isset($_GET['idreports'])?$_GET['idreports']:null;
$name = isset($_GET['name'])?$_GET['name']:null;
$currentFilter = "";
$user = "";
$fixed = "";

switch($filter)
{
  case 'all': $filter = 'all'; $currentFilter = "ALL"; break;
  case 'open': $filter = 'open'; $currentFilter = "OPEN"; break;
  case 'closed': $filter = 'closed'; $currentFilter = "CLOSED"; break;
  case 'advert': $filter = 'advert'; $currentFilter = "ADVERTS"; break;
  case 'user': $filter = 'user'; $currentFilter = "USERS"; break;
  case 'review': $filter = 'review'; $currentFilter = "REVIEWS"; break;
  case 'idreports': $filter = 'idreports'; $currentFilter = "#".$idreports; break;
  default: $filter = null; $currentFilter = "OPEN"; break;
}

$ascdesc = isset($_GET['ascdesc'])?$_GET['ascdesc']:'DESC';

switch(strtoupper($ascdesc))
{
  case 'DESC': $ascdesc = 'ASC'; break;
  case 'ASC': $ascdesc = 'DESC'; break;
}

?>
<div class="back-end">
  <div id="container">
    <div id="box">
      <div id="breadcrumb">

        <?php

        if ($filter == "idreports") {

          $sql = "SELECT fixed, type, advert FROM reports WHERE idreports='$idreports'";
          $result = mysqli_query($conn, $sql);

          while ($row = mysqli_fetch_assoc($result)){

            $fixed = $row['fixed'];
            $type = $row['type'];
            $advert = $row['advert'];

            if ($fixed == 0) {

              echo "
                        <p><a href=\"reports.php\">Reports</a> / <span id=\"userLink\"><a href=\"?filter=idreports&idreports=$idreports\">#$idreports</a> / </span></p>
                        <div>
                        <ul>
                        <li><span><a id=\"tableFilter\"href=\"#\"> $currentFilter</a></span><ul id=\"dropdown\"><div></div>
                        <li id=\"liResolve\"><a href=\"#\">Resolve</a></li>";

              if ($type == "Advert") {
                echo "<li><a href=\"viewAdvert.php?id=$advert\" target=\"_blank\">View advert</a></li>";
              } else if ($type == "Review") {
                echo "<li><a href=\"viewAdvert.php?id=$advert\" target=\"_blank\">View review</a></li>";
              }

            } else {

              echo "
                        <p><a href=\"reports.php\">Reports</a> / <a href='#' style='color: #FFAF00; font-weight: 600;'>#$idreports</a> <span id=\"userLink\"><a href=\"?filter=idreports&idreports=$idreports\">#$idreports</a> / </span></p>
                        <div>
                        <ul>
                        <li><span><a id=\"tableFilter\"href=\"#\"></a></span><ul id=\"dropdown\"><div></div>";

            }

          }

        } else {

          echo "
                    <p><a href=\"reports.php\">Reports</a> / <span id=\"userLink\"><a href=\"?filter=idreports&idreports=$idreports\">#$idreports</a> / </span></p>
                    <div>
                    <ul>
                    <li><span><a id=\"tableFilter\"href=\"#\"> $currentFilter</a></span><ul id=\"dropdown\"><div></div>
                    <li id=\"all\"><a href=\"?filter=all\">All</a></li>
                    <li id=\"open\"><a href=\"?filter=open\">Open</a></li>
                    <li id=\"closed\"><a href=\"?filter=closed\">Closed</a></li>
                    <li id=\"advert\"><a href=\"?filter=advert\">Adverts</a></li>
                    <li id=\"user\"><a href=\"?filter=user\">Users</a></li>
                    <li id=\"user\"><a href=\"?filter=review\">Reviews</a></li>";
        }

        ?>
        </ul>
        </li>
        </ul>
        <div>
        </div>
      </div>
    </div>
    <div id="box2">
      <div id="reports">

        <?php

        $where = "fixed=0 ";
        if ($filter != "" && $filter != "all" && $filter != 'iduser') {
          $where = "type='$filter' ";
        }
        if ($filter == "idreports") {
          $where = "idreports=$idreports ";
        }
        if ($filter == "open") {
          $where = "fixed=0 ";
        }
        if ($filter == "closed") {
          $where = "fixed=1 ";
        }
        if ($filter == "all") {
          $where = "1 ";
        }

        $ascdesc = "";
        $order = isset($_GET['sort'])?$_GET['sort']:'date';
        $ascdesc = isset($_GET['ascdesc'])?$_GET['ascdesc']:'ASC';
        switch(strtoupper($ascdesc))
        {
          case 'DESC': $ascdesc = 'ASC'; break;
          case 'ASC': $ascdesc = 'DESC'; break;
        }

        $sql4 = "SELECT * FROM reports INNER JOIN shutuser ON reports.user=shutuser.iduser WHERE ".$where."ORDER BY $order $ascdesc";

        $result4 = mysqli_query($conn, $sql4);

        if (mysqli_num_rows($result4) === 0) {

          echo "<h1>No results.</h1>";

        } else {

          if ($filter !== "idreports") {
            echo "
                  <table cellspacing=\"0\" cellpadding=\"0\">
            <thead>
            <tr>
            <th><a href=\"?filter=$filter&sort=fixed&ascdesc=$ascdesc\">Status</a></th>
            <th><a href=\"?filter=$filter&sort=idreports&ascdesc=$ascdesc\">Reporter</a></th>
            <th><a href=\"?filter=$filter&sort=date&ascdesc=$ascdesc\">Issued</a></th>
            <th><a href=\"?filter=$filter&sort=category&ascdesc=$ascdesc\">Category</a></th>
            <th><a href=\"?filter=$filter&sort=subject&ascdesc=$ascdesc\">User</a></th>
            </tr>
            </thead>
          </table>
          <div class=\"admin-table-body\">
            <table cellspacing=\"0\" cellpadding=\"0\">
              <tbody>
                ";
          }

          while ($row = mysqli_fetch_assoc($result4)) {

            $idreports = $row['idreports'];
            $category = $row['category'];
            $fixed = $row['fixed'];
            $dateFixed = $row['dateFixed'];
            $fixedAdmin = $row['fixedAdmin'];
            $type = $row['type'];
            $advert = $row['advert'];
            $user = $row['user'];
            $userName = $row['fname']." ".$row['surname'];
            $reporter = $row['reporter'];
            $message = $row['message'];
            $date = $row['date'];

            $date = strtotime($date);
            $date = date("j F Y, g:i A", $date);

            $dateFixed = strtotime($dateFixed);
            $dateFixed = date("j F Y, g:i A", $dateFixed);

            $type = ucwords($type);

            if ($fixed == 1) {
              $fixed = "Closed";
            } else {
              $fixed = "Open";
            }

            if ($filter == "idreports") {

              echo "<section><h1>Report details</h1><ul>
                                <li><span>Status</span>$fixed</li>
                                <li><span>Type</span>$type</li>
                                <li><span>Issued</span>$date</li>";

              $sql3 = "SELECT fname, surname FROM shutuser WHERE iduser=$reporter";

              $result3 = mysqli_query($conn, $sql3);

              while ($row = mysqli_fetch_assoc($result3)) {
                $reporterName = $row['fname']." ".$row['surname'];
                echo "<li><span>Reporter</span>$reporterName</li>";
              }

              echo "<li><span>User</span>$userName</li>";

              if ($fixed == "Closed") {
                echo "<li><span>Closed</span>$dateFixed</li>
                        <li><span>Admin</span>$fixedAdmin</li>";
              }
              
              if ($type == "advert") {
                echo "<li><span>Advert</span><p>$advert</p></li>";
              }

              echo "
                                <li><span>Category</span>$category</li>
                                <li><span>Message</span>$message</li>
                                </ul></section>";
            } else {

              echo "<tr onclick=\"window.location='?filter=idreports&idreports=$idreports'\">";

              echo "<td>$fixed</td>";

              $sql2 = "SELECT fname, surname FROM shutuser WHERE iduser=$reporter";

              $result2 = mysqli_query($conn, $sql2);

              while ($row = mysqli_fetch_assoc($result2)) {
                $reporterName = $row['fname']." ".$row['surname'];
                echo "<td>$reporterName</td>";
              }

              echo "
                          <td>$date</td>
                          <td>$category</td>
                          <td>$userName</td>
                          <input type='hidden' name='idadmin' value='$idreports'>
                          </tr>";
            }
          }

          if ($filter != "idreports") {
            echo "</tbody></table>";
          }
        }
        ?>

      </div>

      <div id="resolve">
        <h1>Resolve report #<?php echo $idreports; ?></h1>
        <ul class="errors" style="display: none">
          <li class="errorMessage">Please correct the following errors:</li>
          <li class="changekeyError"></li>
        </ul>
        <form id="reportForm" action="server/processAdmin.php" method="post" onsubmit="return validateReport()">
          <fieldset>
            <div>
              <select id="resolve" onchange="changeSubmit(this);" required>
                <option value="" disabled selected>Select an action</option>
                <option value="none">Dismiss</option>

                <?php

                $sqlCheckType = "SELECT type, advert, review FROM reports WHERE idreports=$idreports";
                $resultCheckType = mysqli_query($conn, $sqlCheckType);

                while ($row = mysqli_fetch_assoc($resultCheckType)) {

                  $type = $row['type'];
                  $idadvert = $row['advert'];
                  $idreview = $row['review'];

                  if ($type == "Advert") {
                    echo "<option value=\"deleteAdvert\">Delete advert</option>";
                  } else if ($type == "Review") {
                    echo "<option value=\"deleteReview\">Delete review</option>";
                  }

                $sessionChangekey = $_SESSION['changekey'];

                echo "<option value=\"suspend\">Suspend user</option>
                            <option value=\"delete\">Delete user</option>
                            </select>
                            <div id=\"suspend\">
                            <select id='susReas' name=\"suspendReason\">
                              <option value=\"\">Select a reason</option>
                              <option value=\"Spam\">Defamation</option>
                              <option value=\"Fraud\">Fraud</option>
                              <option value=\"Harassment\">Harassment</option>
                              <option value=\"Inappropriate content\">Inappropriate content</option>
                              <option value=\"Phishing\">Phishing</option>
                              <option value=\"Spam\">Spam</option>
                              <option value=\"System misuse\">System misuse</option>
                            </select>
                            <select id='susDur' name=\"suspendDuration\">
                              <option value=\"\">Select a duration</option>
                              <option value=\"1 day\">1 day</option>
                              <option value=\"3 days\">3 days</option>
                              <option value=\"1 week\">1 week</option>
                              <option value=\"2 weeks\">2 weeks</option>
                              <option value=\"1 month\">1 month</option>
                              <option value=\"3 months\">3 months</option>
                            </select>
                            </div>
                            <input type=\"password\" name=\"changekey\" placeholder=\"Change key\" required>  
                            </div>
                            <div>     
                            <input type=\"hidden\" name=\"idreports\" value=\"$idreports\">
                            <input type=\"hidden\" name=\"iduser\" value=\"$user\">
                            <input type=\"hidden\" name=\"idadvert\" value=\"$idadvert\">
                            <input type=\"hidden\" name=\"idreview\" value=\"$idreview\">
                            <input type=\"hidden\" name=\"type\" value=\"$type\">
                            <input type=\"hidden\" name=\"sessionChangekey\" value=\"$sessionChangekey\">
                            <input type=\"submit\" id=\"confirm\" value=\"RESOLVE REPORT\">";

                }

                ?>

            </div>
          </fieldset>
        </form>
      </div>

      <script>

        function validateReport() {

          var changekey = document.forms["reportForm"]["changekey"].value,
              sessionChangekey = document.forms["reportForm"]["sessionChangekey"].value;

          if (changekey !== sessionChangekey) {
            $('.errors').show();
            $('.errorMessage').show();
            $('.changekeyError').html("Change key is incorrect");
            $('.changekeyError').show();
            return false;
          } else {
            $('.errors').hide();
          }

        }
      </script>

    </div>

    <?php
    echo javascript("admin.js");
    echo pageClose();
    ?>
