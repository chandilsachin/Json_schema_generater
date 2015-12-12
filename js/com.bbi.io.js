function readFile(filePath,callback)
{
	var reader = new FileReader();
	reader.onload = function(e){
		
		var content = e.target.result;
		callback(content);
	};
	reader.readAsText(filePath);
}


