<?php
include 'server/db_conn.php';
require_once('functions_admin.php');
require_once('adminCheckSession.php');
echo pageIni("Users | Admin - Shuttershare");
echo nav4();
echo nav6("", "", " class=\"active\"", "");

$filter = isset($_GET['filter'])?$_GET['filter']:null;
$iduser = isset($_GET['iduser'])?$_GET['iduser']:null;
$name = "";
$active = "";
$suspended = "";
$currentFilter = "";

switch($filter)
{
  case 'all': $filter = 'all'; $currentFilter = " ALL"; break;
  case 'flagged': $filter = 'flagged'; $currentFilter = " REPORTED"; break;
  case 'suspension': $filter = 'suspension'; $currentFilter = " SUSPENDED"; break;
  case 'active': $filter = 'active'; $currentFilter = " ACTIVE"; break;
  case 'inactive': $filter = 'inactive'; $currentFilter = " INACTIVE"; break;
  case 'iduser': $filter = 'iduser'; break;
  default: $filter = null; $currentFilter = " ALL"; break;
}

$ascdesc = isset($_GET['ascdesc'])?$_GET['ascdesc']:'DESC';

switch(strtoupper($ascdesc))
{
  case 'DESC': $ascdesc = 'ASC'; break;
  case 'ASC': $ascdesc = 'DESC'; break;
  default: $ascdesc = 'ASC'; break;
}

