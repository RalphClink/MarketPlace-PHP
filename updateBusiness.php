<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $method = $_SERVER['REQUEST_METHOD'];
    $error = null;
    $content = "";

    try {
        $db = $pg->getDB();
        $userID = $pg->getUserID();
        $userType = getUserType($userID);
        $userBusiness = getUserBusiness($userID);

        if ($userType == 2) {
            if ($method == "POST") {
                if (!isset($_POST['businessName']) || !isset($_POST['businessLogo'])) {
                    header("Location: businessPage.php");
                }
                // Save the uploaded image file first
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

                $businessName = $_POST['businessName'];

                $sql = "UPDATE businesses
                        SET businessName = '$businessName',
                            businessLogo = '$target_file'
                        WHERE businessID = $userBusiness";
                $db->execute($sql);
            }
        } else {
            $content = '<p>Only users with an admin account may update their business</p>';
        }
    } catch (exception $ex) {
        header('Location: login.php');
    }


    header('Location: businessPage.php');

?>