<?php
include_once ('controller/util.io.php');

function compareJsonFile($file1, $file2) {
	$content1 = readFileContent($file1['tmp_name']);
	$content2 = readFileContent($file2['tmp_name']);

	$jsonObj1 = json_decode($content1, true);
	$jsonObj2 = json_decode($content2, true);

	return traversObj_compare($jsonObj1, 0, "",$jsonObj2);

}

$errorClass = "not-avail";
function traversObj_compare($obj, $level, $prefix, $obj2) {
	$html = "";
	global $errorClass;
	foreach ($obj as $key => $value) {
		if (isset($obj2[$key])) {
			if (is_array($value) && is_array($obj2[$key])) {
				$html .= traversObj_compare($value, $level + 1, $prefix . generateKey($key, $level, ""), $obj2[$key]);
			} else {
				//$html .= $prefix . generateKey($key, $level, "") . "</br>";
			}
		} else {
			$html .= $prefix . generateKey($key, $level, $errorClass) . "</br>";
		}
	}
	return $html;
}

function traversObj($obj, $level, $prefix) {
	$html = "";
	foreach ($obj as $key => $value) {
		if (is_array($value)) {

			$html .= traversObj($value, $level + 1, $prefix . generateKey($key, $level));
		} else {
			$html .= $prefix . generateKey($key, $level) . "</br>";
		}
	}
	return $html;
}

function generateKey($key, $level, $class) {
	return "<span class='entry level$level $class'>$key</span>";
}

class JsonComparator {

}
?>

<html>
	<head>
		<title>Compare Json File</title>
		<style>
			body {
				margin: 0px;
			}
			.entry {
				margin: 3px;
				display: inline-block;
				border: solid 1px black;
			}
			.fileContainer {
				display: inline-block;
				border: solid 1px black;
				width: 49.5%;
			}
			#file1Container {

			}
			#file2Container {
				float: right;
			}
			.not-avail {
				background-color:#00FF00;
				border-radius: 3px;
				padding: 2px;
			}

		</style>
	</head>
	<body >
		<div >
			<div style="border:solid 1px black;">
				<div class="fileContainer" id="file1Container">
					<?php
					if (isset($_FILES['file1'])) {
						$file1 = $_FILES['file1'];

						if (isset($_FILES['file2'])) {
							$file2 = $_FILES['file2'];
							$res = compareJsonFile($file1, $file2);
							if(strlen(trim($res))<1)
								echo "no changes found";
							else {
								echo $res;
							}
						}
					}
					?>
				</div>
				<div class="fileContainer" id="file2Container">
					s
				</div>
			</div>

			<form action="jsonComparator.php" method="post" enctype="multipart/form-data">
				<label>File 1:</label>
				<input type="file" name="file1"/>
				<label>File 2:</label>
				<input type="file" name="file2"/>
				<button>
					Compare
				</button>
			</form>
		</div>
	</body>
</html>