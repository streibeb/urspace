<?php
session_start();
include_once("config.php");

// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// perform database query
$sql = "SELECT * FROM Users WHERE email = '".$_POST["uName"]."' and password = '".$_POST["pWord"]."';";
$result = mysqli_query($conn, $sql);

// if no results found
if (mysqli_num_rows($result)<=0)
{
	//display error and close database
echo	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
    </script>
<title>Login</title>
</head>
<body class="infoPage">
Invalid username and/or password.<br/>Redirecting...
</body>
</html>';
mysqli_close($conn);
echo ' <META HTTP-EQUIV="Refresh" Content="3; URL=index.html"> ';


}else{
	// Store Session Data
	$row = mysqli_fetch_assoc($result);
$_SESSION['login_user']= $row['userId'];

	// close database connection
	mysqli_free_result($result);
mysqli_close($conn);




echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
    </script>
<title>Login</title>
</head>
<body class="infoPage">
Login Successful.<br/>Redirecting...
</body>
</html>';
// redirect
echo '   <META HTTP-EQUIV="Refresh" Content="3; URL=wall.php"> ';
exit();
}
?>
