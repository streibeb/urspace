<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">

<head>
<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
    </script>
<title>Sign Up</title>
</head>
<body class="infoPage">

<?php
include_once("config.php");
// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}


$sql = "INSERT INTO Users (email,firstName,lastName,dateOfBirth,password) VALUES ('".$_POST['eMail']
."','".$_POST['fName']."','".$_POST['lName']."','".$_POST['bDay']." 00:00:01','".$_POST['pWord1']."');";

//attempt to create new record
if (mysqli_query($conn, $sql)) {
   echo "Sign-up successful. Welcome to URspace!<br/>Redirecting...";
   // close database connection
mysqli_close($conn);
echo ' <META HTTP-EQUIV="Refresh" Content="3; URL=login.html"> ';

} else { // if failed to add a new record:
    echo "User email already exists.<br/>Redirecting...";
	// close database connection
mysqli_close($conn);

echo ' <META HTTP-EQUIV="Refresh" Content="3; URL=signup.html"> ';

}


?>


</body>
</html>
