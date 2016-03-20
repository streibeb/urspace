<?php
include_once("config.php");
// include function to add hashtags

//create json variable
$sResp = array();
// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

//change SQL result queries to proper variable names
$prevselect = $_GET['prevselect'];

if ($prevselect != ''){
	$result = mysqli_query($conn, "SELECT DISTINCT ".$_GET['to']." FROM 
			Courses where ".$_GET['from']." = '".$_GET['optionSelected']."' AND ".$_GET['prevselect']." = '".$_GET['prevselectvalue']."';");
}else{
	$result = mysqli_query($conn, "SELECT DISTINCT ".$_GET['to']." FROM 
			Courses where ".$_GET['from']." = '".$_GET['optionSelected']."';");
}

if($result !=  FALSE){
// loop through converting data into json object
	while($row = mysqli_fetch_assoc($result)){
				$sRow["result"]=$row["".$_GET['to'].""];
				$sResp[] = $sRow;
	}
}

echo json_encode($sResp);

// close database connection
if($result !=  FALSE)
	mysqli_free_result($result);

mysqli_close($conn);
?>
