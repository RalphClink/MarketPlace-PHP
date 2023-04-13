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
            $sql = "SELECT soldListings.listingName, soldListings.listingPrice, 
                    CONCAT(businessUsers.firstName, ' ', businessUsers.lastName) AS sellerName,
                    businesses.businessName
                    FROM soldListings INNER JOIN businessUsers ON (soldListings.buyerID = businessUsers.userID)
                    INNER JOIN businesses ON (businessUsers.businessID = businesses.businessID)
                    WHERE soldListings.sellerID = $userID";
            $result = $db->query($sql);
            if ($result!=false) {
                if ($result->size()==0) {
                    $content = "<p>No Listings Found</p>";
                } else {
                    $table = new HtmlTable($result);
                    $content = $table->getHtml(array(
                        'listingName'=>'Listing Name',
                        'listingPrice'=>'Listing Price',
                        'businessName'=>'Company',
                        'sellerName'=>'Company Representative'
                    ));
                }
            }
        }
    } catch (exception $ex) {
        header('Location: login.php');
    }

    $pg->setTitle("Listings You've Sold");
    $pg->setContent($content);
    print $pg->getHtml();


?>
