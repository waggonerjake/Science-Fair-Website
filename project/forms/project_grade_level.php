<?php
require_once "dbconnect.php";
require_once "util.php";
require_once "insertion.php";
require_once "update.php";

$errorHasOccured = false;
$msg = "";
$editMsg = "";

$projectGradeLevelLabel = "Project Grade Level*";
$projectGradeLevel = "";

$projectGradeLevelEditLabel = "Project Grade Level*";
$projectGradeLevelEdit = "";

$activeLabel = "Active Y/N*";
$active = "";

$activeEditLabel = "Active Y/N*";
$activeEdit = "";

if (isset($_POST['projectGradeLevelEnter'])) {
    if (validateNormalField($_POST['addProjectGradeLevel'], $projectGradeLevelLabel, $msg, "Enter a project grade level", $projectGradeLevel, $projectGradeLevel) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['activeProjectGrade'], $activeLabel, $msg, "Yes or No must be selected", $active, $active) == false) {
        $errorHasOccured = true;
    }

    if ($errorHasOccured == false) {
        $status = insertProjectGradeLevel($projectGradeLevel, $active);
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
				showSnackbar("A project grade level was added successfully!", 5000);
			</script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Project Grade Level").'", 5000);
        </script>';
    }
}

if (isset($_POST['projectGradeLevelEditEnter'])) {
    if (validateNormalField($_POST['editProjectGradeLevel'], $projectGradeLevelEditLabel, $editMsg, "Project Grade Level must be specified", $projectGradeLevelEdit, $projectGradeLevelEdit) == false) {
        $errorHasOccured = true;
    }

    if (validateNormalField($_POST['activeEditProjectGrade'], $activeEditLabel, $editMsg, "Yes or No must be selected", $activeEdit, $activeEdit) == false) {
        $errorHasOccured = true;
    }

    if ($errorHasOccured == false) {
        $id = $_POST['pglEditID'];
        $status = updateProjectGradeLevel($id, $projectGradeLevelEdit, $activeEdit);
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
				showSnackbar("A project grade level was edited successfully!", 5000);
			</script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Project Grade Level").'", 5000);
        </script>';
    }
}

if(isset($_POST['projectGradeLevelDelEnter']))
{
    if(empty(trim($_POST['pglDelID'])) == false)
    {
        $id = $_POST['pglDelID'];
        $status = deleteRecordFromTable($id,"PROJECT_GRADE_LEVEL","projectGradeID");
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
				showSnackbar("A project grade level was deleted successfully!", 5000);
			</script>';
		}
    }
    else
    {
        $delMsg = "<span style=\"color:red\">Please choose a booth to delete using the button above.</span>";
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Project Grade Level").'", 5000);
        </script>';
    }
}
?>

