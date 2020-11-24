
<?php
require_once "dbconnect.php";
require_once "util.php";
require_once "selection.php";
require_once "update.php";

$currentID = $_SESSION['id'];

$timeLable = "Session Time:";
$numberLable = "Project Number: ";
$titleLable = "Title: ";
$boothLable = "Booth: ";
$scoreLable = "Score: ";

$id = "";
$score = "";
$msg = "";
$errorHasOccured = false;
//----------------------EDIT VALIDATION-------------------------
if (isset($_POST['scoreSubmit'])) {
	
    if (validateNormalField($_POST['assignmentScore'], $scoreLable, $msg, "score must be populated", $dummy, $score) == false) {
        $errorHasOccured = true;
    }
	
	if(!is_numeric($score) && empty($score) == false){
		$errorMessage = "score must be numeric";
	    if(empty($msg))
        {
            $msg = 'Error: '.$errorMessage;
        }
        else $msg = $msg.", ".$errorMessage;
		$errorHasOccured = true;
	}
	
	if(($score < 1 || $score > 100) && empty($score) == false){
		$errorMessage = "score must be between 1 and 100";
	    if(empty($msg))
        {
            $msg = 'Error: '.$errorMessage;
        }
        else $msg = $msg.", ".$errorMessage;
		$errorHasOccured = true;
	}
	
    if ($errorHasOccured == false) {
        $id = $_POST['assignmentID'];
        $status = updateScore($id, $score);
		
		callStoredProcedure("update_ranks", [$currentID], false);
		
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
				showSnackbar("A score was entered successfully!", 5000);
			</script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Score").'", 5000);
        </script>';
    }
}
?>
<div class = "2u"></div>
<div class = "8u">
    <button type="button" class="collapsible">Score a project</button>
    <div class="content">
        <div class = "12u$">
            <div class='tabcontainer'>
                <div class="tab">
                    <button id = "scoreTab" class="tablinks active" onclick="changeTab(event, 'score', false,'scoreTab')">Enter a Score</button>
                </div>

                <div id="score" class="tabcontent">
                    <button id="viewAssignmentsButton" onclick="toggleTable('scoreTableModal', 'assignment-close')">Select Project</button>
                    <p><?php echo $msg; ?></p>
                    <form method="post" action="">
                        <strong><?php echo $timeLable ?> </strong> <span id="timeStartSmall"></span><span id="timeEndSmall"></span><br>
                        <strong><?php echo $numberLable ?> </strong> <span id="numberSmall"></span><br>
                        <strong><?php echo $titleLable ?> </strong> <span id="titleSmall"></span><br>
                        <strong><?php echo $boothLable ?> </strong> <span id="boothSmall"></span><br>
                        <strong><?php echo $scoreLable ?></strong>
                        <div class = "3u">
                            <input type="text" name="assignmentScore" id="assignmentScore" value="" placeholder="1 to 100"/>
                        </div>
                                
                        <input type="hidden" name="assignmentID" id="assignmentID" value="" />
                        
                        <div class="box alt align-right"> 
                            <ul class="actions">
                                <input name="scoreSubmit" class="btn special" id="scoreSubmit" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" class="init">    
    var scoreTable;
    $(document).ready(function() {
        scoreTable = $('#scoreTable').DataTable({
            "columnDefs": [{
                "targets": [0], // Hide assignment ID
                "visible": false,
                "searchable": false
            }]
        });

        $('#viewAssignmentsButton').click(function() {
            $('#scoreTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = scoreTable.rows('.selected').data()[0];
                $('#assignmentID').val(rowData[0]);
                $('#timeStartSmall').text(rowData[4] + '-');
                $('#timeEndSmall').text(rowData[5]);
                $('#numberSmall').text(rowData[1]);
                $('#titleSmall').text(rowData[2]);
                $('#boothSmall').text(rowData[3]);
				if(rowData[6] != 'N/A')
                {
                    $('#assignmentScore').val('Score has already been entered');
                    $('#assignmentScore').prop('disabled', true);
                }
                else
                {
                    $('#assignmentScore').val('');
                    $('#assignmentScore').prop('disabled', false);
                }


                $(this).toggleClass('selected');

                var scoreTableModal = document.getElementById('scoreTableModal');
                scoreTableModal.style.display = "none";
            });
        });
    });
</script>

<div id="scoreTableModal" class="modal">
    <div class="modal-content">
        <span id="assignment-close" class="modal-close">&times;</span>
        <?php
		$rows = getRowsByField('v_ASSIGNMENT', 'judgeID', $currentID);
        print '<table  id="scoreTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to score a project</caption>';
        print '<thead>
            <tr><th>assignmentID</th><th>Project Number</th><th>Title</th><th>Booth Number</th><th>Session Start Time</th><th>Session End Time</th><th>Score</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
			$storedScore = $row['score'];
            $storedScore = ($storedScore == -1) ? 'N/A' : $storedScore;
            print '<tr>';
            print '<td>' . $row['assignmentID'] . '</td><td>' . $row['projectNumber'] . '</td><td>' . $row['title'] . '</td><td>' . $row['boothNumber'] . '</td><td>' . $row['startTime'] . '</td><td>' . $row['endTime'] . '</td><td>' . $storedScore . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>
<div class="2u$"></div>