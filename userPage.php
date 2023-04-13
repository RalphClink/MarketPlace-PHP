<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'framework/htmlTable.php';
    require_once 'models/businessUser.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    try {
        $pg = new MasterPage();
        if (isset($_SESSION['userID'])) {
            $user = $pg->getUser();
            $userID = $user->getUserID();
            $db = $pg->getDB();
            $content = '';
    
            $sql = "SELECT userID, firstName, lastName, email, userType, businessName
                    FROM businessUsers INNER JOIN businesses 
                    ON businessUsers.businessID = businesses.businessID
                    WHERE userID = $userID";
            $userInfo = $db->query($sql);
            
            $row = $userInfo->fetch();
            $content.= '<p><strong>Name:</strong> ' . $row['firstName'] . ' ' . $row['lastName'];
            $content.= '<p><strong>Email:</strong> ' . $row['email'];
            // 0 = Buyer | 1 = Seller | 2 = Admin
            switch($row['userType']) {
                case 0:
                    $content.= '<p><strong>User Type:</strong> Buyer</p>'; 
                    break;
                case 1:
                    $content.= '<p><strong>User Type:</strong> Seller</p>';
                    break;
                case 2:
                    $content.='<p><strong>User Type:</strong> Admin</p>';
                    break;
            }
            $content.= '<p><strong>Business: </strong>' . $row['businessName']; 
                
            $pg->setTitle('Your Account');
            $pg->setContent($content);
            print $pg->getHTML();
        } else {
            header('Location: login.php');
        }
    } catch (exception $ex) {
        header('Location: login.php');
    }

