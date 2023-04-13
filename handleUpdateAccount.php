<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $content = "";
    $error = null;

    if (ISSET($_SESSION['userID'])) {
        if (getuserType($_SESSION['userID']) == 2) {
            if (ISSET($_SESION['updateUserID'])) {
                $db = $pg->getDB();
            }
        }
    }

    

