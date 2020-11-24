<?php
require_once "dbconnect.php";
require_once "util.php";
include "selection.php";

//Student check for duplicate entires is done within the student form since it doesnt have any unique values
function insertStudent($firstName, $lastName, $middleName, $grade, $project, $gender, $county, $city, $school)
{
	include "dbconnect.php";
	if (empty(trim($middleName)) == false) {
		$stmt = $con->prepare("INSERT INTO STUDENT(firstName, lastName, middleName, gradeID, genderID, schoolID, countyID, cityID, projectID)
									VALUES(?,?,?,?,?,?,?,?,?)");
		$stmt->bindValue(1, strtoupper($firstName), PDO::PARAM_STR);
		$stmt->bindValue(2, strtoupper($lastName), PDO::PARAM_STR);
		$stmt->bindValue(3, strtoupper($middleName), PDO::PARAM_STR);
		$stmt->bindValue(4, $grade, PDO::PARAM_INT);
		$stmt->bindValue(5, $gender, PDO::PARAM_INT);
		$stmt->bindValue(6, $school, PDO::PARAM_INT);
		$stmt->bindValue(7, $county, PDO::PARAM_INT);
		$stmt->bindValue(8, $city, PDO::PARAM_INT);
		$stmt->bindValue(9, $project, PDO::PARAM_INT);
		$stmt->execute();
	} else {
		$stmt = $con->prepare("INSERT INTO STUDENT(firstName, lastName, gradeID, genderID, schoolID, countyID, cityID, projectID)
									VALUES(?,?,?,?,?,?,?,?)");
		$stmt->bindValue(1, strtoupper($firstName), PDO::PARAM_STR);
		$stmt->bindValue(2, strtoupper($lastName), PDO::PARAM_STR);
		$stmt->bindValue(3, $grade, PDO::PARAM_INT);
		$stmt->bindValue(4, $gender, PDO::PARAM_INT);
		$stmt->bindValue(5, $school, PDO::PARAM_INT);
		$stmt->bindValue(6, $county, PDO::PARAM_INT);
		$stmt->bindValue(7, $city, PDO::PARAM_INT);
		$stmt->bindValue(8, $project, PDO::PARAM_INT);
		$stmt->execute();
	}
}

function insertProject($projectNumber, $title, $abstract, $gradeID, $categoryID, $boothID, $cnID)
{
	include "dbconnect.php";
	$stmt = $con->prepare("INSERT INTO PROJECT VALUES(0,?,?,?,?,?,?,?,-1)");
	$stmt->bindValue(1, $projectNumber, PDO::PARAM_INT);
	$stmt->bindValue(2, strtoupper($title), PDO::PARAM_STR);
	$stmt->bindValue(3, strtoupper($abstract), PDO::PARAM_STR);
	$stmt->bindValue(4, $gradeID, PDO::PARAM_INT);
	$stmt->bindValue(5, $categoryID, PDO::PARAM_INT);
	$stmt->bindValue(6, $boothID, PDO::PARAM_INT);
	$stmt->bindValue(7, strtoupper($cnID), PDO::PARAM_STR);
	try
	{
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		if(strpos($e->getMessage(), 'CNID') || strpos($e->getMessage(), 'PROJECT-NUMBER'))
		{
			return insertionErrorMsg($e->getMessage(), 'Project');
		}
		else //used for booth since its a unqiue foreign key
		{
			preg_match('/.*Duplicate entry \'(.*?)\' for key \'U_([a-zA-Z-]*)\'.*/', $e->getMessage(), $output);
			$boothID = strtolower($output[1]);
			$boothNumber = getRowsByField('BOOTH_NUMBER', 'boothID', $boothID);
			return "Error: A project is already using booth number '".$boothNumber[0]['boothNumber']."' Please choose a different booth to assign to this project.";
		}
	}
	return 'OK';
}

function insertSchool($name, $county, $city)
{
	include "dbconnect.php";
	$stmt = $con->prepare("INSERT INTO SCHOOL VALUES (0, ?, ?, ?)");
	$stmt->bindValue(1, $county, PDO::PARAM_INT);
	$stmt->bindValue(2, strtoupper($name), PDO::PARAM_STR);
	$stmt->bindValue(3, $city, PDO::PARAM_INT);
	try
	{
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		preg_match('/.*Duplicate entry \'(.*?)\' for key \'U_([a-zA-Z-]*)\'.*/', $e->getMessage(), $output);
		$duplicateValue = strtolower($output[1]);
		$split = preg_split('/[-]/', $duplicateValue, null);
		$countyID = $split[0];
		$cityID = $split[2];
		$schoolRecord = checkRowWithVaryingFields('v_SCHOOL', ['countyID','cityID'], [$countyID, $cityID]);
		return "Error: A school already exists with the name '".strtolower($name)."' in city '".strtolower($schoolRecord[0]['cityName'])."' in '".strtolower($schoolRecord[0]['countyName'])."' county. Please
		choose a different school, city, and county combination.";
	}
	return 'OK';
}

function insertProjectGradeLevel($projectGradeLevel, $active)
{
	include "dbconnect.php";
	$stmt = $con->prepare("INSERT INTO PROJECT_GRADE_LEVEL VALUES (0, ?, ?)");
	$stmt->bindValue(1, $projectGradeLevel, PDO::PARAM_INT);
	$stmt->bindValue(2, $active, PDO::PARAM_STR);
	try
	{
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		return insertionErrorMsg($e->getMessage(), 'Grade Level');
	}
	return 'OK';
}

function insertCity($city, $county)
{
	include "dbconnect.php";
	$stmt = $con->prepare("INSERT INTO CITY VALUES (0, ?, ?)");
	$stmt->bindValue(1, strtoupper($city), PDO::PARAM_STR);
	$stmt->bindValue(2, $county, PDO::PARAM_INT);
	try
	{
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		return insertionErrorMsg($e->getMessage(), 'City');
	}
	return 'OK';
}

function insertSession($sessionStart, $sessionEnd, $activeSession, $name)
{
	include "dbconnect.php";
	$stmt = $con->prepare("INSERT INTO SESSION VALUES (0, ?, ?, ?, ?)");
	$stmt->bindValue(1, $sessionStart, PDO::PARAM_STR);
	$stmt->bindValue(2, $sessionEnd, PDO::PARAM_STR);
	$stmt->bindValue(3, $activeSession, PDO::PARAM_STR);
	$stmt->bindValue(4, strtoupper($name), PDO::PARAM_STR);
	try
	{
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		return insertionErrorMsg($e->getMessage(), 'Session');
	}
	return 'OK';
}

function insertCategory($categoryName, $activeCategory)
{
	include "dbconnect.php";
	$stmt = $con->prepare("INSERT INTO CATEGORY VALUES (0, ?, ?)");
	$stmt->bindValue(1, strtoupper($categoryName), PDO::PARAM_STR);
	$stmt->bindValue(2, $activeCategory, PDO::PARAM_STR);
	try
	{
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		return insertionErrorMsg($e->getMessage(), 'Category');
	}
	return 'OK';
}


function insertBooth($boothnum, $activeBooth)
{
	include "dbconnect.php";
	$stmt = $con->prepare("INSERT INTO BOOTH_NUMBER VALUES (0, ?, ?)");
	$stmt->bindValue(1, $boothnum, PDO::PARAM_INT);
	$stmt->bindValue(2, $activeBooth, PDO::PARAM_STR);
	try
	{
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		return insertionErrorMsg($e->getMessage(), 'Booth');
	}
	return 'OK';
}

function insertGrade($grade, $activeGrade)
{
	include "dbconnect.php";
	$stmt = $con->prepare("INSERT INTO GRADE VALUES (0, ?, ?)");
	$stmt->bindValue(1, $grade, PDO::PARAM_INT);
	$stmt->bindValue(2, $activeGrade, PDO::PARAM_STR);
	try
	{
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		return insertionErrorMsg($e->getMessage(), 'Grade');
	}
	return 'OK';
}

function insertCounty($countyName)
{
	include "dbconnect.php";
	$stmt = $con->prepare("INSERT INTO COUNTY VALUES (0, ?)");
	$stmt->bindValue(1, strtoupper($countyName), PDO::PARAM_STR);
	try
	{
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		return insertionErrorMsg($e->getMessage(), 'County');
	}
	return 'OK';
}

function insertJudge($fname, $lname, $mname, $tit, $highDeg, $emp, $em, $pass)
{
	include "dbconnect.php";
	if (empty(trim($mname) == false)) {
		$stmt = $con->prepare("INSERT INTO JUDGE(firstName, lastName, middleName, title, highestDegree, employer, email, password) 
									VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bindValue(1, strtoupper($fname), PDO::PARAM_STR);
		$stmt->bindValue(2, strtoupper($lname), PDO::PARAM_STR);
		$stmt->bindValue(3, strtoupper($mname), PDO::PARAM_STR);
		$stmt->bindValue(4, strtoupper($tit), PDO::PARAM_STR);
		$stmt->bindValue(5, strtoupper($highDeg), PDO::PARAM_STR);
		$stmt->bindValue(6, strtoupper($emp), PDO::PARAM_STR);
		$stmt->bindValue(7, strtoupper($em), PDO::PARAM_STR);
		$stmt->bindValue(8, $pass, PDO::PARAM_STR);
		if ($stmt->execute() == false) {
			return false;
		}
	} else {
		$stmt = $con->prepare("INSERT INTO JUDGE(firstName, lastName, title, highestDegree, employer, email, passsword) 
									VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bindValue(1, strtoupper($fname), PDO::PARAM_STR);
		$stmt->bindValue(2, strtoupper($lname), PDO::PARAM_STR);
		$stmt->bindValue(3, strtoupper($tit), PDO::PARAM_STR);
		$stmt->bindValue(4, strtoupper($highDeg), PDO::PARAM_STR);
		$stmt->bindValue(5, strtoupper($emp), PDO::PARAM_STR);
		$stmt->bindValue(6, strtoupper($em), PDO::PARAM_STR);
		$stmt->bindValue(7, $pass, PDO::PARAM_STR);
		if ($stmt->execute() == false) {
			return false;
		}
	}
	return 'OK';
}

function insertAdmin($fn, $ln, $mn, $em, $pass, $level, $active)
{
	include "dbconnect.php";
	if (empty(trim($mn)) == false) {
		$stmt = $con->prepare("INSERT INTO ADMINISTRATOR(firstName, lastName, middleName, email, password, levelID, active)
									VALUES(?,?,?,?,?,?,?)");
		$stmt->bindValue(1, strtoupper($fn), PDO::PARAM_STR);
		$stmt->bindValue(2, strtoupper($ln), PDO::PARAM_STR);
		$stmt->bindValue(3, strtoupper($mn), PDO::PARAM_STR);
		$stmt->bindValue(4, strtoupper($em), PDO::PARAM_STR);
		$stmt->bindValue(5, $pass, PDO::PARAM_STR);
		$stmt->bindValue(6, strtoupper($level), PDO::PARAM_INT);
		$stmt->bindValue(7, strtoupper($active), PDO::PARAM_STR);
		try
		{
			$stmt->execute();
		}
		catch(PDOException $e)
        {
			return insertionErrorMsg($e->getMessage(), 'Admin');
        }
	} else {
		$stmt = $con->prepare("INSERT INTO ADMINISTRATOR(firstName, lastName, email, password, levelID, active)
									VALUES(?,?,?,?,?,?)");
		$stmt->bindValue(1, strtoupper($fn), PDO::PARAM_STR);
		$stmt->bindValue(2, strtoupper($ln), PDO::PARAM_STR);
		$stmt->bindValue(3, strtoupper($em), PDO::PARAM_STR);
		$stmt->bindValue(4, $pass, PDO::PARAM_STR);
		$stmt->bindValue(5, strtoupper($level), PDO::PARAM_INT);
		$stmt->bindValue(6, strtoupper($active), PDO::PARAM_STR);
		try
		{
			$stmt->execute();
		}
		catch(PDOException $e)
        {
			return insertionErrorMsg($e->getMessage(), 'Admin');
        }
	}
	return 'OK';
}

function insertJudgeCatPref($email, $category1Pref, $category2Pref, $category3Pref)
{
	include "dbconnect.php";
	$rows = getRowsByField('JUDGE', 'email', strtoupper($email));
	$id = $rows[0]['judgeID']; //Get newly created judge's ID

	$stmt = $con->prepare('INSERT INTO JUDGE_CATEGORY_PREFERENCE VALUES (?, ?)');
	$stmt->bindValue(1, $id, PDO::PARAM_INT);
	$stmt->bindValue(2, $category1Pref, PDO::PARAM_INT);
	$stmt->execute();

	if (empty($category2Pref) == false) {
		$stmt->bindValue(2, $category2Pref, PDO::PARAM_INT);
		$stmt->execute();
	}

	if (empty($category3Pref) == false) {
		$stmt->bindValue(2, $category3Pref, PDO::PARAM_INT);
		$stmt->execute();
	}
	return 'OK';
}

function insertJudgeGradPref($email, $gradePref1, $gradePref2, $gradePref3)
{
	include "dbconnect.php";
	$rows = getRowsByField('JUDGE', 'email', strtoupper($email));
	$id = $rows[0]['judgeID'];

	$stmt = $con->prepare('INSERT INTO JUDGE_GRADE_PREFERENCE VALUES (?, ?)');
	$stmt->bindValue(1, $id, PDO::PARAM_INT);
	$stmt->bindValue(2, $gradePref1, PDO::PARAM_INT);
	$stmt->execute();

	if (empty($gradePref2) == false) {
		$stmt->bindValue(2, $gradePref2, PDO::PARAM_INT);
		$stmt->execute();
	}

	if (empty($gradePref3) == false) {
		$stmt->bindValue(2, $gradePref3, PDO::PARAM_INT);
		$stmt->execute();
	}
	return 'OK';
}
