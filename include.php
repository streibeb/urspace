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
