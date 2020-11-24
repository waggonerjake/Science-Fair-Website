<?php
require_once "util.php";
require_once "selection.php";

function updateAdmin($id, $fn, $ln, $mn, $em, $level, $active)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE ADMINISTRATOR SET firstName = ?, lastName = ?, middleName = ?, email 
= ?, levelID = ?, active = ? WHERE adminID = ?");
	$stmt->bindValue(1, strtoupper($fn), PDO::PARAM_STR);
	$stmt->bindValue(2, strtoupper($ln), PDO::PARAM_STR);
	$stmt->bindValue(3, strtoupper($mn), PDO::PARAM_STR);
	$stmt->bindValue(4, strtoupper($em), PDO::PARAM_STR);
	$stmt->bindValue(5, strtoupper($level), PDO::PARAM_INT);
	$stmt->bindValue(6, strtoupper($active), PDO::PARAM_STR);
	$stmt->bindValue(7, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'Admin');
	}
	return 'OK';
}

function updateProjectGradeLevel($id, $projectGradeLevel, $active)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE PROJECT_GRADE_LEVEL SET levelName = ?, active = ? WHERE projectGradeID = ?");
	$stmt->bindValue(1, $projectGradeLevel, PDO::PARAM_STR);
	$stmt->bindValue(2, strtoupper($active), PDO::PARAM_STR);
	$stmt->bindValue(3, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'Grade Level');
	}
	return 'OK';
}

function updateCity($id, $city, $county)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE CITY SET cityName = ?, countyID = ? WHERE cityID = ?");
	$stmt->bindValue(1, $city, PDO::PARAM_STR);
	$stmt->bindValue(2, $county, PDO::PARAM_INT);
	$stmt->bindValue(3, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'City');
	}
	return 'OK';
}

//Student check for duplicate entires is done within the student form since it doesnt have any unique values
function updateStudent(
	$id,
	$firstName,
	$lastName,
	$middleName,
	$grade,
	$project,
	$gender,
	$county,
	$city,
	$schoolName
) {
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE STUDENT SET 
firstName=?,lastName=?,middleName=?,gradeID=?,genderID=?,schoolID=?,countyID=?,cityID=?,projectID=? 
WHERE studentID=?");
	$stmt->bindValue(1, strtoupper($firstName), PDO::PARAM_STR);
	$stmt->bindValue(2, strtoupper($lastName), PDO::PARAM_STR);
	$stmt->bindValue(3, strtoupper($middleName), PDO::PARAM_STR);
	$stmt->bindValue(4, $grade, PDO::PARAM_INT);
	$stmt->bindValue(5, $gender, PDO::PARAM_INT);
	$stmt->bindValue(6, $schoolName, PDO::PARAM_INT);
	$stmt->bindValue(7, $county, PDO::PARAM_INT);
	$stmt->bindValue(8, $city, PDO::PARAM_INT);
	$stmt->bindValue(9, $project, PDO::PARAM_INT);
	$stmt->bindValue(10, $id, PDO::PARAM_INT);
	$stmt->execute();
}

function updateSchool($id, $name, $county, $city)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE SCHOOL SET cityID = ?, countyID = ?, schoolName = ? WHERE schoolID = 
?");
	$stmt->bindValue(1, $city, PDO::PARAM_INT);
	$stmt->bindValue(2, $county, PDO::PARAM_INT);
	$stmt->bindValue(3, strtoupper($name), PDO::PARAM_STR);
	$stmt->bindValue(4, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		preg_match(
			'/.*Duplicate entry \'(.*?)\' for key \'U_([a-zA-Z-]*)\'.*/',
			$e->getMessage(),
			$output
		);
		$duplicateValue = strtolower($output[1]);
		$split = preg_split('/[-]/', $duplicateValue, null);
		$countyID = $split[0];
		$cityID = $split[2];
		$schoolRecord = checkRowWithVaryingFields('v_SCHOOL', ['countyID', 'cityID'], [
			$countyID,
			$cityID
		]);
		return "Error: A school already exists with the name '" . strtolower($name) . "' in city 
'" . strtolower($schoolRecord[0]['cityName']) . "' in 
'" . strtolower($schoolRecord[0]['countyName']) . "' county. Please
			choose a different school, city, and county combination.";
	}
	return 'OK';
}

