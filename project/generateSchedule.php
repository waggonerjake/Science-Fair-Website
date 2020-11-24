<?php

class Assignment
{
    public $sessionID;
    public $judgeID;
    public $projectID;
}

$assignmentsPerSession;
$projectAssignmentIndex = 0;

function getSessions()
{
    include "dbconnect.php";
    $stmt = $con->prepare("SELECT * FROM SESSION ORDER BY startTime ASC");
    $stmt->execute();
    $sessions = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($sessions, $row);
    }
    return $sessions;
}

function getJudges()
{
    return selectAll("JUDGE");
}

function getProjects()
{
    return selectAll("PROJECT");
}

function assignSessions($sessions, $judges)
{
    $assignments = [];
    foreach ($sessions as $value) {
        // create assigments per session equal to the amount of judges
        $assignmentsPerSession = count($judges);
        for ($i = 0; $i < $assignmentsPerSession; $i++) {
            $assignment = new Assignment();
            $assignment->sessionID = $value['sessionID'];
            $assignmentJSON = json_encode($assignment);
            array_push($assignments, $assignmentJSON);
        }
    }
    return $assignments;
}

function assignProjects($assignments, $projects, $projectAssignmentIndex)
{
    $newAssignments = [];
    for ($i = 0; $i < count($assignments); $i++) {
        $item = json_decode($assignments[$i], true);
        $item['projectID'] = $projects[$projectAssignmentIndex]['projectID'];
        array_push($newAssignments, json_encode($item));
        $projectAssignmentIndex++;
        $projectAssignmentIndex = $projectAssignmentIndex % count($projects);
    }
    return $newAssignments;
}

function assignJudges($assignments, $judges, $projects, $specialJudge)
{

    // convert json to array
    $newAssignments = [];
    for ($i = 0; $i < count($assignments); $i++) {
        $curAssigment = json_decode($assignments[$i], true);
        array_push($newAssignments, $curAssigment);
    }
    for ($i = 0; $i < count($judges); $i++) {
        // find projects based on judge prefered categories and grade level
        $preferedProjectIds = findPreferedProjects($judges[$i], $projects);

        $assignedProjects = [];
        // find a project in each session to judge
        // go through the assignments
        $session = -1;
        for ($x = 0; $x < count($newAssignments); $x++) {
            $curAssigment = &$newAssignments[$x];
            if ($curAssigment['sessionID'] != $session) {
                // match a session with the prefered ids
                for ($y = 0; $y < count($preferedProjectIds); $y++) {
                    if ($curAssigment['projectID'] == $preferedProjectIds[$y] && $curAssigment['judgeID'] == null && !in_array($curAssigment['projectID'], $assignedProjects)) {
                        $curAssigment['judgeID'] = $judges[$i];
                        array_push($assignedProjects, $curAssigment['projectID']);
                        $y = count($preferedProjectIds);
                        $session = $curAssigment['sessionID'];
                    }
                }
            }
        }
    }

    $finalAssignments = [];
    for ($i = 0; $i < sizeof($newAssignments); $i++) {
        $curAssigment = &$newAssignments[$i];
        if ($curAssigment['judgeID'] == null) {
            $curAssigment['judgeID'] = $specialJudge;
        }

        $curAssigment = json_encode($newAssignments[$i]);
        array_push($finalAssignments, $curAssigment);
    }
    return $finalAssignments;
}

function findPreferedProjects($judge, $projects)
{
    $preferedProjectIDs = [];
    // go to database to find prefered catagoryID and gradelevelIDs
    $preferedCatagories = getPreferedCatagories($judge);
    $preferedGradeLevels = getPreferedGradeLevels($judge);

    for ($i = 0; $i < count($projects); $i++) {

        // check categories
        for ($x = 0; $x < count($preferedCatagories); $x++) {
            if ($projects[$i]['categoryID'] == $preferedCatagories[$x]['categoryID'] && !in_array($preferedCatagories[$x]['categoryID'], $preferedProjectIDs, true)) {
                array_push($preferedProjectIDs, $projects[$i]['projectID']);
            }
        }

        // check gradelevels
        for ($x = 0; $x < count($preferedGradeLevels); $x++) {
            if ($projects[$i]['projectGradeID'] == $preferedGradeLevels[$x]['projectGradeID'] && !in_array($preferedGradeLevels[$x]['projectGradeID'], $preferedGradeLevels, true) && !in_array($preferedGradeLevels[$x]['projectGradeID'], $preferedProjectIDs, true)) {
                array_push($preferedProjectIDs, $projects[$i]['projectID']);
            }
        }
    }

    return $preferedProjectIDs;
}

function getPreferedCatagories($judge)
{
    include "dbconnect.php";
    $preferedIds = [];
    $stmt = $con->prepare("SELECT `categoryID` FROM JUDGE_CATEGORY_PREFERENCE WHERE judgeID = ?");
    $stmt->bindValue(1, $judge['judgeID'], PDO::PARAM_STR);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($preferedIds, $row);
    }
    return $preferedIds;
}

function getPreferedGradeLevels($judge)
{
    include "dbconnect.php";
    $preferedIds = [];
    $stmt = $con->prepare("SELECT `projectGradeID` FROM JUDGE_GRADE_PREFERENCE WHERE judgeID = ?");
    $stmt->bindValue(1, $judge['judgeID'], PDO::PARAM_STR);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($preferedIds, $row);
    }
    return $preferedIds;
}

function insert($assignments) {
    include "dbconnect.php";
    $status; //Boolean
    for ($i = 0; $i < count($assignments); $i++) {
        $currentAssigment = json_decode($assignments[$i], true);
        $stmt = $con->prepare("INSERT INTO ASSIGNMENT(sessionID, judgeID, projectID, score, rank) VALUES(?,?,?,-1,-1)");
        $stmt->bindValue(1, $currentAssigment['sessionID'], PDO::PARAM_INT);
        $stmt->bindValue(2, $currentAssigment['judgeID']['judgeID'], PDO::PARAM_INT);
        $stmt->bindValue(3, $currentAssigment['projectID'], PDO::PARAM_INT);
        $status = $stmt->execute();
    }
    if($status)
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("The schedule was generated successfully!", 5000);
        </script>';
    }
    else
    {
        //May want to make this error message more descriptive
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("There was an error generating the schedule.", 5000);
        </script>';
    }

}


if (isset($_POST['scheduleGenEnter'])) {
    // get data from database
    $sessions = getSessions();
    $projects = getProjects();
    $judges = getJudges();

    $specialJudge;
    $regJudge = [];
    for ($i = 0; $i < count($judges); $i++) {
        if ($judges[$i]['firstName'] == 'SPECIAL') {
            $specialJudge = $judges[$i];
        }
        else {
            array_push($regJudge, $judges[$i]);
        }
    }

    // add assignment objects into array, setting sessionIDs
    $assignments = assignSessions($sessions, $regJudge);
    $assignments = assignProjects($assignments, $projects, $projectAssignmentIndex);
    $assignments = assignJudges($assignments, $regJudge, $projects, $specialJudge);

    //clear previously generated schedule
    include "deletion.php";
    clearTable('ASSIGNMENT');
    // insert into database
    insert($assignments);
}

?>