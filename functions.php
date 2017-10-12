<?php
/* ============================================================================== */

/* =============================== PAGE SETTINGS ================================ */

/* ============================================================================== */

//ini_set( "session.save_path", "/unn_w13033255/sessionData");
//ini_set( "session.save_path", "/home/unn_w13022053/sessionData");
//ini_set("session.save_path", "/Users/jairajlalli/Desktop/NU Final Year/Professionalism & Web Case Project/newCameraSharingIdea/site/sessionData");
//ini_set( "session.save_path", "/home/unn_w13022053/sessionData");
ini_set("session.save_path", "/home/unn_w13020720/sessionData");
session_start();



function pageIni($title){
    $pageIni = <<<PAGEINI
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="stylesheet" href="resources/css/general.css" type="text/css">
    <link rel="stylesheet" href="resources/css/shuttershare.css" type="text/css">
    <link rel="stylesheet" href="resources/css/fonts.css" type="text/css">
    <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css">
    <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
    <link rel="stylesheet" href="resources/fonts/font-awesome-4.6.3/css/font-awesome.min.css">

		<link rel="icon" href="resources/img/favicon.ico">

		<title>$title</title>
	</head>
	<body>
PAGEINI;
  $pageIni .="\n";
  return $pageIni;
}

function footer(){
  	$footer = <<<footer
  	<footer>
        <div class="wrapper">
          <div class="footer-column-1">
            <ul>
              <li><a href="#"><span>ShutterShare</span></a></li>
              <li><a href="#">About</a></li>
              <li><a href="createAdvert.php">Advertise a Camera</a></li>
              <li><a href="searchResults.php">Search for a Camera</a></li>
              <li><a href="#">Latest Renting Offers</a></li>
            </ul>
          </div>
          <div class="footer-column-2">
            <ul>
              <li><a href="#"><span>Account</span></a></li>
              <li><a href="register.php">Sign Up</a></li>
              <li><a href="login.php">Sign In</a></li>
              <li><a href="forgotPassword.php">Forgot Password</a></li>
              <li><a href="myaccount.php">My Account</a></li>
            </ul>
          </div>
          <div class="footer-column-3">
            <ul>
              <li><a href="#"><span>Legalities</span></a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Terms &amp; Conditions</a></li>
              <li><a href="#">Cookie Policy</a></li>
            </ul>
          </div>
          <div class="footer-bottom">
            <p>&copy; 2017 ShutterShare, All Rights Reserved.</p>
          </div>
        </div>
    </footer>
footer;
	$footer .= "\n";
	return $footer;
}

function javascript($jsfile){
  	$javascript = <<<javascript
  	<script src="server/libs/jquery-2.2.1.js"></script>
  <script src="server/js/$jsfile"></script>
javascript;
	$javascript .= "\n";
	return $javascript;
}

function javaAdvert($jsfile){
  $javascript = <<<javascript
    <script src="server/libs/jquery-2.2.1.js"></script>
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script src="server/js/$jsfile"></script>
javascript;
  $javascript .= "\n";
  return $javascript;
}

function pageClose(){
  	$pageClose = <<<pageClose
  </body>
</html>
pageClose;
	return $pageClose;
}

/* ============================================================================== */

/* ================================ BREADCRUMBS ================================= */

/* ============================================================================== */

function breadcrumb($location, $section, $page){
	$breadcrumb = <<<breadcrumb
				<section class="breadcrumb">
					<p><a href="$location">$section</a> &gt; <span>$page</span></p>
				</section>
				<h1>$page</h1>
breadcrumb;
	$breadcrumb .="\n";
	return $breadcrumb;
}

/* ============================================================================== */

/* ================================ NAVIGATIONS ================================= */

/* ============================================================================== */

