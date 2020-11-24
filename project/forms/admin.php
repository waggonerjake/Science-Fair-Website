<?php
require_once "dbconnect.php";
require_once "util.php";
require_once "insertion.php";
require_once "selection.php";
require_once "update.php";


//may want to give each tab their own variables as well
$errorHasOccured = false;
$msg = '';
$delMsg = "";

$fnLabel = "First Name*";
$fn = "";
$firstNameValue = "";

$lnLabel = "Last Name*";
$ln = "";
$lastNameValue = "";

$mnLabel = "Middle Name";
$mn = "";
$middleNameValue = "";

$emLabel = "Email*";
$em = "";
$emailValue = "";

$confirmEmLabel = "Confirm Email*";
$confirmEm = "";
$confirmEmailValue = "";

$passLabel = "Password*";
$pass = "";
$passValue = '';

$confirmPassLabel = "Confirm Password*";
$confirmPass = "";
$confirmPassValue = '';

$levelLabel = "Level*";
$level = "";

$activeLabel = "Active Y/N*";
$active = "";

$editMsg = "";

$fnEditLabel = "First Name*";
$fnEdit = "";
$firstNameEditValue = "";

$lnEditLabel = "Last Name*";
$lnEdit = "";
$lastNameEditValue = "";

$mnEditLabel = "Middle Name";
$mnEdit = "";
$middleNameEditValue = "";

$emEditLabel = "Email*";
$emEdit = "";
$emailEditValue = "";

$confirmEmEditLabel = "Confirm Email*";
$confirmEmEdit = "";
$confirmEmailEditValue = "";

$levelEditLabel = "Level*";
$levelEdit = "";

$activeEditLabel = "Active Y/N*";
$activeEdit = "";

$id = "";

//----------------------ADD VALIDATION-------------------------
if (isset($_POST['adminAddEnter'])) {
    if (validateNormalField($_POST['adminFirstName'], $fnLabel, $msg, "Please enter first name", $firstNameValue, $fn) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['adminLastName'], $lnLabel, $msg, "Please enter last name", $lastNameValue, $ln) == false) {
        $errorHasOccured = true;
    }

    if (empty(trim($_POST['adminMiddleName'])) == false) {
        $middleNameValue = trim($_POST['adminMiddleName']);
        $mn = $middleNameValue;
    }

    if (!filter_input(INPUT_POST, 'adminEmail',FILTER_VALIDATE_EMAIL)) 
    {
        $emLabel = '<span style="color:red">Email*</span>';
        $errorHasOccured = true;
        if(empty($msg))
        {
            $msg = 'Things to fix: Email must be populated';
        }
        else $msg = $msg.", Email must be populated";
    }
    else 
    {
        $em = trim($_POST['adminEmail']);
    }

    if (!filter_input(INPUT_POST, 'adminConfirmEmail',FILTER_VALIDATE_EMAIL))
    {
        $confirmEmLabel = '<span style="color:red">Confirm Email*</span>';
        $errorHasOccured = true;
        if(empty($msg))
        {
            $msg = 'Things to fix: Confirm Email must be populated';
        }
        else $msg = $msg.", Confirm Email must be populated";
    }
    else 
    {
        $confirmEm = trim($_POST['adminConfirmEmail']);
    }

    if(strpos($confirmEmLabel, 'red') == false && strpos($emLabel, 'red') == false)
    {
        if(strtoupper($confirmEm) != strtoupper($em))
        {
            $errorHasOccured = true;
            if(empty($msg))
            {
                $msg = 'Things to fix: Emails do not match';
            }
            else $msg = $msg.", Emails do not match";
            $emLabel = '<span style="color:red">Email*</span>';
            $confirmEmLabel = '<span style="color:red">Confirm Email*</span>';
        }
        else
        {
            $emailValue = $em;
            $confirmEmailValue = $confirmEm;
        }
    }

    if (validateNormalField($_POST['adminPassword'], $passLabel, $msg, "Please enter password", $dummy, $pass) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['adminConfirmPassword'], $confirmPassLabel, $msg, "Please enter confirm password", $dummy, $confirmPass) == false) {
        $errorHasOccured = true;
    }

    if(strpos($confirmPassLabel, 'red') == false && strpos($passLabel, 'red') == false)
    {
        if($confirmPass != $pass)
        {
            $errorHasOccured = true;
            if(empty($msg))
            {
                $msg = 'Things to fix: Passwords do not match';
            }
            else $msg = $msg.", Passwords do not match";
            $passLabel = '<span style="color:red">Password*</span>';
            $confirmPassLabel = '<span style="color:red">Confirm Password*</span>';
        }
        else
        {
            $passValue = $pass;
            $confirmPassValue = $confirmPass;
        }
    }

    if (validateNormalField($_POST['adminLevel'], $levelLabel, $msg, "Please select a level", $dummy, $level) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['activeAdmin'], $activeLabel, $msg, "Yes or No must be selected", $dummy, $active) == false) {
        $errorHasOccured = true;
    }

    if ($errorHasOccured == false) {
        $status = insertAdmin($fn, $ln, $mn, $em, $pass, $level, $active);
        if($status != 'OK')
		{
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar(`'.$status.'`, 9000);
            </script>';
		}
		else
		{
            $firstNameValue = '';
            $lastNameValue = '';
            $middleNameValue = '';
            $emailValue = '';
            $confirmEmailValue = '';
            $passValue = '';
            $confirmPassValue = '';
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar("An admin was added successfully!", 5000);
            </script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Admin").'", 5000);
        </script>';
    }
}

