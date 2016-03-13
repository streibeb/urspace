<?php
//start session
session_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns = "http://www.w3.org/1999/xhtml">

<head>

<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
<title>Posted</title>
</head>
<body class="infoPage">

<?php
// Open database connection
$conn = mysqli_connect("localhost", "streibeb_cs372rw", "urspace1", "streibeb_cs372");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

$uniqueId = uniqid();
$uploaded = false;

//error check image upload
$tempFile = "uploads/" . basename($_FILES["postPic"]["name"]);
$ext = pathinfo($tempFile, PATHINFO_EXTENSION);
$target_file = "uploads/" . $uniqueId . "." . $ext;


if($target_file == "uploads/")// if user did not upload file
{
	// do nothing
}elseif(!preg_match("/(\.jpg|gif|png|jpeg)/",$target_file)){ // if file extension is bad
	echo "Image must be png, gif, jpg or jpeg format.</br>Redirecting..."; // output error message
	//redirect
echo ' <META HTTP-EQUIV="Refresh" Content="2; URL=post.php"> ';
	exit();
}elseif(file_exists($target_file)){
	// if image already exists.
	echo "Image already exists in database.</br>Redirecting...";
		//redirect
echo ' <META HTTP-EQUIV="Refresh" Content="2; URL=post.php"> ';
	exit();
	
}else{
	 if (move_uploaded_file($_FILES["postPic"]["tmp_name"], $target_file)) {
        echo basename( $_FILES["postPic"]["name"]). " has been uploaded.";
		$uploaded = true;
    } else {
        echo "Error uploading image to database.";
    }
}



 // if user uploaded image and link
if($uploaded){
	//user uploaded image
	$sql = "INSERT INTO Post (creatorId,text,uploadedFile) VALUES ('".$_SESSION['login_user']."','".$_POST['post1']."','".$target_file."'); ";
}else{// user has not uploaded image 
	$sql = "INSERT INTO Post (creatorId,text) VALUES ('".$_SESSION['login_user']."','".$_POST['post1']."'); ";
}


//upload post to database
if (mysqli_query($conn, $sql)) {
	
   echo "<br/>Post successful!<br/>Redirecting...";

} else { // if failed to add a new record: 
    echo "<br/>Post failed<br/>Redirecting...";
}





	// close database connection
mysqli_close($conn);



//redirect
echo ' <META HTTP-EQUIV="Refresh" Content="2; URL=wall.php"> ';
 exit();


?>



</body>
</html>