function updateProject($id, $projectNum, $title, $abstract, $gradeLevel, $category, $boothNumber, $cnID)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE PROJECT SET projectNumber = ?, title = ?, abstract = ?, 
projectGradeID = ?, categoryID = ?, boothID = ?, cnID = ?, rank = -1 WHERE projectID = ?");
	$stmt->bindValue(1, $projectNum, PDO::PARAM_INT);
	$stmt->bindValue(2, strtoupper($title), PDO::PARAM_STR);
	$stmt->bindValue(3, strtoupper($abstract), PDO::PARAM_STR);
	$stmt->bindValue(4, $gradeLevel, PDO::PARAM_INT);
	$stmt->bindValue(5, $category, PDO::PARAM_INT);
	$stmt->bindValue(6, $boothNumber, PDO::PARAM_INT);
	$stmt->bindValue(7, strtoupper($cnID), PDO::PARAM_STR);
	$stmt->bindValue(8, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		if (strpos($e->getMessage(), 'CNID') || strpos($e->getMessage(), 'PROJECT-NUMBER')) {
			return updateErrorMsg($e->getMessage(), 'Project');
		} else //used for booth since its a unqiue foreign key
		{
			preg_match(
				'/.*Duplicate entry \'(.*?)\' for key \'U_([a-zA-Z-]*)\'.*/',
				$e->getMessage(),
				$output
			);
			$boothID = strtolower($output[1]);
			$boothNumber = getRowsByField('BOOTH_NUMBER', 'boothID', $boothID);
			return "Error: Another project is already using booth number 
'" . $boothNumber[0]['boothNumber'] . "' Please choose a different booth to assign to this 
project.";
		}
	}
	return 'OK';
}


function updateSession($id, $sessionStart, $sessionEnd, $active, $name)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE SESSION SET startTime = ?, endTime = ?, active = ?, name = ? WHERE 
sessionID = ?");
	$stmt->bindValue(1, strtoupper($sessionStart), PDO::PARAM_STR);
	$stmt->bindValue(2, strtoupper($sessionEnd), PDO::PARAM_STR);
	$stmt->bindValue(3, strtoupper($active), PDO::PARAM_STR);
	$stmt->bindValue(4, strtoupper($name), PDO::PARAM_STR);
	$stmt->bindValue(5, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'Session');
	}
	return 'OK';
}


function updateCategory($id, $catName, $active)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE CATEGORY SET categoryName = ?, active = ? WHERE categoryID = ?");
	$stmt->bindValue(1, strtoupper($catName), PDO::PARAM_STR);
	$stmt->bindValue(2, strtoupper($active), PDO::PARAM_STR);
	$stmt->bindValue(3, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'Category');
	}
	return 'OK';
}


function updateBooth($id, $boothNum, $active)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE BOOTH_NUMBER SET boothNumber = ?, active = ? WHERE boothID = ?");
	$stmt->bindValue(1, $boothNum, PDO::PARAM_INT);
	$stmt->bindValue(2, strtoupper($active), PDO::PARAM_STR);
	$stmt->bindValue(3, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'Booth');
	}
	return 'OK';
}

function updateGrade($id, $grade, $active)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE GRADE SET grade = ?, active = ? WHERE gradeID = ?");
	$stmt->bindValue(1, $grade, PDO::PARAM_INT);
	$stmt->bindValue(2, strtoupper($active), PDO::PARAM_STR);
	$stmt->bindValue(3, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'Grade');
	}
	return 'OK';
}

function updateCounty($id, $countyName)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE COUNTY SET countyName = ? WHERE countyID = ?");
	$stmt->bindValue(1, strtoupper($countyName), PDO::PARAM_STR);
	$stmt->bindValue(2, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'County');
	}
	return 'OK';
}

