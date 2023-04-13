<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'framework/htmlTable.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $content = "";

    try {
        if (isset($_SESSION['userID'])) {
            $user = $pg->getUser();
            $userID = $user->getUserID();
            $db = $pg->getDB();

            /* Complex Query, joins 3 tables */
            $sql = "SELECT soldListings.soldListingID, soldListings.listingName, soldListings.listingPrice,
                    soldListings.listingImage,
                    CONCAT(businessUsers.firstName, ' ', businessUsers.lastName) AS sellerName,
                    businesses.businessName
                    FROM soldListings INNER JOIN businessUsers ON (soldListings.sellerID = businessUsers.userID)
                    INNER JOIN businesses ON (businessUsers.businessID = businesses.businessID)
                    WHERE soldListings.buyerID = $userID";
            $result = $db->query($sql);
            if ($result!=false) {
                if ($result->size()==0) {
                    $content = "<p>No Listings Found</p>";
                } else {
                $resultCounter = 0;
                $content = "<div id='searchListingResult'>";
                while ($row = $result->fetch()) {
                    // Open a new row container
                    if ($resultCounter % 3 == 0 || $resultCounter == 0) {
                        $content.= "<div id='searchListingResultRow'>";
                        $containerOpenedCounter = 0;
                    }
                    $content.= "<a href=soldListing.php?soldListingID=" . $row['soldListingID'] . ">";
                    $content.= "<div id='searchListingItem'>";
                    $content.= "<img src='" .$row['listingImage'] . "'>";
                    $content.= "<h4>" . $row['listingName'] . "</h4>";
                    $content.= "<p>$" . $row['listingPrice'] . "</p>";
                    $content.= "</div>"; 
                    $content.= "</a>";
                    $resultCounter += 1;
                    // Close a row container
                    if ($resultCounter % 3 == 0) {
                        $content.= "</div>";
                    }
                }
                $content.= "</div>";
                }
            }
        }
    } catch (exception $ex) {
        header('Location: login.php');
    }

    $pg->setTitle("Listings You've Bought");
    $pg->setContent($content);
    print $pg->getHtml();


?>
