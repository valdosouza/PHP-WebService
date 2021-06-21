<?php
class xmlBuilder{
	private static $node = null;
	public static $xml;
	private static function createNodes($array, $node_name){
		$xml = '';
		if (is_array($array) || is_object($array)) {
			foreach ($array as $key=>$value) {
				if (is_numeric($key)) {
					$key = $node_name;
				}
				$xml .= '<'.$key.'>'.xmlBuilder::createNodes($value, $node_name).'</'.$key.'>';
			}
		}else{
			$xml = htmlspecialchars($array, ENT_QUOTES, 'ISO-8859-1');
		}
		return $xml;
	}
	public static function createFromArray($array, $node_block='projeto', $node_name='node'){
		
		$xml = '<?xml version="1.0" encoding="ISO-8859-1" ?>';
		$xml .= '<'.$node_block.'>';
		$xml .= xmlBuilder::createNodes($array, $node_name);
		$xml .= '</'.$node_block .'>';
		return $xml;
	}
	public static function transformToArray($xml){
		if(empty($xml)){
			return array();
		}			
		$doc = new DOMDocument();		
		$doc->loadXML($xml);

		return xmlBuilder::DOMNodeToArray($doc->documentElement);
	}
	private static function DOMNodeToArray($node){
		switch ($node->nodeType) {
			case XML_CDATA_SECTION_NODE:
			case XML_TEXT_NODE:
				$output = trim($node->textContent);
			break;
			case XML_ELEMENT_NODE:
				for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
					$child = $node->childNodes->item($i);
					$v = xmlBuilder:: DOMNodeToArray($child);
					if(isset($child->tagName)) {
						$t = $child->tagName;
						if(!isset($output[$t])) {
							$output[$t] = array();
						}
						$output[$t][] = $v;
					}elseif($v) {
						$output = (string) $v;
					}
				}
				if(!isset($output)) {
					$output = "".$node->textContent;
				}
				if(is_array($output)) {
					if($node->attributes->length) {
						$a = array();
						foreach($node->attributes as $attrName => $attrNode) {
							$a[$attrName] = (string) $attrNode->value;
						}
						$output['@attributes'] = $a;
					}
					foreach ($output as $t => $v) {
						if(is_array($v) && count($v)==1 && $t!='@attributes') {
							$output[$t] = $v[0];
						}
					}
				}
				break;
		}
		return $output;
	}
}
?>