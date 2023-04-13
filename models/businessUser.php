<?php

    require_once 'models/entity.php';

    class businessUserModel extends EntityModel {
        
        function __construct($db, $userID) {
            parent::__construct($db, 'businessUsers');
            parent::defineKey('userID', $userID);
            parent::defineField('firstName');
            parent::defineField('lastName');
            parent::defineField('email');
            parent::defineField('hashedPassword');
            parent::defineField('businessID');
            parent::defineField('userType');

            if ($userID != null) {
                parent::load();
            }
        }

        /* Public Setters */
        public function setFirstName($value) {
            parent::setValue('firstName', "'$value'");
        }
        public function setLastName($value) {
            parent::setValue('lastName', "'$value'");
        }
        public function setEmail($value) {
            parent::setValue('email', "'$value'");
        }
        public function setHashedPassword($value) {
            parent::setValue('hashedPassword', "'$value'");
        }
        public function setBusinessID($value) {
            parent::setValue('businessID', "'$value'");
        }
        public function setUserType($value) {
            parent::setValue('userType', "'$value'");
        }

        /* Public Getters */
        function getUserID() {
            return parent::getID();
        }
        public function getFirstName() {
            return parent::getValue('firstName');
        }
        public function getLastName() {
            return parent::getValue('lastName');
        }
        public function getFullName() {
            return (parent::getValue('firstName') . parent::getValue('lastName'));
        }
        public function getEmail() {
            return parent::getValue('email');
        }
        public function getHashedPassword() {
            return parent::getValue('hashedPassword');
        }
        public function getUserType() {
            return parent::getValue('userType');
        }

        /* Helper Functions */
        public function isValidPassword() {
            $hash = "xxx";
            // To do, get hash and compare
            return true;
        }

    }

?>