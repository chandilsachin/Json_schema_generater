<?php 
    include 'jsonSchemaGen.php';

	$fileName = "BehavioralFile.json";
	$file = fopen($fileName,"r");
	$content = fread($file,fileSize($fileName));
	fclose($file);
	$generater = new JsonSchemaGenerater(json_decode($content));
	$stringOutput = $generater->indent($generater->generate());
	$outputFile = "jsonSchema.json";
	$out = fopen($outputFile,"w");
	fwrite($out,$stringOutput);
	fclose($out);
	echo "<pre>";
	echo $stringOutput;
	echo "</pre>";
?>