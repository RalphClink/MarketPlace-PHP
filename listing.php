<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $listingID = getFromUrl('listingID');
    $content = "";
    $tempTitle = "";

    if (isSpecified($listingID)) {
        $db = $pg->getDB();
        $sql = "SELECT * FROM listings WHERE listingID = $listingID";
        $result = $db->query($sql);

        if ($result != false) {
            if ($result->size() == 0) {
                $content = "<p>Invalid Listing ID</p>";
            } else {
                // Listing info + buy now button goes here
                while ($row = $result->fetch()) {
                    $tempTitle = $row['listingName'];
                    $content = "<div id='listingResult'>";
                    $content.= "<div id='listingLeftSide'>";
                    $content.= "<p>" . $row['listingDescription'] . "</p>";
                    $content.= "<strong><p>$" . $row['listingPrice'] . "</p></strong>";
                    if (getUserType($_SESSION['userID']) == 0) {
                        $content.= "<a href=buyNow.php?listingID=" . $listingID . ">BUY NOW</a>";
                    }
                    $content.= "</div>";
                    $content.= "<div id='listingRightSide'>";
                    $content.= "<img src=" . $row['listingImage'] . "></img>";
                    $content.= "</div>";
                }
            }
        }
    }

    $pg->setTitle($tempTitle);
    $pg->setContent($content);
    print $pg->getHtml();


    /* Checks that the listing ID had been passed into the URL */
    function isSpecified ($var) {
		if ($var==null || trim($var =='')) {
			return false;
		}
		return true;
	}

?>