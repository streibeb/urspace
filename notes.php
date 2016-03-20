<?php
	include_once("config.php");
	//start session
	session_start();
	// if user not logged in, redirect to homepage
	if(!isset($_SESSION['login_user']))
	{
		header('Location: index.php');

	}
	// include function to add hashtags

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">


<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> <!-- This is the link for bootstrap !-->
	<script type = "text/javascript"  src = "java1.js" >
	</script>
	<title>Search</title>
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

		<div class="row row-eq-height"> <!-- Content Row !-->
			<div class="col-xs-2 sideBarCol"> <!-- sidebar column !-->
				<div class="sideBar">
					<br/>
					<a class="buttons" href="<?php echo SIDEBAR_VIEW_POSTS; ?>">View Wall</a>
					<a class="buttons" href="<?php echo SIDEBAR_CREATE_POSTS; ?>">New Post</a>
					<a class="buttons" href="<?php echo SIDEBAR_VIEW_NOTES; ?>">View Notes</a>
					<a class="buttons" href="<?php echo SIDEBAR_ADMIN; ?>">Admin</a>
					<a class="buttons" href="<?php echo WEBSITE_LOGOUT; ?>">Logout</a>
				</div>
			</div>

			<?php
			include_once("config.php");

			//Open database connection
			$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}

			//search for instructor name
			$result = mysqli_query($conn, "SELECT DISTINCT instructor FROM Courses;");
			
			?>

			<div class="col-xs-10 col-md-6 col-md-offset-2"> <!-- content column !-->
				<div class="signupSection">
					<form action="notes.php" method="GET" id="userSearchForm">
						<fieldset class="largeColorsec">
							<legend>Course Notes Search</legend>
							<class id="courseSelector">

								<div id="selectorOptions" class="SelectOptions">
									Please select an instructor name:
									<select id="instructor" class="selectBox" name="instructor">
										<!--> populate with instructor name -->
										<option value=""></option>
										<?php
										while($row = mysqli_fetch_assoc($result)){
											echo '<option value='.$row['instructor'].'>'.$row['instructor'].'</option>';
										}
										?>
									</select>
								</div>				

								<div id="selectorOptions" class="SelectOptions">
									Please select a course name:
									<select id="courseName" class="selectBox" name="courseName"></select>
								</div>
							</class>

							<div id="selectorOptions" class="SelectOptions">
								Please select a course number:
								<select id="courseNumber" class="selectBox" name="courseNumber")></select>
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
						$result = mysqli_query($conn, "SELECT DISTINCT n.* FROM Notes n JOIN Course c ON n.parentCourseId = c.courseId WHERE 
							c.courseName='$courseName' AND c.courseNumber = '$courseNumber' AND c.instructor='$instructor';");
						}
				?>

				<?php
				//print out each note avaible
					/*while($row = mysqli_fetch_assoc($result)){
						echo "<div class=wallPost>";
							echo "<h2>$instructor $courseName $courseNumber</h2>";
							echo "<p class=wallText> ".$row['text']." ";
									echo '<br/><a href="'.$row['uploadedFile'].'">'.$row['uploadedFile'].'</a>';
									echo '<p class="p3">' . $row['timestamp'];
							echo "</p>";
						echo "</div>";
					}*/
				?>

				<?php
					mysqli_free_result($result);
					mysqli_close($conn);
				?>
			</div>
		</div>
	</div>


		<div class="row"> <!-- Footer Row !-->
			<div class="col-xs-12"> 
				<div class="footer">
					<p class="p2">2015 Department of Computer Science CS 215</p>
				</div>
			</div>
		</div>
	</div>

	<script type = "text/javascript"  src = "noteseventlisteners.js" >
	</script>
</body>
</html>