?>
<div class="back-end">
  <div id="container">
    <div id="box">
      <div id="breadcrumb">

        <?php

        if ($filter == "iduser") {

          $sql = "SELECT fname, surname, suspension, active, flagged FROM shutuser WHERE iduser='$iduser'";
          $result = mysqli_query($conn, $sql);

          while ($row = mysqli_fetch_assoc($result)) {

            $name = $row['fname']." ".$row['surname'];
            $suspended = $row['suspension'];
            $active = $row['active'];
            $flagged = $row['flagged'];

            echo "<p><a href=\"users.php\">Users</a> / <span id=\"userLink\"><a href=\"?filter=iduser&iduser=$iduser\">$name</a> / </span></p>
                      <div><ul><li><span><a id=\"tableFilter\"href=\"#\">$name</a></span>
                      <ul id=\"dropdown\"><div></div>";

            if ($active == 1) {
              if ($flagged == 1) {

                echo "<li><a href=\"#\">Reports</a></li>";
              }
              if ($suspended == "1") {
                echo "<li id=\"unsuspendUser\"><a href=\"#\">Unsuspend</a></li>";
              } else {
                echo "<li id=\"suspendUser\"><a href=\"#\">Suspend</a></li>";
              }
              echo "<li id=\"deactivateUser\"><a href=\"#\">Deactivate</a></li>";
            } else {
              echo "<li id=\"deactivateUser\"><a href=\"#\">Reactivate</a></li>";
            }

          }

        } else {

          echo "<p><a href=\"users.php\">Users</a> / </p>
                    <div><ul><li><span><a id=\"tableFilter\"href=\"#\">$currentFilter</a></span>
                    <ul id=\"dropdown\"><div></div>
                    <li><a href=\"?filter=all\">All</a></li>
                    <li><a href=\"?filter=flagged\">Reported</a></li>
                    <li><a href=\"?filter=suspension\">Suspended</a></li>
                    <li><a href=\"?filter=active\">Active</a></li>
                    <li><a href=\"?filter=inactive\">Inactive</a></li>";

        } ?>

        </ul>
        </li>
        </ul>
        <div>
        </div>
      </div>
    </div>
    <div id="box2">
      <div id="users">
        <?php

        $where = "1 ";
        if ($filter != "" && $filter != "all" && $filter != 'iduser' && $filter != 'inactive') {
          $where = "$filter=1 ";
        }
        if ($filter == "inactive") {
          $where = "active=0 ";
        }
        if ($filter == "iduser") {
          $where = "$filter=$iduser ";
        }
        $ascdesc = "";
        $order = isset($_GET['sort'])?$_GET['sort']:'fname';
        $ascdesc = isset($_GET['ascdesc'])?$_GET['ascdesc']:'DESC';
        switch(strtoupper($ascdesc))
        {
          case 'DESC': $ascdesc = 'ASC'; break;
          case 'ASC': $ascdesc = 'DESC'; break;
          default: $ascdesc = 'ASC'; break;
        }
        if ($filter == "iduser") {
          $sql = "SELECT shutuser.iduser, shutuser.fname, shutuser.surname, shutuser.email, shutuser.tel, shutuser.mobile, shutuser.flagged, shutuser.suspension, shutuser.suspensions, suspensions.suspendReason, suspensions.suspendDuration, suspensions.suspendDate, suspensions.suspendEnd, suspensions.suspendAdmin, shutuser.active, shutuser.created, shutuser.logins, shutuser.lastLogin FROM shutuser LEFT OUTER JOIN suspensions ON shutuser.iduser=suspensions.suspendUser AND suspensions.suspendActive=1 WHERE ".$where."ORDER BY $order $ascdesc";
        } else {
          $sql = "SELECT * FROM shutuser WHERE ".$where."ORDER BY $order $ascdesc";
        }

        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

        if (mysqli_num_rows($result) === 0) {

          echo "<h1>No results.</h1>";

        } else {

          if ($filter != "iduser") {
            echo "
                    <table cellspacing=\"0\" cellpadding=\"0\">
                      <thead>
                        <tr>
                          <th><a href=\"?filter=$filter&sort=fname&ascdesc=$ascdesc\">First Name</a></th>
                          <th><a href=\"?filter=$filter&sort=surname&ascdesc=$ascdesc\">Last Name</a></th>
                          <th><a href=\"?filter=$filter&sort=tel&ascdesc=$ascdesc\">Mobile</a></th>
                          <th><a href=\"?filter=$filter&sort=email&ascdesc=$ascdesc\">Email</a></th>
                        </tr>
                      </thead>
                    </table>
                    <div class=\"admin-table-body\">
                      <table cellspacing=\"0\" cellpadding=\"0\">
                        <tbody>
                    ";
          }

          while ($row = mysqli_fetch_assoc($result)) {

            $iduser = $row['iduser'];
            $fname = $row['fname'];
            $surname = $row['surname'];
            $email = $row['email'];
            $tel = $row['tel'];
            $mobile = $row['mobile'];
            $flagged = $row['flagged'];
            $suspended = $row['suspension'];
            $suspensions = $row['suspensions'];

            if ($filter == "iduser") {
              $suspendReason = $row['suspendReason'];
              $suspendDuration = $row['suspendDuration'];
              $suspendDate = $row['suspendDate'];
              $suspendEnd = $row['suspendEnd'];
              $suspendAdmin = $row['suspendAdmin'];
              $suspendDate = strtotime($suspendDate);
              $suspendDate = date("j F Y, g:i A", $suspendDate);
              $suspendEnd = strtotime($suspendEnd);
              $suspendEnd = date("j F Y, g:i A", $suspendEnd);
            }

            $active = $row['active'];
            $created = $row['created'];
            $logins = $row['logins'];
            $lastLogin = $row['lastLogin'];

            if ($active == 1) {
              if ($suspended == 1) {
                $active = "Suspended";
              } else if ($flagged == 1) {
                $active = "Reported";
              } else {
                $active = "Active";
              }
            } else {
              $active = "Inactive";
            }

            if ($mobile == "") {
              $mobile = "N/A";
            }

            $created = strtotime($created);
            $created = date("j F Y, g:i A", $created);

            if ($lastLogin == '0000-00-00 00:00:00') {
              $lastLogin = $created;
            } else {
              $lastLogin = strtotime($lastLogin);
              $lastLogin = date("j F Y, g:i A", $lastLogin);
            }

            if ($filter == "iduser") {

              echo "<section><h1>Personal details</h1><ul>
                                            <li><span>Name</span>$fname $surname</li>";

              if ($tel !== "") {
                echo "<li><span>Telephone</span>$tel</li>";
              }

              if ($mobile !== "") {
                echo "<li><span>Mobile</span>$mobile</li>";
              }

              echo "<li><span>Email</span>$email</li></ul></section>";

              if ($suspended == "1") {
                echo "<section><h1>Suspension details</h1><ul class='border'>
                              <li><span>Reason</span>$suspendReason</li>
                              <li><span>Duration</span>$suspendDuration</li>
                              <li><span>Start</span>$suspendDate</li>
                              <li><span>End</span>$suspendEnd</li>
                              <li><span>Admin</span>$suspendAdmin</li></ul></section>";
              }

              echo "<section><h1>Statistics</h1><ul>
                                                 <li><span>ID</span>$iduser</li>
                                                 <li><span>Status</span>$active</li>
                                                 <li><span>Last login</span>$lastLogin</li>
                                                 <li><span>Logins</span>$logins</li>
                                                 <li><span>Created</span>$created</li>
                                                 <li><span>Suspensions</span>$suspensions</li>
                                           </ul></section>";


            } else {
              echo "
                      <tr onclick=\"window.location='?filter=iduser&iduser=$iduser'\">
                      <td>$fname</td>
                      <td>$surname</td>
                      <td>$mobile</td>
                      <td>$email</td>
                      <input type='hidden' name='iduser' value='$iduser'>
                      </tr>
                  ";
            }
          }
        }

        ?>
        </tbody>
        </table>
      </div>

      <div id="create">
        <h1>Suspend <?php echo "$name" ?>'s account</h1>
        <ul class="errors" style="display: none">
          <li class="errorMessage">Please correct the following errors:</li>
          <li class="changekeyError"></li>
        </ul>
        <form id="suspendUserForm" action="server/processAdmin.php" method="post" onsubmit="return validateSuspendUser()">
          <fieldset id="createStep1">
            <div>
              <select name="suspendReason" required>
                <option value="">Select a reason</option>
                <option value="Spam">Defamation</option>
                <option value="Fraud">Fraud</option>
                <option value="Harassment">Harassment</option>
                <option value="Inappropriate content">Inappropriate content</option>
                <option value="Phishing">Phishing</option>
                <option value="Spam">Spam</option>
                <option value="System misuse">System misuse</option>
              </select>
              <select name="suspendDuration" required>
                <option value="">Select a duration</option>
                <option value="1 day">1 day</option>
                <option value="3 days">3 days</option>
                <option value="1 week">1 week</option>
                <option value="2 weeks">2 weeks</option>
                <option value="1 month">1 month</option>
                <option value="3 months">3 months</option>
              </select>
              <input type="password" name="changekey" maxlength="4" placeholder="Change key" required>
              <input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
              <?php $sessionChangekey = $_SESSION['changekey']; echo "<input type=\"hidden\" name=\"sessionChangekey\" value=\"$sessionChangekey\">"; ?>
            </div>
            <div>
              <input type="submit" id="confirm" name="suspendUser" value="SUSPEND ACCOUNT">
            </div>
          </fieldset>
        </form>
      </div>

      <script>

        function validateSuspendUser() {

          var changekey = document.forms["suspendUserForm"]["changekey"].value,
              sessionChangekey = document.forms["suspendUserForm"]["sessionChangekey"].value;

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

      <div id="unsuspend">
        <h1>Unsuspend <?php echo "$name" ?>'s account</h1>
        <ul class="errors" style="display: none">
          <li class="errorMessage">Please correct the following errors:</li>
          <li class="changekeyError"></li>
        </ul>
        <form id="unsuspendUserForm" action="server/processAdmin.php" method="post" onsubmit="return validateUnsuspendUser()">
          <fieldset id="createStep1">
            <div>
              <input type="password" name="changekey" maxlength="4" placeholder="Change key" required autofocus>
              <input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
              <?php $sessionChangekey = $_SESSION['changekey']; echo "<input type=\"hidden\" name=\"sessionChangekey\" value=\"$sessionChangekey\">"; ?>
            </div>
            <div>
              <input type="submit" id="confirm" name="unsuspendUser" value="UNSUSPEND ACCOUNT">
            </div>
          </fieldset>
        </form>
      </div>

      <script>

        function validateUnsuspendUser() {

          var changekey = document.forms["unsuspendUserForm"]["changekey"].value,
              sessionChangekey = document.forms["unsuspendUserForm"]["sessionChangekey"].value;

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

      <!-- DEACTIVATE USER -->

      <div id="delete">
        <h1>Deactivate <?php echo $name ?>'s account</h1>
        <ul class="errors" style="display: none">
          <li class="errorMessage">Please correct the following errors:</li>
          <li class="changekeyError"></li>
        </ul>
        <form id="deleteUserForm" action="server/processAdmin.php" method="post" onsubmit="return validateDeleteUser()">
          <fieldset>
            <div>
              <input type="password" name="changekey" maxlength="4" placeholder="Change key" required autofocus>
              <input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
              <input type="hidden" name="suspended" value="<?php echo $suspended; ?>">
              <?php $sessionChangekey = $_SESSION['changekey']; echo "<input type=\"hidden\" name=\"sessionChangekey\" value=\"$sessionChangekey\">"; ?>
            </div>
            <div>
              <input type="submit" id="confirm" name="deactivateUser" value="DEACTIVATE ACCOUNT">
            </div>
          </fieldset>
        </form>
      </div>

      <script>

        function validateDeleteUser() {

          var changekey = document.forms["deleteUserForm"]["changekey"].value,
              sessionChangekey = document.forms["deleteUserForm"]["sessionChangekey"].value;

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

      <!-- REACTIVATE USER -->

      <div id="restore">
        <h1>Reactivate <?php echo $name ?>'s account</h1>
        <ul class="errors" style="display: none">
          <li class="errorMessage">Please correct the following errors:</li>
          <li class="changekeyError"></li>
        </ul>
        <form id="reactiveUser" action="server/processAdmin.php" method="post" onsubmit="return validateReactiveUser()">
          <fieldset>
            <div>
              <input type="password" name="changekey" maxlength="4" placeholder="Change key" required autofocus>
              <input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
              <?php $sessionChangekey = $_SESSION['changekey']; echo "<input type=\"hidden\" name=\"sessionChangekey\" value=\"$sessionChangekey\">"; ?>
            </div>
            <div>
              <input type="submit" id="confirm" name="reactivateUser" value="REACTIVATE ACCOUNT">
            </div>
          </fieldset>
        </form>
      </div>

      <script>

        function validateReactiveUser() {

          var changekey = document.forms["reactiveUser"]["changekey"].value,
              sessionChangekey = document.forms["reactiveUser"]["sessionChangekey"].value;

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

        $sql4 = "SELECT * FROM reports INNER JOIN shutuser ON reports.reporter=shutuser.iduser WHERE user=$iduser ORDER BY fixed ASC";
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

          $sql5 = "SELECT fname, surname FROM shutuser WHERE iduser=$iduser";
          $result5 = mysqli_query($conn, $sql5);
          while ($row = mysqli_fetch_assoc($result5)) {
            $ownerName = $row['fname'] . " " . $row['surname'];
            echo "<td>$ownerName</td>";
          }
        }
        mysqli_free_result($result);
        mysqli_close($conn);

        ?>
      </div>

    </div>
  </div>
</div>
</div>
</div>
<?php
echo javascript("admin.js");
echo pageClose();
?>
