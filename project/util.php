<?php

//This function checks to see if the password follows our rules (at least 1 digit, 1 character, and 10+ long)
function validatePassword($password, $confirmPassword, &$msg)
{
    $validPassword = true;
    if(strpos($msg, 'Password') == false)
    {
        //Remove all non-digits then checks if its empty
        $tempPassword = $password;
        $tempConfirm = $confirmPassword;
        $tempPassword = preg_replace('/\D*/','',$tempPassword);
        $tempConfirm = preg_replace('/\D*/','',$tempConfirm);
        
        if(empty($tempPassword) || empty($tempConfirm))
        {
            if(empty($msg))
            {
                $msg = 'Things to fix: Passwords must contain at least 1 digit';
            }
            else $msg = $msg.", Passwords must contain at least 1 digit";
            $validPassword = false;
        }

        //Remove all digits then checks if its empty
        $tempPassword = $password;
        $tempConfirm = $confirmPassword;
        $tempPassword = preg_replace('/\d*/','',$tempPassword);
        $tempConfirm = preg_replace('/\d*/','',$tempConfirm);

        if(empty($tempPassword) || empty($tempConfirm))
        {
            if(empty($msg))
            {
                $msg = 'Things to fix: Passwords must contain at least 1 letter';
            }
            else $msg = $msg.", Passwords must contain at least 1 letter";
            $validPassword = false;
        }

        if(strlen($confirmPassword) < 10 || strlen($password) < 10 )
        {
            if(empty($msg))
            {
                $msg = 'Things to fix: Passwords must be at least 10 characters long';
            }
            else $msg = $msg.", Passwords must be at least 10 characters long";
            $validPassword = false;
        }
    }
    return $validPassword;
} 

// Generate random code (aka password) with length $length
function generateCode($length)
{
    $code = '';
   for($i = 0; $i<$length; $i++)
   {
       //generate a random number between 1 and 35
       $rng = mt_rand(1,35);
       //if the number is greater than 26, minus 26 will generate a digit between 0 and 9
       if ($rng > 26) 
       {
          $rng = $rng - 26;
          $code = $code.$rng;
      }
       else 
       {  
           //Generates ascii value, A = 65, so if its 1 add 64 to get A
           $code = $code.chr($rng + 64);
       }
   }
   return $code;
}

// all the required fields have this same validation, if its empty
// then it'll turn red and get added to the error message
function validateNormalField($formEntry, &$label, &$msg, $errorMessage, &$value, &$result)
{
    $validField = false;
    if(empty(trim($formEntry)))
    {
        $label = '<span style="color:red">'.$label.'</span>';
        if(empty($msg))
        {
            $msg = 'Things to fix: '.$errorMessage;
        }
        else $msg = $msg.", ".$errorMessage;
        $validField = false;
    }
    else 
    {
        $result = trim($formEntry);
        $value = $result;
        $validField = true;
    }
    return $validField;
}

//Used to call the drop down function from ajax
if(isset($_GET['id']))
{
    fillDropDown($_GET['id'], $_GET['field'], $_GET['table'], $_GET['placeholder']);
}

function fillDropDown($id, $field, $table, $placeholder)
{
    include "dbconnect.php";
    echo "<option value=\"\">- ".$placeholder." -</option>";
    $stmt = $con->prepare("SELECT ".$id.", ".$field." FROM ".$table);
	$stmt->execute();
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
		echo "<option value=\"".$row->$id."\">".$row->$field."</option>";
	}
}

if(isset($_GET['unassignedBoothID'])) {
    include "dbconnect.php";
    $placeholder = $_GET['placeholder'];
    $id = 'boothID';
    $field = 'boothNumber';
    echo "<option value=\"\">- ".$placeholder." -</option>";
    $stmt = $con->prepare("SELECT * FROM `BOOTH_NUMBER` WHERE boothID not in (select boothID from PROJECT)");
	$stmt->execute();
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
		echo "<option value=\"".$row->$id."\">".$row->$field."</option>";
	}
}

function getToastErrorMessage($field)
{
    return "Your ".$field." submission has an error. Please check it for more details.";
}

function sendMail($email,$password)
{
    $headers = "From: noreply@sefi.org\r\n";
    $headers .= "Content-type: text/html\r\n"; //need this header for html formatting
    //Use html and css in the email body
    $body ="
    <html>
    <head>
    <style>

    #activate {
        background-attachment: fixed;
        background-position: center top;
        background-size: cover;
        line-height: 1.75;
        text-align: center;
    }

    #activate p {
        color: black;
        font-size: 1.5em;
        margin-bottom: 1.75em;
        text-transform: uppercase;
    }

    </style>
    </head>
        <body>
            <span style=\"opacity: 0\">-</span>
            <section id=\"activate\">
                <p>Thank you for registering as a judge for SEFI!</p>
                <p>Below is your credentials to login</p>
                <ul style=\"list-style-type: none;\">
                    <li>Username: {$email}</li>
                    <li>Password: {$password}</li>
                </ul>
                <p>Click the link below to login to your account</p>
                <a href=\"http://corsair.cs.iupui.edu:24591/server_side_project/project/login.php\">Go to Login Page</a>
            </section>
            <span style=\"opacity: 0\">-</span>
        </body>
    </html>";
    // send email
    mail($email, 'Account registration', $body, $headers);
}