<div class="2u"></div>
<div class="8u">
    <button type="button" class="collapsible">Project Grade Level</button>
    <div class="content">
        <div class="12u$">
            <div class='tabcontainer'>
                <!-- Tab links -->
                <div class="tab">
                    <button id="projectGradeLevelAddTab" class="tablinks active" onclick="changeTab(event, 'projectGradeLevelAdd', false,'projectGradeLevelAddTab')">Add</button>
                    <button id="projectGradeLevelEditTab" class="tablinks" onclick="changeTab(event, 'projectGradeLevelEdit', false, 'projectGradeLevelEditTab')">Edit</button>
                    <button id="projectGradeLevelDelTab" class="tablinks" onclick="changeTab(event, 'projectGradeLevelDelete', false, 'projectGradeLevelDelTab')">Delete</button>
                </div>

                <!-- Tab content -->
                <div id="projectGradeLevelAdd" class="tabcontent">
                    <button id="addPglTableButton" onclick="toggleTable('pglTableModal', 'pgl-close')">View Current Project Grade Levels</button> 
                    <p><?php echo $msg; ?></p>
                    <form method="post" action="">
                        <strong><?php echo $projectGradeLevelLabel ?></strong>
                        <input type="text" name="addProjectGradeLevel" id="addProjectGradeLevel" value="<?php echo $projectGradeLevel ?>" placeholder="Sophomore" />
                        <strong><?php echo $activeLabel ?></strong><br>
                        <input type="radio" id="yesActiveProjectGrade" name="activeProjectGrade" value="Y" checked>
                        <label for="yesActiveProjectGrade">Yes</label>
                        <input type="radio" id="noActiveProjectGrade" name="activeProjectGrade" value="N">
                        <label for="noActiveProjectGrade">No</label><br>
                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="projectGradeLevelEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

                <div id="projectGradeLevelEdit" class="tabcontent">
                    <button id="editPglTableButton" onclick="toggleTable('pglTableModal', 'pgl-close')">Select Project Grade Level to Edit</button>
                    <p><?php echo $editMsg; ?></p>
                    <form method="post" action="">
                        <strong><?php echo $projectGradeLevelEditLabel ?></strong>
                        <input type="text" name="editProjectGradeLevel" id="editProjectGradeLevel" value="<?php echo $projectGradeLevelEdit ?>" placeholder="Sophomore" />
                        <strong><?php echo $activeEditLabel ?></strong><br>
                        <input type="radio" id="yesActiveEditProjectGrade" name="activeEditProjectGrade" value="Y" checked>
                        <label for="yesActiveProjectGrade">Yes</label>
                        <input type="radio" id="noActiveEditProjectGrade" name="activeEditProjectGrade" value="N">
                        <label for="noActiveProjectGrade">No</label><br>

                        <input type="hidden" name="pglEditID" id="pglEditID" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="projectGradeLevelEditEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

                <div id="projectGradeLevelDelete" class="tabcontent">
                    <button id="delPglTableButton" onclick="toggleTable('pglTableModal', 'pgl-close')">Select Project Grade Level to Delete</button>
                    <p><?php echo $delMsg; ?></p>
                    <form method="post" action="">
                        <strong>Project Grade Level*</strong>
                        <input type="text" name="delProjectGradeLevel" id="delProjectGradeLevel" value="" placeholder="Sophomore" disabled/>
                        <strong>Active Y/N*</strong><br>
                        <input type="radio" id="yesActiveDelProjectGrade" name="activeDelProjectGrade" value="Y" disabled>
                        <label for="yesActiveProjectGrade">Yes</label>
                        <input type="radio" id="noActiveDelProjectGrade" name="activeDelProjectGrade" value="N" disabled>
                        <label for="noActiveProjectGrade">No</label><br>

                        <input type="hidden" name="pglDelID" id="pglDelID" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="projectGradeLevelDelEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" class="init">
    var pglTable;
    $(document).ready(function() {
        pglTable = $('#pglTable').DataTable({
            "columnDefs": [{
                "targets": [0],
                "visible": false,
                "searchable": false
            }]
        });

        $('#editPglTableButton').click(function() {
            $('#pglTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = pglTable.rows('.selected').data()[0];
                $('#pglEditID').val(rowData[0]);
                $('#editProjectGradeLevel').val(rowData[1]);

                if (rowData[2] == 'Y') {
                    $('#yesActiveEditProjectGrade').attr('checked', true);
                } else {
                    $('#noActiveEditProjectGrade').attr('checked', true);
                }

                $(this).toggleClass('selected');

                var pglTableModal = document.getElementById('pglTableModal');
                pglTableModal.style.display = "none";
            });
        });

        $('#delPglTableButton').click(function() {
            $('#pglTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = pglTable.rows('.selected').data()[0];
                $('#pglDelID').val(rowData[0]);
                $('#delProjectGradeLevel').val(rowData[1]);

                if (rowData[2] == 'Y') {
                    $('#yesActiveDelProjectGrade').attr('checked', true);
                } else {
                    $('#noActiveDelProjectGrade').attr('checked', true);
                }

                $(this).toggleClass('selected');
                
                var pglTableModal = document.getElementById('pglTableModal');
                pglTableModal.style.display = "none";
            });
        });
    });
</script>

<div id="pglTableModal" class="modal">
    <div class="modal-content">
        <span id="pgl-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("PROJECT_GRADE_LEVEL");
        print '<table  id="pglTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to fill in the form</caption>';
        print '<thead>
            <tr><th>projectGradeID</th><th>Level Name</th><th>Active</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
            print '<tr>';
            print '<td>' . $row['projectGradeID'] . '<td>' . $row['levelName'] . '</td><td>' . $row['active'] . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>
<div class="2u$"></div>