//Notifications
function notify($user){
    include("server/db_conn.php");
    $sqlNotification = "SELECT readMark FROM inbox WHERE touser = $user AND readMark = 0 AND folder != 2 AND folder != 0";
    $rNotification = mysqli_query($conn, $sqlNotification) or die(mysqli_error($conn));
    $messNum = mysqli_num_rows($rNotification);

    if($messNum > 0){
      return $messNum;
    } else{
      return false;
    }
}

//Top navigation home
function nav(){
  $nav1 = <<<NAV1
    <nav id="nav-1">
        <div>
          <a href="index.php"><img src="resources/img/logo.svg"></a>
          <div>
          </div>
        </div>
        <div>
          <ul>
            <li><a href="signin.php">SIGN IN</a></li>
            <li>|</li>
            <li><a href="register.php">REGISTER</a></li>
          </ul>
          <div>
          </div>
        </div>
    </nav>
NAV1;
  $nav1 .="\n";

  if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $userid = $_SESSION['iduser'];
    $messageNum = "";
    $alert = "";

    $unreadNum = notify($userid);
    if($unreadNum != false){
      $alert = "<img src=\"resources/img/alert.svg\">";
      $messageNum = "<span>$unreadNum</span>";
    }

    $nav5 = <<<NAV5
      <nav id="nav-5">
        <div>
          <a href="index.php"><img src="resources/img/logo.svg"></a>
          <div>
          </div>
        </div>
        <div>
          <ul>
            <li><a href="#">$username $alert</a>
              <ul>
                <div></div>
                <li><a href="myaccount.php">My Account</a></li>
                <li><a href="bookings.php">My Bookings</a></li>
                <li><a href="myAdvert.php">My Adverts</a></li>
                <li><a href="inbox.php">Inbox $messageNum</a></li>
                <li><a href="">Settings</a></li>
                <li><a href="signout.php">Sign Out</a></li>
              </ul>
            </li>
            <li>|</li>
            <li><div><a href="createAdvert.php">Advertise a Camera</a></div></li>
          </ul>
          <div>
          </div>
        </div>
      </nav>
NAV5;
  $nav5 .="\n";
    return $nav5;
  } 
  else {
    return $nav1;
  }
}

//top navigation user back-end
function nav2(){
  $username = $_SESSION['username'];
  $userid = $_SESSION['iduser'];

  $messageNum = "";
    $alert = "";

    $unreadNum = notify($userid);
    if($unreadNum != false){
      $alert = "<img src=\"resources/img/alert.svg\">";
      $messageNum = "<span>$unreadNum</span>";
    } 

  $nav2 = <<<NAV2
    <nav id="nav-2">
        <div>
            <ul>
            	<li><a href="index.php">$username $alert</a>
                <ul>
                    <div></div>
                    <li><a href="myaccount.php">My Account</a></li>
                    <li><a href="bookings.php">My Bookings</a></li>
                    <li><a href="myAdvert.php">My Adverts</a></li>
                    <li><a href="inbox.php">Inbox$messageNum</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="signout.php">Sign Out</a></li>
                </ul>
            	</li>
            	<li>|</li>
            	<li><div><a href="createAdvert.php">Advertise a Camera</a></div></li>
            </ul>
            <div>
            </div>
        </div>
    </nav>
NAV2;
	$nav2 .="\n";
	return $nav2;
}

//Side Navigation User Back-end
function nav3($acc, $book, $advert, $inbox){
    $nav3 = <<<NAV3
    <nav id="nav-3">
    	<div id="logo">
    	    <a href="index.php"><img alt="Logo" src="resources/img/logo.svg"></a>
    	</div>
    	<ul>
    	    <li $acc><a href="myaccount.php">My Account</a></li>
    	    <li $book><a href="bookings.php">My Bookings</a></li>
    	   	<li $advert><a href="myAdvert.php">My Adverts</a></li>
    	    <li $inbox><a href="inbox.php">Inbox</a></li>
    	    <li><a href="">Settings</a></li>
    	</ul>
    </nav>
NAV3;
	$nav3 .="\n";
	return $nav3;
}

