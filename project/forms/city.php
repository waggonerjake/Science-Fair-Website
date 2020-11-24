<?php
require_once "dbconnect.php";
require_once "util.php";
require_once "insertion.php";
require_once "update.php";
require_once "deletion.php";

$errorHasOccured = false;
$msg = "";

$cityLabel = "City*";
$city = "";

$cityEditLabel = "City*";
$cityEdit = "";

$countyEditLabel = "County*";
$countyEdit = "";

$countyLabel = "County*";
$county = "";

if (isset($_POST['cityAddEnter'])) {
    if (validateNormalField($_POST['city'], $cityLabel, $msg, "Enter a city", $dummy, $city) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['cityCounty'], $countyLabel, $msg, "Select a county", $dummy, $county) == false) {
        echo 'inserting';
        $errorHasOccured = true;
    }

    if ($errorHasOccured == false) 
    {
        $status = insertCity($city, $county);
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
				showSnackbar("A city was added successfully!", 5000);
			</script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("City").'", 5000);
        </script>';
    }
}
if (isset($_POST['cityEditEnter'])) {
    if (validateNormalField($_POST['cityEditInput'], $cityEditLabel, $msg, "Enter a city", $dummy, $cityEdit) == false) {
        $errorHasOccured = true;
    }
    if (validateNormalField($_POST['cityEditCounty'], $countyEditLabel, $msg, "Select a county", $dummy, $countyEdit) == false) {
        $errorHasOccured = true;
    }

    if ($errorHasOccured == false) 
    {
        $id = $_POST['cityEditID'];
        $status = updateCity($id, $cityEdit, $countyEdit);
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
				showSnackbar("A city was edited successfully!", 5000);
			</script>';
		}
    }
    else
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("City").'", 5000);
        </script>';
    }
}
if(isset($_POST['cityDelEnter']))
{
    if(empty(trim($_POST['cityDelID'])) == false)
    {
        $id = $_POST['cityDelID'];
        $status = deleteRecordFromTable($id,"CITY","cityId");
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
				showSnackbar("A city was deleted successfully!", 5000);
			</script>';
		}
    }
    else
    {
        $delMsg = "<span style=\"color:red\">Please choose a booth to delete using the button above.</span>";
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.getToastErrorMessage("City").'", 5000);
        </script>';
    }
}
?>

<div class="2u"></div>
<div class="8u">
    <button type="button" class="collapsible">City</button>
    <div class="content">
        <div class="12u$">
            <div class='tabcontainer'>
                <!-- Tab links -->
                <div class="tab">
                    <button id="cityAddTab" class="tablinks active" onclick="changeTab(event, 'cityAdd', false, 'cityAddTab')">Add</button>
                    <button id="cityEditTab" class="tablinks" onclick="changeTab(event, 'cityEdit', false, 'cityEditTab')">Edit</button>
                    <button id="cityDelTab" class="tablinks" onclick="changeTab(event, 'cityDelete', false, 'cityDelTab')">Delete</button>
                </div>

                <!-- Tab content -->
                <div id="cityAdd" class="tabcontent">
                    <button id="addCityTableButton" onclick="toggleTable('cityTableModal', 'city-close')">View Current Cities</button>
                    <p><?php echo $msg; ?></p>
                    <form method="post" action="">
                        <strong><?php echo $cityLabel ?></strong>
                        <input type="text" name="city" id="city" value="" placeholder="Indianapolis" />
                        <strong><?php echo $countyLabel ?></strong>
                        <div class="select-wrapper">
                            <select name="cityCounty" id="cityCounty">
                                <script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'countyID', field: 'countyName', table: 'COUNTY', placeholder: 'County'},
										success: function(data){
											$('#cityCounty').html(data);
										}
									});
								</script>
                            </select>
                        </div>
                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="cityAddEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

                <div id="cityEdit" class="tabcontent">
                    <button id="editCityTableButton" onclick="toggleTable('cityTableModal', 'city-close')">Select City to Edit</button>
                    <p><?php echo $editMsg; ?></p>
                    <form method="post" action="">
                        <strong><?php echo $cityEditLabel ?></strong>
                        <input type="text" name="cityEditInput" id="cityEditInput" value="" placeholder="Indianapolis" />
                        <strong><?php echo $countyEditLabel ?></strong>
                        <div class="select-wrapper">
                            <select name="cityEditCounty" id="cityEditCounty">
                                <script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'countyID', field: 'countyName', table: 'COUNTY', placeholder: 'County'},
										success: function(data){
											$('#cityEditCounty').html(data);
										}
									});
								</script>
                            </select>
                        </div>
                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="cityEditEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>

                        <input type="hidden" name="cityEditID" id="cityEditID" value="" />

                    </form>
                </div>

                <div id="cityDelete" class="tabcontent">
                <button id="delCityTableButton" onclick="toggleTable('cityTableModal', 'city-close')">Select City to Edit</button>
                    <p><?php echo $delMsg; ?></p>
                    <form method="post" action="">
                        <strong>City*</strong>
                        <input type="text" name="cityDelInput" id="cityDelInput" value="" placeholder="Indianapolis" disabled/>
                        <strong>County*</strong>
                        <div class="select-wrapper">
                            <select name="cityDelCounty" id="cityDelCounty" disabled>
                                <script>
									$.ajax({
										type: "GET",
										url: 'util.php',
										data: {id: 'countyID', field: 'countyName', table: 'COUNTY', placeholder: 'County'},
										success: function(data){
											$('#cityDelCounty').html(data);
										}
									});
								</script>
                            </select>
                        </div>
                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="cityDelEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>

                        <input type="hidden" name="cityDelID" id="cityDelID" value="" />

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" language="javascript" class="init">
    var cityTable;
    $(document).ready(function() {
        cityTable = $('#cityTable').DataTable({
            "columnDefs": [{
                "targets": [0,3],
                "visible": false,
                "searchable": false
            }]
        });

        $('#editCityTableButton').click(function() {
            $('#cityTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = cityTable.rows('.selected').data()[0];
                $('#cityEditID').val(rowData[0]);
                $('#cityEditInput').val(rowData[1]);
                $('#cityEditCounty').val(rowData[3]);

                $(this).toggleClass('selected');

                var cityTableModal = document.getElementById('cityTableModal');
                cityTableModal.style.display = "none";
            });
        });

        $('#delCityTableButton').click(function() {
            $('#cityTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = cityTable.rows('.selected').data()[0];
                $('#cityDelID').val(rowData[0]);
                $('#cityDelInput').val(rowData[1]);
                $('#cityDelCounty').val(rowData[3]);

                $(this).toggleClass('selected');

                var cityTableModal = document.getElementById('cityTableModal');
                cityTableModal.style.display = "none";
            });
        });
    });
</script>

<div id="cityTableModal" class="modal">
    <div class="modal-content">
        <span id="city-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("v_CITY");
        print '<table  id="cityTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to fill in the form</caption>';
        print '<thead>
            <tr><th>cityID</th><th>City Name</th><th>County Name</th><th>CountyID</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
            print '<tr>';
            print '<td>' . $row['cityID'] . '</td><td>' . $row['cityName'] . '</td><td>' . $row['CountyName'] . '</td><td>' . $row['countyID'] . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>
<div class="2u$"></div>