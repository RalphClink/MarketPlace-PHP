<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $method = $_SERVER['REQUEST_METHOD'];
    $error = null;

    if ($method == "POST") {
        /* Insert New Business First, so user can be linked to it */
        $businessName = $_POST['businessName'];

        $target_dir = "media/businessLogos/";
        $target_file = $target_dir . basename($_FILES["businessLogo"]["name"]);
        $uploadOk = true;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["businessLogo"]["tmp_name"]);
            if ($check !== false) { 
                $content.= "File is an image - " . $check["mime"] . ".";
                $uploadOk = true;
            } else {
                $content.= "File is not an image";
                $uploadOk = false;
            }
        }

        if ($uploadOk == false) {
            $content.= "File not uploaded";
        } else {
            if (move_uploaded_file($_FILES["businessLogo"]["tmp_name"], $target_file)) {
                $content.= "The file " . htmlspecialchars( basename( $_FILES["businessLogo"]["name"])). " has been uploaded.";
            } else {
                $content.= "Error uploading";
            }
        }


        if (addNewBusiness($businessName, $target_file) == "Business Already Exists") {
            $error = "Business Already Exists";
        } else {
            addNewBusiness($businessName, $target_file);
        }

        $salt = 'muchsaltiness';
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $unhashedPassword = $_POST['hashedPassword'];
        $hashedPassword = hash('sha256', $unhashedPassword.$salt);
        $businessID = getNewBusinessID($businessName);
        

        if (addNewAccount($firstName, $lastName, $email, $hashedPassword, $businessID, 2) == "Email already in database") {
            $error = "Email Already Exists";
        } else {
            addNewAccount($firstName, $lastName, $email, $hashedPassword, $businessID, 2);
            header('Location: main.php');
            exit;
        }
    }

    $createAccount = new HtmlTemplate('createBusinessForm.html');
    $content = $createAccount->getHtml(array());
    if ($error != null) {
        $content = '<h2>' . $error . '</h2>';
    }

    $pg->setTitle('Create Business & Account');
    $pg->setContent($content);
    print $pg->getHtml();
?>