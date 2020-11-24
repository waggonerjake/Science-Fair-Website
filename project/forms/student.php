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

$studentFirstNameAddLabel = "First Name*";
$studentEditLabel = "First Name*";
$studentFirstName = "";
$editStudentFirstName = "";
$studentFirstNameAddValue = "";
$studentFirstNameEditValue = "";

$studentLastNameAddLabel = "Last Name*";
$studentLastNameEditLabel = "Last Name*";
$studentLastName = "";
$editStudentLastName = "";
$studentLastNameAddValue = "";
$studentLastNameEditValue = "";

$studentMiddleNameAddLabel = "Middle Name";
$studentMiddleNameEditLabel = "Middle Name";
$studentMiddleName = "";
$editStudentMiddleName = "";
$studentMiddleNameAddValue = "";
$studentMiddleNameEditValue = "";

$studentGradeAddLabel = "Grade*";
$studentGradeEditLabel = "Grade*";
$studentGrade = "";
$editStudentGrade = "";

$studentProjectAddLabel = "Project Number*";
$studentProjectEditLabel = "Project Number*";
$studentProject = "";
$editStudentProject = "";

$studentGenderAddLabel = "Gender*";
$studentGenderEditLabel = "Gender*";
$studentGender = "";
$editStudentGender = "";

$studentCountyAddLabel = "County*";
$studentCountyEditLabel = "County*";
$studentCounty = "";
$editStudentCounty = "";

$studentCityAddLabel = "City*";
$studentCityEditLabel = "City*";
$studentCity = "";
$editStudentCity = "";

$studentSchoolAddLabel = "School Name*";
$studentSchoolEditLabel = "School Name*";
$studentSchool = "";
$editstudentSchool = "";

if (isset($_POST['studentAddEnter'])) {
	if (validateNormalField($_POST['studentFirstName'], $studentFirstNameAddLabel, $addMsg, "Student first name should be filled out completely.", $studentFirstNameAddValue, $studentFirstName) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentLastName'], $studentLastNameAddLabel, $addMsg, "Student last name should be filled out completely.", $studentLastNameAddValue, $studentLastName) == false) {
		$errorHasOccured = true;
	}

	if (empty(trim($_POST['studentMiddleName'])) == false) {
		$studentMiddleName = trim($_POST['studentMiddleName']);
		$studentMiddleNameAddValue = $studentMiddleName;
    }

	if (validateNormalField($_POST['studentGrade'], $studentGradeAddLabel, $addMsg, "Student grade should be filled out completely.", $dummy, $studentGrade) == false) {
		$errorHasOccured = true;
	}
	
	if (validateNormalField($_POST['studentProject'], $studentProjectAddLabel, $addMsg, "Student project level should be filled out completely.", $dummy, $studentProject) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentGender'], $studentGenderAddLabel, $addMsg, "Student gender level should be filled out completely.", $dummy, $studentGender) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentCounty'], $studentCountyAddLabel, $addMsg, "Student county should be filled out completely.", $dummy, $studentCounty) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentCity'], $studentCityAddLabel, $addMsg, "Student city should be filled out completely.", $dummy, $studentCity) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentSchool'], $studentSchoolAddLabel, $addMsg, "Student school name should be filled out completely.", $dummy, $studentSchool) == false) {
		$errorHasOccured = true;
	}

	//Check for duplicate entry in table
	if(sizeof(checkRowWithVaryingFields('STUDENT', ['firstName','lastName', 'middleName','gradeID','genderID','countyID','cityID','schoolID','projectID'], [$studentFirstName, $studentLastName, $studentMiddleName, $studentGrade, $studentGender, $studentCounty, $studentCity, $studentSchool, $studentProject])) != 0)
	{
		$errorHasOccured = true;
		echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("A student with that information already exists!", 5000);
        </script>';
	}

	if ($errorHasOccured == false) {
		$studentFirstNameAddValue = '';
		$studentLastNameAddValue = '';
		$studentMiddleNameAddValue = '';
		insertStudent($studentFirstName, $studentLastName, $studentMiddleName, $studentGrade, $studentProject, $studentGender, $studentCounty, $studentCity, $studentSchool);
		echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("A student was added successfully!", 5000);
        </script>';
	}
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Student").'", 5000);
        </script>';
    }
}

