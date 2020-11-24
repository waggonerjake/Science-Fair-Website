<?php
    function deleteRecordFromTable($id, $table, $idName)
    {
        include "dbconnect.php";
        $stmt = $con->prepare("DELETE FROM ".$table." WHERE ".$idName." = ?");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        try
        {
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            if(strpos($e->getMessage(), 'a foreign key constraint fails')) //Handle delete conflicts
            {
                //TODO: Make this a toast message
                preg_match('/.*`jwaggon_db`\.`([a-zA-Z_]*)`.*/', $e->getMessage(), $output); //gets the table name of that the constraint fails in
                $objectToDelete = str_replace('_',' ', strtolower($table)); //Used to replace _ with spaces if they exist
                return "Error: Cannot delete this '".$objectToDelete."' because 1 or more '".strtolower($output[1])."' item(s) depends on it. It must be
                removed from there first before it can be deleted entirely.";
            }
            else
            {
                return $e->getMessage();
            }
        }
        return 'OK';
    }

    function deleteAPreference($id, $table, $idName, $prefID)
    {
        include "dbconnect.php";
        $stmt = $con->prepare("DELETE FROM ".$table." WHERE judgeID = ? and ".$idName." = ?");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->bindValue(2, $prefID, PDO::PARAM_INT);
        try
        {
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            if(strpos($e->getMessage(), 'a foreign key constraint fails')) //Handle delete conflicts
            {
                //TODO: Make this a toast message
                preg_match('/.*`jwaggon_db`\.`([a-zA-Z_]*)`.*/', $e->getMessage(), $output); //gets the table name of that the constraint fails in
                $objectToDelete = str_replace('_',' ', strtolower($table)); //Used to replace _ with spaces if they exist
                return "Error: Cannot delete this '".$objectToDelete."' because 1 or more '".strtolower($output[1])."' item(s) depends on it. It must be
                removed from there first before it can be deleted entirely.";
            }
            else
            {
                return $e->getMessage();
            }
        }
        return 'OK';
    }

    //Please use with caution...
    function clearTable($table)
    {
        include "dbconnect.php";
        $stmt = $con->prepare("DELETE FROM ".$table);
        try
        {
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
        return 'OK';
    }
?>