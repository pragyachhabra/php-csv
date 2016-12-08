<?php
echo "<html><body><table>\n\n";

//fxReadCSVFile("example.csv");
//fxWriteCSVFile("example.csv","test1,test2,test3,test4,test5,test6,test7,test8,test9,test10,test11,test12,test13,test14,test15,test16,test17,test18,test19,test20,test21,test22,test23,test24,test25,test26,test27,test28,test29,test30,test31,test32,test33,test34,test35");
//fxGetValue("example.csv",2,"boxTextValueExtra");
//fxSetValue("example.csv",2,"boxTextValueExtra","Pragya");
//fxFindRows("example.csv","boxTextValueExtra","test25");
//fxGetValues("example.csv","boxTextValueExtra");
if(!empty($_POST)) {
    $csvName = $_POST['csvNamefxReadCSVFile'];
	$csvNameWriteCSV = $_POST['csvNamefxWriteCSVFile'];
	$datacsvNameWriteCSV = $_POST['datacsvNamefxWriteCSVFile'];
	$csvNameGetValue = $_POST['csvNamefxGetValue'];
	$rowNumGetValue = $_POST['rowNumfxGetValue'];
	$colNameGetValue = $_POST['colNamefxGetValue'];
	$csvNameGetValues = $_POST['csvNamefxGetValues'];
	$colNameGetValues = $_POST['colNamefxGetValues'];
	$csvNameFindRows = $_POST['csvNamefxFindRows'];
	$colNameFindRows = $_POST['colNamefxFindRows'];
	$colValueFindRows = $_POST['colValuefxFindRows'];
	
	if(!empty($csvName)) {
		fxReadCSVFile($csvName);
	}
	else if (!empty($csvNameWriteCSV) AND !empty($datacsvNameWriteCSV)){
		fxWriteCSVFile($csvNameWriteCSV,$datacsvNameWriteCSV);
	}
	else if (!empty($csvNameGetValue) AND !empty($rowNumGetValue) AND !empty($colNameGetValue)){
		fxGetValue($csvNameGetValue,$rowNumGetValue,$colNameGetValue);
	}
	else if (!empty($csvNameGetValues) AND !empty($colNameGetValues)){
		fxGetValues($csvNameGetValues,$colNameGetValues);
	}
	else if (!empty($csvNameFindRows) AND !empty($colNameFindRows)  AND !empty($colValueFindRows)){
		fxFindRows($csvNameFindRows,$colNameFindRows,$colValueFindRows);
	}
}
function getColumnNum($filepath,$columnName) {
	$file = fopen($filepath, 'r');
	$data = fgetcsv($file);
	foreach ($data as $key => $value) {
		if ($value == $columnName) {
			return($key);
		}
	}
	fclose($file);
}
function fxReadCSVFile($filepath) {
    $f = fopen($filepath, "r");
	while (($line = fgetcsv($f)) !== false) {
			echo "<tr>";
			foreach ($line as $cell) {
					echo "<td>" . htmlspecialchars($cell) . "</td>";
			}
			echo "</tr>\n";
	}
	fclose($f);
}

function fxGetValue($filepath,$rownum,$columnname) {
	$array = $fields = array(); $i = 0;
	$handle = @fopen($filepath, "r");
	$newRowNum = $rownum-2;
	$newColumnNum = getColumnNum($filepath,$columnname);
	if ($handle) {
		while (($row = fgetcsv($handle, 4096)) !== false) {
			if (empty($fields)) {
				$fields = $row;
				continue;
			}
			foreach ($row as $k=>$value) {
				if ($i==$newRowNum AND $k==$newColumnNum) {
					echo "$value";
					return;
				}
			}
			$i++;
		}
		if (!feof($handle)) {
			echo "Error: unexpected fgets() fail\n";
		}
		fclose($handle);
	}
}

function fxGetValues($filepath,$columnname) {
	// this array will hold the results
	$unique_ids = array();
	// open the csv file for reading
	$fd = fopen($filepath, 'r');
	$newColumnNum = getColumnNum($filepath,$columnname);
	// read the rows of the csv file, every row returned as an array
	while ($row = fgetcsv($fd)) {
		// using the keys of arrays to make final values unique since php
		// arrays cant contain duplicate keys
		$unique_ids[$row[$newColumnNum]] = true;
	}
	var_dump(array_keys($unique_ids));
}

function fxSetValue($filepath,$rownum,$columnname,$newValue) {
	$array = $fields = array(); $i = 0;
	$newdata = $newfields = array();
	$handle = @fopen($filepath, "r");
	$newRowNum = $rownum-2;
	$newColumnNum = getColumnNum($filepath,$columnname);
	if ($handle) {
		while (($row = fgetcsv($handle, 4096)) !== false) {
			if (empty($fields)) {
				$fields = $row;
				continue;
			}
			if (empty($newfields)) {
				$newfields = $row;
				continue;
			}
			foreach ($row as $k=>$value) {
				if ($i==$newRowNum AND $k==$newColumnNum) {
					$newdata[$i][$newfields[$k]] = $newValue;
				}
				else {
					$newdata[$i][$newfields[$k]] = $value;
				}
			}
			$i++;
		}
		if (!feof($handle)) {
			echo "Error: unexpected fgets() fail\n";
		}
		$fp = fopen('NewFile.csv', 'w');    
		foreach ($newdata as $rows) {
			fputcsv($fp, $rows);
		}    
		fclose($fp);
	}
}