//Admin Navigation
function nav4(){
    $name = $_SESSION['name'];
    $userid = $_SESSION['adminKey'];

    $nav4 = <<<NAV4
    <nav id="nav-4">
    <div>
      <ul>
        <li><a href="#">$name</a>
          <ul>
            <div></div>
            <li><a href="#">Inbox</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="admin_logout.php">Sign Out</a></li>
          </ul>
        </li>
      </ul>
      <div>
      </div>
    </div>
  </nav>
NAV4;
  $nav4 .="\n";
  return $nav4;
}
//Admin Side Navigation
function nav6($adverts, $reports, $users, $admins){
  	$nav6 = <<<NAV6
  	<nav id="nav-3">
      <div id="logo">
        <a href=""><img alt="" src="resources/img/logo.svg"></a>
      </div>
      <ul>
        <li$adverts><a href="adverts.php">Adverts</a></li>
        <li$reports><a href="reports.php">Reports</a></li>
        <li$users><a href="users.php">Users</a></li>
        <li$admins><a href="admins.php">Admins</a></li>
      </ul>
    </nav>
NAV6;
	$nav6 .="\n";
	return $nav6;
}


/* ============================================================================== */

/* ============================== SIGN-IN CONTENT =============================== */

/* ============================================================================== */

function signInStartForm(){
  $username = filter_has_var(INPUT_POST, 'username') ? $_POST['username']: null;

  $passWD = filter_has_var(INPUT_POST, 'password') ? $_POST['password']: null;

  $signInForm = <<<SIGNIN
    <div class="account-side">
      <div class="center">
        <form action="login.php" method="post">
          <div>
            <section class="sign-in-register-title">
                  <h1>Shutter<span id="share">Share</span></h1>
            </section>
              <section class="sign-in-register">
                    <input type="text" name="username" placeholder="Username" value="$username">
                    <input type="password" name="password" placeholder="Password">
              </section>
              <section class="sign-in-register">
                    <input type="submit" value="Sign In" name="signIn">
              </section>
              <section class="register">
                <p><a href="forgotPassword.php">Forgot Password?</a></p>
                <p>Don't have an account? <a href="register.php">Register</a></p>
              </section>
            </div>
          </form>
SIGNIN;
  $signInForm .="\n";
  return $signInForm;
}

function signInForm($errors){

    $username = filter_has_var(INPUT_POST, 'username') ? $_POST['username']: null;

    $passWD = filter_has_var(INPUT_POST, 'password') ? $_POST['password']: null;

    $signInForm = <<<SIGNIN
    <div class="account-side">
      <div class="center">
        <form action="login.php" method="post">
          <div>
            <section class="sign-in-register-title">
                  <h1>Shutter<span id="share">Share</span></h1>

SIGNIN;

    $signInForm .= "<div class='error-container'><p style='color:white; text-align: left; margin-bottom: 15px;'>Please correct the following errors:</p><ul>";

    for ($a=0; $a < count($errors); $a++) {

      $signInForm .= "<li>$errors[$a]</li>\n";
    }

    $signInForm .= "</ul></div>";

    $signInForm .= <<<Part2
            </section>
              <section class="sign-in-register">
                    <input type="text" name="username" placeholder="Username" value="$username">
                    <input type="password" name="password" placeholder="Password">
              </section>
              <section class="sign-in-register">
                    <input type="submit" value="Sign In" name="signIn">
              </section>
              <section class="register">
                <p><a href="forgotPassword.php">Forgot Password?</a></p>
                <p>Don't have an account? <a href="register.php">Register</a></p>
              </section>
            </div>
          </form>
  
Part2;
  $signInForm .="\n";
  return $signInForm;
}

