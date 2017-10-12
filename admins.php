<?php
include 'server/db_conn.php';
require_once('functions_admin.php');
require_once('adminCheckSession.php');
echo pageIni("Admins | Admin - Shuttershare");
echo nav4();
echo nav6("", "", "", " class=\"active\"");

$filter = isset($_GET['filter'])?$_GET['filter']:null;
$iduser = isset($_GET['iduser'])?$_GET['iduser']:null;
$name = isset($_GET['name'])?$_GET['name']:null;
$currentFilter = "";

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

        $masterSession = $_SESSION['master'];

        if ($masterSession == 1) {
          echo "
              <p><a href=\"admins.php\">Admins</a> / </p>

          <div>
            <ul>
              <li><span><a href=\"#\">All</a></span>
                <ul id=\"dropdown\">
                  <div></div>
                  <li id=\"all\"><a href=\"#\">All</a></li>
                  <li id=\"createAdmin\"><a href=\"#\">Create</a></li>";
                  $sqlCheckAdmins = "SELECT * FROM admin WHERE master=0";
                  $resultCheckAdmins = mysqli_query($conn, $sqlCheckAdmins);
                  if (mysqli_num_rows($resultCheckAdmins) != 0) {
                    echo "<li id=\"deleteAdmin\"><a href=\"#\">Deactivate</a></li>";
                  }
                  echo "
                </ul>
              </li>
            </ul>
            <div>
            </div>
          </div>
              ";
        } else {
          echo "<p><a href=\"admins.php\" style='color: #FFAF00; font-weight: 600;'>Admins</a></p>";
        }

        ?>

      </div>
      <div id="box2">
        <div id="admins">
          <table cellspacing="0" cellpadding="0">
            <thead>
            <tr>
              <?php
              echo "<th><a href=\"?sort=firstname&ascdesc=$ascdesc\">First Name</a></th>
                      <th><a href=\"?sort=lastname&ascdesc=$ascdesc\">Last Name</a></th>
                      <th><a href=\"?sort=userkey&ascdesc=$ascdesc\">Admin Key</a></th>";

              if ($masterSession == 1) {
                echo "<th><a href=\"?sort=changekey&ascdesc=$ascdesc\">Change Key</a></th>
                         <th><a href=\"?sort=master&ascdesc=$ascdesc\">Created</a></th>";
              }

              echo "<th><a href=\"?sort=master&ascdesc=$ascdesc\">Type</a></th>
                      <th><a href=\"?sort=email&ascdesc=$ascdesc\">Email</a></th>
                    ";
              ?>
            </tr>
            </thead>
          </table>
          <div class="admin-table-body">
            <table cellspacing="0" cellpadding="0">
              <tbody>
              <?php
              $ascdesc = "";
              $order = isset($_GET['sort'])?$_GET['sort']:'firstname';
              $ascdesc = isset($_GET['ascdesc'])?$_GET['ascdesc']:'DESC';
              switch(strtoupper($ascdesc))
              {
                case 'DESC': $ascdesc = 'ASC'; break;
                case 'ASC': $ascdesc = 'DESC'; break;
                default: $ascdesc = 'ASC'; break;
              }
              $sql = "SELECT idadmin, userkey, changekey, firstname, lastname, email, master, created FROM admin ORDER BY $order $ascdesc";

              $result = mysqli_query($conn, $sql);

              while ($row = mysqli_fetch_assoc($result)) {
                $idadmin = $row['idadmin'];
                $userkey = $row['userkey'];
                $changekey = $row['changekey'];
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $email = $row['email'];
                $master = $row['master'];
                if ($master == 1) {
                  $master = "Master";
                } else {
                  $master = "Standard";
                }
                $created = $row['created'];

                $created = strtotime($created);
                $created = date("j F Y, g:i A", $created);

                echo "
                      <tr>
                      <td>$firstname</td>
                      <td>$lastname</td>
                      <td>$userkey</td>";

                if ($masterSession == 1) {
                  echo "<td>$changekey</td>
                          <td>$created</td>";
                }

                echo "
                      <td>$master</td>
                      <td>$email</td>
                      <input type='hidden' name='idadmin' value='$idadmin'>
                      </tr>
                  ";
              }

              ?>
              </tbody>
            </table>
          </div>
        </div>

        <div id="create">
          <h1>Create a new admin account</h1>
          <ul class="errors" style="display: none">
            <li class="errorMessage">Please correct the following errors:</li>
            <li class="passwordError"></li>
            <li class="secondpassError"></li>
            <li class="changekeyError"></li>
          </ul>
          <form id="createAdmin" action="server/processAdmin.php" method="post" onsubmit="return validateCreate()">
            <fieldset id="createStep1">
              <div>
                <input type="text" name="firstname" placeholder="First name" autofocus required>
                <input type="text" name="lastname" placeholder="Last name" required>
              </div>
              <div>
                <input type="password" name="password" placeholder="First password" required>
                <input type="password" name="passwordConfirm" placeholder="Confirm first password" required>
                <input type="password" name="secondpass" placeholder="Second password" required>
                <input type="password" name="secondpassConfirm" placeholder="Confirm second password" required>
              </div>
              <div>
                <input type="password" name="changekey" maxlength="4" placeholder="Your change key" required>
                <?php $sessionChangekey = $_SESSION['changekey']; echo "<input type=\"hidden\" name=\"sessionChangekey\" value=\"$sessionChangekey\">"; ?>

              </div>
              <div>
                <input type="submit" id="confirm" name="createAdmin" value="CREATE ACCOUNT">
              </div>
            </fieldset>
          </form>
        </div>

        <div id="delete">
          <h1>Deactivate an admin account</h1>
          <ul class="errors" style="display: none">
            <li class="errorMessage">Please correct the following errors:</li>
            <li class="changekeyError"></li>
          </ul>
          <form id="deleteAdminForm" action="server/processAdmin.php" method="post" onsubmit="return validateDelete()">
            <fieldset>
              <div>
                <select name="admin" required>
                  <option value="">Select an admin account</option>
                  <?php
                  $sql = "SELECT idadmin, firstname, lastname FROM admin WHERE master='0' ORDER BY firstname";

                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_assoc($result)) {
                    $idadmin = $row['idadmin'];
                    $name = $row['firstname']." ".$row['lastname'];
                    echo "<option value=\"$idadmin\">$name</option>";
                  }
                  ?>
                </select>
                <input type="password" name="changekey" maxlength="4" placeholder="Change key" required>
                <?php $sessionChangekey = $_SESSION['changekey']; echo "<input type=\"hidden\" name=\"sessionChangekey\" value=\"$sessionChangekey\">"; ?>
              </div>
              <div>
                <input type="submit" id="confirm" name="deleteAdmin" value="DEACTIVATE ACCOUNT">
              </div>
            </fieldset>
          </form>
          <script>

            function validateCreate() {

              var password = document.forms["createAdmin"]["password"].value,
                  passwordConfirm = document.forms["createAdmin"]["passwordConfirm"].value,
                  secondpass = document.forms["createAdmin"]["secondpass"].value,
                  secondpassConfirm = document.forms["createAdmin"]["secondpassConfirm"].value,
                  changekey = document.forms["createAdmin"]["changekey"].value,
                  sessionChangekey = document.forms["createAdmin"]["sessionChangekey"].value;

              if (password !== passwordConfirm) {
                $('.passwordError').html("First passwords do not match");
                $('.passwordError').show();
              } else {
                $('.passwordError').hide();
              }

              if (secondpass !== secondpassConfirm) {
                $('.secondpassError').html("Second passwords do not match");
                $('.secondpassError').show();
              } else {
                $('.secondpassError').hide();
              }

              if (changekey !== sessionChangekey) {
                $('.changekeyError').html("Change key is incorrect");
                $('.changekeyError').show();
              } else {
                $('.changekeyError').hide();
              }

              if (password == passwordConfirm && secondpass == secondpassConfirm && changekey == sessionChangekey) {
                $('.errorMessage').hide();
              }

              if (password !== passwordConfirm || secondpass !== secondpassConfirm || changekey !== sessionChangekey) {
                $('.errors').show();
                $('.errorMessage').show();
                $("#create").animate({ scrollTop: 0 }, "fast");
                return false;
              }

            }

            function validateDelete() {

              var changekey = document.forms["deleteAdminForm"]["changekey"].value,
                  sessionChangekey = document.forms["deleteAdminForm"]["sessionChangekey"].value;

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
      </div>
    </div>
  </div>
</div>
<?php
echo javascript("admin.js");
echo pageClose();
?>
