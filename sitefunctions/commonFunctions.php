<?php
	include_once ("framework/MySQLDB.php");

	function getConnection() {
		$host = 'localhost' ;
		$dbUser ='root';
		$dbPass ='';
		$dbName ='TheAgora';

		// create a new database object and connect to server
		$db = new MySQL($host, $dbUser, $dbPass, $dbName);
		return $db; 	
	}

	function getNewDatabase() {
		// create a new database object and connect to server
		$db = getConnection();
		//  drop the database and then create it again
		try {
			$db->dropDatabase();
		} catch (exception $ex) {
		}
		$db->createDatabase();
		// select the database
		$db->selectDatabase();
		return $db;
	}

	function getDatabase() {
		$db = getConnection();
		$db->selectDatabase();
		return $db;
	}

	// gets a parameter from the URL, or null if not specified
	function getFromURL ($key) {
		if (isset($_GET[$key])) {
			return $_GET[$key];
		}
		return null;
	}

	function sqlSafe ($input) {
		$link = mysqli_connect('localhost', 'root', '', 'TheAgora');
		return mysqli_real_escape_string($link, stripslashes($input));
	}

	
	function getUserID($email, $password) {
		$salt = 'muchsaltiness';
		$email = sqlSafe($email);
		$password = sqlSafe($password);
		$tempHash = hash('sha256', $password.$salt);
		$db = getDatabase();
		$sql = "SELECT userID, hashedPassword FROM businessUsers WHERE email='$email';";

		$result=$db->query($sql);
		if ($result->size() == 1) {
			$row = $result->fetch();
			$hash = $row['hashedPassword'];
			$userID = $row['userID'];
			if ($tempHash == $hash) {
				return $userID;
			}
			if ($hash==null||$hash=="") {
				$result=$db->query("UPDATE businessUsers SET hashedPassword='$tempHash' WHERE userID=$userID");
				return $userID;
			}
		}
	}


	function getUserType($userID) {
		$db = getDatabase();
		$sql = "SELECT userType FROM businessUsers WHERE userID = $userID";
		$result = $db->query($sql);

		if ($result->size() == 1) {
			$row = $result->fetch();
			return $row['userType'];
		}
	}

	function getUserBusiness($userID) {
		$db = getDatabase();
		$sql = "SELECT businessID FROM businessUsers WHERE userID = $userID";
		$result = $db->query($sql);

		if ($result->size() == 1) {
			$row = $result->fetch();
			return $row['businessID'];
		}
	}

	function getUserBusinessName($userID) {
		$db = getDatabase();
		$sql = "SELECT businessName
				FROM businesses INNER JOIN businessUsers 
				ON (businesses.businessID = businessUsers.businessID)
				WHERE userID = $userID";
		$result = $db->query($sql);

		if ($result->size() == 1) {
			$row = $result->fetch();
			return $row['businessName'];
		}
	}

	function addNewAccount($firstName, $lastName, $email, $hashedPassword, $businessID, $userType) {
		$db = getDatabase();
		$sql = "SELECT userID FROM businessUsers WHERE email = '$email';";
		$result = $db->query($sql);
		if ($result->size() > 0) {
			return "Email already in database";
		} else {
			# Insert account into businessUsers table
			$sql = "INSERT INTO businessUsers (firstName, lastName, email, hashedPassword, 
			businessID, userType) VALUES ('$firstName', '$lastName', '$email', '$hashedPassword', 
			$businessID, $userType);";
			$db->execute($sql);
		}
	}

	function addNewBusiness($businessName, $target_file) {
		$db = getDatabase();
		$sql = "SELECT businessName FROM businesses WHERE businessName = '$businessName';";
		$result = $db->query($sql);
		if ($result->size() > 0) {
			return "Business Already Exists";
		} else {
			$sql = "INSERT INTO businesses (businessName, businessLogo)
			VALUES ('$businessName', '$target_file');";
			$db->execute($sql);
		}		
	}

	function getNewBusinessID($businessName) {
		$db = getDatabase();
		$sql = "SELECT MAX(businessID) AS businessID FROM businesses";
		$result = $db->query($sql);
		$row = $result->fetch();
		return $row['businessID'];
	}

	function addListing($listingName, $listingDescription, $listingImage, $listingPrice, $sellerID) {
		$db = getDatabase();
		$sql = "INSERT INTO listings (listingName, listingDescription, listingImage, listingPrice, sellerID)
				VALUES ('$listingName', '$listingDescription', '$listingImage', $listingPrice, $sellerID);";
		$db->execute($sql);
	}

	function isLoggedIn() {
		if (isset($_SESSION['userID'])) {
			return true;
		} else {
			return false;
		}
	}
 
?>