function adminSignInForm($error){
    $adminSignInForm = <<<ADMINSIGNIN
    <div class="admin-login">
    <section>
      <div id="logo">
        <img src="resources/img/logo-text.svg">
        <p>Administrative sign-in</p>
        $error
      </div>
      <form action="server/processLogin.php" method="post">
        <input type="text" name="adminKey" placeholder="Admin key" minlength="8" maxlength="8" pattern="\d*" autofocus required>
        <input type="password" name="firstPassword" placeholder="First password" required>
        <input type="password" name="secondPassword" placeholder="Second password" required>
        <input type="submit" name="signIn"value="SIGN IN">
      </form>
    </section>
  </div>
ADMINSIGNIN;
    $adminSignInForm .="\n";
    return $adminSignInForm;
}

function registerForm($errors){

            $fname = filter_has_var(INPUT_POST, 'fname') ? $_POST['fname'] : null;

            $surname = filter_has_var(INPUT_POST, 'surname') ? $_POST['surname'] : null;

            $username = filter_has_var(INPUT_POST, 'username') ? $_POST['username']: null;
    
            $email = filter_has_var(INPUT_POST, 'email') ? $_POST['email'] : null;
    
            $passWD = filter_has_var(INPUT_POST, 'password') ? $_POST['password']: null;

            $passCon = filter_has_var(INPUT_POST, 'confirmPassword') ? $_POST['confirmPassword']: null;

            $mobileNumber = filter_has_var(INPUT_POST, 'mobileNumber') ? $_POST['mobileNumber']: null;

            $phoneNumber = filter_has_var(INPUT_POST, 'phoneNumber') ? $_POST['phoneNumber']: null;


            $addline1 = filter_has_var(INPUT_POST, 'addline1') ? $_POST['addline1']: null;

            $addline2 = filter_has_var(INPUT_POST, 'addline2') ? $_POST['addline2']: null;

            $city = filter_has_var(INPUT_POST, 'city') ? $_POST['city']: null;

            $zipcode = filter_has_var(INPUT_POST, 'zipcode') ? $_POST['zipcode']: null;

            $region = filter_has_var(INPUT_POST, 'region') ? $_POST['region']: null;

            $country = filter_has_var(INPUT_POST, 'country') ? $_POST['country']: null;


            $question = filter_has_var(INPUT_POST, 'question') ? $_POST['question']: null;

            $answer = filter_has_var(INPUT_POST, 'answer') ? $_POST['answer']: null;

        //Trim Values
            $fname = trim($fname); 
    
            $surname = trim($surname);
    
            $username = trim($username);
    
            $email = trim($email); 
    
            $passWD = trim($passWD);

            $passCon = trim($passCon);
    
            $addline1 = trim($addline1); 
    
            $addline2 = trim($addline2);
    
            $city = trim($city);
    
            $zipcode = trim($zipcode); 
    
            $region = trim($region);
    
            $country = trim($country); 
    
  


    $registerForm = <<<REGISTER
        <div class="account-side">
        <div class="center">
REGISTER;

    $registerForm .= <<<REGISTER
            <form action="login.php" method="post">
                <section class="sign-in-register-title">
                  <h1>Shutter<span id="share">Share</span></h1>
REGISTER;

    $registerForm .= "<div class='error-container'><p style='color:white; text-align: left; margin-bottom: 15px;'>Please correct the following errors:</p><ul>";

    for ($a=0; $a < count($errors); $a++) {
      $registerForm .= "<li style='color:red; margin-bottom: 5px;' class='formError'>$errors[$a]</li>";
    }

    $registerForm .= "</ul></div>";

    $registerForm .= <<<REGISTER
                    <p id="error-tag"></p>
                  </section>
                  <div id="register-part1">
                    <section class="sign-in-register">
                        <input type="text" name="username" placeholder="Username*" value ="$username" required>
                        <input type="text" name="email" placeholder="Email Address*" value ="$email" required>
                        <input type="password" name="password" placeholder="Password*" value ="$passWD" required>
                        <input type="password" name="confirmPassword" placeholder="Confirm Password*" value ="$passCon" required>
                    </section>
                    <section class="sign-in-register">
                        <input type="button" id="step-2" value="Register for Free">
                    </section>
                  <section class="register">
                        <p>Already have an account? <a href="signin.php" >Sign In</a></p>
                    </section>
                </div>
                <div id="register-part2">
                    <section class="sign-in-register">
                        <input type="text" name="fname" placeholder="First Name*" value ="$fname" required>
                        <input type="text" name="surname" placeholder="Last Name*" value ="$surname" required>
                        <input type="text" name="mobileNumber" placeholder="Mobile Number" value ="$mobileNumber">
                        <input type="text" name="phoneNumber" placeholder="Phone Number" value ="$phoneNumber">
                    </section>
                    <section class="sign-in-register">
                        <input type="button" id="step-to-1" value="Previous">
                        <input type="button" id="step-3" value="Next">
                    </section>
                </div>
                <div id="register-part3">
                    <section class="sign-in-register">
                        <input type="text" name="addline1" placeholder="Address Line 1*" value ="$addline1" >
                        <input type="text" name="addline2" placeholder="Address Line 2" value ="$addline2">
                        <input type="text" name="city" placeholder="Town / City*" value ="$city">
                        <input type="text" name="zipcode" placeholder="Postcode*" value ="$zipcode">
                        <input type="text" name="region" placeholder="County*" value ="$region">
                        <input type="text" name="country" placeholder="Country*" value ="$country">
                    </section>
                    <section class="sign-in-register">
                        <input type="button" id="step-to-2" value="Previous">
                        <input type="button" id="step-4" value="next">
                    </section>
                </div>
                 <div id="register-part4">
                    <section class="sign-in-register">
REGISTER;


    $sql = "SELECT idsecurityquestions, question FROM securityquestions";
    include("server/db_conn.php");

    $result = mysqli_query($conn, $sql);
    
    $registerForm .= "<select name='question' required>";      
      $registerForm .="<option value=\"\">-- Select a Security Question* --</option>";
      
      while ($row = mysqli_fetch_assoc($result)) {
        $registerForm .= "<option value='".$row['idsecurityquestions']."'>".$row['question']."</option>";
      }

    $registerForm .= "</select>";

    $registerForm .= <<<REGISTER
        <input type="text" name="answer" placeholder="Answer" required>
                    </section>
                    <section class="sign-in-register">
                        <input type="button" id="step-to-3" value="Previous">
                        <input type="submit" value="Create Account" name="register">
                    </section>
                </div>
            </form>
REGISTER;

  $registerForm .="\n";

  return $registerForm;
}