if (isset($_POST['studentEditEnter'])) {
	if (validateNormalField($_POST['studentEditFirstName'], $studentEditLabel, $editMsg, "Student first name should be filled out completely.", $studentFirstNameEditValue, $editStudentFirstName) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentEditLastName'], $studentLastNameEditLabel, $editMsg, "Student last name should be filled out completely.", $studentLastNameEditValue, $editStudentLastName) == false) {
		$errorHasOccured = true;
	}

	if (empty(trim($_POST['studentEditMiddleName'])) == false) {
		$editStudentMiddleName = trim($_POST['studentEditMiddleName']);
		$studentMiddleNameEditValue = $editStudentMiddleName;
    }

	if (validateNormalField($_POST['studentEditGrade'], $studentGradeEditLabel, $editMsg, "Student grade should be filled out completely.", $dummy, $editStudentGrade) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentEditProject'], $studentProjectEditLabel, $editMsg, "Student project should be filled out completely.", $dummy, $editStudentProject) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentEditGender'], $studentGenderEditLabel, $editMsg, "Student gender should be filled out completely.", $dummy, $editStudentGender) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentEditCounty'], $studentCountyEditLabel, $editMsg, "Student county should be filled out completely.", $dummy, $editStudentCounty) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentEditCity'], $studentCityEditLabel, $editMsg, "Student city should be filled out completely.", $dummy, $editStudentCity) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['studentEditSchoolName'], $studentSchoolEditLabel, $editMsg, "Student school name should be filled out completely.", $dummy, $editstudentSchool) == false) {
		$errorHasOccured = true;
	}

	//Check for duplicate entry in table
	if(sizeof(checkRowWithVaryingFields('STUDENT', ['firstName','lastName', 'middleName','gradeID','genderID','countyID','cityID','schoolID','projectID'], [$editStudentFirstName, $editStudentLastName, $editStudentMiddleName, $editStudentGrade, $editStudentGender, $editStudentCounty, $editStudentCity, $editstudentSchool, $editStudentProject,])) != 0)
	{
		$errorHasOccured = true;
		echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("A student with that information already exists!", 5000);
        </script>';
	}

	if ($errorHasOccured == false) {
		$studentFirstNameEditValue = '';
		$studentLastNameEditValue = '';
		$studentMiddleNameEditValue = '';
		$id = $_POST['studentEditID'];
		updateStudent($id, $editStudentFirstName, $editStudentLastName, $editStudentMiddleName, $editStudentGrade, $editStudentProject, $editStudentGender, $editStudentCounty, $editStudentCity, $editstudentSchool);
		echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("A student was edited successfully!", 5000);
        </script>';
	}
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Student").'", 5000);
        </script>';
    }
}

if (isset($_POST['studentDelEnter'])) {
	if (empty(trim($_POST['studentDelID'])) == false) {
		$id = $_POST['studentDelID'];
		$status = deleteRecordFromTable($id, "STUDENT", "studentID");
		if($status != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("'.$status.'", 9000);
			</script>';
		}
		else
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A student was deleted successfully!", 5000);
			</script>';
		}
	} else {
		$delMsg = "<span style=\"color:red\">Please choose a student to delete using the button above.</span>";
		echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Student").'", 5000);
        </script>';
	}
}

?>

