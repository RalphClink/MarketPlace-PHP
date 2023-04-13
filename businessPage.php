<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'framework/htmlTable.php';
    require_once 'models/businessUser.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $content = '';
    try {
        $pg = new MasterPage();
        $user = $pg->getUser();
        $userID = $pg->getUserID();
        $userType = getUserType($userID);
        $userBusiness = getUserBusiness($userID); 
        $db = $pg->getDB();

        if ($userType == 2) {
            // Get and display business logo first
            $sql = "SELECT businesses.businessLogo FROM businesses
                    INNER JOIN businessUsers ON (businesses.businessID = businessusers.businessID)
                    WHERE businessUsers.userID = $userID;";
            $result = $db->query($sql);
            if ($result->size() == 1) {
                $row = $result->fetch();
                $businessLogo = $row['businessLogo'];
            }

            $content.= "<div id='businessLogoContainer'><img src='$businessLogo'></img></div>";
                            
            $sql = "SELECT userID AS UserID, CONCAT(firstName, ' ', lastName) AS 'Full Name', email AS Email,
            CASE 
                WHEN userType = 0 THEN 'Buyer'
                WHEN userType = 1 THEN 'Seller'
                WHEN userType = 2 THEN 'Admin' END userTypeString, businessName AS Business
            FROM businessUsers INNER JOIN businesses
            ON businessUsers.businessID = businesses.businessID
            WHERE businessUsers.businessID = $userBusiness AND userID != $userID";

            $result = $db->query($sql);

            if ($result->size() > 0) {
                $content.='<p>Users Linked to Your Business</p>';
                $content.= "<strong><a id='addAccountLink' href='createAccount.php'>Add Account</a></strong>";
                $table = new HtmlTable($result);
                $content.= $table->getHtml(array(
                    'UserID'=>'UserID',
                    'Full Name'=>'Full Name',
                    'Email'=>'Email',
                    'userTypeString'=>'User Type',
                    '<a href="updateAccount.php?userID=<<UserID>>">Update</a>'=>'Update',
                    '<a href="deleteAccount.php?userID=<<UserID>>">Delete</a>'=>'Delete'
                ));
            } else {
                $content.='<p>You Currently Have No Users Linked to your Business</p>';
                $content.= "<strong><a id='addAccountLink' href='createAccount.php'>Add Account</a></strong>";
            }

        } else {
            $content ='<p>Only admins can view this page</p>';
        }

        $content.= "<div id='updateBusiness'><strong><p>Update your Business Info Below</p></strong>";
        $update = new HtmlTemplate('updateBusinessForm.html');
        $content.= $update->getHtml(array());
        $content.= "</div>";

        $pg->setTitle(getUserBusinessName($userID));
        $pg->setContent($content);
        print $pg->getHtml();

    } catch (exception $ex) {
        header('Location: login.php');
    }