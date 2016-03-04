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
// Open database connection
$conn = mysqli_connect("localhost", "mantta2t", "winter15", "mantta2t");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
$sql = "INSERT INTO users (Email,FirstName,LastName,Birthday,Password) VALUES ('".$_POST['eMail']
."','".$_POST['fName']."','".$_POST['lName']."','".$_POST['bDay']."','".$_POST['pWord1']."');";

//attempt to create new record
if (mysqli_query($conn, $sql)) {
   echo "Sign-up successful. Welcome to FakeBook!<br/>Redirecting...";
   // close database connection
mysqli_close($conn);
 header( "refresh:3;url=index.html");
   
} else { // if failed to add a new record: 
    echo "User email already exists.<br/>Redirecting...";
	// close database connection
mysqli_close($conn);

 header( "refresh:3;url=signup.html" );

}


?>


</body>
</html>