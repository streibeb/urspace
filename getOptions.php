<?php
include_once("config.php");
// include function to add hashtags
include 'bonus.php';
include_once("config.php");
//create json variable
$sResp = array();
// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SELECT DISTINCT ".$_GET['to']." FROM 
			Course where ".$_GET['from']." = '".$_GET['optionSelected']."';");

if($result !=  FALSE){
// loop through converting data into json object
	while($row = mysqli_fetch_assoc($result)){
				$sRow["result"]=$row["".$_GET['to'].""];
				$sResp[] = $sRow;
	}
}else{
	echo "you fucked u3p";
}

echo json_encode($sResp);


	// close database connection
if($result !=  FALSE)
	mysqli_free_result($result);

mysqli_close($conn);
?>
