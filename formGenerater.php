<?php
$fileName = "schema/jsonSchema.json";
$file = fopen($fileName, "r");
$content = fread($file, filesize($fileName));
fclose($file);
$jsonInput = json_decode($content, true);

class FormGenerater {

	var $schemaObject;

	function __construct($jsonObj) {
		$this -> schemaObject = $jsonObj;
	}

	function generateForm() {
		return $this -> handleProperties($this -> schemaObject);
	}

	function handleProperties($object) {
		$htmlStr = "";
		if (!isset($object['properties'])) {
			$htmlStr .= $this->createTextBox("items", "comma saperate values.");
		} else {
			$object = $object['properties'];
			foreach ($object as $key => $value) {
				if ($this -> isObject($value)) {
					$htmlStr .= $this -> createBox($key, $this -> handleProperties($value));
				} else if ($this -> isArray($value)) {
					$htmlStr .= $this -> createBox($key, $this -> handleProperties($value['items']));
					//$htmlStr .= $this->createTextBox($key,$value['desc']);
				} else {
					$htmlStr .= $this -> createTextBox($key, $key);
				}
			}
		}
		

		return $htmlStr;
	}

	function isObject($object) {
		if (is_array($object) && $object['type'] === "object") {

			return true;
		}
		return false;
	}

	function isArray($object) {

		if (is_array($object) && $object['type'] === "array") {
			return true;
		}
		return false;
	}

	function createBox($title, $content) {
		return "<div class='box'>
					<p class='box-title'>$title</p>
					$content
				</div>";
	}

	function createTextBox($label, $placeHolder) {
		return "<div class='box'>
			<label>$label:</label>
			<input class='form-control' type='text' placeholder='$placeHolder' />
		</div>";
	}

}

$form = new FormGenerater($jsonInput);
echo $form -> generateForm();
//echo $form->createTextBox("Label", "PlaceHolder");
?>
<html>
	<head>
		<title> Form </title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<style>
			.box {
				margin-left: 20px;
				border: solid 1px red;
			}
		</style>
	</head>
	<body>
		<div >

		</div>
	</body>
</html>