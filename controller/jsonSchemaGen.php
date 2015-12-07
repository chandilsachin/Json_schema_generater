<?php
class JsonSchemaGenerater {
	var $jsonObject;
	var $schemaObject;
	var $pointer;

	function __construct($jsonObj) {
		$this -> jsonObject = $jsonObj;

	}

	/*
	 Generates and returns json schema
	 */
	function generate() {
		$this -> schemaObject = $this -> envokeProperty($this -> jsonObject);

		return json_encode($this -> schemaObject, JSON_PRETTY_PRINT);
	}

	/*
	 returns schema of given value that can be jsonObject, jsonArray and json key
	 */
	private function envokeProperty($property) {
		$val = null;
		if (is_object($property)) {

			$val = $this -> populateObject($property);
		} else if (is_array($property)) {
			$val = $this -> populateArray($property);
		} else
			$val = $this -> populateKey($property);
		return $val;
	}

	/*
	 Populate json object into json schema
	 */
	function populateObject($object) {

		$val["type"] = "object";
		$val["desc"] = "";
		foreach ($object as $key => $value) {
			$val["properties"][$key] = $this -> envokeProperty($value);
		}
		return $val;
	}

	/*
	 Populate json array into json schema
	 */
	private function populateArray($array) {
		$val["type"] = "array";
		$val["desc"] = "";
		//$this->envokeProperty($object);
		if (count($array) > 0) {
			foreach ($array as $item) {
				$val["items"] = $this -> envokeProperty($item);
			}
		} else {
			$temp['type'] = "undefined";
			$temp['desc'] = "";
			$val["items"] = $temp;
		}

		return $val;
	}

	/*
	 Populate json key into json schema
	 */
	private function populateKey($key) {
		$val["type"] = $this -> getKeyType($key);
		;
		$val["desc"] = "";
		return $val;
	}

	/*
	 returns type of given value
	 */
	private function getKeyType($value) {
		if (is_string($value))
			return "string";
		else if (is_int($value))
			return "int";
		else if (is_bool($value))
			return "boolean";
		else if (is_float($value))
			return "float";
	}

	/**
	 * Indents a flat JSON string to make it more human-readable.
	 *
	 * @param string $json The original JSON string to process.
	 *
	 * @return string Indented version of the original JSON string.
	 */
	function indent($json) {

		$result = '';
		$pos = 0;
		$strLen = strlen($json);
		$indentStr = '';
		$newLine = "";
		$prevChar = '';
		$outOfQuotes = true;

		for ($i = 0; $i <= $strLen; $i++) {

			// Grab the next character in the string.
			$char = substr($json, $i, 1);

			// Are we inside a quoted string?
			if ($char == '"' && $prevChar != '\\') {
				$outOfQuotes = !$outOfQuotes;

				// If this character is the end of an element,
				// output a new line and indent the next line.
			} else if (($char == '}' || $char == ']') && $outOfQuotes) {
				$result .= $newLine;
				$pos--;
				for ($j = 0; $j < $pos; $j++) {
					$result .= $indentStr;
				}
			}

			// Add the character to the result string.
			$result .= $char;

			// If the last character was the beginning of an element,
			// output a new line and indent the next line.
			if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
				$result .= $newLine;
				if ($char == '{' || $char == '[') {
					$pos++;
				}

				for ($j = 0; $j < $pos; $j++) {
					$result .= $indentStr;
				}
			}

			$prevChar = $char;
		}

		return $result;
	}

}
?>