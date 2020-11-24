<?php
	require_once "dbconnect.php";
	require_once "util.php";
	require_once "insertion.php";
	require_once "selection.php";
	require_once "update.php";
	require_once "deletion.php";

	$errorHasOccured = false;
	$addMsg = "";
	$editMsg = "";
	$delMsg = "";

	$schoolAddLabel = "School Name*";
	$schoolEditLabel = "School Name*";
	$schoolName = "";
	$editSchoolName = "";
	$schoolNameAddValue = "";
	$schoolNameEditValue = "";

	$schoolCountyAddLabel = "County*";
	$schoolCountyEditLabel = "County*";
	$schoolCounty = "";
	$editSchoolCounty = "";

	$schoolCityAddLabel = "City*";
	$schoolCityEditLabel = "City*";
	$schoolCity = "";
	$editSchoolCity = "";

if (isset($_POST['schoolAddEnter'])) {
	if (validateNormalField($_POST['schoolName'], $schoolAddLabel, $addMsg, "School name should be filled out completely.", $schoolNameAddValue, $schoolName) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['schoolCounty'], $schoolCountyAddLabel, $addMsg, "School county should be filled out completely.", $dummy, $schoolCounty) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['schoolCity'], $schoolCityAddLabel, $addMsg, "School city should be filled out completely.", $dummy, $schoolCity) == false) {
		$errorHasOccured = true;
	}

	if ($errorHasOccured == false) {
		$status = insertSchool($schoolName, $schoolCounty, $schoolCity);
		if($status != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar(`'.$status.'`, 9000);
			</script>';
		}
		else
		{
			$schoolNameValue = '';
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A school was added successfully!", 5000);
			</script>';
		}
	}
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("School").'", 5000);
        </script>';
    }
}

if (isset($_POST['schoolEditEnter'])) {
	if (validateNormalField($_POST['schoolEditName'], $schoolEditLabel, $editMsg, "School name should be filled out completely.", $schoolNameEditValue, $editSchoolName) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['schoolEditCounty'], $schoolCountyEditLabel, $editMsg, "School county should be filled out completely.", $dummy, $editSchoolCounty) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['schoolEditCity'], $schoolCityEditLabel, $editMsg, "School city should be filled out completely.", $dummy, $editSchoolCity) == false) {
		$errorHasOccured = true;
	}


	if ($errorHasOccured == false) {
		$id = $_POST['schoolEditID'];
		$status = updateSchool($id, $editSchoolName, $editSchoolCounty, $editSchoolCity);
		if($status != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar(`'.$status.'`, 9000);
			</script>';
		}
		else
		{
			$schoolNameEditValue = '';
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A school was edited successfully!", 5000);
			</script>';
		}
	}
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("School").'", 5000);
        </script>';
    }
}

if (isset($_POST['schoolDelEnter'])) {
	if (empty(trim($_POST['schoolDelID'])) == false) {
		$id = $_POST['schoolDelID'];
		$status = deleteRecordFromTable($id, "SCHOOL", "schoolID");
		if($status != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar(`'.$status.'`, 9000);
			</script>';
		}
		else
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A school was deleted successfully!", 5000);
			</script>';
		}
	} else {
		$delMsg = "<span style=\"color:red\">Please choose a school to delete using the button above.</span>";
		echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("School").'", 5000);
        </script>';
	}
}
?>

