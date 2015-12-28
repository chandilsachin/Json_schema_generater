<?php
include_once('controller/util.io.php');
$fileName = "schema/jsonSchema.json";


$content = readFileContent($fileName);
$jsonInput = json_decode($content, true);

class FormGenerater {

	var $schemaObject;

	function __construct($jsonObj) {
		$this -> schemaObject = $jsonObj;
	}

	function generateForm($oldJson) {
		return $this -> handleObject("", $this -> schemaObject, $oldJson);
	}

	function convertStr($array) {
		if(count($array) < 1)
			return "";
		$str = $array[0];
		for ($i = 1; $i < count($array); $i++) {
			//print_r($array[$i]);
			$str .= ", " . $array[$i];
		}
		return $str;
	}

	function handleObject($title, $object, $value) {
		$html = "";
		if(!isset($object['properties']))
		{
			echo "Problem: There is an empty object in $title object of behavioral file.";
		}
		$object = $object['properties'];
		foreach ($object as $key => $val) {
			if ($this -> isObject($val)) {
				$html .= $this -> handleObject($key, $val, $value[$key]);
			} else if ($this -> isArray($val)) {
				$html .= $this -> handleArray($key, $val, $value[$key]);
			} else {
				$html .= $this -> handleItem($key,$key, $val, $value[$key]);
			}
		}
		return $this -> createBox($title, $html);
	}

	function handleArray($title, $items, $array) {
		$html = "";
		$items = $items['items'];

		if ($this -> isObject($items)) {
			if(isset($array)){
				for ($i = 0; $i < count($array); $i++) {
					$html .= $this -> handleObject("", $items, $array[$i]);
				}
			}
			else {
				$html .= $this -> handleObject("", $items, $array);
			}

		} else if ($this -> isArray($items)) {

			if ($this -> isItemsArray($items)) {
				for($i = 0; $i < count($array); $i++)
				{
					$html .= $this -> handleArray("item: ".($i+1), $items, $array[$i]);
				}
			} else {

			}
		} else {
			//for($i = 0; $i < count($array);$i++)
			{
				$html .= $this -> handleItem(null,$title, $items, $this->convertStr($array));
			}
			
		}
		return $this -> createBox($title, $html);
	}

	function handleItem($title,$placeHolder, $object, $value) {
		if ($object['type'] == "string") {
			return $this -> createTextBox($title, $placeHolder, $value);
		} else if ($object['type'] == "int") {
			return $this -> createTextBox($title, $placeHolder, $value);
		} else if ($object['type'] == "boolean") {
			return $this -> createTextBox($title, $placeHolder, $value);
		} else {
			return $this -> createTextBox($title, $placeHolder, $value);
		}

	}

	function isItemsString($items) {
		if ($items['type'] === "string")
			return true;
		return false;
	}

	function isItemsArray($items) {
		if ($items['type'] === "array")
			return true;
		return false;
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
					<label class='box-title'>$title</label>
					$content
				</div>";
	}

	function createTextBox($label, $placeHolder, $value) {
		// if (is_array($value))
		// echo "problem is here $label";
		if ($label != null)
			$label = "<label>$label:</label>";
		if(count($value))
		return "<div class=''>
			$label
			<input style='display: inline-block;' class='form-control' type='text' placeholder='$placeHolder' value='$value' />
		</div>";
	}

}

$form = new FormGenerater($jsonInput);

//echo $form->createTextBox("Label", "PlaceHolder");
?>
<html>
	<head>
		<title> Form </title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/com.bbi.io.js"></script>
		<script>
			/*
			 $(document).ready(function() {
			 $('body').on("click", "#btn_fill_data", function() {
			 readFile(document.getElementById("file_old_beh_file").files[0], function(content) {
			 var json = JSON.parse(content);
			 });
			 });
			 });*/

		</script>
		<style>
			div.box {
				margin-left: 20px;
				/*border: solid 1px red;*/
				margin: 10px 5px 5px 20px;
				box-shadow: 0px 0px 1px 1px blue;
				padding: 10px 10px 10px 10px;
			}
		</style>

	</head>
	<body>
		<div class="container">
			<div>
				<form action="formGenerater.php" method="post" enctype="multipart/form-data">
					<label>Select old behavioral file:</label>
					<input type="file" name="file_old_beh_file"/>
					<button id="btn_fill_data" class="btn btn-default">
						Fill data
					</button>
				</form>

			</div>
			<div >

				<?php
				if (isset($_FILES["file_old_beh_file"])) {
					$behavioralFile = $_FILES["file_old_beh_file"];

					$json = json_decode(readFileContent($behavioralFile['tmp_name']), true);
					echo $form -> generateForm($json);
				} else {
					echo $form -> generateForm(null);
				}
				?>
			</div>
		</div>

	</body>
</html>