<?php

	require_once ("framework/MySQLDB.php");
	/*
		parameters are template=>title
	*/

	class HtmlTable {
		var $results;
		var $title = null;
	

		function __construct ( $resultSet )	{
			$this->results=$resultSet;
		}


		public function setTitle ($theTitle) {
			$this->title = $theTitle;
		}

		
		// Create an html table from the result set
		public function getHTML($params) {
			$html='<table'.(($this->title==null) ? '':" title=\"$this->title\"").'>';
			
			$html .= $this->headerRow($params);
			$rowClass="odd";  // odd if odd numbered row, else even
			while ($row = $this->results->fetch() ) {
				$html .= $this->detailRow ($params, $row, $rowClass);
				$rowClass =($rowClass == 'odd') ? 'even' : 'odd';
			}
			
			return $html.'</table>'.PHP_EOL ;
		}

		
		// make an HTML header row with the headings
		private function headerRow ($params) {		
			$ans = "<tr>".PHP_EOL;
			foreach ($params as $key=>$value) {
				$ans .="<th>$value</th>".PHP_EOL;
			}
			return $ans.'</tr>'.PHP_EOL;;
		}
		

		// make an HTML table row for each database row
		private function detailRow ($params, $row, $rowClass) {
			$ans ='<tr class="'.$rowClass.'">'.PHP_EOL;
			foreach ($params as $key=>$value) {
				$ans .=$this->applyTemplate($row, $key);		
			}
			$ans .= "</tr>".PHP_EOL; 
			return $ans;
		}
	

		private function applyTemplate ($row, $template) {
			//print "<!-- Template found: $template -->";
			$start=strpos($template,'<<');	// get first << (if any)
			// Shortform, no <<, just fieldname
			if ($start===false) {
				return '<td class="'.$template.'">'.$row[$template].'</td>'.PHP_EOL;
			}
			
			$work=$template;
			$class='tableCell';
			while ($start!==false) {	// note use of not identical (!==)
				$start+=2;  // move to first character after <<
				$endAt=strpos($work,'>>',$start);  // get first >> after start of field
				if ($endAt===false) {
					$start=false; // there isn't one - format error
				} else {
					$size=$endAt-$start;  // how many characters in the fieldname?
					$source=substr($work,$start,$size); // get the field name
					if (strpos($source,'|')==false) {
						$class=$source;
						$dbValue = $row[$source];
					} else {
						$parse = explode('|',$source); // parse $source 
						$field=$parse[0];	// first part is db field	
						$class=$field;
						$dbValue=$row[$field]; //get value from db row to substitute
						if (sizeof($parse)> 1) {
							$extra=$parse[1];	// second part is format
							$dbValue=$this->getFormattedField($dbValue, $extra);
						}
					}
					// replace placeholder with value from DB 
					$work = str_replace('<<' . $source . '>>' ,$dbValue,$work);
				}
			$start=strpos($work,'<<',$start);	// look for next field
			// and repeat until all substitutions are made
			}
			return '<td class="'.$class.'">'.$work.'</td>'.PHP_EOL;
		}
	
		//	we have to format $field as per format
		private function getFormattedField ($field, $format) {
			//print "<!-- Params are $field,$format -->";

			switch ($format) {
				case 'currency' :
					return '$'.number_format ($field,2);
				case 'integer'  :
					return number_format ($field);
				case 'decimal'  :
					return number_format ($field,2);	
				case 'date'     :
					$d=strtotime($field);
					return date('d-M-Y',$d); 
				case 'sdate'     :
					$d=strtotime($field);
					return date('d-M',$d); 
					
				default:
					return $field;
			}
		}	
	}
	
?>
