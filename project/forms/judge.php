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

$fnView = "";
$lnView = "";
$emView = "";
$tView = "";
$empView = "";
$dgView = "";
$ciView = "";

$category1Pref = '';
$category2Pref = '';
$category3Pref = '';
$emCategory = "";

$grd1 = "";
$grd2 = "";
$grd3 = "";
$emGrade = "";

$viewMsg = "";
$catMsg = "";
$gradeMsg = "";

$fnViewLabel = "First Name*";
$firstNameViewValue = "";

$lnViewLabel = "Last Name*";
$lastNameViewValue = "";

$tViewLabel = "Title*";
$titleViewValue = "";

$emViewLabel = "Email*";
$emailViewValue = "";

$EmpViewLabel = "Employer*";
$employerViewValue = "";

$dgViewLabel = "Highest Degree Earned*";
$degreeViewValue = "";

$checkedInViewLabel = "Checked In Y/N*";
$checkedInView = "";

$fnCategoryLabel = "First Name*: ";
$lnCategoryLabel = "Last Name*: ";
$emCategoryLabel = "Email*: ";
$categoryLabel1 = "Preferred Category*: ";
$categoryLabel2 = "Preferred Category: ";
$categoryLabel3 = "Preferred Category: ";

$fnGradeLabel = "First Name*: ";
$lnGradeLabel = "Last Name*: ";
$emGradeLabel = "Email*: ";
$gradeLabel1 = "Preferred Grade*: ";
$gradeLabel2 = "Preferred Grade: ";
$gradeLabel3 = "Preferred Grade: ";

$id = "";
//----------------------EDIT VALIDATION-------------------------
if (isset($_POST['judgeViewEnter'])) {
    if (validateNormalField($_POST['judgeViewFirstName'], $fnViewLabel, $viewMsg, "Please enter first name", $firstNameViewValue, $fnView) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['judgeViewLastName'], $lnViewLabel, $viewMsg, "Please enter last name", $lastNameViewValue, $lnView) == false) {
        $errorHasOccured = true;
    }

    if (!filter_input(INPUT_POST, 'judgeViewEmail',FILTER_VALIDATE_EMAIL)) 
    {
        $emViewLabel = '<span style="color:red">Email*</span>';
        $errorHasOccured = true;
        if(empty($viewMsg))
        {
            $viewMsg = 'Things to fix: Email must be populated';
        }
        else $viewMsg = $viewMsg.", Email must be populated";
    }
    else 
    {
        $emView = trim($_POST['judgeViewEmail']);
    }

    if (validateNormalField($_POST['judgeViewTitle'], $titleViewLabel, $viewMsg, "Please enter a title", $dummy, $tView) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['judgeViewEmployer'], $employerViewLabel, $viewMsg, "Please enter an employer", $dummy, $empView) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['judgeViewDegree'], $dgViewLabel, $viewMsg, "Please select a level", $dummy, $dgView) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['checkedInViewJudge'], $checkedInViewLabel, $viewMsg, "Yes or No must be selected", $dummy, $ciView) == false) {
        $errorHasOccured = true;
    }
    if ($errorHasOccured == false) {
        $id = $_POST['judgeViewID'];
        $status = updateJudge($id, $fnView, $lnView, $emView, $tView, $empView, $dgView, $ciView);
        if($status != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar(`'.$status.'`, 9000);
			</script>';
		}
		else
		{
            $firstNameViewValue = '';
            $lastNameViewValue = '';
            $titleViewValue = '';
            $emailViewValue = '';
            $employerViewValue = '';
			$degreeViewValue = '';
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A judge was edited successfully!", 5000);
			</script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Judge").'", 5000);
        </script>';
    }
}

