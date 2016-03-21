<?php
include_once("config.php");
session_start();

if (isset($_POST['newAdmin']))
{
	addAdmin($_POST['newAdmin']);
}

if (isset($_POST['action']))
{
	$action = $_POST['action'];
	$id = $_POST['id'];

	if ($action == "delete") {
		deletePost($id);
	} else if ($action == "resolve") {
		resolveReports($id);
	}
}

function isAdmin($id)
{
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	$query = "SELECT a.*
	FROM Administrators a
	WHERE a.userId = '$id';";

	// perform database query
	$result = mysqli_query($conn, $query);
	mysqli_close($conn);

	return (mysqli_num_rows($result) > 0);
}

function isSuperAdmin($id)
{
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	$query = "SELECT a.*
	FROM Administrators a
	WHERE a.userId = '$id'
	AND a.isSuperAdmin = true;";

	// perform database query
	$result = mysqli_query($conn, $query);
	mysqli_close($conn);

	return (mysqli_num_rows($result) > 0);
}

function addAdmin($id)
{
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	$query = "INSERT INTO Administrators (userId)
	VALUES ('$id');";

	// perform database query
	$result = mysqli_query($conn, $query);
	mysqli_close($conn);
}

function getNonAdmin()
{
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}


	$query = "SELECT u.*
	FROM Users u
	WHERE NOT EXISTS (SELECT a.*
	FROM Administrators a
	WHERE u.userId = a.userId);";

	// perform database query
	$result = mysqli_query($conn, $query);
	mysqli_close($conn);

	return $result;
}

function getReportedPosts()
{
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	$query = "SELECT p.*
	FROM Posts p
	INNER JOIN ReportedPosts rp
	ON rp.postId = p.postId
	ORDER BY p.timestamp DESC;";

	// perform database query
	$result = mysqli_query($conn, $query);
	mysqli_close($conn);

	return $result;
}

function deletePost($id)
{
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	$query = "DELETE FROM Posts WHERE postId = '$id';";

	// perform database query
	$result = mysqli_query($conn, $query);
	mysqli_close($conn);
}

function resolveReports($id)
{
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	$query = "DELETE FROM ReportedPosts WHERE postId = '$id';";

	// perform database query
	$result = mysqli_query($conn, $query);
	mysqli_close($conn);
}

if(!isAdmin($_SESSION['login_user'])) {
	header("Location: wall.php");
}
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns = "http://www.w3.org/1999/xhtml">
	<head>
		<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> <!-- This is the link for bootstrap !-->
		<!--<script type = "text/javascript"  src = "java1.js" ></script>-->
		<title>Admin Page</title>
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
						<a class="buttons" href="<?php echo SIDEBAR_CREATE_POSTS; ?>">New Post</a>
						<a class="buttons" href="<?php echo SIDEBAR_VIEW_NOTES; ?>">View Notes</a>
						<p class="blankButton">Admin</p>
						<a class="buttons" href="<?php echo SIDEBAR_LOGOUT; ?>">Logout</a>
					</div>
				</div>
				<div class="col-xs-10 col-md-6 col-md-offset-2"> <!-- Content column !-->
					<div class="largeSec">
						<!-- List of non-admins to be promoted !-->
						<div class="nonAdminList">
							<?php if(isSuperAdmin($_SESSION['login_user'])) { ?>
							<form action="administrator.php" method="POST">
								<fieldset class="largeColorsec">
									<legend>Promote User to Admin</legend>
									<div id="selectorOptions" class="SelectOptions">
										Choose a user to promote to an administrator:
										<select id="newAdmin" class="selectBox" name="newAdmin">
											<option value=""></option>
											<?php	//cylce through and populate all of the non-admins
												$result = getNonAdmin();
												while($row = mysqli_fetch_assoc($result))
												{
													echo '<option value='. $row['userId'] . '>'
													. $row['firstName'] . ' ' . $row['lastName'] . '</option>';
												}
											?>
										</select>
									</div>
									<p>
										<input class="contentButtons" type="submit" value="Submit" />
										<input class="contentButtons" type="reset"  value="Reset"  />
									</p>
								</fieldset>
							</form>
							<?php } ?>
						</div>
						<br/>
						<!-- reported posts to be reviewed and deleted !-->
						<div class="wallOptions">
							Disable automatic updating: 	<input type="checkbox" id="dynamicUpdate"></input>
						</div>
						<div id="wallArea">
							<?php
							$result = getReportedPosts();

							while($row = mysqli_fetch_assoc($result))
							{
								$userReportedPost = $post["userReported"];
								$postContent = htmlspecialchars($row['text']);
								$image = false;
								if (!is_null($row["uploadedFile"]))
								{
									$image = true;
									$postFile = USER_IMAGE_UPLOAD_DIRECTORY . $row["uploadedFile"];
								}
								$postId = $row["postId"];
								$postTimestamp = $row["timestamp"];
								?>
								<div class="wallPost" id="post<?=$postId?>">
									<?php
									if ($image)
									{
										?>
										<img src="<?php echo $postFile; ?>" class="wallImg" alt="img">
										<?php
									}
									?>
									<p class="wallText">
									<?php echo $postContent; ?>
									</p>
									<form action="administrator.php" method="POST">
										<select id="action" class="selectBox" name="action">
											<option value='resolve'>Resolve</option>
											<option value='delete'>Delete</option>
										</select>
										<input type="hidden" name="id" value="<?=$postId?>">
										<input type="submit" class="contentButton" value="Submit" />
									</form>
								</div>
							<?php } ?>
						</div>
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
	</body>
	</html>
