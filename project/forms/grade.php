<?php
	require_once "dbconnect.php";
    require_once "util.php";
    require_once "insertion.php";
    require_once "selection.php";
    require_once "update.php";
    require_once "deletion.php";

    $errorHasOccured = false;
    $msg = "";
    $editMsg = '';
    $delMsg = '';

    $gradeLabel = "Grade Level*";
    $editGradeLabel = "Grade Level*";

    $grade = "";
    $editGrade = "";

    $activeGradeLabel = "Active Y/N*";
    $editActiveGradeLabel = "Active Y/N*";
    $activeGrade = "";
    $editActiveGrade = "";

    if(isset($_POST['gradeAddEnter']))
    {
        if(validateNormalField($_POST['grade'], $gradeLabel, $msg, "Grade must be specified", $dummy, $grade) == false || is_numeric($_POST['grade']) == false)
        {
            $errorHasOccured = true;
        }
        
        if(validateNormalField($_POST['activeGrade'], $activeGradeLabel, $msg, "Yes or No must be selected", $dummy, $activeGrade) == false)
        {
            $errorHasOccured = true;
        }
        
        if($errorHasOccured == false)
        {
            $status = insertGrade($grade, $activeGrade);
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
                    showSnackbar("A grade was added successfully!", 5000);
                </script>';
            }
        }
        else
        {
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar("'.getToastErrorMessage("Grade").'", 5000);
            </script>';
        }
    }

    if(isset($_POST['gradeEditEnter']))
    {
        if(validateNormalField($_POST['gradeEditNumber'], $editGradeLabel, $editMsg, "Grade must be specified", $dummy, $editGrade) == false || is_numeric($_POST['gradeEditNumber']) == false)
        {
            $errorHasOccured = true;
        }
        
        if(validateNormalField($_POST['activeEditGrade'], $editActiveGradeLabel, $editMsg, "Yes or No must be selected", $dummy, $editActiveGrade) == false)
        {
            $errorHasOccured = true;
        }
        
        if($errorHasOccured == false)
        {
            $id = $_POST['gradeEditID'];
            $status = updateGrade($id, $editGrade, $editActiveGrade);
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
                    showSnackbar("A grade was edited successfully!", 5000);
                </script>';
            }
        }        
        else
        {
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar("'.getToastErrorMessage("Grade").'", 5000);
            </script>';
        }
    }

    if(isset($_POST['gradeDelEnter']))
    {
        if(empty(trim($_POST['gradeDelID'])) == false)
        {
            $id = $_POST['gradeDelID'];
            $status = deleteRecordFromTable($id,"GRADE","gradeID");
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
                    showSnackbar("A grade was deleted successfully!", 5000);
                </script>';
            }
        }
        else
        {
            $delMsg = "<span style=\"color:red\">Please choose a grade to delete using the button above.</span>";
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar("'.getToastErrorMessage("Grade").'", 5000);
            </script>';
        }
    }
?>

