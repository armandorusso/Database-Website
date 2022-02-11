<?php
    function executeQuery($query){
        $servername = "bec353.encs.concordia.ca";
        $username = "bec353_4";
        $password = "r4yuK28q";
        $conn = new mysqli($servername, $username, $password, "bec353_4");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn -> query($query);

        $conn->close();
        return $result;
    }
    
    function insertQuery($tableName, $elementList){
        if(sizeof($elementList) == 0){
            echo "Array size is 0";
            return false;
        }
        
        $servername = "bec353.encs.concordia.ca";
        $username = "bec353_4";
        $password = "r4yuK28q";
        $conn = new mysqli($servername, $username, $password, "bec353_4");
  
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $query = 'INSERT INTO '.$tableName.' VALUES (';
        for($i = 0; $i < sizeof($elementList); $i++){
          if(is_string($elementList[$i])){
            $query = $query.'"'.$elementList[$i].'"';
          }
          else if(is_null($elementList[$i])){
            $query = $query.'null';
          }
          else if(is_bool($elementList[$i])){
            if($elementList[$i]){
              $query = $query.'true';
            }
            else{
              $query = $query.'false';
            }
          }
          else{
            $query = $query.$elementList[$i];
          }
          
          if($i < sizeof($elementList) - 1){
            $query = $query.', ';
          }
        }
        $query = $query.')';
        
        $result = $conn -> query($query);
        $conn->close();
        return $result;
    }
?>