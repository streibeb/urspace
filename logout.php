<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns = "http://www.w3.org/1999/xhtml">

<head>

<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
    </script>
<title>Logout</title>



</head>
<body class="infoPage">

<?php
//start session
session_start();

// destroy session
if(isset($_SESSION['login_user']))
{
echo "Successfully logged out.<br/>Redirecting...";
session_destroy();
}else{
	echo "You are not logged in.<br/>Redirecting...";
}
//redirect
 header( "refresh:3;url=index.html");
 exit();





?>



</body>
</html>