//---------------------- Category --------------------------
if (isset($_POST['judgeCategoryEnter'])) 
{
    if(validateNormalField($_POST['judgeCategoryName1'], $categoryLabel1, $catMsg, "1st preferred category must be chosen", $dummy, $category1Pref) == false)
    {
        $errorHasOccured = true;
    }

    //Are optional, so just set them to whatever is there
    $category2Pref = trim($_POST['judgeCategoryName2']);
    $category3Pref = trim($_POST['judgeCategoryName3']);

    //1 has priority over 2, 2 has priority over 3. 
    //neither has a preference rating or level logic, but
    //just for this check we're saying that so we know
    //which to clear (prevent duplicates)
    if($category2Pref == $category1Pref)
    {
        $category2Pref = '';
    }

    if($category3Pref == $category1Pref)
    {
        $category3Pref = '';
    }

    if($category3Pref == $category2Pref)
    {
        $category3Pref = '';
    }
	
    if ($errorHasOccured == false) {
        $judgeID = $_POST['judgeCategoryID'];
        $oldCat = $_POST['categoryID1'];
        $status1 = updateJudgeCatPref($judgeID, $category1Pref, $oldCat);
		$status2 = 'OK';
		$status3 = 'OK';
        
        if(empty($category2Pref) == false)
        {
            $oldCat = $_POST['categoryID2'];
            if(empty($oldCat))
            {
                $status2 = insertJudgeCatPref($_POST['judgeCategoryEmailValue'], $category2Pref, '', '');
            }
            else
            {
                $status2 = updateJudgeCatPref($judgeID, $category2Pref, $oldCat);
            }
        }
        else
        {
            $oldCat = $_POST['categoryID2'];
            if(empty($oldCat) == false)
            {
                $status3 = deleteAPreference($judgeID, "JUDGE_CATEGORY_PREFERENCE", "categoryID", $oldCat);
            }
        }

        if(empty($category3Pref) == false)
        { 
            $oldCat = $_POST['categoryID3'];
            if(empty($oldCat))
            {
                $status3 = insertJudgeCatPref($_POST['judgeCategoryEmailValue'], $category3Pref, '', '');
            }
            else
            {
                $status3 = updateJudgeCatPref($judgeID, $category3Pref, $oldCat);
            }
        }
        else
        {
            $oldCat = $_POST['categoryID3'];
            if(empty($oldCat) == false)
            {
                $status3 = deleteAPreference($judgeID, "JUDGE_CATEGORY_PREFERENCE", "categoryID", $oldCat);
            }
        }
		
        if($status1 != 'OK' || $status2 != 'OK' || $status3 != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar(`'.$status1.$status2.$status3.'`, 9000);
			</script>';
		}
		else
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A judge was edited successfully!", 5000);
			</script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Judge").'", 5000);
        </script>';
    }
}

