<?php

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $method = $_SERVER['REQUEST_METHOD'];
    $error = null;
    $userID = null;

    if ($method == 'POST') {
        $email = $_POST['email'];
        $hashedPassword = $_POST['hashedPassword'];

        $userID = getUserID($email, $hashedPassword);
        if ($userID == null) {
            $error = 'Invalid Login Credentials';
        } else {
            $_SESSION['userID'] = $userID;
            #$content = $pg->user->getUserID();
            $_SESSION['accountType'] = getUserType($userID);
            header('Location: main.php');
            exit;
        }
    }

    $login = new HtmlTemplate('loginForm.html');
    $content = $login->getHtml(array());
    if ($error != null) {
        $content = '<h2>' . $error . '</h2>';
    }

    $pg->setTitle('User Login');
    $pg->setContent($content);
    print $pg->getHtml();

?>