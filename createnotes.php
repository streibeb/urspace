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

//before submission code to populate the dropdown lists

// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

//search for instructor name
$result = mysqli_query($conn, "SELECT DISTINCT instructor FROM Courses;");


//handle after submission
if (isset($_POST["submit"])) {
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$uniqueId = uniqid();
	$row = '';
	$parentCourseId = '';
	$result = '';

	$tempFile = USER_NOTES_UPLOAD_DIRECTORY . basename($_FILES["postPic"]["name"]);
	$ext = strtolower(pathinfo($tempFile, PATHINFO_EXTENSION));
	$target_file = USER_NOTES_UPLOAD_DIRECTORY . $uniqueId . "." . $ext;
	$filename = $uniqueId . "." . $ext;

	//error check image upload
	if ($tempFile == USER_NOTES_UPLOAD_DIRECTORY)// if user did not upload file
	{
		// do nothing
	} else if (!preg_match("/(\.jpg|pdf|docx|png|jpeg)/",$target_file)) { // if file extension is bad
		$err =  "Image must be pdf, docx, png, jpg or jpeg format."; // output error message
	} else {
		if (move_uploaded_file($_FILES["postPic"]["tmp_name"], $target_file)) {
			//echo basename( $_FILES["postPic"]["name"]). " has been uploaded.";
		} else {
			$err = "Error uploading image to database.";
		}
	}

	$uid = $_SESSION['login_user'];
	$content = htmlspecialchars(addslashes(trim($_POST['post1'])));

	$instructor = htmlspecialchars(addslashes(trim($_POST['instructor'])));
	$courseName = htmlspecialchars(addslashes(trim($_POST['courseName'])));
	$courseNumber = htmlspecialchars(addslashes(trim($_POST['courseNumber'])));

	$date = date('Y/m/d H:i:s', time());

	//get courseid from course table which will be used as parentCourseId in notes table
	if (isset($instructor) && isset($courseName) && isset($courseNumber)){
		$sql = "SELECT courseId FROM Courses c WHERE
		c.courseName='$courseName' AND c.courseNumber='$courseNumber' AND c.instructor='$instructor';";

		$result = mysqli_query($conn, $sql);

		//get row that matches the correct parameters
		$row = mysqli_fetch_assoc($result);

		//store parentcourseid
		$parentCourseId = $row['courseId'];

	}

	//uploading a file is now required to continue
	$sql = "INSERT INTO Notes (parentCourseId, timestamp,creatorId, text, uploadedFile, isHidden)
	VALUES ('$parentCourseId', '$date','$uid','$content','$filename', '0');";

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
	<script type = "text/javascript"  src = "scripts/java1.js" ></script>
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
					<form action="createnotes.php" method="POST" enctype="multipart/form-data" id="postForm">
						<fieldset class="largeColorsec">
							<legend>Add New Notes</legend>

							<class id="courseSelector">
								<div id="selectorOptions" class="SelectOptions">
									Please select an instructor name:
									<select id="instructor" class="selectBox" name="instructor" required>
										<option value=""></option>
										<?php

										//cylce through and populate all of the instructor values
										while($row = mysqli_fetch_assoc($result)){
											echo '<option value='.$row['instructor'].'>'.$row['instructor'].'</option>';
										}

										//after its done populating instructor, close the connection
										mysqli_free_result($result);
										mysqli_close($conn);
										?>
									</select>
								</div>

								<div id="selectorOptions" class="SelectOptions">
									Please select a course name:
									<select id="courseName" class="selectBox" name="courseName" required></select><!-- Will recieve course name from AJAX!-->
								</div>
							</class>

							<div id="selectorOptions" class="SelectOptions">
								Please select a course number:
								<select id="courseNumber" class="selectBox" name="courseNumber" required></select><!-- Will recieve course number from AJAX!-->
							</div>

							Note Upload: <input type="file" name="postPic" required></input>
							<span class="errorMsg" id="errorUrl"></span><br></br>
							Additional Information (Optional): <br></br><textarea class="textBox" name="post1" rows="7" cols="100"></textarea>
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
	<script type = "text/javascript"  src = "scripts/noteseventlisteners.js" ></script>
</body>
</html>
