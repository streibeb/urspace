<?php
session_start();
// Open database connection
$conn = mysqli_connect("localhost", "streibeb_cs372rw", "urspace1", "streibeb_cs372");
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
	// close database connection
	mysqli_free_result($result);
mysqli_close($conn);


// Store Session Data
$_SESSION['login_user']= $_POST['uName'];

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



