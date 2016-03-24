<?php
/*
  isAdmin
  Purpose:
    Returns whether currently logged in user is an admin
  Parameters:
    $id, the userId of the currently logged in user
  Returns:
    whether the user is an administrator
*/
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

function isBanned($id)
{
	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	$query = "SELECT isBanned
	FROM Users u
	WHERE userId = '$id' AND isBanned = true;";

	// perform database query
	$result = mysqli_query($conn, $query);
	mysqli_close($conn);

	return (mysqli_num_rows($result) != 0);
}

function checkUserSession() {
	if(!isset($_SESSION['login_user'])) {
		header('Location: '.SIDEBAR_LOGIN);
	} else if (isBanned($_SESSION['login_user'])) {
		echo "<script>
					alert('You have been banned!');
					window.location.href='/cs372/logout.php';
					</script>";
		exit();
	}
}
