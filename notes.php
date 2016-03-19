<?php
//start session
session_start();
// if user not logged in, redirect to homepage
if(!isset($_SESSION['login_user']))
{
	header('Location: index.html');

}
// include function to add hashtags

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<script type="text/javascript">
	function update(toOptionSelected,fromSelector, toSelector) {
		
		var parent;

		/*********************************************************/
		if (fromSelector == "instructor"){
			parent = document.getElementById("courseName");
			while (parent.firstChild) {
				parent.removeChild(parent.firstChild);
			}
			parent = document.getElementById("courseNumber");
			while (parent.firstChild) {
				parent.removeChild(parent.firstChild);
			}
		}

		/*********************************************************/

		parent = document.getElementById(toSelector);

		while (parent.firstChild) {
			parent.removeChild(parent.firstChild);
		}

		if (optionSelected = ""){
			return;
		}else{
			
			if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }


    xmlhttp.onreadystatechange = function() {
    	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
               //handle response here
               var response = xmlhttp.responseText;
               var valueMappedArray = JSON.parse(response);
               //alert (valueMappedArray.length);
               var i;
               var option = document.createElement("option");
               option.value ="";
               option.text="";
               parent.add(option);
               for (i = 0; i < valueMappedArray.length; i++){

               	option = document.createElement("option");
               	option.value = valueMappedArray[i].result;
               	option.text = valueMappedArray[i].result;
               	parent.add(option);
               }

           }
       }
       xmlhttp.open("GET","populatenoteslist.php?optionSelected="+ toOptionSelected.value + "&from=" + fromSelector + "&to=" + toSelector,true);
	   xmlhttp.send();
}

</script>

<head>

	<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
	<script type = "text/javascript"  src = "java1.js" >
	</script>
	<title>Search</title>
</head>


<body class="allPages">
	<div class="header">
		<h1>
			<a href="index.html" class="homeLink">
				<img src="blank.jpg" class="placeHolder" alt="img"></img> FakeBook
			</a>
		</h1>
	</div>

	<div class="sideBar">
		<br></br>
		<a class="buttons" href="wall.php">View Wall</a>
		<a class="buttons" href="post.php">New Post</a>
		<p class="blankButton">Search</p>
		<a class="buttons" href="logout.php">Logout</a>
		<br></br>
		<img src="advertisment1.jpg" class="ad" alt="ad1"></img>
		<img src="advertisment2.jpg" class="ad" alt="ad2"></img>
		<img src="advertisment3.jpg" class="ad" alt="ad3"></img>
		<img src="advertisment4.jpg" class="ad" alt="ad4"></img>
		<img src="advertisment5.jpg" class="ad" alt="ad5"></img>

	</div>

	<?php
	include_once("config.php");

	//Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	//search for instructor name
	$result = mysqli_query($conn, "SELECT DISTINCT instructor FROM Course;");

	?>


	<form action="notes.php" method="GET" id="userSearchForm">
		<fieldset class="largeColorsec">
			<legend>Course Notes Search</legend>
			<class id="courseSelector">

				<div id="selectorOptions">
					Please select an instructor name:
					<select id="instructor" name="instructor" onchange="update(this,'instructor','courseName')">
						<!--> populate with instructor name -->
						<option value=""></option>
						<?php
						while($row = mysqli_fetch_assoc($result)){
							echo '<option value='.$row['instructor'].'>'.$row['instructor'].'</option>';
						}
						?>
					</select>
				</div>				

				<div id="selectorOptions">
					Please select a course name:
					<select id="courseName" name="courseName" onchange="update(this,'courseName','courseNumber')" >
					</select>
				</div>
			</div>

			<div id="selectorOptions">
				Please select a course number:
				<select id="courseNumber" name="courseNumber")>

				</select>
			</div>
		</div>

		<span class="errorMsg" id="search1error"></span>
		<p>
			<input type="submit" value="Submit"/>
			<input type="reset" value="Reset"/>
		</p>
	</fieldset>
</form>

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
		while($row = mysqli_fetch_assoc($result)){
			echo "<div class=wallPost>";
				echo "<h2>$instructor $courseName $courseNumber</h2>";
				echo "<p class=wallText> ".$row['text']." ";
						echo '<br/><a href="'.$row['uploadedFile'].'">'.$row['uploadedFile'].'</a>';
						echo '<p class="p3">' . $row['timestamp'];
				echo "</p>";
			echo "</div>";
		}
	?>

	<?php
		mysqli_free_result($result);
		mysqli_close($conn);
	?>

</div>
<div class="footer">
	<p class="p2">2015 Department of Computer Science CS 215</p>
</div>

<script type = "text/javascript"  src = "search1.js" >
</script>
</body>
</html>