function homeContent(){

  $homeContent = <<<HOMECONTENT

  <div id="site">
      <header class="header-home">
        <h1>Shutter<span>Share</span></h1>
        <h2>RENT A CAMERA, SAVE MONEY</h2>
      </header>

      <form id="homeSearch" action="searchResults.php" method="post">
        <input type="text" name="searchTerms" placeholder="Search Terms">

        <select id="pickUpDate" name="pickUpDate">
          <option value="">From</option>
          <option value="">14/11/16</option>
          <option value="">15/11/16</option>
          <option value="">16/11/16</option>
        </select>

        <!--<h1> Drop Downs </h1>-->
        <select id="dropOffDate" name="dropOffDate">
          <option value="">Until</option>
          <option value="">24/11/16</option>
          <option value="">25/11/16</option>
          <option value="">26/11/16</option>
        </select>

        <input type="submit" id="searchSubmit" value="SEARCH">
      </form>

      <section class="cameraRentingOffers">
        <h2>LATEST CAMERA RENTING OFFERS</h2>
  
        <article>
          <a href="viewAdvert.php" class="offer-background">
            <p id="offerPrice">£10 <p>/day</p></p>
            <img src="resources/img/d3200.jpg">
            <p id="offerTitle">Nikon D3200</p>
          </a>
        </article>
  
        <article>
          <a href="viewAdvert.php" class="offer-background">
            <p id="offerPrice">£10 <p>/day</p></p>
            <img src="resources/img/d3200.jpg">
            <p id="offerTitle">Nikon D3200</p>
          </a>
        </article>
  
        <article>
          <a href="viewAdvert.php" class="offer-background">
            <p id="offerPrice">£10 <p>/day</p></p>
            <img src="resources/img/d3200.jpg">
            <p id="offerTitle">Nikon D3200</p>
          </a>
        </article>
  
        <article>
          <a href="viewAdvert.php" class="offer-background">
            <p id="offerPrice">£10 <p>/day</p></p>
            <img src="resources/img/d3200.jpg">
            <p id="offerTitle">Nikon D3200</p>
          </a>
        </article>
  
        <article>
          <a href="viewAdvert.php" class="offer-background">
            <p id="offerPrice">£10 <p>/day</p></p>
            <img src="resources/img/d3200.jpg">
            <p id="offerTitle">Nikon D3200</p>
          </a>
        </article>
      </section>
HOMECONTENT;
  $homeContent .="\n";
  return $homeContent;
}

