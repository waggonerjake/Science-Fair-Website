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

$projectAddLabel = "Project Number*";
$projectEditLabel = "Project Number*";
$projectNumber = "";
$editProjectNumber = "";
$projectNumberAddValue = "";
$projectNumberEditValue = "";

$projectTitleAddLabel = "Title*";
$projectTitleEditLabel = "Title*";
$projectTitle = "";
$editProjectTitle = "";
$projectTitleAddValue = "";
$projectTitleEditValue = "";

$projectAbstractAddLabel = "Abstract*";
$projectAbstractEditLabel = "Abstract*";
$projectAbstract = "";
$editProjectAbstract = "";
$projectAbstractAddValue = "";
$projectAbstractEditValue = "";

$projectCategoryAddLabel = "Category*";
$projectCategoryEditLabel = "Category*";
$projectCategory = "";
$editProjectCategory = "";

$projectGradeLevelAddLabel = "Grade Level*";
$projectGradeLevelEditLabel = "Grade Level*";
$projectGradeLevel = "";
$editProjectGradeLevel = "";

$projectBoothAddLabel = "Booth*";
$projectBoothEditLabel = "Booth*";
$projectBoothNumber = "";
$editProjectBoothNumber = "";

$projectCNAddLabel = "Course Networking ID*";
$projectCNEditLabel = "Course Networking ID*";
$projectCourseNetworkId = "";
$editProjectCourseNetworkId = "";
$projectCNAddValue = "";
$projectCNEditValue = "";

if (isset($_POST['projectAddEnter'])) {
	if (validateNormalField($_POST['projectNumber'], $projectAddLabel, $addMsg, "Project number should be filled out completely.", $projectNumberAddValue, $projectNumber) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectTitle'], $projectTitleAddLabel, $addMsg, "Project title should be filled out completely.", $projectTitleAddValue, $projectTitle) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectAbstract'], $projectAbstractAddLabel, $addMsg, "Project abstract should be filled out completely.", $projectAbstractAddValue, $projectAbstract) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectCategory'], $projectCategoryAddLabel, $addMsg, "Project category should be filled out completely.", $dummy, $projectCategory) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectGradeLevel'], $projectGradeLevelAddLabel, $addMsg, "Project grade level should be filled out completely.", $dummy, $projectGradeLevel) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectBoothNumber'], $projectBoothAddLabel, $addMsg, "Project booth number should be filled out completely.", $dummy, $projectBoothNumber) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectCourseNetworkId'], $projectCNAddLabel, $addMsg, "Project course networking id should be filled out completely.", $projectCNAddValue, $projectCourseNetworkId) == false) {
		$errorHasOccured = true;
	}

	if ($errorHasOccured == false) {
		$status = insertProject($projectNumber, $projectTitle, $projectAbstract, $projectGradeLevel, $projectCategory, $projectBoothNumber, $projectCourseNetworkId);
		if($status != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar(`'.$status.'`, 9000);
			</script>';
		}
		else
		{
			$projectNumberValue = '';
			$projectTitleValue = '';
			$projectAbstractValue = '';
			$projectCNValue = '';
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A project was added successfully!", 5000);
			</script>';
		}
	}
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Project").'", 5000);
        </script>';
    }
}

