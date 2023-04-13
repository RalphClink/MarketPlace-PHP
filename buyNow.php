<?php

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $listingID = getFromUrl('listingID');
    $content = "";

    //if (ISSET($_SESSION['userID'])) {
        //if (getUserType($_SESSION['userID']) == 0) {
            if (isSpecified($listingID)) {
                $db = $pg->getDB();
                $userID = $_SESSION['userID'];
                
                // Get current listing info
                $sql = "SELECT * FROM listings WHERE listingID = $listingID";
                $result = $db->query($sql);
                if ($result->size() == 1) {
                    $row = $result->fetch();
                    $listingName = $row['listingName'];
                    $listingDescription = $row['listingDescription'];
                    $listingImage = $row['listingImage'];
                    $listingPrice = $row['listingPrice'];
                    $sellerID = $row['sellerID'];
                }

                // Insert info into sold listing table
                $sql = "INSERT INTO soldListings 
                        (listingName, listingDescription, listingImage, listingPrice, sellerID, buyerID)
                        VALUES ('$listingName', '$listingDescription', '$listingImage', $listingPrice,
                        $sellerID, $userID);";
                $db->execute($sql);

                // Drop row from listing table
                $sql = "DELETE FROM listings WHERE listingID = $listingID";
                $db->execute($sql);

                header("Location: boughtListings.php");
            } else {
                header("Location: main.php");
            }
    /*    } else {
            header("Location: main.php");
        }
    } else {
        header("Location: login.php");
    } */

    /* Checks that the listing ID had been passed into the URL */
    function isSpecified ($var) {
		if ($var==null || trim($var =='')) {
			return false;
		}
		return true;
	}



?>