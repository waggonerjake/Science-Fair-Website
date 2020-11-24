<?php
	require_once "dbconnect.php";
    require_once "util.php";
    require_once "insertion.php";
    require_once "selection.php";
    require_once "update.php";
    require_once "deletion.php";

    $msg = "";
    $editMsg = "";
    $delMsg = "";

    $countyLabel = "County*";
    $editCountyLabel = "County*";
    $county = "";
    $editCounty = "";

    if(isset($_POST['countyAddEnter']))
    {
        if(validateNormalField($_POST['county'], $countyLabel, $msg, "County must be specified", $dummy, $county) == true)
        {
            $status = insertCounty($county);
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
                    showSnackbar("A county was added successfully!", 5000);
                </script>';
            }
        }
        else
        {
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar("'.getToastErrorMessage("County").'", 5000);
            </script>';
        }
    }

    if(isset($_POST['countyEditEnter']))
    {
        if(validateNormalField($_POST['countyEditName'], $editCountyLabel, $editMsg, "County must be specified", $dummy, $editCounty) == true)
        {
            $id = $_POST['countyEditID'];
            $status = updateCounty($id, $editCounty);
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
                    showSnackbar("A county was edited successfully!", 5000);
                </script>';
            }
        }
        else
        {
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar("'.getToastErrorMessage("County").'", 5000);
            </script>';
        }
    }

    if(isset($_POST['countyDelEnter']))
    {
        if(empty(trim($_POST['countyDelID'])) == false)
        {
            $id = $_POST['countyDelID'];
            $status = deleteRecordFromTable($id,"COUNTY","countyID");
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
                    showSnackbar("A county was deleted successfully!", 5000);
                </script>';
            }
        }
        else
        {
            $delMsg = "<span style=\"color:red\">Please choose a county to delete using the button above.</span>";
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar("'.getToastErrorMessage("County").'", 5000);
            </script>';
        }
    }

?>

<div class = "2u"></div>
<div class = "8u">
    <button type="button" class="collapsible">County</button>
    <div class="content">
        <div class = "12u$">
            <div class='tabcontainer'>
                <div class="tab">
                    <button id="countyAddTab" class="tablinks active" onclick="changeTab(event, 'countyAdd', false, 'countyAddTab')">Add</button>
                    <button id="countyEditTab" class="tablinks" onclick="changeTab(event, 'countyEdit', false, 'countyEditTab')">Edit</button>
                    <button id="countyDelTab" class="tablinks" onclick="changeTab(event, 'countyDelete', false, 'countyDelTab')">Delete</button>
                </div>

                <div id="countyAdd" class="tabcontent">
                    <button id="addCountyTableButton" onclick="toggleTable('countyTableModal', 'county-close')">View Current Counties</button>
                    <p><?php echo $msg;?></p>
                    <form method="post" action="">
                        <strong><?php echo $countyLabel?></strong>
                        <input type="text" name="county" id="county" value="" placeholder="Johnson"/>
                        <div class="box alt align-right"> 
                            <ul class="actions">
                                <input name="countyAddEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>
                
                <div id="countyEdit" class="tabcontent">
                    <button id="editCountyTableButton" onclick="toggleTable('countyTableModal', 'county-close')">Select County to Edit</button>
                    <p><?php echo $editMsg;?></p>
                    <form method="post" action="">
                        <strong><?php echo $editCountyLabel?></strong>
                        <input type="text" name="countyEditName" id="countyEditName" value="" placeholder="Johnson"/>

                        <input type="hidden" name="countyEditID" id="countyEditID" value="" />

                        <div class="box alt align-right"> 
                            <ul class="actions">
                                <input name="countyEditEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

                <div id="countyDelete" class="tabcontent">
                    <button id="delCountyTableButton" onclick="toggleTable('countyTableModal', 'county-close')">Select County to Delete</button>
                    <p><?php echo $delMsg;?></p>
                    <form method="post" action="">
                        <strong>County*</strong>
                        <input type="text" name="countyDelName" id="countyDelName" value="" placeholder="Select a county to delete using the button above" disabled/>

                        <input type="hidden" name="countyDelID" id="countyDelID" value="" />

                        <div class="box alt align-right"> 
                            <ul class="actions">
                                <input name="countyDelEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" class="init">
    var countyTable;
    $(document).ready(function() {
        countyTable = $('#countyTable').DataTable( {
                "columnDefs": [
                    {
                        "targets":[ 0 ],
                        "visible": false,
                        "searchable": false
                    }
                ]
            });

        $('#editCountyTableButton').click(function() {
            $('#countyTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = countyTable.rows('.selected').data()[0];
                $('#countyEditID').val(rowData[0]);
                $('#countyEditName').val(rowData[1]);
                
                $(this).toggleClass('selected');

                var countyTableModal = document.getElementById('countyTableModal');
                countyTableModal.style.display = "none";
            });
        });

        $('#delCountyTableButton').click(function() {
            $('#countyTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = countyTable.rows('.selected').data()[0];
                $('#countyDelID').val(rowData[0]);
                $('#countyDelName').val(rowData[1]);
                
                $(this).toggleClass('selected');

                var countyTableModal = document.getElementById('countyTableModal');
                countyTableModal.style.display = "none";
            });
        });
    });
</script>

<div id="countyTableModal" class="modal">
    <div class="modal-content">
        <span id="county-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("COUNTY");
        print '<table  id="countyTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to fill in the form</caption>';
        print '<thead>
            <tr><th>countyID</th><th>County Name</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
            print '<tr>';
            print '<td>' . $row['countyID'] . '<td>' . $row['countyName'] . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>

<div class = "2u$"></div>