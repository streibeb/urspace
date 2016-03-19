<?php
// TODO: Test that it handles errors.

//start session
session_start();
include_once("config.php");

// if user not logged in, redirect to homepage
if(!isset($_SESSION['login_user']))
{
	header('Location: index.html');
	exit();
}

if (isset($_POST["submit"])) {
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$uniqueId = uniqid();
	$uploaded = false;

	$tempFile = USER_UPLOAD_DIRECTORY . basename($_FILES["postPic"]["name"]);
	$ext = pathinfo($tempFile, PATHINFO_EXTENSION);
	$target_file = USER_UPLOAD_DIRECTORY . $uniqueId . "." . $ext;

	//error check image upload
	if ($tempFile == USER_UPLOAD_DIRECTORY)// if user did not upload file
	{
		// do nothing
	} else if (!preg_match("/(\.jpg|gif|png|jpeg)/",$target_file)) { // if file extension is bad
		$err =  "Image must be png, gif, jpg or jpeg format."; // output error message
	} else {
		if (move_uploaded_file($_FILES["postPic"]["tmp_name"], $target_file)) {
			//echo basename( $_FILES["postPic"]["name"]). " has been uploaded.";
			$uploaded = true;
		} else {
			$err = "Error uploading image to database.";
		}
	}

	$uid = $_SESSION['login_user'];
	$content = htmlspecialchars(addslashes(trim($_POST['post1'])));
	$date = date('Y/m/d H:i:s', time());

	// if user uploaded image and link
	if ($uploaded) {
		//user uploaded image
		$sql = "INSERT INTO Posts (creatorId, text, uploadedFile, timestamp)
		VALUES ('$uid','$content','$target_file','$date');";
	} else {// user has not uploaded image
		$sql = "INSERT INTO Posts (creatorId, text, timestamp)
		VALUES ('$uid','$content','$date');";
	}

	//upload post to database
	if (!mysqli_query($conn, $sql)) {
		$err = mysql_error($conn);
	}

	// close database connection
	mysqli_close($conn);

	if (isset($err)) {
		header('Location: wall.php');
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">

<head>

	<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> <!-- This is the link for bootstrap !-->
	<script type = "text/javascript"  src = "java1.js" ></script>
	<title>Wall Post</title>
</head>

<body class="allPages">
	<div class="container-fluid"> <!-- This is the container div for the page; it is flued so it spands the viewport !-->
		<div class="row"> <!-- Header row !-->
			<div class="col-xs-12">
				<div class="header">
					<h1>
						<a href="index.html" class="homeLink">
							<img src="blank.jpg" class="placeHolder" alt="img"></img> FakeBook
						</a>
					</h1>
				</div>
			</div>
		</div>

		<div class="row"> <!-- Content row!-->
			<div class="col-xs-2"> <!--Sidebar column !-->
				<div class="sideBar">
					<br></br>
					<a class="buttons" href="wall.php">View Posts</a>
					<p class="blankButton">New Post</p>
					<a class="buttons" href="search.php">Search</a>
					<a class="buttons" href="logout.php">Logout</a>
					<br></br>
					<img src="advertisment1.jpg" class="ad" alt="ad1"></img>
					<img src="advertisment2.jpg" class="ad" alt="ad2"></img>
					<img src="advertisment3.jpg" class="ad" alt="ad3"></img>
					<img src="advertisment4.jpg" class="ad" alt="ad4"></img>
					<img src="advertisment5.jpg" class="ad" alt="ad5"></img>

				</div>
			</div>

			<div class="col-xs-10 col-md-6 col-md-offset-2"> <!-- Content column !-->
				<div class="signupSection">
					<?php if (isset($err)) echo "<p>An error has occurred.<br/>'$err'</p>" ?>
					<form action="post.php" method="POST" enctype="multipart/form-data" id="postForm">
						<fieldset class="largeColorsec">
							<legend>New Anonymous Post</legend>

							Picture (optional): <input type="file" name="postPic"></input>
							<span class="errorMsg" id="errorUrl"></span><br></br>
							Post: <br></br><textarea class="textBox" name="post1" rows="7" cols="100"></textarea>
							<br></br><span class="errorMsg" id="errorComments"></span>

							<p>
								<input type="submit" name="submit" value="Submit"/>
								<input type="reset" value="Reset"/>
							</p>
						</fieldset>
					</form>
				</div>
			</div>
		</div>

		<div class="row"> <!-- footer row !-->
			<div class="col-xs-12">
				<div class="footer">
					<p class="p2">2015 Department of Computer Science CS 215</p>
				</div>
			</div>
		</div>
	</div>

	<script type = "text/javascript"  src = "post1.js" ></script>
</body>
</html>
