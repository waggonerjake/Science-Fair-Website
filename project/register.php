<?php
	require_once "util.php";
	require_once "insertion.php";
	require_once "selection.php";
?>

<!DOCTYPE HTML>
<!--
	TODO: Have a way to stop execution and let user
	know if judge already exists (same goes for anything else).
-->
<html>

	<!-- Fav Icon -->
	<link rel="shortcut icon" type="image/ico" href="http://corsair.cs.iupui.edu:24591/server_side_project/project/images/StateFairLogo-removed.png">

	<!-- Template Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/util.js"></script>
	<script src="assets/js/main.js"></script>

	<head>
		<title>Judge Registration</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="landing">

	<?php
		//showResults hides certain elements of the page given the value
		// i.e. false = show the form; true = show the results of the form
		// This is used instead of switching pages simply because I only wanted 
		// to have the one file 
		$showResults = false;
		$firstname = '';
		$middlename ='N/A';
		$lastname = '';
		$title = '';
		$employer = '';
		$email = '';
		$confirmEmail = '';
		$degree = '';
		$category1Pref = '';
		$category2Pref = '';
		$category3Pref = ''; // 2 and 3 will be optional
		$gradePref1 = '';
		$gradePref2 = '';
		$gradePref3 = '';
		$msg = '';

		$firstnameLabel = 'First Name*';
		$firstnameValue = '';
		$middlenameValue = '';
		$lastnameLabel = 'Last Name*';
		$lastnameValue = '';
		$titleLabel = 'Title*';
		$titleValue = '';
		$employerLabel = 'Employer*';
		$employerValue = '';
		$emailLabel = 'Email*';
		$emailValue = '';
		$confirmEmailLabel = 'Confirm Email*';
		$confirmEmailValue = '';
		$category1Label = 'Category Preference 1*';
		$gradePrefLabel = 'Student Grade Level Preference 1*';
		$errorHasOccured = false;

		$categoryList = array();
		$gradeList = array();
		$categoryValues = '';
		$gradeValues = '';

		// Check to see if the register button has been clicked
		if (isset($_POST['judgeEnter']))
		{
			//-------------- Name Validation ---------------------
			if(validateNormalField($_POST['firstName'], $firstnameLabel, $msg, 'First Name must be populated', $firstnameValue, $firstname) == false)
			{
				$errorHasOccured = true;
			}

			//Middle name isnt required so we dont need to do validation
			if(empty(trim($_POST['middleName'])) == false)
			{
				$middlename = trim($_POST['middleName']);
				$middlenameValue = $middlename;
			}

			if(validateNormalField($_POST['lastName'], $lastnameLabel, $msg, 'Last Name must be populated', $lastnameValue, $lastname) == false)
			{
				$errorHasOccured = true;
			}

			//-------------- Title & Employer Validation ---------------------
			if(validateNormalField($_POST['title'], $titleLabel, $msg, 'Title must be populated', $titleValue, $title) == false)
			{
				$errorHasOccured = true;
			}			

			if(validateNormalField($_POST['employer'], $employerLabel, $msg, 'Employer must be populated', $employerValue, $employer) == false)
			{
				$errorHasOccured = true;
			}

			//-------------- Email Validation ---------------------
			//use filter_input function to validate email
			if (!filter_input(INPUT_POST, 'email',FILTER_VALIDATE_EMAIL)) 
			{
				$emailLabel = '<span style="color:red">Email*</span>';
				$errorHasOccured = true;
				if(empty($msg))
				{
					$msg = 'Things to fix: Email must be populated';
				}
				else $msg = $msg.", Email must be populated";
			}
			else 
			{
				$email = trim($_POST['email']);
				$emailValue = $email;
			}

			if (!filter_input(INPUT_POST, 'confirmEmail',FILTER_VALIDATE_EMAIL))
			{
				$confirmEmailLabel = '<span style="color:red">Confirm Email*</span>';
				$errorHasOccured = true;
				if(empty($msg))
				{
					$msg = 'Things to fix: Confirm Email must be populated';
				}
				else $msg = $msg.", Confirm Email must be populated";
			}
			else 
			{
				$confirmEmail = trim($_POST['confirmEmail']);
				$confirmEmailValue = $confirmEmail;
			}

			// check if the email fields are invalid (empty) before comparing them
			if(strpos($confirmEmailLabel, 'red') == false && strpos($emailLabel, 'red') == false)
			{
				if($confirmEmail != $email)
				{
					$errorHasOccured = true;
					if(empty($msg))
					{
						$msg = 'Things to fix: Emails do not match';
					}
					else $msg = $msg.", Emails do not match";
					$emailLabel = '<span style="color:red">Email*</span>';
					$confirmEmailLabel = '<span style="color:red">Confirm Email*</span>';
				}
			}

			//Check if email is already registered
			if(strpos($confirmEmailLabel, 'red') == false && strpos($emailLabel, 'red') == false)
			{
				if(sizeof(getRowsByField('JUDGE','email',$email)) != 0)
				{
					$errorHasOccured = true;
					if(empty($msg))
					{
						$msg = 'Things to fix: Email has already been registered';
					}
					else $msg = $msg.", Email has already been registered";
					$emailLabel = '<span style="color:red">Email*</span>';
					$confirmEmailLabel = '<span style="color:red">Confirm Email*</span>';
				}
			}

			//-------------- Degree Validation ---------------------
			$degree = trim($_POST['degree']); // has a default value so it doesnt need validation

			//-------------- Category Preference Validation ---------------------
			if(validateNormalField($_POST['cat1'], $category1Label, $msg, 'Category 1 preference must be selected', $dummy, $category1Pref) == false)
			{
				$errorHasOccured = true;
			}
			else
			{
				array_push($categoryList, $category1Pref);
			}

			//Are optional, so just set them to whatever is there
			$category2Pref = trim($_POST['cat2']);
			$category3Pref = trim($_POST['cat3']);

			//1 has priority over 2, 2 has priority over 3. 
			//neither has a preference rating or level logic, but
			//just for this check we're saying that so we know
			//which to clear (prevent duplicates)
			if($category2Pref == $category1Pref)
			{
				$category2Pref = '';
			}
			elseif(empty($category2Pref) == false)
			{
				array_push($categoryList, $category2Pref);
			}

			if($category3Pref == $category1Pref)
			{
				$category3Pref = '';
			}

			if($category3Pref == $category2Pref)
			{
				$category3Pref = '';
			}

			if(empty($category3Pref) == false)
			{
				array_push($categoryList, $category3Pref);
			}

			//Get actual values rather than ID of category
			foreach ($categoryList as $ID)
			{
				$rows = getRowsByField('CATEGORY', 'categoryID', $ID);
				$name = $rows[0]['categoryName'];
				if(empty($categoryValues))
				{
					$categoryValues = $name;
				}
				else
				{
					$categoryValues = $categoryValues.", ".$name;
				}
			}

			//-------------- Grade Preference Validation ---------------------
			if(validateNormalField($_POST['projGradePref1'], $gradePrefLabel, $msg, 'Grade level 1 preference must be selected', $dummy, $gradePref1) == false)
			{
				$errorHasOccured = true;
			}			
			else
			{
				array_push($gradeList, $gradePref1);
			}

			$gradePref2 = trim($_POST['projGradePref2']);
			$gradePref3 = trim($_POST['projGradePref3']);
			//Same kind of logic as with categories
			if($gradePref2 == $gradePref1)
			{
				$gradePref2 = '';
			}
			elseif(empty($gradePref2) == false)
			{
				array_push($gradeList, $gradePref2);
			}

			if($gradePref3 == $gradePref1)
			{
				$gradePref3 = '';
			}

			if($gradePref3 == $gradePref2)
			{
				$gradePref3 = '';
			}

			if(empty($gradePref3) == false)
			{
				array_push($gradeList, $gradePref3);
			}

			foreach ($gradeList as $ID)
			{
				$rows = getRowsByField('PROJECT_GRADE_LEVEL', 'projectGradeID', $ID);
				$name = $rows[0]['levelName'];
				if(empty($gradeValues))
				{
					$gradeValues = $name;
				}
				else
				{
					$gradeValues = $gradeValues.", ".$name;
				}
			}

			// if no error has occured, be sure to clear the form so new information can
			// be entered, otherwise keep the information (except for the checkboxes and dropdown, and do again)
			if($errorHasOccured)
			{
				$showResults = false;
			}
			else 
			{
				$showResults = true;
				$firstnameValue = '';
				$middlenameValue = '';
				$lastnameValue = '';
				$emailValue = '';
				$confirmEmailValue = '';
				$employerValue = '';
				$titleValue = '';
			}

			// if everything passed, insert the judge and their preferences into the database
			if($showResults)
			{
				$generatedPassword = generateCode(15);
				sendMail($email,$generatedPassword);
				insertJudge($firstname,$lastname,$middlename,$title,$degree,$employer,$email,$generatedPassword);
				insertJudgeCatPref($email, $category1Pref, $category2Pref, $category3Pref);
				insertJudgeGradPref($email, $gradePref1, $gradePref2, $gradePref3);
			}
		}

		if(isset($_POST['back']))
			$showResults = false;
	?>

			<?php include "banner.php"?>

			<!-- Input Form -->
			<!-- 
				This php if statement use an alternative syntax that makes it easier to read.
				Found here: https://www.php.net/manual/en/control-structures.alternative-syntax.php
				This basically says if showResults is false, display this section of HTML code
			-->
			<?php if ($showResults == false) : ?>
			<section id="one" class="wrapper style1">
				<div class="container">
					<header class="major">
						<h2>Enter your registration information below</h2>
					</header>
					<p><?php echo $msg;?></p>
				</div>
				<form method="post" action="register.php">
					<div class="row uniform 50%">

						<div class="4u 12u$(xsmall)">
							<strong><?php echo $firstnameLabel?></strong>
							<input type="text" name="firstName" id="firstName" value="<?php echo $firstnameValue?>" placeholder="John"/>
						</div>
						<div class="4u 12u$(xsmall)">
							<strong>Middle Name</strong>
							<input type="text" name="middleName" id="middleName" value="<?php echo $middlenameValue?>" placeholder="James"/>
						</div>
						<div class="4u$ 12u$(xsmall)">
							<strong><?php echo $lastnameLabel?></strong>
							<input type="text" name="lastName" id="lastName" value="<?php echo $lastnameValue?>" placeholder="Doe"/>
						</div>

						<div class="6u 12u$(xsmall)">
							<strong><?php echo $titleLabel?></strong>
							<input type="text" name="title" id="title" value="<?php echo $titleValue?>" placeholder="Software Developer"/>
						</div>
						<div class="6u$ 12u$(xsmall)">
							<strong><?php echo $employerLabel?></strong>
							<input type="text" name="employer" id="employer" value="<?php echo $employerValue?>" placeholder="Microsoft"/>
						</div>

						<div class="6u 12u$(xsmall)">
							<strong><?php echo $emailLabel?></strong>
							<input type="email" name="email" id="email" value="<?php echo $emailValue?>" placeholder="JohnDoe@example.com"/>
						</div>
						<div class="6u$ 12u$(xsmall)">
							<strong><?php echo $confirmEmailLabel?></strong>
							<input type="email" name="confirmEmail" id="confirmEmail" value="<?php echo $confirmEmailValue?>" placeholder="JohnDoe@example.com"/>
						</div>

						<div class="12u$">
							<div class="select-wrapper">
								<label for="degree">Highest Degree Earned</label>
								<select name="degree" id="degree">
									<option value="GED">High School Diploma/GED</option>
									<option value="Associate">Associate</option>
									<option value="Bachelor" selected>Bachelor</option>
									<option value="Master">Master</option>
									<option value="Phd">Doctorate/PHD</option>
								</select>
							</div>
						</div>

						<div class="4u">
							<div class="select-wrapper">
								<label for="cat1"><?php echo $category1Label?></label>
								<select name="cat1" id="cat1">
									<script>
										$.ajax({
											type: "GET",
											url: 'util.php',
											data: {id: 'categoryID', field: 'categoryName', table: 'CATEGORY', placeholder: 'Category 1'},
											success: function(data){
												$('#cat1').html(data);
											}
										});
									</script>
								</select>
							</div>
						</div>
						<div class="4u">
							<div class="select-wrapper">
								<label for="cat2">Category Preference 2</label>
								<select name="cat2" id="cat2">
									<script>
										$.ajax({
											type: "GET",
											url: 'util.php',
											data: {id: 'categoryID', field: 'categoryName', table: 'CATEGORY', placeholder: 'Category 2'},
											success: function(data){
												$('#cat2').html(data);
											}
										});
									</script>
								</select>
							</div>
						</div>
						<div class="4u$">
							<div class="select-wrapper">
								<label for="cat3">Category Preference 3</label>
								<select name="cat3" id="cat3">
									<script>
										$.ajax({
											type: "GET",
											url: 'util.php',
											data: {id: 'categoryID', field: 'categoryName', table: 'CATEGORY', placeholder: 'Category 3'},
											success: function(data){
												$('#cat3').html(data);
											}
										});
									</script>
								</select>
							</div>
						</div>

						<div class="4u">
							<div class="select-wrapper">
								<label for="projGradePref1"><?php echo $gradePrefLabel?></label>
								<select name="projGradePref1" id="projGradePref1">
									<script>
										$.ajax({
											type: "GET",
											url: 'util.php',
											data: {id: 'projectGradeID', field: 'levelName', table: 'PROJECT_GRADE_LEVEL', placeholder: 'Grade Level 1'},
											success: function(data){
												$('#projGradePref1').html(data);
											}
										});
									</script>
								</select>
							</div>
						</div>
						<div class="4u">
							<div class="select-wrapper">
								<label for="projGradePref2">Student Grade Level Preference 2</label>
								<select name="projGradePref2" id="projGradePref2">
									<script>
										$.ajax({
											type: "GET",
											url: 'util.php',
											data: {id: 'projectGradeID', field: 'levelName', table: 'PROJECT_GRADE_LEVEL', placeholder: 'Grade Level 2'},
											success: function(data){
												$('#projGradePref2').html(data);
											}
										});
									</script>
								</select>
							</div>
						</div>
						<div class="4u$">
							<div class="select-wrapper">
								<label for="projGradePref3">Student Grade Level Preference 2</label>
								<select name="projGradePref3" id="projGradePref3">
									<script>
										$.ajax({
											type: "GET",
											url: 'util.php',
											data: {id: 'projectGradeID', field: 'levelName', table: 'PROJECT_GRADE_LEVEL', placeholder: 'Grade Level 3'},
											success: function(data){
												$('#projGradePref3').html(data);
											}
										});
									</script>
								</select>
							</div>
						</div>

						<div class="12u$">
							<ul class="actions">
								<input name="judgeEnter" class="btn special" type="submit" value="Register" />
							</ul>
							<small>*Required Field</small>
						</div>
					</div>
				</form>
			</section>
			<?php endif; ?>
			
			<!-- Results Fields -->
			<?php if ($showResults == true) : ?>
		    	<section id="two" class="wrapper style2 special">
					<div class="container">
				  		<header class="major">
							<h3>The following registration information has been submitted</h3>
							<p><?php echo $msg ?></p>
						</header>
						<div class="row uniform 50%">
						<div class="6u 12u$(xsmall)">
							<p>First name: <?php echo $firstname ?></p>
						</div>
						<div class="6u$ 12u$(xsmall)">
							<p>Last name: <?php echo $lastname ?></p>
						</div>
						<div class="6u 12u$(xsmall)">
							<p>Middle name: <?php echo $middlename ?></p>
						</div>
						<div class="6u$ 12u$(xsmall)">
							<p>Email: <?php echo $email ?></p>
						</div>
						<div class="6u 12u$(xsmall)">
							<p>Degree: <?php echo $degree ?></p>
						</div>
						<div class="6u$ 12u$(xsmall)">
							<p>Title: <?php echo $title ?></p>
						</div>
						<div class="6u 12u$(xsmall)">
							<p>Employer: <?php echo $employer ?></p>
						</div>
						<div class="6u$ 12u$(xsmall)">
							<p>Category Preferences: <?php echo $categoryValues ?></p>
						</div>
						<div class="6u 12u$(xsmall)">
							<p>Grade Preference: <?php echo $gradeValues ?></p>
						</div>
					</div>
					<form method="post" action="register.php">
						<div class="12u$">
							<ul class="actions">
								<input name="back" class="btn special" type="submit" value="Register another person"/>
							</ul>
						</div>
					</form>
			  	</section>
			<?php endif; ?>

		<!-- Footer -->
		<footer id="footer">
			<div class="container">
				<ul class="icons">
					<li><a href="login.php">Login</a></li>
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