<?php
	require_once 'framework/htmlTemplate.php';
	require_once 'siteFunctions/commonFunctions.php';
	require_once 'models/businessUser.php';

	class MasterPage {
		var $db;
		var $title;
		var $userID;
		var $user;
		var $content;
		
		public function __construct() {
			$this->title='Untitled';
			$this->userID=null;
			$this->user=null;
			$this->userType=null;
			$this->userBusiness=null;
			$this->content="<p>content not yet specified</p>";
			$this->db=getDatabase();
			$this->init();
		}
		
		private function init () {
			session_save_path ('.\sessions');
			session_start();	
			if (isset($_SESSION['userID'])) {
				$this->userID=$_SESSION['userID'];
			}
			if ($this->userID!=null) {
				$this->user=new businessUserModel($this->db, $this->userID);
			}
		}
			
		public function setTitle($title) {
			$this->title = $title;
		}
		
		public function setContent($content) {
			$this->content=$content;
		}

		public function getDB() {
			return $this->db;
		}
		
		public function getUser() {
			return $this->user;
		}

		public function getUserID() {
			return $this->userID;
		}

		public function getHtml() {		
			$pg = new HtmlTemplate('../html/masterPage.html');
			return $pg->getHtml(array(
				'pagename'=>$this->title,
				'navBar'=>$this->getNavBar(),
				'content'=>$this->content));
		}
		
		public function logout() {
			$this->userID=null;
			$this->user=null;
			session_destroy();
		}
		
		private function getNavBar() {
			if ($this->user==null) {
				return '<li><a href="main.php" id="headerNavItem" title="Landing Page">Home </a></li>
						<li><a href="build.php" id="headerNavItem" title="Builds the Database & Loads Test Data">Build DB</a></li>
						<li><a href="createBusiness.php" id="headerNavItem" title="Create Business">Create Business</a></li>
						<li><a href="login.php" id="headerNavItem" title="Login">Login</a></li>';
			} else {
				$userType = getUserType($_SESSION['userID']);
				if ($userType == 0) {
					# Buyer Menu
					return '<li><a href="main.php" id="headerNavItem" title="Landing Page">Home </a></li>
							<li><a href="searchListings.php" id="headerNavItem" title="Search for Listings">Search Listings</a></li>
							<li><a href="allListings.php" id="headerNavItem" title="Show All Listings">All Listings</a></li>
							<li><a href="boughtListings.php" id="headerNavItem" title="Bought Listings">Bought Listings</a></li>
							<li><a href="userPage.php" id="headerNavItem" title="User Page">My Account</a></li>
							<li><a href="logout.php" id="headerNavItem" title="Logout">Logout</a></li>';
				} else if ($userType == 1) {
					# Seller Menu
					return '<li><a href="main.php" id="headerNavItem" title="Landing Page">Home </a></li>
							<li><a href="createListing.php" id="headerNavItem" title="Create a Listing">Create Listing</a></li>
							<li><a href="currentListings.php" id="headerNavItem" title="Current Listings">Current Listings</a></li>
							<li><a href="soldListings.php" id="headerNavItem" title="Sold Listings">Sold Listings</a></li>
							<li><a href="userPage.php" id="headerNavItem" title="User Page">My Account</a></li>
							<li><a href="logout.php" id="headerNavItem" title="Logout">Logout</a></li>';
				} else if ($userType == 2) {
					# Admin Menu
					return '<li><a href="main.php" id="headerNavItem" title="Landing Page">Home </a></li>
							<li><a href="userPage.php" id="headerNavItem" title="User Page">My Account</a></li>
							<li><a href="businessPage.php" id="headerNavItem" title="Business Page, Admins Only">Business Management</a></li>
							<li><a href="logout.php" id="headerNavItem" title="Logout">Logout</a></li>';
				}
			} 
		}
	}

?>

