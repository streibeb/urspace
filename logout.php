<?php
session_start();

// destroy session
if(isset($_SESSION['login_user']))
{
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
  <html xmlns = "http://www.w3.org/1999/xhtml">
  <head>
  <link rel="stylesheet" type="text/css" href="mystyle.css"></link>
  </script>
  <title>Logout</title>
  </head>
  <body class="infoPage">
  Successfully logged out.<br/>Redirecting...
  </body>
  </html>';
  session_destroy();
}else{
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
  <html xmlns = "http://www.w3.org/1999/xhtml">
  <head>
  <link rel="stylesheet" type="text/css" href="mystyle.css"></link>
  </script>
  <title>Logout</title>
  </head>
  <body class="infoPage">
  You are not logged in.<br/>Redirecting...
  </body>
  </html>';
}
//redirect
<<<<<<< HEAD
echo ' <META HTTP-EQUIV="Refresh" Content="3; URL=login.html"> ';
 exit();
=======
echo ' <META HTTP-EQUIV="Refresh" Content="3; URL=index.html"> ';
exit();
>>>>>>> origin/master





?>
