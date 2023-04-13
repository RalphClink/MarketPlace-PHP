<?php 

    require_once 'framework/MySQLDB.php';

    abstract class EntityModel {
        var $db;			// the database
        var $table;			// the table in the database
        var $primaryKey;	// name of the primary key
        var $fields;		// names of the fields we want
        var $ID;			// the primary key value
        var $data;			// row data
        var $updates;		// data to be changed

        
        function __construct($db, $table) {
            $this->db =$db;
            $this->table = $table;
            $this->fields=array();
            $this->data=array();
            $this->updates=array();
        }

        function defineKey ($name, $value) {
            $this->primaryKey=$name;
            $this->ID=$value;
        }


        function defineField ($name, $format='alpha') {
            $this->fields[$name]=$format;
        }


        public function load() {
	
            $sql='select '.$this->fieldNames().' from '.$this->table.
                 ' where '.$this->primaryKey.'='.$this->ID;
            
            //print $sql;
            
            $result = $this->db->query($sql);
            $row=$result->fetch();
            
            foreach ($this->fields as $field=>$format) {
                $this->data[$field] = $row[$field];	
            }
        }


        public function save() {
            if (needsSave) {
                $sql = "update members ";
                $pfx = 'set';
                foreach ($updates as $field=>$value) {
                    $sql .= $pfx.' '.$field.' = '.
                    $this->getValueString($field,$value);		
                    $pfx=',';
                }
                $sql .= " where memberID = ".$this->memberID;
                
                $db-execute ($sql);
                $this->updates=array();
            }
        }


        public function getID () {
            return $this->ID;
        }

        
        private function getValueString ($field,$value) {
            switch ($this->fields[$field]) {
                case 'number':
                    return $value;
                default:
                    return "'$value'";
            }
        }

        
        private function needsSave() {
            return sizeof($updates) > 0;
        }
        
        
        protected function getValue ($key) {
            return $this->data[$key];
        }
        

        protected function setValue ($key, $value) {
            if (array_key_exists($key,$this->data) ) {
                $oldValue = $this->data[$key];
                if ($value != $oldValue) {
                    $this->updates[$key]=$value;
                }
                $this->data = $value;
            }
        }
    

        private function fieldNames() {
            $result="";
            foreach ($this->fields as $field=>$format) {
                if (!$result=="") {
                    $result .= ", ";
                }
                $result.=$field;
            }
            return $result;
        }

    }

?>