function changePassword() {
  $changePassword = <<<PASSWORD
        <div class="form-background">
          <form id="bookingElements" action="changepassword.php" method="post">
                <input type="password" name="password" placeholder="Old Password">
                <input type="password" name="passwordNew" placeholder="New Password">
                <input type="password" name="passwordNewC" placeholder="Confirm New Password">
                <input type="submit" name="changePassword" value="Edit Password">
PASSWORD;
  $changePassword .="\n";
  return $changePassword;
}

//consider deleting
function resetPassword() {

  $resetPassword = <<<PASSWORD

        <form action='resetpassword.php' method='post'>
          <div>
              <section class='sign-in-register'>
                  <h1>Reset Password $username</h1>
              </section>
        
               <section class='sign-in-register'>
                    <input type='hidden' name='username' value='$username'>
                    <input type='password' name='passwordNew' placeholder='New Password' required>
                    <input type='password' name='passwordNewC' placeholder='Confirm New Password' required>
              </section>
              <section class='sign-in-register'>
                    <input type='submit' value='Reset Password' name='resetPassword'>
              </section>
              <section class='register'>
            </div>
          </form>
PASSWORD;
  $resetPassword .="\n";
  return $resetPassword;
}

function email() {

  $email = <<<EMAIL

         <form action="securityQuestion.php" method="post">
         <section class="sign-in-register-title">
            <h1>Forgot Password</h1>
              <div >
                <p style='color:white; text-align:center; margin-bottom: 15px;'>Please enter your email address associated with your account</p>
              </div>
          </section>
          <div>  
               <section class="sign-in-register">

                    <input type="text" name="email" placeholder="Enter your Email" required>
     
                    <input type="submit" value="Submit" name="forgotPassword">

              </section>
        </div>
      </form>
EMAIL;
  $email .="\n";
  return $email;
}

function successLog($message, $href) {
  $successLog = <<<success
        <div class="book-advert-back">
                <div class="tick">
                    <img src="resources/img/tick.png">
                    <p>$message<p>
                  </div>
                <a id="accountButtonLinkWide" href="$href">BACK</a>
              </div>
success;
  $successLog .="\n";
  return $successLog;
}   

function registererror($errors){
  $errorlist = "";

  if($errors){

    for ($a=0; $a < count($errors); $a++) {
      
      $errorlist .="<p class=\"white\">$errors[$a]</p>";

    } 

    return $errorlist;

  } else{

    return false;
  }

}


/* ============================================================================== */

/* ============================== POP UP FUNCTION =============================== */

/* ============================================================================== */

function newFolder($userid){
  $newFolder = <<<FOLDER
  <div id="popup">
    <form action="server/newFolder.php" id="new-folder" method="post">
      <a id="close-popup" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
      <h2>New Folder</h2>
      <input type="hidden" name="userid" value="$userid" required />
      <input id="folder-name" name="folder-name" placeholder="Folder Name..." type="text" required />
      <input type="submit" id="submit-new-folder" name="submit" value="Create Folder" />
    </form>
  </div>
FOLDER;
  $newFolder .="\n";
  return $newFolder;
}

