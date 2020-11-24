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

$sessionAddNameLabel = "Session Name*";
$sessionAddLabel = "Start Time*";
$sessionAddLabel2 = "End Time*";
$sessionEditNameLabel = "Session Name*";
$sessionEditLabel = "Start Time*";
$sessionEditLabel2 = "End Time*";

$sessionName = '';
$sessionStart = "";
$sessionEnd = "";
$editSessionStart = "";
$editSessionEnd = "";
$editSessionName = '';

$sessionNameValue = '';
$sessionStartAddValue = "";
$sessionEndAddValue = "";
$sessionNameEditValue = '';
$sessionStartEditValue = "";
$sessionEndEditValue = "";

$activeSessionAddLabel = "Active Y/N*";
$activeSessionEditLabel = "Active Y/N*";
$activeSession = "";
$editActiveSession = "";

if (isset($_POST['sessionAddEnter'])) {

	if (validateNormalField($_POST['sessionName'], $sessionAddNameLabel, $addMsg, "Session Name must be specified", $sessionNameValue, $sessionName) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['sessionStart'], $sessionAddLabel, $addMsg, "Session Start Time must be specified", $sessionStartAddValue, $sessionStart) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['sessionEnd'], $sessionAddLabel2, $addMsg, "Session End Time must be specified", $sessionEndAddValue, $sessionEnd) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['activeSession'], $activeSessionAddLabel, $addMsg, "Yes or No must be selected", $dummy, $activeSession) == false) {
		$errorHasOccured = true;
	}
	if ($errorHasOccured == false) {
		$status = insertSession($sessionStart, $sessionEnd, $activeSession, $sessionName);
		if($status != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar(`'.$status.'`, 9000);
			</script>';
		}
		else
		{
			$sessionNameValue = '';
			$sessionStartAddValue = '';
			$sessionEndAddValue = '';
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A session was added successfully!", 5000);
			</script>';
		}
	}
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Session").'", 5000);
        </script>';
    }
}

if (isset($_POST['sessionEditEnter'])) {

	if (validateNormalField($_POST['sessionEditName'], $sessionEditNameLabel, $editMsg, "Session Name must be specified", $sessionNameEditValue, $editSessionName) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['sessionEditStart'], $sessionEditLabel, $editMsg, "Session Start Time must be specified", $sessionStartEditValue, $editSessionStart) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['sessionEditEnd'], $sessionEditLabel2, $editMsg, "Session End Time must be specified", $sessionEndEditValue, $editSessionEnd) == false) {
		$errorHasOccured = true;
	}

	if (validateNormalField($_POST['activeEditSession'], $activeSessionEditLabel, $editMsg, "Yes or No must be selected", $dummy, $editActiveSession) == false) {
		$errorHasOccured = true;
	}
	if ($errorHasOccured == false) {
		$id = $_POST['sessionEditID'];
		$status = updateSession($id, $editSessionStart, $editSessionEnd, $editActiveSession, $editSessionName);
		if($status != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar(`'.$status.'`, 9000);
			</script>';
		}
		else
		{
			$sessionNameEditValue = '';
			$sessionStartEditValue = '';
			$sessionEndEditValue = '';
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A session was edited successfully!", 5000);
			</script>';
		}
	}
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Session").'", 5000);
        </script>';
    }
}

if (isset($_POST['sessionDelEnter'])) {
	if (empty(trim($_POST['sessionDelID'])) == false) {
		$id = $_POST['sessionDelID'];
		$status = deleteRecordFromTable($id, "SESSION", "sessionID");
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
				showSnackbar("A session was deleted successfully!", 5000);
			</script>';
		}
	} else {
		$delMsg = "<span style=\"color:red\">Please choose a session to delete using the button above.</span>";
		echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Session").'", 5000);
        </script>';
	}
}

?>

