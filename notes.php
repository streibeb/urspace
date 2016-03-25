<?php
include_once("config.php");
include_once("include.php");
//start session
session_start();
// if user not logged in, redirect to homepage
checkUserSession();
$uid = $_SESSION['login_user'];

//Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

//search for instructor name
$result = mysqli_query($conn, "SELECT DISTINCT instructor FROM Courses;");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">


<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> <!-- This is the link for bootstrap !-->
	<script type = "text/javascript"  src = "scripts/java1.js" ></script>
	<script type = "text/javascript"  src = "scripts/delete.js" ></script>
	<title>Search</title>
</head>


<body class="allPages">
	<div class="container-fluid"> <!-- This is the container div for the page; it is flued so it spands the viewport !-->
		<div class="row"> <!-- Header row !-->
			<div class="col-xs-12">
				<div class="header">
					<h1>
						<a href="<?=SIDEBAR_VIEW_POSTS?>" class="homeLink">
							<img src="logo.png" class="placeHolder" alt="img"></img> <?php echo WEBSITE_NAME; ?>
						</a>
					</h1>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="row row-eq-height contentRow"> <!-- Content Row !-->
				<div class="col-xs-2 sideBarCol"> <!-- sidebar column !-->
					<div class="sideBar">
						<br/>
						<a class="buttons" href="<?php echo SIDEBAR_VIEW_POSTS; ?>">View Wall</a>
						<a class="buttons" href="<?php echo SIDEBAR_CREATE_POSTS; ?>">Create Post</a>
						<p class="blankButton">View Notes</p>
						<a class="buttons" href="<?php echo SIDEBAR_CREATE_NOTES; ?>">Create Notes</a>
						<?php if (isAdmin($uid)) { ?><a class="buttons" href="<?php echo SIDEBAR_ADMIN; ?>">Admin</a><?php } ?>
						<a class="buttons" href="<?php echo SIDEBAR_LOGOUT; ?>">Logout</a>
					</div>
				</div>
				<div class="col-xs-10 col-md-6 col-md-offset-2"> <!-- content column !-->
					<div class="signupSection">
						<form action="notes.php" method="GET" id="userSearchForm">
							<fieldset class="largeColorsec">
								<legend>Course Notes Search</legend>
								<class id="courseSelector">
									<div id="selectorOptions" class="SelectOptions">
										<div class="courseInputIdent"> Please select an instructor name: </div>
										<select id="instructor" class="selectBox" name="instructor">
											<option value=""></option>
											<?php
											//cylce through and populate all of the instructor values
											while($row = mysqli_fetch_assoc($result)){
												echo '<option value='.$row['instructor'].'>'.$row['instructor'].'</option>';
											}
											?>
										</select>
									</div>

									<div id="selectorOptions" class="SelectOptions">
										<div class="courseInputIdent"> Please select a course name: </div>
										<select id="courseName" class="selectBox" name="courseName"></select><!-- Will recieve course name from AJAX!-->
									</div>
								</class>

								<div id="selectorOptions" class="SelectOptions">
									<div class="courseInputIdent"> Please select a course number: </div>
									<select id="courseNumber" class="selectBox" name="courseNumber")></select><!-- Will recieve course number from AJAX!-->
								</div>
								<span class="errorMsg" id="search1error"></span>
								<p>
									<input class="contentButtons" type="submit" value="Submit"/>
									<input class="contentButtons" type="reset" value="Reset"/>
								</p>
							</fieldset>
						</form>
					</div>

					<div class="largeSec">
					<?php
					//check if course name, instructor, course number are selected
						$instructor = $_GET['instructor'];
						$courseName = $_GET['courseName'];
						$courseNumber = $_GET['courseNumber'];

						if (isset($instructor) && isset($courseName) && isset($courseNumber)){
							$query = "SELECT n.*
							FROM Notes n
							JOIN Courses c ON n.parentCourseId = c.courseId
							WHERE c.courseName='$courseName'
								AND c.courseNumber = '$courseNumber'
								AND c.instructor='$instructor'
								AND n.isHidden = false;";
							$result = mysqli_query($conn, $query);
						}
						//print out each note avaiable based on the options slected
						while($row = mysqli_fetch_assoc($result)){
							echo "<div class=wallPost>";
							echo "<h2>$instructor $courseName $courseNumber</h2>";
							echo "<p class=wallText> ".$row['text']." ";
							echo '<br/><a href="'.USER_NOTES_UPLOAD_DIRECTORY.$row['uploadedFile'].'"><button>Download Attached File</button> </a>';
							//echo '<br/><a href="'.USER_NOTES_UPLOAD_DIRECTORY.$row['uploadedFile'].'">'.$row['uploadedFile'].'</a>';
							echo '<p class="p3">' . $row['timestamp'];
							echo "</p>";
							if ($_SESSION['isAdmin'] || $uid == $row["creatorId"]) {
								echo '<button id="delete_' . $row["notesId"] . '" class="deleteButton">Delete</button>';
							}
							echo "</div>";
						}
						?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row"> <!-- Footer Row !-->
			<div class="col-xs-12">
				<div class="footer">
					<p class="p2">UR Space Copyright © 2016 All Rights Reserved</p>
				</div>
			</div>
		</div>
	</div>
	<script type = "text/javascript"  src = "scripts/noteseventlisteners.js" >
	</script>
</body>
</html>

<?php
	//close SQL connection and free result
	mysqli_free_result($result);
	mysqli_close($conn);
?>
