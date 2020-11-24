<?php
    //proc is the procedure name, args are the arguments passed into the procedure (an Array). result is a boolean that asks if you respect a result back
    function callStoredProcedure($proc, $args, $result)
    {
        include "dbconnect.php";
        $numOfFields = sizeof($args);
        $params = '';

        for($i = 0; $i < $numOfFields; $i++)
        {
            if($numOfFields == 1 || $i == ($numOfFields - 1))
            {
                $params = $params.'?';
            }
            elseif($numOfFields > 1 && $i != $numOfFields - 1)
            {
                $params = $params.'?,';
            }
        }
        
        $query = "CALL ".$proc."(".$params.")";

        $stmt = $con->prepare($query);
        for($i=0; $i < $numOfFields; $i++)
        {
            $stmt->bindValue($i+1, $args[$i], PDO::PARAM_STR);
        }
        $stmt->execute();

        if($result == true)
        {
            $rows = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($rows, $row);
            }
            return $rows;
        }
    }
?>