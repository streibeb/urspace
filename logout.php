<?php
session_start();

$success = false;
// destroy session
if(isset($_SESSION['login_user']))
{
  session_destroy();
  $success = true;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<META HTTP-EQUIV="Refresh" Content="3; URL=index.php">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
</script>
<title>Logout</title>
</head>
<body class="infoPage">
  <?php if ($success) { ?>
  Successfully logged out.<br/>Redirecting...
  <?php } else { ?>
  You are not logged in.<br/>Redirecting...
  <?php } ?>
</body>
</html>