//----------------------EDIT VALIDATION-------------------------
if (isset($_POST['adminEditEnter'])) {
    if (validateNormalField($_POST['adminEditFirstName'], $fnEditLabel, $editMsg, "Please enter first name", $firstNameEditValue, $fnEdit) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['adminEditLastName'], $lnEditLabel, $editMsg, "Please enter last name", $lastNameEditValue, $lnEdit) == false) {
        $errorHasOccured = true;
    }

    if (empty(trim($_POST['adminEditMiddleName'])) == false) {
        $middleNameEditValue = trim($_POST['adminEditMiddleName']);
        $mnEdit = $middleNameEditValue;
    }

    if (!filter_input(INPUT_POST, 'adminEditEmail',FILTER_VALIDATE_EMAIL)) 
    {
        $emEditLabel = '<span style="color:red">Email*</span>';
        $errorHasOccured = true;
        if(empty($editMsg))
        {
            $editMsg = 'Things to fix: Email must be populated';
        }
        else $editMsg = $editMsg.", Email must be populated";
    }
    else 
    {
        $emEdit = trim($_POST['adminEditEmail']);
    }

    if (!filter_input(INPUT_POST, 'adminEditConfirmEmail',FILTER_VALIDATE_EMAIL))
    {
        $confirmEmEditLabel = '<span style="color:red">Confirm Email*</span>';
        $errorHasOccured = true;
        if(empty($editMsg))
        {
            $editMsg = 'Things to fix: Confirm Email must be populated';
        }
        else $editMsg = $editMsg.", Confirm Email must be populated";
    }
    else 
    {
        $confirmEmEdit = trim($_POST['adminEditConfirmEmail']);
    }

    if(strpos($confirmEmEditLabel, 'red') == false && strpos($emEditLabel, 'red') == false)
    {
        if(strtoupper($confirmEmEdit) != strtoupper($emEdit))
        {
            $errorHasOccured = true;
            if(empty($editMsg))
            {
                $editMsg = 'Things to fix: Emails do not match';
            }
            else $editMsg = $editMsg.", Emails do not match";
            $emEditLabel = '<span style="color:red">Email*</span>';
            $confirmEmEditLabel = '<span style="color:red">Confirm Email*</span>';
        }
        else
        {
            $emailEditValue = $emEdit;
            $confirmEmailEditValue = $confirmEmEdit;
        }
    }

    if (validateNormalField($_POST['adminEditLevel'], $levelEditLabel, $editMsg, "Please select a level", $dummy, $levelEdit) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['activeEditAdmin'], $activeEditLabel, $editMsg, "Yes or No must be selected", $dummy, $activeEdit) == false) {
        $errorHasOccured = true;
    }
    if ($errorHasOccured == false) {
        $id = $_POST['adminEditID'];
        $status = updateAdmin($id, $fnEdit, $lnEdit, $mnEdit, $emEdit, $levelEdit, $activeEdit);
        if($status != 'OK')
		{
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar(`'.$status.'`, 9000);
            </script>';
		}
		else
		{
            $firstNameEditValue = '';
            $lastNameEditValue = '';
            $middleNameEditValue = '';
            $emailEditValue = '';
            $confirmEmailEditValue = '';
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar("An admin was edited successfully!", 5000);
            </script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Admin").'", 5000);
        </script>';
    }
}

