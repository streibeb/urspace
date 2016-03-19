<?php
//start session
session_start();

// if user not logged in, redirect to homepage
if(!isset($_SESSION['login_user']))
{
	header('Location: index.html');
	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns = "http://www.w3.org/1999/xhtml">

<head>

	<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> <!-- This is the link for bootstrap !-->
	<script type = "text/javascript"  src = "java1.js" ></script>
	
<title>Public Wall</title>
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
					<p class="blankButton">View Posts</p>
					<a class="buttons" href="post.php">New Post</a>
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

			<div class="col-xs-10"> <!-- content column !-->
				<div class="largeSec">
					<div class="wallOptions">
						Disable automatic updating: 	<input type="checkbox" id="dynamicUpdate"></input>
					</div>
					<div id="wallArea"></div>
					<div id="linksArea" class="pageSelect"></div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="footer">
					<p class="p2">2015 Department of Computer Science CS 215</p>
				</div>
			</div>
		</div>
	</div>

	<script type = "text/javascript"  src = "wall1.js" ></script>
</body>
</html>