//-------------------------- GRADE -------------------------------
if (isset($_POST['judgeGradeEnter']))
{
    if(validateNormalField($_POST['judgeGradeName1'], $gradeLabel1, $catMsg, "1st preferred grade must be chosen", $dummy, $grade1Pref) == false)
    {
        $errorHasOccured = true;
    }

    //Are optional, so just set them to whatever is there
    $grade2Pref = trim($_POST['judgeGradeName2']);
    $grade3Pref = trim($_POST['judgeGradeName3']);

    //1 has priority over 2, 2 has priority over 3. 
    //neither has a preference rating or level logic, but
    //just for this check we're saying that so we know
    //which to clear (prevent duplicates)
    if($grade2Pref == $grade1Pref)
    {
        $grade2Pref = '';
    }

    if($grade3Pref == $grade1Pref)
    {
        $grade3Pref = '';
    }

    if($grade3Pref == $grade2Pref)
    {
        $grade3Pref = '';
    }
	
    if ($errorHasOccured == false) {
        $judgeID = $_POST['judgeGradeID'];
        $oldGrade = $_POST['gradeID1'];
        $status1 = updateJudgeGradePref($judgeID, $grade1Pref, $oldGrade);
		$status2 = 'OK';
		$status3 = 'OK';
        
        if(empty($grade2Pref) == false)
        {
            $oldGrade = $_POST['gradeID2'];
            if(empty($oldGrade))
            {
                $status2 = insertJudgeGradPref($_POST['judgeGradeEmailValue'], $grade2Pref, '', '');
            }
            else
            {
                $status2 = updateJudgeGradePref($judgeID, $grade2Pref, $oldGrade);
            }
        }
        else
        {
            $oldGrade = $_POST['gradeID2'];
            if(empty($oldGrade) == false)
            {
                $status3 = deleteAPreference($judgeID, "JUDGE_GRADE_PREFERENCE", "projectGradeID", $oldGrade);
            }
        }

        if(empty($grade3Pref) == false)
        { 
            $oldGrade = $_POST['gradeID3'];
            if(empty($oldGrade))
            {
                $status3 = insertJudgeGradPref($_POST['judgeGradeEmailValue'], $grade3Pref, '', '');
            }
            else
            {
                $status3 = updateJudgeGradePref($judgeID, $grade3Pref, $oldGrade);
            }
        }
        else
        {
            $oldGrade = $_POST['gradeID3'];
            if(empty($oldGrade) == false)
            {
                $status3 = deleteAPreference($judgeID, "JUDGE_GRADE_PREFERENCE", "projectGradeID", $oldGrade);
            }
        }
		
        if($status1 != 'OK' || $status2 != 'OK' || $status3 != 'OK')
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar(`'.$status1.$status2.$status3.'`, 9000);
			</script>';
		}
		else
		{
			echo '
			<script id="toastCall" type="text/javascript">
				showSnackbar("A judge was edited successfully!", 5000);
			</script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("Judge").'", 5000);
        </script>';
    }
}
?>

