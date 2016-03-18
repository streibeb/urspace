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
	include_once("config.php");
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$uniqueId = uniqid();
	$uploaded = false;
	$tempFile = USER_IMAGE_UPLOAD_DIRECTORY . basename($_FILES["postPic"]["name"]);
	$ext = pathinfo($tempFile, PATHINFO_EXTENSION);
	$target_file = USER_IMAGE_UPLOAD_DIRECTORY . $uniqueId . "." . $ext;

	//error check image upload
	if($tempFile == USER_IMAGE_UPLOAD_DIRECTORY)// if user did not upload file
	{
		// do nothing
	}elseif(!preg_match("/(\.jpg|gif|png|jpeg)/",$target_file)){ // if file extension is bad
		echo "Image must be png, gif, jpg or jpeg format.</br>Redirecting..."; // output error message
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

	$uid = $_SESSION['login_user'];
	// I'm just going to leave this here to bring shame to everyone's families.
	// Seriously.
	// SANITIZE YOUR INPUTS
	//$postText = $_POST['post1'];
	$postText = htmlspecialchars(addslashes(trim($_POST["post1"])));
	$date = date('Y/m/d H:i:s', time());


	// if user uploaded image and link
	if($uploaded){
		//user uploaded image
		$sql = "INSERT INTO Post (timestamp, creatorId, text, uploadedFile)
		VALUES ('$date', '$uid', '$postText', '$target_file');";
	}else{// user has not uploaded image
		$sql = "INSERT INTO Post (timestamp, creatorId, text)
		VALUES ('$date', '$uid', '$postText');";
	}

	//upload post to database
	if (mysqli_query($conn, $sql)) {
		echo "Post successful!<br/>Redirecting...";
		echo '<META HTTP-EQUIV="Refresh" Content="2; URL=wall.php">';
	} else { // if failed to add a new record:
		echo("Error description: " . mysqli_error($conn));
		echo '<br/>';
		echo 'Please report this error to the administators. <br/>';
	}

	// close database connection
	mysqli_close($conn);

	//redirect
	exit();


	?>



</body>
</html>
