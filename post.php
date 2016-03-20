<?php
// TODO: Test that it handles errors.

//start session
session_start();
include_once("config.php");

// if user not logged in, redirect to homepage
if(!isset($_SESSION['login_user']))
{
	header('Location: index.php');
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

	$tempFile = USER_IMAGE_UPLOAD_DIRECTORY . basename($_FILES["postPic"]["name"]);
	$ext = pathinfo($tempFile, PATHINFO_EXTENSION);
	$target_file = USER_IMAGE_UPLOAD_DIRECTORY . $uniqueId . "." . $ext;
	$filename = $uniqueId . "." . $ext;

	//error check image upload
	if ($tempFile == USER_IMAGE_UPLOAD_DIRECTORY)// if user did not upload file
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
		VALUES ('$uid','$content','$filename','$date');";
	} else { // user has not uploaded image
		$sql = "INSERT INTO Posts (creatorId, text, timestamp)
		VALUES ('$uid','$content','$date');";
	}

	//upload post to database
	if (!mysqli_query($conn, $sql)) {
		$err = mysql_error($conn);
	}

	// close database connection
	mysqli_close($conn);

	header('Location: ' . SIDEBAR_VIEW_POSTS);
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
						<a href="index.php" class="homeLink">
							<img src="logo.png" class="placeHolder" alt="img"></img> <?php echo WEBSITE_NAME; ?>
						</a>
					</h1>
				</div>
			</div>
		</div>

		<div class="row row-eq-height contentRow"> <!-- Content row!-->
			<div class="col-xs-2 sideBarCol"> <!--Sidebar column !-->
				<div class="sideBar">
					<br/>
					<a class="buttons" href="<?php echo SIDEBAR_VIEW_POSTS; ?>">View Wall</a>
					<p class="blankButton">New Post</p>
					<a class="buttons" href="<?php echo SIDEBAR_VIEW_NOTES; ?>">View Notes</a>
					<a class="buttons" href="<?php echo SIDEBAR_ADMIN; ?>">Admin</a>
					<a class="buttons" href="<?php echo SIDEBAR_LOGOUT; ?>">Logout</a>
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
								<input type="submit" class="contentButtons" name="submit" value="Submit"/>
								<input type="reset" class="contentButtons" value="Reset"/>
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
