<?php 

    require_once 'framework/htmlTemplate.php';
    require_once 'siteFunctions/commonFunctions.php';
    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();
    $method = $_SERVER['REQUEST_METHOD'];
    $error = null;
    $content = "";

    try {
        $userID = $pg->getUserID();
        $userType = getUserType($userID);

        if ($userType == 1) {
            if ($method == "POST") {
                // Save the uploaded image file first
                $target_dir = "media/listingImages/";
                $target_file = $target_dir . basename($_FILES["listingImage"]["name"]);
                $uploadOk = true;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                if (isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["listingImage"]["tmp_name"]);
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
                    if (move_uploaded_file($_FILES["listingImage"]["tmp_name"], $target_file)) {
                        $content.= "The file " . htmlspecialchars( basename( $_FILES["listingImage"]["name"])). " has been uploaded.";
                    } else {
                        $content.= "Error uploading";
                    }
                }

                $listingName = $_POST['listingName'];
                $listingDescription = $_POST['listingDescription'];
                $listingImage = $target_file;
                $listingPrice = $_POST['listingPrice'];
                $sellerID = $userID;
        
                addListing($listingName, $listingDescription, $listingImage, $listingPrice, $sellerID);
                header("location: currentListings.php");
                exit;
            }
        
            $createListing = new HtmlTemplate('createListingForm.html');
            $content = $createListing->getHtml(array());
            if ($error != null) {
                $content = '<h2' . $error . '</h2>';
            }
        } else {
            $content = '<p>Only users with a seller account may create a listing</p>';
        }
    } catch (exception $ex) {
        header('Location: login.php');
    }


    $pg->setTitle('Add Listing');
    $pg->setContent($content);
    print $pg->getHtml();

?>