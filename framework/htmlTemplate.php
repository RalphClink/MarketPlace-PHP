<?php

	class HtmlTemplate {
		var $template;

		function __construct ($filename){
			$this->template = $filename;
		}

		public function getHTML($params){
			$filename ='html/' . $this->template;
			$result='Loading template'.$filename.PHP_EOL;
			try {
				$html=file_get_contents ($filename);
				foreach ($params as $key=>$value) {
					$html = str_replace ('##'.$key.'##',$value,$html); 
				}
				$result= $html;
			} catch (Exception $e) {
				$result .= 'Problem reading template'.PHP_EOL;
				$result .= 'Exception: '.$e.PHP_EOL;
			}
			return $result;
		}
	}
?>
