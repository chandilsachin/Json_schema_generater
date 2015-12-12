<!DOCTYPE html><html>
	<?php

		$response_code = isset($_GET["code"]);
		if ($response_code == 1)
			echo "Json schema file has been converted. Now you can generate form <a href=\"formGenerater.php\">here</a>";
		?>
	<head>
		<title> Json Schema Generater </title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<style>
		</style>
	</head>
	<body>
		
		<form role="form" action="upload_file.php" method="post" enctype="multipart/form-data">
			
			<div class="container">
				
				<label  >New Json file :</label>
				<input name="new_json" class=" top-margin form-control" type="file" id="file_new_json" value="Choose new json file" />
				<button style="" class="btn btn-default">
					Upload and generate
				</button>
			</div>
		</form>

	</body>
</html>