<?php

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $content = "";

    $method = $_SERVER['REQUEST_METHOD'];
    $updateUserID = getFromUrl('userID');

    /* If the user ID is specified in the URL, 
        set the session to be equal to the user ID */
    if (isSpecified($updateUserID)) {
        unset($_SESSION['updateUserID']);
        $_SESSION['updateUserID'] = $updateUserID;
    }
    
    if (ISSET($_SESSION['userID'])) {
        if (getuserType($_SESSION['userID']) == 2) {
            if (ISSET($_SESSION['updateUserID'])) {
                if ($method == "POST") {
                    $db = $pg->getDB();

                    /* Save information from form as variables */
                    $updateUserID = $_SESSION['updateUserID'];
                    $salt = 'muchsaltiness';
                    $firstName = $_POST['firstName'];
                    $lastName = $_POST['lastName'];
                    $email = $_POST['email'];
                    $unhashedPassword = $_POST['hashedPassword'];
                    $hashedPassword = hash('sha256', $unhashedPassword.$salt);

                    /* Execute SQL to update the users account */
                    $sql = "UPDATE businessUsers SET
                            firstName = '$firstName',
                            lastName = '$lastName',
                            email = '$email',
                            hashedPassword = '$hashedPassword'
                            WHERE userID = $updateUserID;";
                    $db->execute($sql);

                    unset($_SESSION['updateUserID']);
                    header("Location: businessPage.php");
                }
            } else {
                header("Location: businessPage.php");
            }
        } else {
            header("Location: main.php");
        }
    } else {
        header("Location: login.php");
    }

    $form = new HtmlTemplate('updateAccountForm.html');
    $content.= $form->getHtml(array());

    $pg->setTitle("Update User");
    $pg->setContent($content);
    print $pg->getHtml();

    // Checks that the URL has a userID in it
    function isSpecified ($var) {
		if ($var==null || trim($var =='')) {
			return false;
		}
		return true;
	}

?>