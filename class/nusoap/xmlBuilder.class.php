<?php
class xmlBuilder{
	protected $lastArray = array();
	protected $lastXml = null;
	private $node = null;
	public $xml;
	
	public function __construct(){

	}
	private function setLastArray($array){
		if(is_array($array)){
			$this->lastArray = $array;
		}
	}
	public function getLastArray(){
		return $this->lastArray;
	}
	private function setLastXml($xml){
		$this->lastXml = $xml;
	}
	public function getLastXml(){
		return $this->lastXml;
	}
	private function createNodes($array, $node_name){
		$xml = '';
		if (is_array($array) || is_object($array)) {
			foreach ($array as $key=>$value) {
				if (is_numeric($key)) {
					$key = $node_name;
				}
				$xml .= '<'.$key.'>'.$this->createNodes($value, $node_name).'</'.$key.'>';
			}
		}else{
			$xml = htmlspecialchars($array, 0, "UTF-8");
		}
		return $xml;
	}
	public function createFromArray($array, $node_block='projeto', $node_name='node'){
		$this->setLastArray($array);
		$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
		$xml .= '<'.$node_block.'>';
		$xml .= $this->createNodes($array, $node_name);
		$xml .= '</'.$node_block .'>';
		$this->setLastXml($xml);
		return $xml;
	}
	public function transformToArray($xml){
		$this->xml = $xml;

		$doc = new DOMDocument();
		$doc->loadXML($xml);
		return $this->DOMNodeToArray($doc->documentElement);
	}
	private function DOMNodeToArray($node){
		switch ($node->nodeType) {
			case XML_CDATA_SECTION_NODE:
			case XML_TEXT_NODE:
				$output = trim($node->textContent);
			break;
			case XML_ELEMENT_NODE:
				for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
					$child = $node->childNodes->item($i);
					$v = $this->DOMNodeToArray($child);
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
					$output = null;
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