//----------------------DELETE VALIDATION-------------------------
if(isset($_POST['adminDelEnter'])) {
    if (empty(trim($_POST['adminDelID'])) == false) {
        $id = $_POST['adminDelID'];
        $status = deleteRecordFromTable($id, "ADMINISTRATOR", "adminID");
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
                showSnackbar("An admin was deleted successfully!", 5000);
            </script>';
		}
    }
    else
    {
        $delMsg = "<span style=\"color:red\">Please choose an admin to delete using the button above.</span>";
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Admin").'", 5000);
        </script>';
    }
}
?>

<div class="2u"></div>
<div class="8u">
    <button type="button" class="collapsible">Admin</button>
    <div class="content">
        <div class="12u$">
            <div class='tabcontainer'>

                <div class="tab">
                    <button id="adminAddTab" class="tablinks active" onclick="changeTab(event, 'adminAdd', false,'adminAddTab')">Add</button>
                    <button id="adminEditTab" class="tablinks" onclick="changeTab(event, 'adminEdit', false, 'adminEditTab')">Edit</button>
                    <button id="adminDelTab" class="tablinks" onclick="changeTab(event, 'adminDelete', false, 'adminDelTab')">Delete</button>
                </div>
                <div id="adminAdd" class="tabcontent">
                    <button id="addAdminTableButton" onclick="toggleTable('adminTableModal', 'admin-close')">View Current Admins</button>
                    <p><?php echo $msg; ?></p>
                    <form method="post" action="">
                        <strong><?php echo $fnLabel ?></strong>
                        <input type="text" name="adminFirstName" id="adminFirstName" value="<?php echo $firstNameValue ?>" placeholder="John" />
                        <strong><?php echo $lnLabel ?></strong>
                        <input type="text" name="adminLastName" id="adminLastName" value="<?php echo $lastNameValue ?>" placeholder="Doe" />
                        <strong><?php echo $mnLabel ?></strong>
                        <input type="text" name="adminMiddleName" id="adminMiddleName" value="<?php echo $middleNameValue ?>" placeholder="Jimmy" />
                        <strong><?php echo $emLabel ?></strong>
                        <input type="email" name="adminEmail" id="adminEmail" value="<?php echo $emailValue ?>" placeholder="JohnDoe@gmail.com" />
                        <strong><?php echo $confirmEmLabel ?></strong>
                        <input type="email" name="adminConfirmEmail" id="adminConfirmEmail" value="<?php echo $confirmEmailValue ?>" placeholder="JohnDoe@gmail.com" />
                        <strong><?php echo $passLabel ?></strong>
                        <input type="password" name="adminPassword" id="adminPassword" value="<?php echo $passValue?>" placeholder="Password" />
                        <strong><?php echo $confirmPassLabel ?></strong>
                        <input type="password" name="adminConfirmPassword" id="adminConfirmPassword" value="<?php echo $confirmPassValue ?>" placeholder="Password" />
                        <strong><?php echo $levelLabel ?></strong>
                        <div class="12u$">
                            <div class="select-wrapper">
                                <select name="adminLevel" id="adminLevel">
                                    <script>
                                        $.ajax({
                                            type: "GET",
                                            url: 'util.php',
                                            data: {id: 'levelID', field: 'level', table: 'ADMIN_LEVEL', placeholder: 'Admin Level'},
                                            success: function(data){
                                                $('#adminLevel').html(data);
                                            }
                                        });
								    </script>
                                </select>
                            </div>
                        </div>
                        <strong><?php echo $activeLabel ?></strong><br>
                        <input type="radio" id="yesActiveAdmin" name="activeAdmin" value="Y" checked>
                        <label for="yesActiveAdmin">Yes</label>
                        <input type="radio" id="noActiveAdmin" name="activeAdmin" value="N">
                        <label for="noActiveAdmin">No</label><br>

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="adminAddEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

                <div id="adminEdit" class="tabcontent">
                    <button id="editAdminTableButton" onclick="toggleTable('adminTableModal', 'admin-close')">Select Admin to Edit</button>
                    <p><?php echo $editMsg; ?></p>
                    <form method="post" action="">
                        <strong><?php echo $fnEditLabel ?></strong>
                        <input type="text" name="adminEditFirstName" id="adminEditFirstName" value="<?php echo $firstNameEditValue ?>" placeholder="John" />
                        <strong><?php echo $lnEditLabel ?></strong>
                        <input type="text" name="adminEditLastName" id="adminEditLastName" value="<?php echo $lastNameEditValue ?>" placeholder="Doe" />
                        <strong><?php echo $mnEditLabel ?></strong>
                        <input type="text" name="adminEditMiddleName" id="adminEditMiddleName" value="<?php echo $middleNameEditValue ?>" placeholder="Jimmy" />
                        <strong><?php echo $emEditLabel ?></strong>
                        <input type="email" name="adminEditEmail" id="adminEditEmail" value="<?php echo $emailEditValue ?>" placeholder="JohnDoe@gmail.com" />
                        <strong><?php echo $confirmEmEditLabel ?></strong>
                        <input type="email" name="adminEditConfirmEmail" id="adminEditConfirmEmail" value="<?php echo $confirmEmailEditValue ?>" placeholder="JohnDoe@gmail.com" />
                        <strong><?php echo $levelEditLabel ?></strong>
                        <div class="12u$">
                            <div class="select-wrapper">
                                <select name="adminEditLevel" id="adminEditLevel">
                                    <script>
                                        $.ajax({
                                            type: "GET",
                                            url: 'util.php',
                                            data: {id: 'levelID', field: 'level', table: 'ADMIN_LEVEL', placeholder: 'Admin Level'},
                                            success: function(data){
                                                $('#adminEditLevel').html(data);
                                            }
                                        });
								    </script>
                                </select>
                            </div>
                        </div>
                        <strong><?php echo $activeLabel ?></strong><br>
                        <input type="radio" id="yesEditActiveAdmin" name="activeEditAdmin" value="Y" checked>
                        <label for="yesEditActiveAdmin">Yes</label>
                        <input type="radio" id="noEditActiveAdmin" name="activeEditAdmin" value="N">
                        <label for="noEditActiveAdmin">No</label><br>

                        <input type="hidden" name="adminEditID" id="adminEditID" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="adminEditEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

                <div id="adminDelete" class="tabcontent">
                    <button id="delAdminTableButton" onclick="toggleTable('adminTableModal', 'admin-close')">Select Admin to Delete</button>
                    <p><?php echo $delMsg; ?></p>
                    <form method="post" action="">
                        <strong>First Name*</strong>
                        <input type="text" name="adminDelFirstName" id="adminDelFirstName" value="" placeholder="John" disabled/>
                        <strong>Last Name*</strong>
                        <input type="text" name="adminDelLastName" id="adminDelLastName" value="" placeholder="Doe" disabled/>
                        <strong>Middle Name</strong>
                        <input type="text" name="adminDelMiddleName" id="adminDelMiddleName" value="" placeholder="Jimmy" disabled/>
                        <strong>Email*</strong>
                        <input type="email" name="adminDelEmail" id="adminDelEmail" value="" placeholder="JohnDoe@gmail.com" disabled/>
                        <strong>Level*</strong>
                        <div class="12u$">
                            <div class="select-wrapper">
                                <select name="adminDelLevel" id="adminDelLevel" disabled>
                                    <script>
                                        $.ajax({
                                            type: "GET",
                                            url: 'util.php',
                                            data: {id: 'levelID', field: 'level', table: 'ADMIN_LEVEL', placeholder: 'Admin Level'},
                                            success: function(data){
                                                $('#adminDelLevel').html(data);
                                            }
                                        });
								    </script>
                                </select>
                            </div>
                        </div>
                        <strong>Active Y/N*</strong><br>
                        <input type="radio" id="yesDelActiveAdmin" name="activeDelAdmin" value="Y" disabled>
                        <label for="yesDelActiveAdmin">Yes</label>
                        <input type="radio" id="noDelActiveAdmin" name="activeDelAdmin" value="N" disabled>
                        <label for="noDelActiveAdmin">No</label><br>

                        <input type="hidden" name="adminDelID" id="adminDelID" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="adminDelEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" class="init">    
    var adminTable;
    $(document).ready(function() {
        adminTable = $('#adminTable').DataTable({
            "columnDefs": [{
                "targets": [0,7], // Hide both adminID and levelID from view
                "visible": false,
                "searchable": false
            }]
        });

        $('#editAdminTableButton').click(function() {
            $('#adminTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = adminTable.rows('.selected').data()[0];
                $('#adminEditID').val(rowData[0]);
                $('#adminEditFirstName').val(rowData[1]);
                $('#adminEditLastName').val(rowData[2]);
                $('#adminEditMiddleName').val(rowData[3]);
                $('#adminEditEmail').val(rowData[4]);
                $('#adminEditConfirmEmail').val(rowData[4]);
                $('#adminEditLevel').val(rowData[7]);

                if (rowData[6] == 'Y') {
                    $('#yesEditActiveAdmin').attr('checked', true);
                } else {
                    $('#noEditActiveAdmin').attr('checked', true);
                }

                $(this).toggleClass('selected');

                var adminTableModal = document.getElementById('adminTableModal');
                adminTableModal.style.display = "none";
            });
        });

        $('#delAdminTableButton').click(function() {
            $('#adminTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = adminTable.rows('.selected').data()[0];
                $('#adminDelID').val(rowData[0]);
                $('#adminDelFirstName').val(rowData[1]);
                $('#adminDelLastName').val(rowData[2]);
                $('#adminDelMiddleName').val(rowData[3]);
                $('#adminDelEmail').val(rowData[4]);
                $('#adminDelLevel').val(rowData[7]);

                if (rowData[6] == 'Y') {
                    $('#yesDelActiveAdmin').attr('checked', true);
                } else {
                    $('#noDelActiveAdmin').attr('checked', true);
                }

                $(this).toggleClass('selected');

                var adminTableModal = document.getElementById('adminTableModal');
                adminTableModal.style.display = "none";
            });
        });
    });
</script>
<div id="adminTableModal" class="modal">
    <div class="modal-content">
        <span id="admin-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("v_ADMINISTRATOR");
        print '<table  id="adminTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to fill in the form</caption>';
        print '<thead>
            <tr><th>adminID</th><th>First Name</th><th>Last Name</th><th>Middle Name</th><th>Email</th><th>Level Name</th><th>Active</th><th>levelID</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
            print '<tr>';
            print '<td>' . $row['adminID'] . '</td><td>' . $row['firstName'] . '</td><td>' . $row['lastName'] . '</td><td>' . $row['middleName'] . '</td><td>' . $row['email'] . '</td><td>' . $row['level'] . '</td><td>' . $row['active'] . '</td><td>' . $row['levelID'] . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>
<div class="2u$"></div>