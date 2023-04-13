<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'framework/htmlTable.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $content = "";

    try {
        $db = $pg->getDB();
        $sql = "SELECT * FROM listings ORDER BY listingID;";
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
                    $content.= "<a href=listing.php?listingID=" . $row['listingID'] . ">";
                    $content.= "<div id='searchListingItem'>";
                    $content.= "<img src=" . $row['listingImage'] . "></img>";
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
    } catch (exception $ex) {
        header('Location: login.php');
    }

    $pg->setTitle('All Listings');
    $pg->setContent($content);
    print $pg->getHtml();


?>
