<?php
include 'controller/jsonSchemaGen.php';

$inFile = $_GET['new_json'];
$file = fopen($inFile, 'r');
$content = fread($file, filesize($inFile));
fclose($file);

$outFile = "schema/".$inFile;
$outFile = fopen($outFile, "w");
fwrite($outFile, $content);
fclose($outFile);

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