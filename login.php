<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns = "http://www.w3.org/1999/xhtml">

<head>

<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
    </script>
<title>Login</title>



</head>
<body class="infoPage">

<?php
//start session
session_start();
// Open database connection
$conn = mysqli_connect("localhost", "mantta2t", "winter15", "mantta2t");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// perform database query
$sql = "SELECT * FROM users WHERE Email = '".$_POST["uName"]."' and Password = '".$_POST["pWord"]."';";
$result = mysqli_query($conn, $sql);

// if no results found
if (mysqli_num_rows($result)<=0)
{
	//display error and close database
echo "Invalid username and/or password.<br/>Redirecting...";
mysqli_close($conn);
header( "refresh:3;url=index.html");


}else{
	// close database connection
	mysqli_free_result($result);
mysqli_close($conn);


// Store Session Data
$_SESSION['login_user']= $_POST['uName'];

echo "Login Successful.<br/>Redirecting...";
// redirect
header( "refresh:3;url=wall.php");
exit();
}






?>



</body>
</html>