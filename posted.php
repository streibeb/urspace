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
$uploaded = false;

//error check image upload
$target_file = "uploads/" . basename($_FILES["wallPic"]["name"]);

if($target_file == "uploads/")// if user did not upload file
{
	// do nothing
}elseif(!preg_match("/(\.jpg|gif|png|jpeg)/",$target_file)){ // if file extension is bad
	echo "Image must be png, gif, jpg or jpeg format.</br>Redirecting..."; // output error message
	//redirect
	 header( "refresh:2;url=post.php");
	exit();
}elseif(file_exists($target_file)){
	// if image already exists.
	echo "Image already exists in database.</br>Redirecting...";
		//redirect
	 header( "refresh:2;url=post.php");
	exit();
	
}else{
	 if (move_uploaded_file($_FILES["wallPic"]["tmp_name"], $target_file)) {
        echo basename( $_FILES["wallPic"]["name"]). " has been uploaded.";
		$uploaded = true;
    } else {
        echo "Error uploading image to database.";
    }
}

// Open database connection
$conn = mysqli_connect("localhost", "mantta2t", "winter15", "mantta2t");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

 // if user uploaded image and link
if($uploaded && $_POST['urlInput'] != "")
{
	$sql = "INSERT INTO posts (UserEmail,comments,ImageLocation,Link) VALUES ('".$_SESSION['login_user']."','".$_POST['comments']."','".$target_file."','".$_POST['urlInput']."'); ";
}elseif(!$uploaded && $_POST['urlInput'] != ""){
	// user uploaded link but no image
	$sql = "INSERT INTO posts (UserEmail,comments,Link) VALUES ('".$_SESSION['login_user']."','".$_POST['comments']."','".$_POST['urlInput']."'); ";
}elseif($uploaded && $_POST['urlInput'] == ""){
	//user uploaded image but no link
	$sql = "INSERT INTO posts (UserEmail,comments,ImageLocation) VALUES ('".$_SESSION['login_user']."','".$_POST['comments']."','".$target_file."'); ";
}else{// user has not uploaded image or link
	$sql = "INSERT INTO posts (UserEmail,comments) VALUES ('".$_SESSION['login_user']."','".$_POST['comments']."'); ";
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
 header( "refresh:2;url=wall.php");
 exit();


?>



</body>
</html>