<div class = "2u"></div><div class="8u">
	<button type="button" class="collapsible">School</button>
	<div class="content">
		<div class="12u$">
			<div class='tabcontainer'>
				<div class="tab">
					<button id="schoolAddTab" class="tablinks active" onclick="changeTab(event, 'schoolAdd', false,'schoolAddTab')">Add</button>
					<button id="schoolEditTab" class="tablinks" onclick="changeTab(event, 'schoolEdit', false, 'schoolEditTab')">Edit</button>
					<button id="schoolDelTab" class="tablinks" onclick="changeTab(event, 'schoolDelete', false, 'schoolDelTab')">Delete</button>
				</div>
				<div id="schoolAdd" class="tabcontent">
					<button id="addSchoolTableButton" onclick="toggleTable('schoolTableModal', 'school-close')">View Current Schools</button>
					<p><?php echo $addMsg; ?></p>
					<form method="post" action="">
						<strong> <?php echo $schoolAddLabel ?> </strong>
						<input type="text" name="schoolName" id="schoolName" value="<?php echo $schoolNameAddValue ?>" placeholder="Indianapolis High School" />
						<strong><?php echo $schoolCountyAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="schoolCounty" id="schoolCounty">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'countyID', field: 'countyName', table: 'COUNTY', placeholder: 'County'},
										success: function(data){
											$('#schoolCounty').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $schoolCityAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="schoolCity" id="schoolCity">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'cityID', field: 'cityName', table: 'CITY', placeholder: 'City'},
										success: function(data){
											$('#schoolCity').html(data);
										}
									});
								</script>
							</select>
						</div>
						<div class="box alt align-right">
							<ul class="actions">
								<input name="schoolAddEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>

				<div id="schoolEdit" class="tabcontent">
					<button id="editSchoolTableButton" onclick="toggleTable('schoolTableModal', 'school-close')">Select School to Edit</button>
					<p><?php echo $editMsg; ?></p>
					<form method="post" action="">
						<strong> <?php echo $schoolEditLabel ?> </strong>
						<input type="text" name="schoolEditName" id="schoolEditName" value="<?php echo $schoolNameEditValue ?>" placeholder="Indianapolis High School" />
						<strong><?php echo $schoolCountyEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="schoolEditCounty" id="schoolEditCounty">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'countyID', field: 'countyName', table: 'COUNTY', placeholder: 'County'},
										success: function(data){
											$('#schoolEditCounty').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $schoolCityEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="schoolEditCity" id="schoolEditCity">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'cityID', field: 'cityName', table: 'CITY', placeholder: 'City'},
										success: function(data){
											$('#schoolEditCity').html(data);
										}
									});
								</script>
							</select>
						</div>
						
						<input type="hidden" name="schoolEditID" id="schoolEditID" value="" />

						<div class="box alt align-right">
							<ul class="actions">
								<input name="schoolEditEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>

				<div id="schoolDelete" class="tabcontent">
					<button id="delSchoolTableButton" onclick="toggleTable('schoolTableModal', 'school-close')">Select School to Delete</button>
					<p><?php echo $delMsg; ?></p>
					<form method="post" action="">
						<strong>School Name*</strong>
						<input type="text" name="schoolDelName" id="schoolDelName" value="" placeholder="Select a school to delete using the button above" disabled />
						<strong>County*</strong>
						<div class="select-wrapper">
							<select name="schoolDelCounty" id="schoolDelCounty" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'countyID', field: 'countyName', table: 'COUNTY', placeholder: 'County'},
										success: function(data){
											$('#schoolDelCounty').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong>City*</strong>
						<div class="select-wrapper">
							<select name="schoolDelCity" id="schoolDelCity" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'cityID', field: 'cityName', table: 'CITY', placeholder: 'City'},
										success: function(data){
											$('#schoolDelCity').html(data);
										}
									});
								</script>
							</select>
						</div>
						
						<input type="hidden" name="schoolDelID" id="schoolDelID" value="" />

						<div class="box alt align-right">
							<ul class="actions">
								<input name="schoolDelEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" language="javascript" class="init">
	var schoolTable;
	$(document).ready(function() {
		schoolTable = $('#schoolTable').DataTable({
			"columnDefs": [{
				"targets": [0,4,5],
				"visible": false,
				"searchable": false
			}]
		});

		$('#editSchoolTableButton').click(function() {
			$('#schoolTable tbody').on('click', 'tr', function() {
				$(this).toggleClass('selected');
				var rowData = schoolTable.rows('.selected').data()[0];
				$('#schoolEditID').val(rowData[0]);
				$('#schoolEditName').val(rowData[1]);
				$('#schoolEditCity').val(rowData[4]);
				$('#schoolEditCounty').val(rowData[5]);

				$(this).toggleClass('selected');

				var schoolTableModal = document.getElementById('schoolTableModal');
                schoolTableModal.style.display = "none";
			});
		});

		$('#delSchoolTableButton').click(function() {
			$('#schoolTable tbody').on('click', 'tr', function() {
				$(this).toggleClass('selected');
				var rowData = schoolTable.rows('.selected').data()[0];
				$('#schoolDelID').val(rowData[0]);
				$('#schoolDelName').val(rowData[1]);
				$('#schoolDelCity').val(rowData[4]);
				$('#schoolDelCounty').val(rowData[5]);

				$(this).toggleClass('selected');

				var schoolTableModal = document.getElementById('schoolTableModal');
                schoolTableModal.style.display = "none";
			});
		});
	});
</script>

<div id="schoolTableModal" class="modal">
	<div class="modal-content">
		<span id="school-close" class="modal-close">&times;</span>
		<?php
		$rows = selectAll("v_SCHOOL");
		print '<table  id="schoolTable" class="display" cellspacing="0" width="100%">';
		print '<caption>Make a selection from the table below to fill in the form</caption>';
		print '<thead>
            <tr><th>schoolID</th><th>School Name</th><th>City Name</th><th>County Name</th><th>cityID</th><th>countyID</th></tr></thead><tfoot>';
		print '<tbody>';
		foreach ($rows as $row) {
			print '<tr>';
			print '<td>' . $row['schoolID'] . '</td><td>' . $row['schoolName'] . '</td><td>' . $row['cityName'] . '</td><td>' . $row['countyName'] . '</td><td>' . $row['cityID'] . '</td><td>' . $row['countyID'] . '</td>';
			print '</tr>';
		}
		print '</tbody>';
		print '</table>';
		?>
	</div>
</div>

<div class = "2u$"></div>