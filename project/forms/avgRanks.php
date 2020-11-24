<?php
require_once "dbconnect.php";
require_once "util.php";
require_once "selection.php";
require_once "proc.php";
?>

<div class="2u"></div>
<div class="8u">
	<button type="button" class="collapsible">Project Average Rankings</button>
	<div class="content">
		<div class="12u$">
			<div class='tabcontainer'>
				<div class="tab">
					<button id="projectViewTab" class="tablinks active" onclick="changeTab(event, 'projectView', false,'projectViewTab')">View</button>
				</div>
				<div id="projectView" class="tabcontent">
					<button id="viewProjectTableButton" onclick="toggleTable('projectRankingsTableModal', 'project-view-close')">View Average Project Rankings</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" language="javascript" class="init">
	var avgProjectTable;
	$(document).ready(function() {
		avgProjectTable = $('#projectRankingsTable').DataTable({
			"columnDefs": [{
				"targets": [0,7,8,9],
				"visible": false,
				"searchable": false
			}]
		});
	});
</script>

<div id="projectRankingsTableModal" class="modal">
	<div class="modal-content">
		<span id="project-view-close" class="modal-close">&times;</span>
		<?php
		
		$rows = selectAll("v_PROJECT");

		print '<table  id="projectRankingsTable" class="display" cellspacing="0" width="100%">';
		print '<caption>Average rankings are only available once every project has been scored</caption>';
		print '<thead>
            <tr><th>projectID</th><th>Rank</th><th>Project Number</th><th>Title</th><th>Grade</th><th>Category</th><th>Booth Number</th><th>projectGradeID</th><th>categoryID</th><th>boothID</th></tr></thead><tfoot>';
		print '<tbody>';
		foreach ($rows as $row) {
            $rank = $row['rank'];
            $rank = ($rank == -1) ? 'Not enough scores yet' : $rank;
			print '<tr>';
			print '<td>' . $row['projectID'] . '</td><td>' . $rank . '</td><td>' . $row['projectNumber'] . '</td><td>' . $row['title'] . '</td><td>' . $row['levelName'] . '</td><td>' . $row['categoryName'] . '</td><td>' . $row['boothNumber'] . '</td><td>' . $row['projectGradeID'] . '</td><td>' . $row['categoryID'] . '</td><td>' . $row['boothID'] . '</td>';
			print '</tr>';
		}
		print '</tbody>';
		print '</table>';
		?>
	</div>
</div>

<div class="2u$"></div>