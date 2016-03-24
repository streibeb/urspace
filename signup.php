<?php
include_once("config.php");
session_start();
//If the user is already logged in, take them to the wall page.
if(isset($_SESSION['login_user']))
{
	header('Location: '.SIDEBAR_VIEW_POSTS);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">

<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> <!-- This is the link for bootstrap !-->
	<script type = "text/javascript"  src = "scripts/java1.js" ></script>
	<title>Sign Up</title>
</head>

<body class="allPages">
	<div class="container-fluid"> <!-- This is the container div for the page; it is flued so it spands the viewport !-->
		<div class="row"> <!--Row for the header !-->
			<div class="col-xs-12">
				<div class="header">
					<h1>
						<a href="<?=SIDEBAR_VIEW_POSTS?>" class="homeLink">
						<img src="logo.png" class="placeHolder" alt="img"></img> <?php echo WEBSITE_NAME; ?>
						</a>
					</h1>
				</div>
			</div>
		</div>
		<div class="col-xs-10 col-md-6 col-md-offset-2"> <!-- the content column !-->
			<div class="signupSection">
				<form action="signupConfirmation.php" method="post" enctype="multipart/form-data" id="signupForm">
					<fieldset class="standardSec">
						<legend>Sign Up</legend>
						First Name: <input type="text" name="fName" id ="fName"></input>
						<span class="errorMsg" id="fNameerror"></span><br></br>
						Last Name: <input type="text" name="lName" id ="lName"></input>
						<span class="errorMsg" id="lNameerror"></span><br></br>
						Birthdate (YYYY-MM-DD): <input type="text" name="bDay" id="bDay"></input>
						<span class="errorMsg" id="bDayerror"></span><br></br>
						Email: <input type="text" name="eMail" id="eMail"></input>
						<span class="errorMsg" id="eMailerror"></span><br></br>
						Password: <input type="password" name="pWord1" id="pWord1"></input>
						<span class="errorMsg" id="pWord1error"></span><br></br>
						Re-enter Password: <input type="password" name="pWord2" id="pWord2"></input>
						<span class="errorMsg" id="pWord2error"></span>
						<p>
							<input type="submit" value="Submit"/>
							<input type="reset" value="Reset"/>
						</p>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<div class="row"><!-- Row for footer !-->
		<div class="col-xs-12">
			<div class="footer">
				<p class="p2">UR Space Copyright © 2016 All Rights Reserved</p>
			</div>
		</div>
	</div>
</div>
<script type = "text/javascript"  src = "scripts/signup1.js" ></script>
</body>
</html>
