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

$boothAddLabel = "Booth Number*";
$boothEditLabel = "Booth Number*";
$boothNumber = "";
$editBoothNumber = "";

$activeBoothAddLabel = "Active Y/N*";
$activeBoothEditLabel = "Active Y/N*";
$activeBooth = "";
$editActiveBooth = "";

if(isset($_POST['boothAddEnter']))
{
    if(validateNormalField($_POST['booth'], $boothAddLabel, $addMsg, "Booth number must be specified", $dummy, $boothNumber) == false || is_numeric($_POST['booth']) == false)
    {
        $errorHasOccured = true;
    }

    if(validateNormalField($_POST['activeBooth'], $activeBoothAddLabel, $addMsg, "Yes or No must be selected", $dummy, $activeBooth) == false)
    {
        $errorHasOccured = true;
    }

    if($errorHasOccured == false)
    {
        $status = insertBooth($boothNumber, $activeBooth);
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
				showSnackbar("A booth was added successfully!", 5000);
			</script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Booth").'", 5000);
        </script>';
    }
}

if(isset($_POST['boothEditEnter']))
{
    if(validateNormalField($_POST['boothEditNumber'], $boothEditLabel, $editMsg, "Booth number must be specified", $dummy, $editBoothNumber) == false || is_numeric($_POST['boothEditNumber']) == false)
    {
        $errorHasOccured = true;
    }

    if(validateNormalField($_POST['activeEditBooth'], $activeboothEditLabel, $editMsg, "Yes or No must be selected", $dummy, $editActiveBooth) == false)
    {
        $errorHasOccured = true;
    }

    if($errorHasOccured == false)
    {
        $id = $_POST['boothEditID'];
        $status = updateBooth($id, $editBoothNumber, $editActiveBooth);
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
				showSnackbar("A booth was edited successfully!", 5000);
			</script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Booth").'", 5000);
        </script>';
    }
}

if(isset($_POST['boothDelEnter']))
{
    if(empty(trim($_POST['boothDelID'])) == false)
    {
        $id = $_POST['boothDelID'];
        $status = deleteRecordFromTable($id,"BOOTH_NUMBER","boothID");
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
				showSnackbar("A booth was deleted successfully!", 5000);
			</script>';
		}
    }
    else
    {
        $delMsg = "<span style=\"color:red\">Please choose a booth to delete using the button above.</span>";
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Booth").'", 5s000);
        </script>';
    }
}
?>

<div class="2u"></div>
<div class="8u">
    <button type="button" class="collapsible">Booth</button>
    <div class="content">
        <div class="12u$">
            <div class='tabcontainer'>
                <!-- Tab links -->
                <div class="tab">
                    <button id = "boothAddTab" class="tablinks active" onclick="changeTab(event, 'boothAdd', false,'boothAddTab')">Add</button>
                    <button id = "boothEditTab" class="tablinks" onclick="changeTab(event, 'boothEdit', false, 'boothEditTab')">Edit</button>
                    <button id = "boothDelTab" class="tablinks" onclick="changeTab(event, 'boothDelete', false, 'boothDelTab')">Delete</button>
                </div>

                <!-- Tab content -->
                <div id="boothAdd" class="tabcontent">
                    <button id="addBoothTableButton" onclick="toggleTable('boothTableModal', 'booth-close')">View Current Booths</button> 
                    <p><?php echo $addMsg;?></p>
                    <form method="post" action="">
                        <strong><?php echo $boothAddLabel?></strong>
                        <input type="text" name="booth" id="booth" value="" placeholder="####" />
                        <strong><?php echo $activeBoothAddLabel?></strong><br>
                        <input type="radio" id="yesActiveBooth" name="activeBooth" value="Y" checked>
                        <label for="yesActiveBooth">Yes</label>
                        <input type="radio" id="noActiveBooth" name="activeBooth" value="N">
                        <label for="noActiveBooth">No</label><br>
                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="boothAddEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

                <div id="boothEdit" class="tabcontent">
                <button id="editBoothTableButton" onclick="toggleTable('boothTableModal', 'booth-close')">Select Booth to Edit</button>
                    <p><?php echo $editMsg;?></p>
                    <form method="post" action="">
                        <strong><?php echo $boothEditLabel?></strong>
                        <input type="text" name="boothEditNumber" id="boothEditNumber" value="" placeholder="####" />
                        <strong><?php echo $activeBoothEditLabel?></strong><br>
                        <input type="radio" id="yesEditActiveBooth" name="activeEditBooth" value="Y" checked>
                        <label for="yesEditActiveBooth">Yes</label>
                        <input type="radio" id="noEditActiveBooth" name="activeEditBooth" value="N">
                        <label for="noEditActiveBooth">No</label><br>

                        <input type="hidden" name="boothEditID" id="boothEditID" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="boothEditEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

                <div id="boothDelete" class="tabcontent">
                    <button id="delBoothTableButton" onclick="toggleTable('boothTableModal', 'booth-close')">Select Booth to Delete</button>
                    <p><?php echo $delMsg;?></p>
                    <form method="post" action="">
                        <strong>Booth Number*</strong>
                        <input type="text" name="boothDelNumber" id="boothDelNumber" value="" placeholder="Select a booth to delete using the button above" disabled/>
                        <strong>Active Y/N*</strong><br>
                        <input type="radio" id="yesDelActiveBooth" name="activeDelBooth" value="Y" disabled>
                        <label for="yesDelActiveBooth">Yes</label>
                        <input type="radio" id="noDelActiveBooth" name="activeDelBooth" value="N" disabled>
                        <label for="noDelActiveBooth">No</label><br>

                        <input type="hidden" name="boothDelID" id="boothDelID" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="boothDelEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" class="init">
    var boothTable;
    $(document).ready(function() {
        boothTable = $('#boothTable').DataTable( {
                "columnDefs": [
                    {
                        "targets":[ 0 ],
                        "visible": false,
                        "searchable": false
                    }
                ]
            });

        $('#editBoothTableButton').click(function() {
            $('#boothTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = boothTable.rows('.selected').data()[0];
                $('#boothEditID').val(rowData[0]);
                $('#boothEditNumber').val(rowData[1]);
                
                if (rowData[2] == 'Y') {
                    $('#yesEditActiveBooth').attr('checked', true);
                }
                else {
                    $('#noEditActiveBooth').attr('checked', true);
                }
                
                $(this).toggleClass('selected');

                var boothTabelModal = document.getElementById('boothTableModal');
                boothTabelModal.style.display = "none";
            });
        });

        $('#delBoothTableButton').click(function() {
            $('#boothTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = boothTable.rows('.selected').data()[0];
                $('#boothDelID').val(rowData[0]);
                $('#boothDelNumber').val(rowData[1]);
                
                if (rowData[2] == 'Y') {
                    $('#yesDelActiveBooth').attr('checked', true);
                }
                else {
                    $('#noDelActiveBooth').attr('checked', true);
                }
                
                $(this).toggleClass('selected');

                var boothTabelModal = document.getElementById('boothTableModal');
                boothTabelModal.style.display = "none";
            });
        });
    });
</script>
<!-- TODO: Add a snackbar/toast for general success & failure message -->
<div id="boothTableModal" class="modal">
    <div class="modal-content">
        <span id="booth-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("BOOTH_NUMBER");
        print '<table  id="boothTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to fill in the form</caption>';
        print '<thead>
            <tr><th>boothID</th><th>Booth Number</th><th>Active</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
            print '<tr>';
            print '<td>' . $row['boothID'] . '<td>' . $row['boothNumber'] . '</td><td>' . $row['active'] . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>
<div class="2u$"></div>