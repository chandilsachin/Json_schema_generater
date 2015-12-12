<?php
include 'controller/jsonSchemaGen.php';

function uploadFile($source, $target) {
	$file = fopen($source["tmp_name"], 'r');
	$content = fread($file, $source['size']);
	fclose($file);

	$outFile = fopen($target, "w");
	fwrite($outFile, $content);
	fclose($outFile);
	return $content;
}

$inFile = $_FILES['new_json'];
$content = uploadFile($inFile, "schema/".$inFile['name']);
// Generate Json Schema file
$generater = new JsonSchemaGenerater(json_decode($content));
$stringOutput = $generater -> indent($generater -> generate());
$outputFile = "schema/jsonSchema.json";
$out = fopen($outputFile, "w");
fwrite($out, $stringOutput);
fclose($out);

print "Uploaded!";
header('Location: generateSchema.php?code=1');
?>