<div class = "2u"></div>
<div class = "8u">
    <button type="button" class="collapsible">Grade</button>
    <div class="content">
        <div class = "12u$">
            <div class='tabcontainer'>
                <div class="tab">
                    <button id="gradeAddTab" class="tablinks active" onclick="changeTab(event, 'gradeAdd', false, 'gradeAddTab')">Add</button>
                    <button id="gradeEditTab" class="tablinks" onclick="changeTab(event, 'gradeEdit', false, 'gradeEditTab')">Edit</button>
                    <button id="gradeDelTab" class="tablinks" onclick="changeTab(event, 'gradeDelete', false, 'gradeDelTab')">Delete</button>
                </div>

                <div id="gradeAdd" class="tabcontent">
                    <button id="addGradeTableButton" onclick="toggleTable('gradeTableModal', 'grade-close')">View Current Grades</button>               
                    <p><?php echo $msg;?></p>
                    <form method="post" action="">
                        <strong><?php echo $gradeLabel?></strong>
                        <input type="text" name="grade" id="grade" value="" placeholder="10" />
                        <strong><?php echo $activeGradeLabel ?></strong><br>
                        <input type="radio" id="yesActiveGrade" name="activeGrade" value="Y" checked>
                        <label for="yesActiveGrade">Yes</label>
                        <input type="radio" id="noActiveGrade" name="activeGrade" value="N">
                        <label for="noActiveGrade">No</label><br>
                        <div class="box alt align-right"> 
                            <ul class="actions">
                                <input name="gradeAddEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

                <div id="gradeEdit" class="tabcontent">
                    <button id="editGradeTableButton" onclick="toggleTable('gradeTableModal', 'grade-close')">Select Grade to Edit</button>
                    <p><?php echo $editMsg;?></p>
                    <form method="post" action="">
                        <strong><?php echo $editGradeLabel?></strong>
                        <input type="text" name="gradeEditNumber" id="gradeEditNumber" value="" placeholder="10" />
                        <strong><?php echo $editActiveGradeLabel ?></strong><br>
                        <input type="radio" id="yesEditActiveGrade" name="activeEditGrade" value="Y" checked>
                        <label for="yesEditActiveGrade">Yes</label>
                        <input type="radio" id="noEditActiveGrade" name="activeEditGrade" value="N">
                        <label for="noEditActiveGrade">No</label><br>

                        <input type="hidden" name="gradeEditID" id="gradeEditID" value="" />

                        <div class="box alt align-right"> 
                            <ul class="actions">
                                <input name="gradeEditEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

                <div id="gradeDelete" class="tabcontent">
                    <button id="delGradeTableButton" onclick="toggleTable('gradeTableModal', 'grade-close')">Select Grade to Delete</button>
                    <p><?php echo $delMsg;?></p>
                    <form method="post" action="">
                        <strong>Grade Level*</strong>
                        <input type="text" name="gradeDelNumber" id="gradeDelNumber" value="" placeholder="Select a grade to delete using the button above" disabled />
                        <strong>Active Y/N*</strong><br>
                        <input type="radio" id="yesDelActiveGrade" name="activeDelGrade" value="Y" disabled>
                        <label for="yesDelActiveGrade">Yes</label>
                        <input type="radio" id="noDelActiveGrade" name="activeDelGrade" value="N" disabled>
                        <label for="noDelActiveGrade">No</label><br>

                        <input type="hidden" name="gradeDelID" id="gradeDelID" value="" />

                        <div class="box alt align-right"> 
                            <ul class="actions">
                                <input name="gradeDelEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" class="init">
    //Since we use the same table for delete and edit, they populate the same fields
    //when we select one. Try selecting edit, then select some entry. Then go to delete
    //and select another entry. Go back to edit and now it'll be the one you selected in
    //the delete tab...
    var gradeTable;
    $(document).ready(function() {
        gradeTable = $('#gradeTable').DataTable( {
                "columnDefs": [
                    {
                        "targets":[ 0 ],
                        "visible": false,
                        "searchable": false
                    }
                ]
            });

        $('#editGradeTableButton').click(function() {
            $('#gradeTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = gradeTable.rows('.selected').data()[0];
                $('#gradeEditID').val(rowData[0]);
                $('#gradeEditNumber').val(rowData[1]);
                
                if (rowData[2] == 'Y') {
                    $('#yesEditActiveGrade').attr('checked', true);
                }
                else {
                    $('#noEditActiveGrade').attr('checked', true);
                }
                
                $(this).toggleClass('selected');

                var gradeTableModal = document.getElementById('gradeTableModal');
                gradeTableModal.style.display = "none";
            });
        });

        $('#delGradeTableButton').click(function() {
            $('#gradeTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = gradeTable.rows('.selected').data()[0];
                $('#gradeDelID').val(rowData[0]);
                $('#gradeDelNumber').val(rowData[1]);
                
                if (rowData[2] == 'Y') {
                    $('#yesDelActiveGrade').attr('checked', true);
                }
                else {
                    $('#noDelActiveGrade').attr('checked', true);
                }
                
                $(this).toggleClass('selected');

                var gradeTableModal = document.getElementById('gradeTableModal');
                gradeTableModal.style.display = "none";
            });
        });
    });
</script>

<div id="gradeTableModal" class="modal">
    <div class="modal-content">
        <span id="grade-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("GRADE");
        print '<table  id="gradeTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to fill in the form</caption>';
        print '<thead>
            <tr><th>gradeID</th><th>Grade</th><th>Active</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
            print '<tr>';
            print '<td>' . $row['gradeID'] . '<td>' . $row['grade'] . '</td><td>' . $row['active'] . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>

<div class = "2u$"></div>