function reportAdvert($idadvert, $owner, $reporter){
    $newFolder = <<<FOLDER
  <div id="popup" class="issueReport">
    <form action="server/processAdmin.php" id="new-folder" method="post" width="500px">
      <a id="close-reviewad-up" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
      <h2>Report</h2>
      <select name="type" required>
        <option class="defaultOption" value="">What are you reporting?</option>
        <option value="Advert">Advert</option>
        <option value="User">User</option>
      </select>
      <select name="category" required>
        <option class="defaultOption" value="">Select a category</option>
        <option value="Fraud">Fraud</option>
        <option value="Inappropriate content">Inappropriate content</option>
        <option value="Phishing">Phishing</option>
        <option value="Spam">Spam</option>
        <option value="System misuse">System misuse</option>
      </select>
      <textarea name="message" placeholder="Enter a description..." required></textarea>
      <input type='hidden' name='idadvert' value='$idadvert'>
      <input type='hidden' name='user' value='$owner'>
      <input type='hidden' name='reporter' value='$reporter'>
      <input type="submit" id="submit-new-folder" name="issueReport" value="Report" />
    </form>
  </div>
FOLDER;
    $newFolder .="\n";
    return $newFolder;
}

function reportReview($id, $owner, $reporter){
    $newFolder = <<<FOLDER
  <div id="popup" class="issueReportReview">
     <form action="server/processAdmin.php" id="new-folder" method="post" width="500px">
      <a id="close-reviewrep-up" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
      <h2>Report</h2>
      <select name="category" required>
        <option class="defaultOption" value="">Select a category</option>
        <option value="Fraud">Fraud</option>
        <option value="Inappropriate content">Inappropriate content</option>
        <option value="Phishing">Phishing</option>
        <option value="Spam">Spam</option>
        <option value="System misuse">System misuse</option>
      </select>
      <textarea name="message" placeholder="Enter a description..." required></textarea>
      <input type='hidden' name='type' value='Review'>
      <input type='hidden' name='idadvert' value='$id'>
      <input type='hidden' id='subrevid' name='idreview' value=''>
      <input type='hidden' name='user' value='$owner'>
      <input type='hidden' name='reporter' value='$reporter'>
      <input type="submit" id="submit-new-folder" name="issueReportReview" value="Report" />
    </form>
  </div>
FOLDER;
    $newFolder .="\n";
    return $newFolder;
}

function messageAdvert($advertid, $userid, $ownerid, $ownername, $title){
  $form = <<<FOLDER
  <div id="popup" class="messAdvert">
    <form action="server/sendMessage.php" id="new-message" method="post">
      <a id="close-popup" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
      <h2>Send Message to $ownername</h2>
      <input type="hidden" value="$advertid" id="advert_message"/>
      <input type="hidden" name="to_new" value="$ownername" />
      <input type="hidden" name="from_new" value="$userid" required />
      <input id="subject-popup" name="subject_new" placeholder="Subject..." type="text" required />
      <textarea placeholder="Message..." name="body"></textarea>
      <input type="submit" id="submit-new-folder" name="submit" value="Send" />
    </form>
  </div>
FOLDER;
  $form .="\n";
  return $form;
}

function pleaseSignIn(){
  $form = <<<FOLDER
  <div id="popup" class="messAdvert">
    <form>
      <a id="close-popup" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
      <p>Please <a href="signin.php">Sign In</a></p>
      <p>or <a href="register.php">Sign Up</a></p>
    </form>
  </div>
FOLDER;
  $form .="\n";
  return $form;
}

function signInPlease(){
  $form = <<<FOLDER
  <div id="popup" class="issueReport">
    <form>
      <a id="close-report" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
      <p>Please <a href="signin.php">Sign In</a></p>
      <p>or <a href="register.php">Sign Up</a></p>
    </form>
  </div>
FOLDER;
  $form .="\n";
  return $form;
}

?>