if (isset($_POST['projectEditEnter'])) {
	if (validateNormalField($_POST['projectEditNumber'], $projectEditLabel, $editMsg, "Project number should be filled out completely.", $projectNumberEditValue, $editProjectNumber) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectEditTitle'], $projectTitleEditLabel, $editMsg, "Project title should be filled out completely.", $projectTitleEditValue, $editProjectTitle) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectEditAbstract'], $projectAbstractEditLabel, $editMsg, "Project abstract should be filled out completely.", $projectAbstractEditValue, $editProjectAbstract) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectEditCategory'], $projectCategoryEditLabel, $editMsg, "Project category should be filled out completely.", $dummy, $editProjectCategory) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectEditGradeLevel'], $projectGradeLevelEditLabel, $editMsg, "Project grade level should be filled out completely.", $dummy, $editProjectGradeLevel) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectEditBoothNumber'], $projectBoothEditLabel, $editMsg, "Project booth number should be filled out completely.", $dummy, $editProjectBoothNumber) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['projectEditCourseNetworkId'], $projectCNEditLabel, $editMsg, "Project course networking id should be filled out completely.", $projectCNEditValue, $editProjectCourseNetworkId) == false) {
		$errorHasOccured = true;
	}


	if ($errorHasOccured == false) {
		$id = $_POST['projectEditID'];
		$status = updateProject($id, $editProjectNumber, $editProjectTitle, $editProjectAbstract, $editProjectGradeLevel, $editProjectCategory, $editProjectBoothNumber, $editProjectCourseNetworkId);
		if($status != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar(`'.$status.'`, 9000);
			</script>';
		}
		else
		{
			$projectNumberEditValue = '';
			$projectTitleEditValue = '';
			$projectAbstractEditValue = '';
			$projectCNEditValue = '';
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A project was edited successfully!", 5000);
			</script>';
		}
	}
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Project").'", 5000);
        </script>';
    }
}

if (isset($_POST['projectDelEnter'])) {
	if (empty(trim($_POST['projectDelID'])) == false) {
		$id = $_POST['projectDelID'];
		$status = deleteRecordFromTable($id, "PROJECT", "projectID");
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
				showSnackbar("A project was deleted successfully!", 5000);
			</script>';
		}
	} else {
		$delMsg = "<span style=\"color:red\">Please choose a project to delete using the button above.</span>";
		echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Project").'", 5000);
        </script>';
	}
}

?>

