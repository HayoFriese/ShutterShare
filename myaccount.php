<?php

include 'server/db_conn.php';

require_once('functions.php');

echo pageIni("My Account - Shuttershare");

$usernameloggedin = $_SESSION['username'];

$userid_loggedin = $_SESSION['iduser'];

if (isset( $_POST['personalDetails'] ) ) {
	
	$usernameloggedin = $_SESSION['username'];

	$userid_loggedin = $_SESSION['iduser'];
	
	//Filter Var
		$fname = filter_has_var(INPUT_POST, 'fname') ? $_POST['fname'] : null;

	    $surname = filter_has_var(INPUT_POST, 'surname') ? $_POST['surname'] : null;

    	$username = filter_has_var(INPUT_POST, 'username') ? $_POST['username']: null;
                    
    	$email = filter_has_var(INPUT_POST, 'email') ? $_POST['email'] : null;

    	$mobileNumber = filter_has_var(INPUT_POST, 'mobileNumber') ? $_POST['mobileNumber']: null;

    	$phoneNumber = filter_has_var(INPUT_POST, 'phoneNumber') ? $_POST['phoneNumber']: null;

    //Trim
	    $fname = trim($fname); 

	    $surname = trim($surname);

	    $username = trim($username);

	    $email = trim($email);

	$sql = "UPDATE shutuser SET username='$username' , fname='$fname', surname='$surname', email='$email', mobile='$mobileNumber', tel='$phoneNumber' WHERE iduser='$userid_loggedin'";

	if (mysqli_query($conn, $sql)) {

		$_SESSION['username'] = $username;

	  	echo nav2();

  		echo nav3(" class=\"active\"", "", "", "");

  		require_once('server/accountDetails.php');

	} else {

	    echo "Error updating record: " . mysqli_error($conn);

	}

} elseif ( isset( $_POST['addressDetails'] ) ) {

		//Filter Var	
			$addline1 = filter_has_var(INPUT_POST, 'addline1') ? $_POST['addline1'] : null;

		    $addline2 = filter_has_var(INPUT_POST, 'addline2') ? $_POST['addline2'] : null;

		    $city = filter_has_var(INPUT_POST, 'city') ? $_POST['city']: null;
		                    
		    $zipcode = filter_has_var(INPUT_POST, 'zipcode') ? $_POST['zipcode'] : null;

		    $region = filter_has_var(INPUT_POST, 'region') ? $_POST['region']: null;

		    $country = filter_has_var(INPUT_POST, 'country') ? $_POST['country']: null;

		    $billingAd = filter_has_var(INPUT_POST, 'billingAd') ? $_POST['billingAd'] : null;

		//Trim
		    $addline1 = trim($addline1);

		    $addline2 = trim($addline2);

		    $city = trim($city);

		    $zipcode = trim($zipcode);

		    $region = trim($region);

		    $country= trim($country);

		//SQL
			$sql = "UPDATE billingaddress SET addline1='$addline1' , addline2='$addline2', city='$city', region='$region', zipcode='$zipcode', country='$country' WHERE idbillingaddress='$billingAd'";
			
			if (mysqli_query($conn, $sql)) {

			  	echo nav2();

		  		echo nav3(" class=\"active\"", "", "", "");

		  		require_once('server/accountDetails.php');

			} else {

				die(mysqli_error($conn));


			}

} elseif ( isset($_POST['paymentDetails']  ) ) {


		//Filter Var	
			$cardtype = filter_has_var(INPUT_POST, 'cardtype') ? $_POST['cardtype'] : null;

		    $cardnum = filter_has_var(INPUT_POST, 'cardnum') ? $_POST['cardnum'] : null;

		    $name = filter_has_var(INPUT_POST, 'name') ? $_POST['name']: null;
		                    
		    $expmonth = filter_has_var(INPUT_POST, 'expmonth') ? $_POST['expmonth'] : null;

		    $expyear = filter_has_var(INPUT_POST, 'expyear') ? $_POST['expyear'] : null;

		    $ccv = filter_has_var(INPUT_POST, 'ccv') ? $_POST['ccv'] : null;

		//Trim
		    $cardtype = trim($cardtype);

		    $name = trim($name);


			$sql = "SELECT paymentDet from shutuser WHERE iduser = '$userid_loggedin'";

	        $result = mysqli_query($conn, $sql);

	        $numb = mysqli_fetch_array($result, MYSQLI_NUM);


	    if($numb[0] == 0){
        
            $paymentsql = "INSERT INTO paymentdetails (cardtype, CCV, cardnum, name, expmonth, expyear ,user)
               
            VALUES ( ?,?,?,?,?,?,?)";

            $paymentstmt = mysqli_prepare($conn, $paymentsql) or die(mysqli_error($conn));
    
            mysqli_stmt_bind_param($paymentstmt, "sssssss", $cardtype, $ccv, $cardnum, $name, $expmonth, $expyear, $userid_loggedin ) or die(mysqli_error($conn));
    
            mysqli_stmt_execute($paymentstmt) or die(mysqli_error($conn));
    
            mysqli_stmt_close($paymentstmt);

            $paymentID = mysqli_insert_id($conn);

            $sql = "UPDATE shutuser SET paymentDet = 1 WHERE iduser ='$userid_loggedin'";
									
			if (mysqli_query($conn, $sql)) {

				echo nav2();

				echo nav3(" class=\"active\"", "", "", "");

				require_once('server/accountDetails.php');

			} else {

				die(mysqli_error($conn));

			}
     
        } else {

			$sql = "UPDATE paymentdetails SET cardtype='$cardtype' , CCV='$ccv', cardnum='$cardnum', name='$name', expmonth='$expmonth', expyear='$expyear' WHERE user='$userid_loggedin'";
							
			if (mysqli_query($conn, $sql)) {

			  	echo nav2();

		  		echo nav3(" class=\"active\"", "", "", "");

		  		require_once('server/accountDetails.php');

			} else {

				die(mysqli_error($conn));

			}


        }
} else {

	if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){

		echo pageIni("Access Denied");

    	echo error();

	} else {
		echo pageIni("My Account - Shuttershare");
	
		echo nav2();

		echo nav3(" class=\"active\"", "", "", "");	

		require_once('server/accountDetails.php');

	}

} 
	echo javascript("account.js");
	echo pageClose();
?>