function fxWriteCSVFile($filepath,$rowData) {
	$csv = $rowData.PHP_EOL;
	$fname = $filepath;//give the csv file a name
	$fp = fopen($fname,'a');//open csv file
	fwrite($fp,$csv);//write csv data to data.csv
	fclose($fp);//close data.csv;
	echo "Row inserted successfully";
}

function fxFindRows($filepath,$columnname,$columnvalue) {
	$array = $fields = array(); $i = 0;
	$handle = @fopen($filepath, "r");
	$newColumnNum = getColumnNum($filepath,$columnname);
	$outputString="";
	if ($handle) {
		while (($row = fgetcsv($handle, 4096)) !== false) {
			if (empty($fields)) {
				$fields = $row;
				continue;
			}
			foreach ($row as $k=>$value) {
				if ($value==$columnvalue AND $k==$newColumnNum) {
					$j=$i+2;
					if (empty($outputString)) {
						$outputString="$j";
					}
					else {
						$outputString.=",$j";
					}
				}
			}
			$i++;
		}
		echo "Row number $outputString contains Value: $columnvalue";
		
		if (!feof($handle)) {
			echo "Error: unexpected fgets() fail\n";
		}
		fclose($handle);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<!-- Meta Data -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
 	

        <title></title>
        <meta name="description" content=""/>
        <meta name="keywords" content=""/>
        <meta name="author" content=""/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
 

<form action="" method="post" >
			<select name="subject" id="subject">
				<option value="Booking">Select</option>
				<option value="readCSVFile">readCSVFile</option>
				<option value="writeCSVFile">writeCSVFile</option>
					<option value="getValue">getValue
</option>
				<option value="findRows">findRows
</option>		<option value="getValues">getValues

</option>
			</select>

			</div>

			<div class="readCSVFile" id="Other" style="display:none;" > 
		
			<input name="csvNamefxReadCSVFile" type="text"   placeholder="Enter CSV Name" id="read" value="" />
			<input type="submit" value="submit" name="submit" />


	
         
  </div>  		<div class="writeCSVFile" id="Other" style="display:none;" > 
		
			<input name="csvNamefxWriteCSVFile" type="text"  placeholder="Enter CSV Name" value="" />
			<input name="datacsvNamefxWriteCSVFile" type="text"  placeholder="Enter comma separated Data" value=""  />
			<input type="submit" value="submit"  name="submit" />
	
         
  </div>  	
  	<div class="getValue" id="Other" style="display:none;" > 
		
			<input name="csvNamefxGetValue" type="text"   placeholder="Enter CSV Name" value="" />
			<input name="rowNumfxGetValue" type="text"   placeholder="Enter Row Number" value="" />
			<input name="colNamefxGetValue" type="text"   placeholder="Enter Column name" value="" />

			<input type="submit" value="submit"  name="submit" />
         
  </div> 
   	<div class="findRows" id="Other" style="display:none;" > 
		
			<input name="csvNamefxFindRows" type="text"   placeholder="Enter CSV Name" value="" />
			<input name="colNamefxFindRows" type="text"   placeholder="Enter Column Name" value="" />
			<input name="colValuefxFindRows" type="text"   placeholder="Enter Column Value" value="" />
			<input type="submit" value="submit"  name="submit" />
	
         
  </div> 
   	<div class="getValues" id="Other" style="display:none;"> 
		
			<input name="csvNamefxGetValues" type="text"   placeholder="Enter CSV Name" value="" />
			<input name="colNamefxGetValues" type="text"   placeholder="Enter Column Name" value="" />
			<input type="submit" value="submit"  name="submit" />
	
     
  </div> 
  </form>
  
			 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
</div><script type="text/javascript">
function validateForm(){
    
   var x= $("form input.textbox").val();
  
    if ($('.showother').is(":visible")) {
    if ( x==null || x=="" )
{
alert("Text box be filled out");
return false;
}
}
}
$(document).ready(function(){
$('#subject').change(function () {
   
    if ($(this).val() == "Booking") {
    $('.showother').show();
    } 
	 else if ($(this).val() == "readCSVFile") {
    $('.readCSVFile').show();
 $('.getValues').hide();
	    $('.findRows').hide();
	  $('.getValue').hide();
	  $('.writeCSVFile').hide();
	
    }
	 else if ($(this).val() == "writeCSVFile") {
    $('.writeCSVFile').show();
	  $('.readCSVFile').hide();
	   $('.getValues').hide();
	    $('.findRows').hide();
	  $('.getValue').hide();
    }
	 else if ($(this).val() == "getValue") {
    $('.getValue').show();
	  $('.writeCSVFile').hide();
	  $('.readCSVFile').hide();
	    $('.getValues').hide();
	    $('.findRows').hide();
    }
	 else if ($(this).val() == "findRows") {
    $('.findRows').show();
	  $('.getValue').hide();
	  $('.writeCSVFile').hide();
	  $('.readCSVFile').hide();
	     $('.getValues').hide();
    }
	 else if ($(this).val() == "getValues") {
    $('.getValues').show();
	    $('.findRows').hide();
	  $('.getValue').hide();
	  $('.writeCSVFile').hide();
	  $('.readCSVFile').hide();
    }else {
            $('.showother').hide();
    }
	
   
});

});
</script> 

</body>
</html>