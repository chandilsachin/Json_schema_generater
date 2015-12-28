<?PHP
	function readFileContent($fileName) {
	$file = fopen($fileName, "r");
	$content = fread($file, filesize($fileName));
	fclose($file);
	return $content;
}
 ?>