<div class="2u"></div>
<div class="8u">
	<button type="button" class="collapsible">Session</button>
	<div class="content">
		<div class="12u$">
			<div class='tabcontainer'>
				<div class="tab">
					<button id="sessionAddTab" class="tablinks active" onclick="changeTab(event, 'sessionAdd', false,'sessionAddTab')">Add</button>
					<button id="sessionEditTab" class="tablinks" onclick="changeTab(event, 'sessionEdit', false, 'sessionEditTab')">Edit</button>
					<button id="sessionDelTab" class="tablinks" onclick="changeTab(event, 'sessionDelete', false, 'sessionDelTab')">Delete</button>
				</div>

				<div id="sessionAdd" class="tabcontent">
					<button id="addSessionTableButton" onclick="toggleTable('sessionTableModal', 'session-close')">View Current Sessions</button>
					<p><?php echo $addMsg; ?></p>
					<form method="post" action="">
						<strong><?php echo $sessionAddNameLabel ?></strong>
                        <input type="text" name="sessionName" id="sessionName" value="<?php echo $sessionNameValue ?>" placeholder="Session 1" />
						<strong><?php echo $sessionAddLabel ?></strong>
						<input type="time" id="sessionStart" name="sessionStart" value="<?php echo $sessionStartAddValue?>" placeholder="8:00 AM">
						<div class="2u$"></div><br>
						<strong><?php echo $sessionAddLabel2 ?></strong>
						<input type="time" id="sessionEnd" name="sessionEnd" value="<?php echo $sessionEndAddValue?>">
						<div class="2u$"></div>
						<strong><?php echo $activeSessionAddLabel ?></strong><br>
						<input type="radio" id="yesActiveSession" name="activeSession" value="Y" checked>
						<label for="yesActiveSession">Yes</label>
						<input type="radio" id="noActiveSession" name="activeSession" value="N">
						<label for="noActiveSession">No</label><br>
						<div class="box alt align-right">
							<ul class="actions">
								<input name="sessionAddEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>

				<div id="sessionEdit" class="tabcontent">
					<button id="editSessionTableButton" onclick="toggleTable('sessionTableModal', 'session-close')">Select Session to Edit</button>
					<p><?php echo $editMsg; ?></p>
					<form method="post" action="">
						<strong><?php echo $sessionEditNameLabel ?></strong>
                        <input type="text" name="sessionEditName" id="sessionEditName" value="<?php echo $sessionNameEditValue ?>" placeholder="Session 1" />
						<strong><?php echo $sessionEditLabel ?></strong>
						<input type="time" id="sessionEditStart" name="sessionEditStart" value="<?php echo $sessionStartEditValue?>" placeholder="8:00 AM">
						<div class="2u$"></div><br>
						<strong><?php echo $sessionEditLabel2 ?></strong>
						<input type="time" id="sessionEditEnd" name="sessionEditEnd" value="<?php echo $sessionEndEditValue?>">
						<div class="2u$"></div>
						<strong><?php echo $activeSessionEditLabel ?></strong><br>
						<input type="radio" id="yesEditActiveSession" name="activeEditSession" value="Y" checked>
						<label for="yesEditActiveSession">Yes</label>
						<input type="radio" id="noEditActiveSession" name="activeEditSession" value="N">
						<label for="noEditActiveSession">No</label><br>

						<input type="hidden" name="sessionEditID" id="sessionEditID" value="" />

						<div class="box alt align-right">
							<ul class="actions">
								<input name="sessionEditEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>

				<div id="sessionDelete" class="tabcontent">
					<button id="delSessionTableButton" onclick="toggleTable('sessionTableModal', 'session-close')">Select Session to Delete</button>
					<p><?php echo $delMsg; ?></p>
					<form method="post" action="">
						<strong>Session Name*</strong>
						<input type="text" name="sessionDelName" id="sessionDelName" value="" placeholder="Please select a session to delete using the button above" />
						<div class="2u$"></div><br>
						<strong>Start Time*</strong>
						<input type="time" id="sessionDelStart" name="sessionDelStart" value="" placeholder="8:00 AM" disabled>
						<div class="2u$"></div><br>
						<strong>End Time*</strong>
						<input type="time" id="sessionDelEnd" name="sessionDelEnd" disabled>
						<div class="2u$"></div>
						<strong>Active Y/N*</strong><br>
						<input type="radio" id="yesDelActiveSession" name="activeDelSession" value="Y" disabled>
						<label for="yesDelActiveSession">Yes</label>
						<input type="radio" id="noDelActiveSession" name="activeDelSession" value="N" disabled>
						<label for="noDelActiveSession">No</label><br>

						<input type="hidden" name="sessionDelID" id="sessionDelID" value="" />

						<div class="box alt align-right">
							<ul class="actions">
								<input name="sessionDelEnter" class="btn special" type="submit" value="Submit" />
							</ul>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" language="javascript" class="init">
	var sessionTable;
	$(document).ready(function() {
		sessionTable = $('#sessionTable').DataTable({
			"columnDefs": [{
				"targets": [0],
				"visible": false,
				"searchable": false
			}]
		});

		$('#editSessionTableButton').click(function() {
			$('#sessionTable tbody').on('click', 'tr', function() {
				$(this).toggleClass('selected');
				var rowData = sessionTable.rows('.selected').data()[0];
				$('#sessionEditID').val(rowData[0]);
				$('#sessionEditName').val(rowData[1]);
				$('#sessionEditStart').val(rowData[2]);
				$('#sessionEditEnd').val(rowData[3]);


				if (rowData[4] == 'Y') {
					$('#yesEditActiveSession').attr('checked', true);
				} else {
					$('#noEditActiveSession').attr('checked', true);
				}

				$(this).toggleClass('selected');

				var sessionTableModal = document.getElementById('sessionTableModal');
                sessionTableModal.style.display = "none";
			});
		});

		$('#delSessionTableButton').click(function() {
			$('#sessionTable tbody').on('click', 'tr', function() {
				$(this).toggleClass('selected');
				var rowData = sessionTable.rows('.selected').data()[0];
				$('#sessionDelID').val(rowData[0]);
				$('#sessionDelName').val(rowData[1]);
				$('#sessionDelStart').val(rowData[2]);
				$('#sessionDelEnd').val(rowData[3]);

				if (rowData[4] == 'Y') {
					$('#yesDelActiveSession').attr('checked', true);
				} else {
					$('#noDelActiveSession').attr('checked', true);
				}

				$(this).toggleClass('selected');

				var sessionTableModal = document.getElementById('sessionTableModal');
                sessionTableModal.style.display = "none";
			});
		});
	});
</script>

<div id="sessionTableModal" class="modal">
	<div class="modal-content">
		<span id="session-close" class="modal-close">&times;</span>
		<?php
		$rows = selectAll("SESSION");
		print '<table  id="sessionTable" class="display" cellspacing="0" width="100%">';
		print '<caption>Make a selection from the table below to fill in the form</caption>';
		print '<thead>
            <tr><th>sessionID</th><th>Session Name</th><th>Session Start</th><th>Session End</th><th>Active</th></tr></thead><tfoot>';
		print '<tbody>';
		foreach ($rows as $row) {
			print '<tr>';
			print '<td>' . $row['sessionID'] . '</td><td>' . $row['name'] . '</td><td>' . $row['startTime'] . '</td><td>' . $row['endTime'] . '</td><td>' . $row['active'] . '</td>';
			print '</tr>';
		}
		print '</tbody>';
		print '</table>';
		?>
	</div>
</div>

<div class="2u$"></div>