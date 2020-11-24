<?php
    //Add other selection functions here
    function selectAll($table) {
        include "dbconnect.php";
        $stmt = $con->prepare("SELECT * FROM ".$table);
        $stmt->execute();

        $rows = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($rows, $row);
        }
        return $rows;
    }

    //Used to call the getRowsByField function from ajax
    if(isset($_GET['select']))
    {
        getRowsByField($_GET['table'], $_GET['field'], $_GET['value']);
    }

    function getRowsByField($table, $field, $value)
    {
        include "dbconnect.php";
        $stmt = $con->prepare("SELECT * FROM ".$table." WHERE ".$field." like ?");
        $stmt->bindValue(1, $value, PDO::PARAM_STR);
        $stmt->execute();

        $rows = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($rows, $row);
        }
        
        //Ajax calls need print
        if(isset($_GET['select']))
        {
            echo json_encode($rows);
        }
        else
        {
            return $rows;
        }
    }

    //Fields and Values are array's that correspond to each other by index
    //e.g. Fields=('countyName','cityName'); Values=('Marion','Indianapolis'); This
    //means our query will have WHERE countyName like Marion AND cityName like Indianapolis
    function checkRowWithVaryingFields($table, $fields, $values)
    {
        include "dbconnect.php";
        $numOfFields = sizeof($fields);
        $query = "SELECT * FROM ".$table." WHERE ";

        for($i = 0; $i < $numOfFields; $i++)
        {
            $clause = '';
            if($numOfFields == 1 || $i == ($numOfFields - 1))
            {
                $clause = $fields[$i]." like ?";
            }
            elseif($numOfFields > 1 && $i != $numOfFields - 1)
            {
                $clause = $fields[$i]." like ? AND ";
            }
            $query = $query.$clause;
        }

        $stmt = $con->prepare($query);
        for($i=0; $i < $numOfFields; $i++)
        {
            $stmt->bindValue($i+1, $values[$i], PDO::PARAM_STR);
        }
        $stmt->execute();

        $rows = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($rows, $row);
        }
        return $rows;
    }
?>