<div class="2u"></div>
<div class="8u">
    <button type="button" class="collapsible">Judge Information</button>
    <div class="content">
        <div class="12u$">
            <div class='tabcontainer'>

                <div class="tab">
                    <button id="judgeStatusTab" class="tablinks checkedIn" onclick="changeTab(event, 'judgeStatus', false,'judgeStatusTab')">Status</button>
                    <button id="judgeViewTab" class="tablinks" onclick="changeTab(event, 'judgeView', false,'judgeViewTab')">View/Edit Base Info</button>
                    <button id="judgeCategoryTab" class="tablinks" onclick="changeTab(event, 'judgeCategory', false,'judgeCategoryTab')">View/Edit Category Preferences</button>
                    <button id="judgeGradeTab" class="tablinks" onclick="changeTab(event, 'judgeGrade', false,'judgeGradeTab')">View/Edit Grade Preferences</button>
                </div>
                <div id="judgeStatus" class="tabcontent">
                    <button id="viewJudgeStatusTableButton" onclick="toggleTable('judgeStatusTableModal', 'judgeView-close')">View Check-in Status</button>
				</div>
                <div id="judgeView" class="tabcontent">
                    <button id="viewJudgeTableButton" onclick="toggleTable('judgeTableModal', 'judge-close')">View Current Judges</button>
                    <p><?php echo $viewMsg; ?></p>
                    <form method="post" action="">
                        <strong><?php echo $fnViewLabel ?></strong>
                        <input type="text" name="judgeViewFirstName" id="judgeViewFirstName" value="<?php echo $firstNameViewValue ?>" placeholder="John" />
                        <strong><?php echo $lnViewLabel ?></strong>
                        <input type="text" name="judgeViewLastName" id="judgeViewLastName" value="<?php echo $lastNameViewValue ?>" placeholder="Doe" />
                        <strong><?php echo $emViewLabel ?></strong>
                        <input type="email" name="judgeViewEmail" id="judgeViewEmail" value="<?php echo $emailViewValue ?>" placeholder="JohnDoe@gmail.com" />
                        <strong><?php echo $tViewLabel ?></strong>
                        <input type="text" name="judgeViewTitle" id="judgeViewTitle" value="<?php echo $titleViewValue ?>" placeholder="Teacher" />
                        <strong><?php echo $EmpViewLabel ?></strong>
                        <input type="text" name="judgeViewEmployer" id="judgeViewEmployer" value="<?php echo $employerViewValue ?>" placeholder="Google" />
                        <strong><?php echo $dgViewLabel ?></strong>
                        <input type="text" name="judgeViewDegree" id="judgeViewDegree" value="<?php echo $degreeViewValue ?>" placeholder="Bachelor" />
                        <strong><?php echo $checkedInViewLabel ?></strong><br>
                        <input type="radio" id="yesViewActiveJudge" name="checkedInViewJudge" value="Y" checked>
                        <label for="yesViewActiveJudge">Yes</label>
                        <input type="radio" id="noViewActiveJudge" name="checkedInViewJudge" value="N">
                        <label for="noViewActiveJudge">No</label><br>

                        <input type="hidden" name="judgeViewID" id="judgeViewID" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="judgeViewEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>
                <div id="judgeCategory" class="tabcontent">
                    <button id="viewJudgeCategoryTableButton" onclick="toggleTable('judgeCategoryTableModal', 'judgeCategory-close')">View Current Judges</button>
                    <p><?php echo $catMsg; ?></p>
                    <form method="post" action="">
                        <strong><?php echo $fnCategoryLabel ?></strong><input type="text" name="judgeCategoryFirstName" id="judgeCategoryFirstName" placeholder="John" disabled />
                        <strong><?php echo $lnCategoryLabel ?></strong><input type="text" name="judgeCategoryLastName" id="judgeCategoryLastName" placeholder="Doe" disabled />
                        <strong><?php echo $emCategoryLabel ?></strong><input type="text" name="judgeCategoryEmail" id="judgeCategoryEmail" placeholder="JohnDoe@gmail.com" disabled/>
                        <strong><?php echo $categoryLabel1 ?></strong>
						<div class="select-wrapper">
							<select name="judgeCategoryName1" id="judgeCategoryName1">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'categoryID', field: 'categoryName', table: 'CATEGORY', placeholder: 'Category'},
										success: function(data){
											$('#judgeCategoryName1').html(data);
										}
									});
								</script>
							</select>
						</div>
                        <strong><?php echo $categoryLabel2 ?></strong>
						<div class="select-wrapper">
							<select name="judgeCategoryName2" id="judgeCategoryName2">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'categoryID', field: 'categoryName', table: 'CATEGORY', placeholder: 'Category'},
										success: function(data){
											$('#judgeCategoryName2').html(data);
										}
									});
								</script>
							</select>
						</div>
                        <strong><?php echo $categoryLabel3 ?></strong>
						<div class="select-wrapper">
							<select name="judgeCategoryName3" id="judgeCategoryName3">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'categoryID', field: 'categoryName', table: 'CATEGORY', placeholder: 'Category'},
										success: function(data){
											$('#judgeCategoryName3').html(data);
										}
									});
								</script>
							</select>
						</div><br>

                        <input type="hidden" name="judgeCategoryID" id="judgeCategoryID" value="" />
                        <input type="hidden" name="categoryID1" id="categoryID1" value="" />
                        <input type="hidden" name="categoryID2" id="categoryID2" value="" />
                        <input type="hidden" name="categoryID3" id="categoryID3" value="" />
                        <input type="hidden" name="judgeCategoryEmailValue" id="judgeCategoryEmailValue" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="judgeCategoryEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>
                <div id="judgeGrade" class="tabcontent">
                    <button id="viewJudgeGradeTableButton" onclick="toggleTable('judgeGradeTableModal', 'judgeGrade-close')">View Current Judges</button>
                    <p><?php echo $gradeMsg; ?></p>
                    <form method="post" action="">
                        <strong><?php echo $fnGradeLabel ?></strong><input type="text" name="judgeGradeFirstName" id="judgeGradeFirstName" placeholder="John" disabled />
                        <strong><?php echo $lnGradeLabel ?></strong><input type="text" name="judgeGradeLastName" id="judgeGradeLastName" placeholder="Doe" disabled />
                        <strong><?php echo $emGradeLabel ?></strong><input type="text" name="judgeGradeEmail" id="judgeGradeEmail" placeholder="JohnDoe@gmail.com" disabled />
                        <strong><?php echo $gradeLabel1 ?></strong>
						<div class="select-wrapper">
							<select name="judgeGradeName1" id="judgeGradeName1">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'projectGradeID', field: 'levelName', table: 'PROJECT_GRADE_LEVEL', placeholder: 'Grade Level'},
										success: function(data){
											$('#judgeGradeName1').html(data);
										}
									});
								</script>
							</select>
						</div>
                        <strong><?php echo $gradeLabel2 ?></strong>
						<div class="select-wrapper">
							<select name="judgeGradeName2" id="judgeGradeName2">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'projectGradeID', field: 'levelName', table: 'PROJECT_GRADE_LEVEL', placeholder: 'Grade Level'},
										success: function(data){
											$('#judgeGradeName2').html(data);
										}
									});
								</script>
							</select>
						</div>
                        <strong><?php echo $gradeLabel3 ?></strong>
						<div class="select-wrapper">
							<select name="judgeGradeName3" id="judgeGradeName3">
								<script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'projectGradeID', field: 'levelName', table: 'PROJECT_GRADE_LEVEL', placeholder: 'Grade Level'},
										success: function(data){
											$('#judgeGradeName3').html(data);
										}
									});
								</script>
							</select>
						</div><br>

                        <input type="hidden" name="judgeGradeID" id="judgeGradeID" value="" />
                        <input type="hidden" name="gradeID1" id="gradeID1" value="" />
                        <input type="hidden" name="gradeID2" id="gradeID2" value="" />
                        <input type="hidden" name="gradeID3" id="gradeID3" value="" />
                        <input type="hidden" name="judgeGradeEmailValue" id="judgeGradeEmailValue" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="judgeGradeEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" class="init">    
    var judgeTable;
	var judgeStatusTable;
    var judgeCategoryTable;
    var judgeGradeTable;
	
    $(document).ready(function() {
        judgeTable = $('#judgeTable').DataTable({
            "columnDefs": [{
                "targets": [0,7], // Hide both judgeID and levelID from view
                "visible": false,
                "searchable": false
            }]
        });
		
        judgeCategoryTable = $('#judgeCategoryTable').DataTable({
            "columnDefs": [{
                "targets": [0, 4, 6, 8], // hide both judgeID and categoryID
                "visible": false,
                "searchable": false
            }]
        });
		
        judgeGradeTable = $('#judgeGradeTable').DataTable({
            "columnDefs": [{
                "targets": [0, 4, 6, 8], // Hide both judgeID and gradeID
                "visible": false,
                "searchable": false
            }]
        });
		
        judgeStatusTable = $('#judgeStatusTable').DataTable();

        $('#viewJudgeTableButton').click(function() {
            $('#judgeTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = judgeTable.rows('.selected').data()[0];
                $('#judgeViewID').val(rowData[0]);
                $('#judgeViewFirstName').val(rowData[1]);
                $('#judgeViewLastName').val(rowData[2]);
                $('#judgeViewEmail').val(rowData[3]);
                $('#judgeViewTitle').val(rowData[5]);
                $('#judgeViewEmployer').val(rowData[6]);
                $('#judgeViewDegree').val(rowData[7]);

                if (rowData[7] == 'Y') {
                    $('#yesViewActiveJudge').attr('checked', true);
                } else {
                    $('#noViewActiveJudge').attr('checked', true);
                }

                $(this).toggleClass('selected');

                var judgeTableModal = document.getElementById('judgeTableModal');
                judgeTableModal.style.display = "none";
            });
        });

        $('#viewJudgeCategoryTableButton').click(function() {
            $('#judgeCategoryTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = judgeCategoryTable.rows('.selected').data()[0];
                $('#judgeCategoryID').val(rowData[0]);
                $('#judgeCategoryFirstName').val(rowData[1]);
                $('#judgeCategoryLastName').val(rowData[2]);
                $('#judgeCategoryEmail').val(rowData[3]);
                $('#judgeCategoryEmailValue').val(rowData[3]);
                $('#categoryID1').val(rowData[4]);
                $('#judgeCategoryName1').val(rowData[4]);
                if (rowData[6] == 'N/A') {
					$('#categoryID2').val('');
					$('#judgeCategoryName2').val('');
				}else{
					$('#categoryID2').val(rowData[6]);
					$('#judgeCategoryName2').val(rowData[6]);
                }
				if (rowData[8] == 'N/A') {
					$('#categoryID3').val('');
					$('#judgeCategoryName3').val('');
				}else{
					$('#categoryID3').val(rowData[8]);
					$('#judgeCategoryName3').val(rowData[8]);
				}

                $(this).toggleClass('selected');

                var judgeCategoryTableModal = document.getElementById('judgeCategoryTableModal');
                judgeCategoryTableModal.style.display = "none";
            });
        });
		
        $('#viewJudgeGradeTableButton').click(function() {
            $('#judgeGradeTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = judgeGradeTable.rows('.selected').data()[0];
                $('#judgeGradeID').val(rowData[0]);
                $('#judgeGradeFirstName').val(rowData[1]);
                $('#judgeGradeLastName').val(rowData[2]);
                $('#judgeGradeEmail').val(rowData[3]);
                $('#judgeGradeEmailValue').val(rowData[3]);
                $('#gradeID1').val(rowData[4]);
                $('#judgeGradeName1').val(rowData[4]);
                if (rowData[6] == 'N/A') {
					$('#gradeID2').val('');
					$('#judgeGradeName2').val('');
				}else{
					$('#gradeID2').val(rowData[6]);
					$('#judgeGradeName2').val(rowData[6]);
                }
				if (rowData[8] == 'N/A') {
					$('#gradeID3').val('');
					$('#judgeGradeName3').val('');
				}else{
					$('#gradeID3').val(rowData[8]);
					$('#judgeGradeName3').val(rowData[8]);
				}
				

                $(this).toggleClass('selected');

                var judgeGradeTableModal = document.getElementById('judgeGradeTableModal');
                judgeGradeTableModal.style.display = "none";
            });
        });
    });
