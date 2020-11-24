<?php
	session_start();
	require_once "util.php";
	require_once "dbconnect.php";
	$_SESSION['username'] = '';
	$_SESSION['password'] = '';
	$_SESSION['id'] = '';
	$_SESSION['glc'] = false;
?>

<!DOCTYPE HTML>
<!--
	Author: Jake Waggoner
	Changes Made By: Noah Pearson
	Template Used: Spatial
	Date: 9-17-2020
	Filename: login.php
-->
<html>

	<!-- Fav Icon -->
	<link rel="shortcut icon" type="image/ico" href="http://corsair.cs.iupui.edu:24591/server_side_project/project/images/StateFairLogo-removed.png">

	<head>
		<title>SEFI Login</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="landing">

	<?php
		$username = '';
		$password = '';
		$msg = '';
		$isAdmin = false;
		$usernameLabel = 'Username/Email*';
		$usernameValue = '';
		$passwordLabel = 'Password*';
		$passwordValue = '';
		$hashedPassword = '';
		$errorHasOccured = false;
		// Check to see if the register button has been clicked
		if (isset($_POST['enter']))
		{
			if(validateNormalField($_POST['username'], $usernameLabel, $msg, 'Username must be populated', $usernameValue, $username) == false)
			{
				$errorHasOccured = true;
			}
			else
			{
				$_SESSION["username"] = $username;
			}

			if(validateNormalField($_POST['password'], $passwordLabel, $msg, 'Password must be populated', $passwordValue, $password) == false)
			{
				$errorHasOccured = true;
			}
			else
			{
				$_SESSION["password"] = $password;
			}
			
			//Check creds to make sure theyre in the database
			if($errorHasOccured == false)
			{
				$adminStmt = $con->prepare("SELECT adminID from ADMINISTRATOR where email = ? AND password = ?");
				$adminStmt->bindValue(1, strtoupper($username), PDO::PARAM_STR);
				$adminStmt->bindValue(2, $password, PDO::PARAM_STR);
				$adminStmt->execute();

				$adminRow = $adminStmt->fetch(PDO::FETCH_OBJ);

				$judgeStmt = $con->prepare("SELECT judgeID from JUDGE where email = ? AND password = ?");
				$judgeStmt->bindValue(1, strtoupper($username), PDO::PARAM_STR);
				$judgeStmt->bindValue(2, $password, PDO::PARAM_STR);
				$judgeStmt->execute();

				$judgeRow = $judgeStmt->fetch(PDO::FETCH_OBJ);

				//Neither a judge or admin
				if(empty($adminRow->adminID) && empty($judgeRow->judgeID))
				{
					$errorHasOccured = true;
					$msg = "Username and Password are incorrect";
				}
				//Both judge and admin
				elseif(empty($adminRow->adminID) == false && empty($judgeRow->judgeID) == false)
				{
					$isAdmin = true;
					$_SESSION['id'] = $adminRow->adminID;
				}
				// Just an admin
				elseif(empty($adminRow->adminID) == false && empty($judgeRow->judgeID))
				{
					$isAdmin = true;
					$_SESSION['id'] = $adminRow->adminID;
				}
				// Just a judge
				else
				{
					$isAdmin = false;
					$_SESSION['id'] = $judgeRow->judgeID;
				}

				if($isAdmin)
				{
					//Used to tell if the admin is a grade level chair or not
					$gradeLevelChairStmt = $con->prepare("SELECT level from v_ADMINISTRATOR where email = ?");
					$gradeLevelChairStmt->bindValue(1, strtoupper($username), PDO::PARAM_STR);
					$gradeLevelChairStmt->execute();

					$gradeLevelChairRow = $gradeLevelChairStmt->fetch(PDO::FETCH_OBJ);
					if($gradeLevelChairRow->level == 'Grade Level Chair')
					{
						$_SESSION['glc'] = true;
					}
				}

				$_SESSION["isAdmin"] = $isAdmin;
				// direct to main page where info is added
				Header ("Location:index.php");
			}
		}
	?>

			<?php include "banner.php"?>
            
			<!-- Input -->
			<section id="one" class="wrapper style1">
				<div class="container">
					<header class="major">
						<h2>Enter your Username and Password to Login</h2>
					</header>
				</div>
				<div class = "box alt">
					<div class = "row uniform">
					<div class = "4u"></div>
					<div class = "8u$">
						<p><?php echo $msg;?></p>
					</div>
					</div>
				</div>
				<form method="post" action="">
                    <div class="box alt">
					    <div class="row uniform">
                            <div class = "4u"></div>
                            <div class="4u align-center">
                                <strong><?php echo $usernameLabel?></strong>
                                <input type="text" name="username" id="username" value="<?php echo $usernameValue?>" placeholder="JohnDoe@Example.com"/>
                            </div>
                            <div class = "4u$"></div>

                            <div class = "4u"></div>
                            <div class="4u align-center">
							    <strong><?php echo $passwordLabel?></strong>
							    <input type="password" name="password" id="password" value="<?php echo $passwordValue?>" placeholder="Password123"/>
						    </div>
                            <div class = "4u$"></div>

                            <div class = "4u"></div>
                            <div class="4u align-center">
							    <ul class="actions">
                                    <input name="enter" class="btn special" type="submit" value="Login" />
                                </ul>
							    <small>*Required Field</small>
                            </div>
                            <div class = "4u$"></div>
                        </div>
                    </div>
				</form>
			</section>

		<!-- Footer -->
		<footer id="footer">
			<div class="container">
				<ul class="icons">
					<li><a href="register.php">Register</a></li>
				</ul>
				<ul class="copyright">
					<li>&copy; Copyright 2015 Science Education Foundation of Indiana, Inc.</li>
					<li>All Rights Reserved</li>
				</ul>
			</div>
		</footer>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
