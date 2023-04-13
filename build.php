<?php
	
    require_once 'siteFunctions/commonFunctions.php';
	require_once 'siteFunctions/masterPage.php';
	require_once 'framework/MySQLDB.php';

	try {
		$db=getNewDatabase();
		$db->execute("DROP TABLE IF EXISTS soldListing, listing, businesses, businessUsers;");
	   
        $db->execute("CREATE TABLE businesses ( 
            businessID INTEGER PRIMARY KEY auto_increment,
            businessName VARCHAR(255),
            businessLogo VARCHAR(255)
        );");
	   		
        $db->execute("CREATE TABLE businessUsers (
            userID INTEGER PRIMARY KEY auto_increment,
            firstName VARCHAR(255),
            lastName VARCHAR(255),
            email VARCHAR(255),
            hashedPassword VARCHAR(255),
            userType TINYINT,
            businessID INT,
            FOREIGN KEY (businessID) REFERENCES businesses(businessID)
        );");
        
        $db->execute("CREATE TABLE listings (
            listingID INTEGER PRIMARY KEY auto_increment,
            listingName VARCHAR(255),
            listingDescription VARCHAR(255),
            listingImage VARCHAR(255),
            listingPrice DECIMAL(10, 2),
            sellerID INTEGER,
            FOREIGN KEY (sellerID) REFERENCES businessUsers(userID)
        );");

        $db->execute("CREATE TABLE soldListings (
            soldListingID INTEGER PRIMARY KEY auto_increment,
            listingName VARCHAR(255),
            listingDescription VARCHAR(255),
            listingImage VARCHAR(255),
            listingPrice DECIMAL(10, 2),
            sellerID INTEGER,
            buyerID INTEGER,
            FOREIGN KEY (sellerID) REFERENCES businessUsers(userID),
            FOREIGN KEY (buyerID) REFERENCES businessUsers(userID)
        );");
			
		$db->execute("INSERT INTO businesses (businessName, businessLogo)
            VALUES ('Sarif Industries', 'media/businesslogos/sarifindustries.jpg'),
                   ('Belltower Associates', NULL)");

    
        $salt = 'muchsaltiness';
        $hashedPassword = hash('sha256', 'password'.$salt);
        $db->execute("INSERT INTO businessUsers (firstName, lastName, email, hashedPassword, userType, businessID)
                    VALUES ('Adam', 'Jensen', 'adamjensen@sarrifindustries.com', '$hashedPassword', 0, 1),
                           ('Francis', 'Pritchard', 'francispritchard@sarrifindustries.com', '$hashedPassword', 1, 1),
                           ('David', 'Sarrif', 'davidsarrif@sarrifindustries.com', '$hashedPassword', 2, 1),
                           ('Lawrence', 'Barrett', 'lawrencebarret@belltower.com', '$hashedPassword', 0, 2),
                           ('Pieter', 'Burke', 'pieterburke@belltower.com', '$hashedPassword', 1, 2),
                           ('Roger', 'John-Ffolkes', 'rogerjohnffolkes@belltower.com', '$hashedPassword', 2, 2);");
    
        
        $db->execute("INSERT INTO listings (listingName, listingDescription, listingImage, listingPrice, sellerID)
                    VALUES  ('Prime Beef Mince', 'Prime Beef Mince, produced in New Zealand', 'media/listingImages/primebeefmince.jpg', 15.00, 2),
                            ('Beef Sausauges', 'Canterbury grown Beef Sausages', 'media/listingimages/beefsausages.jpg', 12.50, 2),
                            ('Filleted Salmon', 'Caught just off Bluff, this Salmon has been de-boned, ready for consumption!', 'media/listingImagesfilletedsalmon.jpg', 60.25, 2),
                            ('Chicken Breast', 'Ethically sourced, cruelty free Chicken Breast', 'media/listingimages/chickenbreast.jpg', 18.75, 2),
                            ('Value Beef Mince', 'Value mince, cheaper than prime beef mince but still very tasty!', 'media/listingimages/valuebeefmince.jpg', 8.50, 2),
                            ('Fresh Hoki', 'Hoki caught off the coast of Australia and imported into New Zealand', 'media/listingimages/freshhoki.jpg', 25.15, 2);");
                            
        $db->execute("INSERT INTO soldListings (listingName, listingDescription, listingImage, listingPrice, sellerID, buyerID)
                    VALUES ('Frozen Carrots', 'Kiwi Grown Carrots, nice and Orange!', 'media/listingimages/frozencarrots.jpg', 8.50, 2, 1),
                           ('Pork Chops', 'Imported Pork Chops, do not ask!', 'media/listingimages/porkchops.jpg', 15.45,  5, 1),
                           ('Potatoes', 'Family grown Potatoes', 'media/listingimages/potatoes.jpg', 17.35, 5, 4),
                           ('Frozen Peas', 'Imported bag of Frozen Peas', 'media/listingimages/frozenpeas.jpg', 5.00, 5, 4);");
                    

        $content = '<p>The Database has Been Created Successfully</p>';

		$pg=new MasterPage();
		$pg->setTitle('Database build/rebuild');
		$pg->setContent($content);
		print $pg->getHtml();
		
	} catch (exception $ex) {
		print $ex;
	}

?>
