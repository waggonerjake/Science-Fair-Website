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


	$categoryAddLabel = "Category Name*";
	$categoryEditLabel = "Category Name*";
	$categoryName = "";
	$editCategoryName = "";
	$categoryAddValue = "";
	$categoryEditValue = "";

	$activeCategoryAddLabel = "Active Y/N*";
	$activeCategoryEditLabel = "Active Y/N*";
	$activeCategory = "";
	$editActiveCategory = "";

	if(isset($_POST['categoryAddEnter']))
	{
        if(validateNormalField($_POST['category'], $categoryAddLabel, $addMsg, "Category must be specified", $categoryAddValue, $categoryName) == false)
        {
            $errorHasOccured = true;
        }

        if(validateNormalField($_POST['activeCategory'], $activeCategoryAddLabel, $addMsg, "Yes or No must be selected", $dummy, $activeCategory) == false)
        {
            $errorHasOccured = true;
        }

        if($errorHasOccured == false)
        {
            $status = insertCategory($categoryName, $activeCategory);
            if($status != 'OK')
            {
                echo '
                <script id="toastCall" type="text/javascript">
                    showSnackbar(`'.$status.'`, 9000);
                </script>';
            }
            else
            {
                $categoryAddValue = '';
                echo '
			    <script id="toastCall" type="text/javascript">
				    showSnackbar("A category was added successfully!", 5000);
			    </script>';
            }
        }
        else
        {
            echo '
            <script id="toastCall" type="text/javascript">
                showSnackbar("'.getToastErrorMessage("Category").'", 5000);
            </script>';
        }
	}

	if(isset($_POST['categoryEditEnter']))
	{
    		if(validateNormalField($_POST['categoryEditName'], $categoryEditLabel, $editMsg, "Category name must be specified", $categoryEditValue, $editCategoryName) == false)
   		{
        		$errorHasOccured = true;
    		}

    		if(validateNormalField($_POST['activeEditCategory'], $activeCategoryEditLabel, $editMsg, "Yes or No must be selected", $dummy, $editActiveCategory) == false)
    		{
        		$errorHasOccured = true;
    		}

    		if($errorHasOccured == false)
    		{
        		$id = $_POST['categoryEditID'];
                $status = updateCategory($id, $editCategoryName, $editActiveCategory);
                if($status != 'OK')
                {
                    echo '
                    <script id="toastCall" type="text/javascript">
                        showSnackbar(`'.$status.'`, 9000);
                    </script>';
                }
                else
                {
                    $categoryEditValue = '';
                    echo '
			        <script id="toastCall" type="text/javascript">
				        showSnackbar("A category was edited successfully!", 5000);
			        </script>';
                }
    		}
            else
            {
                echo '
                <script id="toastCall" type="text/javascript">
                    showSnackbar("'.getToastErrorMessage("Category").'", 5000);
                </script>';
            }
	}
	
	if(isset($_POST['categoryDelEnter']))
	{
    		if(empty(trim($_POST['categoryDelID'])) == false)
    		{
        		$id = $_POST['categoryDelID'];
                $status = deleteRecordFromTable($id,"CATEGORY","categoryID");
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
				        showSnackbar("A category was deleted successfully!", 5000);
			        </script>';
                }
    		}
    		else
    		{
                $delMsg = "<span style=\"color:red\">Please choose a category to delete using the button above.</span>";
                echo '
                <script id="toastCall" type="text/javascript">
                    showSnackbar("'.getToastErrorMessage("Category").'", 5000);
                </script>';
    		}
	}


?>


