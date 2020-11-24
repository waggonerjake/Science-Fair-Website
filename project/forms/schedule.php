<?php
require_once "dbconnect.php";
require_once "util.php";
include "generateSchedule.php";
require_once "update.php";

$title = "";

//TODO: Needs validation and overall testing
if (isset($_POST['scheduleEditEnter'])) {
    $id = $_POST["assignmentId"];
    $judgeId = $_POST["scheduleEditJudge"];
    $sessionID = $_POST["scheduleEditSession"];
    $projectId = $_POST['projectId'];
    // $boothId = $_POST['scheduleEditBooth'];
    $status = updateAssignment($id, $judgeId, $sessionID);
    //$status2 = updateProjectBooth($projectId, $boothId);
    if($status != 'OK')
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar(`'.$status.'`, 9000);
        </script>';
    }
    // else if ($status2 != 'OK') {
    //     echo '
    //     <script id="toastCall" type="text/javascript">
    //         showSnackbar(`'.$status2.'`, 9000);
    //     </script>';
    // }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("The schedule was edited successfully!", 5000);
        </script>';
    }
}
?>

<div class="2u"></div>
<div class="8u">
    <button type="button" class="collapsible">Schedule & Scores</button>
    <div class="content">
        <div class="12u$">
            <div class='tabcontainer'>
                <!-- Tab links -->
                <div class="tab">
                    <button id="scheduleViewTab" class="tablinks active" onclick="changeTab(event, 'scheduleView', false, 'scheduleViewTab')">View</button>
                    <?php if ($_SESSION['glc'] == false) : ?>
                    <button id="scheduleGenTab" class="tablinks" onclick="changeTab(event, 'generateSchedule', false, 'scheduleGenTab')">Generate</button>
                    <button id="scheduleEditTab" class="tablinks" onclick="changeTab(event, 'scheduleEdit', false, 'scheduleEditTab')">Edit</button>
                    <?php endif; ?>
                </div>

                <!-- Tab content -->
                <div id="scheduleView" class="tabcontent">
                    <button id="viewScheduleTableButton" onclick="toggleTable('scheduleTableModal', 'schedule-close')">View Current Schedule & Scores</button>
                </div>


                <div id="generateSchedule" class="tabcontent">
                    <form method="post" action="">
                        <div class="box alt align-left">
                            <ul class="actions">
                                <input name="scheduleGenEnter" type="submit" value="Generate" />
                            </ul>
                        </div>
                    </form>
                    <div class="10u$"></div>
                </div>

                <div id="scheduleEdit" class="tabcontent">
                    <button id="editScheduleTableButton" onclick="toggleTable('scheduleTableModal', 'schedule-close')">View Current Schedule</button>
                    <form method="post" action="">
                        <strong>Session Name</strong>
                        <div class="select-wrapper">
                            <select name="scheduleEditSession" id="scheduleEditSession" onchange="setNames(this.value)">
                                <script>
                                    $.ajax({
                                        type: "GET",
                                        url: 'util.php',
                                        data: {
                                            id: 'sessionID',
                                            field: 'name',
                                            table: 'SESSION',
                                            placeholder: 'Session'
                                        },
                                        success: function(data) {
                                            $('#scheduleEditSession').html(data);
                                        }
                                    });
                                </script>
                            </select>
                        </div>
                        <strong>Project Title</strong>
                        <input type="text" name="scheduleEditProject" id="scheduleEditProject" value="" placeholder="Select an assignment using the button above to reassign the judge" disabled />
                        <strong>Project Number</strong>
                        <input type="text" name="scheduleEditProjectNum" id="scheduleEditProjectNum" value="" placeholder="Select an assignment using the button above to reassign the judge" disabled />
                        <!-- <strong>Booth Number</strong>
                        <div class="select-wrapper">
                            <select name="scheduleEditBooth" id="scheduleEditBooth" onchange="setNames(this.value)">
                                <script>
                                    $.ajax({
                                        type: "GET",
                                        url: 'util.php',
                                        data: {
                                            unassignedBoothID: 'boothID',
                                            placeholder: 'Booth Number'
                                        },
                                        success: function(data) {
                                            $('#scheduleEditBooth').html(data);
                                        }
                                    });
                                </script>
                            </select>
                        </div> -->
                        <strong>Judge First Name</strong>
                        <input type="text" name="scheduleEditFirst" id="scheduleEditFirst" value="" placeholder="Select an assignment using the button above to reassign the judge" disabled />
                        <strong>Judge Last Name</strong>
                        <input type="text" name="scheduleEditLast" id="scheduleEditLast" value="" placeholder="Select an assignment using the button above to reassign the judge" disabled />
                        <strong>Judge*</strong>
                        <div class="select-wrapper">
                            <select name="scheduleEditJudge" id="scheduleEditJudge" onchange="setNames(this.value)">
                                <script>
                                    $.ajax({
                                        type: "GET",
                                        url: 'util.php',
                                        data: {
                                            id: 'judgeID',
                                            field: 'email',
                                            table: 'JUDGE',
                                            placeholder: 'Judge Email'
                                        },
                                        success: function(data) {
                                            $('#scheduleEditJudge').html(data);
                                        }
                                    });
                                </script>
                            </select>
                        </div>
                        <input type="hidden" name="assignmentId" id="assignmentId" value="" />
                        <input type="hidden" name="projectId" id="projectId" value="" />
                        <div class="box alt align-left">
                            <ul class="actions">
                                <input name="scheduleEditEnter" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                    <div class="10u$"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" class="init">
    var scheduleTable;
    $(document).ready(function() {
        scheduleTable = $('#scheduleTable').DataTable({
            "columnDefs": [{
                "targets": [0, 11, 12, 13, 14],
                "visible": false,
                "searchable": false
            }]
        });

        $('#editScheduleTableButton').click(function() {
            $('#scheduleTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = scheduleTable.rows('.selected').data()[0];
                $('#scheduleEditSession').val(rowData[11]);
                $('#scheduleEditJudge').val(rowData[12]);
                $('#assignmentId').val(rowData[0]);
                $('projectId').val(rowData[13]);
                // $('scheduleEditBooth').append('<option value=' + rowData[14] + '>' + rowData[14] + '</option>');
                $('#scheduleEditProject').val(rowData[9]);
                $('#scheduleEditFirst').val(rowData[1]);
                $('#scheduleEditLast').val(rowData[2]);
                $('#scheduleEditProjectNum').val(rowData[8]);
                $(this).toggleClass('selected');

                
                var scheduleTableModal = document.getElementById('scheduleTableModal');
                scheduleTableModal.style.display = "none";
            });
        });
    });

    function setNames(id) {
        $.ajax({
            type: "GET",
            url: 'selection.php',
            dataType: 'json',
            data: {
                select: 'dummy',
                field: 'judgeID',
                table: 'JUDGE',
                value: id
            },
            success: function(data) {
                document.getElementById('scheduleEditFirst').value = data[0]['firstName'];
                document.getElementById('scheduleEditLast').value = data[0]['lastName'];
            }
        });
    }
