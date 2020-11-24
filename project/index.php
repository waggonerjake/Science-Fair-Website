<?php
//Group1
	session_start();
	require_once "dbconnect.php";
	require_once "util.php";

	include "insertion.php";
	//Uncomment this out when we want session protection
	if(empty($_SESSION['username']) || empty($_SESSION['password']) || isset($_SESSION['isAdmin']) == false || empty($_SESSION['id']))
	{
		Header ("Location:login.php");
	}

	//The following code is placed in index because it needs to be ran every time the page gets refreshed
	//regardless of what caused it to refresh
	//***************************************************
	include "proc.php";
	//Used for setting avg ranks for projects
	$rows = selectAll("v_PROJECT");

	foreach($rows as $row)
	{
		$avg = callStoredProcedure('getAverageRank',[$row['projectID']], true);
		if(empty($avg) == false)
		{
			$avg = $avg[0]['average'];
		}
		callStoredProcedure('updateProjectRank',[$avg,$row['projectID']], false);
	}
	//***************************************************
?>

<!DOCTYPE HTML>
<!--
	Spatial by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>

	<meta charset="utf-8">

	<!-- Datatable CSS -->
	<link rel="stylesheet" type="text/css" href="assets/DataTables-1.10.3/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="assets/DataTables-1.10.3/examples/resources/syntax/shCore.css">
	<style type="text/css" class="init"></style>
	
	<!-- Template Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/util.js"></script>
	<script src="assets/js/main.js"></script>

	<!-- Datatable Scripts -->
	<script type="text/javascript" language="javascript" src="assets/DataTables-1.10.3/media/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="assets/DataTables-1.10.3/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="assets/DataTables-1.10.3/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="assets/DataTables-1.10.3/examples/resources/demo.js"></script>

	<!-- Fav Icon -->
	<link rel="shortcut icon" type="image/ico" href="http://corsair.cs.iupui.edu:24591/server_side_project/project/images/StateFairLogo-removed.png">

<head>
	<title>SEFI Dashboard</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="assets/css/main.css" />
</head>

<body class="landing">

	<div id="snackbar"></div>

	<?php include "banner.php";?>

	<script>
	function showSnackbar(message, timer) 
		{
			var toastMessage = document.getElementById("snackbar");
			toastMessage.textContent = message;
			toastMessage.className = "show";
			$('#toastCall').remove();
            setTimeout(function(){ toastMessage.className = toastMessage.className.replace("show", ""); }, timer);
        }
	</script>

	<section id="one" class="wrapper style1">
		<div class="box alt">
			<div class="row uniform 25%">
				<?php if ($_SESSION["isAdmin"] && !$_SESSION['glc']) : ?>
				<div class="2u"></div>
				<div class="8u">
					<h2>Admin Controls</h2>
				</div>
				<div class="2u$"></div>

				<?php
				include "upload.php";
				include "forms/schedule.php";
				include "forms/avgRanks.php";
				include "forms/session.php";
				include "forms/student.php";
				include "forms/grade.php";
				include "forms/school.php";
				include "forms/county.php";
				include "forms/city.php";
				include "forms/project.php";
				include "forms/project_grade_level.php";
				include "forms/category.php";
				include "forms/booth.php";
				include "forms/judge.php";
				include "forms/admin.php";
				?>
				<?php endif; ?>

				<?php if ($_SESSION["isAdmin"] && $_SESSION['glc']) : ?>
				<div class="2u"></div>
				<div class="8u">
					<h2>Grade Level Chair Controls</h2>
				</div>
				<div class="2u$"></div>

				<?php
				include "forms/schedule.php";
				include "forms/avgRanks.php";
				?>
				<?php endif; ?>

				<?php if (!$_SESSION["isAdmin"]) : ?>
				<div class="2u"></div>
				<div class="8u">
					<h2>Judge Controls</h2>
				</div>
				<div class="2u$"></div>

				<?php
				include "forms/checkin.php";
				include "forms/score.php"
				?>
				<?php endif; ?>
				<div class="2u"></div>
				<div class="8u">
					<br />
					<small>*Required Field</small>
				</div>
				<div class="2u$"></div>
			</div>
		</div>
	</section>

	<!-- Footer -->
	<footer id="footer">
		<div class="container">
			<?php if ($_SESSION["isAdmin"]) : ?>
			<ul class="icons">
				<li><a href="login.php">Logout</a></li>
			</ul>
			<?php endif; ?>
			<ul class="copyright">
				<li>&copy; Copyright 2015 Science Education Foundation of Indiana, Inc.</li>
				<li>All Rights Reserved</li>
			</ul>
		</div>
	</footer>

	<script>
		var coll = document.getElementsByClassName("collapsible");
		var i;

		for (i = 0; i < coll.length; i++) {
			coll[i].addEventListener("click", function() {
				this.classList.toggle("active");
				var content = this.nextElementSibling;
				if (content.style.display === "inline") {
					content.style.display = "none";
				} else {
					content.style.display = "inline";
					var linkID = content.getElementsByClassName('tablinks')[0].id;
					var tabcontentID = content.getElementsByClassName('tabcontent')[0].id;
					onclick = changeTab(event, tabcontentID, true, linkID);
				}
			});
		}

		function changeTab(evt, tab, init = false, tabLinkID) {
			var i, tabcontents, tablinks;
			var container = document.getElementById(tabLinkID).closest('.tabcontainer');
			tabcontents = container.children;

			for (i = 0; i < tabcontents.length; i++) {
				if(tabcontents[i].className.includes("tabcontent"))
				{
					tabcontents[i].style.display = "none";
				}
			}

			tablinks = container.getElementsByClassName("tablinks");
			for (i = 0; i < tablinks.length; i++) {
				tablinks[i].className = tablinks[i].className.replace(" active", "");
			}
			
			document.getElementById(tab).style.display = "block";
			
			if(init == false)
				evt.currentTarget.className += " active";
			else
				document.getElementById(tabLinkID).className += " active";
			
		}

		function toggleTable(modalName, spanName) {
			var modal = document.getElementById(modalName);
			var span = document.getElementById(spanName);

			modal.style.display = "block";
			span.onclick = function() {
				modal.style.display = "none";
			}
			window.onclick = function(event) {
				if (event.target == modal) {
					modal.style.display = "none";
				}
			}
		}
	</script>
</body>
</html>