function insertionErrorMsg($msg, $entity)
{
    if(strpos($msg, 'Duplicate entry'))
    {
        //TODO: Make this a toast message
        preg_match('/.*Duplicate entry \'(.*?)\' for key \'U_([a-zA-Z-]*)\'.*/', $msg, $output); //gets the value and field with duplicate entry
        $duplicateField = ((strpos($output[2], '-')) ? str_replace('-',' ', strtolower($output[2])) : strtolower($output[2])); //Used to replace - with spaces if they exist in the field
        $duplicateValue = strtolower($output[1]);
        return "Error: Cannot insert '".$duplicateValue."' because another '".$entity."' already has '".$duplicateValue. "' as their '".$duplicateField."'. Please
        choose a different '".$duplicateField."' to use for this '".$entity."'.";
    }
    else
    {
        return $msg;
    }
}

//Similar to insetionErrorMsg, however it has a slightly different error message
function updateErrorMsg($msg, $entity)
{
    if(strpos($msg, 'Duplicate entry'))
    {
        //TODO: Make this a toast message
        preg_match('/.*Duplicate entry \'(.*?)\' for key \'U_([a-zA-Z-]*)\'.*/', $msg, $output);
        $duplicateField = ((strpos($output[2], '-')) ? str_replace('-',' ', strtolower($output[2])) : strtolower($output[2]));
        $duplicateValue = strtolower($output[1]);
        return "Error: Cannot edit '".$entity."' because another '".$entity."' already has '".$duplicateValue. "' as their '".$duplicateField."'. Please
        choose a different '".$duplicateField."' to use for this '".$entity."'.";
    }
    else
    {
        return $msg;
    }
}

