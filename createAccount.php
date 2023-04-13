<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $method = $_SERVER['REQUEST_METHOD'];
    $error = null;

    if (isset($_SESSION['userID'])) {
        if (getUserType($_SESSION['userID'])) {
            if ($method == "POST") {
                $businessID = getUserBusiness($_SESSION['userID']);
                $salt = 'muchsaltiness';
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $email = $_POST['email'];
                $unhashedPassword = $_POST['hashedPassword'];
                $hashedPassword = hash('sha256', $unhashedPassword.$salt);
                $userType = $_POST['userType'];
        
                if (addNewAccount($firstName, $lastName, $email, $hashedPassword, $businessID, $userType) == "Email already in database") {
                    $error = "Email Already Exists";
                } else {
                    addNewAccount($firstName, $lastName, $email, $hashedPassword, $businessID, $userType);
                    header('Location: businessPage.php');
                    exit;
                }
            }
        }
    }

    if (isset($_SESSION['userID'])) {
        if (getUserType($_SESSION['userID'])) {
            $createAccount = new HtmlTemplate('createAccountForm.html');
            $content = $createAccount->getHtml(array());
            if ($error != null) {
                $content = '<h2>' . $error . '</h2>';
            }

            $pg->setTitle('Create Account');
            $pg->setContent($content);
            print $pg->getHtml();
        } else {
            header("Location: main.php");
        }
    } else {
        header("Location: login.php");
    }


?>