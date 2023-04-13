<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $content = "";

    $keywords = getFromUrl('searchListings');

    if (ISSET($_SESSION['userID'])) {
        if (getUserType($_SESSION['userID']) == 0) {
            if (isSpecified($keywords)) {
                $db = $pg->getDB();
                $sql = "SELECT * FROM listings WHERE
                        listingName LIKE '%$keywords%' OR
                        listingDescription LIKE '%$keywords%'
                        ORDER BY listingID;";
                $result = $db->query($sql);
                if ($result != false) {
                    if ($result->size()==0) {
                        $content = "<p>No Listings Found</p>";
                    } else {
                        $resultCounter = 0;
                        $rowDivOpen = false;
        
                        $content = "<div id='searchListingResult'>";
                        while ($row = $result->fetch()) {
                            // Open a new row container
                            if ($resultCounter % 3 == 0 || $resultCounter == 0) {
                                $content.= "<div id='searchListingResultRow'>";
                                $rowDivOpen = true;
                            }
                            $content.= "<a href=listing.php?listingID=" . $row['listingID'] . ">";
                            $content.= "<div id='searchListingItem'>";
                            $content.= "<img src='" . $row['listingImage'] . "'>";
                            $content.= "<h4>" . $row['listingName'] . "</h4>";
                            $content.= "<p>$" . $row['listingPrice'] . "</p>";
                            $content.= "</div>"; 
                            $content.= "</a>";
                            $resultCounter += 1;
                            // Close a row container
                            if ($resultCounter % 3 == 0) {
                                $content.= "</div>";
                                $rowDivOpen = false;
                                
                            }
                        }
                        if ($rowDivOpen == true) {
                            $content.= "</div>";
                        }
                    $content.= "</div>";   
                    }
                }
            }
        } else {
            header("location: main.php");
        }  
    } else {
        header("location: login.php");
    }
    
    
    // Show the search form so the user can do another search if they want
    $form = new HtmlTemplate('searchListings.html');
    $oldValues = array('oldSearch'=>getOldValue($keywords));
    $content.= $form->getHtml($oldValues);

    $pg->setTitle('Listings Search');
    $pg->setContent($content);
    print $pg->getHtml();

    // This checks that the keywords are actually set
    function isSpecified ($var) {
		if ($var==null || trim($var =='')) {
			return false;
		}
		return true;
	}

    // If we have a value return ~ value='xxx' else return blank
	function getOldValue ($val) {
		if ($val==null) {
			return "";
		}
		return ' value="'.$val.'" ';
	}

?>