//************************************************************
//Parsing is based off of the following template:
//Student First Name,Student Middle Name,Student Last Name,School Name,City Name,County Name,Grade,Gender,Project Number,Title,Abstract,Grade Level,Category,CN ID,Booth Number
//************************************************************
function uploadCSV($pathToFile)
{
    //include 'selection.php';
    //include 'insertion.php';

    $fileStream = fopen($pathToFile, 'r');
    $line = fgets($fileStream); //Skip first line (Header)

    //List of things to insert
    $booths = array();
    $categories = array();
    $gradeLevels = array();
    $counties = array();
    $grades = array();

    class City //Object that will be JSON data formatted as {name: city, county: county}
    {
        public $name;
        public $county;
    }
    $cities = array(); //Array will hold city objects

    class School //Object that will be JSON data formatted as {name: school name, county: county, city: city}
    {
        public $name;
        public $county;
        public $city;
    }
    $schools = array();

    class Project //Object that will be JSON data formatted as {number: project number, title: title, abstract: abstract, grade: grade, category: category, booth: booth, cnID: cnID}
    {
        public $number;
        public $title;
        public $abstract;
        public $grade;
        public $category;
        public $booth;
        public $cnID;
    }
    $projects = array();

    class Student //Object that will be JSON data formatted as {first: first name, middle: middle name, last: last name, grade: grade, gender: gender, school: school, county: county, city: city, project: project}
    {
        public $first;
        public $middle;
        public $last;
        public $grade;
        public $gender;
        public $school;
        public $county;
        public $city;
        public $project;
    }
    $students = array();
    
    
    //Counter is used for keeping track of errors and what iteration we're on
    $counter = 0;
    //Execution Order: Booth -> Category -> Grade Level -> County -> Grade -> City -> School -> Project -> Student
    while($line = fgets($fileStream)) //Keep reading lines of the file
    {
        //Used for comments in CSV (only needed for if they dont remove the example data from the template)
        if($line[0] == '#')
        {
            continue;
        }

        $split = preg_split('/[,]/', $line, null); //Split csv by commas into an array

        //Booth
        $boothNum = trim($split[14]);
        if(is_numeric($boothNum) == false)
        {
            return "Row ".($counter+ 1)."'s booth number is not a number. Please check <strong>".basename($pathToFile)."</strong> to make sure everything is correct.";
        }
        if(sizeof(getRowsByField('BOOTH_NUMBER','boothNumber',$boothNum)) == 0 && in_array($boothNum, $booths) == false)//If it doesnt exist in the table and we havent seen it already
        {
            array_push($booths, $boothNum);
        }

        //Category
        $categoryName = trim($split[12]);
        if(sizeof(getRowsByField('CATEGORY','categoryName',$categoryName)) == 0 && in_array($categoryName, $categories) == false)
        {
            array_push($categories, $categoryName);
        }

        //Grade Level
        $projectGradeLevel = trim($split[11]);
        if(sizeof(getRowsByField('PROJECT_GRADE_LEVEL','levelName',$projectGradeLevel)) == 0 && in_array($projectGradeLevel, $gradeLevels) == false)
        {
            array_push($gradeLevels, $projectGradeLevel);
        }

        //County
        $county = trim($split[5]);
        if(sizeof(getRowsByField('COUNTY','countyName',$county)) == 0 && in_array($county, $counties) == false)
        {
            array_push($counties, $county);
        }

        //Grade
        $grade = trim($split[6]);
        if(is_numeric($grade) == false)
        {
            return "Row ".($counter+1)."'s grade is not a number. Please check <strong>".basename($pathToFile)."</strong> to make sure everything is correct.";
        }
        if(sizeof(getRowsByField('GRADE','grade',$grade)) == 0 && in_array($grade, $grades) == false)
        {
            array_push($grades, $grade);
        }

        //City
        //A city must have a county, so use the one we are submitting
        $city = trim($split[4]);
        if(sizeof(getRowsByField('CITY','cityName',$city)) == 0)
        {
            $cityObj = new City();
            $cityObj->name = $city;
            $cityObj->county = $county;
            $citiesJSON = json_encode($cityObj);
            if(in_array($citiesJSON, $cities) == false) //Only add the JSON if we havent added it before
            {
                array_push($cities, $citiesJSON);
            }
        }

        //School
        //A school must have a city and county
        $school = trim($split[3]);
        if(sizeof(checkRowWithVaryingFields('v_SCHOOL', ['cityName','countyName'], [$city, $county])) == 0)
        {
            $schoolObj = new School();
            $schoolObj->name = $school;
            $schoolObj->county = $county;
            $schoolObj->city = $city;
            $schoolJSON = json_encode($schoolObj);
            if(in_array($schoolJSON, $schools) == false)
            {
                array_push($schools, $schoolJSON);
            }
        }

        //Project
        //A project relies on booth, category, and project grade
        //Needs extra validation because a booth and cnID must be globally unqiue per project
        $projectNumber = trim($split[8]);
        $title = trim($split[9]);
        $abstract = trim($split[10]);
        $cnID = trim($split[13]);

        if(is_numeric($projectNumber) == false)
        {
            return "Row ".($counter+1)."'s project number is not a number. Please check <strong>".basename($pathToFile)."</strong> to make sure everything is correct.";
        }
        if(sizeof(getRowsByField('v_PROJECT','boothNumber',$boothNum)) != 0)
        {
            return "Row ".($counter+1)."'s booth number is already being used by another project. Please check <strong>".basename($pathToFile)."</strong> to make sure everything is correct.";
        }
        if(sizeof(getRowsByField('PROJECT','cnID',$cnID)) != 0)
        {
            return "Row ".($counter+1)."'s cnID is is already being used by another project. Please check <strong>".basename($pathToFile)."</strong> to make sure everything is correct.";
        }
        if(sizeof(getRowsByField('PROJECT','projectNumber',$projectNumber)) == 0)
        {
            $projObj = new Project();
            $projObj->number = $projectNumber;
            $projObj->title = $title;
            $projObj->abstract = $abstract;
            $projObj->grade = $projectGradeLevel;
            $projObj->category = $categoryName;
            $projObj->booth = $boothNum;
            $projObj->cnID = $cnID;
            $projJSON = json_encode($projObj);
            if(in_array($projJSON, $projects) == false)
            {
                //This loop goes through all the projects we have in our current list
                //and compares the booths and cnIDs to the one currently being parsed
                for($i = 0; $i < sizeof($projects); $i++)
                {
                    $current_project = json_decode($projects[$i],true);
                    if($current_project['booth'] == $boothNum)
                    {
                        return "Row ".($counter+1)."'s booth number is already being used by another project. Please check <strong>".basename($pathToFile)."</strong> to make sure everything is correct.";
                    }
                    if($current_project['cnID'] == $cnID)
                    {
                        return "Row ".($counter+1)."'s cnID is is already being used by another project. Please check <strong>".basename($pathToFile)."</strong> to make sure everything is correct.";
                    }
                }
                array_push($projects, $projJSON);
            }
        }

        //Student
        //A student relies on grade, school, county, city, and project
        $firstname = trim($split[0]);
        $middlename = trim($split[1]);
        $lastname = trim($split[2]);
        $gender = trim($split[7]);

        if(strtoupper($gender) != "MALE" && strtoupper($gender) != "FEMALE" && strtoupper($gender) != "OTHER" && strtoupper($gender) != "PREFER NOT TO ANSWER")
        {
            return "Row ".($counter+1)."'s student gender must be 1 of the following: 
            <ul>
                <li>*Male</li><br>
                <li>*Female</li><br>
                <li>*Other</li><br>
                <li>*Prfer Not To Answer</li>
            </ul>
            Please check <strong>".basename($pathToFile)."</strong> to make sure everything is correct.";
        }
        //This if statement essentially checks the entire record of the student table since nothing is unique about a student
        if(sizeof(checkRowWithVaryingFields('v_STUDENT', ['firstName','lastName', 'middleName','grade','genderName','countyName','cityName','schoolName','projectNumber'], [$firstname, $lastname, $middlename, $grade, $gender, $county, $city, $school, $projectNumber])) == 0)
        {
            $studentObj = new Student();
            $studentObj->first = $firstname;
            $studentObj->last = $lastname;
            $studentObj->middle = $middlename;
            $studentObj->grade = $grade;
            $studentObj->gender = $gender;
            $studentObj->county = $county;
            $studentObj->city = $city;
            $studentObj->school = $school;
            $studentObj->project = $projectNumber;
            $studentJSON = json_encode($studentObj);
            if(in_array($studentJSON, $students) == false)
            {
                array_push($students, $studentJSON);
            }
        }
        $counter++;
    }

    //Call the insertion functions with the execution order in mind

    //Insert Booths
    for($i = 0; $i < sizeof($booths); $i++)
    {
        insertBooth($booths[$i], 'Y'); //Since we're assigning the booth we assume its active; everything else follows some logic
    }

    //Insert Categories
    for($i = 0; $i < sizeof($categories); $i++)
    {
        insertCategory($categories[$i], 'Y');
    }

    //Insert Grade Levels
    for($i = 0; $i < sizeof($gradeLevels); $i++)
    {
        insertProjectGradeLevel($gradeLevels[$i], 'Y');
    }

    //Insert Counties
    for($i = 0; $i < sizeof($counties); $i++)
    {
        insertCounty($counties[$i]);
    }

    //Insert Grades
    for($i = 0; $i < sizeof($grades); $i++)
    {
        insertGrade($grades[$i], 'Y');
    }

    //Insert Cities
    for($i = 0; $i < sizeof($cities); $i++)
    {
        $current_city = json_decode($cities[$i],true);
        $countyID = getRowsByField('COUNTY','countyName',$current_city['county'])[0]['countyID']; //Only returns 1 row, index 0, and we want countyID
        insertCity($current_city['name'],$countyID);
    }

    //Insert Schools
    for($i = 0; $i < sizeof($schools); $i++)
    {
        $current_school = json_decode($schools[$i],true);
        $countyID = getRowsByField('COUNTY','countyName',$current_school['county'])[0]['countyID'];
        $cityID = getRowsByField('CITY','cityName',$current_school['city'])[0]['cityID'];
        insertSchool($current_school['name'], $countyID, $cityID);
    }

    //Insert Project
    for($i = 0; $i < sizeof($projects); $i++)
    {
        $current_project = json_decode($projects[$i],true);
        $gradeLevelID = getRowsByField('PROJECT_GRADE_LEVEL','levelName',$current_project['grade'])[0]['projectGradeID'];
        $categoryID = getRowsByField('CATEGORY','categoryName',$current_project['category'])[0]['categoryID'];
        $boothID = getRowsByField('BOOTH_NUMBER','boothNumber',$current_project['booth'])[0]['boothID'];
        insertProject($current_project['number'], $current_project['title'], $current_project['abstract'], $gradeLevelID, $categoryID, $boothID, $current_project['cnID']);
    }

    //Insert Student
    for($i = 0; $i < sizeof($students); $i++)
    {
        $current_student = json_decode($students[$i],true);
        $gradeID = getRowsByField('GRADE','grade',$current_student['grade'])[0]['gradeID'];
        $genderID = getRowsByField('GENDER','genderName',$current_student['gender'])[0]['genderID'];
        $schoolID = getRowsByField('SCHOOL','schoolName',$current_student['school'])[0]['schoolID'];
        $countyID = getRowsByField('COUNTY','countyName',$current_student['county'])[0]['countyID'];
        $cityID = getRowsByField('CITY','cityName',$current_student['city'])[0]['cityID'];
        $projectID = getRowsByField('PROJECT','projectNumber',$current_student['project'])[0]['projectID'];
        insertStudent($current_student['first'], $current_student['last'], $current_student['middle'], $gradeID, $projectID, $genderID, $countyID, $cityID, $schoolID);
    }
    return 'OK';
}

?>