</script>

<div id="judgeStatusTableModal" class="modal">
    <div class="modal-content">
        <span id="judgeView-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("v_JUDGE");
        print '<table  id="judgeStatusTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Judges</caption>';
        print '<thead>
            <tr><th>Check-in Status</th><th>First Name</th><th>Last Name</th><th>Title</th><th>Email/Username</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
			if($row['checkin'] == 'Y') $color = 'green';
			else $color = 'red';
            print '<tr>';
            print '<td><span style="font-weight:bolder; color:' . $color . ';">' . $row['checkin'] . '</span>' . '</td><td>' . $row['firstName'] . '</td><td>' . $row['lastName'] . '</td><td>' . $row['title'] . '</td><td>' . $row['email'] . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>

<div id="judgeTableModal" class="modal">
    <div class="modal-content">
        <span id="judge-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("v_JUDGE");
        print '<table  id="judgeTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to edit an entry</caption>';
        print '<thead>
            <tr><th>judgeID</th><th>First Name</th><th>Last Name</th><th>Email/Username</th><th>Password</th><th>Title</th><th>Employer</th><th>Degree</th><th>checkin</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
            print '<tr>';
            print '<td>' . $row['judgeID'] . '</td><td>' . $row['firstName'] . '</td><td>' . $row['lastName'] . '</td><td>' . $row['email'] . '</td><td>' . $row['password'] . '</td><td>' . $row['title'] . '</td><td>' . $row['employer'] . '</td><td>' . $row['highestDegree'] . '</td><td>' . $row['checkin'] . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>