function updateAssignment($id, $judgeID, $sessionID)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE ASSIGNMENT SET judgeID = ?, sessionID = ? WHERE assignmentID = ?");
	$stmt->bindValue(1, $judgeID, PDO::PARAM_INT);
	$stmt->bindValue(2, $sessionID, PDO::PARAM_INT);
	$stmt->bindValue(3, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return $e->getMessage();
		//return updateErrorMsg($e->getMessage(), 'Assignment');
	}
	return 'OK';
}

function insertCheckin()
{
	include "dbconnect.php";
	$stmt = $con->prepare('UPDATE JUDGE SET checkin = "Y" where email = ? AND password = ?');
	$stmt->bindValue(1, strtoupper($_SESSION["username"]), PDO::PARAM_STR);
	$stmt->bindValue(2, $_SESSION["password"], PDO::PARAM_STR);
	$stmt->execute();
}

function insertCheckout()
{
	include "dbconnect.php";
	$stmt = $con->prepare('UPDATE JUDGE SET checkin = "N" where email = ? AND password = ?');
	$stmt->bindValue(1, strtoupper($_SESSION["username"]), PDO::PARAM_STR);
	$stmt->bindValue(2, $_SESSION["password"], PDO::PARAM_STR);
	$stmt->execute();
}

function updateScore($id, $sc)
{
	include "dbconnect.php";
	$stmt = $con->prepare('UPDATE ASSIGNMENT SET score = ? WHERE assignmentID = ?');
	$stmt->bindValue(1, $sc, PDO::PARAM_INT);
	$stmt->bindValue(2, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'Score');
	}
	return 'OK';
}

function updateJudgeCatPref($jid, $cid, $oldCat)
{
	include "dbconnect.php";
	$stmt = $con->prepare('UPDATE JUDGE_CATEGORY_PREFERENCE SET categoryID = ? WHERE judgeID = ? AND categoryID = ?');
	$stmt->bindValue(1, $cid, PDO::PARAM_INT);
	$stmt->bindValue(2, $jid, PDO::PARAM_INT);
	$stmt->bindValue(3, $oldCat, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'JudgeCategory');
	}
	return 'OK';
}

function updateJudgeGradePref($jid, $gid, $oldGrade)
{
	include "dbconnect.php";
	$stmt = $con->prepare('UPDATE JUDGE_GRADE_PREFERENCE SET projectGradeID = ? WHERE judgeID = ? AND projectGradeID = ?');
	$stmt->bindValue(1, $gid, PDO::PARAM_INT);
	$stmt->bindValue(2, $jid, PDO::PARAM_INT);
	$stmt->bindValue(3, $oldGrade, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'JudgeGrade');
	}
	return 'OK';
}

function updateJudge($id, $fn, $ln, $em, $t, $emp, $dg, $ci)
{
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE JUDGE SET firstName = ?, lastName = ?, title = ?, highestDegree = ?, employer = ?, email = ?, checkin = ? WHERE judgeID = ?");
	$stmt->bindValue(1, strtoupper($fn), PDO::PARAM_STR);
	$stmt->bindValue(2, strtoupper($ln), PDO::PARAM_STR);
	$stmt->bindValue(3, strtoupper($t), PDO::PARAM_STR);
	$stmt->bindValue(4, strtoupper($dg), PDO::PARAM_STR);
	$stmt->bindValue(5, strtoupper($emp), PDO::PARAM_STR);
	$stmt->bindValue(6, strtoupper($em), PDO::PARAM_STR);
	$stmt->bindValue(7, strtoupper($ci), PDO::PARAM_STR);
	$stmt->bindValue(8, $id, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'Judge');
	}
	return 'OK';
}

function updateProjectBooth($id, $boothID) {
	include "dbconnect.php";
	$stmt = $con->prepare("UPDATE PROJECT set boothID = ? WHERE projectID = ?");
	$stmt->bindValue(1, $id, PDO::PARAM_INT);
	$stmt->bindValue(2, $boothID, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch(PDOException $e) {
		return updateErrorMsg($e->getMessage(), 'Project');
	}
}
