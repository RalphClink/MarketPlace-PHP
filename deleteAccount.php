<?php

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $content = "";

    $deleteUserID = getFromUrl('userID');

    try {
        if (ISSET($_SESSION['userID'])) {
            if (getUserType($_SESSION['userID']) == 2) {
                if (isSpecified($deleteUserID)) {
                    $db = $pg->getDB();
    
                    $sql = "DELETE FROM businessUsers
                            WHERE userID = $deleteUserID;";
                    $db->execute($sql);
    
                    header("Location: businessPage.php");
                } else {
                    header("Location: businessPage.php");
                }
            } else {
                header("Location: main.php");
            }
        } else {
            header("Location: login.php");
        }
    } catch (exception $ex) {
        if (str_contains($ex, 'parent row')) {
            $content.= "User currently has listings bought or active, cannot delete";
        }

        $pg->setTitle('Error Deleting user');
        $pg->setContent($content);
        print $pg->getHtml();
    }
    

    function isSpecified ($var) {
		if ($var==null || trim($var =='')) {
			return false;
		}
		return true;
	}



?>