<div class="2u"></div>
<div class="8u">
	<button type="button" class="collapsible">Project</button>
	<div class="content">
		<div class="12u$">
			<div class='tabcontainer'>
				<div class="tab">
					<button id="projectAddTab" class="tablinks active" onclick="changeTab(event, 'projectAdd', false,'projectAddTab')">Add</button>
					<button id="projectEditTab" class="tablinks" onclick="changeTab(event, 'projectEdit', false, 'projectEditTab')">Edit</button>
					<button id="projectDelTab" class="tablinks" onclick="changeTab(event, 'projectDelete', false, 'projectDelTab')">Delete</button>
				</div>
				<div id="projectAdd" class="tabcontent">
					<button id="addProjectTableButton" onclick="toggleTable('projectTableModal', 'project-close')">View Current Projects</button>
					<p><?php echo $addMsg; ?></p>
					<form method="post" action="">
						<strong> <?php echo $projectAddLabel ?> </strong>
						<input type="text" name="projectNumber" id="projectNumber" value="<?php echo $projectNumberAddValue ?>" placeholder="####" />
						<strong><?php echo $projectTitleAddLabel ?></strong>
						<input type="text" name="projectTitle" id="projectTitle" value="<?php echo $projectTitleAddValue ?>" placeholder="How much Oxygen in 'Air': An Analysis" />
						<strong><?php echo $projectAbstractAddLabel ?></strong>
						<textarea name="projectAbstract" id="projectAbstract" rows="4" cols="50" maxlength="1000" placeholder="No more than 1000 characters"><?php echo $projectAbstractAddValue ?></textarea>
						<strong><?php echo $projectCategoryAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="projectCategory" id="projectCategory">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'categoryID', field: 'categoryName', table: 'CATEGORY', placeholder: 'Category'},
										success: function(data){
											$('#projectCategory').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $projectGradeLevelAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="projectGradeLevel" id="projectGradeLevel">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'projectGradeID', field: 'levelName', table: 'PROJECT_GRADE_LEVEL', placeholder: 'Grade Level'},
										success: function(data){
											$('#projectGradeLevel').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $projectBoothAddLabel ?></strong>
						<div class="select-wrapper">
							<select name="projectBoothNumber" id="projectBoothNumber">
								<!-- Use ajax to generate the drop downs -->
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'boothID', field: 'boothNumber', table: 'BOOTH_NUMBER', placeholder: 'Booth'},
										success: function(data){
											$('#projectBoothNumber').html(data);
										}
									});
								</script>
							</select>
						</div>

						<strong><?php echo $projectCNAddLabel ?></strong>
						<input type="text" name="projectCourseNetworkId" id="projectCourseNetworkId" value="<?php echo $projectCNAddValue ?>" placeholder="Course Networking ID" />

						<div class="box alt align-right">
							<ul class="actions">
								<input name="projectAddEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>

				<div id="projectEdit" class="tabcontent">
					<button id="editProjectTableButton" onclick="toggleTable('projectTableModal', 'project-close')">Select Project to Edit</button>
					<p><?php echo $editMsg; ?></p>
					<form method="post" action="">
						<strong> <?php echo $projectEditLabel ?> </strong>
						<input type="text" name="projectEditNumber" id="projectEditNumber" value="<?php echo $projectNumberEditValue ?>" placeholder="####" />
						<strong><?php echo $projectTitleEditLabel ?></strong>
						<input type="text" name="projectEditTitle" id="projectEditTitle" value="<?php echo $projectTitleEditValue ?>" placeholder="How much Oxygen in 'Air': An Analysis" />
						<strong><?php echo $projectAbstractEditLabel ?></strong>
						<textarea name="projectEditAbstract" id="projectEditAbstract" rows="4" cols="50" maxlength="1000" placeholder="No more than 1000 characters"><?php echo $projectAbstractEditValue ?></textarea>
						<strong><?php echo $projectCategoryEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="projectEditCategory" id="projectEditCategory">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'categoryID', field: 'categoryName', table: 'CATEGORY', placeholder: 'Category'},
										success: function(data){
											$('#projectEditCategory').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $projectGradeLevelEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="projectEditGradeLevel" id="projectEditGradeLevel">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'projectGradeID', field: 'levelName', table: 'PROJECT_GRADE_LEVEL', placeholder: 'Grade Level'},
										success: function(data){
											$('#projectEditGradeLevel').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong><?php echo $projectBoothEditLabel ?></strong>
						<div class="select-wrapper">
							<select name="projectEditBoothNumber" id="projectEditBoothNumber">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'boothID', field: 'boothNumber', table: 'BOOTH_NUMBER', placeholder: 'Booth'},
										success: function(data){
											$('#projectEditBoothNumber').html(data);
										}
									});
								</script>
							</select>
						</div>

						<strong><?php echo $projectCNEditLabel ?></strong>
						<input type="text" name="projectEditCourseNetworkId" id="projectEditCourseNetworkId" value="<?php echo $projectCNEditValue ?>" placeholder="Course Networking ID" />

						<input type="hidden" name="projectEditID" id="projectEditID" value="" />

						<div class="box alt align-right">
							<ul class="actions">
								<input name="projectEditEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>

				<div id="projectDelete" class="tabcontent">
					<button id="delProjectTableButton" onclick="toggleTable('projectTableModal', 'project-close')">Select Project to Delete</button>
					<p><?php echo $delMsg; ?></p>
					<form method="post" action="">
						<strong>Project Number*</strong>
						<input type="text" name="projectDelNumber" id="projectDelNumber" value="" placeholder="####" disabled />
						<strong>Title*</strong>
						<input type="text" name="projectDelTitle" id="projectDelTitle" value="" placeholder="How much Oxygen in 'Air': An Analysis" disabled />
						<strong>Abstract*</strong>
						<textarea name="projectDelAbstract" id="projectDelAbstract" rows="4" cols="50" maxlength="1000" placeholder="No more than 1000 characters" disabled></textarea>
						<strong>Category*</strong>
						<div class="select-wrapper">
							<select name="projectDelCategory" id="projectDelCategory" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'categoryID', field: 'categoryName', table: 'CATEGORY', placeholder: 'Category'},
										success: function(data){
											$('#projectDelCategory').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong>Grade Level*</strong>
						<div class="select-wrapper">
							<select name="projectDelGradeLevel" id="projectDelGradeLevel" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'projectGradeID', field: 'levelName', table: 'PROJECT_GRADE_LEVEL', placeholder: 'Grade Level'},
										success: function(data){
											$('#projectDelGradeLevel').html(data);
										}
									});
								</script>
							</select>
						</div>
						<strong>Booth*</strong>
						<div class="select-wrapper">
							<select name="projectDelBoothNumber" id="projectDelBoothNumber" disabled>
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'boothID', field: 'boothNumber', table: 'BOOTH_NUMBER', placeholder: 'Booth'},
										success: function(data){
											$('#projectDelBoothNumber').html(data);
										}
									});
								</script>
							</select>
						</div>

						<strong>Course Networking ID*</strong>
						<input type="text" name="projectDelCourseNetworkId" id="projectDelCourseNetworkId" value="" placeholder="Course Networking ID" disabled />

						<input type="hidden" name="projectDelID" id="projectDelID" value="" />

						<div class="box alt align-right">
							<ul class="actions">
								<input name="projectDelEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" language="javascript" class="init">
	var projectTable;
	$(document).ready(function() {
		projectTable = $('#projectTable').DataTable({
			"columnDefs": [{
				"targets": [0,8,9,10,11],
				"visible": false,
				"searchable": false
			}]
		});

		$('#editProjectTableButton').click(function() {
			$('#projectTable tbody').on('click', 'tr', function() {
				$(this).toggleClass('selected');
				var rowData = projectTable.rows('.selected').data()[0];
				$('#projectEditID').val(rowData[0]);
				$('#projectEditNumber').val(rowData[1]);
				$('#projectEditTitle').val(rowData[2]);
				$('#projectEditAbstract').val(rowData[3]);
				$('#projectEditCategory').val(rowData[10]);
				$('#projectEditGradeLevel').val(rowData[9]);
				$('#projectEditBoothNumber').val(rowData[11]);
				$('#projectEditCourseNetworkId').val(rowData[7]);

				$(this).toggleClass('selected');

				var projectTableModal = document.getElementById('projectTableModal');
                projectTableModal.style.display = "none";
			});
		});

		$('#delProjectTableButton').click(function() {
			$('#projectTable tbody').on('click', 'tr', function() {
				$(this).toggleClass('selected');
				var rowData = projectTable.rows('.selected').data()[0];
				$('#projectDelID').val(rowData[0]);
				$('#projectDelNumber').val(rowData[1]);
				$('#projectDelTitle').val(rowData[2]);
				$('#projectDelAbstract').val(rowData[3]);
				$('#projectDelCategory').val(rowData[10]);
				$('#projectDelGradeLevel').val(rowData[9]);
				$('#projectDelBoothNumber').val(rowData[11]);
				$('#projectDelCourseNetworkId').val(rowData[7]);

				$(this).toggleClass('selected');
				
				var projectTableModal = document.getElementById('projectTableModal');
                projectTableModal.style.display = "none";
			});
		});
	});
