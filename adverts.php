<?php
include 'server/db_conn.php';
require_once('functions_admin.php');
require_once('adminCheckSession.php');
echo pageIni("Adverts | Admin - Shuttershare");
echo nav4();
echo nav6(" class=\"active\"", "", "", "");

$filter = isset($_GET['filter'])?$_GET['filter']:null;
$idadvert = isset($_GET['idadvert'])?$_GET['idadvert']:null;
$active = isset($_GET['active'])?$_GET['active']:null;
$name = isset($_GET['name'])?$_GET['name']:null;
$currentFilter = "";

switch($filter)
{
  case 'all': $filter = 'all'; $currentFilter = "ALL"; break;
  case 'active': $filter = 'active'; $currentFilter = "ACTIVE"; break;
  case 'inactive': $filter = 'inactive'; $currentFilter = "INACTIVE"; break;
  case 'flagged': $filter = 'flagged'; $currentFilter = "REPORTED"; break;
  case 'advert': $filter = 'advert'; $currentFilter = "ADVERTS"; break;
  case 'user': $filter = 'user'; $currentFilter = "USERS"; break;
  case 'idadvert': $filter = 'idadvert'; $currentFilter = "#".$idadvert; break;
  default: $filter = null; $currentFilter = "ALL"; break;
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
    <p><a href="adverts.php">Adverts</a> / <?php echo "<span id=\"userLink\"><a href=\"?filter=idadvert&idadvert=$idadvert\">#$idadvert</a> / </span>"; ?></p>
    <div>
      <ul>
        <li><span><a  id="tableFilter"href="#"> <?php echo $currentFilter; ?></a></span>
          <ul id="dropdown">
            <div></div>
            <?php

            if ($filter == "idadvert") {
              echo "<li><a href=\"viewAdvert.php?id=$idadvert\" target=\"_blank\">View</a></li>";

              $sql = "SELECT active, flagged FROM advert WHERE idadvert='$idadvert'";

//              $sql = "SELECT active FROM profcase.advert WHERE idadvert='$idadvert'";

              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
                $active = $row['active'];
                $flagged = $row['flagged'];

                $sqlReports = "SELECT * FROM reports WHERE advert=$idadvert  AND type='Advert'";
                $resultReports = mysqli_query($conn, $sqlReports);

                if ($flagged == 1 || mysqli_num_rows($resultReports) !== 0) {
                  echo "<li><a href=\"#\">Reports</a></li>";
                }
                if ($active == 1) {
                  echo "<li><a href=\"#\">Delete</a></li>";
                } else {
                  echo "<li><a href=\"#\">Restore</a></li>";
                }
              }
            } else {
              echo "<li id=\"all\"><a href=\"?filter=all\">All</a></li>
            <li id=\"active\"><a href=\"?filter=active\">Active</a></li>
            <li id=\"inactive\"><a href=\"?filter=inactive\">Inactive</a></li>
            <li id=\"flagged\"><a href=\"?filter=flagged\">Reported</a></li>";
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
    <div id="adverts">
      <?php

      $where = "WHERE 1 ";

      if ($filter != "" && $filter != "all" && $filter != 'iduser') {
        $where = "WHERE type='$filter' ";
      }
      if ($filter == "idadvert") {
        $where = "INNER JOIN shutuser ON advert.user=shutuser.iduser WHERE idadvert=$idadvert ";
      }
      if ($filter == "active") {
        $where = "WHERE active=1 ";
      }
      if ($filter == "inactive") {
        $where = "WHERE active=0 ";
      }
      if ($filter == "flagged") {
        $where = "WHERE flagged=1 ";
      }

      $ascdesc = "";
      $order = isset($_GET['sort'])?$_GET['sort']:'date';
      $ascdesc = isset($_GET['ascdesc'])?$_GET['ascdesc']:'ASC';
      switch(strtoupper($ascdesc))
      {
        case 'DESC': $ascdesc = 'ASC'; break;
        case 'ASC': $ascdesc = 'DESC'; break;
      }

      if ($filter == "idadvert") {
        $sql2 = "SELECT advert.idadvert, advert.title, advert.adDesc, advert.cost, advert.date, advert.keywords, advert.flagged, advert.active, advert.user, advert.views, shutuser.fname, shutuser.surname FROM advert ".$where."ORDER BY $order $ascdesc";
      } else {
        $sql2 = "SELECT * FROM advert ".$where."ORDER BY $order $ascdesc";
      }

      $result2 = mysqli_query($conn, $sql2);

      if (mysqli_num_rows($result2) === 0) {

        echo "<h1>No results.</h1>";

      } else {

        if ($filter != "idadvert") {

          echo "
            <table cellspacing=\"0\" cellpadding=\"0\">
            <thead>
            <tr>  
              <th><a href=\"?filter=$filter&sort=idadvert&ascdesc=$ascdesc\">Owner</a></th>
              <th><a href=\"?filter=$filter&sort=title&ascdesc=$ascdesc\">Title</a></th>
              <th><a href=\"?filter=$filter&sort=cost&ascdesc=$ascdesc\">Cost</a></th>
              <th><a href=\"?filter=$filter&sort=date&ascdesc=$ascdesc\">Created</a></th>
            </tr>
            </thead>
            </table>
            <div class=\"admin-table-body\">
            <table cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
        ";

        }

        while ($row = mysqli_fetch_assoc($result2)) {

          $idadvert = $row['idadvert'];
          $title = $row['title'];
          $adDesc = $row['adDesc'];
          $cost = $row['cost'];
          $cost = number_format($cost, 2, '.', '');
          $date = $row['date'];
          $keywords = $row['keywords'];
          $flagged = $row['flagged'];
          $active = $row['active'];
          $user = $row['user'];
          $views = $row['views'];
          $date = strtotime($date);
          $date = date("j F Y, g:i A", $date);

          if ($filter == "idadvert") {
            $name = $row['fname']." ".$row['surname'];
          }

          if ($flagged == 1) {
            $active = "Reported";
          } else if ($active == 1) {
            $active = "Active";
          } else {
            $active = "Inactive";
          }

          if ($filter == "idadvert") {

            echo "<section><h1>Advert details</h1><ul>
                    <li><span>Owner</span><p>$name</p></li>
                    <li><span>Title</span><p>$title</p></li>
                    <li><span>Description</span><p>$adDesc</p></li>
                    <li><span>Cost</span>&pound;$cost</li>
                    <li><span>Keywords</span><p>$keywords</p></li></ul></section>";

            echo "<section><h1>Statistics</h1><ul>
                    <li><span>Status</span>$active</li>
                    <li><span>Created</span>$date</li>
                    <li><span>Views</span>$views</li></ul></section>";

          } else {

            echo "<tr onclick=\"window.location='?filter=idadvert&idadvert=$idadvert'\">";

            $sql3 = "SELECT fname, surname FROM shutuser WHERE iduser=$user";

            $result3 = mysqli_query($conn, $sql3);

            while ($row = mysqli_fetch_assoc($result3)) {
              $ownerName = $row['fname']." ".$row['surname'];
              echo "<td>$ownerName</td>";
            }

            echo"<td>$title</td>
                 <td>&pound;$cost</td>
                 <td>$date</td>
                 <input type='hidden' name='idadmin' value='$idadvert'>
                 </tr>
              ";
          }
        } echo "</table>";
      }

      ?>

    </div>

    <div id="delete">
      <h1>Delete <?php echo $name; ?>'s advert</h1>
      <ul class="errors" style="display: none">
        <li class="errorMessage">Please correct the following errors:</li>
        <li class="changekeyError"></li>
      </ul>
      <form id="deleteForm" action="server/processAdmin.php" method="post" onsubmit="return validateDeleteReport()">
        <fieldset>
          <div>
            <input type="password" name="changekey" maxlength="4" placeholder="Change key" required autofocus>
            <input type="hidden" name="idadvert" value="<?php echo $idadvert; ?>">
            <?php $sessionChangekey = $_SESSION['changekey']; echo "<input type=\"hidden\" name=\"sessionChangekey\" value=\"$sessionChangekey\">"; ?>

          </div>
          <div>
            <input type="submit" id="confirm" name="deleteAdvert" value="DELETE ADVERT">
          </div>
        </fieldset>
      </form>
    </div>

    <script>

      function validateDeleteReport() {

        var changekey = document.forms["deleteForm"]["changekey"].value,
            sessionChangekey = document.forms["deleteForm"]["sessionChangekey"].value;

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

    <div id="restore">
      <h1>Restore <?php echo $name; ?>'s advert</h1>
      <ul class="errors" style="display: none">
        <li class="errorMessage">Please correct the following errors:</li>
        <li class="changekeyError"></li>
      </ul>
      <form id="restoreForm" action="server/processAdmin.php" method="post" onsubmit="return validateRestoreReport()">
        <fieldset>
          <div>
            <input type="password" name="changekey" maxlength="4" placeholder="Change key" required autofocus>
            <input type="hidden" name="idadvert" value="<?php echo $idadvert; ?>">
            <?php $sessionChangekey = $_SESSION['changekey']; echo "<input type=\"hidden\" name=\"sessionChangekey\" value=\"$sessionChangekey\">"; ?>
          </div>
          <div>
            <input type="submit" id="confirm" name="restoreAdvert" value="RESTORE ADVERT">
          </div>
        </fieldset>
      </form>
    </div>

    <script>

      function validateRestoreReport() {

        var changekey = document.forms["restoreForm"]["changekey"].value,
            sessionChangekey = document.forms["restoreForm"]["sessionChangekey"].value;

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

    <div id="advertReports">
      <?php
      echo "<table cellspacing=\"0\" cellpadding=\"0\">
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
            <tbody>";

      $sql4 = "SELECT * FROM reports INNER JOIN shutuser ON reports.reporter=shutuser.iduser WHERE advert=$idadvert AND type='Advert' ORDER BY fixed ASC";
      $result4 = mysqli_query($conn, $sql4);
      while ($row = mysqli_fetch_assoc($result4)) {
        $idreports = $row['idreports'];
        $reporter = $row['reporter'];
        $date = $row['date'];
        $category = $row['category'];
        $user = $row['user'];
        $fixed = $row['fixed'];
        if ($fixed == 1) {
          $fixed = "Closed";
        } else {
          $fixed = "Open";
        }
        $reporterName = $row['fname'] . " " . $row['surname'];
        $date = strtotime($date);
        $date = date("j F Y, g:i A", $date);
        echo "<tr onclick=\"window.open('reports.php?filter=idreports&idreports=$idreports')\">
              <td>$fixed</td>
              <td>$reporterName</td>
              <td>$date</td>
              <td>$category</td>";

        $sql5 = "SELECT fname, surname FROM shutuser WHERE iduser=$user";
        $result5 = mysqli_query($conn, $sql5);
        while ($row = mysqli_fetch_assoc($result5)) {
          $ownerName = $row['fname'] . " " . $row['surname'];
          echo "<td>$ownerName</td>";
        }
      }


      ?>
    </div>

  </div>
<?php
echo javascript("admin.js");
echo pageClose();
?>