<div class="2u"></div>
<div class="8u">
	<button type="button" class="collapsible">Student</button>
	<div class="content">
		<div class="12u$">
			<div class='tabcontainer'>
				<div class="tab">
					<button id="studentAddTab" class="tablinks active" onclick="changeTab(event, 'studentAdd', false,'studentAddTab')">Add</button>
					<button id="studentEditTab" class="tablinks" onclick="changeTab(event, 'studentEdit', false, 'studentEditTab')">Edit</button>
					<button id="studentDelTab" class="tablinks" onclick="changeTab(event, 'studentDelete', false, 'studentDelTab')">Delete</button>
				</div>
				<div id="studentAdd" class="tabcontent">
					<button id="addStudentTableButton" onclick="toggleTable('studentTableModal', 'student-close')">View Current Students</button>
					<p><?php echo $addMsg; ?></p>
					<form method="post" action="">
						<strong> <?php echo $studentFirstNameAddLabel ?> </strong>
						<input type="text" name="studentFirstName" id="studentFirstName" value="<?php echo $studentFirstNameAddValue ?>" placeholder="John" />
						<strong><?php echo $studentLastNameAddLabel ?></strong>
						<input type="text" name="studentLastName" id="studentLastName" value="<?php echo $studentLastNameAddValue ?>" placeholder="Doe" />
						<strong><?php echo $studentMiddleNameAddLabel ?></strong>
						<input type="text" name="studentMiddleName" id="studentMiddleName" value="<?php echo $studentMiddleNameAddValue ?>" placeholder="Jimmy" />
						<strong><?php echo $studentGradeAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentGrade" id="studentGrade">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'gradeID', field: 'grade', table: 'GRADE', placeholder: 'Grade'},
										success: function(data){
											$('#studentGrade').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $studentProjectAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentProject" id="studentProject">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'projectID', field: 'projectNumber', table: 'PROJECT', placeholder: 'Project'},
										success: function(data){
											$('#studentProject').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $studentGenderAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentGender" id="studentGender">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'genderID', field: 'genderName', table: 'GENDER', placeholder: 'Gender'},
										success: function(data){
											$('#studentGender').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $studentCountyAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentCounty" id="studentCounty">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'countyID', field: 'countyName', table: 'COUNTY', placeholder: 'County'},
										success: function(data){
											$('#studentCounty').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $studentCityAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentCity" id="studentCity">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'cityID', field: 'cityName', table: 'CITY', placeholder: 'City'},
										success: function(data){
											$('#studentCity').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $studentSchoolAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentSchool" id="studentSchool">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'schoolID', field: 'schoolName', table: 'SCHOOL', placeholder: 'School'},
										success: function(data){
											$('#studentSchool').html(data);
										}
									});
								</script>
							</select>
						</div>
						
						<div class="box alt align-right">
							<ul class="actions">
								<input name="studentAddEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>

				<div id="studentEdit" class="tabcontent">
					<button id="editStudentTableButton" onclick="toggleTable('studentTableModal', 'student-close')">Select Student to Edit</button>
					<p><?php echo $editMsg; ?></p>
					<form method="post" action="">
						<strong> <?php echo $studentEditLabel ?> </strong>
						<input type="text" name="studentEditFirstName" id="studentEditFirstName" value="<?php echo $studentFirstNameEditValue ?>" placeholder="John" />
						<strong><?php echo $studentLastNameEditLabel ?></strong>
						<input type="text" name="studentEditLastName" id="studentEditLastName" value="<?php echo $studentLastNameEditValue ?>" placeholder="Doe" />
						<strong><?php echo $studentMiddleNameEditLabel ?></strong>
						<input type="text" name="studentEditMiddleName" id="studentEditMiddleName" value="<?php echo $studentMiddleNameEditValue ?>" placeholder="Jimmy" />
						<strong><?php echo $studentGradeEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentEditGrade" id="studentEditGrade">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'gradeID', field: 'grade', table: 'GRADE', placeholder: 'Grade'},
										success: function(data){
											$('#studentEditGrade').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $studentProjectEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentEditProject" id="studentEditProject">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'projectID', field: 'projectNumber', table: 'PROJECT', placeholder: 'Project'},
										success: function(data){
											$('#studentEditProject').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $studentGenderEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentEditGender" id="studentEditGender">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'genderID', field: 'genderName', table: 'GENDER', placeholder: 'Gender'},
										success: function(data){
											$('#studentEditGender').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $studentCountyEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentEditCounty" id="studentEditCounty">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'countyID', field: 'countyName', table: 'COUNTY', placeholder: 'County'},
										success: function(data){
											$('#studentEditCounty').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $studentCityEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentEditCity" id="studentEditCity">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'cityID', field: 'cityName', table: 'CITY', placeholder: 'City'},
										success: function(data){
											$('#studentEditCity').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $studentSchoolEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="studentEditSchoolName" id="studentEditSchoolName">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'schoolID', field: 'schoolName', table: 'SCHOOL', placeholder: 'School'},
										success: function(data){
											$('#studentEditSchoolName').html(data);
										}
									});
								</script>
							</select>
						</div>
						
						
						<input type="hidden" name="studentEditID" id="studentEditID" value="" />

						<div class="box alt align-right">
							<ul class="actions">
								<input name="studentEditEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>

				<div id="studentDelete" class="tabcontent">
					<button id="delStudentTableButton" onclick="toggleTable('studentTableModal', 'student-close')">Select Student to Delete</button>
					<p><?php echo $delMsg; ?></p>
					<form method="post" action="">
						<strong>First Name*</strong>
						<input type="text" name="studentDelFirstName" id="studentDelFirstName" value="" placeholder="John" disabled />
						<strong>Last Name*</strong>
						<input type="text" name="studentDelLastName" id="studentDelLastName" value="" placeholder="Doe" disabled />
						<strong>Middle Name</strong>
						<input type="text" name="studentDelMiddleName" id="studentDelMiddleName" placeholder="Jimmy" disabled></textarea>
						<strong>Grade*</strong>
						<div class="select-wrapper">
							<select name="studentDelGrade" id="studentDelGrade" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'gradeID', field: 'grade', table: 'GRADE', placeholder: 'Grade'},
										success: function(data){
											$('#studentDelGrade').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong>Project Number*</strong>
						<div class="select-wrapper">
							<select name="studentDelProject" id="studentDelProject" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'projectID', field: 'projectNumber', table: 'PROJECT', placeholder: 'Project'},
										success: function(data){
											$('#studentDelProject').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong>Gender*</strong>
						<div class="select-wrapper">
							<select name="studentDelGender" id="studentDelGender" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'genderID', field: 'genderName', table: 'GENDER', placeholder: 'Gender'},
										success: function(data){
											$('#studentDelGender').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong>County*</strong>
						<div class="select-wrapper">
							<select name="studentDelCounty" id="studentDelCounty" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'countyID', field: 'countyName', table: 'COUNTY', placeholder: 'County'},
										success: function(data){
											$('#studentDelCounty').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong>City*</strong>
						<div class="select-wrapper">
							<select name="studentDelCity" id="studentDelCity" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'cityID', field: 'cityName', table: 'CITY', placeholder: 'City'},
										success: function(data){
											$('#studentDelCity').html(data);
										}
									});
								</script>
							</select>
						</div>

						<strong>School Name*</strong>
						<div class="select-wrapper">
							<select name="studentDelSchoolName" id="studentDelSchoolName" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'schoolID', field: 'schoolName', table: 'SCHOOL', placeholder: 'School'},
										success: function(data){
											$('#studentDelSchoolName').html(data);
										}
									});
								</script>
							</select>
						</div>

						<input type="hidden" name="studentDelID" id="studentDelID" value="" />

						<div class="box alt align-right">
							<ul class="actions">
								<input name="studentDelEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" language="javascript" class="init">
	var studentTable;
	$(document).ready(function() {
		studentTable = $('#studentTable').DataTable({
			"columnDefs": [{
				"targets": [0, 10, 11, 12, 13, 14, 15],
				"visible": false,
				"searchable": false
			}]
		});

		$('#editStudentTableButton').click(function() {
			$('#studentTable tbody').on('click', 'tr', function() {
				$(this).toggleClass('selected');
				var rowData = studentTable.rows('.selected').data()[0];
				$('#studentEditID').val(rowData[0]);
				$('#studentEditFirstName').val(rowData[1]);
				$('#studentEditLastName').val(rowData[2]);
				$('#studentEditMiddleName').val(rowData[3]);
				$('#studentEditGrade').val(rowData[10]);
				$('#studentEditGender').val(rowData[11]);
				$('#studentEditCounty').val(rowData[12]);
				$('#studentEditCity').val(rowData[13]);
				$('#studentEditSchoolName').val(rowData[14]);
				$('#studentEditProject').val(rowData[15]);

				$(this).toggleClass('selected');

				var studentTableModal = document.getElementById('studentTableModal');
                studentTableModal.style.display = "none";
			});
		});

		$('#delStudentTableButton').click(function() {
			$('#studentTable tbody').on('click', 'tr', function() {
				$(this).toggleClass('selected');
				var rowData = studentTable.rows('.selected').data()[0];
				$('#studentDelID').val(rowData[0]);
				$('#studentDelFirstName').val(rowData[1]);
				$('#studentDelLastName').val(rowData[2]);
				$('#studentDelMiddleName').val(rowData[3]);
				$('#studentDelGrade').val(rowData[10]);
				$('#studentDelGender').val(rowData[11]);
				$('#studentDelCounty').val(rowData[12]);
				$('#studentDelCity').val(rowData[13]);
				$('#studentDelSchoolName').val(rowData[14]);
				$('#studentDelProject').val(rowData[15]);

				$(this).toggleClass('selected');

				var studentTableModal = document.getElementById('studentTableModal');
                studentTableModal.style.display = "none";
			});
		});
	});