<div class = "2u"></div>
<div class = "8u">
    <button type="button" class="collapsible">Category</button>
    <div class="content">
        <div class = "12u$">
		<div class = 'tabcontainer'>
			<div class = "tab">
				<button id = "categoryAddTab" class="tablinks active" onclick="changeTab(event, 'categoryAdd', false,'categoryAddTab')">Add</button>
                    		<button id = "categoryEditTab" class="tablinks" onclick="changeTab(event, 'categoryEdit', false, 'categoryEditTab')">Edit</button>
                    		<button id = "categoryDelTab" class="tablinks" onclick="changeTab(event, 'categoryDelete', false, 'categoryDelTab')">Delete</button>
			</div>
			<div id = "categoryAdd" class = "tabcontent">
				<button id="addCategoryTableButton" onclick="toggleTable('categoryTableModal', 'category-close')">View Current Categories</button>
				<p><?php echo $addMsg;?></p>
            			<form method="post" action="">
                			<strong> <?php echo $categoryAddLabel?> </strong>
                			<input type="text" name="category" id="category" value="<?php echo $categoryAddValue?>" placeholder="Computer Science"/>
                			<strong> <?php echo $activeCategoryAddLabel?> </strong><br>
                			<input type="radio" id="yesActiveCategory" name="activeCategory" value="Y" checked>
                			<label for="yesActiveCategory">Yes</label>
                			<input type="radio" id="noActiveCategory" name="activeCategory" value="N">
                			<label for="noActiveCategory">No</label><br>
                			<div class="box alt align-right"> 
                    				<ul class="actions">
                        				<input name="categoryAddEnter" class="btn special" type="submit" value="Submit" />
                    				</ul>
                			</div>
            			</form>
        		</div>
		
		<div id="categoryEdit" class="tabcontent">
                    <button id="editCategoryTableButton" onclick="toggleTable('categoryTableModal', 'category-close')">Select Category to Edit</button>
                    <p><?php echo $editMsg;?></p>
                    <form method="post" action="">
                        <strong><?php echo $categoryEditLabel?></strong>
                        <input type="text" name="categoryEditName" id="categoryEditName" value="<?php echo $categoryEditValue?>" placeholder="Category" />
                        <strong><?php echo $activeCategoryEditLabel?></strong><br>
                        <input type="radio" id="yesEditActiveCategory" name="activeEditCategory" value="Y" checked>
                        <label for="yesEditActiveCategory">Yes</label>
                        <input type="radio" id="noEditActiveCategory" name="activeEditCategory" value="N">
                        <label for="noEditActiveCategory">No</label><br>

                        <input type="hidden" name="categoryEditID" id="categoryEditID" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="categoryEditEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>


                <div id="categoryDelete" class="tabcontent">
                    <button id="delCategoryTableButton" onclick="toggleTable('categoryTableModal', 'category-close')">Select Category to Delete</button>
                    <p><?php echo $delMsg;?></p>
                    <form method="post" action="">
                        <strong>Category Name*</strong>
                        <input type="text" name="categoryDelName" id="categoryDelName" value="" placeholder="Category" disabled/>
                        <strong>Active Y/N*</strong><br>
                        <input type="radio" id="yesDelActiveCategory" name="activeDelCategory" value="Y" disabled>
                        <label for="yesDelActiveCategory">Yes</label>
                        <input type="radio" id="noDelActiveCategory" name="activeDelCategory" value="N" disabled>
                        <label for="noDelActiveCategory">No</label><br>

                        <input type="hidden" name="categoryDelID" id="categoryDelID" value="" />

                        <div class="box alt align-right">
                            <ul class="actions">
                                <input name="categoryDelEnter" class="btn special" type="submit" value="Submit" />
                            </ul>
                        </div>
                    </form>
                </div>
	</div>
    </div>
</div>
</div>

<script type="text/javascript" language="javascript" class="init">
    var categoryTable;
    $(document).ready(function() {
        categoryTable = $('#categoryTable').DataTable( {
                "columnDefs": [
                    {
                        "targets":[ 0 ],
                        "visible": false,
                        "searchable": false
                    }
                ]
            });

        $('#editCategoryTableButton').click(function() {
            $('#categoryTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = categoryTable.rows('.selected').data()[0];
                $('#categoryEditID').val(rowData[0]);
                $('#categoryEditName').val(rowData[1]);
                
                if (rowData[2] == 'Y') {
                    $('#yesEditActiveCategory').attr('checked', true);
                }
                else {
                    $('#noEditActiveCategory').attr('checked', true);
                }
                
                $(this).toggleClass('selected');

                var categoryTableModal = document.getElementById('categoryTableModal');
                categoryTableModal.style.display = "none";
            });
        });

        $('#delCategoryTableButton').click(function() {
            $('#categoryTable tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                var rowData = categoryTable.rows('.selected').data()[0];
                $('#categoryDelID').val(rowData[0]);
                $('#categoryDelName').val(rowData[1]);
                
                if (rowData[2] == 'Y') {
                    $('#yesDelActiveCategory').attr('checked', true);
                }
                else {
                    $('#noDelActiveCategory').attr('checked', true);
                }
                
                $(this).toggleClass('selected');

                var categoryTableModal = document.getElementById('categoryTableModal');
                categoryTableModal.style.display = "none";
            });
        });
    });
</script>

<div id="categoryTableModal" class="modal">
    <div class="modal-content">
        <span id="category-close" class="modal-close">&times;</span>
        <?php
        $rows = selectAll("CATEGORY");
        print '<table  id="categoryTable" class="display" cellspacing="0" width="100%">';
        print '<caption>Make a selection from the table below to fill in the form</caption>';
        print '<thead>
            <tr><th>categoryID</th><th>Category Name</th><th>Active</th></tr></thead><tfoot>';
        print '<tbody>';
        foreach ($rows as $row) {
            print '<tr>';
            print '<td>' . $row['categoryID'] . '<td>' . $row['categoryName'] . '</td><td>' . $row['active'] . '</td>';
            print '</tr>';
        }
        print '</tbody>';
        print '</table>';
        ?>
    </div>
</div>
<div class="2u$"></div>