</script>

<div id="projectTableModal" class="modal">
	<div class="modal-content">
		<span id="project-close" class="modal-close">&times;</span>
		<?php
		$rows = selectAll("v_PROJECT");
		print '<table  id="projectTable" class="display" cellspacing="0" width="100%">';
		print '<caption>Make a selection from the table below to fill in the form</caption>';
		print '<thead>
            <tr><th>projectID</th><th>Project Number</th><th>Title</th><th>Abstract</th><th>Grade</th><th>Category</th><th>Booth Number</th><th>Course Networking ID</th><th>Rank</th><th>projectGradeID</th><th>categoryID</th><th>boothID</th></tr></thead><tfoot>';
		print '<tbody>';
		foreach ($rows as $row) {
			print '<tr>';
			print '<td>' . $row['projectID'] . '</td><td>' . $row['projectNumber'] . '</td><td>' . $row['title'] . '</td><td>' . $row['abstract'] . '</td><td>' . $row['levelName'] . '</td><td>' . $row['categoryName'] . '</td><td>' . $row['boothNumber'] . '</td><td>' . $row['cnID'] . '</td><td>' . $row['rank'] . '</td><td>' . $row['projectGradeID'] . '</td><td>' . $row['categoryID'] . '</td><td>' . $row['boothID'] . '</td>';
			print '</tr>';
		}
		print '</tbody>';
		print '</table>';
		?>
	</div>
</div>

<div class="2u$"></div>