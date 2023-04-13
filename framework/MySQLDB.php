<?php
    /*
    MySQL Database Connection Class
    */

    class MySQL {
        var $host;
        var $dbUser;
        var $dbPass;
        var $dbName;
        var $dbConn;
        var $dbconnectError;


        function __construct($host, $dbUser, $dbPass, $dbName) {
            $this->host   = $host;
            $this->dbUser = $dbUser;
            $this->dbPass = $dbPass;
            $this->dbName = $dbName;
            $this->connectToServer();
        }


        function connectToServer() {
            $this->dbConn = mysqli_connect($this->host, $this->dbUser, $this->dbPass);
            if (!$this->dbConn) {
                trigger_error('could not connect to server' );
                $this->dbconnectError = true;
            } else {
                #echo "<div id='serverConMessage'>Connected to Server Succesfully</div>";
            }
        }

        function selectDatabase() {
            if (!mysqli_select_db($this->dbConn, $this->dbName)) {
                trigger_error('could not select database');  
                $this->dbconnectError = true;                     
            }
        }
        

        function dropDatabase(){
            $sql = "DROP DATABASE IF EXISTS $this->dbName";
            #echo "<div id='buildDBInfo'> $sql </div>";
            if ($this->query($sql)) {
                #echo "<div id='buildDBInfo'> the $this->dbName database was dropped</div>";
            } else {
                #echo "<div id='buildDBInfo'> the $this->dbName database was not dropped</div>";
            }
        }


        function createDatabase() {
            $sql = "CREATE DATABASE IF NOT EXISTS $this->dbName ";
            #echo "<div id='buildDBInfo'> $sql  </div>";
            if ($this->query($sql)) {
                #echo "<div id='buildDBInfo'> the $this->dbName database was created</div>";
            } else {
                #echo "<div id='buildDBInfo'> the $this->dbName database was not created</div>";
            }
        }


        function isError() {
            if ($this->dbconnectError) {
                return true;
            }
            $error = mysqli_error( $this->dbConn );
            if (empty ($error)) {
                return false;
            } else {
                return true;   
            }
        }


        function createTable($table, $sql) {
            $result = $this->query($sql);
            if ($result == True) {
                echo "$table was added<br>";
            } else {
                echo "$table was not added<br>";
            }
        }
        
        function insertRow($sql) {
            $aQResult = $this->query($sql);
            if ($aQResult) {
                print ("the record was inserted<br>") ;
            }
        }

        
        function execute($sql) {
            if (!$queryResource = mysqli_query($this->dbConn, $sql)) {
                throw new exception ( 'Query Failed: ' . mysqli_error ($this->dbConn ) . ' SQL: ' . $sql );
            }
            return new MySQLResult( $this, $queryResource ) ; 
        }
        
            
        function query($sql) {
            mysqli_query( $this->dbConn, "set character_set_results='utf8'"); 
            if (!$queryResource = mysqli_query($this->dbConn, $sql)) {
                trigger_error ('Query Failed: <br>' . mysqli_error($this->dbConn ) . '<br> SQL: ' . $sql);
                return false;
            }
        
            return new MySQLResult( $this, $queryResource ); 
        }
    }


    class MySQLResult {
        var $mysql;
        var $query;

        function __construct(&$mysql, $query) {
            $this->mysql = &$mysql;
            $this->query = $query;
        }

        function size() {
            return mysqli_num_rows($this->query);
        }

        function fetch() {
            if ($row = mysqli_fetch_array($this->query, MYSQLI_ASSOC)) {
                return $row;
            } else if ( $this->size() > 0) {
                mysqli_data_seek($this->query, 0);
                return false;
            } else {
                return false;
            }         
        }

        function insertID() {
            /**
             * returns the ID of the last row inserted
            * @return  int
            * @access  public
            */
            return mysqli_insert_id($this->mysql->dbConn);
        }


        function isError() {
            return $this->mysql->isError();
        }
    }