</script>
<div id="scheduleTableModal" class="modal">
    <div class="modal-content">
        <span id="schedule-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("v_ASSIGNMENT");
        print '<table  id="scheduleTable" class="display" cellspacing="0" width="100%">';
        print '<thead>
            <tr><th>AssignmentID</th><th>Judge First Name</th><th>Judge Last Name</th><th>Judge Email</th><th>Session Name</th><th>Session Start</th><th>Session End</th><th>BoothNumber</th><th>Project Number</th><th>Project Title</th><th>Score</th><th>SessionID</th><th>JudgeID</th><th>ProjectID</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
            $score = $row['score'];
            $score = ($score == -1) ? 'N/A' : $score;
            print '<tr>';
            print '<td>' . $row['assignmentID'] . '</td><td>' . $row['firstName'] . '</td><td>' . $row['lastName'] . '</td><td>' . $row['email'] . '</td><td>' . $row['name'] . '</td><td>' . $row['startTime'] . '</td><td>' . $row['endTime'] . '</td><td>' . $row['boothNumber'] . '</td><td>' . $row['projectNumber'] . '</td><td>' . $row['title'] . '</td><td>' . $score . '</td><td>' . $row['sessionID'] . '</td><td>' . $row['judgeID'] . '</td><td>' . $row['projectID'] . '</td><td>' . $row['projectID'] . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>
<div class="2u$"></div>