<div id="judgeCategoryTableModal" class="modal">
    <div class="modal-content">
        <span id="judgeCategory-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("v_JUDGE_CATEGORY_PREFERENCE");
        print '<table  id="judgeCategoryTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to edit category preference</caption>';
        print '<thead>
            <tr><th>judgeID</th><th>First Name</th><th>Last Name</th><th>Email/Username</th><th>1st Category Preference ID</th><th>Category Preference</th><th>2nd Category Preference ID</th><th>Category Preference</th><th>3rd Category Preference ID</th><th>Category Preference</th></tr></thead><tfoot>';
        print '<tbody>';
        
		$consume = array();
        foreach ($rows as $row) {
            $judgeID = $row['judgeID'];
			$exists = false;
			foreach ($consume as $consumed){
				if($judgeID == $consumed){ 
					$exists = true;
				}
			}
			if(!$exists){
				array_push($consume, $judgeID);
				$missing = 2;
				$categoryID = $row['categoryID'];
				print '<tr>';
				print '<td>' . $judgeID . '</td><td>' . $row['firstName'] . '</td><td>' . $row['lastName'] . '</td><td>' . $row['email'] . '</td><td>' . $categoryID . '</td><td>' . $row['categoryName'] . '</td>';
				foreach ($rows as $row2){
					if($judgeID == $row2['judgeID']){
						$categoryID2 = $row2['categoryID'];
						if($categoryID != $categoryID2){
							$missing--;
							print '<td>' . $categoryID2 . '</td><td>' . $row2['categoryName'] . '</td>';
						}
					}
				}
				for($i = 0; $i < $missing; $i++){
					print '<td>N/A</td><td>N/A</td>';
				}
				print '</tr>';
			}
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>

<div id="judgeGradeTableModal" class="modal">
    <div class="modal-content">
        <span id="judgeGrade-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("v_JUDGE_GRADE_PREFERENCE");
        print '<table  id="judgeGradeTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to edit grade level preference</caption>';
        print '<thead>
            <tr><th>judgeID</th><th>First Name</th><th>Last Name</th><th>Email/Username</th><th>1st Grade Preference ID</th><th>Grade Preference</th><th>2nd Grade Preference ID</th><th>Grade Preference</th><th>3rd Grade Preference ID</th><th>Grade Preference</th></tr></thead><tfoot>';
        print '<tbody>';
		$consume = array();
        foreach ($rows as $row) {
			$judgeID = $row['judgeID'];
			$exists = false;
			foreach ($consume as $consumed){
				if($judgeID == $consumed){ 
					$exists = true;
				}
			}
			if(!$exists){
				array_push($consume, $judgeID);
				$missing = 2;
				$projectGradeID = $row['projectGradeID'];
				print '<tr>';
				print '<td>' . $judgeID . '</td><td>' . $row['firstName'] . '</td><td>' . $row['lastName'] . '</td><td>' . $row['email'] . '</td><td>' . $projectGradeID . '</td><td>' . $row['levelName'] . '</td>';
				foreach ($rows as $row2){
					if($judgeID == $row2['judgeID']){
						$projectGradeID2 = $row2['projectGradeID'];
						if($projectGradeID != $projectGradeID2){
							$missing--;
							print '<td>' . $projectGradeID2 . '</td><td>' . $row2['levelName'] . '</td>';
						}
					}
				}
				for($i = 0; $i < $missing; $i++){
					print '<td>N/A</td><td>N/A</td>';
				}
				print '</tr>';
			}
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>

<div class="2u$"></div>