</script>
<div id="studentTableModal" class="modal">
	<div class="modal-content">
		<span id="student-close" class="modal-close">&times;</span>
		<?php
		$rows = selectAll("v_STUDENT");
		print '<table  id="studentTable" class="display" cellspacing="0" width="100%">';
		print '<caption>Make a selection from the table below to fill in the form</caption>';
		print '<thead>
            <tr><th>studentID</th><th>First Name</th><th>Last Name</th><th>Middle Name</th><th>Grade</th><th>Project Number</th><th>Gender</th><th>County Name</th><th>City Name</th><th>School Name</th><th>gradeID</th><th>genderID</th><th>countyID</th><th>cityID</th><th>schoolID</th><th>projectID</th></tr></thead><tfoot>';
		print '<tbody>';
		foreach ($rows as $row) {
			print '<tr>';
			print '<td>' . $row['studentID'] . '</td><td>' . $row['firstName'] . '</td><td>' . $row['lastName'] . '</td><td>' . $row['middleName'] . '</td><td>' . $row['grade'] . '</td><td>' . $row['projectNumber'] . '</td><td>' . $row['genderName'] . '</td><td>' . $row['countyName'] . '</td><td>' . $row['cityName'] . '</td><td>' . $row['schoolName'] . '</td><td>' . $row['gradeID'] . '</td><td>' . $row['genderID'] . '</td><td>' . $row['countyID'] . '</td><td>' . $row['cityID'] . '</td><td>' . $row['schoolID'] . '</td><td>' . $row['projectID'] . '</td>';
			print '</tr>';
		}
		print '</tbody>';
		print '</table>';
		?>
	</div>
</div>

<div class="2u$"></div>