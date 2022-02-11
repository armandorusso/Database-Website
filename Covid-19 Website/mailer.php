<?php
    include 'databaseConnection.php';
    include 'region.php';
    
    function sendEmail($medicalNumber, $subject, $messageBody, $guidelines, $checkRegionStates){
      
        $result = executeQuery('SELECT * FROM Person WHERE medicalNumber = '.$medicalNumber);
        $row = $result->fetch_assoc();
        
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $email = $row['email'];
        $postal = $row['postalCode'];
        
        $regionList = array();
        
        if($checkRegionStates){
          $regionList = getRegionsFromPostal($postal);
        }
        
        $regionName = null;
        $oldAlertLevel = null;
        $newAlertLevel = null;
        
        $regionListSize = sizeof($regionList);
        if($regionListSize >= 1){
          $regionName = $regionList[0]->name;
        
          if($regionListSize >= 2){
            $newAlertLevel = $regionList[0]->alertLevel;
            $oldAlertLevel = $regionList[1]->alertLevel;
          }
          else{
            $newAlertLevel = $regionList[0]->alertLevel;
          }
        }
        
        mail($email, $subject.' '.$guidelines, $messageBody);
        
        $messageElements = array();
        array_push($messageElements, $medicalNumber);
        array_push($messageElements, $email);
        array_push($messageElements, date('Y-m-d H:i:s'));
        array_push($messageElements, $regionName);
        array_push($messageElements, $guidelines);
        array_push($messageElements, $oldAlertLevel);
        array_push($messageElements, $newAlertLevel);
        
        insertQuery('Messages', $messageElements);
    }
?>