<?php
require_once "util.php";

$erroHasOccured = false;
$template = "(<a href=\"assets/studentProjectInfoTemplate.csv\" download>download template here</a>)";
$fileUploadLabel = "Select a csv file to upload fair information ".$template.":";

if(isset($_POST['uploadFile']))
{
    if(empty($_FILES['fileUpload']['name']) == false)
    {
        //Set some pre-check variables like where to put the file, its name,
        //and the extension
        $target_file = 'uploads/' . basename($_FILES["fileUpload"]["name"]);
        $filename = pathinfo($_FILES["fileUpload"]["name"]);
        $extension = $filename['extension'];

        if ($_FILES["fileUpload"]["size"] > 2000000) 
        {
            $fileUploadLabel = '<span style="color:red;">File must be less than 2MB '.$template.':</span>';
            $erroHasOccured = true;
        }
        if($extension != "csv" && $erroHasOccured == false) 
        {
            $fileUploadLabel = '<span style="color:red;">File must be a .csv '.$template.':</span>';
            $erroHasOccured = true;
        }
        if($erroHasOccured == false)
        {
            if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file))
            {
                $status = uploadCSV($target_file);
                if($status != 'OK')
                {
                    $fileUploadLabel = '<span style="color:red;">'.$status.'</span>';
                }
                else
                {
                    $fileUploadLabel = "The file ".$_FILES["fileUpload"]["name"]. " has been uploaded.";
                }
            } 
            else 
            {
                $fileUploadLabel = '<span style="color:red;">There was an error uploading your file '.$template.':</span>';
            }
        }
    }
    else
    {
        $fileUploadLabel = '<span style="color:red;">Please select a file to upload:</span>';
    }
}
?>

<div class="2u"></div>
<form method="post" action="" enctype="multipart/form-data">
    <div class="box alt align-left">
        <ul class="actions">
            <label for="fileUpload"><?php echo $fileUploadLabel?></label>
            <input type="file" name="fileUpload"><br>
            <input name="uploadFile" class="btn upload" type="submit" value="Upload CSV"/>
        </ul>
    </div>
</form>
<div class="10u$"></div>